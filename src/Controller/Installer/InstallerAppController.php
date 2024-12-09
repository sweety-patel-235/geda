<?php
namespace App\Controller\Installer;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Event\Event;


class InstallerAppController extends AppController
{
    public $helpers = [
      'Html' => [
          'className' => 'BootstrapUI.Html'
      ],
      'Form' => [
          'className' => 'BootstrapUI.Form'
      ],
      'Paginator' => [
          'className' => 'BootstrapUI.Paginator'
      ],
    ];
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Auth',
                    [
                    'authorize' => ['Controller'], // Added this line
                    'loginAction' => [
                        'controller' => '../users',
                        'action' => 'login',

                     ],'authenticate' => [
                          'Form' => [
                            'userModel' => 'Customers', // Added This
                            'fields' => [
                              'username' => 'email',
                              'password' => 'password',
                             ]
                           ]
                    ],'loginRedirect' => [
                         'controller' => '/user',
                         'action' => 'index'
                    ],
                ]);
        $this->layout = "frontend";

        
    }


    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
		// Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
		if($this->ismobile()){
			$this->Auth->allow();
		}else {
			$this->Auth->allow(['register']);
		}
	}
    public function isAuthorized($user)
    {
		// customer can use following actions
        if(isset($user['customer_type']) && $user['customer_type'] === 'installer') {
            /*if(!($this->Vendors->exists(['id' => $user['vendor_id']]))){
                return false;
            }*/
            return true;
        }
        // Default deny
        return false;
    }
	/**
	* EncodeAuth
	* Behaviour		: Public
	* @defination	: Method is use to encode authentication data
	*/
	public function EncodeAuth($data)
	{
		return base64_encode($data);
	}

	/**
	* DecodeAuth
	* Behaviour		: Public
	* @defination	: Method is use to decode authentication data
	*/
	public function DecodeAuth($data)
	{
		return base64_decode($data);
	}

    //Kalpak - 13-11-2014 START
    public function validatePartnerSiteRequest()
	{
		if(!isset($_SERVER['PHP_AUTH_USER']) || $_SERVER['PHP_AUTH_USER']!= HTTP_PHP_AUTH_USER || !isset($_SERVER['PHP_AUTH_PW']) || $_SERVER['PHP_AUTH_PW']!=HTTP_PHP_AUTH_PASSWD) {
			$this->REST_Error_Handling(400, 'fail', 'Bad Request');
            return false;
        }
        $this->header_x_hash = $this->request->header('X-Hash');
        if(!$this->ApiToken->ValidateHash($this->header_x_hash, $this->request->data)) {
			$this->REST_Error_Handling(400, 'fail', 'Bad Request. In Valid Data.');
            return false;
		}
		return true;
	}
    //Kalpak - 13-11-2014 END


	//Jitendra - 20-09-2014 START

	public function validateRequest($logins=false) {

		$this->header_x_public		= $this->request->header('token');
		$this->header_x_hash		= $this->request->header('X-Hash');
		$this->device_id			= $this->request->header('device_id');
        $hash                       = $this->request->data('x-hash');

		//echo "====>".$this->header_x_public."<=====>".$this->header_x_hash."<====";
		$this->log('REQUEST DATA START', 'debug');
		if(count($this->request->data)>0) {
			$this->log($this->request->data, 'debug');
			$this->log('POST Request', 'debug');
			$hash = $this->ApiToken->GenerateHash($this->request->data);
			$data = $this->request->data;
		} else if(isset($this->request->query)){
			$this->log($this->request->query, 'debug');
			$this->log('GET Request', 'debug');
			$hash = $this->ApiToken->GenerateHash($this->request->query);
			$data = $this->request->query;
		}
		$this->log('REQUEST DATA END', 'debug');
		$this->log('X-Public HEADER :: =>'.$this->header_x_public, 'debug');
		$this->log('X-Hash HEADER :: =>'.$this->header_x_hash, 'debug');
		$this->log('device_id HEADER :: =>'.$this->device_id, 'debug');
		$this->log('Generated Hash :: =>'.$hash, 'debug');

		if(!$this->ApiToken->ValidateToken($this->header_x_public, $this->device_id)) {
			$this->blnTokenValidate	= false;
		}
		$this->ApiToken->SetAPIResponse('token', $this->ApiToken->token);
		$this->response->header('X-Public', $this->ApiToken->token);
		
		/* if(!$this->ApiToken->ValidateHash($this->header_x_hash, $data)) {
			if(!$logins || empty($hash)) {
                $this->REST_Error_Handling(400, 'fail', 'Bad Request');
            }
			return false;
		} */
		return true;
	}
	public function blackhole(){

		$message	= 'Invalid Request blackhole';
		$this->set(array(
						'message' => $message,
						'_serialize' => array('message')
					));
		//$this->render(FALSE, 'blackhole'); //I needed to add this
		$this->Session->setFlash(__('Unable to update your phone.'));
		$this->response->send();
		$this->_stop();
	}
	private function REST_Error_Handling($status_code, $status, $message) {
		$this->layout	= 'empty';
		$ext			= (isset($this->request->params['ext']))?$this->request->params['ext']:'json';
		$this->set('ext', $ext);
		$this->set('response', array('status'=>$status,'error'=>$message));
		$this->render('/Element/rest/bad_request'); //I needed to add this
		$this->response->statusCode($status_code);
		$this->response->send();
		exit;
	}
}