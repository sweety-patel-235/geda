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
 * This Model use for installer activation codes . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer activation codes
 * @author   
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class DeveloperActivationCodesTable extends AppTable
{
	var $table = 'developer_activation_codes';
	/**
	 *
	 * The installer activation code of $ACTIVATION_CODE_USED is universe
	 *
	 * Potential value are 1 (identify activation code used)
	 *
	 * @var Int
	 *
	 */
	var $ACTIVATION_CODE_USED = 1;
	/**
	 *
	 * The installer activation code of $ACTIVATION_CODE_NOT_USED is universe
	 *
	 * Potential value are 0 (identify activation code not used)
	 *
	 * @var Int
	 *
	 */
	var $ACTIVATION_CODE_NOT_USED = 0;

	public function initialize(array $config)
    {
        $this->table($this->table);        
    }
}
?>