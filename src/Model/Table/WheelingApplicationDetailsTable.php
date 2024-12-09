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

class WheelingApplicationDetailsTable extends AppTable
{
    var $table = 'wheeling_application_details';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    public function check_wheeling_status($geo_id){

        $wheeling                 = $this->find("all",['fields'=>['app_geo_loc_id','Wheeling_Agreement_document'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $wheeling;
        
    }
    public function check_wheeling_approval_status($geo_id){

        $wheeling_approval                 = $this->find("all",['fields'=>['app_geo_loc_id','wheeling_approve'],'conditions'=>array('app_geo_loc_id'=>$geo_id)])->first();
        return $wheeling_approval;
         
    }
    
}