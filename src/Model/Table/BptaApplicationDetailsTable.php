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

class BptaApplicationDetailsTable extends AppTable
{
    var $table = 'bpta_application_details';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    
    public function check_bpta_status($geo_id){

        $bpta                 = $this->find("all",['fields'=>['app_geo_loc_id','bpta_document1'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $meter;
        
    }
    public function check_bpta_approval_status($geo_id){

        $bpta_approval                 = $this->find("all",['fields'=>['app_geo_loc_id','bpta_approve'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $meter_approval;
         
    }
}