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


/**
 * Short description for file
 * This Model use for BranchMasters. It extends Table Class
 * @category  Class File
 * @Desc      Manage BranchMasters
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class BranchMastersTable extends AppTable
{
	var $STATUS_ACTIVE 			= 1;
	var $STATUS_INACTIVE 		= 0;	
	
	var $validationSet			= "";
	var $validate				= array();
	
	public function initialize(array $config)
    {
        $this->table('branch_masters');
        $this->addAssociations([
          //'hasMany' => ['EventInvitations','EventBids','EventLogs','EventLots'],
          'belongsTo' => ['Installers']
        ]);
    }

	/**
	 *
	 * The status of $validate_timezone is universe
	 *
	 * Potential value are validate time zone
	 *
	 * @var Array
	 *
	 */
	public $validate_timezone =  array(
		/*
			'timezone' => array(
					'rule' => array('maxLength',5),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Please select valid Time zone.'
			)
			*/
	);

	public $validate_registration = array(
       
    );

    /**
     * Edit validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
    	$validator->notEmpty('parent_id', 'Main branch must be select.');
		$validator->notEmpty('title', 'Title can not be blank.');
		$validator->notEmpty('area', 'Area can not be blank.');
		$validator->notEmpty('city','City must be select');

    	return $validator;
    }
    /**
     * Main Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationMain_add(Validator $validator)
    {
    	$validator->notEmpty('title', 'Title can not be blank.');
		$validator->notEmpty('state', 'State can not be blank.');

    	return $validator;
    }
    /**
     * Main Edit validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationMain_edit(Validator $validator)
    {
    	$validator->notEmpty('title', 'Title can not be blank.');
		$validator->notEmpty('state', 'State can not be blank.');

    	return $validator;
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
    	$validator->notEmpty('parent_id', 'Main branch must be select.');
		$validator->notEmpty('title', 'Title can not be blank.');
		$validator->notEmpty('area', 'Area can not be blank.');
		$validator->notEmpty('city','City must be select');

    	return $validator;
    }
    /* use : get master branch id */
    public function findMasterId($branch_id) {
        $memberData = $this->find("all",['conditions'=>['id'=>$branch_id]])->last()->toArray();
        //pr($memberData);
        if(!empty($memberData['parent_id'])) {
            return $memberData['parent_id'];
        } else {
            return $branch_id;
        }
    }

    public function getBranchList($main_branch_id){
        return $this->find('list',
            ['keyField'=>'id','valueField'=>'title',
            'conditions'=>['OR'=>['id'=>$main_branch_id,'parent_id'=>$main_branch_id]]])->toArray();
    }
}
?>