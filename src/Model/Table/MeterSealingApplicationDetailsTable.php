<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class MeterSealingApplicationDetailsTable extends AppTable
{
    var $table = 'meter_sealing_application_details';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    public function check_meter_status($geo_id){

        $meter                 = $this->find("all",['fields'=>['app_geo_loc_id','meter_sealing_report'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $meter;
        
    }
    public function check_meter_approval_status($geo_id){

        $meter_approval                 = $this->find("all",['fields'=>['app_geo_loc_id','meter_approve'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $meter_approval;
         
    }
    
}