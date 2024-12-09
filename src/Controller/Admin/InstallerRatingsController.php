<?php
namespace App\Controller\Admin;

use App\Controller\AppController;

use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class InstallerRatingsController extends AppController
{	
	
	var $helpers = array('Time','Html','Form','ExPaginator');
	
	public $arrDefaultAdminUserRights 	= array(); 
	public $PAGE_NAME 					= '';

	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();
		$this->loadComponent('Paginator');

		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('Adminaction');
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('Department');
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');
		$this->loadModel('ApiToken');
		$this->loadModel('GhiData');
		$this->loadModel('InstallerRatings');

		$this->set('Userright',$this->Userright);
    }

    private function SetVariables($post_variables) {
		
	}

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 * @return void
	 */
	public function index() {
		
		
	}

	
	/**
	 *
	 * admin_add
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function add()
	{
		$this->intCurAdminUserRight = $this->Userright->ANALYSTS_ADD;
		$this->setAdminArea();
		$userEntity = $this->Users->newEntity($this->request->data() ,['validate' => 'add']);
		/*$this->User->bindModel(
			array('belongsTo' => array(
					'TimeZone' => array(
						'className' => 'TimeZone',
						'foreignKey' => 'timezone',
					)
				)
			)
		);*/

		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();

		if(!$userEntity->errors() && !empty($this->request->data)) {
			$this->request->data['Users']['userrights'] = ltrim($this->arrDefaultAdminUserRights);
            $this->request->data['Users']['apikey'] = sha1(time());
			$newUsers =  $this->Users->newEntity($this->request->data['Users']);
			if($this->Users->save($newUsers)) {
				$this->UserDepartment->AddUserDepartment($newUsers->id,$this->request->data['Users']);
				$this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ANALYSTS_ADD,$newUsers->id,'Added Admin user id::'.$newUsers->id);
				$this->Flash->set('User has been saved.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
				return $this->redirect(WEB_ADMIN_PREFIX.'/users');
			}
		}
		if(isset($this->data['User']['timezone'])) $timezone = $this->data['User']['timezone'];
		$ADMINUSER_RETAILERS = array();
		$this->set('Users',$userEntity);
		$this->set('emailrights',array());
		$this->set('department',$this->Department->GetDepartmentList());
		$this->set('data',$this->request->data);
		$this->set('timezone',$timezone);
		$this->set('DEFAULT_USER_TIMEZONE', $this->Users->DEFAULT_USER_TIMEZONE);
		$this->set('ADMINUSER_RETAILERS', $ADMINUSER_RETAILERS);
		$this->set('arrError', $arrError);
	}

	/**
	 *
	 * save_installer_rating
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to save installer rating(feedback).
	 *
	 */
	public function save_installer_rating()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$installer_id		= $this->ApiToken->customer_id;
		if(!empty($installer_id)) {
			$ratingPatchEntity					= $this->InstallerRatings->newEntity($this->request->data());
			$ratingPatchEntity->installer_id 	= $installer_id;
			$ratingPatchEntity->points 			= $this->request->data['rate'];
			$ratingPatchEntity->rating_type_id 	= 1;
			$ratingPatchEntity->created 		= date("Y-m-d H:i:s");
			$this->InstallerRatings->save($ratingPatchEntity);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Rating save successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Customer not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}
