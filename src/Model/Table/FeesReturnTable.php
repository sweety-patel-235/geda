<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Couchdb\Couchdb;
class FeesReturnTable extends AppTable
{
	var $table = 'fees_return';
	var $data  = array();
	
	var $data_entity            = array();
	
	
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
		$validator->notEmpty('spg_applicant', 'Name of SPG/ Applicant can not be blank.');
		$validator->notEmpty('registration_no', 'Registration No. can not be blank.');
		$validator->notEmpty('registration_date', 'Registration Date can not be blank.');
		$validator->notEmpty('mobile', 'Mobile No. can not be blank.');
		$validator->notEmpty('capacity', 'Project Capacity kW(AC) can not be blank.');
		$validator->notEmpty('discom', 'Discom must be select.');
		$validator->notEmpty('email', 'Email can not be blank.');
		if(!empty($this->data['email']))
		{
			$validator->add("email", "validFormat", [
			"rule" => ["email", false],
			"message" => "Email must be valid."
			]);
		}
		//$validator->notEmpty('name_getco', 'Name of GETCO S/S can not be blank.');
		//$validator->notEmpty('draft_no', 'Demand Draft No. can not be blank.');
		//$validator->notEmpty('draft_date', 'Demand Draft Date can not be blank.');
		//$validator->notEmpty('demand_bank_name', 'Bank Name can not be blank.');
		/*$validator->add("demand_bank_name", [
				 "validFormat" => [
				 'rule' => array('custom', '/^[a-zA-Z\s&.]+$/'),
				 'message' => 'Bank Name alphabets and space are allows.'
					]
		]);*/
		
		
		$validator->notEmpty('account_no', 'Account No. can not be blank.');
		$validator->notEmpty('bank_name', 'Bank Name can not be blank.');
		
		if(empty($this->data['disclaimer'])) {
			$validator->add("disclaimer", [
				 "_empty" => [
				 "rule" => [$this, "customFalse"],
				 "message" => 'Agree to all the terms and conditions of GEDA.'
					]
			 ]);
			//$validator->notEmpty('disclaimer', 'Agree to all the terms and conditions of GEDA.');
		}
		$validator->add("bank_name", [
				 "validFormat" => [
				 'rule' => array('custom', '/^[a-zA-Z\s&.]+$/'),
				 'message' => 'Bank Name alphabets and space are allows.'
					]
		]);
		$validator->notEmpty('ifsc_code', 'IFSC Code can not be blank.');
		
		if(strlen(trim($this->data['ifsc_code']))!=11)
		{
			$validator->add("ifsc_code", [
				 "_empty" => [
				 "rule" => [$this, "customFalse"],
				 "message" => "IFSC Code Number must be a 11 letters."
					]
			 ]);
		}
		$validator->add("ifsc_code", [
				 "validFormat" => [
				 'rule' => array('custom', '/^[a-zA-Z0-9]+$/'),
				 'message' => 'IFSC Code only alpha numeric charectors allows.'
					]
		]);
		$validator->add("account_no", [
				 "validFormat" => [
				 'rule' => array('custom', '/^[0-9]+$/'),
				 'message' => 'Account No. only numeric charectors allows.'
					]
		]);
		/*$validator->add('capacity', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
				'message' => 'Decimal only'
			]
		]);
		$validator->add('demand_amount', [
			'validFormat'=>[
				'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
				'message' => 'Decimal only'
			]
		]);*/
		/*$validator->add("draft_no", [
				 "validFormat" => [
				 'rule' => array('custom', '/^[0-9]+$/'),
				 'message' => 'Demand Draft No. only numeric charectors allows.'
					]
		]);*/
		//$validator->notEmpty('date_ppa_signed', 'Date of PPA signed with DISCOM can not be blank.');
		$validator->notEmpty('date_ppa_term', 'Date of PPA termination with DISCOM can not be blank.');

