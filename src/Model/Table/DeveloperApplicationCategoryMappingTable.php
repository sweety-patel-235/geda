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

class DeveloperApplicationCategoryMappingTable extends AppTable
{
    var $table     = 'developer_application_category_mapping';

    public function initialize(array $config)
    {
        $this->table($this->table);
    }

    public function saveDeveloperAppCategoryMapping($installer_id, $category_ids, $payment_success_id)
    {
        if (isset($installer_id) && !empty($installer_id)) {
            $category_arr = explode(",", $category_ids);
            foreach($category_arr as $val){
                $ApplicationCategoryTable 					= TableRegistry::get('ApplicationCategory');
                $ApplicationCategoryDetails                 = $ApplicationCategoryTable->find('all', array('conditions' => array('id' => $val)))->first();
                $developer_charges                          = isset($ApplicationCategoryDetails->developer_charges) ? $ApplicationCategoryDetails->developer_charges : 0;
                $gst_fees                                   = isset($ApplicationCategoryDetails->developer_tax_percentage) ? (($developer_charges * $ApplicationCategoryDetails->developer_tax_percentage) / 100) : 0;
                $developer_total_charges                    = $developer_charges + $gst_fees;

                $devMapEntity                               = $this->newEntity();
                $devMapEntity->installer_id                 = $installer_id;
                $devMapEntity->payment_success_id           = $payment_success_id;
                $devMapEntity->application_category_id      = $val;
                $devMapEntity->developer_fee                = $developer_charges;
                $devMapEntity->gst_fees                     = $gst_fees;
                $devMapEntity->developer_total_fee          = $developer_total_charges;
                $this->save($devMapEntity);
            }            
        }
    }
    
    public function updateDeveloperAppCategoryMapping($installer_id, $payment_success_id){
        $arr=[];
        if(!empty($installer_id) && !empty($payment_success_id)) {
            $arr['payment_success_id'] 	= $payment_success_id;
           
            $this->updateAll($arr,array('installer_id'=>$installer_id,'payment_success_id IS NULL'));
        }
    }
    
}
