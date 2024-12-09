<?php
/************************************************************
* File Name : AdminactionTable.php 							*
* purpose	: Admin Log Action file 						*
* @package  : 												*
* @author 	: Khushal Bhalsod								*
* @since 	: 22/04/2016									*
************************************************************/

namespace App\Model\Table;

use App\Model\Entity\User;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class AdminactionTable extends Table 
{
	public $table = 'adminactions';

	public function initialize(array $config)
    {
    	$this->table($this->table);
    }

	/*Admin User Log Action*/
	public $ADMIN_LOGIN 				= 1;
	public $ADMIN_LOGOUT 				= 2;
	public $ADD_ADMIN_USER 				= 3;
	public $EDIT_ADMIN_USER 			= 4;
	public $CHANGED_PROFILE 			= 5;
	public $INACTIVATED_ADMIN_USER 		= 6;
	public $ACTIVATED_ADMIN_USER 		= 7;
	
	/*Admin User Right Log Action*/
	public $ADD_REMOVE_ADMIN_USER_RIGHTS  = 8;

	/*Admin User Role Log Action*/
	public $ADD_ADMIN_USER_ROLE 		= 9;
	public $EDIT_ADMIN_USER_ROLE 		= 10;
	public $DELETE_ADMIN_USER_ROLE 		= 11;

	/*Parameter Type Log Action*/
	public $ADD_PARAMETER_TYPE 			= 12;
	public $EDIT_PARAMETER_TYPE 		= 13;

	/*Parameter Log Action*/
	public $ADD_PARAMETER 				= 14;
	public $EDIT_PARAMETER 				= 15;

	/*Installer Plan Log Action*/
	public $ADD_INSTALLER_PLAN 			= 16;
	public $EDIT_INSTALLER_PLAN 		= 17;
	public $ACTIVATED_INSTALLER_PLAN 	= 18;
	public $IN_ACTIVATED_INSTALLER_PLAN	= 19;

	/*Financial Incentives Log Action*/
	public $ADD_FINANCIAL_INCENTIVES 			= 20;
	public $EDIT_FINANCIAL_INCENTIVES 			= 21;
	public $ACTIVATED_FINANCIAL_INCENTIVES 		= 22;
	public $IN_ACTIVATED_FINANCIAL_INCENTIVES	= 23;

	/* Customer Log Action*/
	public $ACTIVATED_CUSTOMER 			= 	24;
	public $IN_ACTIVATED_CUSTOMER 		= 	25;

	/* ACTIVATED_INSTALLER Log Action*/
	public $ADD_INSTALLER 				= 	26;
	public $EDIT_INSTALLER 				= 	27;
	public $ACTIVATED_INSTALLER 		= 	28;
	public $IN_ACTIVATED_INSTALLER 		= 	29;
	
	public $ADD_MEMBER	 				= 	30;
	public $EDIT_MEMBER 				= 	31;
	public $ACTIVE_MEMBER 				= 	32;
	public $INACTIVE_MEMBER				= 	33;
	
	public $ADD_BRANCH_MASTER			= 34;
	public $EDIT_BRANCH_MASTER			= 35;
}

?>