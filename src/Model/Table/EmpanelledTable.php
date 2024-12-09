<?php
/************************************************************
* File Name : OffersTable.php 								*
* purpose	: Offer Model Table file 						*
* @package  : 												*
* @author 	: CP Soni										*
* @since 	: 23/04/2016									*
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;
use Cake\Validation\Validator;


class EmpanelledTable extends AppTable
{
	var $table = 'empanelleds';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    public function validationAdd(Validator $validator)
    {
    	$validator->notEmpty('name', 'First Name can not be blank.');
		$validator->notEmpty('lastname', 'Last Name can not be blank.');

		$validator->notEmpty('username', 'Username can not be blank.');
		$validator->notEmpty('password', 'Password can not be blank.');
		$validator->notEmpty('confirmpassword', 'Confirmpassword can not be blank.');
		$validator->notEmpty('usertype','User Type must be select');
		$validator->add('password', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['confirmpassword']) &&
		            $context['data']['confirmpassword'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]);

    	return $validator;
    }
    public function validationEdit(Validator $validator)
    {
    	$validator->notEmpty('name', 'First Name can not be blank.');
		$validator->notEmpty('lastname', 'Last Name can not be blank.');

		$validator->notEmpty('username', 'Username can not be blank.');
		$validator->notEmpty('password', 'Password can not be blank.');
		$validator->notEmpty('confirmpassword', 'Confirmpassword can not be blank.');
		$validator->notEmpty('usertype','User Type must be select');
		$validator->add('password', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['confirmpassword']) &&
		            $context['data']['confirmpassword'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]);

    	return $validator;
    }
}
?>