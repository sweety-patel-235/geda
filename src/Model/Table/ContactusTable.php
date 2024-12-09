<?php
/************************************************************
* File Name : ContactusTable.php 							*
* purpose	: Manage Database Opration of Contact us page	*
* @package  : 												*
* @author 	: Pravin Sanghani								*
* @since 	: 23/04/2016									*
************************************************************/

namespace App\Model\Table;
use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

class ContactusTable extends AppTable
{
	var $table = 'contactus';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';
	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
	/**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationContactus(Validator $validator)
    {
		
    	$validator->notEmpty('name', 'Please Enter Name.');
		$validator->notEmpty('email', 'Please Enter Email.')
		->add('email', 'valid-email', [
			'rule' => 'email',
			'message'=>'Please Enter valid Email.'
			]);
		$validator->notEmpty('mobile', 'Please Enter valid Mobile no.')
		->add('mobile', 'custom',[ 
			'rule'=> [$this, 'ValidateMobileNumber'],
			'message'=>'Please Enter valid Mobile no.']);
		$validator->notEmpty('subject', 'Please Enter Subject.');
		$validator->notEmpty('message', 'Please Enter Message.');
		
    	return $validator;
    }
	/**
	 *
	 * GetInstallerNameList
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get Parameter list if having master right to logged in user
	 *
	 */
	public function GetInstallerNameList(){
		return $this->find('list',array('keyField' => 'id',
    'valueField' => 'name','conditions'=>array('status'=>$this->STATUS_ACTIVE,'customer_type'=>'installer'),'order'=>array('name')));
	}
	
}
?>