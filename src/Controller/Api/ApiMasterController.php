<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;;
use Cake\Validation\Validator;

class ApiMasterController extends AppController
{
	public function initialize()
    {

    	$logData = array('request_data' => print_r($_REQUEST,1));
    	$this->writeapilog($logData);
     	//exit("sadsad");
    }	
	
	/**
	 *
	 * write api log
	 *
	 * Behaviour : Public
	 *
	 * @defination :  Method is use to log any action performed by mobile user
	 *
	 */
	public function writeapilog($apiData = array())
	{
		
		$this->loadModel('Apilogs');
		$apiLogEntity = $this->Apilogs->newEntity();
		
		$apiLogEntity->user_id  		=  	isset($apiData['user_id']) ? $apiData['user_id'] : 0;
		$apiLogEntity->device_id    	=  	isset($apiData['device_id']) ? $apiData['device_id'] : '';
		$apiLogEntity->api_title  		=	isset($apiData['api_title']) ? $apiData['api_title'] : '';
		$apiLogEntity->api_type     	= 	isset($apiData['api_type']) ? $apiData['api_type'] : '';
		$apiLogEntity->request_data    	= 	isset($apiData['request_data']) ? $apiData['request_data'] : '';
		$apiLogEntity->response_data   	= 	isset($apiData['response_data']) ? $apiData['response_data'] : '';
		$apiLogEntity->is_success      	= 	isset($apiData['is_success']) ? $apiData['is_success'] : '0';	
		$apiLogEntity->created     	 	= 	$this->NOW();
		$this->Apilogs->save($apiLogEntity);
	}
	
}
