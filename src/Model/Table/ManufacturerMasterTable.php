<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
class ManufacturerMasterTable extends AppTable
{
	var $table = 'manufacturer_master';
	var $data  = array();
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	/*
	Use 	: Get Manufacturer List
	Author 	: Axay Shah
	Date 	: 15 December 2020
	*/
	public function GetManufacturerList(){
		$data = $this->find()->where(['status =' => 1])->order(['name' => 'ASC'])->toList();
	    return $data;
	}
	public function GetManufacturerDropDown($arrCondition=array()){
		$manufacturer_arr = array();
		if(!empty($arrCondition) && is_array($arrCondition)){
			$manufacturer_arr 	= $this->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>$arrCondition])->order(['name' => 'ASC'])->toArray();
		}
		return $manufacturer_arr;
	}

	public function GetMenufacturerById($id){
		$manufacturer_arr 	= $this->find('all',array('conditions'=> array('id'=> $id)))->first();
		return $manufacturer_arr;
	}
	public function manufacturerList($make_type) {

		$ManufacturerMaster 		= TableRegistry::get('ManufacturerMaster');
		$arrRow 					= array();
		$arrSelectedRowData 	= $ManufacturerMaster->find('all',array('conditions'=> array('make_type'=> $make_type),'order'=>array('name')))->toArray();
		if(!empty($arrSelectedRowData)) {
			foreach($arrSelectedRowData as $val) {
				$arrRow[$val->short_code]= $val->name;
			}
		}
		return $arrRow;
	}

	public function manufacturerDropdown($make_type) {

		$ManufacturerMaster 		= TableRegistry::get('ManufacturerMaster');
		$arrRow 					= array();
		$arrSelectedRowData 	= $ManufacturerMaster->find('all',array('conditions'=> array('make_type'=> $make_type),'order'=>array('name')))->toArray();
		if(!empty($arrSelectedRowData)) {
			foreach($arrSelectedRowData as $val) {
				$arrRow[$val->id]= $val->name;
			}
		}
		return $arrRow;
	}

}
?>