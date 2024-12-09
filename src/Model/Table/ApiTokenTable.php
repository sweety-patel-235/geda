<?php
namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class ApiTokenTable extends AppTable {

	public $api_response	= array();
	public $arrToken		= array();

	public $token			= '';
	/**
	 * 
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'api_tokens';
	public function initialize(array $config)
	{
		$this->table($this->table);
	}

	public $validate		= array(
        'token' => array(
            'Unique' => array(
                'rule'		=> 'isUnique',
				'last'		=> true,
                'message'	=> 'Token is already exists.'
            )
        )
    );

	public function ValidateToken($token=null, $device_id=null) {
	
		if(empty($device_id)) {
			$this->device_not_found	= true;
		}

		if(empty($token)) {
			$this->token	= $this->GenerateNewToken($device_id);
			return false;
		} else {
			$tokens	= $this->find('all', array('conditions'=>array('token' => $token)))->toArray();
			$tokens	=	(isset($tokens[0])?$tokens[0]:array());	
			if(empty($tokens)) {
				$this->token	= $this->GenerateNewToken($device_id);
				return false;
			} else {
				$this->token		= $tokens['token'];
				$this->customer_id	= $tokens['customer_id'];
				$this->id			= $tokens['id'];
				if($tokens['device_id'] == $device_id || (!empty($device_id) && empty($tokens['device_id']))) {
					$this->device_id		= $device_id;
				} else {
					$this->device_mismatch	= true;
				}
			}
			$this->arrToken	= $token;
			$this->updateAll(
				array('last_access' => "'".date('Y-m-d H:i:s')."'"),
				array('id' => $tokens['id'])
			);
			return true;
		}
	}

	public function SetAPIResponse($key, $value) {
		$this->api_response[$key]	= $value;
	}

	public function GenerateAPIResponse() {

		$arrReturn		= array();
		$response_keys	= array_keys($this->api_response);
		$arrReturn		= array_merge($this->api_response);

		return json_encode($arrReturn);
	}

	public function GenerateNewToken() 
	{
		$date						= date('Y-m-d H:i:s');
		$rand						= rand(10000,99999);
		$rand						= strtotime($date).$rand;
		$this->token				= md5($rand.HMAC_HASH_PRIVATE_KEY);
		$tokenEntity 				= $this->newEntity();
		$tokenEntity->token 		= $this->token;
		$tokenEntity->customer_id 	= 0;
		$tokenEntity->last_access 	= $date;
		$tokenEntity->created 		= $date;
		if($this->save($tokenEntity)) {
			$this->arrToken	= $this->get($tokenEntity->id);
			return $this->token;
		}
		return "";
	}

	public function ValidateHash($request_hash, $content) {

		$hash			= $this->GenerateHash($content);

		//echo "<br> ==Header :: ".$request_hash;
		//echo "<br> ==Generated :: ".$hash;
		//return false;
		if($request_hash==$hash)
			return true;
		return false;

	}

	public function LoggedInAPIUser($token, $customer_id, $token_id=null) {

		if(!empty($token_id)) {
			$this->updateAll(
								array('customer_id' => $customer_id),
								array('id' => $token_id)
							);
		} else {
			$this->updateAll(
								array("customer_id" => $customer_id),
								array("token" => $token)
							);
		}
		

        /** Find User Type */
       /*  $conditions         = array('Customers.id' => $customer_id);
        $arrCustomer        = $Customers->find('first',array('conditions'=>$conditions,'fields'=>array('Customers.collection_user','Customer.zipcode')));
        $CollectionUser     = isset($arrCustomer['Customers']['collection_user'])?$arrCustomer['Customers']['collection_user']:false;
        $pincode            = isset($arrCustomer['Customers']['zipcode'])?$arrCustomer['Customers']['zipcode']:"";
        */ /** Find User Type */

        /* Set Pledge Flag */
        /* App::import("model","Pledge");
		$Pledge		    = new Pledge();
        $conditions		= array("Pledge.customer_id"=>$customer_id);
        $PledgeCount	= $Pledge->find("count",array("conditions"=>$conditions));
        $Pledge			= ($PledgeCount > 0)?"Y":"N";
        $this->SetAPIResponse('Pledge',$Pledge);
        */ /* Set Pledge Flag */
	}
}