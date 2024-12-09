<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for installer rating . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer rating information
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class InstallerRatingsTable extends AppTable
{
	var $table = 'installer_ratings';

	public function initialize(array $config)
    {
        $this->table($this->table);        
    }
}
?>