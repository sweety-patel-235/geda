<?php
namespace App\Model\Table;
use App\Model\Table\Entity;
use App\Model\Entity\User;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Security;
use Cake\Event\Event;
use App\Controller\AppController;
use Dompdf\Dompdf;
use Cake\Core\Configure;
use Cake\View\View;
use Cake\View\Helper;
use Cake\View\Helper\MyUtils;
use Cake\Utility\Hash;

/**
 * Short description for file
 * This Model use for Energy Generation Log
 * @category  Class File
 * @Desc      Manage Energy Generation Log
 * @author    Kalpak Prajapati
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class EnergyGenerationLogTable extends AppTable
{
	var $table 			= 'energy_generation_log';
	var $dataPass 		= array();
	public function initialize(array $config)
    {
        $this->table($this->table);
    }
}
?>