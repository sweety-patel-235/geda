<?php
/************************************************************
* File Name : InstallerStatesTable.php 			      		*
* purpose	: Installer Working Area State file 			*
* @package  : 												*
* @author 	: Pravin Sanghani								*
* @since 	: 30/06/2016									*
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;

class InstallerStatesTable extends AppTable
{
	var $table = 'installer_region_states';
	var $STATUS_ACTIVE      = 'A';
    var $STATUS_INACTIVE    = 'I';

	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
    public function getInstallerStateList($id = null)
    {
        $stateArr = array();
        if(!empty($id))
        {
         $stateArr = $this->find('all',
                    ['join'=>[
                        'c' => [
                            'table' => 'states',
                            'type' => 'INNER',
                           'conditions' => ['c.id = InstallerStates.state_id']
                            ],
                    ],
					'fields'=>['c.statename','c.region_id','c.id']
					])->where(['InstallerStates.installer_id' => $id])->toArray();
        }
        return $stateArr;    
    }
}

?>