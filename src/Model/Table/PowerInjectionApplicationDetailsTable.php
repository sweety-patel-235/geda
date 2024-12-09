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

class PowerInjectionApplicationDetailsTable extends AppTable
{
    var $table = 'power_injection_application_details';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    public function check_injection_status($geo_id){

        $injection                 = $this->find("all",['fields'=>['app_geo_loc_id','power_injection_report'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $injection;
        
    }
    public function check_injection_approval_status($geo_id){

        $injection_approval                 = $this->find("all",['fields'=>['app_geo_loc_id','power_injection_approve'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $injection_approval;
         
    }
    
}