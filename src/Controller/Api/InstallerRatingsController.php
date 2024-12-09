<?php
namespace App\Controller\Api;

class InstallerRatingsController extends ApiMasterController
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
		$this->loadModel('InstallerRatings');
    }

    private function SetVariables($post_variables) {
		
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
			
			$ratingPatchEntity	= $this->InstallerRatings->newEntity($this->request->data());
			
			$ratingPatchEntity->installer_id 	= $installer_id;
			$ratingPatchEntity->points 			= $this->request->data['rate'];
			$ratingPatchEntity->rating_type_id 	= 1;
			
			$this->InstallerRatings->save($ratingPatchEntity);

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Rating save successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Installer not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;

	}	
}
