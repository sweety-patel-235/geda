<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class RegistrationSchemeTable extends AppTable
{
	var $table = 'registration_scheme';
	
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

    public function getReportData($id)
    {
        $RegistrationScheme   = $this->find('all',['conditions'=>['application_id'=>$id]])->first();
        return $RegistrationScheme;
    }

    public function GenerateApplicationNo($application,$state) {
        $StateCode  = $this->GetStateCode($application->application_id);
        $id         = $application->id;
        $YEAR       = date("y",strtotime($application->created))."-".(date("y",strtotime($application->created))+1);
        $id         = $StateCode."/RT/AH/REG/".$YEAR."/1".str_pad($id, 7, "0", STR_PAD_LEFT);
        return $id;
    }

    public function GetStateCode($application) {
        $ApplyOnlines   = TableRegistry::get('ApplyOnlines');
        $States         = TableRegistry::get('States');
		$arrAppData 	= $ApplyOnlines->find("all",['fields'=>['state','apply_state'],
                                            'conditions'=>['ApplyOnlines.id'=>$application]])->first();
    
        $STATENAME  = "";
		$arrState 	= $States->find("list",['keyField'=>'id','valueField'=>'statename',
                                                'conditions'=>['States.id'=>$arrAppData->apply_state]])->toArray();
		if(!empty($arrState) && isset($arrState[$arrAppData->apply_state])) {
			$STATENAME = $arrState[$arrAppData->apply_state];
		} else {
            $STATENAME = $arrAppData->state;
        }

        $Code = "";
        if (!empty($STATENAME)) {
            switch (strtolower($STATENAME)) {
                case 'jharkhand':
                    $Code = "JH";
                    break;
                default:
                    $Code = strtoupper(substr($state,0,2));
                    break;
            }
        }
        return $Code;
    }
}
?>