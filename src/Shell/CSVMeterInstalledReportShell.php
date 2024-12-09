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
use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Session;
use Cake\View\Helper;
use Cake\Core\App;
use Cake\View\Helper\SessionHelper;
use Cake\View\Helper\Userright;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Dompdf\Dompdf;
class CSVMeterInstalledReportShell extends Shell
{
	var $defaults 		= array('extension' => '.csv', 'excelName' => 'ExcelSheet', 'sheet1Name' => 'Sheet1');
	var $BunchSize 		= 3000;
	var $LIMIT_ROWS		= false;
	var $connection 	= "";
	var $PREFIX			= "";
	public function initialize()
	{
		parent::initialize();
		$this->loadModel('BranchMasters');
		$this->loadModel('DiscomMaster');
		$this->loadModel('Installation');
		$this->loadModel('Subsidy');
	}

	public function main()
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '-1');
		set_time_limit(0);
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		$this->connection = ConnectionManager::get('default');
		$this->GenerateMeterInstalledReport();
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}

	private function GenerateMeterInstalledReport($shortName="")
	{
		$RecordFound 			= true;
		$BunchSize				= $this->BunchSize;
		$MAKE_INVERTERS 		= $this->Installation->MAKE_INVERTERS;
		$StartRecord			= 0;
		$shortName 				= (!empty($shortName)?"_".$shortName:"");
		$fileName 				= $this->PREFIX.'CSVMeterInstalled'.$shortName;
		$csvfilepath        	= ROOT . DS .'webroot'.DS.MISREPORT_PATH.$fileName.$this->defaults['extension'];
		$SelectSql 				= "	SELECT apply_onlines.id as ApplicationID,
									apply_onlines.geda_application_no as 'Registeration No.',
									installers.installer_name as 'Installer Name',
									installer_category.category_name as 'Installer Category',
									discom_master.title as 'Discom',
									discom_circle.title as 'Circle',
									discom_division.title as 'Division/Zone',
									discom_subdivision.title as 'Sub-division',
									apply_onlines.consumer_no as 'Consumer No.',
									apply_onlines.consumer_mobile as 'Consumer Mobile',
									CONCAT(apply_onlines.customer_name_prefixed, ' ', apply_onlines.name_of_consumer_applicant,' ',apply_onlines.last_name, ' ', apply_onlines.third_name) as 'Consumer Name',
									DATE_FORMAT(mis_report_data.meter_installed_date,'%Y-%m-%d') as 'Date of Installation of Solar Meter',
									apply_onlines.original_capacity as 'Applied PV Capacity',
									apply_onlines.pv_capacity as 'PV Capacity',
									apply_onlines_others.existing_capacity as 'Existing PV Capacity (kW)',
									'' AS 'Inverter Name',
									'' AS 'Inverter Capacity',
									'' AS 'PV Module Name',
									'' AS 'PV Module Capacity',
									project_installation.modules_data AS modules_data,
									project_installation.inverter_data AS inverter_data
									FROM project_installation
									LEFT JOIN projects ON projects.id = project_installation.project_id
									LEFT JOIN apply_onlines ON projects.id = apply_onlines.project_id
									LEFT JOIN mis_report_data ON mis_report_data.application_id = apply_onlines.id
									LEFT JOIN installers ON installers.id = apply_onlines.installer_id
									LEFT JOIN installer_category_mapping ON installers.id = installer_category_mapping.installer_id
									LEFT JOIN installer_category ON installer_category.id = installer_category_mapping.category_id
									LEFT JOIN district_master ON district_master.id = apply_onlines.district
									LEFT JOIN branch_masters ON branch_masters.id = apply_onlines.discom
									LEFT JOIN discom_master ON branch_masters.discom_id = discom_master.id
									LEFT JOIN discom_master as discom_circle ON discom_circle.id = apply_onlines.circle
									LEFT JOIN discom_master as discom_division ON discom_division.id = apply_onlines.division
									LEFT JOIN discom_master as discom_subdivision ON discom_subdivision.id = apply_onlines.subdivision
									LEFT JOIN apply_onlines_others ON apply_onlines_others.application_id = apply_onlines.id
									WHERE apply_onlines.geda_application_no != ''
									ORDER BY apply_onlines.id ASC";
		if (file_exists($csvfilepath)) {
			unlink($csvfilepath);
		}
		while ($RecordFound)
		{
			$TempFieldArray = array("Sr No.");
			$LIMIT = " LIMIT $StartRecord,$BunchSize";
			echo "\r\n LIMIT ==> ".$LIMIT."\r\n";
			$applicationData = $this->connection->execute($SelectSql.$LIMIT)->fetchAll('assoc');
			if (!empty($applicationData))
			{
				$ReportFields = array_keys($applicationData[0]);
				$ExportFields = array_merge($TempFieldArray,$ReportFields);
				unset($ExportFields[20]);
				unset($ExportFields[21]);
				if (!file_exists($csvfilepath)) {
					$HeaderString 	= "";
					$seperator		= "";
					foreach ($ExportFields as $Field_Name) {
						$HeaderString 	.= $seperator.$Field_Name;
						$seperator      = ",";
					}
					echo "\r\n csvfilepath ==> ".basename($csvfilepath)."\r\n";
					$HeaderString = rtrim($HeaderString,",");
					$HeaderString = $HeaderString."\r\n";
					$fp = fopen($csvfilepath,"w+");
					fwrite($fp,$HeaderString);
					fclose($fp);
					chmod($csvfilepath,0777);
					$HeaderString = "";
				}
				$RowString 	= "";
				$RowID 		= ($StartRecord > 0)?$StartRecord:1;
				foreach($applicationData as $key=>$Report_Data)
				{
					$seperator 			= "";
					$RowStringData 		= "";
					$MODULES_DATA 		= !empty($Report_Data['modules_data'])?unserialize($Report_Data['modules_data']):"";
					$ARR_MODULES_DATA 	= array("MAKE"=>"","CAPACITY"=>0);
					if (!empty($MODULES_DATA)) {
						foreach ($MODULES_DATA as $MODULE) {
							if (isset($MODULE['m_make']) && !empty($MODULE['m_make'])) {
								$ARR_MODULES_DATA['MAKE'] .= "|".$MODULE['m_make'];
							}
							if (isset($MODULE['m_capacity']) && !empty($MODULE['m_capacity']) && isset($MODULE['m_modules']) && !empty($MODULE['m_modules'])) {
								$CAPACITY 						= (floatval($MODULE['m_capacity']) * floatval($MODULE['m_modules']));
								$ARR_MODULES_DATA['CAPACITY'] += $CAPACITY;
							}
						}
						if (!empty($ARR_MODULES_DATA['CAPACITY'])) {
							$ARR_MODULES_DATA['CAPACITY'] = round(($ARR_MODULES_DATA['CAPACITY']/1000),3);
						}
						$ARR_MODULES_DATA['MAKE'] = trim($ARR_MODULES_DATA['MAKE'],"|");
					}
					$INVERTER_DATA 		= !empty($Report_Data['inverter_data'])?unserialize($Report_Data['inverter_data']):"";
					$ARR_INVERTER_DATA 	= array("MAKE"=>"","CAPACITY"=>0);
					if (!empty($INVERTER_DATA)) {
						foreach ($INVERTER_DATA as $INVERTER) {
							if (isset($INVERTER['i_make']) && !empty($INVERTER['i_make'])) {
								if ($INVERTER['i_make'] == 27 && !empty($INVERTER['i_make_other'])) {
									$ARR_INVERTER_DATA['MAKE'] .= "|".$INVERTER['i_make_other'];
								} else if (isset($MAKE_INVERTERS[$INVERTER['i_make']])) {
									$ARR_INVERTER_DATA['MAKE'] .= "|".$MAKE_INVERTERS[$INVERTER['i_make']];
								}
							}
							if (isset($INVERTER['i_capacity']) && !empty($INVERTER['i_capacity']) && isset($INVERTER['i_modules']) && !empty($INVERTER['i_modules'])) {
								$CAPACITY 						= (floatval($INVERTER['i_capacity']) * floatval($INVERTER['i_modules']));
								$ARR_INVERTER_DATA['CAPACITY'] += $CAPACITY;
							}
						}
						if (!empty($ARR_INVERTER_DATA['CAPACITY'])) {
							$ARR_INVERTER_DATA['CAPACITY'] = round(($ARR_INVERTER_DATA['CAPACITY']),3);
						}
						$ARR_INVERTER_DATA['MAKE'] = trim($ARR_INVERTER_DATA['MAKE'],"|");
					}
					foreach ($ExportFields as $Field_Name) {
						$RowData 		= "";
						switch ($Field_Name) {
							case 'Sr No.': {
								$RowData = $RowID;
								break;
							}
							case 'Inverter Name': {
								$INVERTER_NAME 	= !empty($ARR_INVERTER_DATA['MAKE'])?$ARR_INVERTER_DATA['MAKE']:"-";
								$RowData 		= trim($INVERTER_NAME);
								break;
							}
							case 'Inverter Capacity': {
								$INVERTER_CAPACITY 	= !empty($ARR_INVERTER_DATA['CAPACITY'])?$ARR_INVERTER_DATA['CAPACITY']:"-";
								$RowData 			= $INVERTER_CAPACITY;
								break;
							}
							case 'PV Module Name': {
								$MODULE_NAME 	= !empty($ARR_MODULES_DATA['MAKE'])?$ARR_MODULES_DATA['MAKE']:"-";
								$RowData 		= trim($MODULE_NAME);
								break;
							}
							case 'PV Module Capacity': {
								$MODULE_CAPACITY 	= !empty($ARR_MODULES_DATA['CAPACITY'])?$ARR_MODULES_DATA['CAPACITY']:"-";
								$RowData 			= $MODULE_CAPACITY;
								break;
							}
							default: {
								$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
								break;
							}
						}
						if ($RowData == "0000-00-00 00:00:00" || $RowData == "0000-00-00") {
							$RowData = "";
						}
						$RowStringData .= $seperator.'"'.$RowData.'"';
						$seperator 	= ",";
					}
					$RowID++;
					$RowStringData = rtrim($RowStringData,",");
					$RowString .= $RowStringData."\r\n";
				}
				$fp = fopen($csvfilepath,"a+");
				fwrite($fp,$RowString);
				fclose($fp);
				$RowString = "";
			} else {
				$RecordFound = false;
				$ZipFileName    = WWW_ROOT.MISREPORT_PATH.$fileName.".zip";
				$ZipSource      = $csvfilepath;
				if(file_exists($ZipSource))
				{
					if (file_exists($ZipFileName)) unlink($ZipFileName);
					echo "\r\n--ZipFileName:: ".$ZipFileName."--\r\n";
					$ZipCommand     = "zip -j ".$ZipFileName." ".$csvfilepath;
					echo $ZipCommand;
					system($ZipCommand);
					unlink($csvfilepath);
				}
			}
			if ($this->LIMIT_ROWS) {
				$RecordFound 	= false;
				$ZipFileName    = WWW_ROOT.MISREPORT_PATH.$fileName.".zip";
				$ZipSource      = $csvfilepath;
				if(file_exists($ZipSource))
				{
					if (file_exists($ZipFileName)) unlink($ZipFileName);
					echo "\r\n--ZipFileName:: ".$ZipFileName."--\r\n";
					$ZipCommand     = "zip -j ".$ZipFileName." ".$csvfilepath;
					echo $ZipCommand;
					system($ZipCommand);
					unlink($csvfilepath);
				}
			}
			$StartRecord = $StartRecord + $BunchSize;
		}
	}
}