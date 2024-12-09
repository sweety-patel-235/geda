<?php
/************************************************************
* File Name : InstallerAgencyRating.php 					*
* purpose	: For Manage Indtaller Rating Agency			*
* @package  : 												*
* @author 	: Pravin Sanghani    							*
* @since 	: 29/06/2016									*
************************************************************/

namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

use App\Model\Table\Entity;

class InstallerAgencyRatingTable extends AppTable
{
	var $table = 'installer_agency_rating';
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
    public function installerRatting($id = null){
        return  $this->find('all')->where(['installer_id' => $id])->toArray();
    }

}
?>