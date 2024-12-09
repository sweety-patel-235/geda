<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class CustomersController extends AppController
{
    public $user_department = array();
    public $arrDefaultAdminUserRights = array();
    public $helpers = array('Time','Html','Form','ExPaginator');
    public $PAGE_NAME = '';

    private function SetVariables($post_variables) {
        if(isset($post_variables['mobile']))
            $this->request->data['Customers']['mobile']		= $post_variables['mobile'];
        if(isset($post_variables['email']))
            $this->request->data['Customers']['email']		= $post_variables['email'];
        if(isset($post_variables['name']))
            $this->request->data['Customers']['name']		= $post_variables['name'];
        if(isset($post_variables['pass']))
            $this->request->data['Customers']['password']	= $post_variables['pass'];
        if(isset($post_variables['state']))
            $this->request->data['Customers']['state']		= $post_variables['state'];
        if(isset($post_variables['token']))
            $this->ApiToken->token	= $post_variables['token'];
    }

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
        $this->loadModel('Customers');
        $this->loadModel('Sessions');
        $this->loadModel('States');
        $this->loadModel('Department');
        $this->loadModel('UserDepartment');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');
        $this->loadModel('ApiToken');
        $this->loadModel('SmsResponse');
        $this->loadModel('Installers');
        $this->loadModel('CheckUserRole');
		$this->loadModel('Parameters');
		$this->loadModel('ProjectAssignBd');
        $this->loadModel('ApplyOnlineApprovals');
		$this->set('Userright',$this->Userright);
    }
    /*
     * Displays a index
     *
     * @param mixed What page to display
     * @return void
     */
    public function index() {

        $this->intCurAdminUserRight = $this->Userright->LIST_CUSTOMER;
        $this->setAdminArea();

        if (!empty($this->Customers->validate)) {
            foreach ($this->Customers->validate as $field => $rules) {
                $this->Customers->validator()->remove($field); //Remove all validation in search page
            }
        }

        $arrcustomerList	= array();
        $arrCondition		= array();
        $this->SortBy		= "Customers.id";
        $this->Direction	= "ASC";
        $this->intLimit		= PAGE_RECORD_LIMIT;
        $this->CurrentPage  = 1;
        $option 			= array();
        $option['colName']  = array('id','name','email','mobile','last_login_date','created','action');

        $this->SetSortingVars('Customers',$option);
        $arrCondition		= $this->_generateCustomerSearchCondition();

        /*$arrCondition['between'] = ["Users.lastlogin","2015-10-01 01:00:00","2015-10-02 23:59:59"];*/
        $this->paginate		= array('conditions' => $arrCondition,
            'fields' => array('id','name','email','mobile','status','last_login_date','created'),
            'order'=>array($this->SortBy => $this->Direction),
            'page'=> $this->CurrentPage,
            'limit' => $this->intLimit);
        $arrcustomerList	= $this->paginate('Customers');
        $arrUserType['']	= "Select";



        //$usertypes = $this->Userrole->getAdminuserRoles();
        $usertypes = array();
        //foreach($usertypes as $key=>$value) $arrUserType[$key] = $value;

        $option['dt_selector']	='table-example';
        $option['formId']		='formmain';
        $option['url']			= WEB_ADMIN_PREFIX.'customers';
        $JqdTablescr 			= $this->JqdTable->create($option);
        $this->set('arrcustomerList',$arrcustomerList->toArray());
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('arrUserType',$arrUserType);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("page_count",(isset($this->request->params['paging']['Customers']['pageCount'])?$this->request->params['paging']['Customers']['pageCount']:0));
        $out = array();

        /*$blnEditAdminuserRights		= $this->Userright->checkadminrights($this->Userright->ANALYSTS_EDIT);
        $blnEnableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_ENABLE);
        $blnDisableAdminuserRights	= $this->Userright->checkadminrights($this->Userright->ANALYSTS_DISABLE);*/
        //pr($arrcustomerList->toArray()); exit;
        $blnEditCustomersRights		= $this->Userright->checkadminrights($this->Userright->EDIT_CUSTOMER);
        foreach($arrcustomerList->toArray() as $key => $val) {
            $temparr = array();
            foreach($option['colName'] as $key) {
                if(isset($val[$key])) {
                    if($key == 'last_login_date' || $key == 'created')
                        $temparr[$key] = date("d-m-Y H:i:s",strtotime($val[$key]));
                    else
                        $temparr[$key] = $val[$key];
                }else{
                    $temparr[$key]='';
                }
                if($key == 'action') {
                    if($key=='action') {
                        $temparr['action']='';
                        //$temparr['action'].= $this->Userright->linkEditAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/view/'.encode($val['id']),'<i class="fa fa-edit"> </i>','','viewRecord',' title="View Customer Info"')."&nbsp;";
                        if($blnEditCustomersRights){
                            if(empty($val['status']))
                                $temparr['action'].= $this->Userright->linkEnableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/enable/'.encode($val['id']),'<i class="fa fa-check-circle-o"></i>','','actionRecord',' title="Activate"')."&nbsp;";
                            if(!empty($val['status']))
                                $temparr['action'].= $this->Userright->linkDisableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/disable/'.encode($val['id']),'<i class="fa fa-circle-o"></i>','','actionRecord',' title="In-Activate"')."&nbsp;";
                            $temparr['action'].= $this->Userright->linkListProjects(constant('WEB_URL').constant('ADMIN_PATH').'projects/index/'.encode($val['id']),'<i class="fa fa-eye"></i>','',' target="_blank" title = "View Projects"')."&nbsp;";
                        }
                    }
                }
            }
            $out[] = $temparr;
        }
        if ($this->request->is('ajax')){
            header('Content-type: application/json');
            echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
                "recordsTotal"    => intval( $this->request->params['paging']['Customers']['count']),
                "recordsFiltered" => intval( $this->request->params['paging']['Customers']['count']),
                "data"            => $out));
            die;
        }
    }

    /**
     *
     * _generateCustomerSearchCondition
     *
     * Behaviour : Private
     *
     * @param : $id  : Id is use to identify for which user condition to be generated if its not null
     * @defination : Method is use to generate search condition using which admin user data can be listed
     *
     */
    private function _generateCustomerSearchCondition($id=null)
    {
        $arrCondition	= array();
        $blnSinCompany	= true;
        if(!empty($id)) $this->request->data['Customers']['id'] = $id;
        if(count($this->request->data)==0) $this->request->data['Customers']['status'] = $this->Customers->STATUS_ACTIVE;
        if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['Customers']['id']) && trim($this->request->data['Customers']['id'])!='')
            {
                $strID = trim($this->request->data['Customers']['id'],',');
                $arrCondition['Customers.id'] = $this->request->data['Customers']['id'];/* array_unique(explode(',',$strID));*/
            }

            if(isset($this->request->data['Customers']['status']) && !empty($this->request->data['Customers']['status']))
            {
                $status = $this->request->data['Customers']['status'];
                if($this->request->data['Customers']['status']=='I') $status = $this->Customers->STATUS_INACTIVE;
                $arrCondition['Customers.status'] = $status;
            }

            if(isset($this->request->data['Customers']['username']) && $this->request->data['Customers']['username']!='')
            {
                $arrCondition['Customers.username LIKE'] = '%'.$this->request->data['Customers']['username'].'%';
            }

            if(isset($this->request->data['Customers']['email']) && $this->request->data['Customers']['email']!='')
            {
                $arrCondition['Customers.email LIKE'] = '%'.$this->request->data['Customers']['email'].'%';
            }

            if(isset($this->request->data['Customers']['mobile']) && $this->request->data['Customers']['mobile']!='')
            {
                $arrCondition['Customers.mobile LIKE'] = '%'.$this->request->data['Customers']['mobile'].'%';
            }
            if(isset($this->request->data['Customers']['city']) && $this->request->data['Customers']['city']!='')
            {
                $arrCondition['Customers.city LIKE'] = '%'.$this->request->data['Customers']['city'].'%';
            }
            if(isset($this->request->data['Customers']['designation']) && $this->request->data['Customers']['designation']!='')
            {
                $arrCondition['Customers.designation LIKE'] = '%'.$this->request->data['Customers']['designation'].'%';
            }

            if(isset($this->request->data['Customers']['usertype']) && $this->request->data['Customers']['usertype']!='')
            {
                $arrCondition['Customers.usertype'] = $this->request->data['Customers']['usertype'];
            }
            if(isset($this->request->data['Customers']['name']) && $this->request->data['Customers']['name']!='')
            {
                $arrCondition['Customers.name LIKE'] = '%'.$this->request->data['Customers']['name'].'%';
            }
            if(isset($this->request->data['Customers']['search_date']) && $this->request->data['Customers']['search_date']!='')
            {
                if($this->request->data['Customers']['search_period'] == 1 || $this->request->data['Customers']['search_period'] == 2)
                {
                    $arrSearchPara	= $this->Customers->setSearchDateParameter($this->request->data['Customers']['search_period'],$this->modelClass);

                    $this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Customers->findConditionByPeriod( $this->request->data['Customers']['search_date'],
                    $this->request->data['Customers']['search_period'],
                    $this->request->data['Customers']['DateFrom'],
                    $this->request->data['Customers']['DateTo'],
                    $this->Session->read('Customers.timezone'));
                if(!empty($arrperiodcondi)){
                    $arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
        }
        return $arrCondition;
    }


    /**
     *
     * admin_disable
     *
     * Behaviour : Public
     *
     * @param : $id   : Id is use to identify particular admin whoes account is to be disabled
     * @defination : Method is use to disable particular User who profile is active
     *
     */
    function disable($id=null) {
        $this->initAdminRightHelper();
        $this->intCurAdminUserRight = $this->Userright->EDIT_CUSTOMER;
        $id = intval(decode($id));
        $this->setAdminArea();

        if(((int)$id)==0) $this->redirect('index');
        if($this->Customers->updateAll(['status' => 0], ['id' => $id]))
        {
            $this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->IN_ACTIVATED_CUSTOMER,$id,'Inactivated Customer id :: '.$id);
            $this->Flash->set('Customer has been In-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
            return $this->redirect(array('action'=>'index'));
            exit;
        }
        else
        {
            $this->Flash->set('Customer In-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
        }
    }
    /**
     *
     * admin_enable
     *
     * Behaviour : Public
     *
     * @param : $id   : Id is use to identify admin whoes profile is to be activate
     * @defination : Method is use to enabled the admin profile who is disabled
     *
     */
    function enable($id=null) {
        $this->initAdminRightHelper();
        $this->intCurAdminUserRight = $this->Userright->EDIT_CUSTOMER;
        $id = intval(decode($id));
        $this->setAdminArea();

        if(((int)$id)==0) $this->redirect('index');
        if($this->Customers->updateAll(['status' => 1], ['id' => $id]))
        {
            $this->writeadminlog($this->Session->read('User.id'),$this->Adminaction->ACTIVATED_CUSTOMER,$user_arr->id,'Activated Customer id :: '.$user_arr->id);
            $this->Flash->set('Customer has been Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
            return $this->redirect(array('action'=>'index'));
            exit;
        }
        else
        {
            $this->Flash->set('Customer Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
            exit;
        }
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
     * state_list
     *
     * Behaviour : Public
     *
     * @defination : Method is use state_list
     *
     */
    public function state_list()
    {
        //$StatesList = $this->States->find("all",['fields'=>['id','statename']])->toArray();
        $States = $this->States->find('all', array(
            'fields' => array('id', 'statename'),
            'order' => array('statename' => 'ASC')
        ))->toArray();
        $StatesList = array();
        if(!empty($States)){
            foreach ($States as $State) {
                $StatesList[] = array("id"=>$State['id'],"statename"=>$State['statename']);
            }
            $msg = "State List.";
            $this->ApiToken->SetAPIResponse('type', 'ok');
        } else {
            $msg = "No State List found.";
            $this->ApiToken->SetAPIResponse('type', 'error');
        }
        $this->ApiToken->SetAPIResponse('msg', $msg);
        $this->ApiToken->SetAPIResponse('data', $StatesList);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * registration
     *
     * Behaviour : Public
     *
     * @defination : Method is use to add new admin user and provide specific righs using admin interface
     *
     */
    public function registration()
    {
        $customer		  	= null;
        $customercnt	  	= 0;
        $this->autoRender 	= false;
        $this->SetVariables($this->request->data);
        $customersEntity 	= $this->Customers->newEntity($this->request->data());
        $customersEntity->created = $this->NOW();
        $customercnt		= $this->Customers->find('all', array('conditions'=>array('email'=>$this->request->data['email'])))->count();
        $mob="/^[1-9][0-9]*$/";
        if(!preg_match($mob, $customersEntity->mobile))
        {
            $status				= 'error';
            $error				= 'Please enter valid mobile number.';
            $this->ApiToken->SetAPIResponse('msg', $error);
            $this->ApiToken->SetAPIResponse('type', $status);
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        } else {
            if ($_SERVER['REMOTE_ADDR'] != "203.88.138.46" && Configure::read('SERVER_MODE') != "PROD") {
                $status				= 'error';
                $error				= 'We are in maintanance mode. Please try after sometime.';
                $this->ApiToken->SetAPIResponse('msg', $error);
                $this->ApiToken->SetAPIResponse('type', $status);
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        }
        if($customercnt == 0) {
            if ($this->Customers->save($customersEntity)) {
                $status				= 'ok';
                /* Activation code generated and sent SMS and email */
                $activation_code	= $this->Customers->GenerateActivationCode($customersEntity->id);
                $this->SendActivationCodeToCustomer($customersEntity->id,$activation_code, $customersEntity->email, $customersEntity->mobile);
                /* Activation code generated and sent SMS and email */

                /* On registration logged in user. */
                $this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity->id);
                $this->Customers->LoggedinUser($customersEntity->id);
                /* On registration logged in user. */

                //$this->SendNewUserRegistrationNotificationEmail($customersEntity->id, $customer['Customers']['email'], $customer['Customers']['mobile']);
                $this->ApiToken->SetAPIResponse('cus_id', $customersEntity->id);
                $this->ApiToken->SetAPIResponse('active_status', $this->Customers->STATUS_INACTIVE);
            } else {
                $status				= 'error';
                $error				= '';
                $this->ApiToken->SetAPIResponse('msg', $error);
            }
        } else {
            $status				= 'error';
            $error				= 'This email is already registered.';
            $this->ApiToken->SetAPIResponse('msg', $error);
        }
        $this->ApiToken->SetAPIResponse('type', $status);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
    /**
     *
     * SendActivationCodeToCustomer
     *
     * Behaviour : private
     *
     * @defination : Method is use to add new admin user and provide specific righs using admin interface
     *
     */
    private function SendActivationCodeToCustomer($customer_id,$activation_code, $email, $mobile, $blnEmail=true)
    {
        if (!empty($mobile) && SEND_SMS) {
            //Send sms to customer
            $this->Customers->SendSMSActivationCode($customer_id,$mobile,$activation_code);
        }
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && $blnEmail && SEND_EMAIL) {
            //Send email to customer.
            $Email = new Email('default');
            $Email->profile('default');
            $Email->viewVars(array('activation_code' => $activation_code));
            $Email->template('send_activation_code', 'empty')
                ->emailFormat('text')
                ->subject(Configure::read('EMAIL_ENV').PRODUCT_NAME.' Activation Code')
                ->to($email)
                ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                ->send();
        }
    }
    private function SendWellComeEmailToCustomer($customer_name, $email)
    {
        //$email="pravin.sanghani@yugtia.com";
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && SEND_EMAIL) {
            //Send email to customer.
            $Email = new Email('default');
            $Email->profile('default');
            $Email->viewVars(array('customer_name' => $customer_name));
            $Email->template('send_well_come_email', 'default')
                ->emailFormat('html')
                ->subject(Configure::read('EMAIL_ENV')."Welcome to ".PRODUCT_NAME)
                ->to($email)
                ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                ->send();
        }
    }
    /**
     *
     * login
     *
     * Behaviour : public
     *
     * @defination : Method is use to add new admin user and provide specific righs using admin interface
     *
     */
    public function login()
    {
        $this->autoRender = false;
        $this->SetVariables($this->request->data);
        if ($_SERVER['REMOTE_ADDR'] != "203.88.138.46" && Configure::read('SERVER_MODE') != "PROD") {
            $status				= 'error';
            $error				= 'We are in maintanance mode. Please try after sometime.';
            $this->ApiToken->SetAPIResponse('msg', $error);
            $this->ApiToken->SetAPIResponse('type', $status);
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        }
        if (!empty($this->request->data)) {
            $customersEntity = $this->Customers->newEntity($this->request->data,['validate' => 'login']);
            if ($this->request->data['Customers']['password'] != Configure::read('AHA_LOGIN_MASTER_PASSWORD')) {
                $conditions = array(
                    "Customers.email" => (isset($this->request->data['Customers']['email'])?$this->request->data['email']:''),
                    "Customers.password" =>  Security::hash(Configure::read('Security.salt') . (isset($this->request->data['Customers']['password'])?$this->request->data['Customers']['password']:'')));
            } else {
                $conditions = array("Customers.email"=>(isset($this->request->data['Customers']['email'])?$this->request->data['email']:''));
            }
            $error='';
            $customersEntity = $this->Customers->find('all',array('conditions' => $conditions))->toArray();
            
            
            if(empty($customersEntity[0]['id'])) {
                $status				= 'error';
                $error				= 'Please check user detail';
            } else {
                /* Check customer is installer or not */
                $installercnt	= $this->Installers->find('all', array('conditions'=>array('id'=>$customersEntity[0]['installer_id'])))->count();
                $is_installer 	= ($installercnt>0)?'true':'false';
                $paid_member    = false;
                /* Update Last Login Date */
                $custData 			= $this->Customers->get($customersEntity[0]['id']);
                $this->Customers->updateAll(array('last_login_date'=>$this->NOW()),array('id'=>$customersEntity[0]['id']));
                
                //$custPatchEntity 	= $this->Customers->patchEntity($custData,$this->request->data());
                //$custPatchEntity->last_login_date = $this->NOW();
               // $this->Customers->save($custPatchEntity);
                /* Send OTP for in active customer */
                //pr($customersEntity);exit;
                if(isset($customersEntity[0]['status']) &&  $customersEntity[0]['status'] == $this->Customers->STATUS_INACTIVE) {
                    $activation_code = $this->Customers->GenerateActivationCode($customersEntity[0]['id']);
                    $this->SendActivationCodeToCustomer($customersEntity[0]['id'],$activation_code, '', $customersEntity[0]['mobile']);
                }
                /* Send OTP for in active customer */
                $status				= 'ok';
                $this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customersEntity[0]['id']);
                $this->ApiToken->SetAPIResponse('cus_id', $customersEntity[0]['id']);
                $state_logo_arr['logo'] = '';
                if($customersEntity[0]['state'] == 4 || strtolower($customersEntity[0]['state']) == 'gujarat')
                {
                    $state_logo_arr = array('id'=>'4','image' => array(URL_HTTP.'img/state/4/1_4.png',URL_HTTP.'img/state/4/2_4.png'));
                } 
                else {
                    $state_logo_arr = array('id'=>$customersEntity[0]['state'],'image' => array());
                }
                $STATENAME = "";
                if(isset($customersEntity[0]['state']) && $customersEntity[0]['state'] !=""){
                    $states_data = $this->States->find('all', array(
                        'fields' => array('statename'),
                        'conditions' => array('id' => $customersEntity[0]['state'])
                    ))->toArray();
                    if (isset($states_data[0]['statename'])) {
                        $STATENAME = $states_data[0]['statename'];
                    }
                }
                $installerData      = $this->Installers->find('all', array('conditions'=>array('id'=>$customersEntity[0]['installer_id'])))->first();
                $installername      = '';
                if(!empty($installerData))
                {
                    $installername  = $installerData->installer_name;
                }
                $assign_slots           = array();
                $arr_data =array();
                $max =array();
                if(isset($customersEntity[0]['installer_id']) && !empty($customersEntity[0]['installer_id']))
                {
                    $arr_condition      = array("installer_id" => $customersEntity[0]['installer_id']);
                    $InstallerList      = TableRegistry::get('InstallerCategoryMapping');
                    $arr_result         = $InstallerList->find('all',array('conditions'=>$arr_condition))->first();
                    if(!empty($arr_result))
                    {
                        $arr_assign_band    = json_decode($arr_result['allowed_bands']);
                        foreach($arr_assign_band as $key => $value_band)
                        {
                            $assign_slots[] = intval($this->ApplyOnlines->installer_slot_array[$value_band]['min']).' - '.intval($this->ApplyOnlines->installer_slot_array[$value_band]['max']).' kW';
                            $arr_data[$key]['min']          = intval($this->ApplyOnlines->installer_slot_array[$value_band]['min']);
                            $arr_data[$key]['max']          = intval($this->ApplyOnlines->installer_slot_array[$value_band]['max']);
                        } 
                    }
                }
                $assign_slot=implode(", ",$assign_slots);
                $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
                $this->ApiToken->SetAPIResponse('state_id', $customersEntity[0]['state']);
                $this->ApiToken->SetAPIResponse('state_name', $STATENAME);
                $this->ApiToken->SetAPIResponse('merchant_key', Configure::read('PAYU_MERCHANT_KEY'));
                $this->ApiToken->SetAPIResponse('merchant_salt', Configure::read('PAYU_MERCHANT_SALT'));
                $this->ApiToken->SetAPIResponse('payumoneyurl', Configure::read('PAYU_PAYMENT_URL'));
				$this->ApiToken->SetAPIResponse('PAYU_SANDBOX', Configure::read('PAYU_SANDBOX'));
                $this->ApiToken->SetAPIResponse('active_status', $customersEntity[0]['status']);
                $this->ApiToken->SetAPIResponse('name', $customersEntity[0]['name']);
                $this->ApiToken->SetAPIResponse('is_installer', $is_installer);
                $this->ApiToken->SetAPIResponse('paid_member', $paid_member);
                $this->ApiToken->SetAPIResponse('upload_pdf_limit', Configure::read('UPLOAD_PDF_LIMIT'));
                $this->ApiToken->SetAPIResponse('upload_image_limit', Configure::read('UPLOAD_IMAGE_LIMIT'));
                $this->ApiToken->SetAPIResponse('mobile', $customersEntity[0]['mobile']);
                $this->ApiToken->SetAPIResponse('email', $customersEntity[0]['email']);
                $this->ApiToken->SetAPIResponse('installer_id', $customersEntity[0]['installer_id']);
                $this->ApiToken->SetAPIResponse('installer_name',$installername);
                $this->ApiToken->SetAPIResponse('pv_capacity_slots',$assign_slot);
                $this->ApiToken->SetAPIResponse('pv_slots',$arr_data);
                
                $access = array();
                $role= "";
                $is_admin = 0;
				if(!empty($customersEntity)){
                    $customersEntity = $customersEntity[0];
                    if ($customersEntity->user_role) {
                        $role 	= $customersEntity->user_role;
                        $access = $this->CheckUserRole->getuserrole($role, 'home_side');
                        if(in_array($this->Parameters->admin_role,explode(",",$role))){
                            $is_admin = 1;
                        }
                     }else{
                        $role 	= 'no_role';
                        $access = $this->CheckUserRole->getuserrole($role,'home_side');
                     }
                }
                $this->ApiToken->SetAPIResponse('is_admin', $is_admin);
                $this->ApiToken->SetAPIResponse('role', $role);
                $this->ApiToken->SetAPIResponse('rights', $access);
            }
        } else {
            $status				= 'error';
            $error				= 'Please check user detail';
        }
        $this->ApiToken->SetAPIResponse('msg', $error);
        $this->ApiToken->SetAPIResponse('type', $status);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    public function otpactivate()
    {
        $this->autoRender 	= false;
        $activation_code	= $this->request->data('otp');
        $cus_id				= $this->ApiToken->customer_id;
        $this->SetVariables($this->request->data);
        $customer			= $this->Customers->GetCustomerByActivationCode($activation_code,$cus_id);
        if(!empty($customer) && $customer[0]['activation_code'] == $activation_code) {
            if($this->Customers->ActivateCustomer($customer)) {
                $this->SendWellComeEmailToCustomer($customer[0]['name'],$customer[0]['email']);
                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('msg', 'User activated successfully.');
            } else {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'Something went wrong in activation.');
            }
        } else {
            $this->response->statusCode(403);
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Invalid activation code.');

        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
    public function forgotpass() {

        $this->autoRender = false;
        $email		= $this->request->data('email');
        $customer	= $this->Customers->find('all', array('conditions'=>array('email'=>$email)))->toArray();
        $customer 	= (isset($customer[0]))?$customer[0]:'';
        if(empty($customer)) {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'User not found.');
        } else {
            $activation_code = $this->Customers->GenerateActivationCode($customer['id']);
            $this->SendActivationCodeToCustomer($customer['id'],$activation_code, $customer['email'], $customer['mobile']);
            $this->ApiToken->LoggedInAPIUser($this->ApiToken->token, $customer['id']);
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('msg', 'You will receive new OTP shortly.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    public function token()
    {
        $this->autoRender 	= false;
        $token 				= $this->ApiToken->GenerateNewToken();
        $version 			= $this->request->data('version');
        $state_logo_arr = array();
        if($version == "" ) {
            $version = '6.4';
            $this->ApiToken->SetAPIResponse('flag', '0');
            $this->ApiToken->SetAPIResponse('msg', 'Sorry for inconvenience, Server is under maintenance. Please try afer sometime');
        }else if($version != CURRENT_VERSION && !in_array($version,explode(',',OLD_VERSION))) {
            $this->ApiToken->SetAPIResponse('flag', '0');
            $this->ApiToken->SetAPIResponse('msg', 'Please update to latest version.');
        } else {
            $this->ApiToken->SetAPIResponse('flag', '1');
            $this->ApiToken->SetAPIResponse('msg', '');
        }
        if(isset($this->request->data['lat']) && !empty($this->request->data['lat']) && isset($this->request->data['lon']) && !empty($this->request->data['lon']) ){
            $lat 			= $this->request->data['lat'];
            $lon 			= $this->request->data('lon');
            $locationdata 	= GetLocationByLatLong($lat,$lon);
            if(isset($locationdata['state']) && strtolower($locationdata['state']) =='jharkhand'){
                $state_logo_arr['logo'] = array('main'=>'http://www.ahasolar.in/img/state/22/1_22.png',
                    'second'=>'http://www.ahasolar.in/img/state/22/3_22.png');
                $this->ApiToken->SetAPIResponse('state_logo', 'http://www.ahasolar.in/img/jharkhand-government-logo.png');
                $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
            } 
            elseif(isset($locationdata['state']) && ($locationdata['state'] == 4 || strtolower($locationdata['state']) == 'gujarat'))
            {
                $state_logo_arr['logo'] = array('main'=>URL_HTTP.'img/state/4/1_4.png',
                    'second'=>URL_HTTP.'img/state/4/2_4.png');
                $this->ApiToken->SetAPIResponse('state_logo', URL_HTTP.'img/state/4/1_4.png');
                $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
            } 
            else {
                $this->ApiToken->SetAPIResponse('state_logo', '');
                $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
            }
        } else {
            $this->ApiToken->SetAPIResponse('state_logo', '');
            $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
        }
        $this->ApiToken->SetAPIResponse('type', 'ok');
        $this->ApiToken->SetAPIResponse('token', $token);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    private function SendForgotPasswordEmailToCustomer($customer_id, $email, $mobile,$pin)
    {
        if (!empty($customer_id) && SEND_SMS) {
            //Send sms to customer
            $this->Customers->SendSMSForgotPassword($customer_id, $mobile, $pin);
        }
        if(!empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && SEND_EMAIL) {
            //Send email to customer.
            $Email = new Email('default');
            $Email->profile('default');
            $Email->viewVars(array('customer_id' => $customer_id, 'pin' => $pin));
            $Email->template('forgot_password', 'empty')
                ->emailFormat('text')
                ->subject(Configure::read('EMAIL_ENV').PRODUCT_NAME.' New Generated Password')
                ->to($email)
                ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                ->send();
        }
    }

    /**
     *
     * changeloginpassword
     *
     * Behaviour : public
     *
     * @defination : Method is use to change the login password.
     *
     */
    public function changeloginpassword() {

        $this->autoRender = false;
        $this->SetVariables($this->request->data);
        $oldpassword	= $this->request->data('pass');
        $new_password	= $this->request->data('new_pass');
        $cus_id			= $this->ApiToken->customer_id;

        $custData 		= $this->Customers->get($cus_id);
        $old_password 	= Security::hash(Configure::read('Security.salt') . $oldpassword);

        if(!empty($custData)) {
            if(isset($oldpassword) && !empty($oldpassword) && !empty($new_password)) {
                if($old_password == $custData['password']) {
                    $custPatchEntity = $this->Customers->patchEntity($custData, $this->request->data);
                    $custPatchEntity->password = $new_password;
                    $this->Customers->save($custPatchEntity);

                    $this->ApiToken->SetAPIResponse('type', 'ok');
                    $this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
                } else {
                    $this->ApiToken->SetAPIResponse('type', 'error');
                    $this->ApiToken->SetAPIResponse('msg', 'Current password does not match.');
                }
            } elseif(empty($oldpassword) && !empty($new_password)) {
                $custPatchEntity = $this->Customers->patchEntity($custData, $this->request->data);
                $custPatchEntity->password 	= $new_password;
                $this->Customers->save($custPatchEntity);

                /* Check customer is installer or not */
                $installercnt	= $this->Installers->find('all', array('conditions'=>array('customer_id'=>$cus_id)))->count();
                $is_installer 	= ($installercnt>0)?'true':'false';

                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
                $this->ApiToken->SetAPIResponse('is_installer', $is_installer);
            }
        } else{
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'User not found.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * getcustomerprofile
     *
     * Behaviour : public
     *
     * @defination : Method is used to get the customer profile.
     *
     */
    public function getcustomerprofile() {

        $this->autoRender = false;
        $this->SetVariables($this->request->data);
        $cus_id		= $this->ApiToken->customer_id;
        $custData 	= array();
        if(!empty($cus_id)) {
            $custData 	= $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id), 'fields'=>array('name','mobile','state')))->toArray();
        }
        $arrReturn 	= (!empty($custData[0]))?$custData[0]:$custData;
        $this->ApiToken->SetAPIResponse('type', 'ok');
        $this->ApiToken->SetAPIResponse('result', $arrReturn);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * setcustomerprofile
     *
     * Behaviour : public
     *
     * @defination : Method is used to get the customer profile.
     *
     */
    public function setcustomerprofile() {

        $this->autoRender = false;
        $this->SetVariables($this->request->data);

        $cus_id	= $this->ApiToken->customer_id;
        $active_status = '';
        if(!empty($cus_id)) {
            $custData = $this->Customers->get($cus_id);
            if($this->request->data['Customers']['mobile'] != $custData['mobile']) {
                $active_status = 0;
                /* Generate Activation code and send */
                $activation_code = $this->Customers->GenerateActivationCode($custData['id']);
                $this->SendActivationCodeToCustomer($custData['id'],$activation_code, '', $this->request->data['Customers']['mobile']);
                /* set customer status to in-active */
                $customer_status = $this->Customers->STATUS_INACTIVE;
            } else {
                $active_status = 1;
                $customer_status = $this->Customers->STATUS_ACTIVE;
            }
            $custPatchEntity = $this->Customers->patchEntity($custData, $this->request->data);
            $custPatchEntity->status = $customer_status;
            unset($custPatchEntity->password);
            $this->Customers->save($custPatchEntity);
            $StateName = $this->GetStateById($custPatchEntity->state);
            if(strtolower($StateName) =='jharkhand') {
                $state_logo_arr[] = 'http://www.ahasolar.in/img/state/22/1_22.png';
                $state_logo_arr[] = 'http://www.ahasolar.in/img/state/22/3_22.png';
                $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
            } 
            elseif(($StateName == 4 || strtolower($StateName) == 'gujarat'))
            {
                $state_logo_arr[] = URL_HTTP.'img/state/4/1_4.png';
                $state_logo_arr[] = URL_HTTP.'img/state/4/2_4.png';
                $this->ApiToken->SetAPIResponse('state_logo_arr', $state_logo_arr);
            } 
            else {
                $this->ApiToken->SetAPIResponse('state_logo_arr',array());
            }

            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('active_status', $active_status);
            $this->ApiToken->SetAPIResponse('msg', 'Profile updated successfully.');
        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('active_status', $active_status);
            $this->ApiToken->SetAPIResponse('msg', 'User not found.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * resendotp
     *
     * Behaviour : public
     *
     * @defination : Method is used to resend otp for verification.
     *
     */
    public function resendotp() {

        $this->autoRender = false;
        $this->SetVariables($this->request->data);
        $cus_id	= $this->ApiToken->customer_id;

        if(!empty($cus_id)) {
            $custData 	= $this->Customers->get($cus_id);
            /* Activation code generated and sent SMS and email */
            $activation_code = $this->Customers->GenerateActivationCode($custData['id']);
            //echo $custData['id']." ".$activation_code." ".$custData['email']." ".$custData['mobile'];
            $this->SendActivationCodeToCustomer($custData['id'],$activation_code, $custData['email'], $custData['mobile']);
            /* Activation code generated and sent SMS and email */
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('msg', 'OTP sent successfully.');
        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'User not found.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * customeroffer
     *
     * Behaviour : public
     *
     * @defination : Method is used to customerr offer.
     *
     * Author : Khushal Bhalsod
     */
    public function customeroffer() {

        $this->autoRender = false;
        $offerText = "Free download of Professional Version";

        $this->ApiToken->SetAPIResponse('type', 'ok');
        $this->ApiToken->SetAPIResponse('offers', $offerText);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }


    /**
     *
     * GetStateById
     *
     * Behaviour : Public
     *
     * @defination : Method is use GetStateById
     *
     */
    public function GetStateById($id=0)
    {
        $this->autoRender = false;
        $statename = "";
        $States = $this->States->find('all', array(
            'fields' => array('id', 'statename'),
            'conditions' => array('id' => intval($id))
        ))->toArray();
        if(!empty($States)) {
            foreach ($States as $State) {
                $statename = $State['statename'];
            }
        }
        return $statename;
    }
	
	/**
     *
     * buisnessDeveloperList
     *
     * Behaviour : Public
     *
     * @defination : Method used for get buisness developer listing
     *
     * Create by : Feni Vadodaria
     */

    public function buisnessDeveloperList(){

        $this->autoRender   = false;
        $customer_id = $this->ApiToken->customer_id;
        $condition = array();
        $Customerlist      = $this->Customers->find('all',array('fields'=>['installer_id'],'conditions'=>['id' => $customer_id ]))->first();
        if(!empty($Customerlist))
        {
            $allcustomer      = $this->Customers->find('all',array('conditions'=>['installer_id' => $Customerlist->installer_id,'Customers.user_role Like' =>"%".$this->Parameters->bd_role."%"]))->toArray();

            if(isset($allcustomer) && !empty($allcustomer)){
                if(isset($this->request->data['project_id']) && $this->request->data['project_id'] !=""){
                    foreach ($allcustomer as $key => $customer){
                        $customer->selected = $this->ProjectAssignBd->getSelectedCustomer($this->request->data['project_id'],$customer->id);
                    }

                }
            }
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('installer_id',$Customerlist->installer_id);
            $this->ApiToken->SetAPIResponse('result', $allcustomer);
        }
        else {
            $error  = 'No Customer found';
            $this->ApiToken->SetAPIResponse('type', 'eroor');
            $this->ApiToken->SetAPIResponse('result', $error);
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
    /**
     *
     * trackmyapplication
     *
     * Behaviour : Public
     *
     * @defination : Method is use to track consumer application.
     */
    public function trackmyapplication()
    {
        $this->autoRender   = false;
        $geda_no            = isset($this->request->data['geda_no']) ? $this->request->data['geda_no'] : '';
        $consumer_no        = isset($this->request->data['consumer_no']) ? $this->request->data['consumer_no'] : '';
        $mobile_no          = isset($this->request->data['mobile_no']) ? $this->request->data['mobile_no'] : '';

        if(!empty($geda_no) && !empty($consumer_no) && !empty($mobile_no)){
            $ApplyOnlineData    = $this->ApplyOnlines->FindByApplicationNo($geda_no,$consumer_no,$mobile_no);
            if(empty($ApplyOnlineData)){
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg','Please enter valid GEDA Registration No, Consumer No and Consumer Mobile No.');
            }
            else
            {
                $arr_application_status     = $this->ApplyOnlineApprovals->all_status_application($ApplyOnlineData->id);
                $APPLY_ONLINE_MAIN_STATUS   = $this->ApplyOnlineApprovals->apply_online_main_status;
                $arr_data                   = array();
                $counter                    =  0;
                $action                     = array();
                foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) 
                {
                    if($key == 9 && SHOW_SUBSIDY_EXECUTION == 1 && $ApplyOnlineData->disclaimer_subsidy == 1)
                    {

                    }
                    else
                    {
                        $IsActive = array_key_exists($key, $arr_application_status)?$status_flag=1:$status_flag = 0;
                        
                        $action[$counter]['stage_no']       = $key;
                        $action[$counter]['stage_title']    = $value;
                        $action[$counter]['stage_flag']     = $IsActive;  
                        $counter++;
                    }
                }
                $arr_data['data']   = $action;
                
                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('msg','Your Application Track Suuccessfully');
                $this->ApiToken->SetAPIResponse('result', $arr_data);
            }
            
        }
        else{
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg','Please enter GEDA Registration No, Consumer No and Consumer Mobile No.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }
}
