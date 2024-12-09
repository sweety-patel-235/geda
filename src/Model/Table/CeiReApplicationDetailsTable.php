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

class CeiReApplicationDetailsTable extends AppTable
{
    var $table = 'cei_re_application_details';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {  
        $validator->notEmpty('drawing_app_no', 'Enter drawing application number.');
        $validator->notEmpty('drawing_app_status', 'Enter drawing application status.');
        return $validator;
    }
    public function check_drawing_status($geo_id){

        $re_cei_drawing                 = $this->find("all",['fields'=>['app_geo_loc_id','drawing_app_status'],'conditions'=>array('app_geo_loc_id'=>$geo_id,'drawing_app_status'=>'Completed')])->first();
        return $re_cei_drawing;
          //  echo"<pre>"; print_r($re_cei_drawing); die();
    }
    public function check_inspection_status($geo_id){

        $re_cei_drawing                 = $this->find("all",['fields'=>['app_geo_loc_id','cei_app_status'],'conditions'=>array('app_geo_loc_id'=>$geo_id,'cei_app_status'=>'Completed')])->first();
        return $re_cei_drawing;
         // echo"<pre>"; print_r($re_cei_drawing); die();
    }
}