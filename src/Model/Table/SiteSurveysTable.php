<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class SiteSurveysTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
    var $AREA_PARAMS              = ['0' => '', '' => '', '2001' => 'm<sup>2</sup>', '2002' => 'ft<sup>2</sup>'];
    var $AREA_PARAMS_SMP          = ['0' => '', '' => '', '2001' => 'm', '2002' => 'ft'];
    var $LOAD_PARAMS              = ['0' => 'kW', '1' => 'kVA'];
    var $ALL_ROOF_TYPE            = ['0' => '', '' => '', '1' => 'Flat RCC', '2' => 'Tilt Roof', '3' => 'Sheet Metal', '4' => 'Other'];
    var $ALL_ROOF_STRENGTH        = ['0' => '', '' => '', '1' => 'Yes, roof is strong enough to support PV module & MMS', '2' => 'Maybe, structural validation would be required', '3' => 'No, roof is not strong enough'];
    var $ALL_METER_TYPE           = ['0' => '', '' => '', '1' => '3-, 4-wire, CT-operated', '2' => '3-, 4-wire, Direct-connected', '3' => '1-, 2-wire', '4' => 'Other'];
    var $ALL_METER_ACCURACY_CLASS = ['0' => '', '' => '', '1' => '1s', '2' => '0.5s', '3' => '0.2s', '4' => 'Other'];
    var $ALL_BILLING_CYCLE        = ['0' => '', '' => '', '1' => 'Monthly', '2' => 'Bi-monthly'];
    var $ALL_CUSTOMER_TYPE        = ['0' => '', '3001' => 'Residential', '3002' => 'Industrial', '3003' => 'Commercial', '3004' => 'Goverment', '3005' => 'Non-profit educational institute', '3006' =>'Social sectors like hospital'];
	var $data  = array();
    var $data_entity = array();
	public $Name 	= 'SiteSurveys';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'project_survey';

    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }


    /**
     *
     * GetProjectSurveyStep1Data
     *
     * Behaviour : public
     *
     * @defination : Method is use to get survey step 1 data.
     *
     */
    public function GetProjectSurveyStep1Data($params = array())
    {
        $resultArr = array();

        if(empty($params['project_id']) && !empty($params['installer_id']) && !empty($params['building_id'])) {
            return $resultArr;
        }
        
        $CustomerTable  = TableRegistry::get('Customers');
        $customerData   = $CustomerTable->findByInstallerId($params['installer_id'])->first();

        $ProjectTable  = TableRegistry::get('Projects');
        $projectData   = $ProjectTable->findById($params['project_id'])->first();

        $query = $this->find('all');
        $siteSurveyData = $query->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id']])->first();
        
        $resultArr['building_id']         = (!empty($siteSurveyData->building_id)?$siteSurveyData->building_id:1);
        $resultArr['building_name']       = (!empty($siteSurveyData->building_name)?$siteSurveyData->building_name:'');
        $resultArr['surveyer_name']       = (!empty($siteSurveyData->surveyer_name)?$siteSurveyData->surveyer_name:(isset($customerData->name)?$customerData->name:''));
        $resultArr['contact_name']        = (!empty($siteSurveyData->contact_name)?$siteSurveyData->contact_name:""); 
        $resultArr['designation']         = (!empty($siteSurveyData->designation)?$siteSurveyData->designation:"");
        $resultArr['address1']            = (!empty($siteSurveyData->address1)?$siteSurveyData->address1:(isset($projectData->address)?$projectData->address:''));
        $resultArr['address2']            = (!empty($siteSurveyData->address2)?$siteSurveyData->address2:(isset($projectData->city)?$projectData->city:''));
        $resultArr['address3']            = (!empty($siteSurveyData->address3)?$siteSurveyData->address3:(isset($projectData->state)?$projectData->state:''));
        $resultArr['mobile']              = (!empty($siteSurveyData->mobile)?$siteSurveyData->mobile: (isset($customerData->mobile)?$customerData->mobile:''));
        $resultArr['landline']            = (!empty($siteSurveyData->landline)?$siteSurveyData->landline:"");
        $resultArr['email_id']            = (!empty($siteSurveyData->email_id)?$siteSurveyData->email_id:(isset($customerData->email)?$customerData->email:''));
        $resultArr['notes1']              = (!empty($siteSurveyData->notes1)?$siteSurveyData->notes1:"");
        $resultArr['site_lat']            = (!empty($siteSurveyData->site_lat)?$siteSurveyData->site_lat:"");
        $resultArr['site_log']            = (!empty($siteSurveyData->site_log)?$siteSurveyData->site_log:"");
        $resultArr['user_lat']            = (!empty($siteSurveyData->user_lat)?$siteSurveyData->user_lat:"");
        $resultArr['user_log']            = (!empty($siteSurveyData->user_log)?$siteSurveyData->user_log:"");
        return $resultArr;
    }

    /**
     *
     * GetProjectSurveyStep2Data
     *
     * Behaviour : public
     *
     * @defination : Method is use to get survey step 1 data.
     *
     */
    public function GetProjectSurveyStep2Data($params = array())
    {
        $resultArr = array();

        if(empty($params['project_id']) && !empty($params['installer_id']) && !empty($params['building_id'])) {
            return $resultArr;
        }
        
        $SurveyImages   = TableRegistry::get('SiteSurveysImages');       
        $query          = $this->find('all');
        $siteSurveyData = $query->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id']])->first();
        
        $resultArr['building_id']           = (!empty($siteSurveyData->building_id)?$siteSurveyData->building_id:1);
        $resultArr['roof_type']             = (!empty($siteSurveyData->roof_type)?$siteSurveyData->roof_type:0);
        $resultArr['other_roof_type']       = (!empty($siteSurveyData->other_roof_type)?$siteSurveyData->other_roof_type:"");
        $resultArr['roof_strenght']         = (!empty($siteSurveyData->roof_strenght)?$siteSurveyData->roof_strenght:0);
        $resultArr['overall']               = (!empty($siteSurveyData->overall)?$siteSurveyData->overall:'');
        $resultArr['is_overall']            = (!empty($siteSurveyData->is_overall)?$siteSurveyData->is_overall:0);
        $resultArr['shadow_free']           = (!empty($siteSurveyData->shadow_free)?$siteSurveyData->shadow_free:'');
        $resultArr['is_shadow_free']        = (!empty($siteSurveyData->is_shadow_free)?$siteSurveyData->is_shadow_free:0);
        $resultArr['roof_accessible']       = (!empty($siteSurveyData->roof_accessible)?$siteSurveyData->roof_accessible:'');
        $resultArr['road_to_site']          = (!empty($siteSurveyData->road_to_site)?$siteSurveyData->road_to_site:0);
        $resultArr['ladder_to_roof']        = (!empty($siteSurveyData->ladder_to_roof)?$siteSurveyData->ladder_to_roof:0);
        $resultArr['age_of_building']       = (!empty($siteSurveyData->age_of_building)?$siteSurveyData->age_of_building:'');
        $resultArr['azimuth']               = (!empty($siteSurveyData->azimuth)?$siteSurveyData->azimuth:'');
        $resultArr['inclination_of_roof']   = (!empty($siteSurveyData->inclination_of_roof)?$siteSurveyData->inclination_of_roof:'');
        $resultArr['object_on_roof']        = (!empty($siteSurveyData->object_on_roof)?$siteSurveyData->object_on_roof:'');
        $resultArr['height_of_parapet']     = (!empty($siteSurveyData->height_of_parapet)?$siteSurveyData->height_of_parapet:'');
        $resultArr['is_height_of_parapet']  = (!empty($siteSurveyData->is_height_of_parapet)?$siteSurveyData->is_height_of_parapet:0);
        $resultArr['floor_below_tarrace']   = (!empty($siteSurveyData->floor_below_tarrace)?$siteSurveyData->floor_below_tarrace:'');
        $resultArr['dc_cabel_distance']     = (!empty($siteSurveyData->dc_cabel_distance)?$siteSurveyData->dc_cabel_distance:'');
        $resultArr['is_dc_cable_distance']  = (!empty($siteSurveyData->is_dc_cable_distance)?$siteSurveyData->is_dc_cable_distance:0);
        $resultArr['notes2']                = (!empty($siteSurveyData->notes2)?$siteSurveyData->notes2:'');
        
        $resultArr['inverter_count']        = $SurveyImages->find('all')->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id'],'type'=>'place_inverter'])->count();
        $resultArr['battery_count']         = $SurveyImages->find('all')->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id'],'type'=>'place_battery'])->count();
        $resultArr['acdb_count']            = $SurveyImages->find('all')->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id'],'type'=>'place_for_ac_distribution_box'])->count();
        $resultArr['metering_count']        = $SurveyImages->find('all')->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id'],'type'=>'metering_box'])->count();
        $resultArr['take_photograph_count'] = $SurveyImages->find('all')->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id'],'type'=>'take_photographs'])->count();
        
        $resultArr['inverter_img_limit']        = $SurveyImages->INVERTER_IMG_LIMIT;
        $resultArr['battery_img_limit']         = $SurveyImages->BATTERY_IMG_LIMIT;
        $resultArr['acdb_img_limit']            = $SurveyImages->ACDB_IMG_LIMIT;
        $resultArr['metering_img_limit']        = $SurveyImages->METERING_IMG_LIMIT;
        $resultArr['take_photograph_img_limit'] = $SurveyImages->TAKE_PHOTOGRAPH_IMG_LIMIT;

        return $resultArr;
    }

     /**
     *
     * GetProjectSurveyStep3Data
     *
     * Behaviour : public
     *
     * @defination : Method is use to get survey step 3 data.
     *
     */
    public function GetProjectSurveyStep3Data($params = array())
    {
        $resultArr = array();
        $reading_details    = array("ReadingDetails"=>array(array("b_phase_bn"=>"","r_phase_ry"=>"","y_phase"=>"","r_phase_rn"=>"","y_phase_yb"=>"","r_phase"=>"","b_phase"=>"","y_phase_yn"=>"","b_phase_rb"=>"")));
        $genset_details     = array("GensetDetails"=>array(array("kva"=>"","hours"=>""))); 
        $inverter_details   = array("InverterDetails"=>array(array("kva"=>"","hours"=>"")));
        
        if(empty($params['project_id']) && !empty($params['installer_id']) && !empty($params['building_id'])) {
            return $resultArr;
        }
        
        $SurveyImages   = TableRegistry::get('SiteSurveysImages');   
        $query          = $this->find('all');
        $siteSurveyData = $query->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id']])->first();
        
        $resultArr['building_id']                 = (!empty($siteSurveyData->building_id)?$siteSurveyData->building_id:1);
        $resultArr['voltage_pahse_level']         = (!empty($siteSurveyData->voltage_pahse_level)?$siteSurveyData->voltage_pahse_level:'');
        $resultArr['measured_frequency']          = (!empty($siteSurveyData->measured_frequency)?$siteSurveyData->measured_frequency:'');
        $resultArr['avg_diesel_consumption']      = (!empty($siteSurveyData->avg_diesel_consumption)?$siteSurveyData->avg_diesel_consumption:'');
        $resultArr['critical_load']               = (!empty($siteSurveyData->critical_load)?$siteSurveyData->critical_load:'');
        $resultArr['approx_power_consumed']       = (!empty($siteSurveyData->approx_power_consumed)?$siteSurveyData->approx_power_consumed:'');
        $resultArr['working_day_week']            = (!empty($siteSurveyData->working_day_week)?$siteSurveyData->working_day_week:'');
        $resultArr['notes3']                      = (!empty($siteSurveyData->notes3)?$siteSurveyData->notes3:'');
        $resultArr['reading_details']             = (!empty($siteSurveyData->reading_details)?unserialize($siteSurveyData->reading_details):$reading_details);
        $resultArr['genset_details']              = (!empty($siteSurveyData->genset_details)?unserialize($siteSurveyData->genset_details):$genset_details);
        $resultArr['inverter_details']            = (!empty($siteSurveyData->inverter_details)?unserialize($siteSurveyData->inverter_details):$inverter_details);
        
        $resultArr['electricity_bill_count']     = $SurveyImages->find('all')->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id'],'type'=>'electricity_bill'])->count();
        $resultArr['electricity_bill_img_limit'] = $SurveyImages->ELECTRICITY_BILL_IMG_LIMIT;
        return $resultArr;
    }

    /**
     *
     * GetProjectSurveyStep4Data
     *
     * Behaviour : public
     *
     * @defination : Method is use to get survey step 4 data.
     *
     */
    public function GetProjectSurveyStep4Data($params = array())
    {
        $resultArr = array();

        if(empty($params['project_id']) && !empty($params['installer_id']) && !empty($params['building_id'])) {
            return $resultArr;
        }
        
        $query = $this->find('all');
        $siteSurveyData = $query->where(['project_id'=>$params['project_id'],'installer_id'=>$params['installer_id'],'building_id'=>$params['building_id']])->first();
        
        $resultArr['building_id']         =(!empty($siteSurveyData->building_id)?$siteSurveyData->building_id:1);
        $resultArr['distribution_company']=(!empty($siteSurveyData->distribution_company)?$siteSurveyData->distribution_company:'');
        $resultArr['customer_no']         = (!empty($siteSurveyData->customer_no)?$siteSurveyData->customer_no:'');
        $resultArr['meter_type']          = (!empty($siteSurveyData->meter_type)?$siteSurveyData->meter_type:0);
        $resultArr['meter_accuracy']      = (!empty($siteSurveyData->meter_accuracy)?$siteSurveyData->meter_accuracy:0);
        $resultArr['customer_type']       = (!empty($siteSurveyData->customer_type)?$siteSurveyData->customer_type:0);
        $resultArr['sanctioned_load']     = (!empty($siteSurveyData->sanctioned_load)?$siteSurveyData->sanctioned_load:'');
        $resultArr['is_snaction']         = (!empty($siteSurveyData->is_snaction)?$siteSurveyData->is_snaction:0);
        $resultArr['contract_demand']     = (!empty($siteSurveyData->contract_demand)?$siteSurveyData->contract_demand:'');
        $resultArr['is_contract']         = (!empty($siteSurveyData->is_contract)?$siteSurveyData->is_contract:0);
        $resultArr['billing_cycle']       = (!empty($siteSurveyData->billing_cycle)?$siteSurveyData->billing_cycle:0);
        $resultArr['fixcharges_upto']     = (!empty($siteSurveyData->fixcharges_upto)?$siteSurveyData->fixcharges_upto:'');
        $resultArr['fixcharges_upto_rs']  = (!empty($siteSurveyData->fixcharges_upto_rs)?$siteSurveyData->fixcharges_upto_rs:'');
        $resultArr['fxbetween1_from']     = (!empty($siteSurveyData->fxbetween1_from)?$siteSurveyData->fxbetween1_from:'');
        $resultArr['fxbetween1_to']       = (!empty($siteSurveyData->fxbetween1_to)?$siteSurveyData->fxbetween1_to:'');
        $resultArr['fxbetween1_to_rs']    = (!empty($siteSurveyData->fxbetween1_to_rs)?$siteSurveyData->fxbetween1_to_rs:'');
        $resultArr['fxbetween2_from']     = (!empty($siteSurveyData->fxbetween2_from)?$siteSurveyData->fxbetween2_from:'');
        $resultArr['fxbetween2_to']       = (!empty($siteSurveyData->fxbetween2_to)?$siteSurveyData->fxbetween2_to:'');
        $resultArr['fxbetween2_to_rs']    = (!empty($siteSurveyData->fxbetween2_to_rs)?$siteSurveyData->fxbetween2_to_rs:'');
        $resultArr['fxmorethen']          = (!empty($siteSurveyData->fxmorethen)?$siteSurveyData->fxmorethen:'');
        $resultArr['fxmorethen_rs']       = (!empty($siteSurveyData->fxmorethen_rs)?$siteSurveyData->fxmorethen_rs:'');
        $resultArr['eccharges_upto']      = (!empty($siteSurveyData->eccharges_upto)?$siteSurveyData->eccharges_upto:'');
        $resultArr['eccharges_upto_rs']   = (!empty($siteSurveyData->eccharges_upto_rs)?$siteSurveyData->eccharges_upto_rs:'');
        $resultArr['ecbetween1_from']     = (!empty($siteSurveyData->ecbetween1_from)?$siteSurveyData->ecbetween1_from:'');
        $resultArr['ecbetween1_to']       = (!empty($siteSurveyData->ecbetween1_to)?$siteSurveyData->ecbetween1_to:'');
        $resultArr['ecbetween1_to_rs']    = (!empty($siteSurveyData->ecbetween1_to_rs)?$siteSurveyData->ecbetween1_to_rs:'');
        $resultArr['ecbetween2_from']     = (!empty($siteSurveyData->ecbetween2_from)?$siteSurveyData->ecbetween2_from:'');
        $resultArr['ecbetween2_to']       = (!empty($siteSurveyData->ecbetween2_to)?$siteSurveyData->ecbetween2_to:'');
        $resultArr['ecbetween2_to_rs']    = (!empty($siteSurveyData->ecbetween2_to_rs)?$siteSurveyData->ecbetween2_to_rs:'');
        $resultArr['ecmorethen']          = (!empty($siteSurveyData->ecmorethen)?$siteSurveyData->ecmorethen:'');
        $resultArr['ecmorethen_rs']       = (!empty($siteSurveyData->ecmorethen_rs)?$siteSurveyData->ecmorethen_rs:'');
        $resultArr['fuel_charges']        = (!empty($siteSurveyData->fuel_charges)?$siteSurveyData->fuel_charges:'');
        $resultArr['electricity_duty']    = (!empty($siteSurveyData->electricity_duty)?$siteSurveyData->electricity_duty:'');
        $resultArr['other_surcharges1']   = (!empty($siteSurveyData->other_surcharges1)?$siteSurveyData->other_surcharges1:'');
        $resultArr['other_surcharges2']   = (!empty($siteSurveyData->other_surcharges2)?$siteSurveyData->other_surcharges2:'');
        $resultArr['other_rebate']        = (!empty($siteSurveyData->other_rebate)?$siteSurveyData->other_rebate:'');
        $resultArr['notes4']              = (!empty($siteSurveyData->notes4)?$siteSurveyData->notes4:'');
        $resultArr['month_details']       = (!empty($siteSurveyData->month_details)?unserialize($siteSurveyData->month_details):'');
        return $resultArr;
    }

    /**
     *
     * GetProjectSiteSurveyData
     *
     * Behaviour : public
     *
     * @defination : Method use to get survey step data.
     *
     */
    public function GetProjectSiteSurveyData($param = array())
    {
        $result         = array();
        $requestData    = (isset($param['post_data'])?$param['post_data']:array());
       
        $params['installer_id']  = (!empty($param['customer_id'])?$param['customer_id']:0);
        $params['project_id']    = (!empty($requestData['proj_id'])?$requestData['proj_id']:0);
        $params['building_id']   = (!empty($requestData['building_id'])?$requestData['building_id']:0);
        $survey_step            = (!empty($requestData['survey_step'])?$requestData['survey_step']:0);
    
        if(!empty($survey_step) && $survey_step == 1) { 
            $result = $this->GetProjectSurveyStep1Data($params);
        } else if(!empty($survey_step) && $survey_step == 2) {
            $result = $this->GetProjectSurveyStep2Data($params);
        } else if(!empty($survey_step) && $survey_step == 3) {
            $result = $this->GetProjectSurveyStep3Data($params);  
        } else if(!empty($survey_step) && $survey_step == 4) {
            $result = $this->GetProjectSurveyStep4Data($params);  
        }
        return ["status"=>1,"result"=>$result];
    }

     /**
     *
     * GetCustomerDetail
     *
     * Behaviour : public
     *
     * @defination : Method use to get customer detail
     *
     */
    public function GetCustomerDetail($param = array())
    {
        $result         = array();
        $customerId     = (!empty($param['customer_id'])?$param['customer_id']:0);
       
        $CustomerTable = TableRegistry::get('Customers');
        $customerData   = $CustomerTable->findById($customerId)->first();
        $result['building_id']          = '';
        $result['building_name']        = '';
        $result['surveyer_name']        = (!empty($customerData->name)?$customerData->name:""); 
        $result['contact_name']         = '';
        $result['designation']          = '';
        $result['address1']             = '';
        $result['address2']             = '';
        $result['address3']             = '';
        $result['mobile']               = '';
        $result['landline']             = '';
        $result['email_id']             = '';
        $result['notes1']               = '';
        return ["status"=>1,"result"=>$result];
    }           

    /**
     *
     * AddExistingProjectSiteSurveyData
     *
     * Behaviour : public
     *
     * @defination : Method use to add project site survey data.
     *
     */
    public function AddExistingProjectSiteSurveyData($param = array())
    {
        $result         = array();
        $requestData    = (isset($param['post_data'])?$param['post_data']:array());
       
        $customerId     = (!empty($param['customer_id'])?$param['customer_id']:0);
        $projectId      = (!empty($requestData['proj_id'])?$requestData['proj_id']:0);
        $buildingId     = (!empty($requestData['building_id'])?$requestData['building_id']:0);

        if(empty($projectId)) {
            return ["status"=>0,"msg"=>'Invalid Request'];
        }

        if(empty($customerId)) {
            return ["status"=>0,"msg"=>'Invalid Request'];
        }

        if(empty($buildingId)) {
            return ["status"=>0,"msg"=>'Invalid Request'];
        }

        if(!empty($requestData['SiteSurveys']['id'])) {
            unset($requestData['SiteSurveys']['id']);
        }

        $siteSurvey = $this->find('all')
                            ->where(['project_id'=>$projectId,'installer_id'=>$customerId,'building_id'=>$buildingId])
                            ->first();
        $siteSurveyid   = (isset($siteSurvey['id'])?$siteSurvey['id']:0);                       

        /*Save project survey*/
        if(!empty($siteSurveyid)) { 
            $siteSurveyData     = $this->get($siteSurveyid);
            $surveysEntity      = $this->patchEntity($siteSurveyData,$requestData);
            $surveysEntity->modified = $this->NOW();
        } else { 
            $surveysEntity = $this->newEntity($requestData);
            $surveysEntity->created = $this->NOW();
        }
        $surveysEntity->project_id      = $projectId;
        $surveysEntity->installer_id    = $customerId;
        $surveysEntity->building_id     = $buildingId;
        
        $result['proj_id'] = $projectId;
        if($this->save($surveysEntity)) {
            return ["status"=>1,"msg"=>'Project site survey save successfully.',"result"=>$result];
        }
        return ["status"=>0,"msg"=>'Error in save project site survey.',"result"=>$result];
    }

    /**
     *
     * GetTechnoCommercialData
     *
     * Behaviour : public
     *
     * @defination : Method used to get techno commercial data.
     *
     */
     public function GetTechnoCommercialData($param = array())
    {
        $result         = array();
        $customerId     = (!empty($param['customer_id'])?$param['customer_id']:0);
        $projectId      = (!empty($param['project_id'])?$param['project_id']:0);
        $Projects       = TableRegistry::get('Projects');
        if(empty($projectId)) {
            return ["status"=>0,"msg"=>'Invalid Request'];
        }

        if(empty($customerId)) {
            return ["status"=>0,"msg"=>'Invalid Request'];
        }
        // Start cost electricity calculation.        
        $surveyArr = $this->find('all')->where(['project_id'=>$projectId, 'installer_id'=>$customerId])->toArray();
        $cost_electricty    = 0;
        $bill               = array();
        $energy_con         = array();
        $energy_con_detail  = 0;
        $bill_detail        = 0;
        if(!empty($surveyArr)) {
            $inverterRes    = 0;
            $gensetRes      = 0; 
            foreach ($surveyArr as $key => $surveyData) {
               
                $inverterData = (!empty($surveyData['inverter_details']) ? unserialize($surveyData['inverter_details']):'');
               
                if(!empty($inverterData)) {
                    foreach ($inverterData['InverterDetails'] as $key => $value) {
                        if(!empty($value['kva'] && $value['hours']))
                        {
                            $inverterRes += $Projects->calculateInverterUsage($value['kva'], $value['hours']);
                        }
                    }
                }

                $gensetData = (!empty($surveyData['genset_details']) ? unserialize($surveyData['genset_details']):'');
                if(!empty($gensetData)) {
                    foreach ($gensetData['GensetDetails'] as $key => $value) {
                        if(!empty($value['kva'] && $value['hours']))
                        {
                            $gensetRes += $Projects->calculateGeneratorUsage($value['kva'], $value['hours']);
                        }
                    }
                }                
                if(isset($surveyData['month_details']) && !empty($surveyData['month_details'])){
                    $billDetail     =  unserialize($surveyData['month_details']);  
                    $billDetail     = $billDetail['ElectricityBillDetails'];
                    foreach ($billDetail as $key => $value) {
                        $value = (array) $value;
                        $bill[] = $value['bill_amount'];
                        $energy_con[] = $value['power_consume'];
                    }
                }
                
            }
            
            $cost_electricty = $inverterRes + $gensetRes;
            
            $bill_detail = 0;
            $energy_con_detail = 0;
            
            if(count($bill) > 0){
                $bill_detail = (array_sum($bill)/count($bill));
            }
            
            if(count($energy_con) > 0){
                $energy_con_detail = (array_sum($energy_con)/count($energy_con));
            }
        }
        // End cost electricity calculation.  

        $query = $this->find('all');
        $commercialData  = $query->select(['recommended_capacity'=> $query->func()->avg('recommended_capacity'),'estimated_cost'=>$query->func()->avg('estimated_cost'),'avg_generate'=>$query->func()->avg('avg_generate'),'cost_solar'=>$query->func()->avg('cost_solar'),'payback'=>$query->func()->avg('payback'),'avg_monthly_bill'=>$query->func()->avg('avg_monthly_bill'),'avg_energy_consumption'=>$query->func()->avg('avg_energy_consumption'),'overall'=>$query->func()->avg('overall'),'is_overall','customer_type'])
                                ->group("project_id")
                                ->where(['project_id'=>$projectId, 'installer_id'=>$customerId])
                                ->first();

        $result['customer_type']                = (!empty($commercialData->customer_type)?$commercialData->customer_type:0);
        $result['overall']                      = (!empty($commercialData->overall)?$commercialData->overall:0);
        $result['is_overall']                   = (!empty($commercialData->is_overall)?$commercialData->is_overall:0);
        $result['avg_energy_consumption']       = (!empty($commercialData->avg_energy_consumption)?$commercialData->avg_energy_consumption:0);
        $result['avg_monthly_bill']             = (!empty($commercialData->avg_monthly_bill)?$commercialData->avg_monthly_bill:0);
        $result['recommended_capacity']         = (!empty($commercialData->recommended_capacity)?$commercialData->recommended_capacity:0);
        $result['estimated_cost']               = (!empty($commercialData->estimated_cost)?$commercialData->estimated_cost:0);
        $result['avg_generate']                 = (!empty($commercialData->avg_generate)?$commercialData->avg_generate:0);
        $result['cost_solar']                   = (!empty($commercialData->cost_solar)?$commercialData->cost_solar:0);
        $result['payback']                      = (!empty($commercialData->payback)?$commercialData->payback:0);
        $result['cost_electricty']              = (!empty($cost_electricty)?$cost_electricty:0);
        $result['cost_solar_energy_benifit']    = '';
        $result['energy_con_detail']            = $energy_con_detail;
        $result['bill_detail']                  = $bill_detail;
        $result['fin_incentive']                = '';
        return ["status"=>1,"result"=>$result];

    }
	/**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationtab1(Validator $validator)
    {   
        $validator->notEmpty('building_name', 'Building name can not be blank.');
        $validator->notEmpty('contact_name', 'Contact person can not be blank.');
        $validator->notEmpty('mobile', 'Mobile can not be blank.');
        $validator->notEmpty('email_id', 'Email can not be blank.');
        $validator->add("email_id", "validFormat", [
            "rule" => ["email", false],
            "message" => "Email must be valid."
        ]);
        return $validator;
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationtab2(Validator $validator)
    {   

        if($this->data['roof_type'] == '0')
        {
            $validator->add("roof_type", [
                    "_empty" => [
                        "rule" => [$this, "customFunction"],
                        "message" => "Type of roof must be select."
                    ]
                        ]
                );
        }
        $validator->notEmpty('overall', 'Overall can not be blank.');
        $validator->notEmpty('shadow_free', 'Shadow free can not be blank.');

        if($this->data['overall'] < $this->data['shadow_free']){
            $validator->add("shadow_free", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Shadow free area should be less than area."
                    ]
                ]
            );
        }

        return $validator;
    }

    public function customlessFunction($value, $context){
        return false;
    }
    public function customFunction($value, $context) {
        
        if($value == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationtab4(Validator $validator)
    {   
        //equalTo( mixed $check , mixed $comparedTo )
       // $validator->notEmpty('customer_type', 'Customer can not be blank.');
        $validator->add("customer_type", [
                    "_empty" => [
                        "rule" => [$this, "customFunction"],
                        "message" => "Type of customer must be select."
                    ]
                        ]
                );
        return $validator;
    }

}
?>