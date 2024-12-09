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

class CompanyTable extends AppTable
{
	var $table = 'companies';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

 /**
    * userslist
    * list like key value
    * @return list of User
    */
    public function companylist($char = ''){
        return $this->find('list', ['keyField' => 'id','valueField' => 'company_name','conditions'=>array('company_name like'=>$char.'%')])->order('company_name')->limit(10)->toArray();
    }

}
?>