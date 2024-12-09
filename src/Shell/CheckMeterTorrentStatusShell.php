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

class CheckMeterTorrentStatusShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('ChargingCertificate');
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
            $LIMIT_QUERY_SENT         = 1500;
        }
        $LastProcessedApplicationID   = 0;
        //
        $application_status           = array(  $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED,$this->ApplyOnlineApprovals->WORK_STARTS, $this->ApplyOnlineApprovals->WORK_EXECUTED,$this->ApplyOnlineApprovals->APPROVED_FROM_CEI);
        $LastRowID                    = $this->CronApiProcess->GetLastRowID("check_t_meter_report");
        if (!empty($LastRowID)) 
        {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'apply_state'=>$GUJARAT_STATE,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat,$this->ApplyOnlines->torent_dahej),
                                    'id > '=>$LastRowID];
        } else {
            $arrConditions      = [ 'application_status IN '=>$application_status,
                                    'ApplyOnlines.discom IN '=>array($this->ApplyOnlines->torent_ahmedabad,$this->ApplyOnlines->torent_surat,$this->ApplyOnlines->torent_dahej),
                                    'apply_state'=>$GUJARAT_STATE];
        }
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                                [   'fields'        => ["id"],
                                                                    'conditions'    => $arrConditions,
                                                                    'limit'         => $LIMIT_QUERY_SENT,
                                                                    'order'         => 'ApplyOnlines.id ASC',
                                                                ]
                                                        );
        $FetchedRowCount = $arrApplications->count();
        if (!empty($FetchedRowCount))
        {
			foreach($arrApplications as $arrApplication)
			{
                $LastProcessedApplicationID = $arrApplication->id;
				$this->ChargingCertificate->fetchApiMeterInstallation($arrApplication->id);
                $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_t_meter_report");
			}

		} else {
            $this->CronApiProcess->saveAPILog($LastProcessedApplicationID,"check_t_meter_report");
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}