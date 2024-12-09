<?php
namespace App\Controller\Api;
/**
 *
 * Installer Plan Controller
 *
 * @defination : Class is used for managing the plans for installer user in the site.
 *
 * Author : Khushal Bhalsod
 */
class InstallerPlansController extends ApiMasterController
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
		$this->loadModel('InstallerPlans');
		$this->loadModel('Adminaction');
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
			$this->request->data['InstallerPlans']['id']	= $post_variables['id'];
	}

	/**
	 *
	 * getinstallerActiveplan
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to get search installer.
	 *
	 * Author : Khushal Bhalsod
	 */
	public function getinstallerActiveplan()
	{
		$this->autoRender 	= false;
		$installerData 		= array();
		$this->SetVariables($this->request->data);
		
		$arrFiltres['status'] 	= $this->InstallerPlans->STATUS_ACTIVE;
		$installerPlanData  	= $this->InstallerPlans->find('all')->where($arrFiltres)->toArray();
		
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $installerPlanData);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
}
