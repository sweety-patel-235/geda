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

class InstallerUsersController extends AppController
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
        $this->loadModel('Department');
        $this->loadModel('UserDepartment');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');
        $this->loadModel('ApiToken');
        $this->loadModel('Installers');
        $this->loadModel('Customers');
        $this->loadModel('Parameters');
        $this->loadModel('InstallerPlans');
        $this->loadModel('InstallerUsers');
        $this->loadModel('InstallerActivationCodes');
        $this->loadModel('InstallerTerms');
        $this->loadModel('CheckUserRole');
        $this->set('Userright',$this->Userright);
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

    /*
     * Displays a index
     *
     * @param mixed What page to display
     *
     * @return void
     *
     * Author : Khushal Bhalsod
     */
    public function index() {


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
        $CustomerInfo   = $this->Customers->find('all',["conditions"=>array("id"=>intval($cus_id))])->first();
        if (!empty($CustomerInfo)) {
            $installer_id   = $CustomerInfo->installer_id;
            $installerdata	= $this->Customers->find('all')
                                    ->leftJoin(['installers' => 'installers'],['installers.id = Customers.installer_id'])
                                    ->where(['Customers.installer_id' => $installer_id ])
                                    ->where(['Customers.id NOT IN '=> $cus_id ])
                                    ->order(['Customers.id'=>'desc'])
                                    ->toArray();
            $instArr = array();
            if(!empty($installerdata)) {
                foreach($installerdata as $key =>$insArr)
                {
                    $instArr[$key]['cust_id'] 		= 	$insArr['id'];
                    $instArr[$key]['name']		 	= 	$insArr['name'];
                    $instArr[$key]['email']		 	= 	$insArr['email'];
                    $instArr[$key]['address'] 		= 	$insArr['address1'];
                    $instArr[$key]['city'] 			= 	$insArr['city'];
                    $instArr[$key]['state'] 		= 	$insArr['state'];
                    $instArr[$key]['user_role'] 	= 	$insArr['user_role'];
                }
                $rightsArr= array();
                $rights = $this->Parameters->getCustomreUserRights();
                if(!empty($rights))
                {
                    $i=0;
                    foreach($rights as $key=>$rightArr)
                    {
                        $rightsArr[$i]['id'] 		= $key;
                        $rightsArr[$i]['value']		= $rightArr;
                        $i++;
                    }
                }
                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('result', $instArr);
                $this->ApiToken->SetAPIResponse('rights', $rightsArr);
            } else {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
            }
        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Invalid Customer.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * setInstallerUserRole
     *
     * Behaviour : Public
     *
     * @defination : Method is used for set user role.
     *
     * Author : Sachin Patel
     */

    public function setInstallerUserRole(){

        $this->autoRender 	= false;
        $this->SetVariables($this->request->data);
        $cus_id	= $this->ApiToken->customer_id;

        if(!empty($cus_id) && !empty($this->request->data['user_role'])) {
            $user_role_array =json_decode($this->request->data['user_role'],true);
                foreach ($user_role_array as $key => $value){
                    $user_arr = $this->Customers->get($value['id']);
                    if($user_arr->default_admin !="1"){
                       $user_arr->user_role = $value['user_role'];
                       $this->Customers->save($user_arr);
                    }
                }
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('msg', 'Users roles Successfully Applied');

        }else {
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
        $cus_id	= $this->ApiToken->customer_id;

        if(!empty($cus_id)) {
            $instUser 		  = $this->InstallerUsers->findByCustomerId($cus_id)->first();
            if(!empty($instUser['id'])) {
                $instUserEntity 		  = $this->InstallerUsers->get($instUser['id']);
                $instUserEntity->modified = $this->NOW();
                $instUserEntity 		  = $this->InstallerUsers->patchEntity($instUserEntity,$this->request->data());
                $this->InstallerUsers->save($instUserEntity);

                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('msg', 'Success');
            } else {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
            }
        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * uploadterms
     *
     * Behaviour : public
     *
     * @defination : Method used to upload installer terms.
     *
     */
    public function uploadterms()
    {
        $this->autoRender 	= false;
        $this->SetVariables($this->request->data);

        $cus_id	        = $this->ApiToken->customer_id;
        $customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id         = (isset($customerData['installer_id'])?$customerData['installer_id']:0);

        if(!empty($cus_id)) {

            /*Update Term Default Status */
            if(!empty($this->request->data("term_id")) && $this->request->data("action") == "update_default_status") {

                $this->InstallerTerms->updateAll(['is_default' => 0], ['installer_id' =>$cus_id]);
                $this->InstallerTerms->updateAll(['is_default' => 1], ['id' => $this->request->data("term_id"),'installer_id' =>$cus_id]);

                $result = array("term_id"=>$this->request->data("term_id"),"is_default"=>1);
                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('msg', 'Terms Uploaded Successfully.');
                $this->ApiToken->SetAPIResponse('result', $result);
            }

            /*Overright Existing Terms */
            if(!empty($this->request->data("term_id")) && $this->request->data("action") == "upload_terms") {

                $insTermData 		= $this->InstallerTerms->get($this->request->data("term_id"));
                $originalTerms 		= INSTALLER_TERMS_PATH.$cus_id.'/'.$insTermData['termspath'];
                $imagePatchEntity 	= $this->InstallerTerms->patchEntity($insTermData, $this->request->data);

                if(isset($this->request->data['termspath']['name']) && !empty($this->request->data['termspath']['name']) && empty($this->request->data['termspath']['error'])) {
                    $image_path = INSTALLER_TERMS_PATH.$cus_id.'/';
                    if(!file_exists(INSTALLER_TERMS_PATH.$cus_id))
                        mkdir(INSTALLER_TERMS_PATH.$cus_id, 0755,true);
                    $file_name = $this->file_upload($image_path,$this->request->data['termspath'],false,65,65,$image_path);


                    if(!empty($this->request->data("is_default"))) {
                        $this->InstallerTerms->updateAll(['is_default' => 0], ['installer_id' =>$cus_id]);
                    }

                    $imagePatchEntity->termspath        = $file_name;
                    $imagePatchEntity->installer_id     = $cus_id;
                    $imagePatchEntity->is_default 	    = (!empty($this->request->data("is_default"))?$this->request->data("is_default"):0);
                    $imagePatchEntity->status 		    = 1;
                    $imagePatchEntity->modified 	    = $this->NOW();
                    $imagePatchEntity->modified_by 	    = $this->ApiToken->customer_id;
                    $imagePatchEntity->position_order   = $this->request->data("position_order");
                    if($termsRes = $this->InstallerTerms->save($imagePatchEntity)) {
                        if(file_exists($originalTerms)) {
                            unlink($originalTerms);
                        }
                    }
                    $result = array("term_id"=>(isset($termsRes->id)?$termsRes->id:0),"is_default"=>(isset($termsRes->is_default)?$termsRes->is_default:0),"position_order"=>$termsRes->position_order);
                    $this->ApiToken->SetAPIResponse('type', 'ok');
                    $this->ApiToken->SetAPIResponse('msg', 'Terms Uploaded Successfully.');
                    $this->ApiToken->SetAPIResponse('result', $result);
                } else {
                    $this->ApiToken->SetAPIResponse('type', 'error');
                    $this->ApiToken->SetAPIResponse('msg', 'Terms Uploading Failed.');
                }
            }

            /*Upload New Terms*/
            if(empty($this->request->data("term_id")) && $this->request->data("action") == "upload_terms") {

                $termscount = $this->InstallerTerms->find('all',array('conditions'=>array('InstallerTerms.installer_id'=>$cus_id)))->count();

                if($termscount <= 4) {

                    $imagePatchEntity                   = $this->InstallerTerms->newEntity($this->request->data);
                    if(isset($this->request->data['termspath']['name']) && !empty($this->request->data['termspath']['name']) && empty($this->request->data['termspath']['error'])) {

                        $image_path                     = INSTALLER_TERMS_PATH.$cus_id.'/';
                        if(!file_exists(INSTALLER_TERMS_PATH.$cus_id))
                            mkdir(INSTALLER_TERMS_PATH.$cus_id, 0755,true);
                        $file_name                      = $this->file_upload($image_path,$this->request->data['termspath'],false,65,65,$image_path);
                        $imagePatchEntity->termspath    = $file_name;
                        $imagePatchEntity->installer_id = $cus_id;

                        if(!empty($this->request->data("is_default"))) {
                            $this->InstallerTerms->updateAll(['is_default' => 0], ['installer_id' =>$cus_id]);
                        }

                        $imagePatchEntity->is_default 	= (!empty($this->request->data("is_default"))?$this->request->data("is_default"):0);
                        $imagePatchEntity->status 		= 1;
                        $imagePatchEntity->created 		= $this->NOW();
                        $imagePatchEntity->created_by 	= $this->ApiToken->customer_id;
                        $termsRes = $this->InstallerTerms->save($imagePatchEntity);

                        $result = array("term_id"=>(isset($termsRes->id)?$termsRes->id:0),"is_default"=>(isset($termsRes->is_default)?$termsRes->is_default:0));
                        $this->ApiToken->SetAPIResponse('type', 'ok');
                        $this->ApiToken->SetAPIResponse('msg', 'Terms Uploaded Successfully.');
                        $this->ApiToken->SetAPIResponse('result', $result);
                    } else {
                        $this->ApiToken->SetAPIResponse('type', 'error');
                        $this->ApiToken->SetAPIResponse('msg', 'Terms Uploading Failed.');
                    }

                } else {
                    $this->ApiToken->SetAPIResponse('type', 'error');
                    $this->ApiToken->SetAPIResponse('msg', 'You reached Maximum Uploaded Terms Count.');
                }
            }

            if(empty($this->request->data("action"))) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
            }

        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

    /**
     *
     * listterms
     *
     * Behaviour : public
     *
     * @defination : Method used to list installer terms.
     *
     */
    public function listterms()
    {
        $this->autoRender 	= false;
        $this->SetVariables($this->request->data);

        $cus_id	= $this->ApiToken->customer_id;
        $customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);

        if(!empty($cus_id)) {

            $termsArray = $this->InstallerTerms->find('all',array('conditions'=>array('InstallerTerms.installer_id'=>$cus_id)))->toArray();
            $result	= array();

            if(count($termsArray) > 0) {
                foreach($termsArray as $key=>$value){
                    $result[$key] = array('id'=>$value['id'],'termspath'=>INSTALLER_TERMS_URL.$cus_id.'/'.$value['termspath'],'is_default'=>(empty($value['is_default'])?0:1),"position_order"=>$value['position_order']);
                }
            }

            $this->ApiToken->SetAPIResponse('result', $result);
            $this->ApiToken->SetAPIResponse('type', 'ok');
        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

}
