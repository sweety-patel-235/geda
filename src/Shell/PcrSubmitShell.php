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

class PcrSubmitShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('SpinWebserviceApi');
        $this->loadModel('CronApiProcess');
    }

    public function main()
    {
        ini_set("memory_limit","-1");
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent                 = 1;
        $GUJARAT_STATE              = 4;
        $CurrentHour                = date("H");
        $LastProcessedApplicationID = 0;
        
        $LIMIT_QUERY_SENT           = 200; 
        
        $ApplyOnlines       = $this->ApplyOnlines->find();
        
        $application_status = array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
        $LastRowID          = $this->CronApiProcess->GetLastRowID("check_pcr_report");
        $arrConditions      = [ 'application_status IN '=>$application_status,
                                'pcr_code IS NULL',
                                'subsidy_claim_request_applications.received_at'=>'1',
                                'apply_state'=>$GUJARAT_STATE];
        if (!empty($LastRowID)) 
        {
           // array_push($arrConditions,array('ApplyOnlines.id > '=>$LastRowID));
        }
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                            [   'fields'        => ["ApplyOnlines.id",
                                                                                    "ApplyOnlines.pcr_code",
                                                                                    "ApplyOnlines.pv_capacity"],
                                                                'join'          => [
                                                                    [   'table'=>'subsidy_claim_request_applications',
                                                                        'type'=>'INNER',
                                                                        'conditions'=>'subsidy_claim_request_applications.application_id = ApplyOnlines.id'
                                                                    ]
                                                                ],
                                                                'conditions'    => $arrConditions,
                                                                'limit'         => $LIMIT_QUERY_SENT,
                                                                'order'         => 'subsidy_claim_request_applications.received_date ASC',
                                                            ]
                                                    );
        $FetchedRowCount    = $arrApplications->count();

        echo "Total FetchedRowCount ==> ".$FetchedRowCount."\r\n";
        $ProcessedApplicationCnt = 0;
        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $arrApplication)
			{
                if(empty($arrApplication->pcr_code))
                {
                    $LastProcessedApplicationID = $arrApplication->id;
                    $TotalCapacityData          = $ApplyOnlines->select(['TotalCapacity' => $ApplyOnlines->func()->sum('ApplyOnlines.pv_capacity')])->where(['apply_state' => $GUJARAT_STATE,
                        'approval_id' => SPIN_APPROVAL_ID])->toArray();
                    if(($TotalCapacityData[0]['TotalCapacity']+$arrApplication->pv_capacity)<SPIN_APPROVED_CAPACITY)
                    {
                        $LastProcessedApplicationID = $arrApplication->id;
    			        $this->SpinWebserviceApi->pcr_submit($arrApplication->id);  
                        $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_pcr_report");
                        $ProcessedApplicationCnt++;
                    }
                    else
                    {
                        echo "Total Capacity ".$TotalCapacityData[0]['TotalCapacity']." Crossed ".SPIN_APPROVED_CAPACITY." limit";
                    }
                }
			}
		}
        else
        {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_pcr_report");
        }
        echo "\r\nTotal Processed Application Cnt ==> ".$ProcessedApplicationCnt."\r\n";
        
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}