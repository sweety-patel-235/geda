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

use Cake\Event\Event;

use App\Controller\AppController;
use Dompdf\Dompdf;
use Cake\Core\Configure;
use Cake\View\View;
use Cake\View\Helper;
use Cake\View\Helper\MyUtils;
use Cake\Utility\Hash;
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

class SubsidyTable extends AppTable
{
	var $table 			= 'apply_onlines_subsidy';
	var $dataPass 		= array();
	var $SUBCATEGORY_NGO= '14';
	var $SPIN_APPROVAL  = ['342' => array(	'no'		=> '318/20/2018-GCRT',
											'year'		=> '2017-2018',
											'capacity'	=> '33000'
										),
						   '357' => array(	'no'		=> '318/20/2018-GCRT (Part-1)',
						   					'year'		=> '2017-2018',
						   					'capacity'	=> '95000'
						   				)
							];
	public function initialize(array $config)
    {
        $this->table($this->table);
        //$this->loadHelper('MyUtils');
    }
    /**
     *
     * validationTab1
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate project detail tab (tab1)
     *
     */
	public function validationTab1(Validator $validator)
	{   
		$ApplyOnlinesTable 	= TableRegistry::get('ApplyOnlines');
		$applicationDetails = $ApplyOnlinesTable->viewApplication($this->dataPass['application_id']);
		$validator->notEmpty('category', 'Category must be select.');
		$validator->notEmpty('subcategory', 'Subcategory must be select.');
		$validator->notEmpty('district', 'District must be select.');
		$validator->notEmpty('consumer_mobile', 'Consumer mobile can not be blank.');
		$validator->notEmpty('pincode', 'Pincode can not be blank.');
		if(!empty($this->dataPass['subcategory']) && $this->dataPass['subcategory']==$this->SUBCATEGORY_NGO)
		{
			$validator->notEmpty('ngo_id', 'NGO id is required.');
			$validator->notEmpty('ngo_pan', 'NGO pan is required.');
			if(strlen($this->dataPass['ngo_id']) != 15)
            {
                $validator->add("ngo_id", [
					"_empty" => [
					"rule" => [$this, "customlessFunction"],
					"message" => "NGO id must be a 15 digits."
					]
                ]);
            }
            if(strlen($this->dataPass['ngo_pan']) != 10)
            {
                $validator->add("ngo_pan", [
					"_empty" => [
					"rule" => [$this, "customlessFunction"],
					"message" => "NGO Pan must be a 10 digits."
					]
                ]);
            }
		}

		if(empty($this->dataPass['Applyonlinprofile']) && empty($this->dataPass['profile_image']))
		{
			$validator->notEmpty('profile_image', 'Profile photo file required.');
		}
		elseif(isset($this->dataPass['profile_image']['name']) && !empty($this->dataPass['profile_image']['name']))
		{
			
			$SpinWebserviceApi 	= TableRegistry::get('SpinWebserviceApi');
			$mimeType 			= $SpinWebserviceApi->get_mime_type($this->dataPass['profile_image']['tmp_name']);
			if($mimeType == 'image/png')
			{
				$validator->add("profile_image", [
		            "_empty" => [
		                "rule" => [$this, "customlessFunction"],
		                "message" => 'Not valid mime type - '.$mimeType.'.'
		            ]
		        ]);
			}
			
		}
		if($this->dataPass['consumer_mobile'] == $applicationDetails->installer_mobile)
        {
	        $validator->add("consumer_mobile", [
	            "_empty" => [
	                "rule" => [$this, "customlessFunction"],
	                "message" => "Consumer mobile can not be same as installer mobile."
	            ]
	        ]);
        }
        if(strlen($this->dataPass['consumer_mobile']) != 10)
        {
            $validator->add("consumer_mobile", [
                 "_empty" => [
                 "rule" => [$this, "customlessFunction"],
                 "message" => "Consumer mobile must be 10 digits."
                ]
            ]);
        }
        if(strlen($this->dataPass['pincode']) != 6)
        {
            $validator->add("pincode", [
                 "_empty" => [
                 "rule" => [$this, "customlessFunction"],
                 "message" => "Pincode must be 6 digits."
                ]
            ]);
        }
	    return $validator;
	}
	/**
     *
     * validationTab2
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate work order tab (tab3)
     *
     */
	public function validationTab2(Validator $validator)
	{   
		$ApplyOnlinesTable 	= TableRegistry::get('ApplyOnlines');
		$applicationDetails = $ApplyOnlinesTable->viewApplication($this->dataPass['application_id']);
		$strVal 			= 'Aadhar';
		if($ApplyOnlinesTable->category_residental != $applicationDetails->category)
		{
			$strVal 		= 'Pan';
		}
		if(empty($this->dataPass['aadhar_card']))
		{
			$validator->notEmpty('aadhar_card', $strVal.' Card file required.');
		}
		if(empty($this->dataPass['recent_bill']) && $this->dataPass['error_bill']==1)
		{
			$validator->notEmpty('recent_bill', 'Recent Bill file required.');
		}
		if(isset($this->dataPass['aadhar_no']) && $ApplyOnlinesTable->category_residental == $applicationDetails->category) {
            $validator->notEmpty('aadhar_no', 'Aadhar no. can not be blank.');
            if(strlen($this->dataPass['aadhar_no']) != 12)
            {
                $validator->add("aadhar_no", [
                     "_empty" => [
                     "rule" => [$this, "customlessFunction"],
                     "message" => "Aadhaar Card Number must be a 12 digits."
                        ]
                ]);
            }
            if (!preg_match("/^[0-9]+$/i", $this->dataPass['aadhar_no']))
            {
                $validator->add("aadhar_no", [
                        "_empty" => [
                            "rule" => [$this, "customlessFunction"],
                            "message" => "Numbers only."
                        ]
                    ]
                );
            }
        }
        if(isset($this->dataPass['aadhar_no']) && $ApplyOnlinesTable->category_residental != $applicationDetails->category) {
            $validator->notEmpty('aadhar_no', 'PAN Card No. can not be blank.');
            if(strlen($this->dataPass['aadhar_no']) != 10)
            {
                $validator->add("aadhar_no", [
                     "_empty" => [
                     "rule" => [$this, "customlessFunction"],
                     "message" => "PAN Card Number must be a 10 digits."
                        ]
                ]);
            }
        }
		return $validator;
	}
	/**
     *
     * validationTab3
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate work order tab (tab3)
     *
     */
	public function validationTab3(Validator $validator)
	{   
		if(empty($this->dataPass['invoice_copy']))
		{
			$validator->notEmpty('invoice_copy', 'Invoice Copy file required.');
		}
		if(empty($this->dataPass['mou_document']) && empty($this->dataPass['workorder_attachment']))
		{
			$validator->notEmpty('mou_document', 'MoU with between Consumer and Installer file required.');
		}
	    return $validator;
	}
	/**
     *
     * validationTab4
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate CEI Documents tab (tab4)
     *
     */
	public function validationTab4(Validator $validator)
	{   
		if(empty($this->dataPass['cei_approval_doc']))
		{
			$validator->notEmpty('cei_approval_doc', 'CEI Approval file required.');
		}
		if(empty($this->dataPass['cei_inspection_doc']))
		{
			$validator->notEmpty('cei_inspection_doc', 'CEI Inspection file required.');
		}
		if(empty($this->dataPass['cei_self_certification']))
		{
			//$validator->notEmpty('cei_self_certification', 'Self Certification file required.');
		}
		$validator->notEmpty('cei_contractor', 'Name of Electrical Licenced Contractor can not be blank.');
		$validator->notEmpty('cei_superviser', 'Name of Authorised Superviser can not be blank.');
		$validator->notEmpty('cei_licence_no', 'Licence No can not be blank.');
		$validator->notEmpty('cei_authorised_by', 'Authorised Person for Signing can not be blank.');
		$validator->notEmpty('cei_licence_expiry_date', 'Licence valid up to can not be blank.');
		if(isset($this->dataPass['recommended_capacity']) && $this->dataPass['recommended_capacity']<=10)
		{
			$validator->notEmpty('cei_self_certification_date', 'Date of self certification can not be blank.');
		}
	    return $validator;
	}
	/**
     *
     * validationTab5
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate Execution Details tab (tab5)
     *
     */
	public function validationTab5(Validator $validator)
	{   
		$validator->notEmpty('latitude', 'Latitude can not be blank.');
		$validator->notEmpty('longitude', 'Longitude can not be blank.');
		$validator->notEmpty('bidirectional_meter_date', 'Bi-directional Meter Date can not be blank.');
		$validator->notEmpty('bidirectional_manufacture_name', 'Net Meter Manufacture Name can not be blank.');
		$validator->notEmpty('bidirectional_serial_no', 'Net Meter Serial No. can not be blank.');
		$validator->notEmpty('solar_manufacture_name', 'Solar Meter Manufacture Name can not be blank.');
		$validator->notEmpty('solar_serial_no', 'Solar Meter Serial No. can not be blank.');
		if(empty($this->dataPass['bidirectional_installation_sheet']))
		{
			$validator->notEmpty('bidirectional_installation_sheet', 'DisCom Bidirection Meter Installation Sheet file required.');
		}
		if(empty($this->dataPass['bidirectional_meter_certification']))
		{
			$validator->notEmpty('bidirectional_meter_certification', 'Certificate of Bi-directional Meter Installation and Consent for Subsidy file required.');
		}
		/*if(!empty($this->dataPass['latitude']) && !empty($this->dataPass['longitude']))
		{
			$locationdata 	= GetLocationByLatLong($this->dataPass['latitude'],$this->dataPass['longitude']);

			$state_output	= (isset($locationdata['state'])?$locationdata['state']:'');
			
			if(strtolower($state_output)!='gujarat')
			{
				$validator->add("latitude", [
	                    "_empty" => [
	                        "rule" => [$this, "customlessFunction"],
	                        "message" => "The Coordinate or the State entered in the system is not correct."
	                    ]
	                ]
	            );
			}
		}*/
		if(empty($this->dataPass['latitude']))
		{
			$validator->add("latitude", [
	                    "_empty" => [
	                        "rule" => [$this, "customlessFunction"],
	                        "message" => "Latitude can not be blank."
	                    ]
	                ]
	            );
		}
		if(empty($this->dataPass['longitude']))
		{
			$validator->add("longitude", [
	                    "_empty" => [
	                        "rule" => [$this, "customlessFunction"],
	                        "message" => "Longitude can not be blank."
	                    ]
	                ]
	            );
		}
		/*if(empty($this->dataPass['meter_sealing_report']))
		{
			$validator->notEmpty('meter_sealing_report', 'Meter Sealing Report file required.');
		}*/
		
	    return $validator;
	}
	/**
     *
     * validationTab6
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate Execution Details tab (tab6)
     *
     */
	public function validationTab6(Validator $validator)
	{   
		$ApplyOnlinesTable 	= TableRegistry::get('ApplyOnlines');
		$applicationDetails = $ApplyOnlinesTable->viewApplication($this->dataPass['application_id']);
		
		
		$validator->notEmpty('inv_login_id', 'Inverter Login ID can not be blank.');
		$validator->notEmpty('inv_password', 'Password can not be blank.');
		$validator->notEmpty('comm_date', 'Date of Commissioning can not be blank.');
		$validator->notEmpty('inv_login_url', 'Inverter Login URL can not be blank.');
		
		if(empty($this->dataPass['pv_plant_site_photo']))
		{
			$validator->notEmpty('pv_plant_site_photo', 'Solar PV Plant Site Photo file required.');
		}
		/*if(empty($this->dataPass['undertaking_consumer']))
		{
			$validator->notEmpty('undertaking_consumer', 'Undertaking of Consumer file required.');
		}
		if(empty($this->dataPass['geda_inspection_report']))
		{
			$validator->notEmpty('geda_inspection_report', 'GEDA Inspection Report file required.');
		}*/
		if(empty($this->dataPass['pv_module_serial']))
		{
			$validator->notEmpty('pv_module_serial', 'PV Module Serial No. file required.');
		}
		if(empty($this->dataPass['pv_module_certificate']))
		{
			$validator->notEmpty('pv_module_certificate', 'PV module IEC test certificate provided by NABL Laboratory (IEC certificate) file required.');
		}
		if(empty($this->dataPass['pv_module_sheet']))
		{
			$validator->notEmpty('pv_module_sheet', 'Technical data sheet & catalogue of Solar PV Modules of proposed make, model and capacity file required.');
		}
		if(empty($this->dataPass['inverter_serial']))
		{
			$validator->notEmpty('inverter_serial', 'Inverter Serial No. file required.');
		}
		if(empty($this->dataPass['inverter_certificate']))
		{
			$validator->notEmpty('inverter_certificate', 'Inverter IEC test certificate provided by NABL Laboratory( IEC certificate) file required.');
		}
		if(empty($this->dataPass['inverter_sheet']))
		{
			$validator->notEmpty('inverter_sheet', 'Technical data sheet & catalogue of Inverter of proposed make, model and capacity file required.');
		}
		$flag_data_enter    = 0;
        $arr_module         = array();
        foreach($this->dataPass['m_capacity'] as $key=>$val)
        {
            if(!empty($val) &&  !empty($this->dataPass['m_modules'][$key]))
            {
                $flag_data_enter    = 1;
                $arr_module[]       = $val*$this->dataPass['m_modules'][$key];
            }             
        }
        if($flag_data_enter == 0)
        {
            $validator->add("m_capacity", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Please enter at least one capacity and module."
                    ]
                ]
            );
        }
        $flag_data_enter    = 0;
        $arr_inverter       = array();
        foreach($this->dataPass['i_capacity'] as $key=>$val)
        {
            if(!empty($val) &&  !empty($this->dataPass['i_modules'][$key]))
            {
                $flag_data_enter    = 1;
                $arr_inverter[]     = $val*$this->dataPass['i_modules'][$key];
            }             
        }
        if($flag_data_enter == 0)
        {
            $validator->add("i_capacity", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Please enter at least one capacity and module."
                    ]
                ]
            );
        }
        $module_sum     = array_sum($arr_module)/1000;
        $inverter_sum   = array_sum($arr_inverter)/1000;
            
        if($ApplyOnlinesTable->category_residental == $applicationDetails->category)
		{
			if($this->dataPass['approved_capacity']==10 || $this->dataPass['approved_capacity']==6 || $this->dataPass['approved_capacity']==100 || $this->dataPass['approved_capacity']==50)
	        {
	            $plus_data  = $this->dataPass['approved_capacity']; 
	            $minus_data = $this->dataPass['approved_capacity']-(($this->dataPass['approved_capacity']*5)/100); 
	        }
	        else
	        {
	            $plus_data  = $this->dataPass['approved_capacity']+(($this->dataPass['approved_capacity']*5)/100);
	            $minus_data = $this->dataPass['approved_capacity']-(($this->dataPass['approved_capacity']*5)/100);
	        }
		}
        else
        {
        	if($this->dataPass['approved_capacity']==10 || $this->dataPass['approved_capacity']==100)
	        {
	            $plus_data  = $this->dataPass['approved_capacity']; 
	            $minus_data = $this->dataPass['approved_capacity']-(($this->dataPass['approved_capacity']*5)/100); 
	        }
	        else
	        {
	            $plus_data  = $this->dataPass['approved_capacity']+(($this->dataPass['approved_capacity']*5)/100);
	            $minus_data = $this->dataPass['approved_capacity']-(($this->dataPass['approved_capacity']*5)/100);
	        }
        }
        if($module_sum>$plus_data ||  $minus_data>$module_sum)
        {
            $validator->add("cumulative_module", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Cumulative capacity of PV modules(kW) should less than ".$plus_data."  and greater than ".$minus_data."."
                    ]
                ]
            );
        }
        /*if($inverter_sum>$this->dataPass['approved_capacity'])
        {
            $validator->add("cumulative_inverter", [
                    "_empty" => [
                        "rule" => [$this, "customlessFunction"],
                        "message" => "Cumulative capacity of PV Inverters(kW) should less than or equals to ".$this->dataPass['approved_capacity']."."
                    ]
                ]
            );
        }*/
	    return $validator;
	}
	/**
     *
     * customlessFunction
     *
     * Behaviour : Public
     *
     * @param : value
     * @defination : Method is use to returb false
     *
     */
	public function customlessFunction($value, $context){
        return false;
    }
	/**
     *
     * validationTab7
     *
     * Behaviour : Public
     *
     * @param : request data
     * @defination : Method is use to validate For Social Sector Details tab (tab7)
     *
     */
	public function validationTab7(Validator $validator)
	{   
		if(empty($this->dataPass['signing_authority']))
		{
			$validator->notEmpty('signing_authority', 'Photo ID Proof of Signing Authority file required.');
		}
		if(empty($this->dataPass['charity_certificate']))
		{
			$validator->notEmpty('charity_certificate', 'Charity Commissioner Certificate file required.');
		}
		if(empty($this->dataPass['authority_letter']))
		{
			$validator->notEmpty('authority_letter', 'Signing Authority Letter file required.');
		}
		if(empty($this->dataPass['formb']))
		{
			$validator->notEmpty('formb', 'Form B file required.');
		}
		if(empty($this->dataPass['formc']))
		{
			$validator->notEmpty('formc', 'Form C file required.');
		}
		if(empty($this->dataPass['affidavit']))
		{
			$validator->notEmpty('affidavit', 'Affidavit file required.');
		}
		if(empty($this->dataPass['agreement_stamp']))
		{
			$validator->notEmpty('agreement_stamp', 'Agreement file required.');
		}
		if(empty($this->dataPass['social_excel']))
		{
			$validator->notEmpty('social_excel', 'Excel file required.');
		}
		if(empty($this->dataPass['social_pdf']))
		{
			$validator->notEmpty('social_pdf', 'PDF file required.');
		}
		
	    return $validator;
	}
	/**
     *
     * viewSubsidy
     *
     * Behaviour : Public
     *
     * @param : $application_id   : pass application_id
     * @defination : Method is use to fetch subsidy details from application_id
     *
     */
	public function viewSubsidy($application_id)
	{
		$arrSubsidy= $this->find('all',array('conditions'=>array('application_id'=>$application_id)))->first();
		return $arrSubsidy;
	}
	/**
     *
     * GetApplicationDocuments
     *
     * Behaviour : Public
     *
     * @param : $application_id   : pass application_id
     * @defination : Method is use to fetch all required documents from application_id
     *
     */
	public function GetApplicationDocuments($application_id)
	{
		$arrSubsidyData 				= $this->viewSubsidy($application_id);
		$tableApplyDocs 				= TableRegistry::get('ApplyonlinDocs');
		$profile_image 					= $tableApplyDocs->find('all',array('conditions'=>array('application_id'=>$application_id,'doc_type'=>'profile')))->first();
		$main_path 						= WWW_ROOT.SUBSIDY_PATH.$application_id."/";
		$arr_output['invoice_copy']						= '';
		$arr_output['cei_approval_doc']					= '';
		$arr_output['cei_inspection_doc']				= '';
		$arr_output['cei_self_certification']			= '';
		$arr_output['bidirectional_installation_sheet']	= '';
		$arr_output['beneficiary_letter']				= '';
		$arr_output['certificate_bidirectional']		= '';
		$arr_output['photograph']						= '';
		$arr_output['pv_module_certificate']			= '';
		$arr_output['inverter_certificate']				= '';
		if(!empty($arrSubsidyData))
		{
			if(file_exists($main_path.$arrSubsidyData['invoice_copy']) && !empty($arrSubsidyData['invoice_copy']))
			{
				$arr_output['invoice_copy']	= $main_path.$arrSubsidyData['invoice_copy'];
			}
			if(!empty($arrSubsidyData['cei_approval_doc']))
			{
				if(!empty($arrSubsidyData['cei_approval_doc']) && file_exists($main_path.$arrSubsidyData['cei_approval_doc']))
				{
					$arr_output['cei_approval_doc']		= $main_path.$arrSubsidyData['cei_approval_doc'];
				}
				if(!empty($arrSubsidyData['cei_inspection_doc']) && file_exists($main_path.$arrSubsidyData['cei_inspection_doc']))
				{
					$arr_output['cei_inspection_doc']	= $main_path.$arrSubsidyData['cei_inspection_doc'];
				}
			}
			else
			{
				if(!empty($arrSubsidyData['cei_self_certification']) && file_exists($main_path.$arrSubsidyData['cei_self_certification']))
				{
					$arr_output['cei_self_certification']		= $main_path.$arrSubsidyData['cei_self_certification'];
				}
				else
				{
					$Self_Certificate	= $tableApplyDocs->find('all',array('conditions'=>array('application_id'=>$application_id,'doc_type'=>'Self_Certificate')))->first();
					if(!empty($Self_Certificate->file_name)) 
					{
						$DOCUMENT_PATH  = WWW_ROOT.APPLYONLINE_PATH.$application_id.'/';
	                    if (file_exists($DOCUMENT_PATH.$Self_Certificate->file_name)) {
	                        $arr_output['cei_self_certification']	= $DOCUMENT_PATH.$Self_Certificate->file_name;
	                    }
                	}
				}
			}
			if(!empty($arrSubsidyData['bidirectional_installation_sheet']) && file_exists($main_path.$arrSubsidyData['bidirectional_installation_sheet']))
			{
				$arr_output['bidirectional_installation_sheet']	= $main_path.$arrSubsidyData['bidirectional_installation_sheet'];
			}
			if(!empty($arrSubsidyData['agreement_stamp']) && file_exists($main_path.$arrSubsidyData['agreement_stamp']))
			{
				$arr_output['beneficiary_letter']				= $main_path.$arrSubsidyData['agreement_stamp'];
			}
			if(!empty($arrSubsidyData['pv_module_certificate']) && file_exists($main_path.$arrSubsidyData['pv_module_certificate']))
			{
				$arr_output['pv_module_certificate']			= $main_path.$arrSubsidyData['pv_module_certificate'];
			}
			if(!empty($arrSubsidyData['inverter_certificate']) && file_exists($main_path.$arrSubsidyData['inverter_certificate']))
			{
				$arr_output['inverter_certificate']				= $main_path.$arrSubsidyData['inverter_certificate'];
			}
			if(!empty($arrSubsidyData['bidirectional_meter_certification']) && file_exists($main_path.$arrSubsidyData['bidirectional_meter_certification']))
			{	
				$arr_output['certificate_bidirectional']		= $main_path.$arrSubsidyData['bidirectional_meter_certification'];
			}
			if(!empty($arrSubsidyData['pv_plant_site_photo']) && file_exists($main_path.$arrSubsidyData['pv_plant_site_photo']))
			{	
				$arr_output['photograph']						= $main_path.$arrSubsidyData['pv_plant_site_photo'];
			}
			/*if(!empty($profile_image['file_name']) && file_exists(WWW_ROOT.APPLYONLINE_PATH.$application_id.'/'.$profile_image['file_name']))
			{
				$arr_output['photograph']						= WWW_ROOT.APPLYONLINE_PATH.$application_id.'/'.$profile_image['file_name'];
			}*/
			if(file_exists($main_path.'geda_letter.pdf'))
			{
				unlink($main_path.'geda_letter.pdf');
			}
			if(!file_exists($main_path.'geda_letter.pdf'))
			{
		        $this->generateGedaLetterPdf($application_id,false,true);    
		    }
		    if(file_exists($main_path.'geda_letter.pdf'))
			{
				$arr_output['registration_letter']	= $main_path.'geda_letter.pdf';
			}
			if(!file_exists($main_path.'get_all_messages.pdf'))
			{
		        $this->generateCommentsPdf($application_id,true);    
		    }
		    if(file_exists($main_path.'get_all_messages.pdf'))
			{
				$arr_output['get_messages']	= $main_path.'get_all_messages.pdf';
			}
		}
		return $arr_output;
	}
	/**
	 * generateGedaLetterPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application letter file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from applyonline listing
	 *
	 */
	public function generateGedaLetterPdf($id,$isdownload=true,$save_letter=false)
	{
		$ApplyOnlines = TableRegistry::get('ApplyOnlines');
		$Installers = TableRegistry::get('Installers');
		$Members = TableRegistry::get('Members');
		$BranchMasters = TableRegistry::get('BranchMasters');
		$DiscomMaster = TableRegistry::get('DiscomMaster');
		$ApplyOnlineApprovals = TableRegistry::get('ApplyOnlineApprovals');
		$Projects = TableRegistry::get('Projects');

			$applyOnlinesData 			= $ApplyOnlines->viewApplication($id);
			$applyOnlinesData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $applyOnlinesData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($applyOnlinesData->created));

			$Installers_data = $Installers->find("all",['conditions'=>['id'=>$applyOnlinesData->installer_id]])->first();
		    $Membersdata = $Members->find("all",['conditions'=>['member_type'=>'6003','name'=>'CEI']])->first();
		    $discom_data =array();
		    $discom_name ="";
		    if(!empty($applyOnlinesData->area)){
		    	$discom_data                = $Members->find("all",['conditions'=>['area'=>$applyOnlinesData->area,'circle'=>'0','division'=>'0','subdivision'=>'0','section'=>'0']])->first();
		    	
		    	$discom_name                = $BranchMasters->find("all",['conditions'=>['id'=>$discom_data->branch_id]])->first();
		    	$discom_short_name          = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
		    }
			
		$applyOnlineGedaDate= $ApplyOnlineApprovals->getgedaletterStageData($id);
		$project_data 		= $Projects->find("all",['conditions'=>['id'=>$applyOnlinesData->project_id]])->first();
		$view = new View;
		$view->layout= false;
		$view->set("APPLY_ONLINE_MAIN_STATUS",$ApplyOnlineApprovals->apply_online_main_status);
		$view->set("pageTitle","Apply-online View");
		$view->set('ApplyOnlines',$applyOnlinesData);
		$view->set('Installers_data',$Installers_data);
		$view->set('Members',$Membersdata);
		$view->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$view->set('APPLICATION_DATE',$APPLICATION_DATE);
		$view->set('discom_data',$discom_data);
		$view->set('discom_name',$discom_name);
		$view->set('applyOnlineGedaDate',$applyOnlineGedaDate);
		$view->set('project_data',$project_data);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$view->set('discom_short_name',$discom_short_name);

		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		
		if($applyOnlinesData->social_consumer==1)
		{
			$html = $view->render('/Element/applyonlinesocialsector');
		}
		elseif($applyOnlinesData->disclaimer_subsidy==1)
		{
			$html = $view->render('/Element/applyonlinenonsubsidy');
		}
		else
		{
			if($applyOnlinesData->category==$ApplyOnlines->category_residental)
			{
				$html = $view->render('/Element/applyonlineresidencial');
			}
			else
			{
				$html = $view->render('/Element/applyonlineindustrial');
			}
		}
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			if($applyOnlinesData->social_consumer==1)
			{
				$dompdf->stream('applyonlinesocialsector-'.$LETTER_APPLICATION_NO);
			}
			elseif($applyOnlinesData->disclaimer_subsidy==1)
			{
				$dompdf->stream('applyonlinenonsubsidy-'.$LETTER_APPLICATION_NO);
			}
			else
			{
				if($applyOnlinesData->category==$ApplyOnlines->category_residental)
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

		if($save_letter==true)
		{
			if(!file_exists(SUBSIDY_PATH.$id)){
	            @mkdir(SUBSIDY_PATH.$id, 0777,true);
	        }
			$pdfPath = WWW_ROOT.SUBSIDY_PATH.$id.'/geda_letter.pdf';
			file_put_contents($pdfPath, $output);	
		}
		else
		{
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename='".$LETTER_APPLICATION_NO.".pdf'");
			echo $output;
		}
	}
	/**
	 * generateCommentsPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which application all messages should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from subsidy document
	 *
	 */
	public function generateCommentsPdf($id,$save_letter=true)
	{
		$view 				= new View;
		$view->layout		= false;
		$ApplyMessage 		= TableRegistry::get('ApplyonlineMessage');
		$ApplyonlineMessage = $ApplyMessage->GetAllMessagesById($id);
		$view->set(compact('ApplyonlineMessage', $ApplyonlineMessage));
		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		$view->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$html = $view->render('/Element/get_all_messages');
		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		$dompdf->render();
		
		$output = $dompdf->output();
		if($save_letter==true)
		{
			if(!file_exists(SUBSIDY_PATH.$id)){
	            @mkdir(SUBSIDY_PATH.$id, 0777,true);
	        }
			$pdfPath = WWW_ROOT.SUBSIDY_PATH.$id.'/get_all_messages.pdf';
			file_put_contents($pdfPath, $output);	
		}
	}
	/**
	 * calculateDeduction
	 * Behaviour : public
	 * @param : $TotalCapacity  : Pass the total capacity, $category_id : installer category id, $estimated_cost : pass project estimated cost
	 * @defination : Method is use to calaculate deduction amount for category A and category B installer 
	 *
	 */
	public function calculateDeduction($TotalCapacity,$category_id,$estimated_cost)
	{
		$deductionApplied 	= ($category_id==1) ? CATEGORY_A_TOTAL_CAPACITY : CATEGORY_B_TOTAL_CAPACITY;
		if($TotalCapacity > $deductionApplied)
		{
			return ($estimated_cost*100000*DEDUCTION_PERCENTAGE)/100;
		}
		return 0;
	}
}
?>