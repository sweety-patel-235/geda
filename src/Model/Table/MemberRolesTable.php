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
 * This Model use for Members. It extends Table Class
 * @category  Class File
 * @Desc      Manage Members
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class MemberRolesTable extends AppTable
{
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */

	 public $Name 	= 'MemberRoles';
    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */

	public $useTable = 'member_roles';
	
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }

	public function getMemberRoles($app_type=''){
		
		
		$memberRoles = $this->find('all', array(
			'fields' => array('member_id'),
			'conditions' => array('app_type' => $app_type)
		))->toArray();
		
		return $memberRoles;
	}
	

}
?>