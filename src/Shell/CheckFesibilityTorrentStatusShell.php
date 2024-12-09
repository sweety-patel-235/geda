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

class CheckFesibilityTorrentStatusShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
        $this->loadModel('FesibilityReport');
		$this->loadModel('CronApiProcess');
    }

    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent 		          = 1;
        $GUJARAT_STATE 		          = 4;
        $CurrentHour                  = date("H");
        if ($CurrentHour > 8 && $CurrentHour < 20) {
            $LIMIT_QUERY_SENT         = 500; 
        } else {
            $LIMIT_QUERY_SENT	      = 1500;
        }
        
        $LastProcessedApplicationID   = 0;
        $application_status = array($this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,
                                    $this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,
                                    $this->ApplyOnlineApprovals->FEASIBILITY_APPROVAL,
                                    $this->ApplyOnlineApprovals->SUBSIDY_AVAILIBILITY,
                                    $this->ApplyOnlineApprovals->WORK_STARTS,
                                    $this->ApplyOnlineApprovals->APPROVED_FROM_CEI,
                                    $this->ApplyOnlineApprovals->METER_INSTALLATION,
                                    $this->ApplyOnlineApprovals->DRAWING_APPLIED,
                                    $this->ApplyOnlineApprovals->WORK_EXECUTED,
                                    $this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED,
                                    $this->ApplyOnlineApprovals->APPROVED_FROM_DISCOM,
                                    $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED);
        $LastRowID          = $this->CronApiProcess->GetLastRowID("check_to_fe_report");
        if (!empty($LastRowID)) 
        {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.id > '=>$LastRowID,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    ['OR'=>['payment_approve'=>'0','payment_approve IS NULL']]];
        } else {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat),
                                    ['OR'=>['payment_approve'=>'0','payment_approve IS NULL']]];
        }
        $arrApplications 	= $this->ApplyOnlines->find('all',
                                                                [   'fields'        => ["ApplyOnlines.id"],
                                                                    'join'          => ['table'           =>'fesibility_report',
                                                                        'type'=>'left',
                                                                        'conditions'=>'fesibility_report.application_id = ApplyOnlines.id'
                                                                    ],
                                                                    'conditions'    => $arrConditions,
                                                                    'limit'         => $LIMIT_QUERY_SENT,
                                                                    'order'         => 'ApplyOnlines.id ASC',
                                                                ]
                                                        );
        $FetchedRowCount = $arrApplications->count();
        //echo "\r\n--FetchedRowCount::".$FetchedRowCount."--\r\n";
        echo "\r\n--arrConditions::".json_encode($arrConditions)."--\r\n";
        //die;
        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $rowid=>$arrApplication)
			{
                $allStages  = $this->ApplyOnlineApprovals->Approvalstage($arrApplication->id);
                if(in_array($this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,$allStages))
                {
                    echo "\r\n--rowid::".$rowid."--\r\n";
                    $LastProcessedApplicationID = $arrApplication->id;
                    $this->FesibilityReport->fetchApiFeasibility($arrApplication->id,true);
                    $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_to_fe_report");
                }
			}
            echo "\r\n--LastProcessedApplicationID::".$LastProcessedApplicationID."--\r\n";
		} else {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_to_fe_report");
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}