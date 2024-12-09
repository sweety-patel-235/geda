<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class SpinWebserviceApiTable extends AppTable
{
	var $table                  = 'spin_webservice_api';
	var $apitimestamp           = '';
	var $data                   = array();
	var $apiaction              = '';
	var $application_id         = '';
	var $installer_id           = '';
	var $APIURL                 = '';
	var $token                  = '';
	var $arrDiscom              = array('466'=>'34','467'=>'33','468'=>'43','469'=>'35','470'=>'61','471'=>'62');
	var $grid_level_voltage     = array('230'=>'1','415'=>'2','11000'=>'3','33000'=>'4','66000'=>'5');
	var $IPADD                  = '103.233.170.222';
	public function initialize(array $config)
	{
		$this->apitimestamp     = date("d.m.Y.H.i.s");
		$this->APIURL           = (SPIN_PRODUCTTION==1) ? SPIN_API_LIVE_URL : SPIN_API_DEV_URL;
		$this->token            = (SPIN_PRODUCTTION==1) ? SPIN_API_LIVE_TOKEN : SPIN_API_DEV_TOKEN;
		$this->table($this->table);         
	}
	
	public function discom_catgApi()
	{
		$this->apiaction        = 'discom_catg';
		$this->apiaction        = 'agency_list';
		$this->apiaction        = 'approval';
		return $this->commonApi();
	}
	/**
	 *
	 * AddAgency
	 *
	 * Behaviour : public
	 *
	 * @param : $installer_id  : Id is use to identify for which installer we want to add agency in spin
	 * @defination : Method is use to generate add agency in spin
	 *
	 */
	public function AddAgency($installer_id,$application_id)
	{
		$this->apiaction        = 'agency_data';
		$this->installer_id     = $installer_id;
		$this->application_id   = $application_id;
		return $this->commonApi();
	}
	/**
	 *
	 * AddPcrFiles
	 *
	 * Behaviour : public
	 *
	 * @param : $application_id  : Id is use to identify for which application we want to add PCR files after spin code come in spin
	 * @defination : Method is use to generate add PCR Files in spin
	 *
	 */
	public function AddPcrFiles($application_id)
	{
		$this->apiaction        = 'pcr_files';
		$this->application_id   = $application_id;
		return $this->commonApi();
	}
	/**
	 *
	 * pcr_submit
	 *
	 * Behaviour : public
	 *
	 * @param : $application_id  : Id is use to identify for which application we want to submit pcr
	 * @defination : Method is use to generate add pcr in spin
	 *
	 */
	public function pcr_submit($application_id)
	{
		$this->apiaction        = 'pcr_data';
		$this->application_id   = $application_id;
		return $this->commonApi();
	}
	/**
	 *
	 * commonApi
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : Common method is used to call spin API and stored request and response data in our database.
	 *
	 */
	public function commonApi()
	{   
		switch($this->apiaction)
		{
			case 'pcr_data':
			case 'agency_data':
			case 'pcr_files':
			break;
			default :
				$ch             = curl_init($this->APIURL.$this->apiaction.'/'.$this->token);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Forwarded-For: '.$this->IPADD));
				//curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				//curl_setopt($ch, CURLOPT_POST, true);
				//curl_setopt($ch, CURLOPT_POSTFIELDS,$arrRequest);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
				$output         = curl_exec($ch);
				$Response       = json_decode($output,1);
				curl_close ($ch);
				pr($Response);
				exit;
				foreach($Response['data'] as $data)
				{
					//$spinEntity             = $spin_data->newEntity();
				   // $spinEntity->spin_code  =$data['agency_cd'];
					//$spinEntity->name       =$data['agency_name'];
					//$spin_data->save($spinEntity);
				}
				
			break;
		}
		if($this->apiaction=='pcr_data')
		{
			$ApplyOnlines                           = TableRegistry::get('ApplyOnlines');
			$Projects                               = TableRegistry::get('Projects');
			$Subsidy                                = TableRegistry::get('Subsidy');
			$SubsidyCategory                        = TableRegistry::get('SubsidyCategory');
			$DistrictMaster                         = TableRegistry::get('DistrictMaster');
			$Installation                           = TableRegistry::get('Installation');
			$Installers                             = TableRegistry::get('Installers');
			$viewapplication                        = $ApplyOnlines->viewApplication($this->application_id);
			$projectsData                           = $Projects->get($viewapplication->project_id);
			$subsidyData                            = $Subsidy->find('all',array('conditions'=>array('application_id'=>$this->application_id)))->first();
			$beneCatDetails                         = $SubsidyCategory->get($subsidyData->category);
			$beneSubDetails                         = $SubsidyCategory->get($subsidyData->subcategory);
			$DistrictMasterDetails                  = $DistrictMaster->get($subsidyData->district);
			//m_type_modules
			$InstallersDetails                      = $Installers->find('all',array('conditions'=>array('id'=>$viewapplication->installer_id)))->first();
			if(!empty($InstallersDetails->agency_code))
			{
				$agency_cd                          = $InstallersDetails->agency_code;
			}
			else
			{
				$agency_cd                          = $this->AddAgency($viewapplication->installer_id,$viewapplication->id);
				$this->apiaction                    = 'pcr_data';
			}

			$arrModulesData                         = unserialize($subsidyData->modules_data);
			$arrInverterData                        = unserialize($subsidyData->inverter_data);
			$CUSTOMER_ADDRESS       = "";
			if (!empty($viewapplication->address1)) {
				$CUSTOMER_ADDRESS .= $viewapplication->address1.", ";
			}
			if (!empty($viewapplication->address2)) {
				$CUSTOMER_ADDRESS .= $viewapplication->address2.", ";
			}
			if (!empty($viewapplication->city)) {
				$CUSTOMER_ADDRESS .= $viewapplication->city.", ";
			}
			if (!empty($viewapplication->state)) {
				$CUSTOMER_ADDRESS .= $viewapplication->state.", ";
			}
			if (!empty($viewapplication->pincode)) {
			   // $CUSTOMER_ADDRESS .= $viewapplication->pincode.", ";
			}
			$CUSTOMER_ADDRESS   = trim($CUSTOMER_ADDRESS,", ");

			$subsidy_data = $Projects->calculatecapitalcostwithsubsidy($projectsData->recommended_capacity,$projectsData->estimated_cost,$projectsData->state,$projectsData->customer_type,true,$viewapplication->social_consumer);
			if ($subsidy_data['central_subcidy_type'] == 0) {
				$CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$subsidy_data['central_subsidy_amount']:"0";
			} else {
				$CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$subsidy_data['central_subsidy_amount']:"0";
			} 
			$url                                    = $this->APIURL.$this->apiaction.'/'.$this->token;
			$arrRequest                             = array();
			$arrRequest['unique_code']              = SPIN_UNIQUE_ID_APPEND.$this->application_id;
			$arrRequest['approval_id']              = SPIN_APPROVAL_ID;
			$arrRequest['fin_year']                 = SPIN_FIN_YEAR;
			$arrRequest['capacity']                 = $projectsData->recommended_capacity;
			$arrRequest['id_bene_catg']             = $beneCatDetails->spin_code;
			$arrRequest['id_bene_details']          = $beneSubDetails->spin_code;
			$arrRequest['ngo_id']                   = $subsidyData->ngo_id;
			$arrRequest['ngo_pan']                  = $subsidyData->ngo_pan;
			$arrRequest['address_org']              = $CUSTOMER_ADDRESS;
			$arrRequest['address_installation']     = $CUSTOMER_ADDRESS;
			$arrRequest['pin_code']                 = $viewapplication->pincode;
			$arrRequest['pin_installation']         = $viewapplication->pincode;
			$arrRequest['installation_district']    = $DistrictMasterDetails->district_code;
			$arrRequest['installation_state']       = $DistrictMasterDetails->state_code;
			$arrRequest['contact_name']             = $viewapplication->name_of_consumer_applicant;
			$arrRequest['mobile']                   = $viewapplication->consumer_mobile;
			$arrRequest['telephone']                = '';
			$arrRequest['email']                    = !empty($viewapplication->consumer_email) ? $viewapplication->consumer_email : $viewapplication->installer_email;
			$arrRequest['statecd']                  = $DistrictMasterDetails->state_code;
			$arrRequest['distcd']                   = $DistrictMasterDetails->district_code;
			$arrRequest['elec_distribution_name']   = $this->arrDiscom[$viewapplication->area];
			$arrRequest['elect_consumer_acc']       = $viewapplication->consumer_no;
			$arrRequest['net_metering_connected']   = ($viewapplication->net_meter==1) ? '1' : '0';
			$arrRequest['remarks_net_metering']     = '';
			$arrRequest['aadhar_number']            = passdecrypt($viewapplication->aadhar_no_or_pan_card_no);
			$cnti               = 1;
			for($i=1;$i<=3;$i++)
			{
				$row            = $i-1;
				if (isset($arrModulesData[$row]) && !empty($arrModulesData[$row]['m_capacity']) && $arrModulesData[$row]['m_modules']) 
				{
					$append_str                                     = ($cnti==1) ? '' : '_'.$cnti;
					$arrRequest['module_capacity'.$append_str]      = '';
					$arrRequest['module_no'.$append_str]            = '';
					$arrRequest['id_solar_technology'.$append_str]  = '';
					$arrRequest['solar_tech_other'.$append_str]     = '';
					if(!empty($arrModulesData[$row]['m_capacity']) && $arrModulesData[$row]['m_modules'])
					{
						$arrRequest['module_capacity'.$append_str]      = $arrModulesData[$row]['m_capacity']*1000;
						$arrRequest['module_no'.$append_str]            = $arrModulesData[$row]['m_modules'];
						$arrRequest['id_solar_technology'.$append_str]  = $arrModulesData[$row]['m_type_modules'];
						$arrRequest['solar_tech_other'.$append_str]     = ($arrModulesData[$row]['m_type_modules']==4) ? $arrModulesData[$row]['m_type_other'] : '';
						$cnti++;
					}
				}
			}
			for($i=$cnti;$i<=3;$i++)
			{
				$append_str                                     = ($i==1) ? '' : '_'.$i;
				$arrRequest['module_capacity'.$append_str]      = '';
				$arrRequest['module_no'.$append_str]            = '';
				$arrRequest['id_solar_technology'.$append_str]  = '';
				$arrRequest['solar_tech_other'.$append_str]     = '';
				$cnti++;
			}
			$cnti               = 1;
			for($i=1;$i<=3;$i++)
			{
				$row            = $i-1;
				if (isset($arrInverterData[$row]) && !empty($arrInverterData[$row]['i_capacity']) && $arrInverterData[$row]['i_modules']) 
				{
					$append_str                                     = ($cnti==1) ? '' : '_'.$cnti;
					$arrRequest['inverter_capacity'.$append_str]    = '';
					$arrRequest['inverter_no'.$append_str]          = '';
					$arrRequest['inv_type'.$append_str]             = '';
					$arrRequest['id_inv_make'.$append_str]          = '';
					$arrRequest['inv_make_other'.$append_str]       = '';
					if(!empty($arrInverterData[$row]['i_capacity']) && $arrInverterData[$row]['i_modules'])
					{
						$arrRequest['inverter_capacity'.$append_str]    = $arrInverterData[$row]['i_capacity']*1000;
						$arrRequest['inverter_no'.$append_str]          = $arrInverterData[$row]['i_modules'];
						$arrRequest['inv_type'.$append_str]             = $Installation->TYPE_INVERTERS_SPIN[$arrInverterData[$row]['i_type_modules']];
						$arrRequest['id_inv_make'.$append_str]          = $Installation->MAKE_INVERTERS_SPIN[$arrInverterData[$row]['i_make']];
						$arrRequest['inv_make_other'.$append_str]       = ($arrInverterData[$row]['i_make']==27) ? $arrInverterData[$row]['i_make_other'] : '';
						$cnti++;
					}
				   
				}
			}
			for($i=$cnti;$i<=3;$i++)
			{
				$append_str                                     = ($i==1) ? '' : '_'.$i;
				$arrRequest['inverter_capacity'.$append_str]    = '';
				$arrRequest['inverter_no'.$append_str]          = '';
				$arrRequest['inv_type'.$append_str]             = '';
				$arrRequest['id_inv_make'.$append_str]          = '';
				$arrRequest['inv_make_other'.$append_str]       = '';
				$cnti++;
			}
			$arrRequest['inverter_capacity_4']      = '';
			$arrRequest['inverter_no_4']            = '';
			$arrRequest['inv_type_4']               = '';
			$arrRequest['id_inv_make_4']            = '';
			$arrRequest['inv_make_other_4']         = '';
			$arrRequest['metering_arrangement']     = $subsidyData->arrangement;
			$arrRequest['grid_phase']               = ($subsidyData->grid_level_phase==1) ? '1' : '2';
			$arrRequest['grid_volt']                = $this->grid_level_voltage[$subsidyData->grid_level_voltage];
			$arrRequest['total_cost']               = $projectsData->estimated_cost*100000;
			$arrRequest['cfa']                      = $CENTRAL_SUBSIDY_AMOUNT;
			$arrRequest['installated_agency']       = $agency_cd;
			$arrRequest['installation_date']        = date('d-m-Y',strtotime($subsidyData->comm_date));
			$arrRequest['project_model']            = '0';
			$arrRequest['solar_tariff']             = '';
			$arrRequest['latitude']                 = $subsidyData->latitude;
			$arrRequest['longitude']                = $subsidyData->longitude;
			$str_request                            = json_encode($arrRequest);
			$ch             = curl_init($url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Forwarded-For: '.$this->IPADD));
			//curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$arrRequest);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
			$output         = curl_exec($ch);
			$Response       = json_decode($output,1);
			curl_close ($ch);
			$SpinWebEntity                  = $this->newEntity();
			$SpinWebEntity->application_id  = $this->application_id;
			$SpinWebEntity->project_id      = $viewapplication->project_id;
			$SpinWebEntity->installer_id    = $viewapplication->installer_id;
			$SpinWebEntity->subsidy_id      = $subsidyData->id;
			$SpinWebEntity->request_data    = $str_request;
			$SpinWebEntity->response_data   = $output;
			$SpinWebEntity->api_url         = $url;
			$SpinWebEntity->created         = $this->NOW();
			$this->save($SpinWebEntity);
			$output_code                    = '';
			if(strtolower($Response['status'])=='success' && isset($Response['pcr_code']))
			{
				$ApplyOnlines->updateAll(array('pcr_code'=>$Response['pcr_code'],'approval_id'=>SPIN_APPROVAL_ID),array('id'=>$this->application_id));
				$this->AddPcrFiles($this->application_id);
			}
			else
			{
				$EmailVars  = array("PCR_CODE"=>'',
									"APPLICATION_ID"=>$viewapplication->id,
									"TEXT_TYPE_SUBMIT"=>"PCR",
									"RESPONSE_PCR"=>$output,
									"DATETIME"=>$this->NOW());
			   /* $to         = "jayshree.tailor@yugtia.com";
				//$to         = "kalpak.yugtia@gmail.com";
				//->bcc("kalpak.yugtia@gmail.com")
				$email      = new Email('default');
				$subject    = "PCR not submitted for Application Id - ".$viewapplication->id;
				$email->profile('default');
				$email->viewVars($EmailVars);
				$message_send = $email->template('spin_response_error', 'default')
					->emailFormat('html')
					->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
					->to($to)
					->subject(Configure::read('EMAIL_ENV').$subject)
					->send();*/
			}
			//echo $output;
		}
		elseif($this->apiaction=='agency_data')
		{
			$Installers         = TableRegistry::get('Installers');
			$DistrictMaster     = TableRegistry::get('DistrictMaster');
			$InstallerDetails   = $Installers->find('all',array('conditions'=>array('id'=>$this->installer_id)))->first();
			$DistrictMasterDetails      = $DistrictMaster->find('all',array('conditions'=>array('district_code'=>$InstallerDetails->district_code)))->first();
			$url                        = $this->APIURL.$this->apiaction.'/'.$this->token;
			$arrRequest                 = array();
			$arrRequest['agency_name']  = $InstallerDetails->installer_name;
			$arrRequest['address']      = $InstallerDetails->address;
			$arrRequest['statecd']      = $DistrictMasterDetails->state_code;;
			$arrRequest['distcd']       = $InstallerDetails->district_code;
			$arrRequest['pan']          = $InstallerDetails->pan;
			$arrRequest['contact_name'] = $InstallerDetails->contact_person;
			$arrRequest['mobile']       = $InstallerDetails->mobile;
			$arrRequest['email']        = $InstallerDetails->email;
			
			$str_request                = json_encode($arrRequest);
			$ch             = curl_init($url);
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Forwarded-For: '.$this->IPADD));
			//curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$arrRequest);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
			$output         = curl_exec($ch);
			$Response       = json_decode($output,1);
			curl_close ($ch);
			$SpinWebEntity                  = $this->newEntity();
			$SpinWebEntity->application_id  = $this->application_id;
			$SpinWebEntity->installer_id    = $this->installer_id;
			$SpinWebEntity->request_data    = $str_request;
			$SpinWebEntity->response_data   = $output;
			$SpinWebEntity->api_url         = $url;
			$SpinWebEntity->created         = $this->NOW();
			$this->save($SpinWebEntity);
			$output_code                    = '';
			if(strtolower($Response['status'])=='success' && isset($Response['agency_cd']))
			{
				$Installers->updateAll(array('agency_code'=>$Response['agency_cd']),array('id'=>$this->installer_id));
				return $Response['agency_cd'];
			}
			else
			{
				
				return '';
			}
		}
		elseif($this->apiaction=='pcr_files')
		{
			$ApplyOnlines       = TableRegistry::get('ApplyOnlines');
			$Projects           = TableRegistry::get('Projects');
			$Subsidy            = TableRegistry::get('Subsidy');
			$SubsidyCategory    = TableRegistry::get('SubsidyCategory');
			$ApplyonlinDocs     = TableRegistry::get('ApplyonlinDocs');
			$subsidyData        = $Subsidy->find('all',array('conditions'=>array('application_id'=>$this->application_id)))->first();
			$beneCatDetails     = $SubsidyCategory->get($subsidyData->category);
			$beneSubDetails     = $SubsidyCategory->get($subsidyData->subcategory);
			$viewapplication    = $ApplyOnlines->viewApplication($this->application_id);
			$ProfilePicture     = $ApplyonlinDocs->find('all',array('conditions'=>array('application_id'=>$this->application_id,'doc_type'=>'profile')))->first();
			$Couchdb        = TableRegistry::get('Couchdb');
			require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
			$COUCHDB        = new Couchdb();
			if(!empty($viewapplication->pcr_code))
			{
				//$this->getCurlValue($DOCUMENT_PATH.$viewapplication->attach_recent_bill,'',$viewapplication->attach_recent_bill)
				$url                                        = $this->APIURL.$this->apiaction.'/'.$this->token;
				$main_path                                  = WWW_ROOT.SUBSIDY_PATH.$viewapplication->id."/";
				$DOCUMENT_PATH                              = WWW_ROOT.APPLYONLINE_PATH.$viewapplication->id."/";
				$Converted_file                             = $this->image_load($viewapplication->id,$ProfilePicture['file_name']);
				if($Converted_file == false)
				{
					$SpinWebEntity                  = $this->newEntity();
					$SpinWebEntity->application_id  = $this->application_id;
					$SpinWebEntity->project_id      = $viewapplication->project_id;
					$SpinWebEntity->installer_id    = $viewapplication->installer_id;
					$SpinWebEntity->subsidy_id      = $subsidyData->id;
					$SpinWebEntity->request_data    = '';
					$SpinWebEntity->response_data   = '{"status":"failure","message":{"file_customer":"Not Valid Mime type."}}';
					$SpinWebEntity->api_url         = '';
					$SpinWebEntity->created         = $this->NOW();
					$this->save($SpinWebEntity);
					return 'Not Valid Mime type.';
				}
				$file_size = filesize($Converted_file)/1000;
				if($file_size>200)
				{
					$Converted_file                         = $this->image_compressed($viewapplication->id,$Converted_file);
					if($Converted_file == false)
					{
						$SpinWebEntity                  = $this->newEntity();
						$SpinWebEntity->application_id  = $this->application_id;
						$SpinWebEntity->project_id      = $viewapplication->project_id;
						$SpinWebEntity->installer_id    = $viewapplication->installer_id;
						$SpinWebEntity->subsidy_id      = $subsidyData->id;
						$SpinWebEntity->request_data    = '';
						$SpinWebEntity->response_data   = '{"status":"failure","message":{"file_customer":"Not Valid size."}}';
						$SpinWebEntity->api_url         = '';
						$SpinWebEntity->created         = $this->NOW();
						$this->save($SpinWebEntity);
						return 'Not Valid Mime type.';
					}
				}
				$file_customer_plant    = $subsidyData->pv_plant_site_photo;
				$ext                    = pathinfo($main_path.$file_customer_plant, PATHINFO_EXTENSION);
				$Converted_plant_file   = $this->image_load_planPhoto($viewapplication->id,$subsidyData->pv_plant_site_photo);
				$file_customer_plant    = $Converted_plant_file;
				if($ext == 'jpg')
				{
					//$file_customer_plant                    = $this->convert_image_jpeg($viewapplication->id,$subsidyData->pv_plant_site_photo);
				}
				//$Converted_file_id_proof    = $this->image_load_planPhoto($viewapplication->id,$subsidyData->aadhar_card,'_aadhar_photo');
				$aadhar_card                = $main_path.$subsidyData->aadhar_card;//$Converted_file_id_proof;
				//$Converted_recent_bill    = $this->image_load_planPhoto($viewapplication->id,$subsidyData->recent_bill,'_recent_bill');
				$recent_bill                = $main_path.$subsidyData->recent_bill;//$Converted_recent_bill;
				$strIdText                                  = 'Aadhaar Card';
				if($viewapplication->category != $ApplyOnlines->category_residental)
				{
					$strIdText                              = 'PAN';
				}
				$arr_converted                              = explode("/",$Converted_file);
				$arrRequest                                 = array();
				$arrRequest['pcr_code']                     = $viewapplication->pcr_code;
				$arrRequest['id_bene_details']              = $beneSubDetails->spin_code;
				$arrRequest['id_proof']                     = $strIdText;
				$bidirectional_meter_certificationData      = $Couchdb->find('all',array('conditions'=>array('application_id'=>$viewapplication->id,'access_type'=>'bidirectional_meter_certification')))->first();
				if(!empty($bidirectional_meter_certificationData)) {
					if(!file_exists($main_path)) mkdir($main_path, 0755);
					$output     = $COUCHDB->getDocument($bidirectional_meter_certificationData->document_id,$bidirectional_meter_certificationData->file_attached,$bidirectional_meter_certificationData->doc_mime_type);
					file_put_contents($main_path.$subsidyData->bidirectional_meter_certification, $output);
				}
				$arrRequest['file_inspection']              = $this->getCurlValue($main_path.$subsidyData->bidirectional_meter_certification,'',$subsidyData->bidirectional_meter_certification);
				$arrRequest['file_customer_plant']          = $this->getCurlValue($file_customer_plant,'',$subsidyData->pv_plant_site_photo);
				$arrRequest['file_customer']                = $this->getCurlValue($Converted_file,'',end($arr_converted));
				$arrRequest['file_id_proof']                = $this->getCurlValue($aadhar_card,'',$subsidyData->aadhar_card);
				$arrRequest['file_discom']                  = $this->getCurlValue($recent_bill,'',$subsidyData->recent_bill);
				$arrRequest['file_module_capacity']         = $this->getCurlValue($main_path.$subsidyData->pv_module_serial,'',$subsidyData->pv_module_serial);
				$arrRequest['file_module_capacity_2']       = $this->getCurlValue($main_path.$subsidyData->pv_module_certificate,'',$subsidyData->pv_module_certificate);
				$arrRequest['file_module_capacity_3']       = $this->getCurlValue($main_path.$subsidyData->pv_module_sheet,'',$subsidyData->pv_module_sheet);
				$arrRequest['file_undertaking_consumer']    = !empty($subsidyData->undertaking_consumer) ? $this->getCurlValue($main_path.$subsidyData->undertaking_consumer,'',$subsidyData->undertaking_consumer) : '';
				

				$str_request                                = json_encode($arrRequest);
			   
			   $fields = (is_array($arrRequest)) ? http_build_query($arrRequest) : $str_request; 
				$ch             = curl_init($url);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Forwarded-For: '.$this->IPADD));
				//curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($ch, CURLOPT_POST, true);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$arrRequest);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
				//curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));  
				curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
				$output         = curl_exec($ch);
				$Response       = json_decode($output,1);
				curl_close ($ch);
			   
				$SpinWebEntity                  = $this->newEntity();
				$SpinWebEntity->application_id  = $this->application_id;
				$SpinWebEntity->project_id      = $viewapplication->project_id;
				$SpinWebEntity->installer_id    = $viewapplication->installer_id;
				$SpinWebEntity->subsidy_id      = $subsidyData->id;
				$SpinWebEntity->request_data    = $str_request;
				$SpinWebEntity->response_data   = $output;
				$SpinWebEntity->api_url         = $url;
				$SpinWebEntity->created         = $this->NOW();
				$this->save($SpinWebEntity);
				if(strtolower($Response['status'])=='success')
				{
					$ApplyOnlines->updateAll(array('pcr_submited'=>$this->NOW()),array('id'=>$this->application_id));
					return $Response['pcr_code'];
				}
				else
				{
					$EmailVars  = array("PCR_CODE"=>$viewapplication->pcr_code,
										"APPLICATION_ID"=>$viewapplication->id,
										"TEXT_TYPE_SUBMIT"=>"PCR files",
										"RESPONSE_PCR"=>$output,
										"DATETIME"=>$this->NOW());
					/*$to         = "jayshree.yugtia@gmail.com";
					$email      = new Email('default');
					$subject    = "PCR files not submitted for ".$viewapplication->pcr_code." Application Id - ".$viewapplication->id;
					$email->profile('default');
					$email->viewVars($EmailVars);
					$message_send = $email->template('spin_response_error', 'default')
						->emailFormat('html')
						->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
						->to($to)
						->subject(Configure::read('EMAIL_ENV').$subject)
						->send();*/
					return '';
				}
			}
		}
		return $Response;
	}
	/**
	 *
	 * getCurlValue
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : check the php version according to return file paths parameter
	 *
	 */
	public function getCurlValue($filename, $contentType, $postname)
	{
		// PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
		// See: https://wiki.php.net/rfc/curl-file-upload
		if (function_exists('curl_file_create')) {
			return curl_file_create($filename, $contentType, $postname);
		}
	 
		// Use the old style if using an older version of PHP
		$value = "@{$filename};filename=" . $postname;
		if ($contentType) {
			$value .= ';type=' . $contentType;
		}
	 
		return $value;
	}  
	/**
	 *
	 * image_load
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : Convert png and jpeg images to jpg
	 *
	 */ 
	public  function image_load($application_id,$imageold)
	{
		$DOCUMENT_PATH      = WWW_ROOT.APPLYONLINE_PATH.($application_id).'/';
		$IMAGE_PATH         = $DOCUMENT_PATH.$imageold;
		if (file_exists($IMAGE_PATH)) 
		{
			$ext                = pathinfo($IMAGE_PATH, PATHINFO_EXTENSION);
			$converted_filename = $DOCUMENT_PATH.$application_id."_profile_photo.jpg";

			if (!file_exists($converted_filename))
			{
				$mimeType           = $this->get_mime_type($IMAGE_PATH);
				$arrMimetype        = strtolower($mimeType);
				
				$arr_jpg            = explode("jpg",$arrMimetype);
				$arr_jpeg           = explode("jpeg",$arrMimetype);
				
				if(count($arr_jpg)==1 && count($arr_jpeg)==1)
				{
					if($mimeType == 'image/png')
					{
						$ext = 'png';
					}
					elseif($mimeType == 'image/x-ms-bmp')
					{
						$ext = 'bmp';
					}
					else
					{
						return false;
					}
					
				}
				if($ext == "png" && (count($arr_jpg)>1 || count($arr_jpeg)>1))
				{
					return false;
				}
				if ($ext == "png" || $ext == "gif" || $ext == "jpeg")
				{
					//new file name once the picture is converted
					if ($ext=="png") $new_pic = imagecreatefrompng($IMAGE_PATH);
					if ($ext=="gif") $new_pic = imagecreatefromgif($IMAGE_PATH);
					if ($ext=="jpeg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);
					if ($ext=="bmp") $new_pic = imagecreatefrombmp($IMAGE_PATH);
				   // header("Content-type: image/jpeg");
					// Create a new true color image with the same size
					$w = imagesx($new_pic);
					$h = imagesy($new_pic);
					$white = imagecreatetruecolor($w, $h);

					// Fill the new image with white background
					$bg = imagecolorallocate($white, 255, 255, 255);
					imagefill($white, 0, 0, $bg);

					// Copy original transparent image onto the new image
					imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);

					$new_pic = $white;
					imagejpeg($new_pic, $converted_filename);
					imagedestroy($new_pic);
				} else {
					$converted_filename = $IMAGE_PATH;
				}
				   
			}
			return $converted_filename;
		}
	}
	/**
	 *
	 * image_compressed
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : Convert and compressed image
	 *
	 */ 
	public  function image_compressed($application_id,$IMAGE_PATH)
	{
		$DOCUMENT_PATH      = WWW_ROOT.APPLYONLINE_PATH.($application_id).'/';
		if (file_exists($IMAGE_PATH)) 
		{
			$ext                = pathinfo($IMAGE_PATH, PATHINFO_EXTENSION);
			$converted_filename = $DOCUMENT_PATH.$application_id."_profile_photo1.jpg";
			$mimeType           = $this->get_mime_type($IMAGE_PATH);
			$arrMimetype        = strtolower($mimeType);
			$arr_jpg            = explode("jpg",$arrMimetype);
			$arr_jpeg           = explode("jpeg",$arrMimetype);
			if(count($arr_jpg)==1 && count($arr_jpeg)==1)
			{
				return false;
			}
		   // try{
				if ($ext == "png" || $ext == "gif" || $ext == "jpeg" ||  $ext == "jpg")
				{
					//new file name once the picture is converted
					if ($ext=="png") $new_pic = imagecreatefrompng($IMAGE_PATH);
					if ($ext=="gif") $new_pic = imagecreatefromgif($IMAGE_PATH);
					if ($ext=="jpeg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);
					if ($ext=="jpg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);
					$w = imagesx($new_pic);
					$h = imagesy($new_pic);
					$white = imagecreatetruecolor($w, $h);

					// Fill the new image with white background
					$bg = imagecolorallocate($white, 255, 255, 255);
					imagefill($white, 0, 0, $bg);

					// Copy original transparent image onto the new image
					imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);

					$new_pic = $white;
					imagejpeg($new_pic, $converted_filename);
					
					list($width, $height) = getimagesize($converted_filename);
					$r = 0.5;
					$crop   = false;
					if ($crop) {
						if ($width > $height) {
							$width = ceil($width-($width*abs($r-$w/$h)));
						} else {
							$height = ceil($height-($height*abs($r-$w/$h)));
						}
						$newwidth = $w;
						$newheight = $h;
					} else {
						if($h == 0)
						{
							return false;
							//throw new DivideByZeroException('Division');
						}
						if ($w/$h > $r) 
						{
							$newwidth = $h*$r;
							$newheight = $h;
						} else {
							$newheight = $w/$r;
							$newwidth = $w;
						}
					}

					system("convert ".$IMAGE_PATH." -resize $newwidth x $newheight -quality 40 ".$converted_filename);
					
					return $converted_filename; 
				} else {
					$converted_filename = $IMAGE_PATH;
				} 
			/*}
			catch (DivideByZeroException $ex)
			{
				echo "catch";
				exit;
				return false;
			}
			catch(Exception $e) {
				return false;
			}   */

			return $converted_filename;
		}
	}
	/**
	 *
	 * get_mime_type
	 *
	 * Behaviour : public
	 *
	 * @param : Pass the absolute file path.
	 * @defination : get the mime type of file
	 *
	 */
	public function get_mime_type($file) {
		$mtype = false;
		if (function_exists('finfo_open')) {
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mtype = finfo_file($finfo, $file);
		finfo_close($finfo);
		} elseif (function_exists('mime_content_type')) {
		$mtype = mime_content_type($file);
		} 
		return $mtype;
	}
	/**
	 *
	 * GetLatestSPINResponse
	 *
	 * Behaviour : public
	 *
	 * @param : Pass the application id.
	 * @defination : get the last failure response from spin
	 *
	 */
	public function GetLatestSPINResponse($id=0)
	{
		$LastFailResponse  = "";
		$LatestApiResponse = $this->find('all',array('conditions'=>array('application_id'=>$id),'order'=>'id desc'))->first();
		if (!empty($LatestApiResponse) && isset($LatestApiResponse->response_data) && !empty($LatestApiResponse->response_data)) 
		{

			$SpinResponse       = json_decode($LatestApiResponse->response_data,2);
			if (isset($SpinResponse['status']) && $SpinResponse['status'] == 'failure') {
				if(is_array($SpinResponse['message']))
				{
					$msg        = current($SpinResponse['message']);
				}
				else
				{
					$msg        = $SpinResponse['message'];
				}
				$LastFailResponse  = "<span class='text-danger'><b>Last Fail Response From SPIN ::</b> ".$msg." on <b>Date: </b>".date(LIST_DATE_FORMAT,strtotime($LatestApiResponse['created']))."</span>";
			}
		}
		return $LastFailResponse;
	}
	/**
	 *
	 * image_load_planPhoto
	 *
	 * Behaviour : public
	 *
	 * @param :
	 * @defination : Convert png and jpeg images to jpg
	 *
	 */
	public  function image_load_planPhoto($application_id,$imageold,$preFix = '_plant_photo')
	{
		$DOCUMENT_PATH      = WWW_ROOT.SUBSIDY_PATH.($application_id).'/';
		$IMAGE_PATH         = $DOCUMENT_PATH.$imageold;
		if (file_exists($IMAGE_PATH))
		{
			$ext                = pathinfo($IMAGE_PATH, PATHINFO_EXTENSION);
			$converted_filename = $DOCUMENT_PATH.$application_id.$preFix.".jpg";
			$mimeType           = $this->get_mime_type($IMAGE_PATH);
			$arrMimetype        = strtolower($mimeType);

			$arr_jpg            = explode("jpg",$arrMimetype);
			$arr_jpeg           = explode("jpeg",$arrMimetype);
			//echo $IMAGE_PATH;
			if(count($arr_jpg)==1 && count($arr_jpeg)==1)
			{
				if($mimeType == 'image/png')
				{
					$ext = 'png';
				}
			}
			if($ext == "png" && (count($arr_jpg)>1 || count($arr_jpeg)>1))
			{
				if($mimeType == 'image/png')
				{
					$ext = 'png';
				}
			}
			if ($ext == "png")
			{
				if (!file_exists($converted_filename))
				{
				//new file name once the picture is converted
					if ($ext=="png") $new_pic = imagecreatefrompng($IMAGE_PATH);
					if ($ext=="gif") $new_pic = imagecreatefromgif($IMAGE_PATH);
					if ($ext=="jpeg") $new_pic = imagecreatefromjpeg($IMAGE_PATH);
				   // header("Content-type: image/jpeg");
					// Create a new true color image with the same size
					$w = imagesx($new_pic);
					$h = imagesy($new_pic);
					$white = imagecreatetruecolor($w, $h);

					// Fill the new image with white background
					$bg = imagecolorallocate($white, 255, 255, 255);
					imagefill($white, 0, 0, $bg);

					// Copy original transparent image onto the new image
					imagecopy($white, $new_pic, 0, 0, 0, 0, $w, $h);

					$new_pic = $white;
					imagejpeg($new_pic, $converted_filename);
					imagedestroy($new_pic);
				}
			} else {
				$converted_filename = $IMAGE_PATH;
			}
			return $converted_filename;
		}
	}
}