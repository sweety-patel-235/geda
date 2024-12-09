<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;
/**
 * Short description for file
 * This Model use for User Department table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage User Department information
 * @author    jaysinh Rajpoot
 * @version   Trio
 * @since     File available since Trio 1.0
 */
class UserDepartmentTable extends Table {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'UserDepartment';

	/**
	 * The status of $ACTIVE_STATUS is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $ACTIVE_STATUS 	= 1;

	/**
	 * The status of $INACTIVE_STATUS is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $INACTIVE_STATUS = 0;

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'user_departments';
    public function initialize(array $config)
    {
        $this->table($this->useTable);

        $this->belongsTo('Users', [
        	'className' => 'Users',
            'foreignKey' => 'user_id',
            'joinType' => 'INNER'
        ]);
    }
    /**
	 *
	 * AddUserDepartment
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for Manage User Department related details if having master right to logged in user
	 *
	**/
    public function AddUserDepartment($uid,$data=array(),$flag=0) 
	{

		if(isset($data) && !empty($data['department_id'])) {
			/* In-Active unwanted Department*/
			if($flag==1){
				$this->InactiveDepartment($uid,$data['department_id']);
		    }
		    foreach ($data['department_id'] as $department) 
		    {	        	
	        	if(isset($department) && !empty($department))
	        	{
					$this->query()->insert(['user_id','department_id','status'])->values(['user_id'=>$uid,'department_id'=>$department,'status'=>$this->ACTIVE_STATUS])->epilog('ON DUPLICATE KEY UPDATE status=1')->execute();
				}
			}
			
		}else {
			return false;
		}
	}
   
	/**
	 *
	 * GetUserDepartment
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get user department details if having master right to logged in user
	 *
	 */
	public function GetUserDepartment($id,$status)
	{
		return $this->find('all',array('conditions'=>array('user_id'=>$id,'status'=>$status)))->toArray();
	}

	/**
	 *
	 * InactiveDepartment
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get user department details if having master right to logged in user
	 *
	 */
	public function InactiveDepartment($id,$data=array())
	{
		return $this->updateAll(
			array('user_departments.status' =>$this->INACTIVE_STATUS),
    		array('user_departments.department_id Not In '=> $data ,'user_departments.user_id'=>$id)
		);
	}

	/**
	 *
	 * GetDepartmentwiseUserlist
	 *
	 * Behaviour : Public
	 *
	 * @defination : Use for get department wise user list if having master right to logged in user
	 *
	 */
	public function GetDepartmentwiseUserlist($did,$parent_id)
	{
		$query = $this->find('all',['contain'=>['Users'],'conditions'=>['UserDepartment.department_id'=>$did,'Users.status'=>1,'UserDepartment.status'=>$this->ACTIVE_STATUS,
				'NOT'=>['UserDepartment.user_id'=>$parent_id]]]
				)->toArray();
		//pr($query);exit;
		/*$query = $this->find('list',['keyField' => 'Users__id','valueField' => 'Users__name'],['contain'=>['Users'],'conditions'=>['UserDepartment.department_id'=>$did,'UserDepartment.status'=>$this->ACTIVE_STATUS,
				'NOT'=>['UserDepartment.user_id'=>$parent_id]]]
				)->toArray();*/

		/*$query = $this->find('all',['joins'=>[ 'table' => 'users','alias' => 'Users','type' => 'inner','foreignKey' => 'user_id']])->toArray();
	 	*/
		//pr($query);exit;
	 	$list_module = array();
	 	for($i=0;$i<count($query);$i++){
	 		$list_module[$query[$i]->user['id']] = $query[$i]->user['username'];
	 	}
	 	
	   	return $list_module;
	}
}
?>