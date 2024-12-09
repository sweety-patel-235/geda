<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
//use App\Model\Table\Security;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
//use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for Ticket table. It extends Table Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    Pravin Sanghani
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class ProjectsTable extends AppTable
{
	/**
	 *
	 * The status of $STATUS_ACTIVE is universe
	 *
	 * Potential value are 1 (identify Admin User Active)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_ACTIVE = 1;
	/**
	 *
	 * The status of $STATUS_INACTIVE is universe
	 *
	 * Potential value are 0 (identify Admin User InActive/Deactive)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_INACTIVE = 0;
	/**
	 *
	 * The area type foot of $AREA_TYPE_FOOT is universe
	 *
	 * Potential value are 2001 (identify Area Type Foot)
	 *
	 * @var Int
	 *
	 */
	var $AREA_TYPE_FOOT = 2001;
	/**
	 *
	 * The area type meter of $AREA_TYPE_METER is universe
	 *
	 * Potential value are 2002 (identify Area Type Meter)
	 *
	 * @var Int
	 *
	 */
	var $AREA_TYPE_METER = 2002;
	/**
	 *
	 * The Backup type Generator of $BACKUP_TYPE_GENERATOR is universe
	 *
	 * Potential value are 1 (identify Backup Type Generator)
	 *
	 * @var Int
	 *
	 */
	var $BACKUP_TYPE_GENERATOR = 1;
	/**
	 *
	 * The Backup type Inverter of $BACKUP_TYPE_INVERTER is universe
	 *
	 * Potential value are 2 (identify Backup Type Inverter)
	 *
	 * @var Int
	 *
	 */
	var $BACKUP_TYPE_INVERTER = 2;

	var $backupTypeArr 		= array(1=>"Generator",2=>"Inverter");
	var $SocialSubsidyArr 	= array('state_subsidy'  		=> '0',
									'state_capacity' 		=> '0',
									'state_subcidy_type' 	=> '1',
									'central_subsidy' 		=> '30',
									'central_capacity' 		=> '500',
									'central_subcidy_type' 	=> '0',
									'other_subsidy' 		=> '0',
									'other_capacity' 		=> '0',
									'other_subcidy_type' 	=> '1',
								);
	var $validate			= array();
	var $validationSet		= "";
	var $table 				= 'projects';
	var $data_post 			= array();
	
	public function initialize(array $config)
    {
        $this->table('projects');
       	$this->addAssociations([
          'hasMany' => ['CustomerProjects'],
          'belongsTo' => ['Customers']
        ]);
                 
    }
	/**
	 *
	 * The status of $validate_timezone is universe
	 *
	 * Potential value are validate time zone
	 *
	 * @var Array
	 *
	 */
	var $validate_timezone =  array(
		/*
			'timezone' => array(
					'rule' => array('maxLength',5),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Please select valid Time zone.'
			)
			*/
	);
	
	/**
	 *
	 *  identicalFieldValues
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field compared with Confirm password field.
	 *
	 */
    function identicalFieldValues( $field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];
            if($v1 !== $v2) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
	/**
	 *
	 *  beforeSave
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field encrypted with Security salt
	 *
	 */
	//Public function beforeSave(Event $event,Entity $entity)
	public function beforeSave($event, $entity, $options)
	{
		if(isset($entity->password) && !empty($entity->password))
		{
			$entity->password = Security::hash(Configure::read('Security.salt') . $entity->password);
		}else if(isset($entity->password) && !empty($entity->newpassword)){
			$entity->password = Security::hash(Configure::read('Security.salt') . $entity->newpassword);
		}
		return TRUE;
	}

	/**
	 *
	 *  getAdminUserTypeWise
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	function getAdminUserTypeWise($Admintype=null,$status=1)
	{
		if($Admintype==null)return array();

		$arrConditions=array("Customers.usertype"=>$Admintype,"Customers.status"=>$status);

		return $this->find('all',array("conditions"=>$arrConditions));
	}

	public function GenerateCustomerRightSession($adminuserid, $session)
	{
		$this->id = $adminuserid;
		$arrAdminuser = $this->get($this->id);
		$arrAdminRoleRights = array();
		$arrUserRights 		= array();
		
		$objUserroleright 	= TableRegistry::get('Userroleright');
		$Admintransaction 	= TableRegistry::get('Admintransaction');
		//pr($arrAdminuser);
		$arrAdminRoleRights = $objUserroleright->getAllAdminUserRoleRight($arrAdminuser['usertype']);
		if(is_array($arrAdminRoleRights) && count($arrAdminRoleRights)>0)
		{
			$arrUserRights 		= unserialize($arrAdminuser['userrights']);
		}
		$userrights			= array();
		$conarr				= array();
        if(is_array($arrAdminRoleRights) && count($arrAdminRoleRights)>0) {
            foreach ($arrAdminRoleRights as $keyid => $arrights) {
                $userrights[$keyid] = $arrights;
            }
        }
        if(is_array($arrUserRights) && count($arrUserRights)>0) {
            foreach ($arrUserRights as $kid => $arights) {
                if (isset($userrights[$kid])) {
                    $userrights[$kid] = $userrights[$kid] . "," . $arights;
                } else {
                    $userrights[$kid] = $arights;
                }
            }
        }
        if(empty($userrights))
		{
			$this->Flash->set('You are not authorized to view that page.');
            return $this->redirect('/admin/users/login');
		}	
		foreach($userrights as $moduleid=>$permissiontypes) {
			$conarr[]=" trnmoduleid='".$moduleid."' AND trntype IN(".implode(",",array_unique(explode(",",$permissiontypes))).") ";
		}
		$arradmintransaction = $Admintransaction->find('list',array('fields'=>array('id'),'conditions'=>array('OR'=>$conarr)));
		$arradmintransaction = $arradmintransaction->toArray();
		return $arradmintransaction;
	}

	public function AddValidation($field,$Rules=array()) {
		$this->validator()->add($field,$Rules);
	}
	

	public function setValiationRules($rule="register")
	{
		$param = 'validate'.strtolower($rule);
		if (isset($this->{$param})) {
			$this->validationSet	= $rule;
			$this->validate			= $this->{$param};
		}
	}

	public function validates($options = array()) 
	{
		// copy the data over from a custom var, otherwise
		$actionSet = 'validate' . Inflector::camelize(Router::getParam('action'));
		if (isset($this->validationSet)) {
			$temp			= $this->validate;
			$param			= 'validate' . $this->validationSet;
			$this->validate = $this->{$param};
		} elseif(isset($this->{$actionSet})) {
			$temp			= $this->validate;
			$param			= $actionSet;
			$this->validate = $this->{$param};
		}
		$errors = $this->invalidFields($options);
		
		// copy it back
		if (isset($temp)) {
			$this->validate = $temp;
			unset($this->validationSet);
		}
		if (is_array($errors)) {
			return count($errors) === 0;
		}
		return $errors;
	}

	/**
	 *
	 *  getAdminUserList
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	function GetProjectsList($Admintype=null,$status=1)
	{
		if($Admintype==null)return array();
		$arrConditions=array("usertype"=>$Admintype,"status"=>$status);
	
		return $this->find('list',array('fields'=>array("Projects.id","Projects.name"),"conditions"=>$arrConditions));
	}

	/**
	 *
	 *  GetAllUser
	 *
	 * Behaviour   : Public
	 *
	 * @param : $Admintype : If available should pass here to define data belong to which Admin Type
	 * @return :  its returns the Admin User Data Array
	 * @defination : this method find the particular admin type wise Admin users. for e.g. Admin Type Consultant
	 *
	 */
	function GetAllProjects($id=null)
	{
		if($id==null)return array();
		$arrConditions=array("id"=>$id,'status'=>$this->STATUS_ACTIVE);
		return $this->find('list',array('fields'=>array("Projects.id","Projects.username"),"conditions"=>$arrConditions));
	}

	/**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationLogin(Validator $validator)
    {
    	$validator->notEmpty('LoginUsername', 'Please Enter Username.');
		$validator->notEmpty('LoginPassword', 'Please Enter Password.');

    	return $validator;
    }

	/**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationAdd(Validator $validator)
    {
    	$validator->notEmpty('name', 'Project Name can not be blank.');

		/* $validator->add('password', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['confirmpassword']) &&
		            $context['data']['confirmpassword'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]); */

    	return $validator;
    }
	

    /**
     * Edit validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationEdit(Validator $validator)
    {
    	$validator->notEmpty('firstname', 'First Name can not be blank.');
		$validator->notEmpty('lastname', 'Last Name can not be blank.');

		$validator->notEmpty('username', 'Username can not be blank.');
		$validator->notEmpty('usertype','User Type must be select');
		$validator->add('password', 'passwordsEqual', [
		    'rule' => function ($value, $context) {
		        return
		            isset($context['data']['confirmpassword']) &&
		            $context['data']['confirmpassword'] === $value;
		    },
		    'message' => 'Password are mismatch.'
		]);

    	return $validator;
    }

	/**
	*
	* calculateMonthChartData
	*
	* Behaviour : public
	*
	* Parameter : $solarRediationData,$solarPvInstall
	*
	* @defination : Method is used to calculate energy month chart data.
	*
	*@Usage : this method used in solar pdf generation.
	*/
	public function calculateMonthChartData($solarRediationData,$solarPvInstall)
	{
		$chartDataArr = array();
		if(!empty($solarRediationData)) {
			for($i=1;$i<=12;$i++) {
				$keyName 		= strtolower(date('M', mktime(0, 0, 0, $i, 10)))."_glo";
				$monthGloVal 	= (isset($solarRediationData[$keyName])?$solarRediationData[$keyName]:0);
				$chartDataArr[$i] = ((((($solarPvInstall*$monthGloVal)*PERFORMANCE_RATIO)/100))*cal_days_in_month(CAL_GREGORIAN, $i, date("Y")));
			}
		}
		return $chartDataArr;
	}

	/**
	*
	* calculateSolarPowerGreenSavingsData
	*
	* Behaviour : public
	*
	* Parameter : 
	*
	* @defination : Method is used to calculate CO2 Avoided equals, Nos. of trees etc
	*
	*@Usage : this method used in solar pdf generation.
	*/
	public function calculateSolarPowerGreenSavingsData($params = array())
	{
		if(empty($params)){
			return array(
				"CO2AvoidedEquals" 	=>  0,
				"noOfTrees"		   	=>  0,
				"carsOffTheRoad"	=> 	0,
				"oilEquivalent"		=> 	0,
				"avgHomePowered"	=> 	0
			);
		}

		/* Input Parameters */
		$solarArraySize 							= $params['recommendedCapacity'];
		$solarYearlyOutput 							= $params['estimatedKWHYear'];
		

		$avgCO2EmittedToProduceElectricityFactor 	= 1; 	//C36
		$avgCO2EmittedToProduceElectricity 			= 0.608; //F36

		$oneGrowingTreeAnualOffset 					= 22.68; //F37
		$oneGrowingTree40YearOffset 				= $oneGrowingTreeAnualOffset * 40; //F38
		$carCO2PollutionPerKM 						= 0.250;	//F39
		$avgYearlyMilesDriven 						= 20000;	//C40
		$annualCO2ForMediumCar 						= $avgYearlyMilesDriven * $carCO2PollutionPerKM; //F41
		
		$avgYearlyHomeElectricityFactor 			= 8900;		//C42
		$avgYearlyHomeElectricity 					= $avgYearlyHomeElectricityFactor * $avgCO2EmittedToProduceElectricity; //F42

		$avgYearlyLightBulbFactor 					= 87.6; //C43
		$avgYearlyLightBulb 						= 40;	//F43

		$avgYearlyComputerFactor 					= 365; //C44
		$avgYearlyComputer  						= 1;	// F44

		$emissionFromGallonOfGasoLine 				= 2.31; //F45

		
		$solarArrayOutput 							= $solarArraySize * $solarYearlyOutput; //C17
		$CO2OffsetTons 								= ($solarArrayOutput * $avgCO2EmittedToProduceElectricity)/$oneGrowingTree40YearOffset;
		$CO2OffsetKM 								= $CO2OffsetTons * $oneGrowingTree40YearOffset;
		$carsOffTheRoadOneYear 						= $CO2OffsetKM / $annualCO2ForMediumCar;
		$gasolineEquivalent							= $CO2OffsetKM / $emissionFromGallonOfGasoLine;
		$treeEquivalent 							= $solarArrayOutput / $oneGrowingTreeAnualOffset;
		$treePlantingEquivalent 					= $solarArrayOutput / $oneGrowingTree40YearOffset;
		$avgHomePowered 							= $solarArrayOutput / $avgYearlyHomeElectricityFactor;
		$avgLightBulbPowered 						= $solarArrayOutput / $avgYearlyLightBulbFactor;
		

		$CO2AvoidedEquals 	= $CO2OffsetTons;
		$noOfTrees 			= $treePlantingEquivalent;
		$carsOffTheRoad 	= $carsOffTheRoadOneYear;
		$oilEquivalent 		= $gasolineEquivalent;
		$avgHomePowered 	= $avgHomePowered;
		return $result =  array(
			"CO2AvoidedEquals" 	=> 	_FormatNumberV2($CO2AvoidedEquals),
			"noOfTrees"		   	=> 	round($noOfTrees),
			"carsOffTheRoad"	=> 	round($carsOffTheRoad),
			"oilEquivalent"		=> 	round($oilEquivalent),
			"avgHomePowered"	=> 	round($avgHomePowered)
		);
		//prd($result);

	}

	/**
	*
	* GetProjectPDFReportId
	*
	* Behaviour : public
	*
	* Parameter : $projectId
	*
	* @defination : Method is used to create report id for report pdf file.
	*
	*/
	public function GetProjectPDFReportId($projectId)
	{	
		$reportId = '';
		if(!empty($projectId)) {
			if(strlen($projectId)==1) $projectId = "00000".$projectId;
			if(strlen($projectId)==2) $projectId = "0000".$projectId;
			if(strlen($projectId)==3) $projectId = "000".$projectId;
			if(strlen($projectId)==4) $projectId = "00".$projectId;
			if(strlen($projectId)==5) $projectId = "0".$projectId;
			$reportId = $projectId;
		}
		return $reportId;
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
	public function getprojectestimation($requestData=array(),$customer_id='')
	{
		$requestData['Projects']['name'] 				= (!empty($requestData['proj_name'])?$requestData['proj_name']:'');
		$energy_con 									= $requestData['energy_con'];
		$requestData['Projects']['energy_con'] 			= (!empty($requestData['energy_con'])?$requestData['energy_con']:0);
		$area_type 										= $requestData['area_type'];
		$requestData['Projects']['area_type']			= (!empty($requestData['area_type'])?$requestData['area_type']:0);
		$area 											= $requestData['area'];	
		$requestData['Projects']['area']				= (!empty($requestData['area'])?$requestData['area']:0);	
		$latitude 										= $requestData['latitude'];
		$requestData['Projects']['latitude']			= (!empty($requestData['latitude'])?$requestData['latitude']:0);
		$longitude										= $requestData['longitude'];
		$requestData['Projects']['longitude']			= (!empty($requestData['longitude'])?$requestData['longitude']:0);
		$avg_month_bill									= $requestData['avg_monthly_bill'];
		$requestData['Projects']['avg_monthly_bill']	= (isset($requestData['avg_monthly_bill'])?$requestData['avg_monthly_bill']:0);
		$backup_type									= $requestData['backup_type'];
		$requestData['Projects']['backup_type'] 		= (!empty($requestData['backup_type'])?$requestData['backup_type']:0);
		$usage_hours									= $requestData['usage_hours'];
		$requestData['Projects']['usage_hours'] 		= (!empty($requestData['usage_hours'])?$requestData['usage_hours']:0);
		$project_type									= $requestData['project_type'];
		$requestData['Projects']['customer_type']		= (!empty($requestData['project_type'])?$requestData['project_type']:0);
		$requestData['Projects']['landmark']			= (!empty($requestData['landmark'])?$requestData['landmark']:'');
		$requestData['Projects']['project_social_consumer']		= (!empty($requestData['project_social_consumer'])?$requestData['project_social_consumer']:'0');
		$requestData['Projects']['project_common_meter']		= (!empty($requestData['project_common_meter'])?$requestData['project_common_meter']:'0');
		$requestData['Projects']['project_disclaimer_subsidy']	= (!empty($requestData['project_disclaimer_subsidy'])?$requestData['project_disclaimer_subsidy']:'0');
		$requestData['Projects']['project_renewable_attr']		= ($requestData['project_renewable_attr']=='1' || $requestData['project_renewable_attr']=='0') ? $requestData['project_renewable_attr'] : NULL;
		$requestData['Projects']['project_renewable_rec']		= ($requestData['project_renewable_attr']=='1') ? NULL : (($requestData['project_renewable_rec']=='1' || $requestData['project_renewable_rec']=='0') ? $requestData['project_renewable_rec'] : NULL);


		$solarPenalArea = 0;
		$monthly_saving	= 0;
		if($area_type == $this->AREA_TYPE_FOOT) {
			$solarPenalArea	= $this->calculatePvInFoot($area);
		} elseif($area_type == $this->AREA_TYPE_METER) { 
			$solarPenalArea	= $this->calculatePvInMeter($area);	
		}

		$state = 'Gujarat';//(isset($requestData['Projects']['state'])?$requestData['Projects']['state']:"");
		$userlat= (isset($requestData['Projects']['userlat'])?$requestData['Projects']['userlat']:"");
		$userlong= (isset($requestData['Projects']['userlong'])?$requestData['Projects']['userlong']:"");
		/** get location information */
		if (!isset($requestData['Projects']['address']) || $requestData['Projects']['address'] == "") {
			$locationdata 									= GetLocationByLatLong($latitude,$longitude);
			$requestData['Projects']['address']				= (isset($locationdata['address'])?$locationdata['address']:'');
			$requestData['Projects']['city']				= (isset($locationdata['city'])?$locationdata['city']:'');
			$requestData['Projects']['state']				= 'Gujarat';//(isset($locationdata['state'])?$locationdata['state']:'');
			$state 											= $requestData['Projects']['state'];
			$requestData['Projects']['state_short_name']	= 'GJ';//(isset($locationdata['state_short_name'])?$locationdata['state_short_name']:'');
			$requestData['Projects']['country']				= (isset($locationdata['country'])?$locationdata['country']:'');
			$requestData['Projects']['pincode']				= (isset($locationdata['postal_code'])?$locationdata['postal_code']:0);
		}
		
		if(!empty($requestData['Projects']['id'])) {
			$projectsData 									= $this->get($requestData['Projects']['id']);
			$requestData['Projects']['address']				= (!empty($requestData['Projects']['address'])?$requestData['Projects']['address']:$projectsData->address);
			$requestData['Projects']['city']				= (!empty($requestData['Projects']['city'])?$requestData['Projects']['city']:$projectsData->city);
			$requestData['Projects']['state']				= 'Gujarat';//(!empty($requestData['Projects']['state'])?$requestData['Projects']['state']:$projectsData->state);
			$state											= (!empty($requestData['Projects']['state'])?$requestData['Projects']['state']:$projectsData->state);
			$requestData['Projects']['state_short_name']	= 'GJ';//(!empty($requestData['Projects']['state_short_name'])?$requestData['Projects']['state_short_name']:$projectsData->state_short_name);
			$requestData['Projects']['country']				= (!empty($requestData['Projects']['country'])?$requestData['Projects']['country']:$projectsData->country);
			$requestData['Projects']['pincode']				= (!empty($requestData['Projects']['pincode'])?$requestData['Projects']['pincode']:$projectsData->pincode);
			$requestData['Projects']['latitude']			= (!empty($requestData['Projects']['latitude'])?$requestData['Projects']['latitude']:$projectsData->latitude);
			$latitude											= (!empty($requestData['Projects']['latitude'])?$requestData['Projects']['latitude']:$projectsData->latitude);

			$requestData['Projects']['longitude']			= (!empty($requestData['Projects']['longitude'])?$requestData['Projects']['longitude']:$projectsData->longitude);
			$longitude										= (!empty($requestData['Projects']['longitude'])?$requestData['Projects']['longitude']:$projectsData->longitude);

			$requestData['Projects']['userlat']		    	= (!empty($requestData['Projects']['userlat'])?$requestData['Projects']['userlat']:$projectsData->userlat);
			$userlat										= (!empty($requestData['Projects']['userlat'])?$requestData['Projects']['userlat']:$projectsData->userlat);

			$requestData['Projects']['userlong']			= (!empty($requestData['Projects']['userlong'])?$requestData['Projects']['userlong']:$projectsData->userlong);
			$userlong										= (!empty($requestData['Projects']['userlong'])?$requestData['Projects']['userlong']:$projectsData->userlong);
		}
		/** get location information */
	
		$solarPvInstall 							= ceil($solarPenalArea/12);
		$solarRediationData							= $this->getSolarRediation($latitude,$longitude);
		$annualTotalRad								= ($solarRediationData['ann_glo']*365);
		$requestData['Projects']['solar_radiation'] = $annualTotalRad;
		$contractLoad 								= round(((($energy_con*12)/((24*365*LOAD_FECTORE)/100))));
		$capacityAcualEnrgyCon						= round(((($energy_con*12)/$annualTotalRad)));
		$recommendedSolarPvInstall 					= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);

		$requestData['Projects']['maximum_capacity'] 		= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
		$requestData['Projects']['recommended_capacity'] 	= $recommendedSolarPvInstall;
		$requestData['Projects']['capacity_kw'] 			= $recommendedSolarPvInstall;
		$requestData['Projects']['original_capacity'] 		= $recommendedSolarPvInstall;
		
		$averageEnrgyGenInDay 	= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));
		$monthChartDataArr		= $this->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		$capitalCost 			= $this->calculatecapitalcost($recommendedSolarPvInstall,$requestData['Projects']['state'],$requestData['Projects']['customer_type']);
		$requestData['Projects']['estimated_cost'] 	= $capitalCost;
		$calculate_subsidy 							= $capitalCost;
		if(!empty($requestData['Projects']['id'])) {
			$ApplyOnlines   		= TableRegistry::get('ApplyOnlines');
			$ApplyOnlineData 		= $ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$requestData['Projects']['id'])))->first();
			if(!empty($ApplyOnlineData) && $ApplyOnlineData->disclaimer_subsidy==1 && SHOW_SUBSIDY_EXECUTION==1)
			{
				$calculate_subsidy 	= 0;
			}
		}
		$capitalCostsubsidy 	= $this->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$calculate_subsidy,$requestData['Projects']['state'],$requestData['Projects']['customer_type']);
		$requestData['Projects']['estimated_cost_subsidy'] =  $capitalCostsubsidy;
		$highRecommendedSolarPvInstall 	= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);	
		$averageEnrgyGenInYear	= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100));
		
		/* Calculate saving */
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		 
		if(!empty($energy_con) && !empty($avg_month_bill)) { 
			$monthly_saving 		= ($avg_month_bill - ($energy_con - $montly_pv_generation) * (($avg_month_bill/$energy_con)-0.5)); 
		}
		
		/* Calculate saving */
		$cost_solar				= 0.0;
		$unitRate 				= 0;	
		if(!empty($avg_month_bill) && !empty($avg_month_bill) && (!empty($energy_con))) {
			$unitRate				= (($avg_month_bill/$energy_con)-0.5);
		}

		$solarChart 			= $this->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,($capitalCostsubsidy/100000),$backup_type,$usage_hours);
		
		$payBack 				= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$requestData['Projects']['payback'] = $payBack;  
		$fromPvSystem 			= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost		= $this->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$avg_month_bill,$capitalCost);	
		$cost_solar				= $gross_solar_cost['net_cog'];
		$requestData['Projects']['estimated_cost'] 		= $capitalCost;	
		$requestData['Projects']['cost_solar'] 			= $cost_solar;	
		$requestData['Projects']['avg_generate']		= $averageEnrgyGenInYear;
		$requestData['Projects']['estimated_kwh_year']	= $energy_con;
		$chart					= $this->genrateApiChartData($fromPvSystem, $monthChartDataArr);	
		$averageEnrgyGenInMonth	= ($averageEnrgyGenInYear/12);
		$payBackGraphData 		= $this->GetPaybackChartData(round(($capitalCostsubsidy/100000),2), ($monthly_saving*12));
		$solar_ratio			= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
		//$savingChartArr2		= array();
		if($payBackGraphData){
			foreach ($payBackGraphData as $key => $value) {
				$savingChartArr[] 		= "{'x':".$key.",'y':".round($value)."}";
			}
		}
		
		if(!empty($customer_id)){
			if(!empty($requestData['Projects']['id'])) {
				$projectsEntity = $this->patchEntity($projectsData,$requestData);
				$projectsEntity->modified 		= $this->NOW();	
				$projectsEntity->modified_by 	= $customer_id;
			} else {
				$projectsEntity = $this->newEntity($requestData['Projects']);
				$projectsEntity->created 		= $this->NOW();
				$projectsEntity->created_by 	= $customer_id;
			}

			$dataProject = array();
			if(!empty($customer_id)) {
				if ($this->save($projectsEntity)) {
					$dataProject['CustomerProjects']['customer_id']	= $customer_id;
					$dataProject['CustomerProjects']['project_id']	= $projectsEntity->id;
					
					$CustomerProjects = TableRegistry::get('CustomerProjects');	
					if(empty($requestData['Projects']['id'])) {
						$customerProjectsEntity = $CustomerProjects->newEntity($dataProject);
						$customerProjectsEntity->created = $this->NOW();
						$CustomerProjects->save($customerProjectsEntity);	
					}
					$customerTable 		= ($requestData['ses_login_type'] == 'developer') ? 'DeveloperCustomers' : 'Customers';
					$CustomersTable 	= TableRegistry::get($customerTable);
					$customerdata       = $CustomersTable->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$customer_id)))->first();
		            $user_roles = explode(",",$customerdata->user_role);
		            $Parameters = TableRegistry::get('Parameters');
		            if (in_array($Parameters->bd_role,$user_roles) && (!in_array($Parameters->admin_role,$user_roles))) 
		            {
		            	$dataProjectBD 										= array();
 						$dataProjectBD['ProjectAssignBd']['projects_id'] 	= $projectsEntity->id;
 						$dataProjectBD['ProjectAssignBd']['customers_id']	= $customer_id;
                        $dataProjectBD['ProjectAssignBd']['created_by']		= $customer_id;
                        $ProjectAssignBd 									= TableRegistry::get('ProjectAssignBd');
                        $assign 											= $ProjectAssignBd->newEntity($dataProjectBD);
                        $assign->created 									= $this->NOW();
                        $ProjectAssignBd->save($assign);
                    }

					/** Save Entry into installer_projects if customer is installer */
					$this->ProjectForInstaller($customer_id,$projectsEntity->id);
					/** Save Entry into installer_projects if customer is installer */
				}
			}
		}	
		$status							= 'ok';
		$messege						= array(); 
		$messege 						= array_merge($messege,$chart);
		$messege['name'] 				= $project_type;
		$messege['is_residential'] 		= $project_type;
		$messege['paybackChart']		= '['.implode(',',$savingChartArr).']';
		$messege['cost_solar']			= (isset($cost_solar)?$cost_solar:0);
		$messege['saving_month']		= _FormatGroupNumberV2($monthly_saving);
		$messege['proj_id']				= isset($projectsEntity->id) ? $projectsEntity->id : '';
		$messege['capacity']			= ($recommendedSolarPvInstall > 10)?floor($recommendedSolarPvInstall):number_format($recommendedSolarPvInstall,1);
		$messege['highcapacity']		= round($highRecommendedSolarPvInstall);
		$messege['est_cost']			= $capitalCost;
		$messege['est_cost_subsidy']	= round(($capitalCostsubsidy/100000),2);
		$messege['avg_gen']				= round($averageEnrgyGenInMonth,2);
		$messege['payback']				= round($payBack,2);
		$messege['solar_ratio'] 		= ($solar_ratio > 100)?'100':round($solar_ratio);
		$messege['project_state'] 		= $state;
		$messege['project_latitude']    = $latitude	;
		$messege['project_longitude']   = $longitude;
		$messege['user_latitude']       = $userlat	;
		$messege['user_longitude']      = $userlong	;
		return $messege;
		
	}

	/**
	 * @name getSolarRediation
	 * @uses getting Solar Rediation Data as per logic
	 * @param float $lat ,float $val
	 * @return $rediationArr
	 * @author Pravin Sanghani
	 * @since 2015-11-19
	 */
	public function getSolarRediation($lat=null,$long=null)
	{
		$GhiData = TableRegistry::get('GhiData');
		$ghidata = array();
		$ghidata = $GhiData->getGhiData($long,$lat);		
		if(!empty($ghidata))
			return $ghidata;
		else
			return $GhiData->getGhiData(72.5800,23.0300);
	}

	/**
	 *
	 * calculatePvInFoot
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate PV Area in Foot
	 *
	 */
	public function calculatePvInFoot($area=null)
	{
		if(empty($area))
			return false;
		$pvArea	= 0;
		if($area <= RUF_RESIDENT_FOOT_LIMIT) {
			$pvArea	= ($area*RUF_RESIDENT/100);
		} else if($area == RUF_COMMERCE_FOOT_LIMIT) { echo "xc";
			$pvArea	= ($area*RUF_COMMERCE/100);
		} else { 
			$pvArea	= ($area*RUF_INDUSTRIAL/100);
		}
		return $pvArea;
	}

	/**
	 *
	 * calculatePvInMeter
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate PV Area in Meter
	 *
	 */
	public function calculatePvInMeter($area=null)
	{
		if(empty($area))
			return false;
		$pvArea		=	0;
		if($area <= RUF_RESIDENT_METER_LIMIT) {
			$pvArea	=	($area*RUF_RESIDENT/100);
		} else if($area <= RUF_COMMERCE_METER_LIMIT) {
			$pvArea	=	($area*RUF_COMMERCE/100);
		} else {
			$pvArea	=	($area*RUF_INDUSTRIAL/100);
		}
		return $pvArea;
	}

	/**
	 * calculatecapitalcost
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate Capital cost
	 *
	 */
	public function calculatecapitalcost($spvi=null,$state='',$customer_type='')
	{
		if(empty($spvi)) return false;
		$cost	=	0;
		if(strtolower($state)=='gujarat' || $state == '4') {
			if (!defined('BAND_1')) @define("BAND_1","0.483");
			if (!defined('BAND_2')) @define("BAND_2","0.48");
			if (!defined('BAND_3')) @define("BAND_3","0.44");
			if (!defined('BAND_4')) @define("BAND_4","0.41");
			if($spvi >= 0.1 && $spvi <= 6) {
				$cost	=	($spvi*BAND_1);
			} else if($spvi > 6 && $spvi <= 10) {
				$cost	=	($spvi*BAND_2);
			} else if($spvi > 10 && $spvi <= 50) {
				$cost	=	($spvi*BAND_3);
			} else {
				$cost	=	($spvi*BAND_4);
			}
		} else {
			if($spvi <= 10) {
				$cost	=	($spvi*COST_UPTO_10_KW);
			} else if($spvi > 10 && $spvi <= 100) {
				$cost	=	($spvi*COST_FOR_10_TO_100_KW);
			} else if($spvi > 100 && $spvi <= 500) {
				$cost	=	($spvi*COST_FOR_100_TO_500_KW);
			} else if($spvi > 500 && $spvi <= 1000) {
				$cost	=	($spvi*COST_FOR_500_TO_1000_KW);
			} else if($spvi > 1000 && $spvi <= 10000) {
				$cost	=	($spvi*COST_FOR_1000_TO_10000_KW);
			} else {
				$cost	=	($spvi*COST_ABOVE_10000_KW);
			}
		}
		return $cost;
	}

	/**
	 * @name calculatecapitalcostwithsubsidy
	 * @uses getting Solar Tarif Data(Cost Solar with subsidy)
	 * @param $recommended_capacity, $cost
	 * @return $costwithsubsidy
	 * @author Khushal Bhalsod
	 */
	public function calculatecapitalcostwithsubsidy($recommendedSolarPvInstall='',$capitalCost='',$state="",$customertype="",$RArray=false,$social_consumer=0)
	{
		$subsidyData 		= array();
		$arrResult 			= array();
		$customertype 		= empty($customertype)?0:$customertype;
		$MStateSubsidy 		= TableRegistry::get('StateSubsidy');
		$MParameter 		= TableRegistry::get('Parameters');
		if (!ctype_digit($customertype)) {
			$returnArr = $MParameter->GetParaIdByName($customertype);
			if (!empty($returnArr)) {
				$para_ids 		= array_keys($returnArr);
				$customertype 	= $para_ids[0];
			}
		}
		$state 				= (strtolower($state) == "jarkhand")?"jharkhand":$state;
		if($social_consumer==0)
		{
			$subsidyData 		= $MStateSubsidy->getSubcidyDataByState(strtolower($state),$customertype);
		}
		else
		{
			$subsidyData 		= $this->SocialSubsidyArr;
		}
		
		$capitalCost 		= ($capitalCost * 100000);
		$subsidy 			= 0;
		$StateSubsidy 		= 0;
		$centralsubsidy 	= 0;
		$othersubsidy 		= 0;
		if(empty($recommendedSolarPvInstall) || empty($capitalCost)) return false;
		$costwithsubsidy 	= 0;
		$recommendedSolarPvInstall = ($recommendedSolarPvInstall>500) ? 500 : $recommendedSolarPvInstall;
		if($recommendedSolarPvInstall < 500 && !empty($subsidyData)) {
			if($subsidyData['state_subcidy_type'] == '0') {
				$arrResult['state_subsidy'] 		= $subsidyData['state_subsidy'];
				$subsidy = ($capitalCost * (($subsidyData['state_subsidy']/100)));
				if(!empty($subsidyData['state_capacity']) && $recommendedSolarPvInstall <= $subsidyData['state_capacity'] ) {
					$StateSubsidy 	= $subsidy + ($subsidyData['state_subsidy'] * ($recommendedSolarPvInstall/100));	
				} else {
					$StateSubsidy 	= ($capitalCost * (($subsidyData['state_subsidy']/100)));
				}
				$arrResult['state_subsidy_amount'] = $StateSubsidy;
			} else {
				if(!empty($subsidyData['state_capacity']) && $recommendedSolarPvInstall <= $subsidyData['state_capacity'] ) {
					$StateSubsidy 						= $subsidyData['state_subsidy'] * $recommendedSolarPvInstall;
				} else {
					$StateSubsidy 						= $subsidyData['state_subsidy'] * $subsidyData['state_capacity'];
				}
			}

			$arrResult['state_subcidy_type'] 	= $subsidyData['state_subcidy_type'];
			$arrResult['state_subsidy'] 		= $subsidyData['state_subsidy'];
			$arrResult['state_subsidy_amount'] 	= $StateSubsidy;

			if($subsidyData['central_subcidy_type'] == '0') {
				if(!empty($subsidyData['central_capacity']) && $recommendedSolarPvInstall <= $subsidyData['central_capacity'] ) {
					$centralsubsidy  = ($capitalCost * ($subsidyData['central_subsidy']/100));
				}
			} else {
				$centralsubsidy =  $subsidy + $subsidyData['central_subsidy'];
			}
			
			$arrResult['central_subcidy_type'] 		= $subsidyData['central_subcidy_type'];
			$arrResult['central_subsidy'] 			= $subsidyData['central_subsidy'];
			$arrResult['central_subsidy_amount'] 	= $centralsubsidy;

			if($subsidyData['other_subcidy_type']=='0') {
				$subsidy = ($capitalCost * (($subsidyData['other_subsidy']/100)));
				if(!empty($subsidyData['other_capacity']) && $recommendedSolarPvInstall <= $subsidyData['other_capacity'] ) {
					$othersubsidy	= $subsidy + ($subsidyData['other_subsidy'] * ($recommendedSolarPvInstall/100));
				} else {
					$othersubsidy 	= $subsidy + ($subsidyData['other_subsidy'] * (2/100));
				}
			} else {
				$othersubsidy =  $subsidy + $subsidyData['other_subsidy'];
			}

			$arrResult['other_subcidy_type'] 		= $subsidyData['other_subcidy_type'];
			$arrResult['other_subsidy'] 			= $subsidyData['other_subsidy'];
			$arrResult['other_subsidy_amount'] 		= $othersubsidy;
			$costwithsubsidy 				= $capitalCost - ($StateSubsidy + $centralsubsidy + $othersubsidy);
			$arrResult['total_cost'] 		= $capitalCost;
			$arrResult['total_subsidy'] 	= ($StateSubsidy + $centralsubsidy + $othersubsidy);
		} else {
			$costwithsubsidy 						= $capitalCost;
			$arrResult['state_subcidy_type'] 		= 0;
			$arrResult['state_subsidy'] 			= 0;
			$arrResult['state_subsidy_amount'] 		= 0;
			$arrResult['central_subcidy_type'] 		= 0;
			$arrResult['central_subsidy'] 			= 0;
			$arrResult['central_subsidy_amount'] 	= 0;
			$arrResult['other_subcidy_type'] 		= 0;
			$arrResult['other_subsidy'] 			= 0;
			$arrResult['other_subsidy_amount'] 		= 0;
			$arrResult['total_cost'] 				= $capitalCost;
			$arrResult['total_subsidy'] 			= 0;
			$arrResult['customertype'] 				= $customertype;
		}
		
		/*if ($_SERVER['REMOTE_ADDR'] == "203.88.138.46") {
			echo "<pre>";
			echo "State::".$state;
			echo "<br />";
			echo "State Subsidy::".$capitalCost;
			echo "<br />";
			echo "recommendedSolarPvInstall::".$recommendedSolarPvInstall;
			echo "<br />";
			echo "State Subsidy::".$StateSubsidy." <br />Central Subsidy::".$centralsubsidy." <br />Other Subsidy::".$othersubsidy;
			echo "<br />";
			echo "costwithsubsidy::".$costwithsubsidy;
			echo "<br />";
			print_r($arrResult);
			die;
		}
		*/
		if (!$RArray) {
			return $costwithsubsidy;
		} else {
			return $arrResult;
		}
	}

	/**
	 * @name getBillingChart
	 * @uses getting Solar Rediation Data as per logic
	 * @param float $lat ,float $val
	 * @return $rediationArr
	 * @author Pravin Sanghani
	 * @since 2015-11-19
	 */
	public function getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,$capitalCost,$backUpType,$usage_hours,$cost_electricty=0)
	{
		$years 				= 25;
		$breakEvenPeriod 	= 0;
		$capitalCost 		= (0-($capitalCost*100000));
		$firstMonthEnrgyGen = $averageEnrgyGenInYear/12;
		$solarChart 		= array();
		$conventionalScenarioArr = array();
		$fromPvSystem 		= array();
		$netEnergy 			= array();
		$cashFlows 			= array();
		$breakEvenAnalysis 	= array();
		$actualMonthlyCredit 	= 0;
		$contractLoad 		= round($contractLoad,2);
		
		if(empty($cost_electricty)){
			if(isset($backUpType) && $backUpType == $this->BACKUP_TYPE_GENERATOR) {
				$cost_electricty = $this->calculateGeneratorUsage($contractLoad, $usage_hours);
			} elseif(isset($backUpType) && $backUpType == $this->BACKUP_TYPE_INVERTER) {
				$cost_electricty = $this->calculateInverterUsage($contractLoad, $usage_hours);
			}	
		}
		
		for($i=1;$i<=$years;$i++)	
		{
			if($i==1) {
				$conventionalScenarioArr[$i]['loadFector']=LOAD_FECTORE;
				$fromPvSystem[$i]['monthlyEnergyGenerated']=round($firstMonthEnrgyGen);
				$fromPvSystem[$i]['yearlyEnergyGenerated']=round($firstMonthEnrgyGen*12);
			} else {
				$conventionalScenarioArr[$i]['loadFector']=round(($conventionalScenarioArr[$i-1]['loadFector']*(1+LOAD_FECTORE_INCREASE/100)), 2);
				$fromPvSystem[$i]['monthlyEnergyGenerated']=round($fromPvSystem[$i-1]['monthlyEnergyGenerated']-($fromPvSystem[$i-1]['monthlyEnergyGenerated']/100));
				$fromPvSystem[$i]['yearlyEnergyGenerated']=round($fromPvSystem[$i]['monthlyEnergyGenerated']*12);
			}
			$conventionalScenarioArr[$i]['unitsConsumed']=round(((($contractLoad*24*365)*$conventionalScenarioArr[$i]['loadFector']/100)/12));	
			$netEnergy[$i]['netMonthlyConsumption']=round($conventionalScenarioArr[$i]['unitsConsumed']-$fromPvSystem[$i]['monthlyEnergyGenerated']);
			$netEnergy[$i]['totalMonthlyBill']=round($netEnergy[$i]['netMonthlyConsumption']*$unitRate);
			$conventionalScenarioArr[$i]['totalMonthlyBill']=round($conventionalScenarioArr[$i]['unitsConsumed']*$unitRate+$cost_electricty);	
			$cashFlows[$i]['netMonthlyCashFlow']=round($actualMonthlyCredit-$netEnergy[$i]['totalMonthlyBill']);
			$cashFlows[$i]['monthlyFinancialSaving']=round($conventionalScenarioArr[$i]['totalMonthlyBill']+$cashFlows[$i]['netMonthlyCashFlow']);
			$cashFlows[$i]['annualFinancialSaving']=round($cashFlows[$i]['monthlyFinancialSaving']*12);
			if($i==1) {
				$breakEvenAnalysis[$i]['cumulativeFinancialSaving']=round($capitalCost+$cashFlows[$i]['annualFinancialSaving']);
				$breakEvenAnalysis[$i]['addFraction']=(($capitalCost < 0 )?(($breakEvenAnalysis[$i]['cumulativeFinancialSaving'] > 0 )?($breakEvenAnalysis[$i]['cumulativeFinancialSaving']/($breakEvenAnalysis[$i]['cumulativeFinancialSaving']-$capitalCost)):0):0);
			} else {
				$breakEvenAnalysis[$i]['cumulativeFinancialSaving']=round($breakEvenAnalysis[$i-1]['cumulativeFinancialSaving']+$cashFlows[$i]['annualFinancialSaving']);
				$breakEvenAnalysis[$i]['addFraction']=(($breakEvenAnalysis[$i-1]['cumulativeFinancialSaving'] < 0 )?(($breakEvenAnalysis[$i]['cumulativeFinancialSaving'] > 0 )?($breakEvenAnalysis[$i]['cumulativeFinancialSaving']/($breakEvenAnalysis[$i]['cumulativeFinancialSaving']-$breakEvenAnalysis[$i-1]['cumulativeFinancialSaving'])):0):0);
			}
			$breakEvenAnalysis[$i]['addYear']	=	(($breakEvenAnalysis[$i]['cumulativeFinancialSaving'] < 0 )?1:0);
			$breakEvenPeriod+=$breakEvenAnalysis[$i]['addYear'];
			$breakEvenPeriod+=$breakEvenAnalysis[$i]['addFraction'];
		}
			$solarChart['conventionalScenarioArr']=$conventionalScenarioArr;
			$solarChart['fromPvSystem']=$fromPvSystem;
			$solarChart['netEnergy']=$netEnergy;
			$solarChart['cashFlows']=$cashFlows;
			$solarChart['breakEvenAnalysis']=$breakEvenAnalysis;
			$solarChart['breakEvenPeriod']=$breakEvenPeriod;
			return $solarChart;
	}

	/**
	 * @name getTarifCalculation
	 * @uses getting Solar Tarif Data(Cost Solar)
	 * @param $year, $curYearEnergyGenerated, $avg_monthly_bill,$capitalCost 
	 * @return $result
	 * @author Khushal Bhalsod
	 */
	public function getTarifCalculation($year,$curYearEnergyGenerated,$avg_monthly_bill,$capitalCost)
	{
		$years						= $year;
		$curYearEnergyGenerated 	= $curYearEnergyGenerated;
		$capitalCost 				= $capitalCost;
		$tariff_value 				= array();
		$cog_value 					= array();
		$levelized_cog_value		= array();
		$result 					= array(); 

		$suminsuranceval			= 0;
		$cog_o_and_m_total			= 0;
		$discount_factor_total		= 0;
		$cog_depreciation_total 	= 0;
		$cog_insurance_cost_total	= 0;
		$cog_return_on_equity_total	= 0;
		$cog_tax_total				= 0;
		$cog_gross_cost_total		= 0;
		$cog_ad_tax_benifit_total	= 0;
		$cog_net_cost_total			= 0;

		########### Get interest loan ###############	
		$loanDataRes = $this->getInterestOnLoan($years,$capitalCost);
		
		for($i=1;$i<=$years;$i++) {
				######################################### TARIFF CALCULATION ######################################		
			if($i == 1) {
				$tariff_value[$i]['net_generation'] 					= $curYearEnergyGenerated;
				$tariff_value[$i]['o_and_m_expense'] 					= $capitalCost * (O_AND_M_COST/100);
				$tariff_value[$i]['insurance_cost']						= $capitalCost * (O_AND_M_COST/100);
				/* used this for calculate advanced tax benefit */
				$tariff_value[$i]['accelerated_opening_balance']   		= $capitalCost * 100000;
				$tariff_value[$i]['accelerated_depreciation_rate']  	= (RATE_OF_ACCELERATED_DEPRE/100); 
				$tariff_value[$i]['accelerated_depreciation_amount']	= $tariff_value[$i]['accelerated_opening_balance'] * $tariff_value[$i]['accelerated_depreciation_rate'];
				$tariff_value[$i]['accelerated_closing_balance'] 		= $tariff_value[$i]['accelerated_opening_balance'] - $tariff_value[$i]['accelerated_depreciation_amount'];
				$tariff_value[$i]['AD Benefit'] 						= $tariff_value[$i]['accelerated_depreciation_amount'] * (CORPORATE_TAX_RATE/100);
				/* used this for calculate advanced tax benefit */
			} else {
				$tariff_value[$i]['net_generation'] 					= $tariff_value[$i-1]['net_generation'] * (1-(ANNUAL_DEGREDATION/100));
				$tariff_value[$i]['o_and_m_expense'] 					= $tariff_value[$i-1]['o_and_m_expense'] * (1+(O_AND_M_ESCLATION/100));
				$suminsuranceval 										+= $tariff_value[$i-1]['insurance_cost']; 
				$tariff_value[$i]['insurance_cost']						= ($capitalCost-($suminsuranceval)) * (O_AND_M_COST/100);
				/* used this for calculate advanced tax benefit */
				$tariff_value[$i]['accelerated_opening_balance']   		= $tariff_value[$i-1]['accelerated_closing_balance'];
				$tariff_value[$i]['accelerated_depreciation_rate']  	= (RATE_OF_ACCELERATED_DEPRE/100); 
				$tariff_value[$i]['accelerated_depreciation_amount']	= $tariff_value[$i]['accelerated_opening_balance'] * $tariff_value[$i]['accelerated_depreciation_rate'];
				$tariff_value[$i]['accelerated_closing_balance'] 		= $tariff_value[$i]['accelerated_opening_balance'] - $tariff_value[$i]['accelerated_depreciation_amount'];
				$tariff_value[$i]['AD Benefit'] 						= $tariff_value[$i]['accelerated_depreciation_amount'] * (CORPORATE_TAX_RATE/100);
				/* used this for calculate advanced tax benefit */
			}
				$depreciationAmt 						= ((($i<=10)?RATE_DEPRECATION_FOR_10:RATE_DEPRECATION_NEXT_15)/100) * $capitalCost * 100000;
				$tariff_value[$i]['depreciation'] 		= $depreciationAmt/100000;
				$tariff_value[$i]['interest_on_loan']	= $loanDataRes[$i]['interest_on_loan'];
				$tariff_value[$i]['return_on_equity']	= $capitalCost * (1-(DEBT_FRATION/100))*(ROE/100);
				$tariff_value[$i]['tax']				= (($i<=ALTERNATE_TAX_RATE)?(($tariff_value[$i]['return_on_equity']*(MIN_ALTERNATE_TAX_RATE/100))/(1-(MIN_ALTERNATE_TAX_RATE/100))):(($tariff_value[$i]['return_on_equity']*(CORPORATE_TAX_RATE/100))/(1-(CORPORATE_TAX_RATE/100))));
				$tariff_value[$i]['gross_cost']			= $tariff_value[$i]['o_and_m_expense'] + $tariff_value[$i]['depreciation']+$tariff_value[$i]['interest_on_loan']+$tariff_value[$i]['insurance_cost']+$tariff_value[$i]['return_on_equity']+$tariff_value[$i]['tax'];
				$tariff_value[$i]['ad_tax_benifit']		= ($tariff_value[$i]['AD Benefit']/100000);
				$tariff_value[$i]['net_cost']			= $tariff_value[$i]['gross_cost']-$tariff_value[$i]['ad_tax_benifit'];
				
				######################################### Discount FACTOR #########################################
				$tariff_value[$i]['discount_factor']	= 1/(pow((1+(DISCOUNT_FACTOR/100)),($i-1)));

				######################################### Discounted COG ###########################################
				
				$cog_value[$i]['o_and_m_expense'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['o_and_m_expense'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['depreciation'] 			= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['depreciation'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['interest_on_loan'] 		= (($tariff_value[$i]['net_generation'] > 0)?($tariff_value[$i]['interest_on_loan'] * $tariff_value[$i]['discount_factor'] * 100000)/($tariff_value[$i]['net_generation']):0);
				$cog_value[$i]['insurance_cost'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['insurance_cost'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['return_on_equity'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['return_on_equity'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['tax'] 					= (($tariff_value[$i]['net_generation'] > 0)?($tariff_value[$i]['tax'] * $tariff_value[$i]['discount_factor'] * 100000)/($tariff_value[$i]['net_generation']):0);
				$cog_value[$i]['gross_cost'] 			= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['gross_cost'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['ad_tax_benifit'] 		= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['ad_tax_benifit'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
				$cog_value[$i]['net_cost'] 				= (($curYearEnergyGenerated > 0)?($tariff_value[$i]['net_cost'] * $tariff_value[$i]['discount_factor'] * 100000)/($curYearEnergyGenerated):0);
		
				######################################### Levelized COG ###########################################
				$cog_o_and_m_total 						+= $cog_value[$i]['o_and_m_expense'];
				$cog_depreciation_total 				+= $cog_value[$i]['depreciation'];
				$cog_insurance_cost_total 				+= $cog_value[$i]['insurance_cost'];
				$cog_return_on_equity_total 			+= $cog_value[$i]['return_on_equity'];
				$cog_tax_total 							+= $cog_value[$i]['tax'];
				$cog_gross_cost_total 					+= $cog_value[$i]['gross_cost'];
				$cog_ad_tax_benifit_total 				+= $cog_value[$i]['ad_tax_benifit'];
				$cog_net_cost_total 					+= $cog_value[$i]['net_cost'];
				$discount_factor_total 					+= $tariff_value[$i]['discount_factor'];  
		}

		$levelized_cog_value['o_and_m_expense']			= ($cog_o_and_m_total)/($discount_factor_total);
		$levelized_cog_value['depreciation']			= ($cog_depreciation_total)/($discount_factor_total);
		$levelized_cog_value['interest_on_loan'] 		= '';
		$levelized_cog_value['insurance_cost'] 			= ($cog_insurance_cost_total)/($discount_factor_total);
		$levelized_cog_value['return_on_equity'] 		= ($cog_return_on_equity_total)/($discount_factor_total);
		$levelized_cog_value['tax'] 					= ($cog_tax_total)/($discount_factor_total);
		$levelized_cog_value['gross_cog'] 				= ($cog_gross_cost_total)/($discount_factor_total);
		$levelized_cog_value['tax_benefit']				= ($cog_ad_tax_benifit_total)/($discount_factor_total);
		$levelized_cog_value['net_cog'] 				= ($cog_net_cost_total)/($discount_factor_total);

		$result['gross_cog'] 	= $levelized_cog_value['gross_cog'];
		$result['net_cog'] 		= $levelized_cog_value['net_cog'];
		return $result; 
	}

	/**
	 *
	 * genrateApiChartData
	 *
	 * Behaviour : private
	 *
	 * @defination : Method is use to calculate PV Area in Foot
	 *
	 */
	public function genrateApiChartData($fromPvSystem=array(), $monthChartDataArr,$grnrate=true)
	{
		$year			= date('Y');
		$yearChart		= array();	
		$monthChart		= array();
		$yearArr		= array();
		$monthArr		= array();
		$yearArr_FE 	= array();
		$monthArr_FE 	= array();
		if($grnrate) {
			foreach($fromPvSystem as $key=>$arrChartType)
			{
				if($key <= 12) {
					$monthDataVal 	= (isset($monthChartDataArr[$key])?round($monthChartDataArr[$key]):0);
					$monthArr[]		= "{'x':".$key.",'y':".$monthDataVal."}";
					$monthArr_FE[] 	= array("x"=>$key,"y"=>$monthDataVal);
				}	
				$yearArr[]=	"{'x':".$year.",'y':".$arrChartType['yearlyEnergyGenerated']."}";
				$yearArr_FE[] 	= array("x"=>$year,"y"=>$arrChartType['yearlyEnergyGenerated']);
				$year++;
			}
		}
		return array('yearChart'=>'['.implode(',',$yearArr).']',
					'yearArr_FE' => $yearArr_FE,'monthArr_FE' => $monthArr_FE,
					'monthChart'=>'['.implode(',',$monthArr).']');
	}

	/**
	 * @name getInterestOnLoan
	 * @uses getting interest on loan data.
	 * @param int $year ,float $capitalCost
	 * @return $arrResult
	 * @author Khushal Bhalsod
	 */
	public function getInterestOnLoan($years, $capitalCost)
	{
		$arrResult 	= array();		
		for($i=1;$i<=$years;$i++) {

			if($i == 1) {
				$arrResult[$i]['openinig_amount'] 		= (($capitalCost * (1-(CAPITAL_SUBSIDY/100))) * (DEBT_FRATION/100) * 100000);
				$arrResult[$i]['annual_principal_paid'] = ($i>MORATORIUM_PERIOD?($i<=(LOAN_TENURE+MORATORIUM_PERIOD)?$arrResult[$i]['openinig_amount']/(LOAN_TENURE):0):0);
				$arrResult[$i]['closing_balance'] 		= $arrResult[$i]['openinig_amount'] - $arrResult[$i]['annual_principal_paid'];
				$arrResult[$i]['annual_interest']	 	= ((($arrResult[$i]['openinig_amount']+$arrResult[$i]['closing_balance'])/2) * (INTEREST_RATE_ON_LOAN/100)); 
				$arrResult[$i]['interest_on_loan'] 		= ($arrResult[$i]['annual_interest']/100000);
			} else {
				$arrResult[$i]['openinig_amount'] 		= $arrResult[$i-1]['closing_balance'];
				$arrResult[$i]['annual_principal_paid'] = (($i>MORATORIUM_PERIOD)?(($i<=(LOAN_TENURE+MORATORIUM_PERIOD))?($arrResult[$i]['openinig_amount']/(LOAN_TENURE)):0):0);
				$arrResult[$i]['closing_balance'] 		= $arrResult[$i]['openinig_amount'] - $arrResult[$i]['annual_principal_paid'];
				$arrResult[$i]['annual_interest']	 	= ((($arrResult[$i]['openinig_amount']+$arrResult[$i]['closing_balance'])/2) * (INTEREST_RATE_ON_LOAN/100)); 
				$arrResult[$i]['interest_on_loan'] 		= ($arrResult[$i]['annual_interest']/100000);
			}
		}
		return $arrResult;
	}

	/**
	*
	* calculateInverterUsage
	*
	* Behaviour : public
	*
	* Parameter : $contractLoad
	*
	* Parameter : $usage_hours
	*
	* @defination : Method is used to calculate inverter usage.
	*
	*/
	public function calculateInverterUsage($contract_load=0, $usage_hours)
	{
		$cost_electricty 		= 0; 
		$usage_hours 			= (isset($usage_hours)?$usage_hours:0);
		$electricity_equivalent = (($usage_hours>0)?$usage_hours:0) * 0.5 * $contract_load;
		$cost_electricty_day 	= $electricity_equivalent * INVERTER_ELECTRICITY_COST;
		$cost_electricty 		= (($cost_electricty_day > 0)?($cost_electricty_day * 365/12):0);
		return $cost_electricty;
	}

	/**
	*
	* calculateMonthChartData
	*
	* Behaviour : public
	*
	* Parameter : $contractLoad
	*
	* Parameter : $usage_hours: 
	*
	* @defination : Method is used to calculate generator usage.
	*
	*/
	public function calculateGeneratorUsage($contract_load=0, $usage_hours)
	{
		$cost_electricty 		= 0; 
		$usage_hours 			= (isset($usage_hours)?$usage_hours:0);
		$electricity_equivalent = (($usage_hours>0)?$usage_hours:0) * 0.5 * $contract_load;
		$cost_electricty_day 	= $electricity_equivalent * GENERATOR_ELECTRICITY_COST;
		$cost_electricty 		= (($cost_electricty_day > 0)?($cost_electricty_day * 365/12):0);
		return $cost_electricty;
	}


	/**
	*
	* getMonthEnergyAndSavingData
	*
	* Behaviour : public
	*
	* Parameter : $solarRediationData,$energy_con
	*
	* Parameter : $solarPvInstall,$avg_month_bill
	*
	* @defination : Method is used to get month energy and month saving data.
	*
	*/
	public function getMonthEnergyAndSavingData($solarRediationData,$solarPvInstall,$avg_month_bill,$energy_con) 
	{
		$dataArr = array();
		if(!empty($solarRediationData)) { 
			for($i=1;$i<=12;$i++) {
				$keyName 		= strtolower(date('M', mktime(0, 0, 0, $i, 10)))."_glo";
				$monthGloVal 	= (isset($solarRediationData[$keyName])?$solarRediationData[$keyName]:0);
				$dataArr['energy_data'][$i] = ((((($solarPvInstall*$monthGloVal)*PERFORMANCE_RATIO)/100))*cal_days_in_month(CAL_GREGORIAN, $i, date("Y")));
			
				$averageEnrgyGenInDay   = (((($solarPvInstall*$solarRediationData[$keyName])*PERFORMANCE_RATIO)/100));
       			$montly_pv_generation   = ($averageEnrgyGenInDay * 30);
       			if(!empty($energy_con))
       			{
       				$dataArr['saving_data'][$i] = ($avg_month_bill - ($energy_con - $montly_pv_generation) * (($avg_month_bill/$energy_con)-0.5));	
       			} else {
       				$dataArr['saving_data'][$i] = 0;
       			}
       	 		
			}
		}
		return $dataArr;
	}

	/**
	*
	* getSolarRediationGHIChartData
	*
	* Behaviour : public
	*
	* Parameter : $lat,$long
	*
	* @defination : Method is used to get solar radiation GHI Chart Data.
	*
	*/
	public function getSolarRediationGHIChartData($lat=null,$long=null)
	{
		$GhiData = TableRegistry::get('GhiData');
		$ghidata = array();
		
		$ghidata = $GhiData->getGhiData($long,$lat);		
		$dataArr = array();
		if(!empty($ghidata)) {
			for($i=1;$i<=12;$i++) {
				$keyName = strtolower(date('M', mktime(0, 0, 0, $i, 10)))."_glo";
				$dataArr['radiation_ghi_data'][$i] 	= (isset($ghidata[$keyName])?$ghidata[$keyName]:0);
			}
		}
		return $dataArr;
	}

	/**
	*
	* GetPaybackChartData
	*
	* Behaviour : public
	*
	* Parameter : $estimated_cost,$yearly_saving
	*
	* @defination : Method is used to get payback chart data.
	*
	*/
	public function GetPaybackChartData($estimated_cost, $yearly_saving)
	{

		$year 			= date('Y');
		$yearly_payback	= array();
		$total_saving 	= 0;
		$estimated_cost = (isset($estimated_cost)?$estimated_cost * 100000:0);
		for($i=$year;$i<=($year+24);$i++) {
			$yearly_payback[$i] = $total_saving - $estimated_cost;
			$total_saving 	= $total_saving + $yearly_saving;
		}
		return $yearly_payback;
	}
	/**
	 *
	 * energyGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate energy graph.
	 *
	 */
	public function energyGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php');		
 		
		$ydata = (isset($gData['energy_data'])?array_values($gData['energy_data']):array());

		// Create the graph. 
		$graph = new \Graph(600,350,'auto');
		$graph->img->SetMargin(30,90,40,50);
		$graph->title->Set("Solar Energy Generation in kWh");
		$graph->SetScale("textlin");
		$graph->SetBox(false);

		//$graph->ygrid->SetColor('gray');
		$graph->ygrid->Show(false);
		$graph->ygrid->SetFill(false);
		
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

		// Create the bar plots
		$barplot = new \BarPlot($ydata);
		$graph->Add($barplot);
		$barplot->SetWeight(0);
		$barplot->SetFillGradient("#71BF57","#71BF57",GRAD_HOR);
		$barplot->SetWidth(17);

		// Display the graph
		$filepath = WWW_ROOT."/tmp/energy".time().".png";
		$graphData = $graph->Stroke($filepath); 
		return $filepath;
	}

	/**
	 *
	 * paybackGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate payback graph.
	 *
	 */
	public function paybackGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_scatter.php');		
 		
		$ydata = (isset($gData)?array_values($gData):array());
		$xdata = (isset($gData)?array_keys($gData):array());

		// Create the graph. 
		$graph = new \Graph(700,350,'auto');
		$graph->img->SetMargin(80,90,40,50);
		$graph->title->Set("");
		$graph->SetScale("intlin");
		$graph->SetShadow();
		$graph->SetBox(false);
		$graph->title->Set("Solar Payback");
		$graph->xaxis->SetPos("min"); 

		$graph->yaxis->SetLabelMargin(12);
		$graph->xaxis->SetLabelMargin(6);
		$graph->xaxis->SetTickLabels($xdata);
		$graph->yaxis->SetTickSide(SIDE_LEFT);
		$graph->xaxis->SetTickSide(SIDE_DOWN);
		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR);

		$lineplot = new \ScatterPlot($ydata);
		$lineplot->mark->SetType(MARK_SQUARE);
		$lineplot->mark->SetFillColor("#FFCB29");
		$lineplot->SetImpuls();
		$lineplot->SetColor("#71BF57");
		$lineplot->SetWeight(6);
		$lineplot->mark->SetWidth(10);
		$graph->Add($lineplot);
		
		// Display the graph
		$filepath = WWW_ROOT."/tmp/payback".time().".png";
		$graphData = $graph->Stroke($filepath);
		return $filepath;
	}

	/**
	 *
	 * radiationGraph
	 *
	 * Behaviour : Public
	 *	
	 * Parameter : gData(int)
	 *
	 * @defination : Method is use to generate solar radiation GHI graph.
	 *
	 */
	public function radiationGraph($gData = array())
	{
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph.php');
		require_once(ROOT . DS  . 'vendor' . DS  . 'jpgraph' . DS . 'src' . DS . 'jpgraph_bar.php');		
 		
 		$ydata = (isset($gData['radiation_ghi_data'])?array_values($gData['radiation_ghi_data']):array());
		// Create the graph. 
		$graph = new \Graph(550,350,'auto');
		$graph->img->SetMargin(30,90,40,50);
		$graph->title->Set("Monthly Solar Radiation GHI (kW h/m2)");
		$graph->SetScale("textlin");
		$graph->SetBox(false);

		//$graph->ygrid->SetColor('gray');
		$graph->ygrid->Show(false);
		$graph->ygrid->SetFill(false);
		
		$graph->yaxis->HideLine(false);
		$graph->yaxis->HideTicks(false,false);

		// For background to be gradient, setfill is needed first.
		$graph->SetBackgroundGradient('#FFFFFF', '#FFFFFF', GRAD_HOR, BGRAD_PLOT);

		// Create the bar plots
		$barplot = new \BarPlot($ydata);
		$graph->Add($barplot);
		$barplot->SetWeight(0);
		$barplot->SetFillGradient("#71BF57","#71BF57",GRAD_HOR);
		$barplot->SetWidth(17);

		// Display the graph
		$filepath = WWW_ROOT."/tmp/radiation".time().".png";
		
		$graphData = $graph->Stroke($filepath);

		return $filepath;
	}

	public function projectByStateCount($state = '',$fromDate='',$toDate='') 
	{
		if(empty($fromDate) && empty($toDate)) {
			return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = Projects.state or states.statename = Projects.state']],
				'conditions'=>['states.id' => $state]
				])->count();
		} else {
			$StartTime  = date("Y-m-d",strtotime($fromDate))." 00:00:00";
            $EndTime    = date("Y-m-d",strtotime($toDate))." 23:59:59";

			return $this->find('all',['join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = Projects.state or states.statename = Projects.state'],
						'Parameters'=>[
                                    'table' => 'parameters',
                                    'type' => 'INNER',
                                    'conditions' => 'Parameters.para_id = Projects.customer_type'],
                        'CustomerProjects'=>[
                                    'table' => 'customer_projects',
                                    'type' => 'INNER',
                                    'conditions' => 'CustomerProjects.project_id = Projects.id'],
                        'Customers'=>[
                                    'table' => 'customers',
                                    'type' => 'INNER',
                                    'conditions' => 'Customers.id = CustomerProjects.customer_id'],        
                        ],
				'conditions'=>['states.id' => $state,'Projects.created BETWEEN :start AND :end']
				])->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->count();
		}
	}

	public function projectByState($state = '',$fromDate ='',$toDate = '') 
	{
		$StartTime  = date("Y-m-d",strtotime($fromDate))." 00:00:00";
        $EndTime    = date("Y-m-d",strtotime($toDate))." 23:59:59";

		return $this->find('all',[
			'fields'=>['Projects.id','Projects.name','Customers.mobile','Projects.city','Parameters.para_value'],
			'join'=>[['table'=>'states','type'=>'left','conditions'=>'states.id = Projects.state or states.statename = Projects.state'],
						'Parameters'=>[
                                    'table' => 'parameters',
                                    'type' => 'INNER',
                                    'conditions' => 'Parameters.para_id = Projects.customer_type'],
                        'CustomerProjects'=>[
                                    'table' => 'customer_projects',
                                    'type' => 'INNER',
                                    'conditions' => 'CustomerProjects.project_id = Projects.id'],
                        'Customers'=>[
                                    'table' => 'customers',
                                    'type' => 'INNER',
                                    'conditions' => 'Customers.id = CustomerProjects.customer_id'],        
                        ],
			'conditions'=>['states.id' => $state,'Projects.created BETWEEN :start AND :end'],
				'limit'=>'10',
				'order'=>'Projects.id'
			])->bind(':start', $StartTime, 'date')->bind(':end',   $EndTime, 'date')->toArray();
	}

	public function getTypewiseProjects($state = '')
    {
    	$resultArray        = array();
        $TypewiseProjects   = $this->find();
        $TypewiseProjects->hydrate(false);
        $TypewiseProjects->select(['Parameters.para_value','count' => $TypewiseProjects->func()->count('Projects.id')])->group('Projects.customer_type');
        $TypewiseProjects->join([['table'=>'states',
								'type'=>'left',
								'conditions'=>'states.id = Projects.state or states.statename = Projects.state'],
        						['table' => 'parameters',
	                            'alias' => 'Parameters',
	                            'type' => 'INNER',
	                            'conditions' => 'Parameters.para_id = Projects.customer_type',
                                ]])->where(['states.id' => $state]);
        $arrResult = $TypewiseProjects->toList();

        if (!empty($arrResult)) {
            foreach ($arrResult as $Row) {
                $resultArray[$Row['Parameters']['para_value']] = $Row['count'];
            }
        }
        return $resultArray;
    }

    public function getProjectHistoryCurrentYear($state = '')
    {
        $Projects = $this->find();
        $Projects->hydrate(false);
        $Projects->select(['Project_Month' => 'MONTH(Projects.created)','count' => $Projects->func()->count('Projects.id')])->group('Project_Month');
        $Projects->join([['table'=>'states',
						'type'=>'INNER',
						'conditions'=>'states.id = Projects.state or states.statename = Projects.state']]);
        $Projects->where(function ($exp,$q) {
            $StartTime  = date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
            $EndTime    = date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
            return $exp->between('Projects.created', $StartTime, $EndTime);
        });
        $resultArray = $Projects->toList();
        if (!empty($resultArray)) {
            $arrResult = array();
            foreach ($resultArray as $resultRow) {
                $MONTH              = strtoupper(date("M",strtotime(date("Y",strtotime($this->NOW()))."-".$resultRow['Project_Month']."-01 00:00:00")));
                $arrResult[$MONTH]  = $resultRow['count'];
            }
            $resultArray = $arrResult;
        }
        return $resultArray;
    }

	public function ProjectForInstaller($customer_id=0,$project_id=0)
	{
		$Customer = TableRegistry::get('Customers');
		$customerData = $Customer->find('all', array('conditions'=>array('id'=>$customer_id)))->first();
		if (!empty($customerData) && isset($customerData['installer_id']) && !empty($customerData['installer_id']))
		{
			$InstallerProjects 	= TableRegistry::get('InstallerProjects');
			$arrCondition 		= array('installer_id'=>$customerData['installer_id'],
										"project_id"=>$project_id);
			$count 				= $InstallerProjects->find("all",array('conditions'=>$arrCondition))->first();
			if (empty($count))
			{
				$dataProject['InstallerProjects']['installer_id']		= $customerData['installer_id'];
				$dataProject['InstallerProjects']['project_id']			= $project_id;
				$dataProject['InstallerProjects']['contact_code']		= 'SELF';
				$dataProject['InstallerProjects']['status']				= 4002;
				$dataProject['InstallerProjects']['contacted_status']	= 1;
				if(!empty($project_id)) {
					$InstallerProjectsEntity = $InstallerProjects->newEntity($dataProject);
					$InstallerProjectsEntity->created = date("Y-m-d H:i:s");
					$InstallerProjects->save($InstallerProjectsEntity);	
				}
			}
		}
	}

	/**
	 *
	 * getprojectestimationV2
	 * Behaviour : public
	 * @defination : Method is use to add new admin user and provide specific righs using admin interface
	 */
	public function getprojectestimationV2($requestData=array(),$customer_id='',$SaveProject=false)
	{
		$requestData['Projects']['name'] 				= (!empty($requestData['proj_name'])?$requestData['proj_name']:'');
		$requestData['Projects']['energy_con'] 			= (!empty($requestData['energy_con'])?$requestData['energy_con']:0);
		$requestData['Projects']['area_type']			= (!empty($requestData['area_type'])?$requestData['area_type']:0);
		$requestData['Projects']['area']				= (!empty($requestData['area'])?$requestData['area']:0);	
		$requestData['Projects']['latitude']			= (!empty($requestData['latitude'])?$requestData['latitude']:0);
		$requestData['Projects']['longitude']			= (!empty($requestData['longitude'])?$requestData['longitude']:0);
		$requestData['Projects']['avg_monthly_bill']	= (isset($requestData['avg_monthly_bill'])?$requestData['avg_monthly_bill']:0);
		$requestData['Projects']['backup_type']			= (!empty($requestData['backup_type'])?$requestData['backup_type']:0);
		$requestData['Projects']['usage_hours'] 		= (!empty($requestData['usage_hours'])?$requestData['usage_hours']:0);
		$requestData['Projects']['customer_type']		= (!empty($requestData['project_type'])?$requestData['project_type']:0);
		$requestData['Projects']['address']				= (!empty($requestData['address'])?$requestData['address']:'');
        $requestData['Projects']['project_source']		= (!empty($requestData['project_source'])?$requestData['project_source']:0);

		$energy_con 									= $requestData['energy_con'];
		$area_type 										= $requestData['area_type'];
		$area 											= $requestData['area'];	
		$latitude 										= $requestData['latitude'];
		$longitude										= $requestData['longitude'];
		$avg_month_bill									= $requestData['avg_monthly_bill'];
		$backup_type									= $requestData['backup_type'];
		$usage_hours									= $requestData['usage_hours'];
		$project_type									= $requestData['project_type'];
		$solarPenalArea 								= 0;
		$monthly_saving									= 0;
		$address										= (isset($requestData['address'])?$requestData['address']:'');
		$city											= (isset($requestData['city'])?$requestData['city']:'');
		$state											= 'Gujarat';//(isset($requestData['state'])?$requestData['state']:'');
		$state_short_name								= 'GJ';//(isset($requestData['state_short_name'])?$requestData['state_short_name']:'');
		$country										= (isset($requestData['country'])?$requestData['country']:'');
		$pincode										= (isset($requestData['postal_code'])?$requestData['postal_code']:0);
		$recommended_capacity 							= isset($requestData['recommended_capacity'])?$requestData['recommended_capacity']:0;
		if($area_type == $this->AREA_TYPE_FOOT) {
			$solarPenalArea	= $this->calculatePvInFoot($area);
		} elseif($area_type == $this->AREA_TYPE_METER) { 
			$solarPenalArea	= $this->calculatePvInMeter($area);	
		}
		if (!empty($latitude) && !empty($longitude)) {
			$locationdata 									= GetLocationByLatLong($latitude,$longitude);	
			$requestData['Projects']['address']				= (isset($locationdata['address'])?$locationdata['address']:$address);
			$requestData['Projects']['city']				= (isset($locationdata['city'])?$locationdata['city']:$city);
			$requestData['Projects']['state']				= 'Gujarat';//(isset($locationdata['state'])?$locationdata['state']:$state);
			$state 											= $requestData['Projects']['state'];
			$requestData['Projects']['state_short_name']	= 'GJ';//(isset($locationdata['state_short_name'])?$locationdata['state_short_name']:$state_short_name);
			$requestData['Projects']['country']				= (isset($locationdata['country'])?$locationdata['country']:$country);
			$requestData['Projects']['pincode']				= (isset($locationdata['postal_code'])?$locationdata['postal_code']:$pincode);
		} else {
			$requestData['Projects']['address']				= $address;
			$requestData['Projects']['city']				= $city;
			$requestData['Projects']['state']				= $state;
			$requestData['Projects']['state_short_name']	= $state_short_name;
			$requestData['Projects']['country']				= $country;
			$requestData['Projects']['pincode']				= $pincode;
		}
		/** get location information */
		
		if(!empty($requestData['Projects']['id'])) {
			$projectsData 									= $this->get($requestData['Projects']['id']);
			$requestData['Projects']['address']				= (!empty($requestData['Projects']['address'])?$requestData['Projects']['address']:$projectsData->address);
			$requestData['Projects']['city']				= (!empty($requestData['Projects']['city'])?$requestData['Projects']['city']:$projectsData->city);
			$requestData['Projects']['state']				= (!empty($requestData['Projects']['state'])?$requestData['Projects']['state']:$projectsData->state);
			$state											= (!empty($requestData['Projects']['state'])?$requestData['Projects']['state']:$projectsData->state);
			$requestData['Projects']['state_short_name']	= (!empty($requestData['Projects']['state_short_name'])?$requestData['Projects']['state_short_name']:$projectsData->state_short_name);
			$requestData['Projects']['country']				= (!empty($requestData['Projects']['country'])?$requestData['Projects']['country']:$projectsData->country);
			$requestData['Projects']['pincode']				= (!empty($requestData['Projects']['pincode'])?$requestData['Projects']['pincode']:$projectsData->pincode);
		}
		/** get location information */
	
		$solarPvInstall 									= ceil($solarPenalArea/12);
		$solarRediationData									= $this->getSolarRediation($latitude,$longitude);
		$annualTotalRad										= ($solarRediationData['ann_glo']*365);
		$requestData['Projects']['solar_radiation'] 		= $annualTotalRad; 
		$contractLoad 										= round(((($energy_con*12)/((24*365*LOAD_FECTORE)/100))));
		$capacityAcualEnrgyCon								= round(((($energy_con*12)/$annualTotalRad)));

		if (empty($recommended_capacity)) {
			$recommendedSolarPvInstall 							= min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
			$highRecommendedSolarPvInstall 						= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
			$requestData['Projects']['maximum_capacity'] 		= max($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
			$requestData['Projects']['recommended_capacity'] 	= $recommendedSolarPvInstall;
			$requestData['Projects']['capacity_kw'] 			= $recommendedSolarPvInstall;
		} else {
			$recommendedSolarPvInstall 							= $recommended_capacity;
			$requestData['Projects']['maximum_capacity'] 		= $recommended_capacity;
			$requestData['Projects']['recommended_capacity'] 	= $recommended_capacity;
			$requestData['Projects']['capacity_kw'] 			= $recommended_capacity;
			$highRecommendedSolarPvInstall 						= $recommended_capacity;
		}
		$averageEnrgyGenInDay 								= (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));
		$monthChartDataArr									= $this->calculateMonthChartData($solarRediationData,$recommendedSolarPvInstall);
		$capitalCost 										= $this->calculatecapitalcost($recommendedSolarPvInstall,$requestData['Projects']['state'],$requestData['Projects']['customer_type']);
		$requestData['Projects']['estimated_cost'] 			= $capitalCost;
		$calculate_subsidy 									= $capitalCost;
		if(!empty($requestData['Projects']['id'])) {
			$ApplyOnlines   		= TableRegistry::get('ApplyOnlines');
			$ApplyOnlineData 		= $ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$requestData['Projects']['id'])))->first();
			if(!empty($ApplyOnlineData) && $ApplyOnlineData->disclaimer_subsidy==1 && SHOW_SUBSIDY_EXECUTION==1)
			{
				$calculate_subsidy 	= 0;
			}
		}
		$capitalCostsubsidy 								= $this->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$calculate_subsidy,$requestData['Projects']['state'],$requestData['Projects']['customer_type']);
		$SubsidyDetailInfo 									= $this->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$calculate_subsidy,$requestData['Projects']['state'],$requestData['Projects']['customer_type'],true);
		$requestData['Projects']['estimated_cost_subsidy'] 	= $capitalCostsubsidy;
		$averageEnrgyGenInYear								= round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100));
		
		/* Calculate saving */
		$montly_pv_generation 	= ($averageEnrgyGenInDay * 30);
		if(!empty($energy_con) && !empty($avg_month_bill)) { 
			$monthly_saving 		= ($avg_month_bill - ($energy_con - $montly_pv_generation) * (($avg_month_bill/$energy_con)-0.5)); 
		}
		/* Calculate saving */
		$cost_solar				= 0.0;
		$unitRate 				= 0;	
		if(!empty($avg_month_bill) && !empty($avg_month_bill) && (!empty($energy_con))) {
			$unitRate				= (($avg_month_bill/$energy_con)-0.5);
		}

		$solarChart 									= $this->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,($capitalCostsubsidy/100000),$backup_type,$usage_hours);
		$payBack 										= (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);
		$requestData['Projects']['payback'] 			= $payBack;  
		$fromPvSystem 									= (isset($solarChart['fromPvSystem'])?$solarChart['fromPvSystem']:array());
		$gross_solar_cost								= $this->getTarifCalculation(25,$fromPvSystem[1]['yearlyEnergyGenerated'],$avg_month_bill,$capitalCost);	
		$cost_solar										= $gross_solar_cost['net_cog'];
		$requestData['Projects']['estimated_cost'] 		= $capitalCost;	
		$requestData['Projects']['cost_solar'] 			= $cost_solar;	
		$requestData['Projects']['avg_generate']		= $averageEnrgyGenInYear;
		$requestData['Projects']['estimated_kwh_year']	= $energy_con;
		$chart											= $this->genrateApiChartData($fromPvSystem, $monthChartDataArr);	
		$averageEnrgyGenInMonth							= ($averageEnrgyGenInYear/12);
		$payBackGraphData 								= $this->GetPaybackChartData(round(($capitalCostsubsidy/100000),2), ($monthly_saving*12));
		$solar_ratio									= (($energy_con > 0)?(($averageEnrgyGenInMonth/$energy_con) * 100):0);
		if($payBackGraphData){
			foreach ($payBackGraphData as $key => $value) {
				$savingChartArr[] 		= "{'x':".$key.",'y':".round($value)."}";
			}
		}

		if(!empty($customer_id) && $SaveProject==true) {
			if(!empty($requestData['Projects']['id'])) {
				$projectsEntity 				= $this->patchEntity($projectsData,$requestData);
				$projectsEntity->modified 		= $this->NOW();	
				$projectsEntity->modified_by 	= $customer_id;
			} else {
				$projectsEntity 				= $this->newEntity($requestData['Projects']);
				$projectsEntity->created 		= $this->NOW();
				$projectsEntity->created_by 	= $customer_id;
			}
			$dataProject = array();
			if(!empty($customer_id)) {
				if ($this->save($projectsEntity)) {
					$dataProject['CustomerProjects']['customer_id']	= $customer_id;
					$dataProject['CustomerProjects']['project_id']	= $projectsEntity->id;
					$CustomerProjects = TableRegistry::get('CustomerProjects');	
					if(empty($requestData['Projects']['id'])) {
						$customerProjectsEntity = $CustomerProjects->newEntity($dataProject);
						$customerProjectsEntity->created = $this->NOW();
						$CustomerProjects->save($customerProjectsEntity);	
					}
					$customerTable 		= ($requestData['ses_login_type'] == 'developer') ? 'DeveloperCustomers' : 'Customers';
					$CustomersTable 	= TableRegistry::get($customerTable);
					$customerdata       = $CustomersTable->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$customer_id)))->first();
		            $user_roles = explode(",",$customerdata->user_role);
		            $Parameters = TableRegistry::get('Parameters');
		            if (in_array($Parameters->bd_role,$user_roles) && (!in_array($Parameters->admin_role,$user_roles))) 
		            {
		            	$dataProjectBD 										= array();
 						$dataProjectBD['ProjectAssignBd']['projects_id'] 	= $projectsEntity->id;
 						$dataProjectBD['ProjectAssignBd']['customers_id']	= $customer_id;
                        $dataProjectBD['ProjectAssignBd']['created_by']		= $customer_id;
                        $ProjectAssignBd 									= TableRegistry::get('ProjectAssignBd');
                        $assign 											= $ProjectAssignBd->newEntity($dataProjectBD);
                        $assign->created 									= $this->NOW();
                        $ProjectAssignBd->save($assign);
                    }
					/** Save Entry into installer_projects if customer is installer */
					$this->ProjectForInstaller($customer_id,$projectsEntity->id);
					/** Save Entry into installer_projects if customer is installer */
				}
			}
		}	
		$status						= 'ok';
		$messege					= array(); 
		$messege 					= array_merge($messege,$chart);
		$messege['name'] 			= $project_type;
		$messege['is_residential'] 	= $project_type;
		$messege['paybackChart']	= '['.implode(',',$savingChartArr).']';
		$messege['cost_solar']		= (isset($cost_solar)?$cost_solar:0);
		$messege['saving_month']	= _FormatGroupNumberV2($monthly_saving);
		$messege['proj_id']			= isset($projectsEntity->id) ? $projectsEntity->id : '';
		$messege['capacity']		= ($recommendedSolarPvInstall > 10)?floor($recommendedSolarPvInstall):number_format($recommendedSolarPvInstall,1);
		$messege['highcapacity']	= round($highRecommendedSolarPvInstall);
		$messege['est_cost']		= $capitalCost;
		$messege['est_cost_subsidy']= round(($capitalCostsubsidy/100000),2);
		$messege['avg_gen']			= round($averageEnrgyGenInMonth,2);
		$messege['payback']			= round($payBack,2);
		$messege['solar_ratio'] 	= ($solar_ratio > 100)?'100':round($solar_ratio);
		$messege['project_state'] 	= $state;
		$messege['SubsidyDetail'] 	= $SubsidyDetailInfo;
		return $messege;
	}
	public function validationTab4(Validator $validator)
	{   
		$validator->notEmpty('proj_name', 'Please enter value for project name.');
	    $validator->notEmpty('area', 'Please enter numeric value for rooftop area.')->add('area', [
            'validFormat'=>[
                'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
                'message' => 'Numbers only'
            ]
        ]);
	    $validator->add("area", [
	        "_empty" => [
	        "rule" => [$this, "custom_project_zero"],
	        "message" => "Rooftop area must be greater than zero."
	            ]
	        ]
	    );
        $validator->notEmpty('avg_monthly_bill', 'Please enter numeric value for average monthly bill.')->add('avg_monthly_bill', [
            'validFormat'=>[
                'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
                'message' => 'Numbers only'
            ]
        ]);
        $validator->add("avg_monthly_bill", [
	        "_empty" => [
	        "rule" => [$this, "custom_project_zero"],
	        "message" => "Average monthly bill must be greater than zero."
	            ]
	        ]
	    );
        $validator->notEmpty('energy_con', 'Please enter numeric value for monthly unit consumed.')->add('energy_con', [
            'validFormat'=>[
                'rule' => array('custom', '/(^([0-9.]+)(\d+)?$)/'),
                'message' => 'Numbers only'
            ]
        ]);
        $validator->add("energy_con", [
	        "_empty" => [
	        "rule" => [$this, "custom_project_zero"],
	        "message" => "Monthly unit consumed must be greater than zero."
	            ]
	        ]
	    );
	    $validator->notEmpty('latitude', 'Please enter latitude.')->add('latitude', [
            'validFormat'=>[
                'rule' => array('custom', '/(^[-]?([0-9.]+)(\d+)?$)/'),
                'message' => 'Numbers only'
            ]
        ]);
        $validator->notEmpty('longitude', 'Please enter longitude.')->add('longitude', [
            'validFormat'=>[
                'rule' => array('custom', '/(^[-]?([0-9.]+)(\d+)?$)/'),
                'message' => 'Numbers only'
            ]
        ]);
        
        if(isset($this->data_post['project_social_consumer']) && $this->data_post['project_social_consumer']=='1')
        {
        	$validator->add("project_social_consumer", [
                "_empty" => [
                "rule" => [$this, "custom_capacity_validation"],
                "message" => "PV capacity exeeding limit."
                    ]
                ]
        );
        }
        else
        {
        	$validator->add("project_type", [
                "_empty" => [
                "rule" => [$this, "custom_capacity_validation"],
                "message" => "PV capacity exeeding limit."
                    ]
                ]
        );
        }
        $ApplyOnlines 	= TableRegistry::get('ApplyOnlines');
        
        if(IS_CAPTIVE_OPEN == 1)
        {
        	//&& empty($this->data_post['project_social_consumer'])
        	if(($this->data_post['project_type'] == $ApplyOnlines->category_industrial || $this->data_post['project_type'] == $ApplyOnlines->category_ht_indus || $this->data_post['project_type'] == $ApplyOnlines->category_commercial || $this->data_post['project_type'] == $ApplyOnlines->category_others) )
	        {
	        	if($this->data_post['project_renewable_attr'] == '')
	        	{
	        		//$validator->notEmpty('project_renewable_attr', 'Please check at-least one.');
	        	}
	        }
	        // && empty($this->data_post['project_social_consumer'])
	        if($this->data_post['project_renewable_attr'] == '0' && $this->data_post['project_renewable_rec'] == '')
        	{
        		//$validator->notEmpty('project_renewable_rec', 'Please check at-least one.');
        	}
        	/*if((!isset($this->data_post['project_social_consumer']) || $this->data_post['project_social_consumer']=='0') && $this->data_post['project_type']==$ApplyOnlines->category_others && NON_RES_SOCIAL_SECTOR==1)
	        {
	        	$validator->add("project_social_consumer", [
	                "_empty" => [
	                "rule" => [$this, "custom_project_zero"],
	                "message" => "Please check social consumer."
	                    ]
	                ]
	        );
	        }*/
        }
        else
        {
        	if((!isset($this->data_post['project_social_consumer']) || $this->data_post['project_social_consumer']=='0') && $this->data_post['project_type']!=$ApplyOnlines->category_residental && NON_RES_SOCIAL_SECTOR==1)
	        {
	        	$validator->add("project_social_consumer", [
	                "_empty" => [
	                "rule" => [$this, "custom_project_zero"],
	                "message" => "Please check social consumer."
	                    ]
	                ]
	        );
	        }
        }
	    return $validator;
	}
	public function custom_project_zero($value, $context)
    {
    	if($value>0)
    	{
    		return true;
    	}
    	return false;
    }
    /**
    *
    * custom_capacity_validation
    *
    * Behaviour : public
    *
    * Parameter : capacity
    *
    * @defination : Method is used to check installer Category A (Max. Under Residential: 93.50 MW Max. Under Social Sector: 16.50 MW) Category B (Max. Under Residential: 12.75 MW Max. Under Social Sector: 2.25 MW)
    *
    */
    public function custom_capacity_validation($value, $context)
    {
        if(isset($this->data_post['ins_id']) && !empty($this->data_post['ins_id']))
        {
        	return $this->checked_total_capacity_installer($this->data_post['ins_id'],$value);
        }
        return true;
        //return false;
    }
     /**
    *
    * checked_total_capacity_installer
    *
    * Behaviour : public
    *
    * Parameter : capacity
    *
    * @defination : Method is used to check installer Category A (Max. Under Residential: 93.50 MW Max. Under Social Sector: 16.50 MW) Category B (Max. Under Residential: 12.75 MW Max. Under Social Sector: 2.25 MW)
    *
    */
    public function checked_total_capacity_installer($installer_id,$value=0)
    {
    	//return STOP_ADD_APPLICATION_MSG;
        //echo $this->data['ApplyOnlines']['installer_id'];
        if(isset($installer_id) && !empty($installer_id) && (isset($this->data_post['project_disclaimer_subsidy']) && $this->data_post['project_disclaimer_subsidy']==0))
        {
        	$installer_id           = $installer_id;
            $apply_approval_table   = TableRegistry::get('ApplyOnlineApprovals');
            $ApplyOnlines   		= TableRegistry::get('ApplyOnlines');
            $applyOnline            = $this->find();
            if((isset($this->data_post['project_social_consumer']) && $this->data_post['project_social_consumer']=='1'))
            {
                $where_query        = " and apply_onlines.social_consumer='1' ";
            }
            else
            {
                $where_query        = " and apply_onlines.social_consumer='0' ";
            }
            $cur_pv_capacity        = "0";
            
            $connection             = ConnectionManager::get('default');
            $arrApplication         = array();

            $ApplicationID          = "SELECT SUM(apply_onlines.pv_capacity) as total_pv_capacity,((CASE WHEN 1=1 THEN(
                                SELECT ic.capacity
                                FROM installer_category_mapping as icm left join installer_category as ic on icm.category_id=ic.id
                                WHERE installer_id=$installer_id
                ) END)) as capacity,((CASE WHEN 1=1 THEN(
                                SELECT ic.id
                                FROM installer_category_mapping as icm left join installer_category as ic on icm.category_id=ic.id
                                WHERE installer_id=$installer_id
                ) END)) as id from apply_onlines
                WHERE apply_onlines.id In (SELECT apply_onlines.id FROM `apply_onlines`
                LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id
                WHERE apply_onlines.installer_id in (select installer_id from installer_category_mapping where category_id=(select category_id from installer_category_mapping  where installer_id='".$installer_id."'))  AND apply_online_approvals.stage IN ('".$apply_approval_table->APPLICATION_SUBMITTED."') GROUP by apply_online_approvals.application_id) $where_query";

            $installerData              = $connection->execute($ApplicationID)->fetchAll('assoc');
            $total_MW_capacity          = $installerData[0]['total_pv_capacity'];
            $InstallerCategoryMapping   = TableRegistry::get('InstallerCategoryMapping');
            
            $allocated_capacity         = $installerData[0]['capacity'];
            $allocated_id               = $installerData[0]['id'];
            //echo $allocated_id;
            $flag_full_social           = 0;
            $flag_full_residential      = 0;
            if(!empty($total_MW_capacity) && $total_MW_capacity>0 && $allocated_id!=3)
            {
                $capacity_allowed   = ($allocated_capacity*SOCIAL_CAT_PER)/100;
                if(($total_MW_capacity) > $capacity_allowed)
                {
                	if(isset($this->data_post['project_social_consumer']) && $this->data_post['project_social_consumer']=='1')
		            {
		            	return 'Your PV capacity for social consumer quota has been finished. Kindly contact GEDA office for further detail.';
		            }
		            else
		            {
		            	$flag_full_social=1;
		            }
                }
                
            	$capacity_allowed           = ($allocated_capacity*RESIDENTIAL_CAT_PER)/100;
                if(($total_MW_capacity) > $capacity_allowed)
                {
                	$flag_full_residential = 1;
		           	return 'Your PV capacity for residential quota has been finished. Kindly contact GEDA office for further detail.';

                }  
            }
            if($allocated_id==2 && $flag_full_residential==0)
            {
                $ApplicationID          = "SELECT SUM(apply_onlines.pv_capacity) as total_pv_capacity from apply_onlines 
                WHERE apply_onlines.id In (SELECT apply_onlines.id FROM `apply_onlines`
                LEFT JOIN apply_online_approvals ON apply_online_approvals.application_id = apply_onlines.id
                WHERE apply_onlines.installer_id in ('".$installer_id."') AND apply_online_approvals.stage IN ('".$apply_approval_table->APPLICATION_SUBMITTED."') GROUP by apply_online_approvals.application_id) and category='".$ApplyOnlines->category_residental."'";
                $installerData          = $connection->execute($ApplicationID)->fetchAll('assoc');
                $total_MW_capacity      = $installerData[0]['total_pv_capacity'];
                if(!empty($total_MW_capacity) && $total_MW_capacity>0)
                {
                    $capacity_allowed       = RESIDENTIAL_TOTAL_CAPACITY;
                    if(($total_MW_capacity) >= $capacity_allowed && strtotime(DATE_STOP_1_1_3) < strtotime(date('Y-m-d H:i:s')))
                    {
                    	if(isset($this->data_post['project_type']) && $this->data_post['project_type']==$ApplyOnlines->category_residental)
			            {
			            	return 'Your PV capacity for Residential quota has been finished. Kindly contact GEDA office for further detail.';
			            }
			            else
			            {
			            	$flag_full_residential=1;
			            } 
                    }
                }
            }

            

            if($flag_full_residential==1 && $flag_full_social==1)
            {
                return 'Your PV capacity final quota has been finished. Kindly contact GEDA office for further detail.';
            }
        }
        return true;
        //return false;
    }
}
?>