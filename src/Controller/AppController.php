<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Network\Session;
use Cake\View\Helper;
use Cake\Core\App;
use Cake\View\Helper\SessionHelper;
use Cake\View\Helper\Userright;
use Cake\Utility\Hash;
use Cake\Datasource\ConnectionManager;
use Cake\Event\Event;
use Dompdf\Dompdf;
use Options\Options;
use Couchdb\Couchdb;
/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
session_start();
class AppController extends Controller
{

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */
	public $components 	= ['RequestHandler','Cookie','JqdTable'];
	//public $helpers 	= ['Session'];
    public function initialize()
    {
        parent::initialize();
		$this->loadModel('Admintransaction');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('SiteSurveys');
		$this->loadModel('SiteSurveysImages');
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('BranchMasters');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('InstallerSubscription');
		$this->loadModel('InspectionReport');
		$this->loadModel('ApplyonlinePayment');
		$this->loadComponent('Flash');
		$this->loadComponent('Userright');
		$this->loadComponent('Session');
		$this->loadComponent('Image');
		$this->loadComponent('PhpExcel');
		$this->loadModel('DiscomMaster');
		$this->loadModel('Members');
		$this->loadModel('ApplyOnlinesOthers');
		$this->loadModel('Couchdb');
		$this->loadModel('DeveloperApplicationCategoryMapping');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('ApplicationCategory');
		$this->Session 	= $this->request->Session();
		
		$this->set('Session',$this->Session);

		if (isset($this->request['params']['admin']) && $this->request['params']['admin'] == 1) {
			$this->layout = 'default';
		}
		if(API_MAINTENANCE_MODE=='1' && $this->request['controller']!='Maintenance' && !in_array($_SERVER['REMOTE_ADDR'],array("203.88.138.46","86.98.53.143")))
        {
        	return $this->redirect('Maintenance');
        }
        $login_type  			= $this->Session->read('Customers.login_type');
        $arrCategoryMapped 		= array();
		$arrActiveCategory 		= array();
		$arrActiveCategoryIds 	= array();
		if($login_type == 'developer') {
			$developerDetails 		= $this->DeveloperCustomers->find('all',array('conditions'=>array('id'=>$this->Session->read('Customers.id'))))->first();

			$arrDeveloperCategory 	= $this->DeveloperApplicationCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$developerDetails->installer_id)))->toArray();
			if(!empty($arrDeveloperCategory)) {
				foreach($arrDeveloperCategory as $category) {
					$arrCategoryMapped[] 	= $category->application_category_id;
				}
			}
		}
		$ApplicationCategoryDetails	 = $this->ApplicationCategory->find('all',array('conditions'=>array('status'=>1)))->toArray();
		if(!empty($ApplicationCategoryDetails)) 
		{
			foreach($ApplicationCategoryDetails as $k=>$acm) {
				$arrActiveCategory[$acm->id]['category_name'] 			= $acm->category_name;
				$arrActiveCategory[$acm->id]['developer_charges'] 		= $acm->developer_charges;
				$arrActiveCategory[$acm->id]['developer_tax_percentage']= $acm->developer_tax_percentage;
				$arrActiveCategory[$acm->id]['route_name'] 				= $acm->route_name;
				$arrActiveCategoryIds[] 								= $acm->id;
			}
		}

		$this->set('developerCategory',$arrCategoryMapped);
		$this->set('activeCategory',$arrActiveCategory);
		$this->set('activeCategoryIds',$arrActiveCategoryIds);
        if($this->request->params['action']!='logout' && $this->request->params['action']!='claim subsidy' && $this->request->params['action']!='subsidyclaims' && $this->request->params['action']!='savesubsidyclaims' && $this->request->params['action']!='approvesubsidyclaims' && strtolower($this->request->params['controller'])!='payutransfer' && strtolower($this->request->params['controller'])!='inspection' && (!isset($this->request->params['prefix'])) && (!isset($this->request->params['admin'])) && strtolower($this->request->params['controller'])!='applyonlines' && $this->request->params['action']!='fetchInstaller' && $this->request->params['action']!='getDistrict'  && $this->request->params['controller']!='PayutransferKusum' && strtolower($this->request->params['controller'])!='reapplicationpayment' && strtolower($this->request->params['controller'])!='geoapplicationpayment' && strtolower($this->request->params['controller'])!='geoshiftingapplicationpayment' &&strtolower($this->request->params['controller'])!='developerpayment' &&strtolower($this->request->params['controller'])!='test'&& strtolower($this->request->params['controller'])!='developer' && strtolower($this->request->params['controller'])!='reports' &&
		strtolower($this->request->params['controller'])!='applicationdeveloperpermission'  && strtolower($this->request->params['controller'])!='transferdeveloperpermission')
        {
        	$this->loadComponent('Csrf'); 
        }
        if(strtolower($this->request->params['controller'])=='applyonlines' && ($this->request->params['action']=='index' || $this->request->params['action']=='applyonline_list' || $this->request->params['action']=='AdditionalCapacity'))
        {
        	$this->loadComponent('Csrf');
        }
        $visitor 	= $this->ApplyOnlines->visitorTracker($this->Session->read('Customers.id'));
        $this->set('visitor',$visitor);
        if(!isset($_SERVER['HTTPS'])) {
        	//return $this->redirect('https://geda.ahasolar.in');
        }
		if(in_array($_SERVER['REQUEST_METHOD'],array('OPTIONS','TRACE','HEAD'))) {
			echo '<html>
				<head><title>405 Not Allowed</title></head>
				<body>
				<center><h1>405 Not Allowed</h1></center>
				</body>
				</html>';
			exit;
		}
    }
	/**
     *
     * The status of $arrError is universe
     *
     * Potential value is array of models inherited in class
     *
     * @public array
     *
     */
	public $arrError= array();
	
	
	 /**
	 *
	 * The status of $intCurAdminUserRight is universe
	 *
	 * Potential value is Admin user rights value used for current admin
	 *
	 * @public string
	 *
	 */
	public $intCurAdminUserRight = "";
	/**
	 *
	 * The status of $adminright is universe
	 *
	 * Potential value is rights used for admin
	 *
	 * @public string
	 *
	 */
	public $adminright = "";

	/**
	 *
	 * variable used for ExPaginator Helper
	 * @public string
	 *
	 */
	public $ExPaginator = "";

	/**
	 *
	 * variable used for get city_id
	 * @public string
	 *
	 */
	public $city_id = "";

	/**
	 *
	 * variable used for get area_id
	 * @public string
	 *
	 */
	public $area_id = "";

	/**
	 *
	 * variable used for City Name
	 * @public string
	 *
	 */
	public $city_name = "";

	/**
	 *
	 * variable used for get Area Name
	 * @public string
	 *
	 */
	public $area_name = "";

	/**
	 *
	 * variable used for get Active Status
	 * @public string
	 *
	 */
	public $ACTIVE_STATUS =1;

	/**
	 *
	 * variable used for get Inactive Status
	 * @public string
	 *
	 */
	public $INACTIVE_STATUS =0;
	
	/**
     *
     * The status of $period is universe
     *
     * Potential value is period it time perioud that can be today yesterday or user define
     *
     * @public array
     *
     */
    public $period = array(''=>'Select Period',1=>'Today',2=>'Yesterday',3=>'Custom');

    /**
     *
     * The installer roles of $installerRoles is universe
     *
     * Potential value is $installerRoles.
     *
     * @public array
     *
     */
    public $installerRoles = array(1=>'Technical Survey',2=>'Commercial',3=>'BD',4=>'Execution');
    
	public function beforeFilter(Event $event)
	{
	    parent::beforeFilter($event);

	    $this->IP_ADDRESS = $this->_getipaddress();
		$this->set("IP_ADDRESS",$this->IP_ADDRESS);
		$AjaxRequest = 0;
		//if($this->RequestHandler->isAjax()) {
		if($this->request->is('ajax')){
			$AjaxRequest = 1;
		}
		$this->set("AjaxRequest",$AjaxRequest);
		
		$hash = false;
		$hash = $this->request->header('X-hash');
		if($hash) {
			$this->validateRequest();
			//$this->Auth->allow();
			//$this->Security->unlockedActions = array($this->params['action']);
		}
		
	}
	
	private function AllowedActions()
	{
		$allowedActions = array();
		switch($this->params['controller'])
		{
			case "versions":
			{
				$allowedActions = array('index');
			}
		}
		return $allowedActions;
	}
	
	/**
	 *
	 * initAdminRightHelper
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is to create object of AdminrightHelper class
	 *
	 */
	
	public function initAdminRightHelper()
	{
		
		/*App::import('Helper', 'Userright');
		
		$this->View			= new View($this->Controller);
		$this->Userright	= new UserrightHelper($this->View);*/
	}
	/**
     *
     * setAdminArea
     *
     * Behaviour : Public
     *
     * @param : $isRistricted   : Value is true of false, base on this restriction is set in admin adrea
     * @defination : Method is use to set admin area use for admin base on restriction set for particular
     *
     */
    public function setAdminArea($isRistricted=true)
	{
		$ClsUserrole  = TableRegistry::get('Userroles');
		$this->set('usertypes', $ClsUserrole->setAdminuserRoles($this->request->session()));
        $this->set('user_type',$this->request->session()->read('User.usertype'));
        $this->set('myrights',$this->request->session()->read('User.userrights'));
        $this->set('username',$this->request->session()->read('User.username'));
        $this->set('fullname',$this->request->session()->read('User.name'));
		/** SET GLOBAL VARIABLES TO BE USED EVERY VIEW */

		if (!$this->request->session()->check('User.username') && $isRistricted) {
			if($this->request->is('ajax'))
			{
				$this->header('HTTP/1.1 401: SESSION TIMEOUT');
				echo json_encode(array("Data"=>"SESSION TIMEOUT"));
				$this->response->send();
				exit;
			}
			$currentURL = $this->selfURL();
			return $this->redirect('/admin/users/login');
			//return $this->redirect(['controller' => 'users', 'action' => 'login']);
			exit();
        }elseif($this->request->session()->check('User.username') && !$isRistricted) {
            return $this->redirect('/admin/dashboard/');
            exit();
        }
		if($isRistricted)
        {
			/* if(isset($_SERVER["HTTP_REFERER"])){
				$refhost = parse_url(str_replace("www.","",$_SERVER["HTTP_REFERER"]));
				if(isset($refhost['port']) && isset($refhost['host']))
                    $refhost['host']=$refhost['host'].":".$refhost['port'];
                if(isset($refhost['host']) && $refhost['host']!=REFERER_HOST ) {
                    return $this->redirect('/admin/users/logout');
					exit();
				}
			} */
            $this->setAdminRights();
			$this->_getAdminRightsDetail();
        }
		$this->ReGeneratedUpdatedAdminUserMenu();
		$PageTitle = $this->getPageTitle();
		if (!empty($PageTitle)) $this->set("title_for_layout",$PageTitle);
		$this->set("IP_ADDRESS",$this->_getipaddress());
    }
    
	public function ReGeneratedUpdatedAdminUserMenu()
	{
		$blnAdminUserUpdated = $this->Cookie->read('AU');
		if($blnAdminUserUpdated == 'Y' && $this->Session->check('User.id'))
		{
			$this->Users->GenerateAdminuserRightSession($this->Session->read('User.id'), $this->Session);

            if($this->request->is('ajax')){
                $this->RegenerateAdminMenu();
                $this->Cookie->delete('AU');
                if (!in_array($this->intCurAdminUserRight, (array)$this->allowed_actions)) {
                    $this->redirect('/users/index');
                }
            }
		}
	}
	
	private function getPageTitle()
	{
		$PageTitle = '';
		if (empty($this->intCurAdminUserRight)) return $PageTitle;
		$PageTitleRow = $this->Admintransaction->findById($this->intCurAdminUserRight);
		if (!empty($PageTitleRow)) {
			$PageTitle = $PageTitleRow->toArray()[0]->menutitle;
		}
		
		return $PageTitle;
	}

	 /* _getipaddress
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

	/* getcityname
	 *
	 * Behaviour : Public
	 *
	 * @return : IP address
	 * @defination :  Method is use to get cityname
	 *
	 */
	function getcityname()
	{
		if(!empty($this->city_id) && $this->city_id!=0)
		{
			$cityArray=$this->Location->find('first',array('fields'=>array('city'),'conditions'=>array('id'=>$this->city_id)));
			$this->city_name=(isset($cityArray['Location']['city'])?$cityArray['Location']['city']:'');
		}
	}
	
	/* getareaname
	 *
	 * Behaviour : Public
	 *
	 * @return : IP address
	 * @defination :  Method is use to get areaname
	 *
	 */
	function getareaname()
	{
		if(!empty($this->area_id) && $this->area_id!=0)
		{
			$areaArray=$this->CityArea->find('first',array('fields'=>array('area'),'conditions'=>array('id'=>$this->area_id)));
			$this->area_name=$areaArray['CityArea']['area'];
		}
	}
	/**
     *
     * selfURL
     *
     * Behaviour : Public
     *
     * @return : Url of current page
     * @defination :  Method is use to create and return url of current page
     *
     */
    public function selfURL() {
	    $s = empty($_SERVER["HTTPS"]) ? '' : (($_SERVER["HTTPS"] == "on") ? "s" : "");
        $protocol = $this->strleft(strtolower($_SERVER["SERVER_PROTOCOL"]), "/").$s;
        $port = ($_SERVER["SERVER_PORT"] == "80") ? "" : (":".$_SERVER["SERVER_PORT"]);
        return $protocol."://".$_SERVER['SERVER_NAME'].$port.$_SERVER['REQUEST_URI'];
    }
	/**
     *
     * strleft
     *
     * Behaviour : Public
     *
     * @param : $s1   :  String 1 as 1st parameter
     * @param :  $s2  :  String 2 as 2nd parameter
     * @return : Padding base on argument
     * @defination : Method is use to provide padding base on argument
     *
     */
    public function strleft($s1, $s2) {
        return substr($s1, 0, strpos($s1, $s2));
    }
	/**
	 *
	 * writeadminlog
	 *
	 * Behaviour : Public
	 *
	 * @param : $adminuserid   : To identify specific amdin user on whoes action log is recorded
	 * @param : $actionid   : Which action to be logged
	 * @param : $actionvalue : Value to be logged related to action
	 * @param : $remark : If any remark about log
	 * @defination :  Method is use to log any action performed by admin
	 *
	 */
	public function writeadminlog($adminuserid, $actionid, $actionvalue="", $remark="")
	{
		
		$this->loadModel('Adminlogs');
		$adminlog_entity = $this->Adminlogs->newEntity();
		
		$adminlog_entity->adminuserid  = $adminuserid;
		$adminlog_entity->actionid     = $actionid;
		$adminlog_entity->actionvalue  = $actionvalue;
		$adminlog_entity->ipaddress    = $this->_getipaddress();
		$adminlog_entity->remark       = $remark;
		$adminlog_entity->created      = $this->NOW();
		
		$this->Adminlogs->save($adminlog_entity);
	}

	/**
	 *
	 * file_upload
	 *
	 * Behaviour : Public
	 *
	 * @param : $adminuserid   : To identify specific amdin user on whoes action log is recorded
	 * @param : $actionid   : Which action to be logged
	 * @param : $actionvalue : Value to be logged related to action
	 * @param : $remark : If any remark about log
	 * @defination :  Method is use to log any action performed by admin
	 *
	 */
	public function file_upload($path, $file,$is_resize = false,$width="", $hight="",$resize_path='',$prefix_file = '',$access_type='',$docsEntity=array())
	{
		$customerId 	= $this->Session->read('Customers.id');
		$ext 			= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name 		= $prefix_file.date('Ymdhis').rand();
		$file_location 	= WWW_ROOT.$path.$file_name.'.'.$ext;

		move_uploaded_file($file['tmp_name'],$file_location);
		if($is_resize && !empty($width) && !empty($hight) && !empty($file_location)){
			@$resize_path = WWW_ROOT.$resize_path.'r_'.$file_name.'.'.$ext;
			@$this->Image->prepare($file_location);
			@$this->Image->resize($width,$hight);//width,height,Red,Green,Blue
			@$this->Image->save($resize_path);
		}
		$passFileName 	= $file_name.'.'.$ext;
		$couchdbId 		= $this->Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
		return $file_name.'.'.$ext;
	}

	public function file_upload_rfid($path, $file,$is_resize = false,$width="", $hight="",$resize_path='',$prefix_file = '',$access_type='',$docsEntity=array())
	{
		$customerId 	= $this->Session->read('Customers.id');
		$ext 			= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name 		= $prefix_file.date('Ymdhis').rand();
		$file_location 	= WWW_ROOT.$path.$file_name.'.'.$ext;
		
		move_uploaded_file($file['tmp_name'],$file_location);
		if($is_resize && !empty($width) && !empty($hight) && !empty($file_location)){
			@$resize_path = WWW_ROOT.$resize_path.'r_'.$file_name.'.'.$ext;
			@$this->Image->prepare($file_location);
			@$this->Image->resize($width,$hight);//width,height,Red,Green,Blue
			@$this->Image->save($resize_path);
		}
		$passFileName 	= $file_name.'.'.$ext;
		//$couchdbId 		= $this->Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
		return $file_name.'.'.$ext;
	}
	/**
	 * now
	 * Behaviour : Public
	 * @return : date
	 * @defination : Method is get the current date and time
	 */
	public function NOW()
	{
		return date("Y-m-d H:i:s");
	}
	
	/**
     *
     * setAdminRights
     *
     * Behaviour : Public
     *
     * @defination : Method is use to set admin rights base  on rights set for admin
     *
     */
    public function setAdminRights(){
        $userrights				= $this->request->session()->read('User.userrights');
        $this->allowed_actions	= $userrights;
		if(!in_array($this->intCurAdminUserRight, (array)$this->allowed_actions)) {
            $this->Flash->set('You are not authorized to view that page.');
            $this->redirect(ADMIN_PATH.'users/index');
        }
    }
	
	/**
	 *
	 * _getAdminRightsDetail
	 *
	 * Behaviour	: private
	 * @return		:
	 * @defination : Method is use to get admin rights detail base on current admin user logged in
	 *
	 */
	private function _getAdminRightsDetail()
	{
		$UserRights = $this->request->session()->read('header_menu');
		if(empty($UserRights)) {
			$this->_GenerateAdminMenu();
		}
	}
	/**
	 *
	 * _GenerateAdminMenu
	 *
	 * Behaviour : private
	 * @params :
	 * @return :
	 * @defination : Method is use to generate Admin Menu Based on Admin User Rights
	 *
	 */
	private function _GenerateAdminMenu()
	{
		$arrAdminUserRights	= array_unique( $this->request->session()->read('User.userrights'));
		//pr($arrAdminUserRights);
		if(count($arrAdminUserRights) > 0)
		{ 	
			$connection = ConnectionManager::get('default');
			//echo trim(implode(",",$arrAdminUserRights));
			$query = "CALL GetAdminRightsByTrnId('".trim(implode(",",$arrAdminUserRights),',')."')";
			$arrResult	= $connection->execute($query)->fetchAll('assoc');
			
			if(is_array($arrResult) && count($arrResult)>0)
			{
				$menu = array();
				foreach ($arrResult as $MenuGroup=>$row) {
					if (isset($menu[$row['trngrouptitle']]['submenu'])) {
						$menu[$row['trngrouptitle']]['submenu'][] 	= ["url"=>$row['pageurl'],"menutitle"=>$row['menutitle']];
					} else {
						$menu[$row['trngrouptitle']]['title']		= $row['trngrouptitle'];
						$menu[$row['trngrouptitle']]['icon']		= $row['icon'];
						$menu[$row['trngrouptitle']]['submenu'][] 	= ["url"=>$row['pageurl'],"menutitle"=>$row['menutitle']];
					}
				}
				if(is_array($menu) && count($menu)>0)
				{
					$scriptdata = '<ul class="page-sidebar-menu page-sidebar-menu-hover-submenu  page-sidebar-menu-closed" data-auto-scroll="true" data-slide-speed="200">';
					$closeflag	= true;
					$i=1;
					foreach ($menu as $MenuGroup=>$MainMenu)
					{
						$class="";	
						$imgcl="dark";	
						if($this->name==$MenuGroup || ($this->name=='Dashboard' && $i==1)) {
							$imgcl="blue";
						}
						$c = 1;
						if (count($MainMenu['submenu']) > 0) {
							foreach($MainMenu['submenu'] as $SubMenu) {
								if($c == 1){
									$scriptdata = $scriptdata."<li><a class='master_menu_id' href=\"".WEB_ADMIN_URL.trim($SubMenu['url'])."\">
									<i class='".$MainMenu['icon']."'></i>
									<span class='title'>".trim($MenuGroup)."</span></a>";
									$scriptdata = $scriptdata."<ul class='sub-menu'>";
								}
								$scriptdata = $scriptdata."<li><a href=\"".WEB_ADMIN_URL.trim($SubMenu['url'])."\"><span class='title'>".trim($SubMenu['menutitle'])."</span></a></li>";
								$c++;
							}
						}
						$scriptdata = $scriptdata."</ul></li>";
						$i++;
					}
					$scriptdata = $scriptdata."</ul>";
					$this->request->session()->write('header_menu',$scriptdata);
				}
			}
		}
	}
	/**
	 *
	 * RegenerateAdminMenu
	 *
	 * Behaviour	: public
	 * @return		:
	 * @defination : Method is use to get admin rights detail base on current adminuser logged in
	 *
	 */
	public function RegenerateAdminMenu()
	{
		$this->_GenerateAdminMenu();
	}
	
	/**
	 *
	 * Set Sorting Variables 
	 *
	 * Behaviour	: public
	 * @return		:
	 * @defination : Method is use to Set Sorting Variables base on listing in view.
	 *
	 */
	public function SetSortingVars($table="",$option=array(),$sortArr = array())
	{
		if(isset($this->request->data['length']) && $this->request->data['length']!='' && $this->request->data['length']>0)
		{
			$this->intLimit = (!preg_match('/^[0-9]+$/', $this->request->data['length'])?$this->intLimit:$this->request->data['length']);
		}
		if(isset($this->request->data['start']) && $this->request->data['start']!="")
		{
			if($this->request->data['start']==0)
				$this->CurrentPage = 1;
			else	
				$this->CurrentPage = (int)(($this->request->data['start']/$this->intLimit)+1);
		}
		if(!empty($sortArr) && isset($this->request->data["order"][0]['column']) && isset($option['colName'][$this->request->data["order"][0]['column']]) && isset($sortArr[$option['colName'][$this->request->data["order"][0]['column']]]))
		{
			 //$this->SortBy	= "Customers.".$option['colName'][$this->request->data["order"][0]['column']];
			 $this->SortBy	= $sortArr[$option['colName'][$this->request->data["order"][0]['column']]];
		}	
		else if($table != "Parameters" && isset($this->request->data["order"][0]['column']) && $this->request->data["order"][0]['column']!="")
		{
			$this->SortBy	= trim($table.'.'.$option['colName'][$this->request->data["order"][0]['column']]);
		} else if ($table == "Parameters" && isset($this->request->data["order"][0]['column']) && trim($this->request->data["order"][0]['column']) == "id") {
			$this->SortBy=$table.'.para_id';
		} else if ($table == "Parameters") {
			$this->SortBy=$table.'.para_id';
		} else {
			$this->SortBy=$table.'.id';
		}
		if(isset($this->request->data["order"][0]['dir']) && $this->request->data["order"][0]['dir']!="")
		{
			$direc=strtolower($this->request->data["order"][0]['dir']);
			if($direc!='asc' && $direc!='desc')
			{
				$this->Direction = 'desc';
			}
			else
				$this->Direction = $direc;
		}
		
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
		
		$this->header_x_public		= $this->request->header('X-public');
		$this->header_x_hash		= $this->request->header('X-hash');
		$this->device_id			= $this->request->header('device_id');
        $hash                       = $this->request->data('X-hash');
        if(empty($this->header_x_public))
			$this->header_x_public		= $this->request->header('token');

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

		// Save API Log
		$headers = array();
		if(!function_exists('getallheaders')) {
	    	$headers = array();
	        foreach($_SERVER as $key => $value) {
	            if(substr($key, 0, 5) == 'HTTP_') {
	                $headers[str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))))] = $value;
	            }
	        }
    	}
    	else
    	{
    		foreach (getallheaders() as $name => $value) { 
				$headers[$name] = $value;
			}
    	}
		
		
		$api_url 		= 'http://'.$_SERVER['HTTP_HOST'].$_SERVER[ 'REQUEST_URI'];
		$requestStr 	= (isset($_REQUEST) ? print_r($_REQUEST, true) : "");
		$headerStr 		= (isset($headers) ? print_r($headers, true) : "");
		$filedataStr 	= (isset($_FILES) ? print_r($_FILES, true) : "");
		
		$this->loadModel('ApiLogs');
		$apiLog_entity = $this->ApiLogs->newEntity();
		
		$apiLog_entity->request_data  			= $requestStr;
		$apiLog_entity->request_header_data     = $headerStr;
		$apiLog_entity->request_file_data  		= $filedataStr;
		$apiLog_entity->created    				= $this->NOW();
		$apiLog_entity->api_url       			= $api_url;
		$this->ApiLogs->save($apiLog_entity);
		// Save API Log

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
	/**
	* DecodeAuth
	* Behaviour		: Public
	* @defination	: Method is use to chack request is from mobile of not
	* @created by   : Pravin Sanghani
	* @date 		: 05-07-2016	
	*/
	public function ismobile()
	{
		if($this->request->header('token') || $this->request->header('x-hash'))
			return true;
		else	
			return false;	
	}
	public function genratePDFSiteSurveyreport($id,$project,$isdownload=false,$isInstallerhide=0)
	{
		ini_set('max_execution_time', 300);
		$this->layout 		= false;
		/* Get all project installers */
		$projectInstallers 	= array();
		if(empty($isInstallerhide))
		{
			$projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
		}	
		
		/* Generate map URL based on project location */
		$latLng = $project->latitude.",".$project->longitude;
		$mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=272x378&markers=color:blue%7C'.$latLng.'&sensor=false';
		$mapImage = file_get_contents($mapUrl);
		$mapImage = 'data:image/png;base64,' . base64_encode($mapImage);
		/* Radiation Graph Generate */
		$radiationGraphArr 	= $this->Projects->getSolarRediationGHIChartData($project->latitude,$project->longitude);
		$radiationGraphData['radiation_ghi_data'] = (!empty($radiationGraphArr['radiation_ghi_data'])?$radiationGraphArr['radiation_ghi_data']:array());
		if(!empty($radiationGraphData)){
			$radiationGraphImg = $this->Projects->radiationGraph($radiationGraphData);	
		}
				
		/* Energy and Month Saving Data */
		$solarRediationData = $this->GhiData->getGhiData($project->longitude,$project->latitude);
		$energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project->recommended_capacity,$project->avg_monthly_bill,$project->estimated_kwh_year);
		
		/* Solar PV Chart Data */
		$monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		$monthly_saving 	= array_sum($monthSavinData);
		$estimated_cost_subsidy = isset($project->estimated_cost_subsidy)?round(($project->estimated_cost_subsidy/100000),2):$project->estimated_cost;
		$payBackGraphData 	= $this->Projects->GetPaybackChartData($estimated_cost_subsidy, $monthly_saving);
		if(!empty($payBackGraphData)){
			$paybackGraphImg 	= $this->Projects->paybackGraph($payBackGraphData);	
		}
		
		/* Get Environment Benefit Data. */
		$inpdataArr['recommendedCapacity'] = $project->estimated_kwh_year;
		$inpdataArr['estimatedKWHYear'] = $project->recommended_capacity;
		$environmentData = $this->Projects->calculateSolarPowerGreenSavingsData($inpdataArr);
		$hideInstaller = $isInstallerhide;
		/* Get PDF Report id. */
		$projectReportId = $this->Projects->GetProjectPDFReportId($id);
		$this->set(compact("project","projectInstallers","mapImage","radiationGraphImg","paybackGraphImg","radiationGraphData","energyAndSavingDataArr","projectReportId","environmentData","hideInstaller"));
		$this->set('pageTitle','Project');

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		//$dompdf->set_option('defaultFont', "Helvetica");
		 $html = $this->render('project_report');
		//exit;
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('report-'.$projectReportId);	
		}
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/sitesurveyreport-'.$projectReportId.'.pdf';
		file_put_contents($pdfPath, $output);
		
		return $pdfPath;
	}
	public function genrateReportMain()
	{
		ini_set('max_execution_time', 300);
		$this->layout 		= false;
		/* Get all project installers */
		$projectInstallers 	= array();
		// // if(empty($isInstallerhide))
		// // {
		// // 	$projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
		// // }	
		
		// /* Generate map URL based on project location */
		// $latLng = $project->latitude.",".$project->longitude;
		// $mapUrl = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=272x378&markers=color:blue%7C'.$latLng.'&sensor=false';
		// $mapImage = file_get_contents($mapUrl);
		// $mapImage = 'data:image/png;base64,' . base64_encode($mapImage);
		// /* Radiation Graph Generate */
		// $radiationGraphArr 	= $this->Projects->getSolarRediationGHIChartData($project->latitude,$project->longitude);
		// $radiationGraphData['radiation_ghi_data'] = (!empty($radiationGraphArr['radiation_ghi_data'])?$radiationGraphArr['radiation_ghi_data']:array());
		// if(!empty($radiationGraphData)){
		// 	$radiationGraphImg = $this->Projects->radiationGraph($radiationGraphData);	
		// }
				
		// /* Energy and Month Saving Data */
		// $solarRediationData = $this->GhiData->getGhiData($project->longitude,$project->latitude);
		// $energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project->recommended_capacity,$project->avg_monthly_bill,$project->estimated_kwh_year);
		
		// /* Solar PV Chart Data */
		// $monthSavinData 	= (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
		// $monthly_saving 	= array_sum($monthSavinData);
		// $payBackGraphData 	= $this->Projects->GetPaybackChartData($project->estimated_cost, $monthly_saving);
		// if(!empty($payBackGraphData)){
		// 	$paybackGraphImg 	= $this->Projects->paybackGraph($payBackGraphData);	
		// }
		
		// /* Get Environment Benefit Data. */
		// $inpdataArr['recommendedCapacity'] = $project->estimated_kwh_year;
		// $inpdataArr['estimatedKWHYear'] = $project->recommended_capacity;
		// $environmentData = $this->Projects->calculateSolarPowerGreenSavingsData($inpdataArr);
		// $hideInstaller = $isInstallerhide;
		// /* Get PDF Report id. */
		// $projectReportId = $this->Projects->GetProjectPDFReportId($id);
		// $this->set(compact("project","projectInstallers","mapImage","radiationGraphImg","paybackGraphImg","radiationGraphData","energyAndSavingDataArr","projectReportId","environmentData","hideInstaller"));
		// $this->set('pageTitle','Project');
		$projectReportId = 1;
		/* Generate PDF for estimation of project */
		
		ini_set('max_input_time','200M'); 
		ini_set('memory_limit','200M'); 
		set_time_limit(3600);
		
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		//$dompdf->set_option('defaultFont', "Helvetica");
		$html = $this->render('project_report_draft');
		$dompdf->loadHtml($html);
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		// Output the generated PDF to Browser
		// if($isdownload){
		 	$dompdf->stream('report-'.$projectReportId);	
		// }
		$output = $dompdf->output();

		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/reportDraft-'.$projectReportId.'.pdf';
		file_put_contents($pdfPath, $output);
		
		return $pdfPath;
	}

	/**
	 *
	 * GenerateProjectResultData
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to get project result data.
	 *
	 */
	public function GenerateProjectResultData($param = array())
	{
		/*Set requested parameter*/
		$monthDetail_para 		= isset($param['month_details'])?$param['month_details']:'';
		$gensetDetail_para 		= isset($param['genset_details'])?$param['genset_details']:'';
		$inverterDetail_para 	= isset($param['inverter_details'])?$param['inverter_details']:'';
		/* 	$usageHours_para 	= isset($param['usage_hours'])?$param['usage_hours']:0;
			$backupType_para 	= isset($param['backup_type'])?$param['backup_type']:''; */
		$overall_para 			= isset($param['overall'])?$param['overall']:'';
		$is_overall_para	 	= isset($param['is_overall'])?$param['is_overall']:'';
		$lat_para 				= isset($param['latitude'])?$param['latitude']:0;
		$lon_para 				= isset($param['longitude'])?$param['longitude']:0;
		$criticalLoad_para 		= isset($param['critical_load'])?$param['critical_load']:'';
		$state_para 			= isset($param['state'])?$param['state']:'';
		$customerType_para 		= isset($param['customer_type'])?$param['customer_type']:0;

		$bill 			= 0;
		$energy_con 	= 0;
		$totalCostGen 	= 0;	
		
		if(isset($monthDetail_para) && !empty($monthDetail_para)){
			$billDetail 	=  unserialize($monthDetail_para);	
			$billDetail 	= $billDetail['ElectricityBillDetails'];
			foreach ($billDetail as $key => $value) {
				$bill = $bill + $value['bill_amount'];
				$energy_con = $energy_con + $value['power_consume'];
			}
		}

		$costGen = array();
		if(isset($gensetDetail_para) && !empty($gensetDetail_para)){
			$billDetail =  unserialize($gensetDetail_para);	
			$billDetail = $billDetail['GensetDetails'];
			foreach ($billDetail as $key => $value) {
				$costGen[] = $this->Projects->calculateGeneratorUsage($value['kva'],$value['hours']);
			}
		}

		if(isset($inverterDetail_para) && !empty($inverterDetail_para)){
			$billDetail =  unserialize($inverterDetail_para);	
			$billDetail = $billDetail['InverterDetails'];
			foreach ($billDetail as $key => $value) {
				$costGen[] = $this->Projects->calculateInverterUsage($value['kva'],$value['hours']);
			}
		}		
		$avg_energy_con  = 0;

		$totalCostGen 	= array_sum($costGen);
		$monthlyBill 	= ($bill/12);
		if(!empty($energy_con)){
			$avg_energy_con 	= ($energy_con/12);
		}
		
		if(!empty($is_overall_para) && $is_overall_para == $this->Projects->AREA_TYPE_FOOT) {
			$solarPenalArea	= $this->Projects->calculatePvInFoot($overall_para);
		} else { 
			$solarPenalArea	= $this->Projects->calculatePvInMeter($overall_para);	
		}

		$solarPvInstall 	= ceil($solarPenalArea/12);
		$solarRediationData	= $this->Projects->getSolarRediation($lat_para,$lon_para);		
		$annualTotalRad		= ($solarRediationData['ann_glo']*365);		

		$contractLoad 			= round(((($criticalLoad_para*12)/((24*365*LOAD_FECTORE)/100))));
		$capacityAcualEnrgyCon	= round(((($criticalLoad_para*12)/$annualTotalRad)));

		$recommendedSolarPvInstall 	= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInDay 		= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);
		$monthChartDataArr			= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);

		$capitalCost 				= $this->Projects->calculatecapitalcost($recommendedSolarPvInstall,$state_para,$customerType_para);
		$capitalCostsubsidy 		= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$state_para,$customerType_para);

		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInYear	= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);

		/* Calculate saving */
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * ((!empty($energy_con)?($bill/$energy_con):0)-0.5)); 

		/* Calculate saving */
		$cost_solar				= 0.0;	
		$unitRate				= ((!empty($criticalLoad_para)?($monthlyBill/$criticalLoad_para):0)-0.5);
		$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,0,0,$totalCostGen);

		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->Projects->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$monthlyBill,$capitalCost);	
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->Projects->genrateApiChartData($fromPvSystem, $monthChartDataArr);	
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);

		$resultArr['recommended_capacity'] 			= (isset($recommendedSolarPvInstall)?$recommendedSolarPvInstall:0);
		$resultArr['estimated_cost'] 				= (isset($capitalCost)?$capitalCost:0);
		$resultArr['avg_generate'] 					= (isset($averageEnrgyGenInMonth)?$averageEnrgyGenInMonth:0);
		$resultArr['cost_solar'] 					= (isset($cost_solar)?$cost_solar:0);
		$resultArr['payback']						= (isset($payBack)?$payBack:0);
		$resultArr['contract_load']					= (isset($contractLoad)?$contractLoad:0);
		$resultArr['solar_ratio']					= (isset($solar_ratio)?$solar_ratio:0);
		$resultArr['estimated_cost_subsidy']		= (isset($capitalCostsubsidy)?$capitalCostsubsidy:0);
		$resultArr['avg_monthly_bill']				= (isset($monthlyBill)?$monthlyBill:0);
		$resultArr['avg_energy_consumption']		= (isset($avg_energy_con)?$avg_energy_con:0);
		$resultArr['maximum_capacity']				= (isset($highRecommendedSolarPvInstall)?$highRecommendedSolarPvInstall:0);
		return $resultArr;
	}
	/**
	 *
	 * survey_download_xls
	 *
	 * Behaviour : public
	 *
	 * @param : result_data , $isdownload=false, $isInstallerhide=0
	 *
	 * @defination : Method is use to download .pdf file from modal popup of survey listing
	 *
	 */
	public function survey_download_xls($result_data, $project_name, $all_area_types, $all_area_type_smp, $all_load, $all_meter, $all_meter_accuracy, $all_roof, $all_roof_st, $all_billing,$mail_send=0)
	{
		$counter_id=1;
		$arr_data=array();
		$counter_id=1;
		$arr_data=array();
		$PhpExcel=$this->PhpExcel;
		$PhpExcel->createExcel();

		$PhpExcel->additonalSheet(1,'Introduction');
		$gdImage = imagecreatefrompng('pdf/images/logo_pdf.png');
		
		// Add a drawing to the worksheetecho date('H:i:s') . " Add a drawing to the worksheet\n";
		$objDrawing = new \PHPExcel_Worksheet_MemoryDrawing();
		$objDrawing->setImageResource($gdImage);
		$objDrawing->setRenderingFunction();
		$objDrawing->setMimeType();
		$objDrawing->setHeight(90);
		//$objDrawing->setWidth(90);
		$objDrawing->setCoordinates('A2');
		$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
		$PhpExcel->writeCellValue('B8', 'Document Name');
		$PhpExcel->writeCellValue('C8', ':');
		$PhpExcel->writeCellValue('D8', $project_name);	
		$PhpExcel->writeCellValue('B9', 'Revised By');
		$PhpExcel->writeCellValue('C9', ':');
		$PhpExcel->writeCellValue('B10', 'Reviewed By');
		$PhpExcel->writeCellValue('C10', ':');
		$PhpExcel->writeCellValue('B11', 'Powered By');
		$PhpExcel->writeCellValue('C11', ':');
		$PhpExcel->writeCellValue('D11', 'AHASOLAR PRIVATE LIMITED');
		$PhpExcel->writeCellValue('B12', 'Prepared By');
		$PhpExcel->writeCellValue('C12', ':');

		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getProtection()->setSheet(true);
		//$PhpExcel->getExcelObj()->getSecurity()->setWorkbookPassword("1234"); 

		/*$PhpExcel->getExcelObj()->getSecurity()->setLockWindows(true);
    	$PhpExcel->getExcelObj()->getSecurity()->setLockStructure(true);

    	$PhpExcel->getExcelObj()->getSecurity()->setWorkbookPassword('secret');*/

		//$PhpExcel->getSecurity()->setLockRevision(true); 
    	//$PhpExcel->getSecurity()->setLockWindows(true); 
    	//$PhpExcel->getSecurity()->setLockStructure(true); 
    	//$PhpExcel->getSecurity()->setWorkbookPassword('1234'); 

		$PhpExcel->additonalSheet(2,'Area Details');
		$PhpExcel->writeMergeCellValue('A1:AE1');
		$PhpExcel->writeCellValue('A1','Project Name - '.$project_name);
		$PhpExcel->fillCellColour('A1:AM1','D7E4BD');
		$PhpExcel->fillCellFont('A1','000000',TRUE,'16px');
		$PhpExcel->getExcelObj()->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
		$j=2;
		$PhpExcel->writeCellValue('A'.$j, 'Site No.');
		$PhpExcel->writeCellValue('B'.$j, 'Building Name');
		//$PhpExcel->writeCellValue('C'.$j, "Orientation");
		$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
		$PhpExcel->writeCellValue('D'.$j, "Survey Date");
		$PhpExcel->writeCellValue('E'.$j, "Latitude");
		$PhpExcel->writeCellValue('F'.$j, "Longitute");
		$PhpExcel->writeCellValue('G'.$j, "Total Area");
		$PhpExcel->writeCellValue('H'.$j, "Shadowed Area");
		$PhpExcel->writeCellValue('I'.$j, "Shadow Free Area");
		$PhpExcel->writeCellValue('J'.$j, "Estimated Solar PV capacity");
		$PhpExcel->writeCellValue('K'.$j, "DG Availability");
		$PhpExcel->writeCellValue('L'.$j, "Electricity Bill");
		$PhpExcel->writeCellValue('M'.$j, "Accesibility to Roof");
		$PhpExcel->writeCellValue('N'.$j, "Name of the Distribution Company");
		$PhpExcel->writeCellValue('O'.$j, "Consumer No.");
		$PhpExcel->writeCellValue('P'.$j, "Contact Person");
		$PhpExcel->writeCellValue('Q'.$j, "Contact No.");
		$PhpExcel->writeCellValue('R'.$j, "Contact Email");
		$PhpExcel->writeCellValue('S'.$j, "Surveyor");
		$PhpExcel->writeCellValue('T'.$j, "Type of roof");
		$PhpExcel->writeCellValue('U'.$j, "Roof Strength");
		$PhpExcel->writeCellValue('V'.$j, "Age of Building");
		$PhpExcel->writeCellValue('W'.$j, "Azimuth");
		$PhpExcel->writeCellValue('X'.$j, "Inclination");
		$PhpExcel->writeCellValue('Y'.$j, "Height of Parapet?");
		$PhpExcel->writeCellValue('Z'.$j, "Floors");
		$PhpExcel->writeCellValue('AA'.$j, "Disctance DC cable");
		$PhpExcel->writeCellValue('AB'.$j, "Place for Inverter?");
		$PhpExcel->writeCellValue('AC'.$j, "Place for Battery?");
		$PhpExcel->writeCellValue('AD'.$j, "Place for AC Distribution Box?");
		$PhpExcel->writeCellValue('AE'.$j, "Place for Metering Point?");
		$PhpExcel->writeCellValue('AF'.$j, "Voltage Level");
		$PhpExcel->writeCellValue('AG'.$j, "Measured Frequency");
		$PhpExcel->writeCellValue('AH'.$j, "Critical Load");
		$PhpExcel->writeCellValue('AI'.$j, "Meter Type");
		$PhpExcel->writeCellValue('AJ'.$j, "Meter Accuracy Class");
		$PhpExcel->writeCellValue('AK'.$j, "Sanctioned Load");
		$PhpExcel->writeCellValue('AL'.$j, "Contract Demand");
		$PhpExcel->writeCellValue('AM'.$j, "Billing Cycle");

		$PhpExcel->getExcelObj()->getActiveSheet()->freezePane('A3');
		//$PhpExcel->getExcelObj()->getActiveSheet()->freezePane('B3');
		for($i=65;$i<=90;$i++)
		{
			$PhpExcel->fillCellFont((chr($i)).$j,'000000',TRUE);
		}
		for($i=65;$i<=77;$i++)
		{
			$PhpExcel->fillCellFont('A'.(chr($i)).$j,'000000',TRUE);
		}
		for($ch=65;$ch<=90;$ch++)
		{
			if($ch!=71 && $ch!=72 && $ch!=73  && $ch!=74  && $ch!=86 && $ch!=89)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension(chr($ch))->setAutoSize(true);
				
			}
		}
		for($ch=65;$ch<=77;$ch++)
		{
			if($ch!=65 && $ch!=66 && $ch!=67 && $ch!=68 && $ch!=69 && $ch!=70 && $ch!=71 && $ch!=72 && $ch!=74 && $ch!=75 && $ch!=76 && $ch!=77)
			{
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('A'.chr($ch))->setAutoSize(true);
			}
		}
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('G2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('H2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('I2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('J2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('V2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('Y2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AA2')->getAlignment()->setWrapText(true); 
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AB2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AC2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AD2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AE2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AF2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AG2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AH2')->getAlignment()->setWrapText(true);
		//$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AI2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AJ2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AK2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AL2')->getAlignment()->setWrapText(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getStyle('AM2')->getAlignment()->setWrapText(true);
		
		$counter_id=1;
		$arr_data=array();
		$j++;
		$PhpExcel->writeCellValue('G'.$j, "(sq.m)");
		$PhpExcel->writeCellValue('H'.$j, "(sq.m)");
		$PhpExcel->writeCellValue('I'.$j, "(sq.m)");
		$PhpExcel->writeCellValue('K'.$j, "kVA");
		
		$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
		$j++;
		$total_con_demand  = 0;
		$total_rec_cap     = 0;
		$total_area        = 0;
		$total_shadow_free = 0;
		$site_avail        = 0;
		$all_site_pow_con  = 0;
		foreach($result_data as $val)
		{
			$avg_pow_con     = 0;
			$avg_bill_amt    = 0;
			$total_gen_kva   = 0;
			$total_gen_hours = 0;
			if(!empty($val['genset_details']))
			{
				$arr_genset      = unserialize($val['genset_details']);
				foreach($arr_genset['GensetDetails']  as $val_gen)
				{
					$total_gen_kva   = $total_gen_kva + $val_gen['kva'];
					$total_gen_hours = $total_gen_hours + $val_gen['hours'];
				}
			}
			if(!empty($val['month_details']))
			{
				$site_avail++;
				$arr_month       = unserialize($val['month_details']);
				$arr_all_month   = $arr_month['ElectricityBillDetails'];
				$sum_power_con   = 0;
				$sum_bill_amount = 0;
				$total_val=0;
				for($i=0;$i<=11;$i++)
				{
					if(strtolower($arr_all_month[$i]['year'])!='year')
					{		
						$sum_power_con   = $sum_power_con+$arr_all_month[$i]['power_consume'];
						$sum_bill_amount = $sum_bill_amount+$arr_all_month[$i]['bill_amount'];
						$total_val++;
					}				
				}
				
				if($total_val>0)
				{
					$all_site_pow_con = $all_site_pow_con + ($sum_power_con/$total_val);
					$avg_pow_con      = number_format($sum_power_con/$total_val,'2','.',',');
					$avg_bill_amt     = number_format($sum_bill_amount/$total_val,'2','.',',');
				}
			}
			$shadow_free       = $val['shadow_free'];
			$overall           = $val['overall'];
			$height_of_parapet = $val['height_of_parapet'];
			$dc_cabel_distance = $val['dc_cabel_distance'];
			$sanctioned_load   = $val['sanctioned_load'];
			if($val['is_shadow_free'] == '2002')
			{
					$one_foot_m  = '0.3048';
					$shadow_free = $shadow_free * 0.3048;
			}
			if($val['is_overall'] == '2002')
			{
					$one_foot_m = '0.3048';
					$overall    = $overall * 0.3048;
			}
			if($val['is_height_of_parapet'] == '2002')
			{
					$one_foot_m = '0.3048';
					$height_of_parapet    = $height_of_parapet * 0.3048;
			}
			if($val['is_dc_cable_distance'] == '2002')
			{
					$one_foot_m = '0.3048';
					$dc_cabel_distance    = $dc_cabel_distance * 0.3048;
			}
			if($val['is_snaction'] == '0')
			{
				$sanctioned_load = $val['sanctioned_load'] * 0.8;
			}
			$disp_inverter_ph     = 'No';
			$disp_battery         = 'No';
			$disp_ac_distribution = 'No';
			$disp_meter_point     = 'No';
			
			$result_photo_data   = $this->SiteSurveysImages->find('list',array('keyField'=>'type', 'valueField'=>'photo', 'conditions' => array('building_id'=>$val->building_id, 'project_id' => $val->project_id)))->toArray();

			if(!empty($result_photo_data)) 
            { 
            	if(array_key_exists('place_inverter',$result_photo_data))
            	{
            		$disp_inverter_ph = 'Yes';
            	}
            	if(array_key_exists('place_battery',$result_photo_data)) 
                { 
                    $disp_battery = 'Yes';
                } 
                if(array_key_exists('place_for_ac_distribution_box',$result_photo_data)) 
                { 
                    $disp_ac_distribution = 'Yes';
                }
                if(array_key_exists('metering_box',$result_photo_data)) 
                { 
                    $disp_meter_point = 'Yes';
                }      
            } 
            
			$PhpExcel->writeCellValue('A'.$j, 'Site-'.$counter_id);
			$PhpExcel->fillCellFont('A'.$j,'000000',TRUE);
			$PhpExcel->writeCellValue('B'.$j, $val['building_name']);
			//$PhpExcel->writeCellValue('C'.$j, '--orientation--');
			$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
			$PhpExcel->writeCellValue('D'.$j, $val['created']);
			$PhpExcel->writeCellValue('E'.$j, $val['user_lat']);
			$PhpExcel->writeCellValue('F'.$j, $val['user_log']);
			$PhpExcel->writeCellValue('G'.$j, $overall);
			$PhpExcel->fillCellColour('G'.$j,'FFCC99');
			$PhpExcel->fillCellFont('G'.$j,'3F3F76');
			
			
			$PhpExcel->writeCellValue('H'.$j, ($overall-$shadow_free));
			$PhpExcel->fillCellColour('H'.$j,'F2F2F2');
			$PhpExcel->fillCellFont('H'.$j,'FA7D00',true);

			$PhpExcel->writeCellValue('I'.$j, $shadow_free);
			$PhpExcel->writeCellValue('J'.$j, $val['recommended_capacity']);
			$PhpExcel->fillCellColour('J'.$j,'F2F2F2');
			$PhpExcel->fillCellFont('J'.$j,'FA7D00',true);
			$PhpExcel->writeCellValue('K'.$j, $total_gen_kva.' kVA - '.$total_gen_hours.' Hours');
			$PhpExcel->writeCellValue('L'.$j, $avg_pow_con.' unit - '.$avg_bill_amt.' amount');
			$PhpExcel->writeCellValue('M'.$j, $val['object_on_roof']);
			$PhpExcel->writeCellValue('N'.$j, $val['distribution_company']);
			$PhpExcel->writeCellValue('O'.$j, $val['customer_no']);
			$PhpExcel->writeCellValue('P'.$j, $val['contact_name']);
			$PhpExcel->writeCellValue('Q'.$j, $val['mobile']);
			$PhpExcel->writeCellValue('R'.$j, $val['email_id']);
			$PhpExcel->writeCellValue('S'.$j, $val['surveyer_name']);
			$PhpExcel->writeCellValue('T'.$j, $all_roof[$val['roof_type']]);
			$PhpExcel->writeCellValue('U'.$j, $all_roof_st[$val['roof_strenght']]);
			$PhpExcel->writeCellValue('V'.$j, $val['age_of_building']);
			$PhpExcel->writeCellValue('W'.$j, $val['azimuth']);
			$PhpExcel->writeCellValue('X'.$j, $val['inclination_of_roof']);
			$PhpExcel->writeCellValue('Y'.$j, $height_of_parapet);
			$PhpExcel->writeCellValue('Z'.$j, $val['floor_below_tarrace']);
			$PhpExcel->writeCellValue('AA'.$j, $dc_cabel_distance);
			$PhpExcel->writeCellValue('AB'.$j, $disp_inverter_ph);
			$PhpExcel->writeCellValue('AC'.$j, $disp_battery);
			$PhpExcel->writeCellValue('AD'.$j, $disp_ac_distribution);
			$PhpExcel->writeCellValue('AE'.$j, $disp_meter_point);
			$PhpExcel->writeCellValue('AF'.$j, $val['voltage_pahse_level']);
			$PhpExcel->writeCellValue('AG'.$j, $val['measured_frequency']);
			$PhpExcel->writeCellValue('AH'.$j, $val['critical_load']);
			$PhpExcel->writeCellValue('AI'.$j, $all_meter[$val['meter_type']]);
			$PhpExcel->writeCellValue('AJ'.$j, $all_meter_accuracy[$val['meter_accuracy']]);
			$PhpExcel->writeCellValue('AK'.$j, $sanctioned_load);
			$PhpExcel->writeCellValue('AL'.$j, $val['contract_demand']);
			$PhpExcel->writeCellValue('AM'.$j, $all_billing[$val['billing_cycle']]);

			$total_con_demand  = $total_con_demand + $sanctioned_load;
			$total_rec_cap     = $total_rec_cap + $val['recommended_capacity'];
			$total_area        = $total_area + $overall;
			$total_shadow_free = $total_shadow_free + $shadow_free;
			$j++;	
			$counter_id++;
		}

		//sanctioned_load
		$PhpExcel->writeMergeCellValue('B'.$j.':C'.$j);
		$j++;

		$PhpExcel->writeCellValue('B'.$j, 'Total Area');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_area);
		$PhpExcel->fillCellColour('E'.$j,'F2F2F2');
		$PhpExcel->fillCellFont('E'.$j,'3F3F3F',true);
		
		$PhpExcel->writeCellValue('F'.$j, 'sq.m');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Shadow Free Area');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_shadow_free);
		$PhpExcel->fillCellColour('E'.$j,'F2F2F2');
		$PhpExcel->fillCellFont('E'.$j,'3F3F3F',true);
		$PhpExcel->writeCellValue('F'.$j, 'sq.m');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Estimated PV Capacity');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_rec_cap);
		$PhpExcel->fillCellColour('E'.$j,'F2F2F2');
		$PhpExcel->fillCellFont('E'.$j,'3F3F3F',true);
		$PhpExcel->writeCellValue('F'.$j, 'kWp');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Total Contract Demand');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $total_con_demand);
		$PhpExcel->fillCellColour('E'.$j,'FFCC99');
		$PhpExcel->fillCellFont('E'.$j,'3F3F76');
		$PhpExcel->writeCellValue('F'.$j, 'kVA');
		$j++;
		$anual_consume=0;
		$anual_month=0;
		if($site_avail>0)
		{
			$anual_consume = number_format($all_site_pow_con/$site_avail,'2','.',',');
			$anual_month   = number_format($anual_consume/12,'2','.',',');
		}
		$PhpExcel->writeCellValue('B'.$j, 'Average Electricity Consumption');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $anual_consume);
		$PhpExcel->writeCellValue('F'.$j, 'kWh p.a');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Electricity Consumption');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, $anual_month);
		$PhpExcel->writeCellValue('F'.$j, 'kWh p.m');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Tariff');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, '');
		$PhpExcel->writeCellValue('F'.$j, 'Rs./kWh');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Bill');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, '');
		$PhpExcel->writeCellValue('F'.$j, 'Rs./month');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Average Electricity Generation');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);
		$PhpExcel->writeCellValue('E'.$j, '');
		$PhpExcel->writeCellValue('F'.$j, 'kWh p.a');
		$j++;
		$PhpExcel->writeCellValue('D'.$j, '');
		$j++;
		$PhpExcel->writeCellValue('B'.$j, 'Ratio of Solar Againts Grid');
		$PhpExcel->writeMergeCellValue('B'.$j.':D'.$j);
		$PhpExcel->fillCellColour('B'.$j.':D'.$j,'000000');
		$PhpExcel->fillCellFont('B'.$j,'FFFFFF',TRUE);

		$PhpExcel->additonalSheet(3,'Electricity Bill');
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);	
		for($i=1;$i<=12;$i++)
		{
			$mon=date("F",mktime(0,0,0,$i,1,date("Y")));
		}
		$row_no=2;
		$counter_id=1;
		foreach($result_data as $val)
		{
			if(!empty($val['month_details']))
			{
			$PhpExcel->writeMergeCellValue('A'.($row_no).':E'.($row_no));
			$PhpExcel->fillCellColour('A'.($row_no).':E'.($row_no),'D7E4BD');
			$PhpExcel->writeCellValue('A'.($row_no), 'Site-'.$counter_id.' - '.$val['building_name']);
			$PhpExcel->fillCellFont('A'.($row_no),'000000',TRUE,'13px');
			$row_no++;
			$PhpExcel->writeCellValue('B'.($row_no), 'Month');
			$PhpExcel->writeCellValue('C'.($row_no), 'Year');
			$PhpExcel->writeCellValue('D'.($row_no), "Units");
			$PhpExcel->writeCellValue('E'.($row_no), "Amount");
			$PhpExcel->fillCellFont('B'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('C'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('D'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('E'.($row_no),'000000',TRUE);
			$row_no++;
			$arr_month=unserialize($val['month_details']);
			$arr_all_month=$arr_month['ElectricityBillDetails'];
			$sum_power_con=0;
			$sum_bill_amount=0;
			$total_val=0;
			for($i=0;$i<=11;$i++)
			{
				$PhpExcel->writeCellValue('B'.($row_no), $arr_all_month[$i]['month']);
				if(strtolower($arr_all_month[$i]['year'])!='year')
				{
					$PhpExcel->writeCellValue('C'.($row_no), $arr_all_month[$i]['year']);
					$PhpExcel->writeCellValue('D'.($row_no), $arr_all_month[$i]['power_consume']);
					$PhpExcel->writeCellValue('E'.($row_no), $arr_all_month[$i]['bill_amount']);
					$sum_power_con   = $sum_power_con+$arr_all_month[$i]['power_consume'];
					$sum_bill_amount = $sum_bill_amount+$arr_all_month[$i]['bill_amount'];
					$total_val++;
				}				
				$row_no++;
			}
			$PhpExcel->writeCellValue('B'.($row_no), 'Average');
			$PhpExcel->fillCellFont('B'.($row_no),'000000',TRUE);
			$PhpExcel->writeCellValue('C'.($row_no), '');
			$avg_pow_con=0;
			$avg_bill_amt=0;
			if($total_val>0)
			{
				$avg_pow_con  = number_format($sum_power_con/$total_val,'2','.',',');
				$avg_bill_amt = number_format($sum_bill_amount/$total_val,'2','.',',');
			}
			$PhpExcel->writeCellValue('D'.($row_no), $avg_pow_con);
			$PhpExcel->writeCellValue('E'.($row_no), $avg_bill_amt);
			$PhpExcel->fillCellFont('D'.($row_no),'000000',TRUE);
			$PhpExcel->fillCellFont('E'.($row_no),'000000',TRUE);
			$row_no++;
			}
			$counter_id++;
		}
		if($mail_send == 0)
		{
			$PhpExcel->downloadFile(time());
		}
		else
		{
			$file_path = $PhpExcel->downloadFile_mail(time());
			return $file_path;
		}
		exit;
	}
	/**
	 *
	 * genratesurveyPDFreport
	 *
	 * Behaviour : public
	 *
	 * @param : site_id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=false, $isInstallerhide=0
	 *
	 * @defination : Method is use to download .pdf file from modal popup of survey listing
	 *
	 */
	public function genratesurveyPDFreport($site_id,$isdownload=false,$is_project=0)
	{
		$this->layout 		= false;
		if($is_project==1)
		{
			$project_id 			= $site_id;
			$result_data 			= $this->SiteSurveys->find('all',array('conditions' => array('project_id'=>$project_id)))->toArray();
			$result_project_data 	= $this->Projects->find('all',array('conditions' => array('id' => $project_id)))->first();
		}
		else
		{
			$result_data 			= $this->SiteSurveys->find('all',array('conditions' => array('id'=>$site_id)))->toArray();
			
		}
		$result_project_data 		= $this->Projects->find('all',array('conditions' => array('id' => $result_data[0]['project_id'])))->first();
		$result_installer 	 		= $this->InstallerProjects->find('all',[
									'join' => [
	                            		'installers' => [
			                                'table' => 'installers',
			                                'type' => 'LEFT',
			                                'conditions' => ['InstallerProjects.installer_id = installers.id']
			                            ],
			                        	'companies' => [
			                                'table' => 'companies',
			                                'type' => 'LEFT',
			                                'conditions' => ['installers.company_id = companies.id']
			                            ]],
			                        'fields' => array('installers.id','installers.installer_name','installers.contact_person','installers.company_id','companies.company_name'),
									'conditions' => ['project_id' => $result_data[0]['project_id'], 'InstallerProjects.status' => '4002']])->toArray();
		$projectReportId 			= $this->Projects->GetProjectPDFReportId($site_id);
		
		foreach($result_data as $survey_data)
		{
			$result_photo_data[$survey_data->id] 		= $this->SiteSurveysImages->find('list',array('keyField'=>'type', 'valueField'=>'photo', 'conditions' => array('building_id'=>$survey_data->building_id, 'project_id' => $survey_data->project_id)))->toArray();
			
			$all_photo_data[$survey_data->id]      		= $this->SiteSurveysImages->find('all',array('conditions' => array('building_id'=>$survey_data->building_id, 'project_id' => $survey_data->project_id)))->toArray();
			

			$stream_opts =  [
	    					"ssl" => [
					        "verify_peer"=>false,
					        "verify_peer_name"=>false,
					    	]
							];  
			$mapImage[$survey_data->id]     = '';

			if($survey_data['site_lat']!=0 && $survey_data['site_log']!=0)
			{
				$latLng   = $survey_data['site_lat'].",".$survey_data['site_log'];
				$mapUrl   = 'https://maps.googleapis.com/maps/api/staticmap?center='.$latLng.'&maptype=hybrid&zoom=10&size=650x378&markers=color:blue%7C'.$latLng.'&sensor=false';
				$mapImage[$survey_data->id] = file_get_contents($mapUrl, false, stream_context_create($stream_opts));
				$mapImage[$survey_data->id] = 'data:image/png;base64,' . base64_encode($mapImage[$survey_data->id]);
			}
		}
		$arr_subscription = array();
		if(!empty($result_installer))
        {
        	$arr_subscription = $this->InstallerSubscription->find('all',array('conditions'=>array('installer_id'=>$result_installer[0]['installers']['id'],'expire_date >='=>date('Y-m-d'),'free_flag'=>'0')))->toArray();
        }
        $this->set('arr_subscription',$arr_subscription);
		$this->set('footer_st_content','Site Survey PV Project Report');
		$this->set('result_data_pass',$result_data);
		$this->set('result_installer',$result_installer);
		$this->set('result_project_data',$result_project_data);
		$this->set('result_photo_data_projects',$result_photo_data);
		$this->set('all_photo_data_projects',$all_photo_data);
		$this->set('all_roof',$this->SiteSurveys->ALL_ROOF_TYPE);
		$this->set('all_roof_st',$this->SiteSurveys->ALL_ROOF_STRENGTH);
		$this->set('all_area_types',$this->SiteSurveys->AREA_PARAMS);
		$this->set('all_area_type_smp',$this->SiteSurveys->AREA_PARAMS_SMP);
		$this->set('all_meter',$this->SiteSurveys->ALL_METER_TYPE);
		$this->set('all_meter_accuracy',$this->SiteSurveys->ALL_METER_ACCURACY_CLASS);
		$this->set('all_billing',$this->SiteSurveys->ALL_BILLING_CYCLE);
		$this->set('all_load',$this->SiteSurveys->LOAD_PARAMS);
		$this->set('all_cust_type',$this->SiteSurveys->ALL_CUSTOMER_TYPE);
		$this->set('mapImage',$mapImage);
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		//$dompdf->set_option('defaultFont', "Courier");
		
		$html = $this->render('/Element/site_survey');
		
		//exit($html);
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		
		$dompdf->render();
		
		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('reportsurvey-'.$projectReportId);	
		}
		$output = $dompdf->output();
		$pdfPath = SITE_ROOT_DIR_PATH.'/tmp/reportsurvey-'.$projectReportId.'.pdf';
		file_put_contents($pdfPath, $output);	
		return $pdfPath; 
	}

	/**
	 * generateApplicationPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from modal popup of applyonline listing
	 *
	 */
	public function generateApplicationPdf($id,$isdownload=true,$mobile=false)
	{
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');             
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			$applyOnlinesDataDocList 	= $this->ApplyonlinDocs->find("all",['conditions'=>['application_id'=>$id,'doc_type'=>'others']])->toArray();
			$Applyonlinprofile  		= $this->ApplyonlinDocs->find('all',['conditions'=>['application_id'=>$id,'doc_type'=>'profile']])->first();
			$divison_list = "";
			if(!empty($applyOnlinesData->division)){
				$divison_list = $this->DiscomMaster->find('all',['conditions'=>['id'=>$applyOnlinesData->division]])->first();
			}
		}
		$discom_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();
		$payumoney_data = $this->Payumoney->find('all',['fields'=>array('Payumoney.transaction_id','Payumoney.payment_date'),'join'=>[
				        'ap' => [
				            'table' => 'applyonline_payment',
				            'type' => 'INNER',
				            'conditions' => ['Payumoney.id = ap.payment_id']
		            	]]])->where(['ap.application_id' => $id])->first();
		
	
		$transaction_id='';
		$payment_date='';
		if(!empty($payumoney_data))
		{
			$transaction_id=($payumoney_data->transaction_id);
			$payment_date=(!empty($payumoney_data->payment_date) ? date(LIST_DATE_FORMAT,strtotime($payumoney_data->payment_date)) : '');
		}
		$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
		if(isset($Applyonlinprofile->couchdb_id) && !empty($Applyonlinprofile->couchdb_id)) 
		{
			$apply_profile_data 		= $this->Couchdb->find('all',array('conditions'=>array('id'=>$Applyonlinprofile->couchdb_id)))->first();
		}
		
		$documentProfile 	= '';
		if(isset($apply_profile_data) && !empty($apply_profile_data)) {
			require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
			$COUCHDB 		= new Couchdb();
			$documentProfile= $COUCHDB->getDocument($apply_profile_data->document_id,$apply_profile_data->file_attached,$apply_profile_data->doc_mime_type,1);
			
		}
		$submitedStage 				= $this->ApplyOnlineApprovals->getsubmittedStageData($id);
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set("applyOnlinesDataDocList",$applyOnlinesDataDocList);
		$this->set("discom_list",$discom_list);
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('transaction_id',$transaction_id);
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$this->set('APPLICATION_DATE',$APPLICATION_DATE);
		$this->set('payment_date',$payment_date);
		$this->set('Applyonlinprofile',$Applyonlinprofile);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set("divison_list",$divison_list);
		$this->set('applyOnlinesOthersData',$applyOnlinesOthersData);
		$this->set('submitedStage',$submitedStage);
		$this->set('Couchdb',$this->Couchdb);
		$this->set('documentProfile',$documentProfile);
		/* Generate PDF for estimation of project */

		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';

		//$options = new Options();
		//$options->set('enable_html5_parser', true);
		//$options['enable_html5_parser'] = true;
		$dompdf = new Dompdf($options = array());
		
		//$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		//$dompdf->set_option("enable_html5_parser", true);

		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$html = $this->render('/Element/applyonline');
		
		$dompdf->loadHtml($html,'UTF-8');

		$dompdf->setPaper('A4', 'portrait');

		@$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('applyonline-'.$LETTER_APPLICATION_NO);	
		}
		$output = $dompdf->output();
		if($mobile){
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
			echo $output;
			die;
		
		}		
	}
	/**
	 * generateGedaInspectionLetterPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateGedaInspectionLetterPdf($id,$isdownload=true)
	{
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			if (empty($applyOnlinesData)) {
				$this->Flash->error('Please Select Valid Application.');
				return $this->redirect('home');
			}
		}
		$InspectionReport 	= $this->InspectionReport->getInspectionReport(3,$id);
		$inspection_data 	= "";
		if (isset($InspectionReport->inspection_data) && !empty($InspectionReport->inspection_data)) {
			$inspection_data = unserialize($InspectionReport->inspection_data);
		}
		$APPLICATION_DATE = date("d.m.Y",strtotime($applyOnlinesData->created));

		$CUSTOMER_ADDRESS = '';

		if (!empty($applyOnlinesData->address1)) {
			$CUSTOMER_ADDRESS .= $applyOnlinesData->address1;
		}
		if (!empty($applyOnlinesData->address1)) {
			$CUSTOMER_ADDRESS .= ", ".$applyOnlinesData->address2;
		}
		if (!empty($applyOnlinesData->city)) {
			$CUSTOMER_ADDRESS .= "<br />".$applyOnlinesData->city;
		}
		if (!empty($applyOnlinesData->state)) {
			$CUSTOMER_ADDRESS .= " ".$applyOnlinesData->state;
		}
		if (!empty($applyOnlinesData->pincode)) {
			$CUSTOMER_ADDRESS .= " ".$applyOnlinesData->pincode;
		}
		$CUSTOMER_ADDRESS = trim(", ",$CUSTOMER_ADDRESS);

		$this->set("INSTALLER_NAME",$applyOnlinesData->installer_name);
		$this->set("CUSTOMER_NAME",$applyOnlinesData->name_of_consumer_applicant);
		$this->set("CUSTOMER_ADDRESS",$CUSTOMER_ADDRESS);
		$this->set("GEDA_REGISTRATION_NO",$applyOnlinesData->geda_application_no);
		$this->set("INSPECTION_DATA",$inspection_data);
		$this->set("pageTitle","GEDA APPROVAL FORM - ".$applyOnlinesData->geda_application_no);

		$LETTER_APPLICATION_NO	= "1".str_pad($id,7, "0", STR_PAD_LEFT);
		
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$html = $this->render('/Element/pdf-template/geda_approval_form');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
		echo $output;
		die;
	}
	/**
	 * generateGedaLetterPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateGedaLetterPdf($id,$isdownload=true,$mobile=false)
	{
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');             
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			$applyOnlinesOthersData 	= $this->ApplyOnlinesOthers->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			$applyOnlinesData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$submitedDateDetails 		= $this->ApplyOnlineApprovals->getsubmittedStageData($id);
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($submitedDateDetails->created));
			//$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			$Installers_data = $this->Installers->find("all",['conditions'=>['id'=>$applyOnlinesData->installer_id]])->first();
		    $Members = $this->Members->find("all",['conditions'=>['member_type'=>'6003','name'=>'CEI']])->first();
		    $discom_data		= array();
		    $discom_name    	= "";
		    $discom_short_name	= "";
		    if(!empty($applyOnlinesData->area)){
		    	$discom_data                = $this->Members->find("all",['conditions'=>['area'=>$applyOnlinesData->area,'circle'=>'0','division'=>'0','subdivision'=>'0','section'=>'0']])->first();
		    	$discom_name                = $this->BranchMasters->find("all",['conditions'=>['id'=>$discom_data->branch_id]])->first();
		    	$discom_short_name          = $this->DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
		    }
		   
		}
		$category_name = '';
		if($applyOnlinesData->social_consumer==1)
		{
			$category_name = 'Institutional-social';
		}
		else{
			if($applyOnlinesData->category==3001){
				$category_name = 'residential';
			}
			else{
				$category_name = 'industrial/commercial';
			}
		}
		$applyOnlineGedaDate= $this->ApplyOnlineApprovals->getgedaletterStageData($id);
		$project_data 		= $this->Projects->find("all",['conditions'=>['id'=>$applyOnlinesData->project_id]])->first();

		$date= date('Y-m-d',strtotime($applyOnlineGedaDate->created));
		$NewText ='';
		$New_updates 	= New_updates;
		if($New_updates <= $date){
				$NewText = 'The provisions of "Approved List of Models and Manufacturers" (ALMM) & its amendment issued by MNRE from time to time shall be applicable.';
        }
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('Installers_data',$Installers_data);
		$this->set('Members',$Members);
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$this->set('APPLICATION_DATE',$APPLICATION_DATE);
		$this->set('discom_data',$discom_data);
		$this->set('discom_name',$discom_name);
		$this->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$this->set('project_data',$project_data);
		$this->set('category_name',$category_name);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set('discom_short_name',$discom_short_name);
		$this->set('applyOnlinesOthersData',$applyOnlinesOthersData);
		$this->set('NewText',$NewText);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());

		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		
		
		$currentdate 	= strtotime($applyOnlinesData->created);
		$submitedStage 	= $this->ApplyOnlineApprovals->getsubmittedStageData($applyOnlinesData->id);
		if($applyOnlinesOthersData->scheme_id == 3) {
			$html = $this->render('/Element/applyonlinescheme3');
		} else {
			if($applyOnlinesData->social_consumer==1 && ($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0))
			{
				if($currentdate >= strtotime(OPEN_NEW_QUATA) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(OPEN_NEW_QUATA)))) {
					$html = $this->render('/Element/applyonlineindustrialopen');
				} else {
					$html = $this->render('/Element/applyonlineindustrial');
				}
			}
			elseif($applyOnlinesData->social_consumer==1)
			{
				$html = $this->render('/Element/applyonlinesocialsector');
			}
			elseif($applyOnlinesData->govt_agency==1)
			{
				if($currentdate >= strtotime(OPEN_NEW_QUATA) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(OPEN_NEW_QUATA)))) {
					$html = $this->render('/Element/applyonlinegovernmentopen');
				} else {
					$html = $this->render('/Element/applyonlinegovernment');
				}
			}
			elseif($applyOnlinesData->disclaimer_subsidy==1)
			{
				if(($applyOnlinesOthersData->renewable_attr == 1 || $applyOnlinesOthersData->renewable_attr === 0) && $applyOnlinesData->category!=3001)
				{
					if($currentdate >= strtotime(OPEN_NEW_QUATA) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(OPEN_NEW_QUATA)))) {
						$html = $this->render('/Element/applyonlineindustrialopen');
					} else {
						$html = $this->render('/Element/applyonlineindustrial');
					}
				}
				else
				{
					if($currentdate >= strtotime(OPEN_NEW_QUATA) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(OPEN_NEW_QUATA)))) {
						$html = $this->render('/Element/applyonlinenonsubsidyopen');
					} else {
						$html = $this->render('/Element/applyonlinenonsubsidy');
					}
				}
			}
			else{
				if($applyOnlinesData->category==3001)
				{
					$html = $this->render('/Element/applyonlineresidencial');
				}
				else
				{
					$html = $this->render('/Element/applyonlineindustrial');
				}
			}
		}
		
		
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		@$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			if($applyOnlinesData->social_consumer==1)
			{
				$dompdf->stream('applyonlinesocialsector-'.$LETTER_APPLICATION_NO);
			}else{
				if($applyOnlinesData->category==3001)
				{
					$dompdf->stream('applyonlineresidencial-'.$LETTER_APPLICATION_NO);
				}
			else 
				{
					$dompdf->stream('applyonlineindustrial-'.$LETTER_APPLICATION_NO);
				}
			}	
		}
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename=".$LETTER_APPLICATION_NO.".pdf");
		echo $output;
		die;
	}
	/**
	 * generateDiscomLetterPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from discom ispection
	 *
	 */
	public function generateDiscomLetterPdf($id,$isdownload=true)
	{
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');             
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			if(empty($applyOnlinesData['apply_onlines_others']['jir_unique_code']))
			{
				$jir_unique_code 		= getRandomNumber(16);
				$this->ApplyOnlinesOthers->updateAll(array('jir_unique_code'=>$jir_unique_code),array('application_id'=>$id));
				$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			}

			$applyOnlinesData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));
			
			$project_data = $this->Projects->find("all",['conditions'=>['id'=>$applyOnlinesData->project_id]])->first();
			$charging_cert_table 		= TableRegistry::get('ChargingCertificate');
			$Charging_data 				= $charging_cert_table->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			$ins_table 		= TableRegistry::get('Installation');
			$Ins_data 					= $ins_table->find('all',array('conditions'=>array('project_id'=>$applyOnlinesData->project_id)))->first();
			$discom_list = $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.parent_id'=>'0']])->toArray();

			if(!empty($project_data))
	        {
	        	$modules_data     = isset($Ins_data->modules_data) ? unserialize($Ins_data->modules_data) : '';
				$inverter_data     = isset($Ins_data->inverter_data) ? unserialize($Ins_data->inverter_data) : '';
	                        $total_commulative= 0;
	            for($i=1;$i<=3;$i++)
	            {
	                $row            = $i-1;
	                $m_capacity     = '';
	                $m_make         = '';
	                $m_modules      = '';
	                $m_type_modules = '';
	                $m_type_other   = '';
	                if (isset($modules_data[$row])) 
	                {
	                    $m_capacity         = $modules_data[$row]['m_capacity'];
	                    $m_make             = $modules_data[$row]['m_make'];
	                    $m_modules          = $modules_data[$row]['m_modules'];
	                    $m_type_modules     = $modules_data[$row]['m_type_modules'];
	                    $m_type_other       = $modules_data[$row]['m_type_other'];
	                    $total_commulative  = $total_commulative + (floatval($modules_data[$row]['m_capacity']) * floatval($modules_data[$row]['m_modules']));
	                }
	            }
	            if ($total_commulative > 0) 
				{
					$total_commulative  = ($total_commulative/1000);
				}
	          	$total_commulative_i  = 0;
				for($i=1;$i<=3;$i++)
				{
					$row                  = $i-1;
					$i_capacity           = '';
					$i_make               = '';
					$i_make_other         = '';
					$i_modules            = '';
					$i_type_modules       = '';
					$i_type_other         = '';
					$i_phase              = '';
					if (isset($inverter_data[$row])) 
					{
						$i_capacity         = $inverter_data[$row]['i_capacity'];
						$i_make             = $inverter_data[$row]['i_make'];
						$i_make_other       = $inverter_data[$row]['i_make_other'];
						$i_modules          = $inverter_data[$row]['i_modules'];
						$i_type_modules     = $inverter_data[$row]['i_type_modules'];
						$i_type_other       = $inverter_data[$row]['i_type_other'];
						if(isset($inverter_data[$row]['i_phase']))
						{
						  $i_phase       = $inverter_data[$row]['i_phase'];
						}
						$total_commulative_i= $total_commulative_i + (floatval($inverter_data[$row]['i_capacity'])*floatval($inverter_data[$row]['i_modules']));
					}
				}
				if ($total_commulative_i > 0) 
	            {
	                $total_commulative_i  = ($total_commulative_i);
	            }
	            $min_cap = min($total_commulative,$total_commulative_i,$applyOnlinesData->pv_capacity);
	            $app_details        = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$applyOnlinesData->project_id)))->first();
	            $arr_project_data['proj_name']              = $project_data->name;
	            $arr_project_data['latitude']               = $project_data->latitude;
	            $arr_project_data['longitude']              = $project_data->longitude;
	            $arr_project_data['customer_type']          = $project_data->customer_type;
	            $arr_project_data['project_type']           = $project_data->customer_type;
	            $arr_project_data['area']                   = $project_data->area;
	            $arr_project_data['area_type']              = $project_data->area_type;
	            $arr_project_data['bill']                   = $project_data->avg_monthly_bill;
	            $arr_project_data['avg_monthly_bill']       = $project_data->avg_monthly_bill;
	            $arr_project_data['backup_type']            = $project_data->backup_type;
	            $arr_project_data['usage_hours']            = $project_data->usage_hours;
	            $arr_project_data['energy_con']             = $project_data->estimated_kwh_year;
	            $arr_project_data['recommended_capacity']   = $min_cap;
	            $arr_project_data['address']                = $project_data->address;
	            $arr_project_data['city']                   = $project_data->city;
	            $arr_project_data['state']                  = $project_data->state;
	            $arr_project_data['state_short_name']       = $project_data->state_short_name;
	            $arr_project_data['country']                = $project_data->country;
	            $arr_project_data['postal_code']            = $project_data->pincode;
	            $arr_project_data['Projects']['id']         = $project_data->id;
	            $result                                     = $this->Projects->getprojectestimationV2($arr_project_data,$app_details->customer_id,true);
	        }
	        $project_data = $this->Projects->find("all",['conditions'=>['id'=>$applyOnlinesData->project_id]])->first();
			$project            = $this->CustomerProjects->findByProjectId($applyOnlinesData->project_id)->contain("Projects","Customers")->first();

			$calculate_subsidy = $project['project']['estimated_cost'];

            if(!empty($applyOnlinesData) && $applyOnlinesData->disclaimer_subsidy==1 && SHOW_SUBSIDY_EXECUTION==1)
            {
                $calculate_subsidy  = 0;
            }
            $subsidy_data = $this->Projects->calculatecapitalcostwithsubsidy($project['project']['recommended_capacity'],$calculate_subsidy,$project['project']['state'],$project['project']['customer_type'],true,$applyOnlinesData->social_consumer);

            if ($subsidy_data['state_subcidy_type'] == 0) {
                $STATE_SUBSIDY          = $subsidy_data['state_subsidy']."%";
                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$this->get_money_format($subsidy_data['state_subsidy_amount']):"-";
            } else {
                $STATE_SUBSIDY          = ($subsidy_data['state_subsidy'] > 0)?$this->get_money_format($subsidy_data['state_subsidy']):"-";
                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$this->get_money_format($subsidy_data['state_subsidy_amount']):"-";
            }
            if($applyOnlinesData->social_consumer==1 || $applyOnlinesData->common_meter==1)
            {
            	$subsidy_data['state_subsidy_amount'] 	= 0;
            	$STATE_SUBSIDY 							= 0;
            	$STATE_SUBSIDY_AMOUNT 					= '-';
            }
            if ($subsidy_data['central_subcidy_type'] == 0) {
                $CENTRAL_SUBSIDY            = $subsidy_data['central_subsidy']."%";
                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$this->get_money_format($subsidy_data['central_subsidy_amount']):"-";
            } else {
                $CENTRAL_SUBSIDY            = ($subsidy_data['central_subsidy'] > 0)?$this->get_money_format($subsidy_data['central_subsidy']):"-";
                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$this->get_money_format($subsidy_data['central_subsidy_amount']):"-";
            } 
            $total_amount = array($subsidy_data['state_subsidy_amount'],$subsidy_data['central_subsidy_amount']);
            $subsidy_amount =array_sum($total_amount);
            $TOTAL_SUBSIDY_AMOUNT   = ($subsidy_amount > 0)?$this->get_money_format($subsidy_amount):"-";    
		}
		
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('project_data',$project_data);
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$this->set('APPLICATION_DATE',$APPLICATION_DATE);
		$this->set('Charging_data',$Charging_data);
		$this->set('Ins_data',$Ins_data);
		$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set("discom_list",$discom_list);
		$this->set('MNRE_subsidy_amount',$CENTRAL_SUBSIDY_AMOUNT);
        $this->set('state_subsidy_amount',$STATE_SUBSIDY_AMOUNT);
        $this->set('total_subsidy_amount',$TOTAL_SUBSIDY_AMOUNT);

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$html = $this->render('/Element/discominspection');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('discominspection-'.$LETTER_APPLICATION_NO);
		} 
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
		echo $output;
		die;
	}
		/**
	 * generateFesibilityReportPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from fesibility report.
	 *
	 */
	public function generateFesibilityReportPdf($id,$isdownload=true)
	{
		$this->layout 					= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Installer.');             
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			$FeasibilityData 			= $this->FesibilityReport->find("all",['conditions'=>['application_id'=>$id]])->first();
			$subdivision 				= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->subdivision,'DiscomMaster.type'=>4]])->toArray();
			$division 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->division,'DiscomMaster.type'=>3]])->toArray();
			$circle 					= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.id'=>$applyOnlinesData->circle,'DiscomMaster.type'=>2]])->toArray();
			$BillCategoryList 			= $this->Parameters->GetParameterList(3)->toArray();
			$applyOnlinesData->aid 		= $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$FILENAME 					= str_replace("/","-",$LETTER_APPLICATION_NO);
			$category 					= "N/A";
			foreach($BillCategoryList as $key=>$val){
				if($key==$applyOnlinesData->category){
					$category 			= $val;	
				}
			}
		}
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Apply-online View");
		$this->set('ApplyOnlines',$applyOnlinesData);
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set('FeasibilityData',$FeasibilityData);
		$this->set('subdivision',$subdivision);
		$this->set('division',$division);
		$this->set('circle',$circle);
		$this->set('BillCategoryList',$BillCategoryList);
		$this->set('category',$category);
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$html = $this->render('/Element/feasibility_report');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('feasibility_report-'.$FILENAME);
		}
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$FILENAME.".pdf'");
		echo $output;
		die;	
	}
	
	/**
	 * generateAgreementLetter
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateAgreementLetter($id,$isdownload=true)
	{
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$applyOnlinesData 			= $this->ApplyOnlines->viewApplication($id);
			if (empty($applyOnlinesData)) {
				$this->Flash->error('Please Select Valid Application.');
				return $this->redirect('home');
			}
		}
		$Installer 			= $this->Installers->get($applyOnlinesData->installer_id);
		$INSTALLER_ADDRESS = '';
		if (!empty($Installer->address)) {
			$INSTALLER_ADDRESS .= $Installer->address;
		}
		if (!empty($Installer->city)) {
			$INSTALLER_ADDRESS .= ", ".$Installer->city;
		}
		if (!empty($Installer->state)) {
			$INSTALLER_ADDRESS .= " ".$Installer->state;
		}
		if (!empty($Installer->pincode)) {
			$INSTALLER_ADDRESS .= " ".$Installer->pincode;
		}
		$INSTALLER_ADDRESS = trim($INSTALLER_ADDRESS,", ");

		$this->set("INSTALLER_NAME",$Installer->installer_name);
		$this->set("CUSTOMER_NAME",$applyOnlinesData->name_of_consumer_applicant);
		$this->set("INSTALLER_ADDRESS",$INSTALLER_ADDRESS);
		$this->set("GEDA_REGISTRATION_NO",$applyOnlinesData->geda_application_no);
		$this->set("pageTitle","GEDA AGREEMENT LETTER - ".$applyOnlinesData->geda_application_no);
		$LETTER_APPLICATION_NO	= "1".str_pad($id,7, "0", STR_PAD_LEFT);
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$html = $this->render('/Element/pdf-template/geda_agreement_letter');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
		echo $output;
		die;
	}
	 /*
     * get_money_format
     * @param mixed $amount
     * @param boolean $suffix
     * @return mixed $thecash
     */
    private function get_money_format($amount, $suffix = 1)
    {
        $explrestunits = "";
        $num = $amount;
        if(strlen($num)>3) {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        if(!$suffix) {
            return $thecash;
        } else {
           // return 'Rs. '. $thecash.'/-';
        	setlocale(LC_MONETARY, 'en_IN');
        	if (function_exists('money_format')) {
				$amount = money_format('%!i', $amount);
			} else {
               $amount  = number_format($amount,"2",".",",");
            }
        	return 'Rs. '.$amount.'/-';
        }
    }

	/**
	 * getSubsidySummarySheet
	 * Behaviour : public
	 * @param : id  : application_ids is use to generate applications
	 * @defination : Method is use to view summary.pdf file from applyonline listing
	 *
	 */
	public function getSubsidySummarySheet($application_id="")
	{
		$this->ApplyOnlines->generateSubsidySummarySheet(decode($application_id),false);
	}
	/**
	 * removeExtraTags
	 * Behaviour : public
	 * @param : 
	 * @defination : Method is use to remove extra tags from posted parameter
	 *
	 */
	public function removeExtraTags($typeForm='')
	{
		$ArrReuqest 	= ($typeForm=='') ? $this->request->data : (isset($this->request->data[$typeForm]) ? $this->request->data[$typeForm] : '');
		if(!empty($ArrReuqest))
		{
			foreach($ArrReuqest as $k1=>$v1)
			{
				if(!is_array($v1) && $k1!='remarks')
				{
					if($typeForm=='')
					{
						$this->request->data[$k1] = strip_tags(DBVarConv($v1));
					}
					else
					{
						$this->request->data[$typeForm][$k1] = strip_tags(DBVarConv($v1));
					}
				}
			}
		}
	}
}
