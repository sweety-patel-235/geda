<?php
/************************************************************
* File Name : FinancialIncentivesTable.php 					*
* purpose	: Financial Incentives Model Table file 		*
* @package  : 												*
* @author 	: Khushal Bhalsod								*
* @since 	: 21/04/2016									*
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;

class FinancialIncentivesTable extends AppTable
{
	var $table = 'financial_incentives';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);        
    }
}
?>