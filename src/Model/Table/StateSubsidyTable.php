<?php
/************************************************************
* File Name : OffersTable.php                               *
* purpose   : Offer Model Table file                        *
* @package  :                                               *
* @author   : CP Soni                                       *
* @since    : 23/04/2016                                    *
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;

class StateSubsidyTable extends AppTable
{
    var $table = 'statesubsidy';
    var $STATUS_ACTIVE          = 'A';
    var $STATUS_INACTIVE        = 'I';
    var $CUSTOMER_TYPE_PARA_ID  = 3;
    var $validate               = array();
    var $RID                    = 0;

    public function initialize(array $config)
    {
        $this->table($this->table);
    }

    public function getSubcidyDataByState($region_id,$customer_type=0)
    {
        $offerData = array(); 
        $arrConditions= array("StateSubsidy.state"=>strtolower($region_id),"StateSubsidy.customer_type"=>$customer_type);
        $offerData = $this->find('all',array("conditions"=>$arrConditions))->first();
        return $offerData;
    }
    /**
    *
    * percentage_validation
    *
    * Behaviour : public
    *
    * Parameter : percentage
    *
    * @defination : Method is used to check integer.
    *
    */
    public function percentage_validation($value, $context)
    {
        if((is_float($value) || is_int($value)) && $value > 100)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
    *
    * integer_validation
    *
    * Behaviour : public
    *
    * Parameter : percentage
    *
    * @defination : Method is used to check integer.
    *
    */
    public function integer_validation($value, $context)
    {
        if(!is_int($value) && $value != 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    /**
    *
    * float_validation
    *
    * Behaviour : public
    *
    * Parameter : percentage
    *
    * @defination : Method is used to check float.
    *
    */
    public function float_validation($value, $context)
    {
        if(!is_float($value) && $value != 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }


    /**
    *
    * custom_subsidy_unique
    *
    * Behaviour : public
    *
    * Parameter : $value
    *
    * @defination : Method is used to check consumer number unique subsidy rules.
    *
    */
    public function custom_subsidy_unique($value, $context)
    {
        $arr_condition = array("state" => strtolower($value),"customer_type" => $this->data['StateSubsidy']['customer_type']);
        if(isset($this->RID) && !empty($this->RID))
        {
            $arr_condition['id != '] = $this->RID;
        }
        $arr_result = $this->find('all',array('conditions'=>$arr_condition))->toArray();
        if(!empty($arr_result))
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    /**
    * Add validation rules.
    *
    * @param \Cake\Validation\Validator $validator Validator instance.
    * @return \Cake\Validation\Validator
    */
    public function validationAdd(Validator $validator)
    {
        $validator->notEmpty('state', 'State is required.');
        $validator->notEmpty('customer_type', 'Category can not be blank.');
        if ($this->data['StateSubsidy']['state_subcidy_type'] == 0) {
            $validator->notEmpty('state_subsidy','State subsidy can not be blank.');
            $validator->notEmpty('state_capacity','State PV Capacity can not be blank.');
            $validator->add("state_subsidy", [
                "_empty" => [
                    "rule" => [$this, "percentage_validation"],
                    "message" => "State subsidy can not be greater than 100."
                ]
                    ]
            );
        } elseif ($this->data['StateSubsidy']['state_subcidy_type'] == 1) {
            $validator->add("state_subsidy", [
                "_empty" => [
                    "rule" => [$this, "integer_validation"],
                    "message" => "State subsidy allowed only positive integer value."
                ]
                    ]
            );
            $validator->add("state_capacity", [
                "_empty" => [
                    "rule" => [$this, "float_validation"],
                    "message" => "State capacity allowed only positive integer/float value."
                ]
                    ]
            );
        }
        if ($this->data['StateSubsidy']['central_subcidy_type'] == 0) {
            $validator->notEmpty('central_subsidy','Central subsidy can not be blank.');
            $validator->notEmpty('central_capacity','Central PV Capacity can not be blank.');
            $validator->add("central_subsidy", [
                    "_empty" => [
                        "rule" => [$this, "percentage_validation"],
                        "message" => "Central subsidy can not be greater than 100."
                    ]
                        ]
                );
        } elseif ($this->data['StateSubsidy']['central_subcidy_type'] == 1) {
            $validator->add("central_subsidy", [
                "_empty" => [
                    "rule" => [$this, "integer_validation"],
                    "message" => "Central subsidy allowed only positive integer value."
                ]
                    ]
            );
            $validator->add("central_capacity", [
                "_empty" => [
                    "rule" => [$this, "float_validation"],
                    "message" => "Cental capacity allowed only positive integer/float value."
                ]
                    ]
            );
        }
        if ($this->data['StateSubsidy']['other_subcidy_type'] == 0) {
            $validator->notEmpty('other_subsidy','Other subsidy can not be blank.');
            $validator->notEmpty('other_capacity','Other PV Capacity can not be blank.');
            $validator->add("other_subsidy", [
                "_empty" => [
                    "rule" => [$this, "percentage_validation"],
                    "message" => "Other subsidy can not be greater than 100."
                ]
                    ]
            );
        } elseif ($this->data['StateSubsidy']['other_subcidy_type'] == 1) {
            $validator->add("other_subsidy", [
                "_empty" => [
                    "rule" => [$this, "integer_validation"],
                    "message" => "Other subsidy allowed only positive integer value."
                ]
                    ]
            );
            $validator->add("other_capacity", [
                "_empty" => [
                    "rule" => [$this, "float_validation"],
                    "message" => "Other capacity allowed only positive integer/float value."
                ]
                    ]
            );
        }
        $validator->add("state", [
                "_empty" => [
                    "rule" => [$this, "custom_subsidy_unique"],
                    "message" => "State subsidy with same Category is already added in database."
                ]
                    ]
            );
        return $validator;
    }
}
?>