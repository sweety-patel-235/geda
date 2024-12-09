<?php
/************************************************************
* File Name : SubscriptionTable.php                         *
* purpose   : Manage Database Opration of Subscription us User*
* @package  :                                               *
* @author   : Pravin Sanghani                               *
* @since    : 23/04/2016                                    *
************************************************************/

namespace App\Model\Table;
use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

class DeveloperPaymentRequestTable extends AppTable
{
	var $table = 'developer_payment_request';
	var $data  = array();
	public function initialize(array $config)
	{
		$this->table($this->table);         
	}
}