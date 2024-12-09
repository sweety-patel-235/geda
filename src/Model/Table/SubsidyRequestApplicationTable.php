<?php
/************************************************************
* File Name : SubsidyRequestTable.php 						*
* purpose	: Manage Database Opration of Subsidy Claim 	*
* @package  : 												*
* @author 	: Kalpak Prajapati								*
* @since 	: 04/12/2018									*
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

class SubsidyRequestApplicationTable extends AppTable
{
	var $table = 'subsidy_claim_request_applications';
	public function initialize(array $config)
    {
        $this->table($this->table);
    }

    public function FindByAppID($ApplicationID=0)
    {
    	return $this->find("all",["conditions"=>array("application_id"=>$ApplicationID)])->toArray();
    }
}
?>