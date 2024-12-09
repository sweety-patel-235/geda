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
 * This Model use for installer projects . It extends Table Class
 * @category  Class File
 * @Desc      Manage installer project information
 * @author    Khushal Bhalsod
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class InstallerProjectsTable extends AppTable
{
	var $table = 'installer_projects';
	
	public function initialize(array $config)
    {
        $this->table($this->table);        
    }

    /**
    * userslist
    * list like key value
    * @return list of User
    */
    public function getProjectwiseInstallerList($project_id = null){
        $stateArr = array();
        if(!empty($project_id))
        {
                $stateArr  = $this->find('all',['join'=>[
                            'installers' => [
                                'table' => 'installers',
                                'type' => 'LEFT',
                                'conditions' => ['InstallerProjects.installer_id = installers.id']
                            ]],
                            'fields' => array('installers.id','installers.installer_name','installers.contact_person','installers.address','installers.city','installers.state','installers.email','installers.contact','installers.contact1'),
                            'conditions' => ['InstallerProjects.project_id' => $project_id],
                            'order' => array('installers.installer_name' => 'ASC')])->toArray();
        }
        return $stateArr;    
    }
    public function getProjectInstallerByid($project_id = null){
        $stateArr = array();
        if(!empty($project_id))
        {
                $stateArr  = $this->find('all',['join'=>[
                            'installers' => [
                                'table' => 'installers',
                                'type' => 'LEFT',
                                'conditions' => ['InstallerProjects.installer_id = installers.id']
                            ]],
                            'fields' => array('installers.id','installers.installer_name','installers.address','installers.city','installers.state','installers.email','installers.contact','installers.contact1'),
                            'conditions' => ['InstallerProjects.id' => $project_id],
                            'order' => array('installers.installer_name' => 'ASC')])->toArray();
        }
        return $stateArr;    
    }
    /**
    *
    * countinstaller
    *
    * Behaviour : public
    *
    * @defination : Method is used to count projectwise installer.
    *
    */
    public function countinstaller($id){
        
          $installerlist = $this->find('all',array('conditions'=>['project_id' => $id ]))->count();
          return $installerlist;
    }
}
?>