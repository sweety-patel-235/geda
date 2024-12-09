<?php
/************************************************************
* File Name : DeveloperCredendtialsTable.php 				*
* purpose	: Table Keeps password information for developer*
* @package  : 												*
* @author 	: 							*
* @since 	: 10/03/2023									*
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

class DeveloperCredendtialsTable extends AppTable
{
	var $table 	= 'developer_passwords';
	
	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>