<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
class ManufacturerAddressMasterTable extends AppTable
{
	var $table = 'manufacturer_address_master';
	var $data  = array();
	public function initialize(array $config)
	{
		
		$this->table($this->table);
	}

	/*
	Use 	: Get Manufacturer Address List
	Author 	: Axay Shah
	Date 	: 15 December 2020
	*/
	public function GetManufacturerAddressList($manufacturerID=0){
		$data = $this->find()
				->where(['status =' => 1])
				->where(['manufacturer_id =' => $manufacturerID])
				->order(['name' => 'ASC'])
				->toList();		
	    return $data;
	}
}
?>