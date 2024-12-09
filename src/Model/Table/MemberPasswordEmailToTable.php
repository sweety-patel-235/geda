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
use Cake\Auth\DefaultPasswordHasher;
use Cake\Network\Email\Email;

/**
 * Short description for file
 * This Model use for Proposal table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Proposal information
 * @author    Pravin Sanghani
 * @version   RR
 * @since     File available since RR 1.0
 */
class MemberPasswordEmailToTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */

	 public $Name 	= 'MemberPasswordEmailTo';
    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */

	public $useTable = 'member_password_email_recipients';

    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
}
?>