<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
//use App\Model\Table\Security;

use Cake\Utility\Security;

use Cake\Event\Event;

use App\Controller\AppController;
use Dompdf\Dompdf;
use Cake\Core\Configure;
use Cake\View\View;
use Cake\View\Helper;
use Cake\View\Helper\MyUtils;
use Cake\Utility\Hash;
//use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for Ticket table. It extends Table Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    Pravin Sanghani
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class DistrictMasterTable extends AppTable
{
	var $table 			= 'district_master';
	var $dataPass 		= array();
	
	public function initialize(array $config)
    {
        $this->table($this->table);
        //$this->loadHelper('MyUtils');
    }
}
?>