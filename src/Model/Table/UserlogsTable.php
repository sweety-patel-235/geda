<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
//use App\Model\Table\Security;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;

class UserlogsTable extends AppTable
{
		
	/**
	 * 
	 * The status of $useTable is universe
	 *
	 * Potential value are database table name
	 *
	 * @var String
	 *
	 */
	var $useTable = 'adminlogs';
	public function initialize(array $config)
    {
        $this->table($this->useTable);
        $this->primaryKey('id');
       	$this->belongsTo('Users', [
        	'className' => 'Users',
            'foreignKey' => 'adminuserid',
            'joinType' => 'LEFT'
        ]);
    }
}
?>