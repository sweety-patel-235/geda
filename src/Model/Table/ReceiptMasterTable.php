<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;

class ReceiptMasterTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'ReceiptMaster';

	/**
	 * The status of $useTable is universe
	 * Potential value are Database Table name
	 * @var String
	 */
	public $useTable = 'receipt_master';
	public function initialize(array $config)
	{
		$this->table($this->useTable);
	}

    public function save_receipt_master($application_id,$receipt_no,$max_serial_no,$app_type,$created_year)
	{
		$save_data           							=   TableRegistry::get('ReceiptMaster'); 
		$save_data_entity    							=   $save_data->newEntity(); 
        $save_data_entity->application_id               =   $application_id;
        $save_data_entity->receipt_no                   =   $receipt_no;   
        $save_data_entity->max_serial_no                =   $max_serial_no;
		$save_data_entity->app_type                     =   $app_type;
		$save_data_entity->created_year                 =   $created_year;
		$save_data_entity->created_date           	    =   $this->NOW();
		$save_data->save($save_data_entity);
		
	}

}