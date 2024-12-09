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
use Cake\Datasource\ConnectionManager;

class GenerateMISReportDataShell extends Shell
{
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
        $this->loadModel('MISReportData');
    }
    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
    	$RecordFound 	= true;
    	$LastAPPID 		= 0;

/** KP */
		$ConnectionManager = ConnectionManager::get('default');
		$ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
		/** KP */

    	while($RecordFound)
    	{
    		$arrApplications 	= $this->ApplyOnlines->find('all',
															[	'fields' => ["ApplyOnlines.id","ApplyOnlines.project_id"],
																'join'=>[
																			['table'=>'installers','alias'=>'Installers','type'=>'INNER','conditions'=>'ApplyOnlines.installer_id = Installers.id']
																		],
																'conditions' => [	'ApplyOnlines.application_status NOT IN '=> array(99,29,30,22,0),
																					'ApplyOnlines.application_status IS NOT NULL',
																					'ApplyOnlines.id > ' => $LastAPPID],
																'order' => ['ApplyOnlines.id'=>'asc'],
																'limit' => 10
															])->toArray();
    		if (!empty($arrApplications)) {
    			foreach ($arrApplications as $arrApplication)
    			{
    				$LastAPPID 			= $arrApplication->id;
    				$PROJECT_ID 		= $arrApplication->project_id;

    				$connection         = ConnectionManager::get('default');
					$sql_icm 			= " SELECT apply_online_approvals.created
											FROM apply_online_approvals
											WHERE apply_online_approvals.application_id = $LastAPPID
											AND apply_online_approvals.stage = '1'
											ORDER BY apply_online_approvals.created
											LIMIT 1";
					$icm_output  		= $connection->execute($sql_icm)->fetchAll('assoc');
					$app_created_date 	= '';
					if(isset($icm_output[0]['created'])) {
						$app_created_date  = $icm_output[0]['created'];
					}

					$sql_icm 			= " SELECT created FROM apply_online_approvals
										    WHERE apply_online_approvals.application_id = $LastAPPID
										    AND apply_online_approvals.stage = '31'
										    group by stage";
					$icm_output  		= $connection->execute($sql_icm)->fetchAll('assoc');
					$submited_date 		= '';
					if(isset($icm_output[0]['created'])) {
						$submited_date  = $icm_output[0]['created'];
					}

					$sql_icm 			= " SELECT COUNT(0) AS CNT
							                FROM applyonlin_docs
							                WHERE applyonlin_docs.application_id = $LastAPPID
							                AND applyonlin_docs.doc_type = 'profile'";
					$icm_output  		= $connection->execute($sql_icm)->fetchAll('assoc');
					$profile_photo 		= 'No';
					if(isset($icm_output[0]['CNT'])) {
						$profile_photo  = ($icm_output[0]['CNT'] > 1)?'Yes':$profile_photo;
					}

					$sql_icm 			= " SELECT created
							                FROM apply_online_approvals
							                WHERE apply_online_approvals.application_id = $LastAPPID
							                AND apply_online_approvals.stage = 29
							                group by apply_online_approvals.stage";
					$icm_output  		= $connection->execute($sql_icm)->fetchAll('assoc');
					$otp_verified_on 	= '';
					if(isset($icm_output[0]['created'])) {
						$otp_verified_on  = $icm_output[0]['created'];
					}

					$sql_icm 				= " SELECT created
								                FROM apply_online_approvals
								                WHERE apply_online_approvals.application_id = $LastAPPID
								                AND apply_online_approvals.stage = 23";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$document_verified_date = '';
					if(isset($icm_output[0]['created'])) {
						$document_verified_date  = $icm_output[0]['created'];
					}

					$sql_icm 				= " SELECT CONCAT(SUBSTR(file_name,5,4),'-',SUBSTR(file_name,9,2),'-',SUBSTR(file_name,11,2),' ',
								                SUBSTR(file_name,13,2),':',SUBSTR(file_name,15,2),':',SUBSTR(file_name,17,2)) as signed_uploaded_date
								                FROM applyonlin_docs
								                WHERE applyonlin_docs.application_id = $LastAPPID
								                AND applyonlin_docs.doc_type = 'Signed_Doc'
								                group by doc_type";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$signed_uploaded_date 	= '';
					if(isset($icm_output[0]['signed_uploaded_date'])) {
						$signed_uploaded_date  = $icm_output[0]['signed_uploaded_date'];
					}

					$sql_icm 				= " SELECT concat(applyonline_messages.message,'|',applyonline_messages.created) as last_comment
								                FROM applyonline_messages
								                WHERE applyonline_messages.application_id = $LastAPPID
								                AND user_type = '6002'
								                ORDER BY applyonline_messages.id DESC
								                LIMIT 1";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$last_comment 			= '';
					$last_comment_date 		= '';
					if(isset($icm_output[0]['last_comment'])) {
						$last_comment_data  = explode("|",$icm_output[0]['last_comment']);
						if (!empty($last_comment_data[0])) {
							$last_comment 		= $last_comment_data[0];
							$last_comment_date 	= $last_comment_data[1];
						}
					}

					$sql_icm 					= " SELECT applyonline_messages.created
									                FROM applyonline_messages
									                WHERE applyonline_messages.application_id = $LastAPPID
									                AND user_type = '0'
									                ORDER BY applyonline_messages.id DESC
									                LIMIT 1";
					$icm_output  				= $connection->execute($sql_icm)->fetchAll('assoc');
					$last_comment_replied_date 	= '';
					if(isset($icm_output[0]['created'])) {
						$last_comment_replied_date  = $icm_output[0]['created'];
					}

					$sql_icm 				= " SELECT CONCAT(fesibility_report.created,'|',fesibility_report.quotation_number,'|',fesibility_report.estimated_amount,'|',fesibility_report.estimated_due_date,'|',IF (fesibility_report.payment_approve = 1,'Yes','No'),'|',fesibility_report.payment_date) as fesibility_report_data
								                FROM fesibility_report
								                WHERE fesibility_report.application_id = $LastAPPID
								                ORDER BY fesibility_report.id DESC LIMIT 1";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$fesibility_report_date	= '';
					$quotation_no 			= '';
					$discom_estimation 		= '';
					$payment_due_date 		= '';
					$payment_received 		= '';
					$payment_date 			= '';
					if(isset($icm_output[0]['fesibility_report_data'])) {
						$fesibility_report_data  = explode("|",$icm_output[0]['fesibility_report_data']);
						if (!empty($fesibility_report_data[0])) {
							$fesibility_report_date	= $fesibility_report_data[0];
							$quotation_no 			= $fesibility_report_data[1];
							$discom_estimation 		= $fesibility_report_data[2];
							$payment_due_date 		= $fesibility_report_data[3];
							$payment_received 		= $fesibility_report_data[4];
							$payment_date 			= $fesibility_report_data[5];
						}
					}

					$sql_icm 					= " SELECT COUNT(0) AS CNT
									                FROM applyonlin_docs
									                WHERE applyonlin_docs.application_id = $LastAPPID
									                AND applyonlin_docs.doc_type = 'Self_Certificate'
									                LIMIT 1";
					$icm_output  				= $connection->execute($sql_icm)->fetchAll('assoc');
					$self_certificate 			= 'No';
					if(isset($icm_output[0]['CNT'])) {
						$self_certificate  = ($icm_output[0]['CNT'] > 1)?'Yes':$self_certificate;
					}

					$sql_icm 					= " SELECT cei_application_details.drawing_app_no
									                FROM cei_application_details
									                WHERE cei_application_details.application_id = $LastAPPID
									                LIMIT 1";
					$icm_output  				= $connection->execute($sql_icm)->fetchAll('assoc');
					$drawing_app_no 			= '';
					if(isset($icm_output[0]['drawing_app_no'])) {
						$drawing_app_no  = $icm_output[0]['drawing_app_no'];
					}

					$sql_icm 					= " SELECT created
									                FROM apply_online_approvals
									                WHERE apply_online_approvals.application_id = $LastAPPID
									                AND apply_online_approvals.stage = 9
									                LIMIT 1";
					$icm_output  				= $connection->execute($sql_icm)->fetchAll('assoc');
					$drawing_approved_date 		= '';
					if(isset($icm_output[0]['drawing_approved_date'])) {
						$drawing_approved_date  = $icm_output[0]['drawing_approved_date'];
					}

					$sql_icm 				= " SELECT concat(workorder_number,'|',workorder_date) as workorder_data
								                FROM project_workorder
								                WHERE project_workorder.project_id = $PROJECT_ID
								                LIMIT 1";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$workorder_number 		= '';
					$workorder_number_date 	= '';
					if(isset($icm_output[0]['workorder_data'])) {
						$workorder_data  = explode("|",$icm_output[0]['workorder_data']);
						if (!empty($workorder_data[0])) {
							$workorder_number 		= $workorder_data[0];
							$workorder_number_date 	= $workorder_data[1];
						}
					}

					$sql_icm 				= " SELECT CONCAT(start_date,'|',end_date,'|',meter_manufacture,'|',meter_serial_no,'|',solar_meter_manufacture,'|',solar_meter_serial_no) as installation_data
								                FROM project_installation
								                WHERE project_installation.project_id = $PROJECT_ID
								                LIMIT 1";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$installation_start_date= '';
					$installation_end_data 	= '';
					$meter_serial_no_make 	= '';
					$meter_serial_no 		= '';
					$solar_meter_manufacture= '';
					$solar_meter_serial_no 	= '';
					if(isset($icm_output[0]['installation_data'])) {
						$installation_data  = explode("|",$icm_output[0]['installation_data']);
						if (!empty($installation_data[0])) {
							$installation_start_date= $installation_data[0];
							$installation_end_data 	= $installation_data[1];
							$meter_serial_no_make 	= $installation_data[2];
							$meter_serial_no 		= $installation_data[3];
							$solar_meter_manufacture= $installation_data[4];
							$solar_meter_serial_no 	= $installation_data[5];
						}
					}

					$sql_icm 				= " SELECT concat(meter_installed_date,'|',agreement_date) as meter_installed_data
								                FROM charging_certificate
								                WHERE charging_certificate.application_id = $LastAPPID
								                LIMIT 1";
					$icm_output  			= $connection->execute($sql_icm)->fetchAll('assoc');
					$meter_installed_date 	= '';
					$agreement_date 		= '';
					if(isset($icm_output[0]['meter_installed_data'])) {
						$meter_installed_data  = explode("|",$icm_output[0]['meter_installed_data']);
						if (!empty($meter_installed_data[0])) {
							$meter_installed_date 	= $meter_installed_data[0];
							$agreement_date 		= $meter_installed_data[1];
						}
					}

					$MISReportDataID 	= $this->MISReportData->find('all',['fields'=>['id'],
																			'conditions'=>['application_id'=>$LastAPPID]])->first();
					if (!empty($MISReportDataID)) {
						$MISReportDataID->id 		= $MISReportDataID->id;
					} else {
						$MISReportDataID 			= $this->MISReportData->newEntity();
						$MISReportDataID->created 	= date("Y-m-d H:i:s");
					}
					$MISReportDataID->application_id 			= $LastAPPID;
					$MISReportDataID->app_created_date 			= $app_created_date;
					$MISReportDataID->submited_date 			= $submited_date;
					$MISReportDataID->profile_photo 			= $profile_photo;
					$MISReportDataID->otp_verified_on 			= $otp_verified_on;
					$MISReportDataID->document_verified_date 	= $document_verified_date;
					$MISReportDataID->signed_uploaded_date 		= $signed_uploaded_date;
					$MISReportDataID->last_comment 				= $last_comment;
					$MISReportDataID->last_comment_date 		= $last_comment_date;
					$MISReportDataID->last_comment_replied_date = $last_comment_replied_date;
					$MISReportDataID->fesibility_report_date 	= $fesibility_report_date;
					$MISReportDataID->quotation_no 				= $quotation_no;
					$MISReportDataID->discom_estimation 		= $discom_estimation;
					$MISReportDataID->payment_due_date 			= $payment_due_date;
					$MISReportDataID->payment_received 			= $payment_received;
					$MISReportDataID->payment_date 				= $payment_date;
					$MISReportDataID->self_certificate 			= $self_certificate;
					$MISReportDataID->drawing_app_no 			= $drawing_app_no;
					$MISReportDataID->drawing_approved_date 	= $drawing_approved_date;
					$MISReportDataID->workorder_number 			= $workorder_number;
					$MISReportDataID->workorder_number_date 	= $workorder_number_date;
					$MISReportDataID->installation_start_date 	= $installation_start_date;
					$MISReportDataID->meter_serial_no_make 		= $meter_serial_no_make;
					$MISReportDataID->installation_end_data 	= $installation_end_data;
					$MISReportDataID->meter_serial_no 			= $meter_serial_no;
					$MISReportDataID->solar_meter_manufacture 	= $solar_meter_manufacture;
					$MISReportDataID->solar_meter_serial_no 	= $solar_meter_serial_no;
					$MISReportDataID->solar_meter_serial_no 	= $solar_meter_serial_no;
					$MISReportDataID->meter_installed_date 		= $meter_installed_date;
					$MISReportDataID->agreement_date 			= $agreement_date;
					$MISReportDataID->modified 					= date("Y-m-d H:i:s");
					$this->MISReportData->save($MISReportDataID);
				}
    		} else {
    			$RecordFound = false;
    			break;
    		}
    	}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}
