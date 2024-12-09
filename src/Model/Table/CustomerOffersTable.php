<?php
/************************************************************
* File Name : CustomeroffersTable.php 						*
* purpose	: Customer Offer Model Table file 				*
* @package  : 												*
* @author 	: CP Soni										*
* @since 	: 23/04/2016									*
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;

class CustomerOffersTable extends AppTable
{
	var $table = 'customer_offers';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);        
    }
}
?>