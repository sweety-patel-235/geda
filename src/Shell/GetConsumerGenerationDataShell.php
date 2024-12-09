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

class GetConsumerGenerationDataShell extends Shell
{

	public function initialize()
	{
		parent::initialize();
		$this->loadModel('ApplyOnlines');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('CronApiProcess');
		$this->loadModel('EnergyGenerationLog');
	}

	public function main()
	{
		ini_set("memory_limit","-1");
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$CurrentHour				= date("H");
		$LastProcessedRequestID		= 0;
		$LIMIT_QUERY_SENT			= 4000;
		$ApplicationID 				= "";
		$ConsumerNo 				= "";
		$GEN_YEAR 					= "";
		$DISCOM 					= "";
		$application_status 		= array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
		$LastRowID 					= $this->CronApiProcess->GetLastRowID("get_consumer_generation_data");
		$arrConditions 				= ['ApplyOnlines.application_status IN '=>$application_status];
		if (!empty($LastRowID)) {
		   array_push($arrConditions,array('ApplyOnlines.id > '=>$LastRowID));
		} else if (!empty($ApplicationID)) {
			array_push($arrConditions,array('ApplyOnlines.id'=>$LastRowID));
		} else if (!empty($ConsumerNo)) {
			array_push($arrConditions,array('ApplyOnlines.consumer_no'=>$ConsumerNo));
		}
		$arrApplications    = $this->ApplyOnlines->find('all',
															[   'fields'		=> ["ApplyOnlines.id","ApplyOnlines.discom","ApplyOnlines.consumer_no"],
																'conditions'	=> $arrConditions,
																'limit'			=> $LIMIT_QUERY_SENT,
																'order' 		=> 'ApplyOnlines.id ASC',
															]);
		$FetchedRowCount	= $arrApplications->count();
		echo "Total FetchedRowCount ==> ".$FetchedRowCount."\r\n";
		$ProcessedApplicationCnt = 0;
		if (!empty($FetchedRowCount))
		{
			foreach($arrApplications as $arrApplication)
			{
				$LastProcessedRequestID = $arrApplication->id;
				$GEN_YEAR 				= date("Y");

				$this->ThirdpartyApiLog->getConsumerGenerationData($arrApplication->discom,$arrApplication->consumer_no,$GEN_YEAR);
				$this->CronApiProcess->saveAPILog($LastProcessedRequestID,"get_consumer_generation_data");

				$API_RESPONSE = $this->ThirdpartyApiLog->Api_Response;
				if (isset($API_RESPONSE->P_OUT_STS_CD) && $API_RESPONSE->P_OUT_STS_CD == 1)
				{
					$ApplicationID 	= $arrApplication->id;
					$ConsumerNo 	= $arrApplication->consumer_no;
					$P_OUT_DATA 	= $API_RESPONSE->P_OUT_DATA;
					$P_OUT_DATA 	= json_decode(json_encode($P_OUT_DATA),true);
					if (!empty($P_OUT_DATA))
					{
						for($mon=1;$mon<=12;$mon++)
						{
							$BILL_MONTH_YEAR 	= isset($P_OUT_DATA['BILL_MON_YEAR_'.$mon])?$P_OUT_DATA['BILL_MON_YEAR_'.$mon]:"";
							$TARIFF 			= isset($P_OUT_DATA['TARIFF_'.$mon])?$P_OUT_DATA['TARIFF_'.$mon]:"";
							$METER_NO 			= isset($P_OUT_DATA['METER_NO_'.$mon])?$P_OUT_DATA['METER_NO_'.$mon]:"";
							$GENERATION 		= isset($P_OUT_DATA['GENERATION_'.$mon])?$P_OUT_DATA['GENERATION_'.$mon]:"";
							if (!empty($BILL_MONTH_YEAR) && !empty($GENERATION))
							{
								$TEMP 			= explode("-",$BILL_MONTH_YEAR);
								$MONTH 			= $TEMP[0];
								$YEAR 			= $TEMP[1];
								$START_DATE		= $YEAR."-".$MONTH."-01";
								$END_DATE		= date("Y-m-t",strtotime($YEAR."-".$MONTH."-01"));
								$arrConditions 	= array("application_id",$ApplicationID,"month"=>$MONTH,"year"=>$YEAR);
								$ExistingRow 	= $this->EnergyGenerationLog->find('all',['fields'=>["id"],'conditions'=>$arrConditions])->first();
								if (empty($ExistingRow))
								{
									$EnergyGenerationLog 					= $this->EnergyGenerationLog->newEntity();
									$EnergyGenerationLog->application_id	= $ApplicationID;
									$EnergyGenerationLog->consumer_no		= $ConsumerNo;
									$EnergyGenerationLog->category			= $TARIFF;
									$EnergyGenerationLog->month				= $MONTH;
									$EnergyGenerationLog->year				= $YEAR;
									$EnergyGenerationLog->meter_no			= $METER_NO;
									$EnergyGenerationLog->start_date		= $START_DATE;
									$EnergyGenerationLog->end_date			= $END_DATE;
									$EnergyGenerationLog->generation		= $GENERATION;
									$EnergyGenerationLog->created			= date("Y-m-d H:i:s");
									$this->EnergyGenerationLog->save($EnergyGenerationLog);
								} else {
									$this->EnergyGenerationLog->updateAll(['generation'=>$GENERATION],['id' => $ExistingRow->id]);
								}
							}
						}
					}
				}
				$ProcessedApplicationCnt++;
			}
		}
		else
		{
			$this->CronApiProcess->saveAPILog($LastProcessedRequestID,"get_consumer_generation_data");
		}
		echo "\r\nTotal Processed Application Cnt ==> ".$ProcessedApplicationCnt."\r\n";
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}
}