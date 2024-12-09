<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class DiscomMasterTable extends AppTable
{
	var $table = 'discom_master';
	
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
		$validator->notEmpty('disCom_application_fee', 'DisCom application fee can not be blank.');
		$validator->notEmpty('jreda_processing_fee', 'JREDA processing fee can not be blank.');
		$validator->add("email", "validFormat", [
			"rule" => ["email", false],
			"message" => "Email must be valid."
		]);
		*/
		return $validator;
	}

	public function GetDiscomHirarchyByID($ID=0) {
		$discom  = $this->find('all',['conditions'=>['id'=>$ID]])->first();
		return $discom;
	}
	public function GetDiscomList($type = '',$page='',$limit='',$sort_by='DiscomMaster.title',$direction='ASC',$searchData=array()) {
		
		$fields             = [ 'DiscomMaster.id',
								'discom_master1.title',
								'discom_master1.short_code',
								'discom_master2.title',
								'discom_master2.short_code',
								'discom_master3.title',
								'discom_master3.short_code',
								'DiscomMaster.title',
								'DiscomMaster.short_code',
								'DiscomMaster.status',
								'logins'=>'CASE WHEN 1=1 THEN (select GROUP_CONCAT(members.email) from members where members.area=DiscomMaster.area and members.circle=DiscomMaster.circle and members.division=DiscomMaster.division and members.subdivision=DiscomMaster.id ) END'
							];
		
		$join_arr			=[	
							'discom_master1'=>['table'=>'discom_master','type'=>'left',
								'conditions'=>'DiscomMaster.area = discom_master1.id'],
							'discom_master2' 	=>['table'=>'discom_master','type'=>'left','conditions'=>'DiscomMaster.circle = discom_master2.id'],
							'discom_master3' 	=>['table'=>'discom_master','type'=>'left','conditions'=>'DiscomMaster.division = discom_master3.id'],
							'branch_masters' 	=>['table'=>'branch_masters','type'=>'left','conditions'=>'branch_masters.discom_id = DiscomMaster.id']];
		
		$arrConditions 		= ['DiscomMaster.status' => 1];
		if(!empty($type)) {
			array_push($arrConditions,array('DiscomMaster.type' => $type));
		}
		
	  	if(isset($searchData['discom_name']) && !empty($searchData['discom_name'])) {
	  		$BranchMasters 		= TableRegistry::get('BranchMasters');
	  		$branchDetails 		= $BranchMasters->find('all',array('conditions'=>array('id'=>$searchData['discom_name'])))->first();
			array_push($arrConditions,array('DiscomMaster.area' => $branchDetails['discom_id']));
		}
		if(isset($searchData['circle']) && !empty($searchData['circle'])) {
			array_push($arrConditions,array('discom_master2.id' => $searchData['circle']));
		}
		if(isset($searchData['division']) && !empty($searchData['division'])) {
			array_push($arrConditions,array('discom_master3.id' => $searchData['division']));
		}
		if(isset($searchData['subdivision']) && !empty($searchData['subdivision'])) {
			array_push($arrConditions,array('DiscomMaster.id' => $searchData['subdivision']));
		}

		$query  = $this->find('all',[ 'fields'=>$fields,
			'join'=>$join_arr,
			'conditions'=>$arrConditions,
			'order' => [$sort_by => $direction]
			]);
		
		if(!empty($page) && !empty($limit)){
			$query = $query->page($page)->limit($limit);
		}

		return $query;
	}
}
?>