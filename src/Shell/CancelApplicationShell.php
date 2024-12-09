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
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class CancelApplicationShell extends Shell
{
	public $CANCEL_LIMIT_DAYS = 3;
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('FesibilityReport');
		$this->loadModel('Installers');
    }
    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $arrStatus 			= array($this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,$this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED);
        $GUJARAT_STATE 		= 4;
		$arrApplications 	= $this->FesibilityReport->find('all',
										[
											'fields'  		=> [
																"FesibilityReport.id","ApplyOnlines.id",
																"FesibilityReport.quotation_number",
																"FesibilityReport.estimated_amount",
																"FesibilityReport.estimated_due_date",
																"FesibilityReport.payment_approve",
																"ApplyOnlines.installer_id",
																"ApplyOnlines.mobile","ApplyOnlines.email",
																"ApplyOnlines.name_of_consumer_applicant","ApplyOnlines.last_name",
																"ApplyOnlines.apply_state","ApplyOnlines.state",
																"ApplyOnlines.geda_application_no",
																"ApplyOnlines.installer_email",
																"ApplyOnlines.consumer_mobile",
																"ApplyOnlines.installer_mobile"
																],
											'join'=>[['table'=>'apply_onlines','alias'=>'ApplyOnlines','type'=>'left','conditions'=>'ApplyOnlines.id = FesibilityReport.application_id']],
											'conditions'	=> ['ApplyOnlines.application_status IN '=>$arrStatus,'ApplyOnlines.apply_state'=>$GUJARAT_STATE]
										]
									);
		foreach($arrApplications as $arrApplication)
		{
			$PaymentDone 	= $arrApplication->payment_approve;
			$DueDateGone 	= (strtotime(date("Y-m-d")) > strtotime($arrApplication->estimated_due_date))?1:0;
			if (!$PaymentDone && $DueDateGone && !empty($arrApplication->estimated_due_date) && $arrApplication->estimated_due_date != '0000-00-00')
			{
				$EstimationGenerationDate 			= strtotime($arrApplication->estimated_due_date);
				$ExtendedLimitDays 					= date("Y-m-d",strtotime($arrApplication->estimated_due_date."+ $this->CANCEL_LIMIT_DAYS Days"));
				$CurrentDate 						= date("Y-m-d");
				$datediff 							= (strtotime($ExtendedLimitDays) - strtotime($CurrentDate));
				$NoDaysSinceApplicationEstimation 	= ($datediff > 0)?round($datediff / (60 * 60 * 24)):0;
				$application 						= $arrApplication->ApplyOnlines;
				$ApplicationNo 						= $application['geda_application_no'];
				//echo $ApplicationNo;
				switch ($NoDaysSinceApplicationEstimation)
				{
					case 9:{
						$SMS_Text = "Dear ".$application['name_of_consumer_applicant']." ".$application['last_name'].", Payment of your Rooftop Solar application ".$ApplicationNo." is due. Please pay with in next 7 days before application gets cancelled.";
						break;
					}
					case 5:{
						$SMS_Text = "Dear ".$application['name_of_consumer_applicant']." ".$application['last_name'].", Payment of your Rooftop Solar application ".$ApplicationNo." is due. Please pay with in next 2 days before application gets cancelled.";
						break;
					}
					case 4:{
						$SMS_Text = "Dear ".$application['name_of_consumer_applicant']." ".$application['last_name'].", Payment of your Rooftop Solar application ".$ApplicationNo." is due. Please pay by tomorrow before application gets cancelled.";
						break;
					}
					case 3:{
						$SMS_Text = "Dear ".$application['name_of_consumer_applicant']." ".$application['last_name'].", Payment of your Rooftop Solar application ".$ApplicationNo." is due. Please pay today before application gets cancelled.";
						break;
					}
				}
				if ($NoDaysSinceApplicationEstimation > 0)
				{
					$arrData = array("application_status"=>$this->ApplyOnlineApprovals->APPLICATION_CANCELLED);
        			$this->ApplyOnlines->updateAll($arrData,['id' => $application['id']]);
        			$this->ApplyOnlineApprovals->saveStatus($application['id'],$this->ApplyOnlineApprovals->APPLICATION_CANCELLED,"1","Application cancelled due to payment not done by consumer after 30 days of Estimation done.");
        			$SMS_Text = "Dear ".$application['name_of_consumer_applicant']." ".$application['last_name'].", Your Rooftop Solar application ".$ApplicationNo." is cancelled due to payment not done.";
        			$ApplyOnlines = TableRegistry::get("ApplyOnlines");
	                if(!empty($application['consumer_mobile']))
	                {
	                    $ApplyOnlines->sendSMS($application['id'],$application['consumer_mobile'],$SMS_Text);
	                }
	                if(!empty($application['installer_mobile']))
	                {
	                    //$ApplyOnlines->sendSMS($application['id'],$application['installer_mobile'],$SMS_Text);
	                }
		            
        			if (!empty($application['email'])) {
        				$CustomerEmail 	= $application['email'];
        				$InstallerEmail	= $application['installer_email'];
        			} else {
        				$CustomerEmail 	= $application['installer_email'];
        				$InstallerEmail	= "";
        			}
					$subject		= "Rooftop Solar application :: ".$ApplicationNo." - Cancelled";
					$email 			= new Email('default');
				 	$email->profile('default');

					$email->viewVars(array('CUSTOMER_NAME' => $application['name_of_consumer_applicant']." ".$application['last_name'],
											'APPLICATION_NO' => $ApplicationNo));
					$email->template('application_cancelled_receipt', 'default')
							->emailFormat('html')
							->from(array('info.geda@ahasolar.in' => PRODUCT_NAME))
						    ->to($CustomerEmail)
						    ->bcc('cancelled-app-email@ahasolar.in')
						    ->subject(Configure::read('EMAIL_ENV').$subject);
					if (!empty($InstallerEmail)) {
						$email->cc($InstallerEmail);
					}
					$email->send();
					$ApplicationCancelEmail_log = TableRegistry::get("ApplicationCancelEmailLog");
					$ApplicationCancelEmailLog 					= $ApplicationCancelEmail_log->newEntity();
					$ApplicationCancelEmailLog->application_id 	= $application['id'];
					$ApplicationCancelEmailLog->email_to 		= $CustomerEmail;
	            	$ApplicationCancelEmailLog->installer_email = $InstallerEmail;
	            	$ApplicationCancelEmailLog->created 		= date('Y-m-d H:i:s');
					$ApplicationCancelEmail_log->save($ApplicationCancelEmailLog);
				}
			} 
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}