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
use Cake\Datasource\ConnectionManager;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Dompdf\Dompdf;

class SiteSurveysController extends AppController
{	
	var $helpers = array('Time','Html','Form','ExPaginator');
	public $arrDefaultAdminUserRights 	= array(); 
	public $PAGE_NAME 					= '';
	public $contact_code_min = 	1000;
	public $contact_code_max =	9999;
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
		$this->loadModel('UserDepartment');
		$this->loadModel('Admintrntype');
		$this->loadModel('Admintrnmodule');
		$this->loadModel('ApiToken');
		$this->loadModel('GhiData');
		$this->loadModel('Installers');
		$this->loadModel('Projects');
		$this->loadModel('Customers');
		$this->loadModel('CustomerProjects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('ProjectLeads');		
		$this->loadModel('SiteSurveys');		
		$this->loadModel('SiteSurveysImages');		
		$this->loadModel('Commissioning');		
		$this->loadModel('Parameters');
        $this->loadModel('SitesurveyProjectRequest');
        $this->loadModel('CommissioningData');
        $this->loadModel('CommissioningImage');
		$this->set('Userright',$this->Userright);
    }

    private function SetVariables($post_variables) { 

		if(isset($post_variables['lat']))
			$this->request->data['SiteSurveys']['latitude']				= $post_variables['lat'];
		if(isset($post_variables['proj_id']))
			$this->request->data['SiteSurveys']['id']					= $post_variables['proj_id'];
		if(isset($post_variables['proj_name']))
			$this->request->data['SiteSurveys']['name'] 				= $post_variables['proj_name'];
		if(isset($post_variables['proj_name']))
			$this->request->data['SiteSurveys']['project_name'] 		= $post_variables['proj_name'];
		if(isset($post_variables['verification_code']))
			$this->request->data['SiteSurveys']['verification_code']	= $post_variables['verification_code'];
		if(isset($post_variables['address']))
			$this->request->data['SiteSurveys']['address']				= $post_variables['address'];
		if(isset($post_variables['city']))
			$this->request->data['SiteSurveys']['city']					= $post_variables['city'];
		if(isset($post_variables['state']))
			$this->request->data['SiteSurveys']['state']				= $post_variables['state'];
		if(isset($post_variables['country']))
			$this->request->data['SiteSurveys']['country']				= $post_variables['country'];
		if(isset($post_variables['pincode']))
			$this->request->data['SiteSurveys']['pincode']				= $post_variables['pincode'];
		if(isset($post_variables['lat']))
			$this->request->data['SiteSurveys']['latitude']				= $post_variables['lat'];
		if(isset($post_variables['lon']))
			$this->request->data['SiteSurveys']['longitude']			= $post_variables['lon'];
		if(isset($post_variables['solar_radiation']))
			$this->request->data['SiteSurveys']['solar_radiation']		= $post_variables['solar_radiation'];
		if(isset($post_variables['roof_area']))
			$this->request->data['SiteSurveys']['area']					= $post_variables['roof_area'];
		if(isset($post_variables['area_type']))	
			$this->request->data['SiteSurveys']['area_type']			= $post_variables['area_type'];
		if(isset($post_variables['c_type']))
			$this->request->data['SiteSurveys']['customer_type']		= $post_variables['c_type'];
		if(isset($post_variables['capacity_kw']))
			$this->request->data['SiteSurveys']['capacity_kw']			= $post_variables['capacity_kw'];
		if(isset($post_variables['esti_cost']))
			$this->request->data['SiteSurveys']['estimated_cost']		= $post_variables['esti_cost'];
		if(isset($post_variables['energy_con']))
			$this->request->data['SiteSurveys']['avg_energy_consumption']	= $post_variables['energy_con'];
		if(isset($post_variables['customized']))
			$this->request->data['SiteSurveys']['customized']			= $post_variables['customized'];
		if(isset($post_variables['discom_id']))
			$this->request->data['SiteSurveys']['discom_id']			= $post_variables['discom_id'];
		if(isset($post_variables['bill']))
			$this->request->data['SiteSurveys']['avg_monthly_bill']		= $post_variables['bill'];
		if(isset($post_variables['contract_load']))
			$this->request->data['SiteSurveys']['contract_load']		= $post_variables['contract_load'];
		if(isset($post_variables['backup_type']))
			$this->request->data['SiteSurveys']['backup_type']			= $post_variables['backup_type'];
		if(isset($post_variables['capacity']))
			$this->request->data['SiteSurveys']['diesel_genset_kva']	= $post_variables['capacity'];
		if(isset($post_variables['usage_hours']))
			$this->request->data['SiteSurveys']['usage_hours']			= $post_variables['usage_hours'];
		if(isset($post_variables['estimated_saving_year']))
			$this->request->data['SiteSurveys']['estimated_saving_year']	= $post_variables['estimated_saving_year'];
		if(isset($post_variables['op_maintence_cost_month']))
			$this->request->data['SiteSurveys']['op_maintence_cost_month']	= $post_variables['op_maintence_cost_month'];
		if(isset($post_variables['project_id']))
			$this->request->data['SiteSurveys']['project_id_off']			= $post_variables['project_id'];
		if(isset($post_variables['offline_project_id']))
			$this->request->data['SiteSurveys']['project_id_off']			= $post_variables['offline_project_id'];
		if(isset($post_variables['proj_id']))
			$this->request->data['SiteSurveys']['project_id']				= $post_variables['proj_id'];
		
			
		// Site survey step 1
		if(isset($post_variables['building_name']))
			$this->request->data['SiteSurveys']['building_name']	= $post_variables['building_name'];
		if(isset($post_variables['surveyer_name']))
			$this->request->data['SiteSurveys']['surveyer_name']	= $post_variables['surveyer_name'];
		if(isset($post_variables['contact_name']))
			$this->request->data['SiteSurveys']['contact_name']		= $post_variables['contact_name'];
		if(isset($post_variables['designation']))
			$this->request->data['SiteSurveys']['designation']		= $post_variables['designation'];
		if(isset($post_variables['address1']))
			$this->request->data['SiteSurveys']['address1']			= $post_variables['address1'];
		if(isset($post_variables['address2']))
			$this->request->data['SiteSurveys']['address2']			= $post_variables['address2'];
		if(isset($post_variables['address3']))
			$this->request->data['SiteSurveys']['address3']			= $post_variables['address3'];
		if(isset($post_variables['mobile']))
			$this->request->data['SiteSurveys']['mobile']			= $post_variables['mobile'];
		if(isset($post_variables['landline']))
			$this->request->data['SiteSurveys']['landline']			= $post_variables['landline'];
		if(isset($post_variables['email_id']))
			$this->request->data['SiteSurveys']['email_id']			= $post_variables['email_id'];
		if(isset($post_variables['notes1']))	
			$this->request->data['SiteSurveys']['notes1']			= $post_variables['notes1'];
		if(isset($post_variables['site_lat']))	
			$this->request->data['SiteSurveys']['site_lat']			= $post_variables['site_lat'];
		if(isset($post_variables['site_log']))	
			$this->request->data['SiteSurveys']['site_log']			= $post_variables['site_log'];
		if(isset($post_variables['user_lat']))	
			$this->request->data['SiteSurveys']['user_lat']			= $post_variables['user_lat'];
		if(isset($post_variables['user_log']))	
			$this->request->data['SiteSurveys']['user_log']			= $post_variables['user_log'];
		
		// Site survey step 2
		if(isset($post_variables['roof_type']))
			$this->request->data['SiteSurveys']['roof_type']		= $post_variables['roof_type'];
		if(isset($post_variables['roof_strenght']))
			$this->request->data['SiteSurveys']['roof_strenght']	= $post_variables['roof_strenght'];
		if(isset($post_variables['overall']))
			$this->request->data['SiteSurveys']['overall']			= $post_variables['overall'];
		if(isset($post_variables['is_overall']))
			$this->request->data['SiteSurveys']['is_overall']		= $post_variables['is_overall'];
		if(isset($post_variables['shadow_free']))
			$this->request->data['SiteSurveys']['shadow_free']		= $post_variables['shadow_free'];
		if(isset($post_variables['is_shadow_free']))
			$this->request->data['SiteSurveys']['is_shadow_free']		= $post_variables['is_shadow_free'];
		if(isset($post_variables['roof_accessible']))
			$this->request->data['SiteSurveys']['roof_accessible']		= $post_variables['roof_accessible'];
		if(isset($post_variables['road_to_site']))
			$this->request->data['SiteSurveys']['road_to_site']			= $post_variables['road_to_site'];
		if(isset($post_variables['ladder_to_roof']))
			$this->request->data['SiteSurveys']['ladder_to_roof']		= $post_variables['ladder_to_roof'];
		if(isset($post_variables['age_of_building']))
			$this->request->data['SiteSurveys']['age_of_building']		= $post_variables['age_of_building'];
		if(isset($post_variables['azimuth']))
			$this->request->data['SiteSurveys']['azimuth']				= $post_variables['azimuth'];
		if(isset($post_variables['inclination_of_roof']))
			$this->request->data['SiteSurveys']['inclination_of_roof']		= $post_variables['inclination_of_roof'];
		if(isset($post_variables['object_on_roof']))
			$this->request->data['SiteSurveys']['object_on_roof']			= $post_variables['object_on_roof'];
		if(isset($post_variables['height_of_parapet']))
			$this->request->data['SiteSurveys']['height_of_parapet']		= $post_variables['height_of_parapet'];
		if(isset($post_variables['is_height_of_parapet']))
			$this->request->data['SiteSurveys']['is_height_of_parapet']		= $post_variables['is_height_of_parapet'];
		if(isset($post_variables['floor_below_tarrace']))
			$this->request->data['SiteSurveys']['floor_below_tarrace']		= $post_variables['floor_below_tarrace'];
		if(isset($post_variables['dc_cabel_distance']))
			$this->request->data['SiteSurveys']['dc_cabel_distance']		= $post_variables['dc_cabel_distance'];
		if(isset($post_variables['is_dc_cable_distance']))
			$this->request->data['SiteSurveys']['is_dc_cable_distance']		= $post_variables['is_dc_cable_distance'];
		if(isset($post_variables['notes2']))
			$this->request->data['SiteSurveys']['notes2']					= $post_variables['notes2'];
		if(isset($post_variables['shadow']))
			$this->request->data['SiteSurveys']['shadow']					= $post_variables['shadow'];

		// Site survey step 3
		
		
		if(isset($post_variables['voltage_pahse_level']))
			$this->request->data['SiteSurveys']['voltage_pahse_level']		= $post_variables['voltage_pahse_level'];
		if(isset($post_variables['measured_frequency']))
			$this->request->data['SiteSurveys']['measured_frequency']		= $post_variables['measured_frequency'];
		if(isset($post_variables['avg_diesel_consumption']))
			$this->request->data['SiteSurveys']['avg_diesel_consumption']	= $post_variables['avg_diesel_consumption'];
		if(isset($post_variables['critical_load']))
			$this->request->data['SiteSurveys']['critical_load']		= $post_variables['critical_load'];
		if(isset($post_variables['approx_power_consumed']))
			$this->request->data['SiteSurveys']['approx_power_consumed']= $post_variables['approx_power_consumed'];
		if(isset($post_variables['working_day_week']))
			$this->request->data['SiteSurveys']['working_day_week']		= $post_variables['working_day_week'];
		if(isset($post_variables['notes3']))
			$this->request->data['SiteSurveys']['notes3']				= $post_variables['notes3'];
		if(isset($post_variables['reading_details']))
			$this->request->data['SiteSurveys']['reading_details']				= serialize(json_decode($post_variables['reading_details'],true));
		if(isset($post_variables['genset_details']))
			$this->request->data['SiteSurveys']['genset_details']				= serialize(json_decode($post_variables['genset_details'],true));
		if(isset($post_variables['inverter_details']))
			$this->request->data['SiteSurveys']['inverter_details']				= serialize(json_decode($post_variables['inverter_details'],true));


		// Site survey step 4
		if(isset($post_variables['distribution_company']))
			$this->request->data['SiteSurveys']['distribution_company']	= $post_variables['distribution_company'];
		if(isset($post_variables['customer_no']))
			$this->request->data['SiteSurveys']['customer_no']			= $post_variables['customer_no'];
		if(isset($post_variables['meter_type']))
			$this->request->data['SiteSurveys']['meter_type']			= $post_variables['meter_type'];
		if(isset($post_variables['meter_accuracy']))
			$this->request->data['SiteSurveys']['meter_accuracy']		= $post_variables['meter_accuracy'];
		if(isset($post_variables['customer_type']))
			$this->request->data['SiteSurveys']['customer_type']		= $post_variables['customer_type'];
		if(isset($post_variables['sanctioned_load']))
			$this->request->data['SiteSurveys']['sanctioned_load']		= $post_variables['sanctioned_load'];
		if(isset($post_variables['is_snaction']))
			$this->request->data['SiteSurveys']['is_snaction']			= $post_variables['is_snaction'];
		if(isset($post_variables['contract_demand']))
			$this->request->data['SiteSurveys']['contract_demand']		= $post_variables['contract_demand'];
		if(isset($post_variables['is_contract']))
			$this->request->data['SiteSurveys']['is_contract']			= $post_variables['is_contract'];
		if(isset($post_variables['billing_cycle']))
			$this->request->data['SiteSurveys']['billing_cycle']		= $post_variables['billing_cycle'];
		if(isset($post_variables['fixcharges_upto']))
			$this->request->data['SiteSurveys']['fixcharges_upto']		= $post_variables['fixcharges_upto'];
		if(isset($post_variables['fixcharges_upto_rs']))
			$this->request->data['SiteSurveys']['fixcharges_upto_rs']	= $post_variables['fixcharges_upto_rs'];
		if(isset($post_variables['fxbetween1_from']))
			$this->request->data['SiteSurveys']['fxbetween1_from']		= $post_variables['fxbetween1_from'];
		if(isset($post_variables['fxbetween1_to']))
			$this->request->data['SiteSurveys']['fxbetween1_to']		= $post_variables['fxbetween1_to'];
		if(isset($post_variables['fxbetween1_to_rs']))
			$this->request->data['SiteSurveys']['fxbetween1_to_rs']		= $post_variables['fxbetween1_to_rs'];
		if(isset($post_variables['fxbetween2_from']))
			$this->request->data['SiteSurveys']['fxbetween2_from']		= $post_variables['fxbetween2_from'];
		if(isset($post_variables['fxbetween2_to']))
			$this->request->data['SiteSurveys']['fxbetween2_to']		= $post_variables['fxbetween2_to'];
		if(isset($post_variables['fxbetween2_to_rs']))
			$this->request->data['SiteSurveys']['fxbetween2_to_rs']		= $post_variables['fxbetween2_to_rs'];
		if(isset($post_variables['fxmorethen']))
			$this->request->data['SiteSurveys']['fxmorethen']			= $post_variables['fxmorethen'];
		if(isset($post_variables['fxmorethen_rs']))
			$this->request->data['SiteSurveys']['fxmorethen_rs']		= $post_variables['fxmorethen_rs'];
		if(isset($post_variables['eccharges_upto']))
			$this->request->data['SiteSurveys']['eccharges_upto']		= $post_variables['eccharges_upto'];
		if(isset($post_variables['eccharges_upto_rs']))
			$this->request->data['SiteSurveys']['eccharges_upto_rs']	= $post_variables['eccharges_upto_rs'];
		if(isset($post_variables['ecbetween1_from']))
			$this->request->data['SiteSurveys']['ecbetween1_from']		= $post_variables['ecbetween1_from'];
		if(isset($post_variables['ecbetween1_to']))
			$this->request->data['SiteSurveys']['ecbetween1_to']		= $post_variables['ecbetween1_to'];
		if(isset($post_variables['ecbetween1_to_rs']))
			$this->request->data['SiteSurveys']['ecbetween1_to_rs']		= $post_variables['ecbetween1_to_rs'];
		if(isset($post_variables['ecbetween2_from']))
			$this->request->data['SiteSurveys']['ecbetween2_from']		= $post_variables['ecbetween2_from'];
		if(isset($post_variables['ecbetween2_to']))
			$this->request->data['SiteSurveys']['ecbetween2_to']		= $post_variables['ecbetween2_to'];
		if(isset($post_variables['ecbetween2_to_rs']))
			$this->request->data['SiteSurveys']['ecbetween2_to_rs']		= $post_variables['ecbetween2_to_rs'];
		if(isset($post_variables['ecmorethen']))
			$this->request->data['SiteSurveys']['ecmorethen']			= $post_variables['ecmorethen'];
		if(isset($post_variables['ecmorethen_rs']))
			$this->request->data['SiteSurveys']['ecmorethen_rs']		= $post_variables['ecmorethen_rs'];
		if(isset($post_variables['fuel_charges']))
			$this->request->data['SiteSurveys']['fuel_charges']			= $post_variables['fuel_charges'];
		if(isset($post_variables['electricity_duty']))
			$this->request->data['SiteSurveys']['electricity_duty']		= $post_variables['electricity_duty'];
		if(isset($post_variables['other_surcharges2']))
			$this->request->data['SiteSurveys']['other_surcharges2']	= $post_variables['other_surcharges2'];
		if(isset($post_variables['other_surcharges1']))
			$this->request->data['SiteSurveys']['other_surcharges1']	= $post_variables['other_surcharges1'];
		if(isset($post_variables['other_rebate']))
			$this->request->data['SiteSurveys']['other_rebate']			= $post_variables['other_rebate'];
		if(isset($post_variables['notes4']))
			$this->request->data['SiteSurveys']['notes4']				= $post_variables['notes4'];
		if(isset($post_variables['month_details']))
			$this->request->data['SiteSurveys']['month_details']				= serialize(json_decode($post_variables['month_details'],true));
		if(isset($post_variables['survey_step']))
			$this->request->data['SiteSurveys']['survey_step']			= $post_variables['survey_step'];
		if(isset($post_variables['image_type']))
			$this->request->data['SiteSurveys']['image_type']			= $post_variables['image_type'];

		
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
		$option['colName']  = array('id','name','email','mobile','action');
		
		$this->SetSortingVars('Customers',$option);
		$arrCondition		= $this->_generateCustomerSearchCondition();
		
		/*$arrCondition['between'] = ["Users.lastlogin","2015-10-01 01:00:00","2015-10-02 23:59:59"];*/
		$this->paginate		= array('conditions' => $arrCondition,
									'fields' => array('id','name','email','mobile','status'),
									'order'=>array($this->SortBy=>$this->Direction),
									'page'=> $this->CurrentPage,
									'limit' => PAGE_RECORD_LIMIT);
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
								$temparr['action'].= $this->Userright->linkDisableAdminuser(constant('WEB_URL').constant('ADMIN_PATH').'customers/disable/'.encode($val['id']),'<i class="fa fa-circle-o"></i>','','actionRecord',' title="De-Activate"')."&nbsp;";
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
			$this->Flash->set('Customer has been De-Activated.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			
			return $this->redirect(array('action'=>'index'));
			exit;
		}
		else
		{
			$this->Flash->set('Customer De-Activation Error! Contact Administrator.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
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
		$user_arr = $this->Customers->get($id);
		$user_arr->status = 1;
		if($this->Customers->save($user_arr))
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
	 * _generateInstallerSearchCondition
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use generate installer search condition.
	 *
	 * Author : Khushal Bhalsod
	 */
	private function _generateInstallerSearchCondition($id=null)
	{
		$arrCondition	= array();		
		if(!empty($id)) $this->request->data['Installers']['id'] = $id;
		
		if(isset($this->request->data) && count($this->request->data)>0) {
            if(isset($this->request->data['Installers']['id']) && trim($this->request->data['Installers']['id'])!='') {
                $strID = trim($this->request->data['Installers']['id'],',');
                $arrCondition['Installers.id'] = $this->request->data['Installers']['id'];
            }
			if(isset($this->request->data['Installers']['status']) && $this->request->data['Installers']['status']!='') {
                $arrCondition['Installers.status'] = $this->request->data['Installers']['status'];
            }else{
				$arrCondition['Installers.status'] = '1';
			}
			if(isset($this->request->data['Installers']['installer_name']) && $this->request->data['Installers']['installer_name']!='') {
                $arrCondition['Installers.installer_name LIKE'] = '%'.$this->request->data['Installers']['installer_name'].'%';
            }
			if(isset($this->request->data['Installers']['email']) && $this->request->data['Installers']['email']!='') {
                $arrCondition['Installers.email LIKE'] = '%'.$this->request->data['Installers']['email'].'%';
            }
			if(isset($this->request->data['Installers']['mobile']) && $this->request->data['Installers']['mobile']!='') {
                $arrCondition['Installers.mobile LIKE'] = '%'.$this->request->data['Installers']['mobile'].'%';
            }
            if(isset($this->request->data['Installers']['address']) && $this->request->data['Installers']['address']!='') {
                $arrCondition['Installers.address LIKE'] = '%'.$this->request->data['Installers']['address'].'%';
            }
			if(isset($this->request->data['Installers']['state']) && $this->request->data['Installers']['state']!='') {
                $arrCondition['Installers.state LIKE'] = '%'.$this->request->data['Installers']['state'].'%';
            }			
			if(isset($this->request->data['Installers']['search_date']) && $this->request->data['Installers']['search_date']!='') {
                if($this->request->data['Installers']['search_period'] == 1 || $this->request->data['Installers']['search_period'] == 2) {
                	$arrSearchPara	= $this->Installers->setSearchDateParameter($this->request->data['Installers']['search_period'],$this->modelClass);
                	$this->request->data[$this->modelClass] = array_merge($this->request->data[$this->modelClass],$arrSearchPara[$this->modelClass]);
                    $this->dateDisabled						= true;
                }
                $arrperiodcondi = $this->Installers->findConditionByPeriod( $this->request->data['Installers']['search_date'],
																		$this->request->data['Installers']['search_period'],
																		$this->request->data['Installers']['DateFrom'],
																		$this->request->data['Installers']['DateTo'],
																		$this->Session->read('Installers.timezone'));
               	if(!empty($arrperiodcondi)){
                	$arrCondition['between'] = $arrperiodcondi['between'];
                }
            }
		}
		return $arrCondition;
	}
	/**
	 *
	 * getProjectEstimation
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 *
	 */
	public function setsitesurveydetail()
	{
		$solarPenalArea = 0;
		$this->autoRender 	= false;		
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		/* Create or update project data here. */
		$projectsData 	= $this->Projects->find('all',array('conditions'=>array('Projects.id'=>$this->request->data['SiteSurveys']['project_id'])))->first();
		
		if(!empty($projectsData)) {
			$this->request->data['SiteSurveys']['latitude'] =(!empty($projectsData['latitude'])?$projectsData['latitude']:'');
			$this->request->data['SiteSurveys']['longitude'] =(!empty($projectsData['longitude'])?$projectsData['longitude']:'');
			$this->request->data['SiteSurveys']['avg_energy_consumption'] =(!empty($projectsData['avg_energy_consumption'])?$projectsData['avg_energy_consumption']:'');
			$this->request->data['SiteSurveys']['customer_type'] =(!empty($projectsData['customer_type'])?$projectsData['customer_type']:'');
			$this->request->data['SiteSurveys']['backup_type'] =(!empty($projectsData['backup_type'])?$projectsData['backup_type']:0);
			$this->request->data['SiteSurveys']['avg_monthly_bill'] =(!empty($projectsData['avg_monthly_bill'])?$projectsData['avg_monthly_bill']:'');
			$this->request->data['SiteSurveys']['usage_hours'] =(!empty($projectsData['usage_hours'])?$projectsData['usage_hours']:0);
			$this->request->data['SiteSurveys']['area'] =(!empty($projectsData['area'])?$projectsData['area']:0);
			$bill 			= $projectsData['bill'];
			$energy_con 	= $projectsData['energy_con'];
			$area_type 		= $projectsData['area_type'];
			$this->request->data['SiteSurveys']['address']					= (!empty($projectsData['address'])?$projectsData['address']:'');
			$this->request->data['SiteSurveys']['city']						= (!empty($projectsData['city'])?$projectsData['city']:'');
			$this->request->data['SiteSurveys']['state']					= (!empty($projectsData['state'])?$projectsData['state']:'');
			$this->request->data['SiteSurveys']['state_short_name']		= (!empty($projectsData['state_short_name'])?$projectsData['state_short_name']:'');
			$this->request->data['SiteSurveys']['country']					= (!empty($projectsData['country'])?$projectsData['country']:'');
			$this->request->data['SiteSurveys']['pincode']					= (!empty($projectsData['pincode'])?$projectsData['pincode']:'');
			$this->request->data['SiteSurveys']['landmark']				= (!empty($projectsData['landmark'])?$projectsData['landmark']:'');
		} else {
			$locationdata = $this->GetLocationByLatLong($this->request->data['SiteSurveys']['latitude'],$this->request->data['SiteSurveys']['longitude']);	
			$this->request->data['SiteSurveys']['address']					= (isset($locationdata['address'])?$locationdata['address']:'');
			$this->request->data['SiteSurveys']['city']					= (isset($locationdata['city'])?$locationdata['city']:'');
			$this->request->data['SiteSurveys']['state']					= (isset($locationdata['state'])?$locationdata['state']:'');
			$this->request->data['SiteSurveys']['state_short_name']		= (isset($locationdata['state_short_name'])?$locationdata['state_short_name']:'');
			$this->request->data['SiteSurveys']['country']					= (isset($locationdata['country'])?$locationdata['country']:'');
			$this->request->data['SiteSurveys']['pincode']					= (isset($locationdata['postal_code'])?$locationdata['postal_code']:'');
			$this->request->data['SiteSurveys']['landmark']					= (isset($locationdata['landmark'])?$locationdata['landmark']:'');
		}
		if($area_type == $this->Projects->AREA_TYPE_FOOT) {
			$solarPenalArea	= $this->Projects->calculatePvInFoot($this->request->data['SiteSurveys']['area']);
		} elseif($area_type == $this->Projects->AREA_TYPE_METER) { 
			$solarPenalArea	= $this->Projects->calculatePvInMeter($this->request->data['SiteSurveys']['area']);	
		}
		$solarPvInstall 	= ceil($solarPenalArea/12);
		$solarRediationData	= $this->Projects->getSolarRediation($this->request->data['SiteSurveys']['latitude'],$this->request->data['SiteSurveys']['longitude']);
		$annualTotalRad		= ($solarRediationData['ann_glo']*365);
		/*$averageEnrgyGenInDay 	= (((($solarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);*/
		
		$contractLoad 			= round(((($this->request->data['SiteSurveys']['avg_energy_consumption']*12)/((24*365*LOAD_FECTORE)/100))));
		$capacityAcualEnrgyCon	= round(((($this->request->data['SiteSurveys']['avg_energy_consumption']*12)/$annualTotalRad)));
		
		$recommendedSolarPvInstall = min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);
		$monthChartDataArr		= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		
		$capitalCost 			= $this->Projects->calculatecapitalcost($recommendedSolarPvInstall,$this->request->data['SiteSurveys']['state'],$this->request->data['SiteSurveys']['customer_type']);
		$capitalCostsubsidy 	= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$this->request->data['SiteSurveys']['state'],$this->request->data['SiteSurveys']['customer_type']);
		
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInYear	= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);
		
		/* Calculate saving */
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		$energy_con =(empty($energy_con)?1:$energy_con);
		$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
		
		/* Calculate saving */
		$cost_solar				= 0.0;	
		$this->request->data['SiteSurveys']['avg_energy_consumption'] = (empty($this->request->data['SiteSurveys']['avg_energy_consumption']?1:$this->request->data['SiteSurveys']['avg_energy_consumption']));
		$unitRate				= (($this->request->data['SiteSurveys']['avg_monthly_bill']/$this->request->data['SiteSurveys']['avg_energy_consumption'])-0.5);
		$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,$this->request->data['SiteSurveys']['backup_type'],$this->request->data['SiteSurveys']['usage_hours']);
		
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->Projects->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$this->request->data['SiteSurveys']['avg_monthly_bill'],$capitalCost);	
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->Projects->genrateApiChartData($fromPvSystem, $monthChartDataArr);	
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
		
		$this->request->data['SiteSurveys']['contract_load']			= (isset($contractLoad)?$contractLoad:0);
		$this->request->data['SiteSurveys']['cost_solar']				= (isset($cost_solar)?$cost_solar:0);
		$this->request->data['SiteSurveys']['solar_ratio']				= (isset($solar_ratio)?$solar_ratio:0);
		$this->request->data['SiteSurveys']['estimated_saving_month']	= (isset($monthly_saving)?$monthly_saving:0);
		$this->request->data['SiteSurveys']['payback']					= (isset($payBack)?$payBack:0);
		$this->request->data['SiteSurveys']['estimated_cost']			= (isset($capitalCost)?$capitalCost:0);
		$this->request->data['SiteSurveys']['estimated_cost_subsidy']	= (isset($capitalCostsubsidy)?$capitalCostsubsidy:0);
		$this->request->data['SiteSurveys']['avg_generate']				= (isset($averageEnrgyGenInMonth)?$averageEnrgyGenInMonth:0);
		$this->request->data['SiteSurveys']['recommended_capacity']	= (isset($recommendedSolarPvInstall)?$recommendedSolarPvInstall:0);
		$this->request->data['SiteSurveys']['maximum_capacity']		= (isset($highRecommendedSolarPvInstall)?$highRecommendedSolarPvInstall:0);
		
		$rec_capacity = 0;
		$rec_capacity = ($recommendedSolarPvInstall > 10)?floor($recommendedSolarPvInstall):number_format($recommendedSolarPvInstall,1);
		
		$dataProject = array();
		$SiteSurveysdata	= $this->SiteSurveys->find('all', array('conditions'=>array('SiteSurveys.id'=>$this->request->data['SiteSurveys']['id'])))->first();
		if(!empty($this->request->data['SiteSurveys']['id']) && !empty($SiteSurveysdata)) {
				$siteSurveyid 	= (isset($SiteSurveysdata['id'])?$SiteSurveysdata['id']:0);
				$projectsData 	= $this->SiteSurveys->get($siteSurveyid);
			$projectsEntity = $this->SiteSurveys->patchEntity($projectsData,$this->request->data());
			$projectsEntity->project_id 		= $this->request->data['SiteSurveys']['project_id'];	
			$projectsEntity->installer_id 		= $cus_id;	
			$projectsEntity->modified 		= $this->NOW();	
			$projectsEntity->modified_by 	= $this->ApiToken->customer_id;
		}else{
			$projectsEntity = $this->SiteSurveys->newEntity($this->request->data['SiteSurveys']);
			$projectsEntity->created 		= $this->NOW();
			$projectsEntity->created_by 	= $this->ApiToken->customer_id;
			$projectsEntity->project_id 		= $this->request->data['SiteSurveys']['project_id'];	
			$projectsEntity->installer_id 		= $cus_id;	
		}	
		/* if(round($rec_capacity)==0)
		{
			$status				= 'error';
			$error				= 'Please enter valid estimation details like Rooftop Area, Monthly Bill and Unit Consumed.';
			$this->ApiToken->SetAPIResponse('msg', $error);	
			
		}else  */
		if ($this->SiteSurveys->save($projectsEntity)) {
			$dataProject['CustomerProjects']['customer_id']	= $this->ApiToken->customer_id;
			$dataProject['CustomerProjects']['project_id']	= $projectsEntity->id;

			$status		= 'ok';
			$message	= array(); 
			//$messege 	= array_merge($messege,$chart);
			$message['proj_id'] 		= $projectsEntity->id;
			$message['project_name'] 		= $projectsEntity->name;
			$message['customer_type'] 	= $projectsEntity->customer_type;
			$message['cost_solar']		= $cost_solar;
			$message['saving_month']	= _FormatGroupNumberV2($monthly_saving);
			$message['capacity']		= $rec_capacity;
			$message['highcapacity']	= round($highRecommendedSolarPvInstall);
			$message['est_cost']		= $capitalCost;
			$message['est_cost_subsidy']= round($capitalCostsubsidy,2);
			$message['avg_gen']			= round($averageEnrgyGenInMonth,2);
			$message['payback']			= round($payBack,2);
			$message['solar_ratio'] 	= ($solar_ratio > 100)?'100':round($solar_ratio);
			$message['area_type'] 		= $projectsEntity->area_type;
			$message['rooftop_area'] 			= $projectsEntity->area;
			$message['shadow'] 			= $projectsEntity->shadow;
			$message['sanction_load'] 	= $projectsEntity->contract_load;
			$message['avg_monthly_unit_consumed'] = $projectsEntity->avg_energy_consumption;
			$message['backup_type']		 = $projectsEntity->backup_type;
			$message['backup_usage'] 	 = $projectsEntity->backup_usage;
			$message['backup_comment'] 		 = '';
			$message['rooftop_comment'] 		 = $projectsEntity->comments;
			$this->ApiToken->SetAPIResponse('result', $message);
		} else {
			$status				= 'error';
			$error				= 'Please try after some time';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo stripslashes($this->ApiToken->GenerateAPIResponse());
	}

	/**
     *
     * sitesurvay_step
     *
     * Behaviour : public
     *
     * @defination : Method is use for project survey (API function).
     *
     */
	public function sitesurvay_step()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$type 			= 'ok';
		$msg 			= '';
		$myobject 		= array(); 
		$result 		= (object) $myobject;

		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id  		= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$action 		= (isset($this->request->data['action'])?$this->request->data['action']:'');
		
		if(!empty($action)) {

			$params['customer_id'] 	= $cus_id;
			$params['post_data'] 	= $this->request->data;
			
			if($action == "ADD_PROJECT_SURVEY") {
				
				$response = $this->SiteSurveys->AddExistingProjectSiteSurveyData($params);
				$type 	= (!empty($response['status'])?"ok":"error");
				$msg 	= (!empty($response['msg'])?$response['msg']:'');
				$result = (!empty($response['result'])?$response['result']:$result);

			} elseif ($action == "ADD_INSTALLER_PROJECT") {
				
				$response 	= $this->AddInstallerProject($params);
				$type 		= (!empty($response['status'])?"ok":"error");
				$msg 		= (!empty($response['msg'])?$response['msg']:'');
				$result 	= (!empty($response['result'])?$response['result']:$result);

			} elseif ($action == "GET_PROJECT_SITE_SURVEY_DATA") { 

				$response 	= $this->SiteSurveys->GetProjectSiteSurveyData($params);
				$type 		= (!empty($response['status'])?"ok":"error");
				$result 	= (!empty($response['result'])?$response['result']:$result);

			} elseif ($action == "GET_SURVEYAR_DATA") {
				
				$params['customer_id'] 	= $this->ApiToken->customer_id;
				$response 	= $this->SiteSurveys->GetCustomerDetail($params);
				$type 		= (!empty($response['status'])?"ok":"error");
				$result 	= (!empty($response['result'])?$response['result']:$result);

			} else {
				$type 	= 'error';
				$msg 	= 'Invalid Request.';
			}

		} else {
			$type 	= 'error';
			$msg 	= 'Invalid Request.';
		}

		$this->ApiToken->SetAPIResponse('type', $type);
		$this->ApiToken->SetAPIResponse('msg', $msg);
		$this->ApiToken->SetAPIResponse('result', $result);

		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	
	public function uploadsurvayimage()
	{ 
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$proj_id 	= (isset($this->request->data['SiteSurveys']['project_id'])?$this->request->data['SiteSurveys']['project_id']:0);
		if(!empty($cus_id)) {
			/*Store installer project*/
			$type = (!empty($this->request->data['image_type'])?$this->request->data['image_type']:'site_photos');
			$imagePatchEntity 						= $this->SiteSurveysImages->newEntity($this->request->data);
			$imagePatchEntity->type 				= $type;
			$imagePatchEntity->building_id 			= (isset($this->request->data['building_id'])?$this->request->data['building_id']:1);
			$imagePatchEntity->project_id 			= $proj_id;
			$imagePatchEntity->installer_id 		= $cus_id;
			if(isset($this->request->data['imagepath']['name']) && !empty($this->request->data['imagepath']['name']))
			{
				$image_path = SITE_SURVEY_PATH.$type.'/';
				if(!file_exists(SITE_SURVEY_PATH.$type))
					mkdir(SITE_SURVEY_PATH.$type, 0755,true);
				$file_name = $this->file_upload($image_path,$this->request->data['imagepath'],false,65,65,$image_path);
				$imagePatchEntity->photo = $file_name;
			//print_r($imagePatchEntity);
			$this->SiteSurveysImages->save($imagePatchEntity);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('msg', 'Image Uploaded Successfully.');
		} else {
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

	function technocommercialsurvey()
	{
		$this->autoRender 	= false;		
		$this->SetVariables($this->request->data);
		$cus_id	= $this->ApiToken->customer_id;	
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$survey_id 	= (isset($this->request->data['survey_id'])?$this->request->data['survey_id']:0);
		if(!empty($cus_id) && $survey_id) {
			$projectData 	= $this->SiteSurveys->get($survey_id);
			$this->request->data['SiteSurveys']['latitude']		= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$this->request->data['SiteSurveys']['longitude']		= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$this->request->data['SiteSurveys']['customer_type']	= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['SiteSurveys']['area']				= (isset($projectData['area'])?$projectData['area']:0);
			$this->request->data['SiteSurveys']['area_type'] 		= (isset($projectData['area_type'])?$projectData['area_type']:0);
			$this->request->data['SiteSurveys']['avg_monthly_bill'] = (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['SiteSurveys']['backup_type']		= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['SiteSurveys']['usage_hours']		= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['SiteSurveys']['usage_hours']		= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['SiteSurveys']['energy_con']		= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['SiteSurveys']['estimated_kwh_year'] = (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['SiteSurveys']['avg_energy_consumption'] = (isset($projectData['avg_energy_consumption'])?$projectData['avg_energy_consumption']:0);
			$this->request->data['SiteSurveys']['state'] = (isset($projectData['state'])?$projectData['state']:0);
				
				
			if($this->request->data['SiteSurveys']['area_type'] == $this->Projects->AREA_TYPE_FOOT) {
				$solarPenalArea	= $this->Projects->calculatePvInFoot($this->request->data['SiteSurveys']['area']);
			} elseif($this->request->data['SiteSurveys']['area_type'] == $this->Projects->AREA_TYPE_METER) { 
				$solarPenalArea	= $this->Projects->calculatePvInMeter($this->request->data['SiteSurveys']['area']);	
			}
			
			$solarPvInstall 	= ceil($solarPenalArea/12);
			$solarRediationData	= $this->Projects->getSolarRediation($this->request->data['SiteSurveys']['latitude'],$this->request->data['SiteSurveys']['longitude']);
			$annualTotalRad		= ($solarRediationData['ann_glo']*365);
			/*$averageEnrgyGenInDay 	= (((($solarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);*/
			
			$contractLoad 			= round(((($this->request->data['SiteSurveys']['avg_energy_consumption']*12)/((24*365*LOAD_FECTORE)/100))));
			$capacityAcualEnrgyCon	= round(((($this->request->data['SiteSurveys']['avg_energy_consumption']*12)/$annualTotalRad)));
			
			$recommendedSolarPvInstall = min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
			$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);
			$monthChartDataArr		= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
			
			$capitalCost 			= $this->Projects->calculatecapitalcost($recommendedSolarPvInstall,$this->request->data['SiteSurveys']['state'],$this->request->data['SiteSurveys']['customer_type']);
			$capitalCostsubsidy 	= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$this->request->data['SiteSurveys']['state'],$this->request->data['SiteSurveys']['customer_type']);
			$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
			$averageEnrgyGenInYear	= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);
			$bill = $this->request->data['SiteSurveys']['avg_monthly_bill'];
			$energy_con = $this->request->data['SiteSurveys']['avg_energy_consumption'];
			/* Calculate saving */
			$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
			$monthly_saving 		= ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
			
			/* Calculate saving */
			$cost_solar				= 0.0;	
			$unitRate				= (($this->request->data['SiteSurveys']['avg_monthly_bill']/$this->request->data['SiteSurveys']['avg_energy_consumption'])-0.5);
			$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,$this->request->data['SiteSurveys']['backup_type'],$this->request->data['SiteSurveys']['usage_hours']);
			
			$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
			$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
			$gross_solar_cost		= $this->Projects->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$this->request->data['SiteSurveys']['avg_monthly_bill'],$capitalCost);	
			$cost_solar				= $gross_solar_cost['net_cog'];
			//$chart					= $this->Projects->genrateApiChartData($fromPvSystem, $monthChartDataArr,false);
			$chart=array('yearChart'=>array(),'monthChart'=>array());
			$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
			$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);			
		
			$status		= 'ok';
			$message	= array(); 
			$message 	= array_merge($message,$chart);
			$message['proj_id'] 		= $projectData['project_id'];
			$message['is_residential'] 	= ($this->request->data['SiteSurveys']['customer_type'] == '3001')?'1':'0';
			$message['cost_solar']		= $cost_solar;
			$message['saving_month']	= _FormatGroupNumberV2($monthly_saving);
			$message['capacity']		= $recommendedSolarPvInstall;
			$message['highcapacity']	= round($highRecommendedSolarPvInstall);
			$message['est_cost']		= $capitalCost;
			$message['est_cost_subsidy']= round($capitalCostsubsidy,2);
			$message['avg_gen']			= round($averageEnrgyGenInMonth,2);
			$message['payback']			= round($payBack,2);
			$message['solar_ratio'] 	= ($solar_ratio > 100)?'100':round($solar_ratio);
			$message['area_type'] 		= $this->request->data['SiteSurveys']['area_type'];
			$message['area'] 			= $this->request->data['SiteSurveys']['area'];
			//$message['shadow'] 			= $this->request->data['SiteSurveys']['shadow'];
			$message['contract_load'] 	= $contractLoad;
			$message['avg_energy_consumption'] = $this->request->data['SiteSurveys']['avg_energy_consumption'];
			$this->ApiToken->SetAPIResponse('result', $message);
		                                                                                                                                                                                                                           } else {
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo stripslashes($this->ApiToken->GenerateAPIResponse());
	}

	/**
     *
     * getsurveydetail
     *
     * Behaviour : public
     *
     * @defination : Method is use for get project survey data.
     *
     */
	public function getsurveydetail()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id  		= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$project_id 	= (isset($this->request->data['SiteSurveys']['project_id'])?$this->request->data['SiteSurveys']['project_id']:0);
		$building_id 	= (isset($this->request->data['building_id'])?$this->request->data['building_id']:1);

		$buildingList 	= $this->SiteSurveys->find('all')->select(['building_id','building_name'])->where(['project_id'=>$project_id,'installer_id'=>$cus_id])->toArray();	
		$surveyData 	= $this->SiteSurveys->find("all",array("conditions"=>array("SiteSurveys.project_id"=>$project_id,"SiteSurveys.installer_id"=>$cus_id,"SiteSurveys.building_id"=>$building_id)))->first();
		$imageData 		= $this->SiteSurveysImages->find("all",array("conditions"=>array("project_id"=>$project_id,"installer_id"=>$cus_id,"building_id"=>$building_id)))->toArray();
		
		if(!empty($surveyData)) {

			$projectArr 	= $this->Projects->get($project_id);
			$projectData 	= $this->SiteSurveys->get($surveyData['id']);


			$arr_month       = unserialize($projectData['month_details']);
            $arr_all_month   = $arr_month['ElectricityBillDetails'];

            $sum_power_con   = 0;
            $sum_bill_amount = 0;
            $total_val       = 0;
            for($i=0;$i<=11;$i++)
            {        
	            $str_year          = '';
	            $str_power_consume = '';
	            $str_bill_amount   = '';
	            if(strtolower($arr_all_month[$i]['year'])!='year' && !empty($arr_all_month[$i]['year']))
	            {
	                $str_year          = $arr_all_month[$i]['year'];
	                $str_power_consume = $arr_all_month[$i]['power_consume'];
	                $str_bill_amount   = $arr_all_month[$i]['bill_amount'];
	                $sum_power_con     = $sum_power_con+$arr_all_month[$i]['power_consume'];
	                $sum_bill_amount   = $sum_bill_amount+$arr_all_month[$i]['bill_amount'];
	                $total_val++;
	            }              
            }
	        $avg_pow_con  = 0;
	        $avg_bill_amt = 0;
	        if($total_val>0)
	        {
	            $avg_pow_con  = ($sum_power_con/$total_val);
	            $avg_bill_amt = ($sum_bill_amount/$total_val);
	        }
	        $projectData['avg_monthly_bill'] = $avg_bill_amt;
	        $projectData['estimated_kwh_year'] = $avg_pow_con*12;
	        $projectData['avg_energy_consumption'] = $avg_pow_con;
			$this->request->data['SiteSurveys']['latitude']			= (isset($projectArr['latitude'])?$projectArr['latitude']:0);
			$this->request->data['SiteSurveys']['longitude']		= (isset($projectArr['longitude'])?$projectArr['longitude']:0);
			$this->request->data['SiteSurveys']['customer_type']	= (isset($projectData['customer_type'])?$projectData['customer_type']:0);
			$this->request->data['SiteSurveys']['overall']			= (isset($projectData['overall'])?$projectData['overall']:0);
			$this->request->data['SiteSurveys']['is_overall'] 		= (isset($projectData['is_overall'])?$projectData['is_overall']:0);
			$this->request->data['SiteSurveys']['avg_monthly_bill'] = (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0);
			$this->request->data['SiteSurveys']['backup_type']		= (isset($projectData['backup_type'])?$projectData['backup_type']:0);
			$this->request->data['SiteSurveys']['usage_hours']		= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['SiteSurveys']['usage_hours']		= (isset($projectData['usage_hours'])?$projectData['usage_hours']:0);
			$this->request->data['SiteSurveys']['energy_con']		= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['SiteSurveys']['estimated_kwh_year'] 		= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$this->request->data['SiteSurveys']['avg_energy_consumption'] 	= (isset($projectData['avg_energy_consumption'])?$projectData['avg_energy_consumption']:0);
			$this->request->data['SiteSurveys']['state'] 					= (isset($projectArr['state'])?$projectArr['state']:"");

			if (empty($projectData['state']))
			{
				$locationdata = $this->GetLocationByLatLong($projectData['site_lat'],$projectData['site_log']);
				if (isset($locationdata['state']) && !empty($locationdata['state'])) {
					$this->request->data['SiteSurveys']['state'] = $locationdata['state'];
				}
			}

			$solarPenalArea = 0;	
			if($this->request->data['SiteSurveys']['is_overall'] == $this->Projects->AREA_TYPE_FOOT) {
				$solarPenalArea	= $this->Projects->calculatePvInFoot($this->request->data['SiteSurveys']['overall']);
			} elseif($this->request->data['SiteSurveys']['is_overall'] == $this->Projects->AREA_TYPE_METER) { 
				$solarPenalArea	= $this->Projects->calculatePvInMeter($this->request->data['SiteSurveys']['overall']);	
			}			

			$solarPvInstall 	= (!empty($solarPenalArea)?ceil($solarPenalArea/12):0);
			$solarRediationData	= $this->Projects->getSolarRediation($this->request->data['SiteSurveys']['latitude'],$this->request->data['SiteSurveys']['longitude']);
			$annualTotalRad		= ($solarRediationData['ann_glo']*365);
			/*$averageEnrgyGenInDay 	= (((($solarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);*/
			
			$contractLoad 			= round(((($this->request->data['SiteSurveys']['avg_energy_consumption']*12)/((24*365*LOAD_FECTORE)/100))));
		
			$capacityAcualEnrgyCon	= round(((($this->request->data['SiteSurveys']['avg_energy_consumption']*12)/$annualTotalRad)));
			
			$recommendedSolarPvInstall 	= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);

			$averageEnrgyGenInDay 		= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100)*1.1);
			$monthChartDataArr			= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
				
			$capitalCost 			= $this->Projects->calculatecapitalcost($recommendedSolarPvInstall,$this->request->data['SiteSurveys']['state'],$this->request->data['SiteSurveys']['customer_type']);

			$capitalCostsubsidy 	= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$this->request->data['SiteSurveys']['state'],$this->request->data['SiteSurveys']['customer_type']);
			
			$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
			$averageEnrgyGenInYear			= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100)*1.1);
			
			$bill 		= $this->request->data['SiteSurveys']['avg_monthly_bill'];
			$energy_con = $this->request->data['SiteSurveys']['avg_energy_consumption'];
			
			/* Calculate saving */
			$montly_pv_generation = ($averageEnrgyGenInDay * 30);
			$monthly_saving = 0;
			$saving_month 	= 0;
			if(!empty($energy_con)){
				$monthly_saving = ($bill - ($energy_con - $montly_pv_generation) * (($bill/$energy_con)-0.5)); 
			}
			
			/* Calculate saving */
			$cost_solar	= 0.0;	
			$unitRate 	= 0;
			if(!empty($this->request->data['SiteSurveys']['avg_energy_consumption'])){
				$unitRate			= (($this->request->data['SiteSurveys']['avg_monthly_bill']/$this->request->data['SiteSurveys']['avg_energy_consumption'])-0.5);
			}
			
			$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,($capitalCostsubsidy/100000),$this->request->data['SiteSurveys']['backup_type'],$this->request->data['SiteSurveys']['usage_hours']);

			$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
			$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
			$gross_solar_cost		= $this->Projects->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$this->request->data['SiteSurveys']['avg_monthly_bill'],$capitalCost);	
			$cost_solar				= $gross_solar_cost['net_cog'];
			$chart					= $this->Projects->genrateApiChartData($fromPvSystem, $monthChartDataArr);	
			$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
			$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
			
			$status								= 'ok';
			$message							= array(); 
			$solar_ratio 						= 0;
			$message['proj_id'] 				= $projectData['project_id'];
			$message['building_id'] 			= $projectData['building_id'];
			$message['is_residential'] 			= ($this->request->data['SiteSurveys']['customer_type'] == '3001')?'1':'0';
			$message['cost_solar']				= $cost_solar;
			$message['saving_month']			= _FormatGroupNumberV2($monthly_saving);
			$message['capacity']				= $recommendedSolarPvInstall;
			$message['highcapacity']			= round($highRecommendedSolarPvInstall);
			$message['est_cost']				= $capitalCost;
			$message['est_cost_subsidy'] 		= round($capitalCostsubsidy,2);
			$message['avg_gen']					= round($averageEnrgyGenInMonth,2);
			$message['payback']					= round($payBack,2);
			$message['solar_ratio'] 			= ($solar_ratio > 100)?'100':round($solar_ratio);
			//$message['shadow'] 				= $this->request->data['SiteSurveys']['shadow'];
			$message['contract_load'] 			= $contractLoad;
			$message['avg_energy_consumption'] 	= $this->request->data['SiteSurveys']['avg_energy_consumption'];
			
			$this->request->data['SiteSurveys']['capitalCost'] 						= $capitalCost;
			$this->request->data['SiteSurveys']['cost_solar'] 						= $cost_solar;
			$this->request->data['SiteSurveys']['saving_month'] 					= $saving_month;
			$this->request->data['SiteSurveys']['recommended_capacity'] 					= $recommendedSolarPvInstall;
			$this->request->data['SiteSurveys']['highRecommendedSolarPvInstall'] 	= round($highRecommendedSolarPvInstall);
			$this->request->data['SiteSurveys']['estimated_cost']					= (isset($capitalCost)?$capitalCost:0);
			$this->request->data['SiteSurveys']['estimated_cost_subsidy']			= (isset($capitalCostsubsidy)?$capitalCostsubsidy:0);
			$this->request->data['SiteSurveys']['avg_generate'] 					= round($averageEnrgyGenInMonth,2);
			$this->request->data['SiteSurveys']['payback'] 							= round($payBack,2);
			$this->request->data['SiteSurveys']['solar_ratio'] 						= ($solar_ratio > 100)?'100':round($solar_ratio);
			$this->request->data['SiteSurveys']['capitalCost'] 						= $cost_solar;
			$this->request->data['SiteSurveys']['contract_load'] 					= $contractLoad;
			
			
			//$customersEntity = $this->Customers->patchEntity($projectData,$this->request->data());
			//$this->SiteSurveys->save($customersEntity);
			$surveysData 	 = $this->SiteSurveys->get($surveyData['id']);
			$this->request->data['SiteSurveys']['id'] = $surveyData['id'];
			$sitesurveyEntity = $this->SiteSurveys->patchEntity($surveysData,$this->request->data);
			$this->SiteSurveys->save($sitesurveyEntity);
			$projectData 	 = $this->SiteSurveys->get($surveyData['id']);
			$message['proj_id'] 		= $projectArr['id'];
			$message['project_name'] 	= (isset($projectArr['name'])?$projectArr['name']:"");
			$message['customer_type'] 	= (isset($projectData['customer_type'])?$projectData['customer_type']:0); 
			$message['cost_solar']		= (isset($projectData['cost_solar'])?$projectData['cost_solar']:0); 
			$message['saving_month']	= (isset($projectData['saving_month'])?$projectData['saving_month']:0); 
			$message['capacity']		= (isset($projectData['recommended_capacity'])?$projectData['recommended_capacity']:0); 
			$message['highcapacity']	= (isset($projectData['highRecommendedSolarPvInstall'])?$projectData['highRecommendedSolarPvInstall']:0); 
			$message['bill']			= (isset($projectData['avg_monthly_bill'])?$projectData['avg_monthly_bill']:0); 
			$message['est_cost']		= (isset($capitalCost)?$capitalCost:0); 
			$message['est_cost_subsidy']= (isset($capitalCostsubsidy)?$capitalCostsubsidy:0);
			$message['avg_gen']			= (isset($projectData['avg_generate'])?$projectData['avg_generate']:0);
			$message['payback']			= (isset($projectData['payback'])?$projectData['payback']:0); 
			$message['solar_ratio'] 	= 100;
			$message['area_type'] 		= (isset($projectData['area_type'])?$projectData['area_type']:0); 
			$message['overall'] 		= (isset($projectData['overall'])?$projectData['overall']:0); 
			$message['shadow'] 			= (isset($projectData['shadow_free'])?$projectData['shadow_free']:0);
			$message['sanction_load'] 	= (isset($projectData['contract_load'])?$projectData['contract_load']:0);
			$message['avg_monthly_unit_consumed'] 	= (isset($projectData['avg_energy_consumption'])?$projectData['avg_energy_consumption']:0);
			$message['azimuth'] 		    		= (isset($projectData['azimuth'])?$projectData['azimuth']:0); 
			$message['inclination_roof']    		= (isset($projectData['inclination_of_roof'])?$projectData['inclination_of_roof']:0);
			$message['inverter_on_terrace'] 		= (isset($projectData['inverter_on_terrace'])?$projectData['inverter_on_terrace']:0);
			$message['inverter_kva'] 	 			= (isset($projectData['inverter_kva'])?$projectData['inverter_kva']:0); 
			$message['site_accessible'] 			= (isset($projectData['site_accessible'])?$projectData['site_accessible']:0); 
			$message['site_dimension'] 	 			= (isset($projectData['site_dimension'])?$projectData['site_dimension']:0); 
			$message['backup_type']		  			= (isset($projectData['backup_type'])?$projectData['backup_type']:0); 
			$message['backup_usage'] 	 			= (isset($projectData['backup_usage'])?$projectData['backup_usage']:0); 

			$message['roof_type']		 			= (isset($projectData['roof_type'])?$projectData['roof_type']:0);
			$message['roof_strenght']	 			= (isset($projectData['roof_strenght'])?$projectData['roof_strenght']:0);
			$message['place_inverter'] 		     	= (isset($projectData['place_inverter'])?$projectData['place_inverter']:"");
			$message['backup_comment'] 		     	= (isset($projectData['backup_comment'])?$projectData['backup_comment']:"");
			$message['place_battery'] 		     	= (isset($projectData['place_battery'])?$projectData['place_battery']:"");
			$message['place_acdb'] 		 		 	= (isset($projectData['place_acdb'])?$projectData['place_acdb']:"");
			$message['place_meter_point'] 		 	= (isset($projectData['place_meter_point'])?$projectData['place_meter_point']:"");
			$message['rooftop_comment'] 		 	= (isset($projectData['comments'])?$projectData['comments']:0);
			$message['site_lat'] 		 		 	= (isset($projectData['site_lat'])?$projectData['site_lat']:"");
			$message['site_log'] 		 		 	= (isset($projectData['site_log'])?$projectData['site_log']:"");
			$message['user_lat'] 		 		 	= (isset($projectData['user_lat'])?$projectData['user_lat']:"");
			$message['user_log'] 		 		 	= (isset($projectData['user_log'])?$projectData['user_log']:"");
			$message['buildingList'] 				= $buildingList;
			
			$photos = array();
			if(!empty($imageData)) {
				foreach($imageData as $key=>$imgData) {
					$photos[$key]['type'] =$imgData['type'];
					$photos[$key]['photo']=(!empty($imgData['photo'])? SITE_SURVEY_URL.$imgData['type'].'/'.$imgData['photo']:DEFAULT_IMAGES);
				}
			}

			$message['photos'] 	= $photos;
			$status				= 'ok';
			$this->ApiToken->SetAPIResponse('result',$message);
		} else {
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('type', $status);
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
     *
     * getProjectBuildingList
     *
     * Behaviour : public
     *
     * @defination : Method is use for get project building list.
     *
     */
	function getProjectBuildingList()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
 		$cus_id   		= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		
		$message 		= array(); 
		$project_id 	= (isset($this->request->data['SiteSurveys']['project_id'])?$this->request->data['SiteSurveys']['project_id']:0);
		
		if(!empty($project_id)) {
			$buildingList 	= $this->SiteSurveys->find('all')->select(['building_id','building_name'])->where(['project_id'=>$project_id,'installer_id'=>$cus_id])->toArray();	
			$message['buildingList'] 	= $buildingList;
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result',$message);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg','Invalid Request');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	function commissioningrequest()
	{
		$this->autoRender	= false;		
		$this->SetVariables($this->request->data);
		$cus_id			= $this->ApiToken->customer_id;	
		$project_id 	= (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   		= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$submit 		= (isset($this->request->data['submit'])?$this->request->data['submit']:0);
		$type 			= (isset($this->request->data['type'])?$this->request->data['type']:'');
		$message =array();
		if(!empty($cus_id) && $project_id) {
			$this->request->data['installer_id'] 		= $cus_id;
			$this->request->data['project_id'] 			= $project_id;
			$this->request->data['request_receive'] 	= '1';
			if(!empty($submit)){
				if($type != '')
				{
					$commission_type_data 				= $this->CommissioningData->find('all',array('conditions'=>array('project_id'=>$project_id,'type'=>$type)))->first();
					if(empty($commission_type_data)){
						$commdataEntity 				= $this->CommissioningData->newEntity($this->request->data);
					}
					else{
						$commdata 						= $this->CommissioningData->get($commission_type_data['id']);
						$commdataEntity 				= $this->CommissioningData->patchEntity($commdata,$this->request->data);
					}
					$commdataEntity->created 			= $this->NOW();
					$commdataEntity->request_receive 	= '1';
					$this->CommissioningData->save($commdataEntity);
					$commission_type_data 				= $this->CommissioningData->find('all',array('conditions'=>array('project_id'=>$project_id)))->toArray();
					$all_reestData['distribution_company']		= array('request_time'=>'','request_date'=>'');
					$all_reestData['state_energy_nodal_agency']	= array('request_time'=>'','request_date'=>'');
					$all_reestData['chief_electrical_inspector']= array('request_time'=>'','request_date'=>'');
					if(!empty($commission_type_data))
					{
						foreach($commission_type_data as $key=>$value) {
						$commission_type_data[$key]['request_time'] = date("h:i a", strtotime($value['created']));
						$commission_type_data[$key]['request_date'] = date("d/m/y", strtotime($value['created']));
						$all_reestData[$value['type']]['request_time']		= date("h:i a", strtotime($value['created']));
						$all_reestData[$value['type']]['request_date']		= date("d/m/y", strtotime($value['created']));
						}
					}
					$message['distribution_company']	= $all_reestData['distribution_company'];
					$message['state_energy_nodal']		= $all_reestData['state_energy_nodal_agency'];
					$message['chief_electrical_ins']	= $all_reestData['chief_electrical_inspector'];
				}
				else
				{
					$commisData = $this->Commissioning->find('all',array('conditions'=>array('Commissioning.installer_id'=>$cus_id,'Commissioning.project_id'=>$project_id)))->first();
					if(empty($commisData)){
						$commisioningEntity 			= $this->Commissioning->newEntity($this->request->data);
						$commisioningEntity->created 	= $this->NOW();
					}
					else{
						$instData 						= $this->Commissioning->get($commisData['id']);
						$commisioningEntity 			= $this->Commissioning->patchEntity($instData,$this->request->data);
					}
					$certificate_path 					= COMMISSIONING_DATA_PATH."certifictae_img/".$project_id.'/';
					if(!file_exists(COMMISSIONING_DATA_PATH."certifictae_img/".$project_id)){
						@mkdir(COMMISSIONING_DATA_PATH."certifictae_img/".$project_id, 0777,true);
					}
					if(isset($this->request->data['certificate_photo']) && !empty($this->request->data['certificate_photo'])) {
						$db_certificate_photo 					= $commisioningEntity->certificate_photo;
						if(file_exists($certificate_path.$db_certificate_photo)){
							@unlink($certificate_path.$db_certificate_photo);
							@unlink($certificate_path.'r_'.$db_certificate_photo);
						}
						$file_name 								= $this->file_upload($certificate_path,$this->request->data['certificate_photo'],true,65,65,$certificate_path,'cert');
						$commisioningEntity->certificate_photo	= $file_name;
					}
					$commisioningEntity->modified 				= $this->NOW();
					$this->Commissioning->save($commisioningEntity);
				}
				$status				= 'ok';
				$error				= 'Request received successfully!';
				$this->ApiToken->SetAPIResponse('msg', $error);
				
			}
			else
			{
				$commisData 			= $this->Commissioning->find('all',array('conditions'=>array('Commissioning.installer_id'=>$cus_id,'Commissioning.project_id'=>$project_id)))->first();
				$commission_type_data 	= $this->CommissioningData->find('all',array('conditions'=>array('project_id'=>$project_id)))->toArray();
				$condition = array('CustomerProjects.project_id' => $project_id);
				$projData 	= $this->CustomerProjects
									->find('all',array('fields'=>['Project.id','Project.name','Project.address','Project.city','Project.created','Project.recommended_capacity','Project.latitude','Project.longitude','Customers.name','Customers.mobile','Customers.email'],'join'=>[ 
										'Project' => [
								            'table' => 'projects',
								            'type' => 'INNER',
								            'conditions' => ['Project.id = CustomerProjects.project_id']
						            	],'Customers' => [
								            'table' => 'customers',
								            'type' => 'INNER',
								            'conditions' => ['Customers.id = CustomerProjects.customer_id']
						            	]],'conditions'=>$condition))->first();
				$arr_datadoclist 						= array();
				if(!empty($commisData))
				{
					$path 								= WWW_ROOT.COMMISSIONING_DATA_PATH."certifictae_img/".$project_id.'/'.$commisData['certificate_photo'];
					if (!empty($commisData['certificate_photo']) && file_exists($path)) 
					{
						$commisData['certificate_photo']= URL_HTTP.COMMISSIONING_DATA_PATH."certifictae_img/".$project_id.'/'.$commisData['certificate_photo'];
					}
					$CommissioningDocList 				= $this->CommissioningImage->find("all",['conditions'=>['commissioning_id'=>$commisData['id']]])->toArray();
					if(isset($CommissioningDocList) && !empty($CommissioningDocList)) 
					{
						foreach ($CommissioningDocList as $key => $value) 
	                    {
							$path 						= WWW_ROOT.COMMISSIONING_DATA_PATH."commissioning_img/".$project_id.'/'.$value['photo'];
							if (empty($value['photo']) || !file_exists($path)) continue;
							$arr_image_data 			= explode(".",$value['photo']);
							$arr_datadoclist[]  		= array('imageurl'=>URL_HTTP.COMMISSIONING_DATA_PATH."commissioning_img/".$project_id.'/'.$value['photo'],'mediaType'=>end($arr_image_data));	    
						}
					}
				}
				else
				{
					$commisData['id'] 				= '';
					$commisData['request_receive'] 	= '';
					$commisData['certificate_no'] 	= '';
					$commisData['certificate_photo']= '';
					$commisData['latitude'] 		= '';
					$commisData['longitude'] 		= '';
				}
				$all_reestData['distribution_company']		= array('request_time'=>'','request_date'=>'');
				$all_reestData['state_energy_nodal_agency']	= array('request_time'=>'','request_date'=>'');
				$all_reestData['chief_electrical_inspector']= array('request_time'=>'','request_date'=>'');
				if(!empty($commission_type_data))
				{
					foreach($commission_type_data as $key=>$value) {
					$commission_type_data[$key]['request_time'] = date("h:i a", strtotime($value['created']));
					$commission_type_data[$key]['request_date'] = date("d/m/y", strtotime($value['created']));
					$all_reestData[$value['type']]['request_time']		= date("h:i a", strtotime($value['created']));
					$all_reestData[$value['type']]['request_date']		= date("d/m/y", strtotime($value['created']));
					}
				}
				$data_number = $this->SiteSurveys->find('all')
                            ->where(['project_id' => $project_id])
                            ->first();
	            $mobile_num='';
	            if(!empty($data_number))
	            {
	                    $mobile_num= $data_number->mobile;
	            }
				$message 							= $commisData;
				$message['distribution_company']	= $all_reestData['distribution_company'];
				$message['state_energy_nodal']		= $all_reestData['state_energy_nodal_agency'];
				$message['chief_electrical_ins']	= $all_reestData['chief_electrical_inspector'];
				$message['request_data']			= $commission_type_data;
				$message['CommissDocList'] 			= $arr_datadoclist;
				//$message['path'] 			= $path;
				$message['installer_id'] 			= $cus_id;
				$message['project_id'] 				= $project_id;
				$message['location'] 				= $projData['Project']['address'];
				$message['capacity'] 				= $projData['Project']['recommended_capacity'];
				//$message['contact_number'] 	= $projData['Customers']['mobile'];
				$message['contact_number'] 			= $mobile_num;
				$status								= 'ok';
			}
		} else {
			$status				= 'error';
			$error				= 'Invalid Request';
			$this->ApiToken->SetAPIResponse('msg', $error);
		}
		$this->ApiToken->SetAPIResponse('result',$message);
		$this->ApiToken->SetAPIResponse('type', $status);
		echo stripslashes($this->ApiToken->GenerateAPIResponse());
	}

	/**
	 *
	 * getTechnoCommercialData
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to get project techno commercial data.
	 *
	 */
	public function getTechnoCommercialData()
	{
		$this->autoRender 	= false;
		$this->SetVariables($this->request->data);
		
		$cus_id			= $this->ApiToken->customer_id;
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   		= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$project_id 	= (isset($this->request->data['proj_id'])?$this->request->data['proj_id']:0);		
		
		if(!empty($project_id)) { 
			
			$param['customer_id'] 	= $cus_id;
			$param['project_id'] 	= $project_id;

			$projectData 	= $this->Projects->findById($project_id)->first();
			$commercialData = $this->SiteSurveys->GetTechnoCommercialData($param);

            $commercialData = (!empty($commercialData['result'])?$commercialData['result']:array());

			/*Techno Commercial chart generate*/
			$param['avg_monthly_bill'] 					= (isset($commercialData['avg_monthly_bill'])?$commercialData['avg_monthly_bill']:0);
			$param['avg_energy_consumption'] 			= (isset($commercialData['avg_energy_consumption'])?$commercialData['avg_energy_consumption']:0);
			$param['is_overall'] 						= (isset($commercialData['is_overall'])?$commercialData['is_overall']:0);
			$param['overall'] 							= (isset($commercialData['overall'])?$commercialData['overall']:0);
			$param['latitude'] 							= (isset($projectData['latitude'])?$projectData['latitude']:0);
			$param['longitude']							= (isset($projectData['longitude'])?$projectData['longitude']:0);
			$param['estimated_kwh_year'] 				= (isset($projectData['estimated_kwh_year'])?$projectData['estimated_kwh_year']:0);
			$param['customer_type'] 					= (isset($commercialData['customer_type'])?$commercialData['customer_type']:0);
			$param['state'] 							= (isset($projectData['state'])?$projectData['state']:'');
			$param['recommended_capacity'] 							= (isset($projectData['recommended_capacity'])?$projectData['recommended_capacity']:'');
			$param['backup_type'] 						= 0;
			$param['bill_detail'] 						= (isset($commercialData['bill_detail'])?$commercialData['bill_detail']:0);
			$param['energy_con_detail'] 				= (isset($commercialData['energy_con_detail'])?$commercialData['energy_con_detail']:0);
			$param['usage_hours'] 						= 0;
			$param['cost_electricty'] 					= (isset($commercialData['cost_electricty'])?$commercialData['cost_electricty']:0);

			$chartData = $this->GetProjectCommercialChartData($param);
			$commercialData['chart_data'] = (!empty($chartData)?$chartData:'');

            $commercialData['chart_data']['paybackChart'] =$this->GetProjectCommercialpaybackChartData($param);
			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('result', $commercialData);
		} else {
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid Request.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

	/**
	 *
	 * AddInstallerProject
	 *
	 * Behaviour : public
	 *
	 * @defination : Method used to create installer own project and add site survey. 
	 *
	 */
	public function AddInstallerProject($param = array())
	{
		$result         = array();
       	$customerId     = (!empty($param['customer_id'])?$param['customer_id']:0);
       	$requestData    = (isset($param['post_data'])?$param['post_data']:array());
       	$projectId      = (!empty($requestData['proj_id'])?$requestData['proj_id']:0);
	
       	/*create new project*/
       	if(empty($projectId)) { 
       		
       		$requestData['Projects'] = (!empty($requestData['SiteSurveys'])?$requestData['SiteSurveys']:array());
       		
       		$this->Projects->newEntity($requestData['Projects']);
       
       		$projectsEntity = $this->Projects->newEntity($requestData['Projects']);
			$projectsEntity->created 		= $this->NOW();
			$projectsEntity->created_by 	= (!empty($this->ApiToken->customer_id)?$this->ApiToken->customer_id:0);
			
			if($this->Projects->save($projectsEntity)){
				
				/*Save customer project data*/
				$dataProject = array();
				$dataProject['CustomerProjects']['customer_id']	= (!empty($this->ApiToken->customer_id)?$this->ApiToken->customer_id:0);
				$dataProject['CustomerProjects']['project_id']	= $projectsEntity->id;
				
				$customerProjectsEntity = $this->CustomerProjects->newEntity($dataProject);
				$customerProjectsEntity->created = $this->NOW();
				$this->CustomerProjects->save($customerProjectsEntity);	

				/*Save installer project lead data*/
				$dataProjectLead = array();
				$activation_codes = $this->Installers->generateInstallerActivationCodes();
				$dataProjectLead['InstallerProjects']['installer_id']	= $customerId;
				$dataProjectLead['InstallerProjects']['project_id']		= $projectsEntity->id;
				$dataProjectLead['InstallerProjects']['contact_code']	= $activation_codes;
				$dataProjectLead['InstallerProjects']['status']			= 4002;				
				
				$installerProjectEntity = $this->InstallerProjects->newEntity($dataProjectLead);
				$installerProjectEntity->created = $this->NOW();
				
				if($this->InstallerProjects->save($installerProjectEntity)) { 

					/*Send Message to customer*/ 
					$installerdata 	= $this->Installers->findById($customerId)->first();
					$installer_name = (!empty($installerdata['installer_name'])?$installerdata['installer_name']:'');
					$customer_id 	= $this->ApiToken->customer_id;
					$this->Customers->SendCustomerInstallerVerificationCode($installer_name,$customer_id,$activation_codes);

					/*Save project site survey data*/
					$params['customer_id'] 			= $customerId;
					$params['post_data'] 			= $requestData;
					$params['post_data']['proj_id'] = $projectsEntity->id;
					$params['post_data']['building_id'] = (!empty($requestData['building_id'])?$requestData['building_id']:1);
					$response = $this->SiteSurveys->AddExistingProjectSiteSurveyData($params);
				}				
				
				$result['proj_id'] = $projectsEntity->id;
				return ["status"=>1,"msg"=>'Project created successfully.',"result"=>$result];
			}
       	} 

       	if(!empty($projectId)) { 

       		/*Save project site survey data*/
			$params['customer_id'] 		= $customerId;
			$params['post_data'] 		= $requestData;
			$response 		= $this->SiteSurveys->AddExistingProjectSiteSurveyData($params);
			$buildingId  	= (!empty($requestData['building_id'])?$requestData['building_id']:0);

			/*Generate Project calculation data and save it*/
       		if(isset($requestData['survey_step']) && $requestData['survey_step'] == 4) {
				
       			$siteSurvey = $this->SiteSurveys->find('all')
                            ->where(['project_id'=>$projectId,'installer_id'=>$customerId,'building_id'=>$buildingId])
                            ->first();

                $projectData = $this->Projects->get($projectId);            
                
				/*Generate Project Data*/
       			$param['month_details'] 		= isset($siteSurvey['month_details'])?$siteSurvey['month_details']:'';
				$param['genset_details'] 		= isset($siteSurvey['genset_details'])?$siteSurvey['genset_details']:'';
				$param['inverter_details'] 		= isset($siteSurvey['inverter_details'])?$siteSurvey['inverter_details']:'';
				$param['overall'] 				= isset($siteSurvey['overall'])?$siteSurvey['overall']:'';
				$param['is_overall'] 			= isset($siteSurvey['is_overall'])?$siteSurvey['is_overall']:'2001';
				$param['latitude'] 				= isset($projectData->latitude)?$projectData->latitude:0;
				$param['longitude'] 			= isset($projectData->longitude)?$projectData->longitude:0;
				$param['critical_load']			= isset($siteSurvey['critical_load'])?$siteSurvey['critical_load']:'';
				$param['state'] 				= isset($siteSurvey['state'])?$siteSurvey['state']:'';
				$param['customer_type'] 		= isset($siteSurvey['customer_type'])?$siteSurvey['customer_type']:0;

				$projectResData = $this->GenerateProjectResultData($param);
				
				/*Update Project Survey Data*/
				if(!empty($siteSurvey['id'])) {
					$siteSurveyData 	= $this->SiteSurveys->get($siteSurvey['id']);
					$siteSurveyEntity = $this->Projects->patchEntity($siteSurveyData,$projectResData);
					$siteSurveyEntity->modified 		= $this->NOW();	
					$this->SiteSurveys->save($siteSurveyEntity);
				}

				if(!empty($buildingId) && $buildingId == 1) {
					/*Update Project Data*/
					$projectsData 	= $this->Projects->get($projectId);
					
					$projectResData['customer_type'] 	= isset($siteSurveyData->customer_type)?$siteSurveyData->customer_type:0;
					$projectResData['estimated_kwh_year'] 			= isset($siteSurveyData->avg_energy_consumption)?$siteSurveyData->avg_energy_consumption:0;
					$projectResData['area'] 			= isset($siteSurveyData->overall)?$siteSurveyData->overall:0;
					$projectResData['area_type'] 		= ((isset($siteSurveyData->is_overall) && !empty($siteSurveyData->is_overall))?$siteSurveyData->is_overall:'2001');
					
					$projectsEntity = $this->Projects->patchEntity($projectsData,$projectResData);
					$projectsEntity->modified 		= $this->NOW();	
					$projectsEntity->modified_by 	= $customerId;
                    			$projectsEntity->project_source 	= $this->Parameters->sourceSiteSurvey;;
					$this->Projects->save($projectsEntity);
				}
	   		}
	   		$result['proj_id'] = $projectId;
	   		return ["status"=>1,"msg"=>'Project saved successfully.',"result"=>$result];     		
       	}

    }

    /**
	 *
	 * save_offline_survey
	 *
	 * Behaviour : public
	 *
	 * @defination : Method used to save installer project offline request. 
	 *
	 */
	public function save_offline_survey($param = array())
    {
    	$type 	= 'ok';
		$msg 	= '';
		$myobject = array(); 
		$result = (object) $myobject;
		$bill  = 0;
		$energy_con  = 0;

		//$requestJsonData 	= file_get_contents("/var/www/html/solar/DEV/WebApplication/src/webroot/offline_request.txt");
		$requestJsonData 	= ($this->request->data['request_data']);
		$requestData 		= (!empty($requestJsonData)?json_decode($requestJsonData):'');
		$this->ApiToken->customer_id = (isset($requestData->cus_id)?$requestData->cus_id:0);
		
		$result 		= array();
		$customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$this->ApiToken->customer_id)))->first();
  		$customerId   	= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		$offlineProjId 	= (isset($requestData->offline_project_id)?$requestData->offline_project_id:0);
		$projectId 		= (isset($requestData->proj_id)?$requestData->proj_id:0);
		
		if(empty($customerId) || empty($offlineProjId)) {
       		$type 	= "error";
			$msg 	= "Invalid Request.";
			
			$this->ApiToken->SetAPIResponse('type', $type);
			$this->ApiToken->SetAPIResponse('msg', $msg);
			$this->ApiToken->SetAPIResponse('is_sync', '0');
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
       	}

		$projRes = $this->SiteSurveys->find('all')->where(['project_id_off'=>$offlineProjId,'installer_id'=>$customerId])->first();
		if(!empty($projRes['project_id'])) {
			
			$result['offline_project_id'] 	= $offlineProjId;
			$result['project_id'] 			= $projRes['project_id'];

			$this->ApiToken->SetAPIResponse('type', 'ok');
			$this->ApiToken->SetAPIResponse('is_sync','1');
			$this->ApiToken->SetAPIResponse('result', $result);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}

		$buidingDataArr = (isset($requestData->projects->building)?$requestData->projects->building:'');
   		$buidingDataArr =  (array) $buidingDataArr;

   		/* Updated Project survey data if project Exist */
       	if(!empty($projectId)) {
			foreach ($buidingDataArr as $key => $buidingData) {
       			$buidingData =  (array) $buidingData;
       			
       			$params['customer_id'] 			= $customerId;
		       	$params['post_data'] 			= $buidingData;
		       	$params['post_data']['proj_id'] = $projectId;
		       	$params['post_data']['area'] 		= $buidingData['overall'];
		       	$params['post_data']['area_type'] 	=  $buidingData['is_overall'];
		       	$params['post_data']['building_id'] = (!empty($buidingData['building_id'])?$buidingData['building_id']:1);
				$response = $this->SiteSurveys->AddExistingProjectSiteSurveyData($params);	
			}

			$type 	= "ok";
			$msg 	= "Project save successfully.";
			$syncStatus = '1';
			$result['offline_project_id'] 	= $offlineProjId;
			$result['project_id'] 			= $projectId;

			$this->ApiToken->SetAPIResponse('type', $type);
			$this->ApiToken->SetAPIResponse('msg', $msg);
			$this->ApiToken->SetAPIResponse('is_sync',$syncStatus);
			$this->ApiToken->SetAPIResponse('result', $result);

			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
		/* Updated Project survey data if project Exist */
		
		if(!empty($buidingDataArr)) {  
		
			$projectId 	= 0;
       		$params 	= array();
       		
       		/*For save project */
       		foreach ($buidingDataArr as $key => $buidingData) {
       			$buidingData =  (array) $buidingData;
				$params['customer_id'] 			= $customerId;
		       	$params['post_data'] 			= $buidingData;
		       	$params['post_data']['proj_id'] = $projectId;
		       	$this->SetVariables($buidingData);
				$params['post_data']['SiteSurveys'] = $this->request->data['SiteSurveys'];
				
				$params['post_data']['SiteSurveys']['area'] 	=  $buidingData['overall'];
				$params['post_data']['SiteSurveys']['area_type']=  (!empty($buidingData['is_overall'])?$buidingData['is_overall']:'2001');
				$params['post_data']['area_type']=  (!empty($buidingData['is_overall'])?$buidingData['is_overall']:'2001');
				$params['post_data']['SiteSurveys']['address'] 	=  $buidingData['address1'].', '.$buidingData['address3'];
				$params['post_data']['SiteSurveys']['city'] 	=  $buidingData['address3'];
				if(isset($buidingData['month_details']) && !empty($buidingData['month_details'])){
					$billDetail 	=  (array) json_decode($buidingData['month_details']);	
					
					$billDetail 	= $billDetail['ElectricityBillDetails'];
					foreach ($billDetail as $key => $value) {
						$value = (array) $value;
						$bill = $bill + $value['bill_amount'];
						$energy_con = $energy_con + $value['power_consume'];
					}
				}
				$avg_energy_con  = 0;
				if(!empty($bill)) {
					$monthlyBill 	= ($bill/12);	
				}
				if(!empty($energy_con)) {
					$avg_energy_con 	= ($energy_con/12);
				}
				$params['post_data']['SiteSurveys']['avg_monthly_bill']				= (isset($monthlyBill)?$monthlyBill:0);
				$params['post_data']['SiteSurveys']['avg_energy_consumption']		= (isset($avg_energy_con)?$avg_energy_con:0);
				$params['post_data']['SiteSurveys']['estimated_kwh_year']		= (isset($avg_energy_con)?$avg_energy_con:0);

				$projResponse 	= $this->AddInstallerProject($params);
				$projectId 		= (!empty($projResponse['result']['proj_id'])?$projResponse['result']['proj_id']:0);       			
       			break;
       		}

       		/*for save project building data*/
       		foreach ($buidingDataArr as $key => $buidingData) {
       			
       			$buidingData =  (array) $buidingData;

       			$params['customer_id'] 			= $customerId;
		       	$params['post_data'] 			= $buidingData;
		       	$params['post_data']['proj_id'] = $projectId;
		       	$projId 						= $projectId;
				$this->SetVariables($buidingData);
				if(isset($buidingData['month_details']) && !empty($buidingData['month_details'])){
					$billDetail 	=  (array) json_decode($buidingData['month_details']);	
					$billDetail 	= $billDetail['ElectricityBillDetails'];
					foreach ($billDetail as $key => $value) {
						$value = (array) $value;
						$bill = $bill + $value['bill_amount'];
						$energy_con = $energy_con + $value['power_consume'];
					}
				}
				$avg_energy_con  = 0;
				if(!empty($bill)) {
					$monthlyBill 	= ($bill/12);	
				}
				if(!empty($energy_con)) {
					$avg_energy_con 	= ($energy_con/12);
				}
				
				$params['post_data']['survey_step'] = 4;
				$params['post_data']['SiteSurveys'] = $this->request->data['SiteSurveys'];
				$params['post_data']['SiteSurveys']['area_type'] =  (!empty($buidingData['is_overall'])?$buidingData['is_overall']:'2001');
				$params['post_data']['area_type'] =  (!empty($buidingData['is_overall'])?$buidingData['is_overall']:'2001');
				$params['post_data']['SiteSurveys']['avg_monthly_bill']				= (isset($monthlyBill)?$monthlyBill:0);
				$params['post_data']['SiteSurveys']['avg_energy_consumption']		= (isset($avg_energy_con)?$avg_energy_con:0);
				$params['post_data']['SiteSurveys']['estimated_kwh_year']			= (isset($avg_energy_con)?$avg_energy_con:0);
				$projResponse 	= $this->AddInstallerProject($params);
			}

       		$type 	= "ok";
			$msg 	= "Project save successfully.";
			$syncStatus = '1';
			$result['offline_project_id'] 	= $offlineProjId;
			$result['project_id'] 			= $projId;

       	} else { 		
			$type 	= "error";
			$msg 	= "Error in project save request.";	
			$syncStatus = '0';		
		}
		$this->ApiToken->SetAPIResponse('type', $type);
		$this->ApiToken->SetAPIResponse('msg', $msg);
		$this->ApiToken->SetAPIResponse('is_sync',$syncStatus);
		$this->ApiToken->SetAPIResponse('result', $result);

		echo $this->ApiToken->GenerateAPIResponse();
		exit;	
    }
	
	 /**
	 *
	 * GetProjectCommercialChartData
	 *
	 * Behaviour : public
	 *
	 * @defination : Method used to get project commercial char data. 
	 *
	 */
    public function GetProjectCommercialChartData($param = array())
    {
    	$bill 			= $param['avg_monthly_bill'];
		$energy_con 	= $param['avg_energy_consumption'];
		$area_type 		= $param['is_overall'];
		$area 			= $param['overall'];
		$lat 			= $param['latitude'];
		$lon 			= $param['longitude']; 
		$est_kwh_year 	= $param['estimated_kwh_year'];
		$customerType 	= $param['customer_type'];
		$state 			= $param['state'];
		$backupType		= $param['backup_type'];
		$usageHours		= $param['usage_hours'];
		$cost_electricty = $param['cost_electricty'];
		$recommendedSolarPvInstall = $param['recommended_capacity'];
		$solarPenalArea = 0;
		if($area_type == $this->Projects->AREA_TYPE_FOOT) {
			$solarPenalArea	= $this->Projects->calculatePvInFoot($area);
		} elseif($area_type == $this->Projects->AREA_TYPE_METER) { 
			$solarPenalArea	= $this->Projects->calculatePvInMeter($area);	
		}
		
		$solarPvInstall 	= ceil($solarPenalArea/12);
		$solarRediationData	= $this->Projects->getSolarRediation($lat,$lon);
		$annualTotalRad		= ($solarRediationData['ann_glo']*365);
		
		$contractLoad 			= round(((($est_kwh_year*12)/((24*365*LOAD_FECTORE)/100))));
		$capacityAcualEnrgyCon	= round(((($est_kwh_year*12)/$annualTotalRad)));
		
		//$recommendedSolarPvInstall = min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));
		$monthChartDataArr		= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		
		$capitalCost 			= $this->Projects->calculatecapitalcost($recommendedSolarPvInstall,$state,$customerType);
		$capitalCostsubsidy 	= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$state,$customerType);
		
		$averageEnrgyGenInYear	= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100));
		
		/* Calculate saving */
		$cost_solar				= 0.0;	
		$unitRate				= ((!empty($est_kwh_year)?($bill/$est_kwh_year):0)-0.5);
		
		$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCostsubsidy,$backupType,$usageHours,$cost_electricty);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$chart					= $this->Projects->genrateApiChartData($fromPvSystem, $monthChartDataArr);

		return $chart;
	}

	/**
	 * @name GetLocationByLatLong
	 * @uses getting Lat Long Value as Per Logic
	 * @param int $val
	 * @return $returnVal
	 * @author Khushal Bhalsod
	 * @since 2015-11-19
	 */
	function GetLocationByLatLong($lat, $lng)
	{
	 	$url 	= 'http://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&sensor=false';
		$json 	= @file_get_contents($url);
		$data	= json_decode($json);
		$status = isset($data->status)?$data->status:"";
		$arrResult = array();
		if($status == "OK") {
			$result = $data->results[0]->address_components;
			$arrResult['address'] = '';
			if(!empty($result)) {
				for($i=0;$i<count($result); $i++) {

					if($result[$i]->types[0] == 'route' || $result[$i]->types[0] == 'sublocality_level_1') {
						$arrResult['address'] .= $result[$i]->long_name.",";
					}
					if($result[$i]->types[0] == 'postal_code') {
						$arrResult['postal_code'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
					}
					if($result[$i]->types[0] == 'locality') {
						$arrResult['city'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
					}
					if($result[$i]->types[0] == 'administrative_area_level_1') {
						$arrResult['state'] = (isset($result[$i]->long_name))?$result[$i]->long_name:'';
						$arrResult['state_short_name'] = (isset($result[$i]->short_name))?$result[$i]->short_name:'';
					}
					if($result[$i]->types[0] == 'country') {
						$arrResult['country'] = $result[$i]->long_name;
					}
				}
				$arrResult['address'] = rtrim($arrResult['address'],",");
			}
			$arrResult['landmark'] = (isset($data->results[0]->formatted_address)? trim($data->results[0]->formatted_address):'');
		} 
		return $arrResult;
	}

    /**
     * @name getsitesurveyrequestpdfexcel
     * @uses getting Lat Long Value as Per Logic
     * @param int $val
     * @return $returnVal
     * @author sachin patel
     * @since 2018-05-28
     */
    function getsitesurveyrequestpdfexcel(){
        $error = "Something Message";
        $this->autoRender 	= false;
        $cust_id                    = $this->ApiToken->customer_id;
        $newRequest                 = $this->SitesurveyProjectRequest->newEntity($this->request->data);
        $newRequest->requested_by   = $cust_id;
        $newRequest->created_by     = $cust_id;
        $newRequest->request_date   = $this->NOW();
        $newRequest->created        = $this->NOW();

        if ($this->SitesurveyProjectRequest->save($newRequest)) {
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('msg', 'Save successfully');
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        }

        $this->ApiToken->SetAPIResponse('error', $error);
        $this->ApiToken->SetAPIResponse('msg', $error);
        echo $this->ApiToken->GenerateAPIResponse();
        exit;



    }

    public function GetProjectCommercialpaybackChartData($param = array())
    {
    	$bill 			= $param['avg_monthly_bill'];
		$energy_con 	= $param['avg_energy_consumption'];
		$area_type 		= $param['is_overall'];
		$area 			= $param['overall'];
		$lat 			= $param['latitude'];
		$lon 			= $param['longitude']; 
		$est_kwh_year 	= $param['estimated_kwh_year'];
		$customerType 	= $param['customer_type'];
		$state 			= $param['state'];
		$backupType		= $param['backup_type'];
		$usageHours		= $param['usage_hours'];
		$cost_electricty = $param['cost_electricty'];
		$recommendedSolarPvInstall = $param['recommended_capacity'];
		
		$solarPvInstall			= $recommendedSolarPvInstall;

		$capitalCost			= $this->Projects->calculatecapitalcost($solarPvInstall,$state,$customerType);
		$solarRediationData		= $this->Projects->getSolarRediation($lat,$lon);

		$annualTotalRad			= ($solarRediationData['ann_glo']*365);

		$capacityAcualEnrgyCon	= ((($est_kwh_year*12)/$annualTotalRad));
		$contractLoad			= ((($est_kwh_year*12)/((24*365*LOAD_FECTORE)/100)));
		$recommendedSolarPvInstall 		= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);

		$averageEnrgyGenInYear	= round(((($solarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100));

		$capitalCostsubsidy		= $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$state,$customerType);
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));
		$monthChartDataArr		= $this->Projects->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		/* Calculate saving */
	    if($est_kwh_year==0)
        {
            $est_kwh_year=1;
        }
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		$monthly_saving 		= ($bill - ($est_kwh_year  - $montly_pv_generation) * (($bill/$est_kwh_year)-0.5));
		/* Calculate saving */

		$cost_solar				= 0.0;
		$unitRate				= (($bill/$est_kwh_year)-0.5);
		$solarChart 			= $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,($capitalCostsubsidy/100000),$backupType	,$usageHours);
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->Projects->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$bill ,$capitalCost);
		$cost_solar				= $gross_solar_cost['net_cog'];
		$chart					= $this->Projects->genrateApiChartData($fromPvSystem,$monthChartDataArr);
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$solar_ratio			= (($est_kwh_year > 0)?(($averageEnrgyGenInMonth/$est_kwh_year) * 100):0);
		$estimated_cost_subsidy = isset($capitalCostsubsidy)?round(($capitalCostsubsidy/100000),2):$capitalCost;
		$payBackGraphData 		=  $this->Projects->GetPaybackChartData($estimated_cost_subsidy, ($monthly_saving*12));
		if($payBackGraphData) {
			foreach ($payBackGraphData as $key => $value) {
				$savingChartArr[] 		= "{'x':".$key.",'y':".round($value)."}";	
			}
		}
		return '['.implode(',',$savingChartArr).']';

	}
	/**
	 *
	 * uploadcommssioningimage
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to add image/document which attached when setinstallastion API call
	 *
	 */
	public function uploadcommssioningimage()
	{ 
		$this->autoRender		= false;
		$this->SetVariables($this->request->data);
		$cus_id					= $this->ApiToken->customer_id;
		$customerData   		= $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
  		$cus_id   				= (isset($customerData['installer_id'])?$customerData['installer_id']:0);
		if(!empty($cus_id)) {
			/*Store installer project*/
			$project_id 		= (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
			$CommisioningData 	= $this->Commissioning->find('all',array('conditions'=>array('Commissioning.installer_id'=>$cus_id,'Commissioning.project_id'=>$project_id)))->first();
			$type 								= 'commissioning_img';
			$imagePatchEntity 					= $this->CommissioningImage->newEntity($this->request->data);
			$imagePatchEntity->type 			= $type;
			$imagePatchEntity->commissioning_id = (isset($CommisioningData['id'])?$CommisioningData['id']:1);
			$imagePatchEntity->project_id 		= $this->request->data['project_id'];
			if(!empty($this->request->data['file_attach_commision']) && $this->request->data['file_attach_commision']!='')
			{
				$image_path 					= COMMISSIONING_DATA_PATH."commissioning_img/".$project_id.'/';
				if(!file_exists(COMMISSIONING_DATA_PATH."commissioning_img/".$project_id.'/')){
					@mkdir(COMMISSIONING_DATA_PATH."commissioning_img/".$project_id.'/', 0777,true);
				}
				$file_name 						= $this->file_upload($image_path,$this->request->data['file_attach_commision'],false,65,65,$image_path);
				$imagePatchEntity->photo 		= $file_name;
				$this->CommissioningImage->save($imagePatchEntity);
				$this->ApiToken->SetAPIResponse('type', 'ok');
				$this->ApiToken->SetAPIResponse('msg', 'Image Uploaded Successfully.');
			} 
			else 
			{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
			}
		} 
		else 
		{
			$this->ApiToken->SetAPIResponse('type', 'error');
			$this->ApiToken->SetAPIResponse('msg', 'Invalid request data.');
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}

}
