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
class CSVMISReportShell extends Shell
{
	var $defaults 		= array('extension' => '.csv', 'excelName' => 'ExcelSheet', 'sheet1Name' => 'Sheet1');
	var $BunchSize 		= 3000;
	var $LIMIT_ROWS		= false;
	var $connection 	= "";
	var $PREFIX			= "";
	public function initialize()
	{
		parent::initialize();
		$this->loadModel('Installers');
		$this->loadModel('SitesurveyProjectRequest');
		$this->loadModel('Customers');
		$this->loadModel('Projects');
		$this->loadModel('SiteSurveys');
		$this->loadModel('InstallerProjects');
		$this->loadModel('SiteSurveysImages');
		$this->loadModel('InstallerSubscription');
		$this->loadModel('MISReportData');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('BranchMasters');
		$this->loadModel('DiscomMaster');
	}

	public function main()
	{
		ini_set('memory_limit', '-1');
		ini_set('max_execution_time', '-1');
		set_time_limit(0);

		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
		/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
        /** KP */
		$this->connection       = ConnectionManager::get('default');

		$branchMasterData 		= $this->BranchMasters->find('all',array('conditions'=>array('status'=>'1'),'order'=>array('discom_id'=>'desc')))->toArray();
		$documentArr 			= array();
		$finalData 				= array();
		foreach($branchMasterData as $valBranch)
		{
			$discom_short_name     	= $this->DiscomMaster->find("all",['conditions'=>['id'=>$valBranch->discom_id]])->first();
			$shortName 				= $valBranch->discom_id;
			if(!empty($discom_short_name)) {
				$shortName 			= str_replace(array(" "),array("_"),$discom_short_name->title);
			}
			$this->GenerateMISReport($shortName,$valBranch->discom_id); //GENERATE DISCOM WISE
		}
		$this->GenerateMISReport(); //GenerateForGUVNL
		$DIR 		= ROOT . DS .'webroot'.DS.MISREPORT_PATH;
		$command 	= "find ".$DIR." -name '*MISReport*.zip' -type f -mtime +1 -exec rm -f {} \;";
		system($command);
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}

	private function GenerateMISReport($shortName="",$discom_id=0)
	{
		$RecordFound 			= true;
		$BunchSize				= $this->BunchSize;
		$StartRecord			= 0;
		$shortName 				= (!empty($shortName)?"_".$shortName:"");
		$fileName 				= $this->PREFIX.date("Ymd").'MISReport'.$shortName;
		$csvfilepath        	= ROOT . DS .'webroot'.DS.MISREPORT_PATH.$fileName. $this->defaults['extension'];
		$this->MISReportData->arrReportFields = array_merge($this->MISReportData->arrReportFields,array('pv_dc_capacity'=>'PV DC Capacity','govt_agency'=>'Government','msme'=>'MSME','geda_letter'=>'GEDA Letter Date'));
		$arrRequestSelected     = array_keys($this->MISReportData->arrReportFields);
		$sql_first              = $this->MISReportData->GetReportFields($arrRequestSelected);
		$sql                    = $this->MISReportData->MISQueryStr();
		if (!empty($discom_id)) {
			$sql .= " and AO.area='".$discom_id."' order by app_created_date desc, AO.id desc ";
		} else {
			$sql .= " order by app_created_date desc, AO.id desc ";
		}
		if (file_exists($csvfilepath)) {
			unlink($csvfilepath);
		}
		while ($RecordFound)
		{
			$LIMIT = " LIMIT $StartRecord,$BunchSize";
			echo "\r\n LIMIT ==> ".$LIMIT."\r\n";
			$applicationData = $this->connection->execute($sql_first.$sql.$LIMIT)->fetchAll('assoc');
			if (!empty($applicationData))
			{
				if (!file_exists($csvfilepath)) {
					$HeaderString 	= "";
					$seperator		= "";
					foreach ($this->MISReportData->ExportFields as $Field_Name) {
						$ColTitle   	= isset($this->MISReportData->arrReportFields[$Field_Name])?$this->MISReportData->arrReportFields[$Field_Name]:"";
						$HeaderString 	.= $seperator.$ColTitle;
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
				$RowID 		= ($StartRecord > 0)?($StartRecord+1):1;
				foreach($applicationData as $key=>$Report_Data) {
					$seperator 		= "";
					$RowStringData 	= "";
					foreach ($this->MISReportData->ExportFields as $Field_Name) {
						$RowData = "";
						switch ($Field_Name) {
							case 'sr_no':
								$RowData = $RowID;
								break;
							case 'application_status':
								$RowData = isset($this->ApplyOnlineApprovals->application_status[$Report_Data[$Field_Name]])?$this->ApplyOnlineApprovals->application_status[$Report_Data[$Field_Name]]:"";
								break;
							case 'govt_agency':
								$RowData = ($Report_Data[$Field_Name] == 1) ? 'YES' : 'NO';
								break;
							case 'msme':
			    				$RowData = ($Report_Data[$Field_Name] == 1) ? 'YES' : 'NO';
								break;
							case 'District':
							$DistrictQuery 	 = "select * from district_name_mapping 
									LEFT JOIN district_master as DMS ON district_name_mapping.district_code = DMS.district_code where district_name='".addslashes($Report_Data[$Field_Name])."'";
							$disData 		= $this->connection->execute($DistrictQuery)->fetchAll('assoc');
							$district_name 	= '';
							if(!empty($disData)) {
								$district_name 	= $disData[0]['name'];
							}
							$RowData = $district_name;
							break;
							case 'geda_letter':
								$gedaLetterDate = $this->ApplyOnlineApprovals->getgedaletterStageDataMIS($Report_Data['id']);
								$RowData = (!empty($gedaLetterDate->created) ? date(LIST_DATE_FORMAT,(strtotime($gedaLetterDate->created))) : '');
							break;
							
							default:
								$RowData = isset($Report_Data[$Field_Name])?$Report_Data[$Field_Name]:"";
								break;
						}
						if ($RowData == "0000-00-00 00:00:00" || $RowData == "0000-00-00" || $RowData == '1970-01-01' || $RowData == '1970-01-01 00:00:00') {
							$RowData = "";
						}
						$RowData 		= str_replace(array('\n','\r\n','"'), array(" "," "), $RowData);
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
					$ZipCommand     = "chmod 777 ".$ZipFileName;
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
					$ZipCommand     = "chmod 777 ".$ZipFileName;
					system($ZipCommand);
					system($ZipCommand);
					unlink($csvfilepath);
				}
			}
			$StartRecord = $StartRecord + $BunchSize;
		}
	}
}