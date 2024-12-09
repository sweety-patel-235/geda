<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class UpdateDetailsTable extends AppTable
{
	var $table          = 'update_details_request_applications';
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
        $validator->notEmpty('aadhar_no', 'Aadhar no. can not be blank.');
        if(empty($this->data['profile_image']))
        {
            $validator->notEmpty('profile_image', 'Consumer Photo can not be blank.');
        }
        if(empty($this->data['electricity_bill']))
        {
            $validator->notEmpty('electricity_bill', 'New Electricity Bill can not be blank.');
        }
        if(empty($this->data['aadhar_card']))
        {
            $validator->notEmpty('aadhar_card', 'Aadhaar Card can not be blank.');
        }
        if($this->data['is_name_update'] == 0 && $this->data['is_division_details'] == 0 && $this->data['is_contract_load'] == 0)
        {
            $validator->add("select_updated", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Please select at-least one option."
                    ]
             ]);
        }
        if(strlen($this->data['aadhar_no']) != 12)
        {
            $validator->add("aadhar_no", [
                 "_empty" => [
                 "rule" => [$this, "customFunction_consumer"],
                 "message" => "Aadhar Card Number must be a 12 digits."
                    ]
             ]);
        }
        if(isset($this->data['profile_image']['name']) && !empty($this->data['profile_image']['name']))
        {
            
            $arrExten   = explode(".",$this->data['profile_image']['name']);
            $extension  = end($arrExten);
            if(!in_array($extension,$this->EXT_PHOTO_ARR))
            {
                $validator->add("profile_image", [
                     "_empty" => [
                     "rule" => [$this, "customFunction_consumer"],
                     "message" => "Allowed extensions are ".implode(', ',$this->EXT_PHOTO_ARR)."."
                        ]
                 ]);
            }

            if(!in_array($this->data['profile_image']['type'],$this->TYPE_PHOTO_ARR))
            {
                $validator->add("profile_image", [
                     "_empty" => [
                     "rule" => [$this, "customFunction_consumer"],
                     "message" => "Invalid file."
                        ]
                 ]);
            }
        }
        /*if(isset($this->data['document_1']['name']) && !empty($this->data['document_1']['name']))
        {
            $arrExten   = explode(".",$this->data['document_1']['name']);
            $extension  = end($arrExten);
            if(!in_array($extension,$this->EXT_ARR))
            {
                $validator->add("document_1", [
                     "_empty" => [
                     "rule" => [$this, "customFunction"],
                     "message" => "Allowed extensions are ".implode(', ',$this->EXT_ARR)."."
                        ]
                 ]);
            }
            if(!in_array($this->data['document_1']['type'],$this->TYPE_ARR))
            {
                $validator->add("document_1", [
                     "_empty" => [
                     "rule" => [$this, "customFunction"],
                     "message" => "Invalid file."
                        ]
                 ]);
            }

        }*/
        /*$validator->add('document_2', 'file', [
            'rule' => ['mimeType', ['image/jpeg', 'image/png']],
            'on' => function ($context) {
                return !empty($context['data']['document_2']);
            }
        ]);*/

        /*
        $validator->notEmpty('installer_id', 'Installer must be select.');
        $validator->notEmpty('disclaimer', 'Disclaimer can not be blank.');
        $validator->notEmpty('customer_name_prefixed', 'Customer Name Prefixed can not be blank.');
        //$validator->notEmpty('rooftop_area', 'Rooftop Area can not be blank.');
        $validator->notEmpty('area_unit', 'Area unit can not be blank.');
        $validator->notEmpty('sanctioned_load', 'Sanctioned load can not be blank.');
        $validator->notEmpty('sanctioned_load_unit', 'Sanctioned load unit can not be blank.');
        $validator->notEmpty('capacity', 'Capacity can not be blank.');
        $validator->notEmpty('name_of_consumer_applicant', 'Name Of Consumer Applicant can not be blank.');
        $validator->notEmpty('customer_name', 'Customer name can not be blank.');
        $validator->notEmpty('address1', 'Address 1 can not be blank.');
        //$validator->notEmpty('address2', 'Address 2 can not be blank.');
        $validator->notEmpty('comunication_address', 'Communication Address can not be blank.');
        $validator->notEmpty('city', 'City can not be blank.');
        $validator->notEmpty('category', 'Category must be select.');
        $validator->notEmpty('state', 'State can not be blank.');
        $validator->notEmpty('pincode', 'Pincode can not be blank.');
        $validator->notEmpty('mobile', 'Mobile can not be blank.');
        //$validator->notEmpty('landline_no', 'Landline no can not be blank.');
        $validator->notEmpty('email', 'Email can not be blank.');
        $validator->notEmpty('discom_name', 'Discom Name can not be blank.');
        $validator->notEmpty('consumer_no', 'Consumer No can not be blank.');
        //$validator->notEmpty('aadhar_no_or_pan_card_no', 'Aadhar no./PAN card no. can not be blank.');
        $validator->notEmpty('sanction_load_contract_demand', 'Sanction load contract demand can not be blank.');
        $validator->notEmpty('file_attach_recent_bill', 'Recent bill file required.');
        $validator->notEmpty('file_attach_latest_receipt', 'Latest receipt file required.');
        $validator->notEmpty('acknowledgement_tax_pay', 'Acknowledgement TAX pay can not be blank.');
        $validator->notEmpty('pv_capacity', 'PV Capacity can not be blank.');
        $validator->notEmpty('tod_billing_system', 'ToD billing system can not be blank.');
        $validator->notEmpty('avail_accelerated_depreciation_benefits', 'Avail accelerated depreciation benefits can not be blank.');
        $validator->notEmpty('payment_gateway', 'Payment gateway can not be blank.');
        $validator->notEmpty('disCom_application_fee', 'DisCom application fee can not be blank.');
        $validator->notEmpty('jreda_processing_fee', 'JREDA processing fee can not be blank.');
    	$validator->add("email", "validFormat", [
		    "rule" => ["email", false],
		    "message" => "Email must be valid."
		]);
        */
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
    public function viewUpdateDetails($application_id)
    {
        $arrUpdate  = $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
        return $arrUpdate;
    }
}