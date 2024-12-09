<?php
namespace App\Controller\Admin;

class InstallerUsersController extends ApiMasterController
{	
	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
        // Always enable the CSRF component.
		parent::initialize();
		
		$this->loadModel('ApiToken');
		$this->loadModel('Installers');
		$this->loadModel('InstallerPlans');
		$this->loadModel('InstallerUsers');
	}

    /**
	 *
	 * SetVariables
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to set varibles.
	 *
	 * Author : Khushal Bhalsod
	 */
    private function SetVariables($post_variables) {
		if(isset($post_variables['id']))
			$this->request->data['InstallerUsers']['id']	= $post_variables['id'];
	}


	/**
	 *
	 * getInstallerUserList
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function getInstallerUserList()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;

		$installerdata	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$cus_id)))->first();
		$installer_id 	= (isset($installerdata['id'])?$installerdata['id']:0);
		
		if(!empty($installer_id)) {
			$installerUserArr	= array();
			$arrFiltres['status'] 		= $this->InstallerUsers->STATUS_ACTIVE;
			$arrFiltres['installer_id'] = $installer_id;
			$installerUserArr = $this->InstallerUsers->find('all')->select(array('name','address','pincode','city','state','email','mobile','rights'))->where($arrFiltres)->toArray();
			$rightsArr = (isset($this->installerRoles)?$this->installerRoles:array());
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $installerUserArr);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	*
	* getInstallerUserDetail
	*
	* Behaviour : public
	*
	* @defination : Method is used to get the Installer user detail.
	*
	* Author : Khushal Bhalsod
	*/
	public function getInstallerUserDetail() {

		$this->autoRender = false;
		$this->SetVariables($this->request->data);
		$userId = $this->request->data('user_id');

		$insUserData 	= array();
		if(!empty($userId)) {
			$insUserData = $this->InstallerUsers
									->find('all')
								    ->select(['InstallerUsers.name','InstallerUsers.mobile','InstallerUsers.email'])
								    ->where(array('InstallerUsers.id' =>$userId))->first();
			
			$arrReturn['name'] 		= (isset($insUserData['name'])?$insUserData['name']:'');
			$arrReturn['mobile'] 	= (isset($insUserData['mobile'])?$insUserData['mobile']:'');
			$arrReturn['email'] 	= (isset($insUserData['email'])?$insUserData['email']:'');

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $arrReturn);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}		
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * updateInstallerUserPermission
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Khushal Bhalsod
	 */	
	public function updateInstallerUserPermission()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$userId = $this->request->data('user_id');
		
		if(!empty($userId)) {
			$instUserEntity = $this->InstallerUsers->get($userId);
			$instUserEntity->modified = $this->NOW(); 
			$instUserEntity = $this->InstallerUsers->patchEntity($instUserEntity,$this->request->data());
			$this->InstallerUsers->save($instUserEntity);

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Success');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}



}
