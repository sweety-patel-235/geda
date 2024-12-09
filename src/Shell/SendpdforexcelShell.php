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

class SendpdforexcelShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('PhpExcel');
        $this->loadModel('Installers');
        $this->loadModel('SitesurveyProjectRequest');
    	$this->loadModel('Customers');
    	$this->loadModel('Projects');
    	$this->loadModel('SiteSurveys');
    	$this->loadModel('InstallerProjects');
    	$this->loadModel('SiteSurveysImages');
    	$this->loadModel('InstallerSubscription');
    	$this->loadComponent('PhpExcel');
    }
    public function main()
    {
        echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$installerArr = $this->Installers->find('all',['conditions'=>['status'=>1,'id >'=>693]])->toArray();
		//echo 'test';
		$arr_request_customer 	= $this->SitesurveyProjectRequest->find('all',array('conditions' => array('send_status'=>'0')))->toArray();
		$customer_count 		= 0;
		foreach($arr_request_customer as $val)
		{
			$arr_customer= $this->Customers->find('all',array('conditions'=>array('id'=>$val['requested_by'])))->first();
			$arr_project = $this->Projects->find('all',array('conditions'=>array('id'=>$val['project_id'])))->first();
			if($val['project_id'] > 0)
			{
				if($val['request_file']=='pdf')
				{
					$pdfPath 	= $this->genratesurveyPDFreport($val['project_id'],false,1);
				}	
				if($val['request_file']=='excel')
				{
					$result_data=$this->SiteSurveys->find('all',array('conditions' => array('project_id'=>$val['project_id'])))->toArray();
			        $arr_projets_data=$this->Projects->find('all',array('fields'=>array('Projects.name'),
			            'conditions'=>array('id'=>$val['project_id'])))->first();
			        $project_name='';
			        if(!empty($arr_projets_data))
			        {
			            $project_name 	= $arr_projets_data['name'];
			            $arr_installer 	= $this->InstallerProjects->find('all',array('conditions' => array('project_id'=>$val['project_id'])))->toarray();
			        }
			        $all_area_types     = $this->SiteSurveys->AREA_PARAMS;
			        $all_area_type_smp  = $this->SiteSurveys->AREA_PARAMS_SMP;
			        $all_load           = $this->SiteSurveys->LOAD_PARAMS;
			        $all_meter          = $this->SiteSurveys->ALL_METER_TYPE;
			        $all_meter_accuracy = $this->SiteSurveys->ALL_METER_ACCURACY_CLASS;
			        $all_roof           = $this->SiteSurveys->ALL_ROOF_TYPE;
			        $all_roof_st        = $this->SiteSurveys->ALL_ROOF_STRENGTH;
			        $all_billing        = $this->SiteSurveys->ALL_BILLING_CYCLE;
			        $pdfPath = $this->survey_download_xls($result_data, $project_name, $all_area_types, $all_area_type_smp, $all_load, $all_meter, $all_meter_accuracy, $all_roof, $all_roof_st, $all_billing,'1');
				}
		        $to			= $arr_customer['email'];
				$subject	= "Site survey report for project ".$arr_project['name']." - Id : ".$arr_project['id'];
				$email 		= new Email('default');
			 	$email->profile('default');

				$email->viewVars(array('CUSTOMER_NAME' => $arr_customer['name'],'PROJECT_NAME' => $arr_project['name'],'PROJECT_ID' => $arr_project['id']));			
				$email->template('sitesurvey_project_survey', 'default')
						->emailFormat('html')
						->from(array('info@ahasolar.in' => PRODUCT_NAME))
					    ->to($to)
					    ->attachments($pdfPath)
					    ->subject(Configure::read('EMAIL_ENV').$subject)
					    ->send();
				$this->SitesurveyProjectRequest->updateAll(array('send_status'=>'1'),array('id'=>$val['id']));
				if(file_exists($pdfPath))
				{
					@unlink($pdfPath);
				}
				$customer_count++;
			}
		}
		echo 'Mail sent to total '.$customer_count.' customer';

		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
    /**
	 *
	 * genratesurveyPDFreport
	 *
	 * Behaviour : public
	 *
	 * @param : site_id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=false, $isInstallerhide=0
	 *
	 * @defination : Method is use to download .pdf file from modal popup of survey listing
	 *
	 */
	public function genratesurveyPDFreport($site_id,$isdownload=false,$is_project=0)
	{
		$this->layout 		= false;
		if($is_project==1)
		{
			$project_id 			= $site_id;
			$result_data 			= $this->SiteSurveys->find('all',array('conditions' => array('project_id'=>$project_id)))->toArray();
			$result_project_data 	= $this->Projects->find('all',array('conditions' => array('id' => $project_id)))->first();
		}
		else
		{
			$result_data 			= $this->SiteSurveys->find('all',array('conditions' => array('id'=>$site_id)))->toArray();
			
		}
		$result_project_data 		= $this->Projects->find('all',array('conditions' => array('id' => $result_data[0]['project_id'])))->first();
		$result_installer 	 		= $this->InstallerProjects->find('all',[
									'join' => [
	                            		'installers' => [
			                                'table' => 'installers',
			                                'type' => 'LEFT',
			                                'conditions' => ['InstallerProjects.installer_id = installers.id']
			                            ],
			                        	'companies' => [
			                                'table' => 'companies',
			                                'type' => 'LEFT',
			                                'conditions' => ['installers.company_id = companies.id']
			                            ]],
			                        'fields' => array('installers.id','installers.installer_name','installers.contact_person','installers.company_id','companies.company_name'),
									'conditions' => ['project_id' => $result_data[0]['project_id'], 'InstallerProjects.status' => '4002']])->toArray();
		$projectReportId 			= $this->Projects->GetProjectPDFReportId($site_id);
		
		foreach($result_data as $survey_data)
		{
			$result_photo_data[$survey_data->id] 		= $this->SiteSurveysImages->find('list',array('keyField'=>'type', 'valueField'=>'photo', 'conditions' => array('building_id'=>$survey_data->building_id, 'project_id' => $survey_data->project_id)))->toArray();
			
			$all_photo_data[$survey_data->id]      		= $this->SiteSurveysImages->find('all',array('conditions' => array('building_id'=>$survey_data->building_id, 'project_id' => $survey_data->project_id)))->toArray();
			

			$stream_opts =  [
	    					"ssl" => [
					        "verify_peer"=>false,
					        "verify_peer_name"=>false,
					    	]
							];  
			$mapImage[$survey_data->id]     = '';

			if($survey_data['site_lat']!=0 && $survey_data['site_log']!=0)
			{
				$latLng   = $survey_data['site_lat'].",".$survey_data['site_log'];
				$mapUrl   = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=650x378&markers=color:blue%7C'.$latLng.'&sensor=false';
				$mapImage[$survey_data->id] = file_get_contents($mapUrl, false, stream_context_create($stream_opts));
				$mapImage[$survey_data->id] = 'data:image/png;base64,' . base64_encode($mapImage[$survey_data->id]);
			}
		}
		$arr_subscription = array();
		if(!empty($result_installer))
        {
        	$arr_subscription = $this->InstallerSubscription->find('all',array('conditions'=>array('installer_id'=>$result_installer[0]['installers']['id'],'expire_date >='=>date('Y-m-d'),'free_flag'=>'0')))->toArray();
        }
        $this->set('arr_subscription',$arr_subscription);
		$this->set('footer_st_content','Site Survey PV Project Report');
		$this->set('result_data_pass',$result_data);
		$this->set('result_installer',$result_installer);
		$this->set('result_project_data',$result_project_data);
		$this->set('result_photo_data_projects',$result_photo_data);
		$this->set('all_photo_data_projects',$all_photo_data);
		$this->set('all_roof',$this->SiteSurveys->ALL_ROOF_TYPE);
		$this->set('all_roof_st',$this->SiteSurveys->ALL_ROOF_STRENGTH);
		$this->set('all_area_types',$this->SiteSurveys->AREA_PARAMS);
		$this->set('all_area_type_smp',$this->SiteSurveys->AREA_PARAMS_SMP);
		$this->set('all_meter',$this->SiteSurveys->ALL_METER_TYPE);
		$this->set('all_meter_accuracy',$this->SiteSurveys->ALL_METER_ACCURACY_CLASS);
		$this->set('all_billing',$this->SiteSurveys->ALL_BILLING_CYCLE);
		$this->set('all_load',$this->SiteSurveys->LOAD_PARAMS);
		$this->set('all_cust_type',$this->SiteSurveys->ALL_CUSTOMER_TYPE);
		$this->set('mapImage',$mapImage);
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		//$dompdf->set_option('defaultFont', "Courier");
		
		$html = $this->render('/Element/site_survey');
		
		//exit($html);
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		
		$dompdf->render();
		
		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('reportsurvey-'.$projectReportId);	
		}
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/reportsurvey-'.$projectReportId.'.pdf';
		file_put_contents($pdfPath, $output);	
		return $pdfPath; 
	}
	/**
	 *
	 * survey_download_xls
	 *
	 * Behaviour : public
	 *
	 * @param : result_data , $isdownload=false, $isInstallerhide=0
	 *
	 * @defination : Method is use to download .pdf file from modal popup of survey listing
	 *
	 */
	public function survey_download_xls($result_data, $project_name, $all_area_types, $all_area_type_smp, $all_load, $all_meter, $all_meter_accuracy, $all_roof, $all_roof_st, $all_billing,$mail_send=0)
	{
		$counter_id=1;
		$arr_data=array();
		$counter_id=1;
		$arr_data=array();
		$PhpExcel=$this->PhpExcel;
		$PhpExcel->createExcel();

		$PhpExcel->additonalSheet(1,'Introduction');
		$gdImage = imagecreatefrompng('pdf/images/logo_pdf.png');
		
		// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
		$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction();
		$objDrawing->setMimeType();
		$objDrawing->setHeight(90);
		//$objDrawing->setWidth(90);
		$objDrawing->setCoordinates('A2');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$PhpExcel->writeCellValue('B8', 'Document Name');
		$PhpExcel->writeCellValue('C8', ':');
		$PhpExcel->writeCellValue('D8', $project_name);	
		$PhpExcel->writeCellValue('B9', 'Revised By');
		$PhpExcel->writeCellValue('C9', ':');
		$PhpExcel->writeCellValue('B10', 'Reviewed By');
		$PhpExcel->writeCellValue('C10', ':');
		$PhpExcel->writeCellValue('B11', 'Powered By');
		$PhpExcel->writeCellValue('C11', ':');
		$PhpExcel->writeCellValue('D11', 'AHASOLAR PRIVATE LIMITED');
		$PhpExcel->writeCellValue('B12', 'Prepared By');
		$PhpExcel->writeCellValue('C12', ':');

		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getProtection()->setSheet(true);

		$PhpExcel->additonalSheet(2,'Area Details');
		$PhpExcel->writeMergeCellValue('A1:AE1');
		$PhpExcel->writeCellValue('A1','Project Name - '.$project_name);
		$PhpExcel->fillCellColour('A1:AM1','D7E4BD');
		$PhpExcel->fillCellFont('A1','000000',TRUE,'16px');
		$PhpExcel->getExcelObj()->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$j=2;
		$PhpExcel->writeCellValue('A'.$j, 'Site No.');
		$PhpExcel->writeCellValue('B'.$j, 'Building Name');
		//$PhpExcel->writeCellValue('C'.$j, "Orientation");
		$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
		$PhpExcel->writeCellValue('D'.$j, "Survey Date");
		$PhpExcel->writeCellValue('E'.$j, "Latitude");
		$PhpExcel->writeCellValue('F'.$j, "Longitute");
		$PhpExcel->writeCellValue('G'.$j, "Total Area");
		$PhpExcel->writeCellValue('H'.$j, "Shadowed Area");
		$PhpExcel->writeCellValue('I'.$j, "Shadow Free Area");
		$PhpExcel->writeCellValue('J'.$j, "Estimated Solar PV capacity");
		$PhpExcel->writeCellValue('K'.$j, "DG Availability");
		$PhpExcel->writeCellValue('L'.$j, "Electricity Bill");
		$PhpExcel->writeCellValue('M'.$j, "Accesibility to Roof");
		$PhpExcel->writeCellValue('N'.$j, "Name of the Distribution Company");
		$PhpExcel->writeCellValue('O'.$j, "Consumer No.");
		$PhpExcel->writeCellValue('P'.$j, "Contact Person");
		$PhpExcel->writeCellValue('Q'.$j, "Contact No.");
		$PhpExcel->writeCellValue('R'.$j, "Contact Email");
		$PhpExcel->writeCellValue('S'.$j, "Surveyor");
		$PhpExcel->writeCellValue('T'.$j, "Type of roof");
		$PhpExcel->writeCellValue('U'.$j, "Roof Strength");
		$PhpExcel->writeCellValue('V'.$j, "Age of Building");
		$PhpExcel->writeCellValue('W'.$j, "Azimuth");
		$PhpExcel->writeCellValue('X'.$j, "Inclination");
		$PhpExcel->writeCellValue('Y'.$j, "Height of Parapet?");
		$PhpExcel->writeCellValue('Z'.$j, "Floors");
		$PhpExcel->writeCellValue('AA'.$j, "Disctance DC cable");
		$PhpExcel->writeCellValue('AB'.$j, "Place for Inverter?");
		$PhpExcel->writeCellValue('AC'.$j, "Place for Battery?");
		$PhpExcel->writeCellValue('AD'.$j, "Place for AC Distribution Box?");
		$PhpExcel->writeCellValue('AE'.$j, "Place for Metering Point?");
		$PhpExcel->writeCellValue('AF'.$j, "Voltage Level");
		$PhpExcel->writeCellValue('AG'.$j, "Measured Frequency");
		$PhpExcel->writeCellValue('AH'.$j, "Critical Load");
		$PhpExcel->writeCellValue('AI'.$j, "Meter Type");
		$PhpExcel->writeCellValue('AJ'.$j, "Meter Accuracy Class");
		$PhpExcel->writeCellValue('AK'.$j, "Sanctioned Load");
		$PhpExcel->writeCellValue('AL'.$j, "Contract Demand");
		$PhpExcel->writeCellValue('AM'.$j, "Billing Cycle");

		$PhpExcel->getExcelObj()->getActiveSheet()->freezePane('A3');
		//$PhpExcel->getExcelObj()->getActiveSheet()->freezePane('B3');
		for($i=65;$i<=90;$i++)
		{
			$PhpExcel->fillCellFont((chr($i)).$j,'000000',TRUE);
		}
		for($i=65;$i<=77;$i++)
		{
			$PhpExcel->fillCellFont('A'.(chr($i)).$j,'000000',TRUE);
		}
		for($ch=65;$ch<=90;$ch++)
		{
			if($ch!=71 && $ch!=72 && $ch!=73  && $ch!=74  && $ch!=86 && $ch!=89)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($ch))->setAutoSize(true);
				
			}
		}
		for($ch=65;$ch<=77;$ch++)
		{
			if($ch!=65 && $ch!=66 && $ch!=67 && $ch!=68 && $ch!=69 && $ch!=70 && $ch!=71 && $ch!=72 && $ch!=74 && $ch!=75 && $ch!=76 && $ch!=77)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('A'.chr($ch))->setAutoSize(true);
			}
		}
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('G2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('H2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('I2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('J2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('V2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('Y2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AA2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AB2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AC2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AD2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AE2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AF2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AG2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AH2')->getAlignment()->setWrapText(true);
		//$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AI2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AJ2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AK2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AL2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AM2')->getAlignment()->setWrapText(true);
		
		$counter_id=1;
		$arr_data=array();
		$j++;
		$PhpExcel->writeCellValue('G'.$j, "(sq.m)");
		$PhpExcel->writeCellValue('H'.$j, "(sq.m)");
		$PhpExcel->writeCellValue('I'.$j, "(sq.m)");
		$PhpExcel->writeCellValue('K'.$j, "kVA");
		
		$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
		$j++;
		$total_con_demand  = 0;
		$total_rec_cap     = 0;
		$total_area        = 0;
		$total_shadow_free = 0;
		$site_avail        = 0;
		$all_site_pow_con  = 0;
		foreach($result_data as $val)
		{
			$avg_pow_con     = 0;
			$avg_bill_amt    = 0;
			$total_gen_kva   = 0;
			$total_gen_hours = 0;
			if(!empty($val['genset_details']))
			{
				$arr_genset      = unserialize($val['genset_details']);
				foreach($arr_genset['GensetDetails']  as $val_gen)
				{
					$total_gen_kva   = $total_gen_kva + $val_gen['kva'];
					$total_gen_hours = $total_gen_hours + $val_gen['hours'];
				}
			}
			if(!empty($val['month_details']))
			{
				$site_avail++;
				$arr_month       = unserialize($val['month_details']);
				$arr_all_month   = $arr_month['ElectricityBillDetails'];
				$sum_power_con   = 0;
				$sum_bill_amount = 0;
				$total_val=0;
				for($i=0;$i<=11;$i++)
				{
					if(strtolower($arr_all_month[$i]['year'])!='year')
					{		
						$sum_power_con   = $sum_power_con+$arr_all_month[$i]['power_consume'];
						$sum_bill_amount = $sum_bill_amount+$arr_all_month[$i]['bill_amount'];
						$total_val++;
					}				
				}
				
				if($total_val>0)
				{
					$all_site_pow_con = $all_site_pow_con + ($sum_power_con/$total_val);
					$avg_pow_con      = number_format($sum_power_con/$total_val,'2','.',',');
					$avg_bill_amt     = number_format($sum_bill_amount/$total_val,'2','.',',');
				}
			}
			$shadow_free       = $val['shadow_free'];
			$overall           = $val['overall'];
			$height_of_parapet = $val['height_of_parapet'];
			$dc_cabel_distance = $val['dc_cabel_distance'];
			$sanctioned_load   = $val['sanctioned_load'];
			if($val['is_shadow_free'] == '2002')
			{
					$one_foot_m  = '0.3048';
					$shadow_free = $shadow_free * 0.3048;
			}
			if($val['is_overall'] == '2002')
			{
					$one_foot_m = '0.3048';
					$overall    = $overall * 0.3048;
			}
			if($val['is_height_of_parapet'] == '2002')
			{
					$one_foot_m = '0.3048';
					$height_of_parapet    = $height_of_parapet * 0.3048;
			}
			if($val['is_dc_cable_distance'] == '2002')
			{
					$one_foot_m = '0.3048';
					$dc_cabel_distance    = $dc_cabel_distance * 0.3048;
			}
			if($val['is_snaction'] == '0')
			{
				$sanctioned_load = $val['sanctioned_load'] * 0.8;
			}
			$disp_inverter_ph     = 'No';
			$disp_battery         = 'No';
			$disp_ac_distribution = 'No';
			$disp_meter_point     = 'No';
			
			$result_photo_data   = $this->SiteSurveysImages->find('list',array('keyField'=>'type', 'valueField'=>'photo', 'conditions' => array('building_id'=>$val->building_id, 'project_id' => $val->project_id)))->toArray();

			if(!empty($result_photo_data)) 
            { 
            	if(array_key_exists('place_inverter',$result_photo_data))
            	{
            		$disp_inverter_ph = 'Yes';
            	}
            	if(array_key_exists('place_battery',$result_photo_data)) 
                { 
                    $disp_battery = 'Yes';
                } 
                if(array_key_exists('place_for_ac_distribution_box',$result_photo_data)) 
                { 
                    $disp_ac_distribution = 'Yes';
                }
                if(array_key_exists('metering_box',$result_photo_data)) 
                { 
                    $disp_meter_point = 'Yes';
                }      
            } 
            
			$PhpExcel->writeCellValue('A'.$j, 'Site-'.$counter_id);
			$PhpExcel->fillCellFont('A'.$j,'000000',TRUE);
			$PhpExcel->writeCellValue('B'.$j, $val['building_name']);
			//$PhpExcel->writeCellValue('C'.$j, '--orientation--');
			$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
			$PhpExcel->writeCellValue('D'.$j, $val['created']);
			$PhpExcel->writeCellValue('E'.$j, $val['user_lat']);
			$PhpExcel->writeCellValue('F'.$j, $val['user_log']);
			$PhpExcel->writeCellValue('G'.$j, $overall);
			$PhpExcel->fillCellColour('G'.$j,'FFCC99');
			$PhpExcel->fillCellFont('G'.$j,'3F3F76');
			
			
			$PhpExcel->writeCellValue('H'.$j, ($overall-$shadow_free));
			$PhpExcel->fillCellColour('H'.$j,'F2F2F2');
			$PhpExcel->fillCellFont('H'.$j,'FA7D00',true);

			$PhpExcel->writeCellValue('I'.$j, $shadow_free);
			$PhpExcel->writeCellValue('J'.$j, $val['recommended_capacity']);
			$PhpExcel->fillCellColour('J'.$j,'F2F2F2');
			$PhpExcel->fillCellFont('J'.$j,'FA7D00',true);
			$PhpExcel->writeCellValue('K'.$j, $total_gen_kva.' kVA - '.$total_gen_hours.' Hours');
			$PhpExcel->writeCellValue('L'.$j, $avg_pow_con.' unit - '.$avg_bill_amt.' amount');
			$PhpExcel->writeCellValue('M'.$j, $val['object_on_roof']);
			$PhpExcel->writeCellValue('N'.$j, $val['distribution_company']);
			$PhpExcel->writeCellValue('O'.$j, $val['customer_no']);
			$PhpExcel->writeCellValue('P'.$j, $val['contact_name']);
			$PhpExcel->writeCellValue('Q'.$j, $val['mobile']);
			$PhpExcel->writeCellValue('R'.$j, $val['email_id']);
			$PhpExcel->writeCellValue('S'.$j, $val['surveyer_name']);
			$PhpExcel->writeCellValue('T'.$j, $all_roof[$val['roof_type']]);
			$PhpExcel->writeCellValue('U'.$j, $all_roof_st[$val['roof_strenght']]);
			$PhpExcel->writeCellValue('V'.$j, $val['age_of_building']);
			$PhpExcel->writeCellValue('W'.$j, $val['azimuth']);
			$PhpExcel->writeCellValue('X'.$j, $val['inclination_of_roof']);
			$PhpExcel->writeCellValue('Y'.$j, $height_of_parapet);
			$PhpExcel->writeCellValue('Z'.$j, $val['floor_below_tarrace']);
			$PhpExcel->writeCellValue('AA'.$j, $dc_cabel_distance);
			$PhpExcel->writeCellValue('AB'.$j, $disp_inverter_ph);
			$PhpExcel->writeCellValue('AC'.$j, $disp_battery);
			$PhpExcel->writeCellValue('AD'.$j, $disp_ac_distribution);
			$PhpExcel->writeCellValue('AE'.$j, $disp_meter_point);
			$PhpExcel->writeCellValue('AF'.$j, $val['voltage_pahse_level']);
			$PhpExcel->writeCellValue('AG'.$j, $val['measured_frequency']);
			$PhpExcel->writeCellValue('AH'.$j, $val['critical_load']);
			$PhpExcel->writeCellValue('AI'.$j, $all_meter[$val['meter_type']]);
			$PhpExcel->writeCellValue('AJ'.$j, $all_meter_accuracy[$val['meter_accuracy']]);
			$PhpExcel->writeCellValue('AK'.$j, $sanctioned_load);
			$PhpExcel->writeCellValue('AL'.$j, $val['contract_demand']);
			$PhpExcel->writeCellValue('AM'.$j, $all_billing[$val['billing_cycle']]);

			$total_con_demand  = $total_con_demand + $sanctioned_load;
			$total_rec_cap     = $total_rec_cap + $val['recommended_capacity'];
			$total_area        = $total_area + $overall;
			$total_shadow_free = $total_shadow_free + $shadow_free;
			$j++;	
			$counter_id++;
		}

		//sanctioned_load
		$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
		$j++;

		$PhpExcel->writeCellValue('B'.$j, 'Total Area');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_area);
		$PhpExcel->fillCellColour('E'.$j,'F2F2F2');
		$PhpExcel->fillCellFont('E'.$j,'3F3F3F',true);
		
		$PhpExcel->writeCellValue('F'.$j, 'sq.m');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Shadow Free Area');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_shadow_free);
		$PhpExcel->fillCellColour('E'.$j,'F2F2F2');
		$PhpExcel->fillCellFont('E'.$j,'3F3F3F',true);
		$PhpExcel->writeCellValue('F'.$j, 'sq.m');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Estimated PV Capacity');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_rec_cap);
		$PhpExcel->fillCellColour('E'.$j,'F2F2F2');
		$PhpExcel->fillCellFont('E'.$j,'3F3F3F',true);
		$PhpExcel->writeCellValue('F'.$j, 'kWp');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Total Contract Demand');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_con_demand);
		$PhpExcel->fillCellColour('E'.$j,'FFCC99');
		$PhpExcel->fillCellFont('E'.$j,'3F3F76');
		$PhpExcel->writeCellValue('F'.$j, 'kVA');
		$j++;
		$anual_consume=0;
		$anual_month=0;
		if($site_avail>0)
		{
			$anual_consume = number_format($all_site_pow_con/$site_avail,'2','.',',');
			$anual_month   = number_format($anual_consume/12,'2','.',',');
		}
		$PhpExcel->writeCellValue('B'.$j, 'Average Electricity Consumption');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $anual_consume);
		$PhpExcel->writeCellValue('F'.$j, 'kWh p.a');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Electricity Consumption');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $anual_month);
		$PhpExcel->writeCellValue('F'.$j, 'kWh p.m');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Tariff');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, '');
		$PhpExcel->writeCellValue('F'.$j, 'Rs./kWh');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Bill');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, '');
		$PhpExcel->writeCellValue('F'.$j, 'Rs./month');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Electricity Generation');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, '');
		$PhpExcel->writeCellValue('F'.$j, 'kWh p.a');
		$j++;
		$PhpExcel->writeCellValue('D'.$j, '');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Ratio of Solar Againts Grid');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);

		$PhpExcel->additonalSheet(3,'Electricity Bill');
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);	
		for($i=1;$i<=12;$i++)
		{
			$mon=date("F",mktime(0,0,0,$i,1,date("Y")));
		}
		$row_no=2;
		$counter_id=1;
		foreach($result_data as $val)
		{
			if(!empty($val['month_details']))
			{
			$PhpExcel->writeMergeCellValue('A'.($row_no).':E'.($row_no));
			$PhpExcel->fillCellColour('A'.($row_no).':E'.($row_no),'D7E4BD');
			$PhpExcel->writeCellValue('A'.($row_no), 'Site-'.$counter_id.' - '.$val['building_name']);
			$PhpExcel->fillCellFont('A'.($row_no),'000000',TRUE,'13px');
			$row_no++;
			$PhpExcel->writeCellValue('B'.($row_no), 'Month');
			$PhpExcel->writeCellValue('C'.($row_no), 'Year');
			$PhpExcel->writeCellValue('D'.($row_no), "Units");
			$PhpExcel->writeCellValue('E'.($row_no), "Amount");
			$PhpExcel->fillCellFont('B'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('C'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('D'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('E'.($row_no),'000000',TRUE);
			$row_no++;
			$arr_month=unserialize($val['month_details']);
			$arr_all_month=$arr_month['ElectricityBillDetails'];
			$sum_power_con=0;
			$sum_bill_amount=0;
			$total_val=0;
			for($i=0;$i<=11;$i++)
			{
				$PhpExcel->writeCellValue('B'.($row_no), $arr_all_month[$i]['month']);
				if(strtolower($arr_all_month[$i]['year'])!='year')
				{
					$PhpExcel->writeCellValue('C'.($row_no), $arr_all_month[$i]['year']);
					$PhpExcel->writeCellValue('D'.($row_no), $arr_all_month[$i]['power_consume']);
					$PhpExcel->writeCellValue('E'.($row_no), $arr_all_month[$i]['bill_amount']);
					$sum_power_con   = $sum_power_con+$arr_all_month[$i]['power_consume'];
					$sum_bill_amount = $sum_bill_amount+$arr_all_month[$i]['bill_amount'];
					$total_val++;
				}				
				$row_no++;
			}
			$PhpExcel->writeCellValue('B'.($row_no), 'Average');
			$PhpExcel->fillCellFont('B'.($row_no),'000000',TRUE);
			$PhpExcel->writeCellValue('C'.($row_no), '');
			$avg_pow_con=0;
			$avg_bill_amt=0;
			if($total_val>0)
			{
				$avg_pow_con  = number_format($sum_power_con/$total_val,'2','.',',');
				$avg_bill_amt = number_format($sum_bill_amount/$total_val,'2','.',',');
			}
			$PhpExcel->writeCellValue('D'.($row_no), $avg_pow_con);
			$PhpExcel->writeCellValue('E'.($row_no), $avg_bill_amt);
			$PhpExcel->fillCellFont('D'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('E'.($row_no),'000000',TRUE);
			$row_no++;
			}
			$counter_id++;
		}
		$file_path = $PhpExcel->downloadFile_mail(time());
		return $file_path;
		exit;
	}
}