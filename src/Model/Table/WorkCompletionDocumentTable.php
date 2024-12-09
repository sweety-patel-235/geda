<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class WorkCompletionDocumentTable extends AppTable
{
	var $table = 'workcompletion_docs';
	
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
        $documents      = $this->find('all',['conditions'=>['rid'=>$id]])->toArray();
        $arrDocuments   = array();
        if (!empty($documents)) {
            foreach ($documents as $document) {
                $image_path = APPLYONLINE_PATH.$document['application_id'].'/workcompletion/';
                if (file_exists($image_path.$document['filename'])) {
                    $document['url'] = APPLYONLINE_URL.$document['application_id'].'/workcompletion/'.$document['filename'];
                    if (strpos($document['filename'], 'tech_spec_1_') !== false) {
                        $arrDocuments['tech_spec_1'][] = $document;
                    } else if (strpos($document['filename'], 'tech_spec_') !== false) {
                        $arrDocuments['tech_spec'][] = $document;
                    }
                }
            }
        }
        return $arrDocuments;
    }
}
?>