		if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->copy_registration))) {
			$validator->notEmpty('file_copy_registration', 'Copy of registration letter file required.');
		}
		if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->pan_card))) {
			$validator->notEmpty('file_pan_card', 'Copy of PPA termination letter or undertaking submitted to Discom.');
		}
		
		if(empty($this->data['payment_receipt'])) {
			if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->indemnity_bond))) {
				$validator->notEmpty('file_indemnity_bond', 'Indemnity Bond file required.');
			}
		} else {
			$validator->notEmpty('demand_amount', 'Receipt amount can not be blank.');
			$validator->notEmpty('receipt_no', 'Receipt No. can not be blank.');
			$validator->notEmpty('receipt_date', 'Receipt Date can not be blank.');
			if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->receipt))) {
				$validator->notEmpty('file_receipt', 'Receipt file required.');
			}
		}
		if(empty($this->data_entity) || (!empty($this->data_entity) && empty($this->data_entity->account_cheque))) {
			$validator->notEmpty('file_account_cheque', 'Cheque file required.');
		}
		
		return $validator;
	}
	/**
	*
	* customFunction_consumer
	* Behaviour : public
	* Parameter : discom
	* @defination : Method is used to check consumer number length validation.
	*/
	public function customFalse($value, $context)
	{
		return false;
		
	}
	/**
	 * saveDetails
	 * Behaviour : Public
	 * @param 		: $arrRequest - requires for pass post form data, $arrSessionInfo - requires for pass login person information.
	 * @defination : Method is use to save data for fees return
	 */
	public function saveDetails($arrRequest,$arrSessionInfo=array()) {
		$this->data 		= $arrRequest;

		if(!isset($arrRequest['id']) || empty($arrRequest['id'])) {
			$FeesReturnEntity	= $this->newEntity($arrRequest,['validate'=>'Add']);
			$logAdd 		= 0;
		} else {
			$FeesReturnData 	= $this->get($arrRequest['id']);
			$this->data_entity 	= $FeesReturnData;
			$FeesReturnEntity	= $this->patchEntity($FeesReturnData,$arrRequest,['validate'=>'Add']);
			$logAdd 			= 1;
		}
		$FeesReturnEntity->submit 	= 0;
		if($logAdd == 1) {
			$FeesReturnFormLog 		= TableRegistry::get('FeesReturnFormLog');
			$jsonStr 				= json_encode($FeesReturnData);
			$arrFormData 			= json_decode($jsonStr,2);
			$fees_return_id 		= $arrFormData['id'];
			unset($arrFormData['id']);
			
			$FeesReturnFormLogEntity 					= $FeesReturnFormLog->newEntity($arrFormData);
			$FeesReturnFormLogEntity->fees_return_id 	= $fees_return_id;
			$FeesReturnFormLog->save($FeesReturnFormLogEntity);
		}
	
		$this->data_entity 			= $FeesReturnEntity;
		if(empty($FeesReturnEntity->errors())) {

			$jir_unique_code 						= getRandomNumber(16);
			$FeesReturnEntity->registration_date 	= date('Y-m-d',strtotime($FeesReturnEntity->registration_date));
			$FeesReturnEntity->receipt_date 	 	= date('Y-m-d',strtotime($FeesReturnEntity->receipt_date));
			$FeesReturnEntity->draft_date 	 		= date('Y-m-d',strtotime($FeesReturnEntity->draft_date));
			$FeesReturnEntity->date_ppa_signed 	 	= date('Y-m-d',strtotime($FeesReturnEntity->date_ppa_signed));
			$FeesReturnEntity->date_ppa_term 	 	= date('Y-m-d',strtotime($FeesReturnEntity->date_ppa_term));
			if(!isset($arrRequest['id']) || empty($arrRequest['id'])) {
				$FeesReturnEntity->created 	 		= $this->NOW();
				$FeesReturnEntity->created_by  		= isset($arrSessionInfo['login_id']) ? $arrSessionInfo['login_id'] : 0;
			}
			if(empty($FeesReturnEntity->jir_unique_code)) {
				$FeesReturnEntity->jir_unique_code 	= $jir_unique_code;
			}
			$FeesReturnEntity->modified 	 		= $this->NOW();
			$FeesReturnEntity->modified_by 			= isset($arrSessionInfo['login_id']) ? $arrSessionInfo['login_id'] : 0;
			$FeesReturnEntity->status 				= 0;
			$FeesReturnEntity->refundable_amount 	= floatval($arrRequest['capacity'])*floatval(REFUNDED_AMOUNT);
			$FeesReturnEntity->gst_amount 			= ((floatval($FeesReturnEntity->refundable_amount)*floatval(REFUNDED_GST_PER)))/100;
			
			
			
			$this->save($FeesReturnEntity);
			if(!isset($arrSessionInfo['login_id'])) {
				$arrSessionInfo['login_id'] 		= $FeesReturnEntity->modified_by;
			}

			$fees_return_no 		= $this->GenerateReceiptNo($FeesReturnEntity);
			$this->updateAll(array('fees_return_no'=>$fees_return_no),array('id'=>$FeesReturnEntity->id));


			$image_path = FEES_RETURN_PATH.$FeesReturnEntity->id.'/';
			if(!file_exists(FEES_RETURN_PATH.$FeesReturnEntity->id)) {
				@mkdir(FEES_RETURN_PATH.$FeesReturnEntity->id, 0777);
			}
			if(!empty($arrRequest['file_copy_registration']) && !empty($arrRequest['file_copy_registration']['name']) ) {
				$db_copy_registration = $FeesReturnEntity->copy_registration;
				if(file_exists($image_path.$db_copy_registration) && !empty($db_copy_registration)){
					@unlink($image_path.$db_copy_registration);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['file_copy_registration'],false,'','',$image_path,'fr_cr','fr_copy_registration',$arrSessionInfo['login_id']);
				$this->updateAll(['copy_registration' => $file_name], ['id' => $FeesReturnEntity->id]);
			}
			if(!empty($arrRequest['file_pan_card']) && !empty($arrRequest['file_pan_card']['name']) ) {
				$db_pan_card = $FeesReturnEntity->pan_card;
				if(file_exists($image_path.$db_pan_card) && !empty($db_pan_card)){
					@unlink($image_path.$db_pan_card);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['file_pan_card'],false,'','',$image_path,'fr_pan','fr_pan_card',$arrSessionInfo['login_id']);
				$this->updateAll(['pan_card' => $file_name], ['id' => $FeesReturnEntity->id]);
			}
			if(!empty($arrRequest['file_receipt']) && !empty($arrRequest['file_receipt']['name']) ) {
				$db_receipt = $FeesReturnEntity->receipt;
				if(file_exists($image_path.$db_receipt) && !empty($db_receipt)){
					@unlink($image_path.$db_receipt);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['file_receipt'],false,'','',$image_path,'fr_re','fr_receipt',$arrSessionInfo['login_id']);
				$this->updateAll(['receipt' => $file_name], ['id' => $FeesReturnEntity->id]);
			}
			if(!empty($arrRequest['file_indemnity_bond']) && !empty($arrRequest['file_indemnity_bond']['name']) ) {
				$db_indemnity_bond = $FeesReturnEntity->indemnity_bond;
				if(file_exists($image_path.$db_indemnity_bond) && !empty($db_indemnity_bond)){
					@unlink($image_path.$db_indemnity_bond);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['file_indemnity_bond'],false,'','',$image_path,'fr_ind','fr_indemnity_bond',$arrSessionInfo['login_id']);
				$this->updateAll(['indemnity_bond' => $file_name], ['id' => $FeesReturnEntity->id]);
			}
			if(!empty($arrRequest['file_account_cheque']) && !empty($arrRequest['file_account_cheque']['name']) ) {
				$db_account_cheque = $FeesReturnEntity->account_cheque;
				if(file_exists($image_path.$db_account_cheque) && !empty($db_account_cheque)){
					@unlink($image_path.$db_account_cheque);
				}
				$file_name = $this->file_upload($image_path,$arrRequest['file_account_cheque'],false,'','',$image_path,'fr_acc_che','fr_account_cheque',$arrSessionInfo['login_id']);
				$this->updateAll(['account_cheque' => $file_name], ['id' => $FeesReturnEntity->id]);
			}
			
			$FeesReturnEntity->submit = 1;
		}
		return $FeesReturnEntity;
	}
	public function GenerateReceiptNo($feesReturn) {
		$StateCode  = 'GUJ';//$this->GetStateCode($application->apply_state,$application->state);
		$id         = $feesReturn->id;
		$appendStr  = 'FR';
		
		$id         = $StateCode."/".$appendStr."/1".str_pad($id,7, "0", STR_PAD_LEFT);
		return $id;
		
	}
	public function file_upload($path, $file,$is_resize = false,$width="", $hight="",$resize_path='',$prefix_file = '',$access_type='',$login_id)
	{
		$Couchdb 		= TableRegistry::get('Couchdb');
		$customerId 	= $login_id;
		$ext 			= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name 		= $prefix_file.date('Ymdhis').rand();
		$file_location 	= WWW_ROOT.$path.$file_name.'.'.$ext;
		move_uploaded_file($file['tmp_name'],$file_location);
		if($is_resize && !empty($width) && !empty($hight) && !empty($file_location)){
			@$resize_path = WWW_ROOT.$resize_path.'r_'.$file_name.'.'.$ext;
			@$this->Image->prepare($file_location);
			@$this->Image->resize($width,$hight);//width,height,Red,Green,Blue
			@$this->Image->save($resize_path);
		}
		$passFileName 	= $file_name.'.'.$ext;
		$couchdbId 		= $Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
		return $file_name.'.'.$ext;
	}
	public function GetExcelColumnName($num)
    {
		$str 			= '';
		$DEFAULT_NUMBER = 64;
		while ($num > 0) {
			$Module = ($num % 26);
			$Module = ($Module > 0?$Module:26);
			$str 	= chr( $Module + $DEFAULT_NUMBER) . $str;
			$num 	= (int) ($num / 26);
		}
		return trim($str);
    }
}