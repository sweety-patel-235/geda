<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class DeveloperApplicationQueryTable extends AppTable
{

	public $Name 	= 'DeveloperApplicationQuery';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'developer_application_query';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }

    public function checkMemberApproval($application_id='',$dev_app_id='',$app_type='',$member_id=''){
		$MemberRoles = TableRegistry::get('MemberRoles');
		
        if(isset($app_type) && isset($member_id) && isset($application_id) && isset($dev_app_id))
		{
            $lastForward = $this->find('all', array(
                'fields'        => array('id','member_id','forward_to'),
                'conditions'    => array('app_dev_per_id' => decode($dev_app_id),'application_id'=>decode($application_id)),
                'order' 		=> array('id' => 'DESC')
            ))->first();
            
            if(isset($lastForward) && !empty($lastForward)){
                
                $lastForwardId = $lastForward['forward_to'];
                if($lastForwardId == $member_id)
                {                   
                    return 1;
                }
            }else{
                
                $memberRole = $MemberRoles->find('all', [
                    'fields' => ['member_id'],
                    'conditions' => ['app_type' => $app_type],
                    'order' => ['role_order' => 'DESC'],
                    'limit' => 1
                ])->first();
                
                if ($memberRole) {
                    $memberIdWithMaxRoleOrder = $memberRole->member_id;
                    if($memberIdWithMaxRoleOrder == $member_id)
                        return 1;
                } 
            }
            return 0;
		}
        return 0;
	}
}