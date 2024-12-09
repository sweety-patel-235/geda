<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
/**
* Short description for file
* This Model use for Ticket table. It extends AppModel Class
* @category  Class File
* @Desc      Manage Ticket information
* @author    jaysinh Rajpoot
* @version   RR
* @since     File available since RR 1.0
*/
class ApplicationProjectCommissioningTable extends Table {
/**
 * The status of $name is universe
 * Potential value are Class Name
 * @var String
 */
	public $Name 	= 'ApplicationProjectCommissioning';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'application_project_commissioning';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}

	public function check_commissioning_status($geo_id){

        $commissioning                 = $this->find("all",['fields'=>['app_geo_loc_id','PC_meter_no'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $commissioning;
          //  echo"<pre>"; print_r($re_cei_drawing); die();
    }
    public function check_intimation_status($geo_id){

        $commissioning                 = $this->find("all",['fields'=>['app_geo_loc_id','intimation_completion'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $commissioning;
          //  echo"<pre>"; print_r($re_cei_drawing); die();
    }
}
?>