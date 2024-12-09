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

class OffersController extends AppController 
{	
	var $arrDefaultAdminUserRights = array(); 
	
	var $helpers = array('Time','Html','Form','ExPaginator');

	var $parameter_status = array('A'=>"Active",'I'=>"In-Active");

    /*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
      	parent::initialize();
		$this->loadComponent('Paginator');
		$this->loadComponent('Image');
		
		$this->loadModel('Offers');
		$this->loadModel('CustomerOffers');
		$this->loadModel('ApiToken');
    }

    private function SetVariables($post_variables) { 

	}

    /**
	 *
	 * getofferlist
	 *
	 * @defination : Method is use to get offer list.
	 *
	 */	
	public function getofferlist()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$customerId			= $this->ApiToken->customer_id;
		$offerData 			= array();
		$offerData  = $this->Offers->getOfferList($customerId);		
		$this->ApiToken->SetAPIResponse('type', 'ok');
		$this->ApiToken->SetAPIResponse('result', $offerData);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	
	/**
	 *
	 * saveofferlist
	 *
	 * @defination : Method is use to save offer.
	 *
	 */	
	public function saveoffer()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$customerId			= $this->ApiToken->customer_id;
		if(!empty($customerId)) {
			
			$offerPatchEntity	= $this->CustomerOffers->newEntity($this->request->data);
			
			$offerPatchEntity->customer_id 		= $customerId;
			$offerPatchEntity->offer_id 		= $this->request->data['offer_id'];
			$offerPatchEntity->created 			= $this->now();
			
			$this->CustomerOffers->save($offerPatchEntity);

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Offer accepted successfully.');
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Customer not found.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	
}