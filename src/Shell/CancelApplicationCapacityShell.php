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

class CancelApplicationCapacityShell extends Shell
{
	var $slots  = array("1" => array("min"=>1.00,"max"=>6.00),
                        "2" => array("min"=>6.01,"max"=>10.00),
                        "3" => array("min"=>10.01,"max"=>50.00),
                        "4" => array("min"=>50.01,"max"=>50000.00)
                    	);
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('FesibilityReport');
		$this->loadModel('Installers');
		$this->loadModel('InstallerCategoryMapping');
    }
    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        
        $arrApplications 	= $this->ApplyOnlines->find('all',
										[
											'fields'  		=> [
																"ApplyOnlines.id",
																"ApplyOnlines.installer_id",
																"ApplyOnlines.mobile",
																"ApplyOnlines.email",
																"ApplyOnlines.name_of_consumer_applicant",
																"ApplyOnlines.last_name",
																"ApplyOnlines.geda_application_no",
																"ApplyOnlines.application_no",
																"ApplyOnlines.installer_email",
																"ApplyOnlines.consumer_mobile",
																"ApplyOnlines.installer_mobile",
																"ApplyOnlines.pv_capacity",
																"Installers.installer_name",
																],
											'join'=>[
														[	'table'=>'installers',
															'alias'=>'Installers',
															'type'=>'INNER',
															'conditions'=>'ApplyOnlines.installer_id = Installers.id']
													],
											'conditions' => [
																'ApplyOnlines.id IN '=> array(92,1381,3823)
												]
										]
									);
        $CancelApplicationCount = 0;
		foreach($arrApplications as $arrApplication)
		{
			if (empty($arrApplication->pv_capacity)) continue;
			$conditions  	= array("installer_id" => $arrApplication->installer_id);
            $arr_result 	= $this->InstallerCategoryMapping->find('all',array('conditions'=>$conditions))->first();
            $allowed_bands  = json_decode($arr_result['allowed_bands']);
            $flag_valid 	= 0;
            foreach($allowed_bands as $value_band)
            {
                if($arrApplication->pv_capacity >= $this->slots[$value_band]['min'] && $arrApplication->pv_capacity <= $this->slots[$value_band]['max'])
                {
                    $flag_valid = 1;
                }
            }
            if ($flag_valid == 0) {
            	echo "\r\n--Application ::".$arrApplication->id." -- ".$arrApplication->Installers['installer_name']." -- ".$arrApplication->application_no." -- ".$arrApplication->pv_capacity." -- ".$arr_result['allowed_bands']."--\r\n";
            	$arrData = array("application_status"=>$this->ApplyOnlineApprovals->APPLICATION_CANCELLED);
    			$this->ApplyOnlines->updateAll($arrData,['id' => $arrApplication->id]);
    			$this->ApplyOnlineApprovals->saveStatus($arrApplication->id,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED,"1","Application cancelled due to installer is not allowed in slot.");
    			$CancelApplicationCount++;
            }
		}
		echo "\r\n--CancelApplicationCount::".$CancelApplicationCount."--\r\n";
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}