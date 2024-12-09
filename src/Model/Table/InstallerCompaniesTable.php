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
 * This Model use for Installer Companies . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer companies
 * @author    CP Soni
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class InstallerCompaniesTable extends AppTable
{
	var $table = 'installers';
	/**
	 *
	 * The installer plan of $STATUS_ACTIVE is universe
	 *
	 * Potential value are 1 (identify installer plan Active)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_ACTIVE = 1;
	/**
	 *
	 * The installer plan of $STATUS_INACTIVE is universe
	 *
	 * Potential value are 0 (identify installer plan InActive/Deactive)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_INACTIVE = 0;

	public function initialize(array $config)
    {
        $this->table($this->table);        
    }
}
?>