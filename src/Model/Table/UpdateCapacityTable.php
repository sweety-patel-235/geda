<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class UpdateCapacityTable extends AppTable
{
	var $table          = 'update_capacity_request_applications';
    var $data           = array(); 
    var $TYPE_ARR       = array('application/pdf'); 
    var $TYPE_PHOTO_ARR = array('image/jpeg'); 
    var $EXT_ARR        = array('pdf'); 
    var $EXT_PHOTO_ARR  = array('jpg', 'jpeg'); 
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
        
        $validator->notEmpty('reason', 'Reason can not be blank.');
        $validator->notEmpty('pv_capacity', 'Registered capacity can not be blank.');
        //$validator->notEmpty('pv_capacity_dc', 'Registered capacity AC can not be blank.');
        if($this->data['pv_capacity'] == 0)
        {
            $validator->add("pv_capacity", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Registered capacity must be greater than 0"
                    ]
             ]);
        }
        if($this->data['pv_capacity'] > $this->data['registered_capacity'])
        {
            $validator->add("pv_capacity", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Registered capacity must be less than ".$this->data['registered_capacity']
                    ]
             ]);
        } 
        if($this->data['pv_capacity_dc'] > $this->data['reg_capacity_dc'])
        {
            $validator->add("pv_capacity_dc", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Registered capacity must be less than ".$this->data['reg_capacity_dc']
                    ]
             ]);
        }
       /* if($this->data['pv_capacity_dc'] == $this->data['reg_capacity_dc'])
        {
            $validator->add("pv_capacity_dc", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Registered capacity should not same as reduce capacity"
                    ]
             ]);
        }
        if($this->data['pv_capacity'] == $this->data['registered_capacity'])
        {
            $validator->add("pv_capacity", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Registered capacity should not same as reduce capacity"
                    ]
             ]);
        }*/
        if($this->data['registered_capacity']>10 && $this->data['pv_capacity'] <=10)
        {
            $validator->add("pv_capacity", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Registered capacity should not less than or equals to 10"
                    ]
             ]);
        }
        if(empty($this->data['consent_letter']))
        {
            $validator->notEmpty('consent_letter', 'Consent letter can not be blank.');
        }
    	return $validator;
    }
    /**
    *
    * customFunction_consumer
    *
    * Behaviour : public
    *
    * Parameter : discom
    *
    * @defination : Method is used to check consumer number length validation.
    *
    */
    public function customFunction_consumer($value, $context)
    {
        return false;
    }
    /**
     *
     * viewUpdateDetails
     *
     * Behaviour : Public
     *
     * @param : $application_id   : pass application_id
     * @defination : Method is use to fetch Update Details request details from
     *
     */
    public function viewCapacityDetails($application_id)
    {
        $arrUpdate  = $this->find('all',array('conditions'=>array('application_id'=>$application_id),'order'=>array('id'=>'desc')))->first();
        return $arrUpdate;
    }
}