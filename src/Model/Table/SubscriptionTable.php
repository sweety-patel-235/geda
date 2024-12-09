<?php
/************************************************************
* File Name : SubscriptionTable.php 						*
* purpose	: Manage Database Opration of Subscription us User*
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

class SubscriptionTable extends AppTable
{
	var $table = 'subscription';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';
	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>