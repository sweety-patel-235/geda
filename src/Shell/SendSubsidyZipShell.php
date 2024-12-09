<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use App\Controller\AppController;
use Cake\Console\Shell;
use Cake\Network\Email\Email;
use Dompdf\Dompdf;
use Cake\Core\Configure;
use Cake\View\View;

class SendSubsidyZipShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Installers');
        $this->loadModel('Projects');
        $this->loadModel('ApplyOnlines');
        $this->loadModel('Subsidy');
        $this->loadModel('SubsidyRequest');
        $this->loadModel('SubsidyRequestApplication');
        $this->loadModel('Emaillog');
        $this->loadModel('CronApiProcess');
    }

    /**
     * now
     * Behaviour : Public
     * @return : date
     * @defination : Method is get the current date and time
     */
    public function NOW()
    {
        return date("Y-m-d H:i:s");
    }

    public function main()
    {
    	ini_set("memory_limit","-1");

        echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";

        $LastRowID              = $this->CronApiProcess->GetLastRowID("send_subsidyzip_cron");

        $Conditions 			= array('SubsidyRequest.processed'=>0,
        							'apply_onlines.pcr_submited IS NOT NULL',
        							'subsidy_claim_request_applications.received_at'=>'1',
        							'subsidy_claim_request_applications.processed'=>'0');
        if (!empty($LastRowID)) {
        	$Conditions['SubsidyRequest.id > '] = $LastRowID;
        }
        $SubsidyRequests 		= $this->SubsidyRequest->find('all',
										array( 	'fields'=> ['SubsidyRequest.id','SubsidyRequest.request_date'],
												'join'=>[
	                                                        [   'table'=>'subsidy_claim_request_applications',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.request_id = SubsidyRequest.id'
	                                                        ],
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->distinct(['SubsidyRequest.id'])->toArray();
		if (empty($SubsidyRequests))
		{
			$Conditions 		= array('SubsidyRequest.processed'=>0,
	        							'apply_onlines.pcr_submited IS NOT NULL',
	        							'subsidy_claim_request_applications.received_at'=>'1',
        								'subsidy_claim_request_applications.processed'=>'0');
			$SubsidyRequests 	= $this->SubsidyRequest->find('all',
										array( 	'fields'=> ['SubsidyRequest.id','SubsidyRequest.request_date'],
												'join'=>[
	                                                        [   'table'=>'subsidy_claim_request_applications',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.request_id = SubsidyRequest.id'
	                                                        ],
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'subsidy_claim_request_applications.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->distinct(['SubsidyRequest.id'])->toArray();
		}
		$DoProcess			= true;
		$SendEMail			= true;
		$processed 			= 1;
		if (!empty($SubsidyRequests))
		{
			foreach($SubsidyRequests as $SubsidyRequest)
			{
				$Conditions 		= array('processed'=>0,'request_id'=>$SubsidyRequest->id,'apply_onlines.pcr_submited IS NOT NULL','received_at'=>'1');
				echo "\r\n--application conditions::".print_r($Conditions)."--\r\n";
				$Request_Date 		= $SubsidyRequest->request_date;
				$ListOfApplications	= $this->SubsidyRequestApplication->find('all',
										array( 	'join'=>[
	                                                        [   'table'=>'apply_onlines',
	                                                            'type'=>'left',
	                                                            'conditions'=>'SubsidyRequestApplication.application_id = apply_onlines.id'
	                                                        ]
	                                                    ],
												'conditions'=>$Conditions))->toArray();
				foreach($ListOfApplications as $ListOfApplication)
				{
					// $application_ids 	= array();
					// array_push($application_ids,$ListOfApplication->application_id);
					echo "\r\n--Application ID:: ".$ListOfApplication->application_id."--\r\n";
					if ($DoProcess)
					{
						$ApplyOnline 	= $this->ApplyOnlines->viewApplication($ListOfApplication->application_id);
						// $ArrDocuments 	= $this->Subsidy->GetApplicationDocuments($ListOfApplication->application_id);
						$documentArr 	= array();

						$SubsidySummarySheet 	= "";
						$SubsidySummarySheet 	= $this->SubsidyRequest->generateSubsidySummarySheet($ListOfApplication->application_id,true);

						echo "\r\n--SubsidySummarySheet:: ".$SubsidySummarySheet."--\r\n";

						// foreach ($ArrDocuments as $document) {
						// 	if (!empty($document) && basename($document) != "") array_push($documentArr, $document);
						// }

						if (!empty($SubsidySummarySheet) && file_exists($SubsidySummarySheet)) {
							array_push($documentArr, $SubsidySummarySheet);
						}
						$ZipFileList 	= implode(" ",$documentArr);
						$ApplicationID 	= $ApplyOnline->id;
						$GEDA_APP_NO	= str_replace("/","_",$ApplyOnline->geda_application_no);
						$ZipFileName	= WWW_ROOT.APPLYONLINE_PATH.$ApplicationID."/".$GEDA_APP_NO.".zip";
						if (file_exists($ZipFileName)) {
							unlink($ZipFileName);
						}
						echo "\r\n--ZipFileName:: ".$ZipFileName."--\r\n";
						$ZipCommand		= "zip -j ".$ZipFileName." ".$ZipFileList;
						system($ZipCommand);

						if (file_exists($ZipFileName) && $SendEMail)
						{
							$this->SubsidyRequestApplication->updateAll(['processed'=>$processed,'processed_completion_date'=>$this->NOW()],
																		['id' => $ListOfApplication->id]);
							$GenerateCoverLetter 	= $this->GenerateCoverLetter($SubsidyRequest->id);
							$GenerateCoverLetterPDF = "";
							if ($GenerateCoverLetter) {
								$GenerateCoverLetterPDF = $this->generateSubsidyCoverLetterPDF($SubsidyRequest->id,$Request_Date);
								echo "\r\n--GenerateCoverLetterPDF:: ".$GenerateCoverLetterPDF."--\r\n";
							}
							$Conditions 	= array('Installers.id'=>$ApplyOnline->installer_id);
		        			$fields 		= ['Installers.id','Installers.installer_name','Installers.email'];
							$arrInstaller	= $this->Installers->find('all',array('fields'=>$fields,'conditions'=>$Conditions))->first();
		        			if (!empty($arrInstaller))
		        			{
		        				if (isset($arrInstaller->email) && !empty($arrInstaller->email))
				                {
				                    $subject   	= "GEDA | USRTP: Subsidy Document/REG. No: ".$ApplyOnline->geda_application_no;
				                    $email     	= new Email('default');
				                    $EmailTo   	= trim($arrInstaller->email);
				                    echo "\r\n--EmailTo:: ".$EmailTo."--\r\n";
				                    if (Configure::read('SERVER_MODE') != "PROD") {
				                    	$EmailTo 	= '';//"kalpak@yugtia.com";
				                    }
				                    echo "\r\n--EmailTo:: ".$EmailTo."--\r\n";
				                    if ($GenerateCoverLetter && !empty($GenerateCoverLetterPDF)) {
										$MESSAGE 	= "Dear ".$arrInstaller->installer_name.",<br /><br />With reference to the GEDA Registration no. <b>".$ApplyOnline->geda_application_no."</b>, the documents required for the submission at GEDA are attached herein. The Cover Letter is attached with this email which needs to be enclosed along with other documents while submitting the hard copy at GEDA office, Gandhinagar. Thank you.<br /><br />Regards,<br /><br />Support Team";
				                    } else {
				                    	$MESSAGE 	= "Dear ".$arrInstaller->installer_name.",<br /><br />With reference to the GEDA Registration no. <b>".$ApplyOnline->geda_application_no."</b>, the documents required for the submission at GEDA are attached herein. Thank you.<br /><br />Regards,<br /><br />Support Team";
				                    }
				                    $email->profile('default');
				                    $email->viewVars(array( 'MESSAGE_CONTENT' => $MESSAGE));
				                    $email->template('installer_notification_email', 'default')
				                            ->emailFormat('html')
				                            ->from(array('info.geda@ahasolar.in' => PRODUCT_NAME))
				                            ->to($EmailTo)
				                            // ->bcc('pulkitdhingra@gmail.com')
				                            ->subject($subject);
				                    if ($GenerateCoverLetter && !empty($GenerateCoverLetterPDF)) {
				                    	$email->addAttachments($GenerateCoverLetterPDF);
				                    }
				                    $email->addAttachments($ZipFileName);
				                    // $email->send();
				                    $Emaillog                  = $this->Emaillog->newEntity();
				                    $Emaillog->email           = $EmailTo;
				                    $Emaillog->send_date       = $this->NOW();
				                    $Emaillog->action          = $subject;
				                    $Emaillog->description     = json_encode(array( 'EMAIL_ADDRESS' => $EmailTo));
				                    $this->Emaillog->save($Emaillog);
				                    unlink($ZipFileName);
				                    if (!empty($GenerateCoverLetterPDF)) unlink($GenerateCoverLetterPDF);
				                    if (!empty($SubsidySummarySheet) && file_exists($SubsidySummarySheet)) unlink($SubsidySummarySheet);
				                }
		        			}
						}
					}
				}
				$NoPendingApplication = $this->GenerateCoverLetter($SubsidyRequest->id);
				if ($DoProcess && $NoPendingApplication) {
					$this->SubsidyRequest->updateAll(['processed'=>$processed,'processed_completion_date'=>$this->NOW()],
													['id' => $SubsidyRequest->id]);
				}
				$this->CronApiProcess->saveAPILog($SubsidyRequest->id,"send_subsidyzip_cron");
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }

    /**
	 * generateSubsidyCoverLetterPDF
	 * Behaviour : public
	 * @param : $request_id
	 * @param : $SubsidyRequests
	 * @param : $isdownload
	 * @defination : Method is use to generate coverletter pdf
	 */
	public function generateSubsidyCoverLetterPDF($request_id=0,$Request_Date,$isdownload=false)
	{
		$Conditions 					= array('request_id'=>$request_id);
		$ListOfApplications				= $this->SubsidyRequestApplication->find('all',array('conditions'=>$Conditions))->toArray();
		$SubsidyRequestDetails 			= $this->SubsidyRequest->find('all',array('conditions' => array('id'=>$request_id)))->first();
		$No_of_Projects					= 0;
		$Total_Capacity					= 0;
		$No_of_Residential				= 0;
		$Total_Capacity_Residential		= 0;
		$No_of_Social_Sector			= 0;
		$Total_Capacity_Social_Sector	= 0;
		$Total_MNRE_Amount 				= 0;
		$Total_State_Amount 			= 0;
		$Total_Subsidy_Amount 			= 0;
		$Total_Geda_Inspection_Report 	= 0;
		$Application_Nos				= array();
		foreach($ListOfApplications as $ListOfApplication)
		{
			$ApplyOnline 	= $this->ApplyOnlines->viewApplication($ListOfApplication->application_id);
			$SubsidyDetails	= $this->Subsidy->find('all',array('conditions'=>array('application_id'=>$ListOfApplication->application_id)))->first();
			$ProjectData 	= $this->Projects->get($ApplyOnline->project_id);
			array_push($Application_Nos,$ApplyOnline->geda_application_no);
			$No_of_Projects++;
			$Total_Capacity += $ApplyOnline->pv_capacity;
			if ($ApplyOnline->category == $this->ApplyOnlines->category_residental) {
				if ($ApplyOnline->social_consumer == 1) {
					$No_of_Social_Sector++;
					$Total_Capacity_Social_Sector += $ApplyOnline->pv_capacity;
				} else {
					$No_of_Residential++;
					$Total_Capacity_Residential += $ApplyOnline->pv_capacity;
				}
				$capitalCost 		= $this->Projects->calculatecapitalcost($ProjectData->recommended_capacity,$ProjectData->state,$ProjectData->customer_type);
				$SubsidyDetailInfo 	= $this->Projects->calculatecapitalcostwithsubsidy($ProjectData->recommended_capacity,$capitalCost,$ProjectData->state,$ProjectData->customer_type,true,$ApplyOnline->social_consumer);
				if($ApplyOnline->social_consumer == 1 || $ApplyOnline->common_meter == 1)
	            {
	                $SubsidyDetailInfo['state_subsidy_amount'] 	= 0;
	            }
				$Total_MNRE_Amount += $SubsidyDetailInfo['central_subsidy_amount'];
				$Total_State_Amount += $SubsidyDetailInfo['state_subsidy_amount'];
				$Total_Subsidy_Amount += $SubsidyDetailInfo['total_subsidy'];
			}
			if(isset($SubsidyDetails->geda_inspection_report) && !empty($SubsidyDetails->geda_inspection_report))
            {
                $path = SUBSIDY_PATH.$SubsidyDetails->application_id."/".$SubsidyDetails->geda_inspection_report;
                if (file_exists($path))
                {
                    $Total_Geda_Inspection_Report++;
                }
            }
			$INSTALLER_NAME = $ApplyOnline->installer['installer_name'];
		}

		$GEDA_APPLICATION_NOS 		= implode("<br />",$Application_Nos);
		$REQUEST_GENERATION_DATE 	= date("d/M/Y",strtotime($Request_Date));

		$view 			= new View();
    	$view->layout 	= false;
    	$view->set('REQUEST_GENERATION_DATE',$REQUEST_GENERATION_DATE);
		$view->set('COVER_LETTER_ADDRESS_DETAILS',SUBSIDY_COVER_LETTER_ADDRESS);
		$view->set('INSTALLER_NAME',$INSTALLER_NAME);
		$view->set('No_of_Projects',$No_of_Projects);
		$view->set('Total_Capacity',$Total_Capacity);
		$view->set('No_of_Residential',$No_of_Residential);
		$view->set('Total_Capacity_Residential',$Total_Capacity_Residential);
		$view->set('No_of_Social_Sector',$No_of_Social_Sector);
		$view->set('Total_Capacity_Social_Sector',$Total_Capacity_Social_Sector);
		$view->set('Total_MNRE_Amount',_FormatNumberV2($Total_MNRE_Amount));
		$view->set('Total_State_Amount',_FormatNumberV2($Total_State_Amount));
		$view->set('Total_Subsidy_Amount',_FormatNumberV2($Total_Subsidy_Amount));
		$view->set('GEDA_APPLICATION_NOS',$GEDA_APPLICATION_NOS);
		$view->set('Total_Geda_Inspection_Report',$Total_Geda_Inspection_Report);
		$view->set('REQUEST_NO',$SubsidyRequestDetails->request_no);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(WWW_ROOT.'/pdf/');
		$view->set('dompdf',$dompdf);
		$html = $view->render('/Element/subsidy_coverletter');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		if($isdownload) {
			$dompdf->stream('subsidy_coverletter-'.$request_id);
		} else {
			$output 	= $dompdf->output();
			$pdfPath 	= WWW_ROOT.'/tmp/subsidy_coverletter-'.$request_id.'.pdf';
			file_put_contents($pdfPath, $output);
			return $pdfPath;
		}
	}

	/**
	 * GenerateCoverLetter
	 * Behaviour : public
	 * @param : $request_id
	 * @defination : Method is use to check for generate coverletter pdf
	 */
	private function GenerateCoverLetter($request_id)
	{
		$Conditions 		= array('processed'=>'0','request_id'=>$request_id);
		$ListOfApplications	= $this->SubsidyRequestApplication->find('all',array('conditions'=>$Conditions))->count();
		if (empty($ListOfApplications)) {
			return true;
		} else {
			return false;
		}
	}
}