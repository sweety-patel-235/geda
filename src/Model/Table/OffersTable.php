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

class OffersTable extends AppTable
{
	var $table = 'offers';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    public function getOfferList($customerId)
    {

    	$query  = $this->find('all');
  	    $offerData =	$query->select([
        						'offer_id' => 'Offers.id', 
        						'offer_title',
        						'offer_desc',
        						'offer_code',
        						'images',
        						'offer_accepted' => $query->newExpr()->addCase([$query->newExpr()->add(['customer_id' => $customerId])],[1,0],['integer','integer'])
        						])
        					->leftJoin(['co' => 'customer_offers'],['Offers.id = co.offer_id','co.customer_id = '.$customerId])
        					->group(['Offers.id'])
        					->toArray();
        return $offerData;	        
    }

}
?>