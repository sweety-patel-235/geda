<?php
/************************************************************
* File Name : InstallerCredendtialsTable.php 				*
* purpose	: Table Keeps password information for installer*
* @package  : 												*
* @author 	: Kalpak Prajapati								*
* @since 	: 25/09/2018									*
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

class InstallerCredendtialsTable extends AppTable
{
	var $table 	= 'installer_passwords';
	
	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
}
?>