<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Network\Session;
use Cake\View\Helper;
use Cake\Core\App;
//use Cake\View\Helper\SessionHelper;
use Cake\Utility\Hash;


class FrontAppController extends AppController
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
        ]
       
    ];
    
    /**
     *
     * setMemberArea
     *
     * Behaviour : Public
     *
     * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
     * @defination : Method is use to set admin area use for admin base on restriction set for particular
     *
     */
    public function setMemberArea($isRistricted=true)
    {
      /** SET GLOBAL VARIABLES TO BE USED EVERY VIEW */
      $this->set('member_id',$this->Session->read('Members.id'));
      $this->set('member_email',$this->Session->read('Members.email'));
      $this->set('member_type',$this->Session->read('Members.member_type'));
      $this->set('member_state',$this->Session->read('Members.state'));
      $this->set('member_name',$this->Session->read('Members.name'));
      /** SET GLOBAL VARIABLES TO BE USED EVERY VIEW */
      if (!$this->Session->check('Members.id') && $isRistricted) {
        if ($this->request->is('ajax'))
        {
          $this->header('HTTP/1.1 401: SESSION TIMEOUT');
          echo json_encode(array("Data"=>"SESSION TIMEOUT"));
          $this->response->send();
          exit;
        }
        $msg = "You need to be logged in to access this area.";
        $this->Flash->error($msg);
        return $this->redirect('/');
      } elseif ($this->Session->check('Members.id') && !$isRistricted) {
        return $this->redirect('/');
      }
      $PageTitle = $this->getPageTitle();
      if (!empty($PageTitle)) $this->set("title_for_layout",$PageTitle);
      $this->set("IP_ADDRESS",$this->_getipaddress());
    }

    /**
     *
     * setCustomerArea
     *
     * Behaviour : Public
     *
     * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
     * @defination : Method is use to set admin area use for admin base on restriction set for particular
     *
     */
    public function setCustomerArea($isRistricted=true)
    {
      $this->set('customers_id',$this->Session->read('Customers.id'));
      $this->set('customers_email',$this->Session->read('Customers.email'));
      $this->set('customer_type',$this->Session->read('Customers.customer_type'));
      $this->set('customers_name',$this->Session->read('Customers.name'));
      $this->set('customers_state',$this->Session->read('Customers.state'));
      /** SET GLOBAL VARIABLES TO BE USED EVERY VIEW */

      if (!$this->Session->check('Customers.id') && $isRistricted) {
        if ($this->request->is('ajax'))
        {
          $this->header('HTTP/1.1 401: SESSION TIMEOUT');
          echo json_encode(array("Data"=>"SESSION TIMEOUT"));
          $this->response->send();
          exit;
        }
        $msg = "You need to be logged in to access this area.";
        //$this->Session->setFlash($msg,"default",array("class"=>"alert-success","icon"=>"icon-info","msgtype"=>"Success :"));
        $this->Flash->error($msg);
        return $this->redirect('/');
      } elseif ($this->Session->check('Customers.id') && !$isRistricted) {
        return $this->redirect('/');
      }
      $PageTitle = $this->getPageTitle();
      if (!empty($PageTitle)) $this->set("title_for_layout",$PageTitle);
      $this->set("IP_ADDRESS",$this->_getipaddress());

    }

    /**
    * getPageTitle
    * Behaviour : Public
    * @return : Page Title
    *
    */
    private function getPageTitle()
    {
      return PAGE_TITLE;
    }


    /**
    *
    * _getipaddress
    *
    * Behaviour : Public
    *
    * @return : IP address
    * @defination :  Method is use to get ipaddress base on url
    *
    */
    function _getipaddress()
    {
      $ipaddress = "";
      if(isset($_ENV['HTTP_X_FORWARDED_FOR'])) $ipaddress = $_ENV['HTTP_X_FORWARDED_FOR'];
      elseif(isset($_ENV['REMOTE_ADDR'])) $ipaddress = $_ENV['REMOTE_ADDR'];
      elseif(isset($_SERVER['REMOTE_ADDR'])) $ipaddress = $_SERVER['REMOTE_ADDR'];
      return $ipaddress;
    }
    
	
    public function initialize()
    {
        
      /*  $this->loadComponent('Auth',
                    [
                    'authorize' => ['Controller'], // Added this line
                    'loginAction' => [
                        'controller' => 'users',
                        'action' => 'login'
                     ],'authenticate' => [
                          'Form' => [
                            'userModel' => 'Customers', // Added This
                            'fields' => [
                              'username' => 'email',
                              'password' => 'password',
                             ]
                           ]
                    ],'loginRedirect' => [
                         'controller' => 'users',
                         'action' => 'index'
                    ],
                ]);*/
        // serch sesstion delete
        $this->loadModel('Sessions');


        $this->layout = "frontend";
        parent::initialize();

        if(strtolower($this->request->controller) != 'applyonlines' || strtolower($this->request->action) != 'applyonline_list') {
          if($this->Session->check("MembersSearch")) {
            $this->Session->delete("MembersSearch");
          }
        }

        if(strtolower($this->request->controller) != 'projects' || strtolower($this->request->action) != 'index'){
          if($this->Session->check("ProjectsSearch")) {
            $this->Session->delete("ProjectsSearch");
          } 
        }
        
    }



    public function isAuthorized($user)
    {
        
        // customer can use following actions
        if (isset($user['customer_type']) && ($user['customer_type'] === 'customer' || $user['customer_type'] === 'installer')) {
            return true;
        }

        // Default deny
        return false;
    }


}
?>