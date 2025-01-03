<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\View\View;
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
use Cake\Datasource\ConnectionManager;

class InstallerregistrationsController extends FrontAppController
{
	//public $helpers = ['Session'];
	public function initialize()
    {
       	parent::initialize();
    	$this->loadComponent('Flash'); 
    	$this->loadModel('Projects');
    	$this->loadModel('Installers');
    	$this->loadModel('Parameters');
    	$this->loadModel('States');
    	$this->loadModel('Company');
    	$this->loadModel('InstallerStates');
    	$this->loadModel('InstallerAgencyRating');
    	$this->loadModel('ApiToken');
    	$this->loadModel('ApplyOnlines');
    	$this->loadModel('Sessions');
    	$this->loadModel('DistrictMaster');
    	$this->loadModel('InstallerMessage');
    	$this->loadModel('Members');
    	$this->loadModel('Emaillog');
    	$this->loadModel('Couchdb');
    	$this->loadModel('InstallerCategoryMapping');
    	$this->loadModel('Customers');
    	$this->loadModel('InstallerPlans');
    	$this->loadModel('InstallerSubscription');
    	$this->loadModel('InstallerActivationCodes');
    	$this->loadModel('InstallerCredendtials');
    	$this->loadModel('ApplicationCategory');
    	$this->loadModel('InstallerApplicationCategoryMapping');
    	//$this->session = $this->request->session();
    	$this->conn = ConnectionManager::get('default');
    }

    /**
	*
	* installer_registration
	*
	* Behaviour : public
	*
	* @defination : Method for solar calculator page.
	*
	* Author : Pravin Sanghani
	*/
	public function installer_registration($company_id=null)
	{
		if(INSTALLER_REGISTRATION == 0) {
			return $this->redirect(URL_HTTP);
		}
		$this->layout 	= 'frontend';
		$this->commonRegistration($company_id);
		
	}
	private function commonRegistration($company_id,$registration_type=0)
	{
		$company_id 	= decode($company_id);
		$member_type 	= $this->Session->read('Members.member_type');
		$member_id 		= $this->Session->read("Members.id");
		$extTitle 		= ($registration_type == 1) ? 'Login Form - Developer' : 'Installer Form - Rooftop';
		$this->set('pageTitle',$extTitle);
		$this->set('east_states',$this->States->getSteates(1));
		$this->set('north_states',$this->States->getSteates(2));
		$this->set('south_states',$this->States->getSteates(3));
		$this->set('north_east_states',$this->States->getSteates(4));
		$this->set('central_states',$this->States->getSteates(5));
		$this->set('west_states',$this->States->getSteates(6));
		$setURL 	= ($registration_type==1) ? 'installer-registration-kusum' : 'installer-registration';
		if(isset($this->request->data['longitude']))
		{
			$this->request->data['Installers']['longitude'] = $this->request->data['longitude'];
		}
		if(isset($this->request->data['latitude'])) 
		{
			$this->request->data['Installers']['latitude'] 	= $this->request->data['latitude'];
		}
		if(isset($this->request->data['landmark'])) 
		{
			$this->request->data['Installers']['address'] 	= $this->request->data['landmark'];
		}
		if(isset($this->request->data['Installers']) && empty($company_id)) {
			$installerDetails 	= $this->Installers->find('all',array('conditions'=>array('pan'=>$this->request->data['Installers']['pan'],'email'=>$this->request->data['Installers']['email'],'mobile'=>$this->request->data['Installers']['mobile'],'OR'=>array('payment_status !='=>1,'geda_approval'=>2))))->first();
			$company_id 		= (isset($installerDetails->company_id) && !empty($installerDetails)) ? $installerDetails->company_id : 0;
			$this->request->data['Installers']['company_id'] = $company_id;
			if(!empty($company_id)) {
				return $this->redirect('/'.$setURL.'/'.encode($company_id));
			}
			
		}
		
		$installer_details 		= $this->Installers->find('all',array('conditions'=>array('company_id'=>$company_id)))->toArray();

		$this->Installers->data = $this->request->data;
		$ins_rate_details 		= array();
		$arr_states 			= array();
		if(!empty($installer_details) && $company_id>0)
		{
			$installerData    	= $this->Installers->get($installer_details[0]['id']);
			$this->Installers->dataRecord 	= $installerData;
			
			$ins_rate_details 	= $this->InstallerAgencyRating->find('all',array('conditions'=>array('installer_id'=>$installer_details[0]['id'])))->toArray();
			$arr_states 		= $this->InstallerStates->find('list',array('keyField'=>'id','valueField'=>'state_id','conditions'=>array('installer_id'=>$installer_details[0]['id'])))->toArray();
		}
		if($company_id>0) 
		{

			$InstallerEntity 				= $this->Installers->patchEntity($installerData, $this->request->data);
			if(!empty($this->request->data))
			{
				$InstallerEntity 			= $this->Installers->patchEntity($installerData, $this->request->data,['validate' => 'FronInstallerRegister']);
				$InstallerEntity->modified	= $this->NOW();
				$flashmsg 					= 'Installer Detailed Updated Successfully.';
			}
		}
		else
		{
			$InstallerEntity 	= $this->Installers->newEntity($this->request->data,['validate' => 'FronInstallerRegister']); 
			$InstallerEntity->stateflg	= 4;
			$InstallerEntity->created	= $this->NOW();
			$InstallerEntity->modified	= $this->NOW();
			$flashmsg 					= 'Installer Detailed Saved Successfully.';
		}
		if(!empty($this->request->data))
		{
			$this->request->data['Installers']['registration_type']	= $registration_type;
			
			
			if(!$InstallerEntity->errors()) 
    		{
    			$InstallerEntity->registration_type = $registration_type;
    			if((isset($this->request->data['Installers']['company_id']) && empty($this->request->data['Installers']['company_id'])) || $this->request->data['Installers']['company_id'] == '0')
				{
					$data['Company']['company_name'] 	= ucwords($this->request->data['Installers']['installer_name']);
					$companyEntity 						= $this->Company->newEntity($data);
					$companyEntity->created				= $this->NOW();
					$companyEntity->updated				= $this->NOW();
					$this->Company->save($companyEntity);
					$InstallerEntity->company_id 		= $companyEntity->id;
					$company_id 						= $companyEntity->id;

				}
				elseif(isset($this->request->data['Installers']['company_id']) && $company_id>0)
				{
					$companyData                     	= $this->Company->get($company_id);
					$data['Company']['company_name'] 	= ucwords($this->request->data['Installers']['installer_name']);
					$companyEntity 						= $this->Company->patchEntity($companyData,$data);
					$companyEntity->updated				= $this->NOW();
					$this->Company->save($companyEntity);
				}
				if(isset($InstallerEntity->district) && !empty($InstallerEntity->district)) {
					$DistrictMasterDetails 				= $this->DistrictMaster->find('all',array('conditions'=>array('id'=>$InstallerEntity->district)))->first();
					$InstallerEntity->district_code 	= $DistrictMasterDetails->district_code;
				}
				if(isset($this->request->data['Installers']['reply']) && !empty($this->request->data['Installers']['reply']))
				{
					$InstallerEntity->geda_approval 		= 3;
					$arrMessage     = $this->InstallerMessage->find('all',
                                            [
                                                'fields'=>['InstallerMessage.id','InstallerMessage.message','InstallerMessage.user_id'],
                                                'conditions'=>['InstallerMessage.installer_id'=>$InstallerEntity->id],
                                                'order'=>['InstallerMessage.id'=>'DESC']
                                            ])->first();
					
					$reply_msg_id  	= (!empty($arrMessage) && isset($arrMessage->id)) ? $arrMessage->id : 0;
					
					$browser 								= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$InstallerMessageEntity					= $this->InstallerMessage->newEntity();
					$InstallerMessageEntity->installer_id 	= $InstallerEntity->id;
					$InstallerMessageEntity->message 		= strip_tags($this->request->data['Installers']['reply']);
					$InstallerMessageEntity->user_type 		= 0;
					$InstallerMessageEntity->user_id 		= 0;
					$InstallerMessageEntity->reply_msg_id 	= $reply_msg_id;
					$InstallerMessageEntity->ip_address 	= $this->IP_ADDRESS;
					$InstallerMessageEntity->created 		= $this->NOW();
					$InstallerMessageEntity->browser_info 	= json_encode($browser);
					$this->InstallerMessage->save($InstallerMessageEntity);

					$membersData 							= $this->Members->find('all',array('conditions'=>array('id'=>$arrMessage->user_id)))->first();
					
					if(!empty($membersData)) {
						$EmailTo 		= $membersData->email;
							
						$subject 		= $InstallerEntity->installer_name." Installer Replied";
						$EmailVars 		= array('CONTACT_NAME' 	=> $membersData->name,
												'QUERY_RAISED' 	=> $arrMessage->message,
												'REPLIED_BY' 	=> $InstallerEntity->installer_name,
												'QUERY_REPLIED' => $this->request->data['Installers']['reply']);
								//->bcc('pulkitdhingra@gmail.com')

						$email 		= new Email('default');
						$email->profile('default');
						$email->viewVars($EmailVars);
						$message_send = $email->template('installer_registration_replied', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($EmailTo)
							->bcc('pulkitdhingra@gmail.com')
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();

						$Emaillog 				= $this->Emaillog->newEntity();
						$Emaillog->email 		= $EmailTo;
						$Emaillog->send_date 	= $this->NOW();
						$Emaillog->action  		= $InstallerEntity->installer_name." Installer Replied";
						$Emaillog->description 	= json_encode(array( 
								'CONTACT_NAME' 	=> $membersData->name,
								'QUERY_RAISED' 	=> $arrMessage->message,
								'QUERY_REPLIED' => $this->request->data['Installers']['reply']));
						$this->Emaillog->save($Emaillog);
					}
				}
				$InstallerEntity->installer_name 	= ucwords($InstallerEntity->installer_name);
				$InstallerEntity->contact_person 	= ucwords($InstallerEntity->contact_person);
				$InstallerEntity->designation 		= ucwords($InstallerEntity->designation);
				$this->Installers->save($InstallerEntity);

				if(isset($this->request->data['Installers']['selected_category']) && !empty($this->request->data['Installers']['selected_category']))
				{
					$this->InstallerApplicationCategoryMapping->deleteAll(['installer_id'=>$InstallerEntity->id]);

					$arrSelectedCategory 	= explode(",", $this->request->data['Installers']['selected_category']);
					foreach($arrSelectedCategory as $val) {
						$devMapEntity 							= $this->InstallerApplicationCategoryMapping->newEntity();
						$devMapEntity->installer_id 			= $InstallerEntity->id;
						$devMapEntity->application_category_id 	= $val;
						$this->InstallerApplicationCategoryMapping->save($devMapEntity);
					}
				}
				

				if($InstallerEntity->payment_status !=1) {
					$this->generateSendOTP($InstallerEntity->id);
				}

				if(isset($this->request->data['Installers']['f_upload_undertaking']['tmp_name']) && !empty($this->request->data['Installers']['f_upload_undertaking']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Installers']['f_upload_undertaking'],'ut',$InstallerEntity->id,'upload_undertaking','upload_undertaking',$InstallerEntity->id);
					$this->Installers->updateAll(
						array("upload_undertaking" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Installers']['f_pan_card']['tmp_name']) && !empty($this->request->data['Installers']['f_pan_card']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Installers']['f_pan_card'],'pc',$InstallerEntity->id,'pan_card','pan_card',$InstallerEntity->id);
					$this->Installers->updateAll(
						array("pan_card" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Installers']['f_gst_certificate']['tmp_name']) && !empty($this->request->data['Installers']['f_gst_certificate']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Installers']['f_gst_certificate'],'gst',$InstallerEntity->id,'gst_certificate','gst_certificate',$InstallerEntity->id);
					$this->Installers->updateAll(
						array("gst_certificate" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Installers']['f_registration_document']['tmp_name']) && !empty($this->request->data['Installers']['f_registration_document']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Installers']['f_registration_document'],'rd',$InstallerEntity->id,'registration_document','registration_document',$InstallerEntity->id);
					$this->Installers->updateAll(
						array("registration_document" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(!empty($installer_details))
				{
					$sql 					= "delete from installer_agency_rating WHERE installer_id = '".$installer_details[0]['id']."'";
					$this->conn->execute($sql);
					$sql 					= "delete from installer_region_states WHERE installer_id = '".$installer_details[0]['id']."'";
					$this->conn->execute($sql);
				}
				if(isset($this->request->data['installer_state']) && !empty($this->request->data['installer_state']))
				{
					foreach ($this->request->data['installer_state'] as $key => $value) {
						if($value != '0')
						{
							$sql = "INSERT INTO `installer_region_states`(`installer_id`, `state_id`, `updated`) VALUES ('".$InstallerEntity->id."','".$value."',NOW()) ON DUPLICATE KEY UPDATE `updated` ='NOW()' ";
							$this->conn->execute($sql);
						}
					}
					
				}
				if(isset($this->request->data['installer_state'])) {
					foreach ($this->request->data['type'] as $key => $value) 
					{
						$data= array();
						$data['installer_id'] 	= $InstallerEntity->id;
						$data['type'] 			= $this->request->data['type'][$key];
						$data['validupto'] 		= $this->request->data['validupto'][$key];
						$data['appno'] 			= $this->request->data['appno'][$key];
						$data['rate_agency'] 	= $this->request->data['rate_agency'][$key];
						$data['agency_rate'] 	= $this->request->data['agency_rate'][$key];
						$data['mnre_rate'] 		= $this->request->data['mnre_rate'][$key];
						$newARLead 				= $this->InstallerAgencyRating->newEntity($data);
						$this->InstallerAgencyRating->save($newARLead);
					}
					$statesArr 	= $this->States->find('all')->where(['id IN' => $this->request->data['installer_state']])->toArray();
					$to			= SEND_QUERY_EMAIL; //$project->customer['email'];
				//$to			= 'pravin.sanghani@yugtia.com'; //$project->customer['email'];
					$subject	= "Installer Registration Alert";
					$email 		= new Email('default');
					$email->profile('default');
					$email->viewVars(array('project_detail' => $this->request->data,'stateArr'=>$statesArr));			
					$email->template('send_installer_reg_query', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
				}
				$this->Flash->success($flashmsg);
				//$company_id=1264;
				if(isset($InstallerEntity->id) && $InstallerEntity->id>0) 
				{   
					if($InstallerEntity->payment_status !=1) {
						return $this->redirect('/verify-otp/'.encode($InstallerEntity->id));
					}
					else
					{
						return $this->redirect('/'.$setURL.'/'.encode($InstallerEntity->company_id));
					}
				}
				else
				{
					
					return $this->redirect('/'.$setURL);
				}
				
			}
						
		}
		$pass_comp_id = '';
		if($company_id>0)
		{
			$pass_comp_id = encode($company_id);
		}
		$arrStateData 				= $this->States->find("list",['keyField'=>'statename','valueField'=>'statename'])->toArray();
		$applicationCategoryData 	= $this->ApplicationCategory->find('all',array('conditions'=>array('status'=>'1','id'=>1)))->toArray();
		$mapDetails 				= $this->InstallerApplicationCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$InstallerEntity->id)))->toArray();
		$arrMap 					= isset($this->request->data['Installers']['selected_category']) ? explode(",",$this->request->data['Installers']['selected_category']) :  array();
		if(!empty($mapDetails)) {
			foreach($mapDetails as $arrDetails) {
				$arrMap[] 			= $arrDetails->application_category_id;
			}
		}
		$arrErrorEntities 	= $InstallerEntity->errors();
		if(isset($arrErrorEntities['selected_category']['_empty'])) {
		} else {
			$InstallerEntity->selected_category 	= '';
			if(!empty($arrMap)) {
				$InstallerEntity->selected_category	= implode(",", $arrMap);
			}
		}
		$this->set('ins_rate_details',$ins_rate_details);			
		$this->set('installer_detail',$InstallerEntity);
		$this->set('company_id',$pass_comp_id);
		$this->set('installer', $installer_details);	
		$this->set('arr_states',$arr_states);
		$this->set('arrStateData',$arrStateData);
		$this->set('arrDistictData',array());
		$this->set('InstallerErrors',$InstallerEntity->errors());
		$this->set('member_id',$member_id);
		$this->set('Couchdb',$this->Couchdb);
		$this->set('RouteURL',$setURL);
		$this->set('registration_type',$registration_type);
		$this->set('applicationCategoryData',$applicationCategoryData);

	}
	public function companylist($char='')
	{
		$this->autoRender = false;

		$result = $this->Company->companylist($char);
		$data = array();
		if(!empty($result))
		{	
			foreach ($result as $key => $val) {
				$name = $val . '|' . $key;
				array_push($data, $name);
			}
		}
		echo json_encode($data);
	}
    public function installer_reg_verification()
    {
        $this->layout = 'frontend';
        $this->set('pageTitle','Installer Verification');

    }
     /**
     *
     * sendOtpInstaller
     *
     * Behaviour : public
     *
     * @defination : Method for generate otp.
     */

	public function sendOtpInstaller(){

    	$getinstaller = $this->Installers->find('all',array('conditions'=>array('company_id'=>$this->request->data['company_id'])))->first();

            if(isset($getinstaller) && !empty($getinstaller))
            {
                $x = 4; // Amount of digits
                $min = pow(10,$x);
                $max = (pow(10,$x+1)-1);
                $activation_code	= rand($min, $max);

                $this->Installers->updateAll(
                    array(array("otp" => $activation_code)),
                    array("id" => $getinstaller->id)
                );
                $this->Installers->SendSMSActivationCode($getinstaller->id,$getinstaller->mobile,$activation_code);
                echo json_encode(array('success'=>'1','msg'=>'send'));
            }else{
            	echo json_encode(array('success'=>'0','msg'=>'send'));
            }
        exit;
    }
    /**
     *
     * co_list
     *
     * Behaviour : public
     *
     * @defination :find the company list to inastaller verification.
     */
    public function co_list($id = null){

        $condition =array();
        if(isset($_REQUEST['term']) && $_REQUEST['term'] != ""){
            $condition['company_name LIKE'] = '%' . $_REQUEST['term'] . '%';
        }
        $company_list = $this->Company->find('all', array('conditions'=>$condition))->toArray();
        $companyArray = array();
        if(!empty($company_list)){
            foreach($company_list as $key => $value){
                $companyArray[] = ['id'=>$value['id'], 'text'=>$value['company_name']];
            }
        }
        echo json_encode($companyArray);
        exit;
    }
    /**
     *
     * submitOtp
     *
     * Behaviour : public
     *
     * @defination :verify the otp and page redirect to inastaller registration.
     */
    public function submitOtp($co_id=null){

        if(!empty($this->request->data)) {
            $getinstallerdata = $this->Installers->find('all',array('conditions'=>array('company_id'=>$this->request->data['company_id'])))->first();
            $otp=$getinstallerdata->otp;

            if($this->request->data('otp')==$otp)
            {
                echo json_encode(array('success'=>'1','link'=>'InstallerRegistrations/installer_registration/'.encode($getinstallerdata->company_id)));
            }
            else
            {
                echo json_encode(array('success'=>'0','msg'=>'Otp is Worng! Please Enter Correct Otp OR Resend!'));
            }
        }
        exit;
    }
    /**
     *
     * installers_update_database
     *
     * Behaviour : public
     *
     * @defination :In order to update databse for installer for state_id
     */
    public function installers_update_database(){
    	$this->autoRender 	= false;
        $all_installer 		= $this->Installers->find('all')->toArray();
        foreach($all_installer as $key=>$val)
        {
       		$state_detail 	=$this->States->find('all',array('conditions'=>array('statename'=>$val->state)))->first();
       		if(!empty($state_detail))
       		{
       			echo $state_detail->id.'<br/>';
       		}
       		else
       		{
       			echo $val->state.'<br>';
       		}
        }
        //exit;
    }
    /**
	*
	* imgfile_upload
	*
	* Behaviour : public
	*
	* @param : id  : $file is use to identify for which image should be select and $path is use to identify the image folder path.
	*
	* @defination : Method is use to save the image in file folder .
	*
	*/
	public function imgfile_upload($file,$prefix_file='',$installer_id,$file_field,$access_type='',$Installer_id='')
	{
		$customerId 	= $Installer_id;
		$name 			= $file['name'];
		$path 			= WWW_ROOT.INSTALLER_PROFILE_PATH.$installer_id.'/';
		if(!file_exists(INSTALLER_PROFILE_PATH.$installer_id)){
			@mkdir(INSTALLER_PROFILE_PATH.$installer_id, 0777,true);
		}
		$updateRequestData 	= $this->Installers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();
		if(!empty($updateRequestData->$file_field) && file_exists($path.$updateRequestData->$file_field))
		{
			@unlink($path.$updateRequestData->$file_field);
		}
		$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
		$file_name   	= $prefix_file.date('YmdHis').rand();
		$file_location  = $path.$file_name.'.'.$ext;

		move_uploaded_file($file['tmp_name'],$file_location);
		$passFileName 	= $file_name.'.'.$ext;
		$couchdbId 		= $this->Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
		return $file_name.'.'.$ext;
	}
	/**
	*
	* VerifyOtp
	*
	* Behaviour : public
	*
	* @param : id  : post insid and otp.
	*
	* @defination : Method is use to verify opt for installer .
	*
	*/
	public function VerifyOtp_old()
	{
		$this->autoRender 	= false;
		$id 				= (isset($this->request->data['insid'])?$this->request->data['insid']:0);
		$otp 				= (isset($this->request->data['otp'])?$this->request->data['otp']:'');
		if(empty($id) || empty($otp)) {
			$ErrorMessage 	= "Please Enter OTP.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
		} else {
				
			$encode_id 				= $id;
			$id 					= intval(decode($id));
			$installerData 			= $this->Installers->find('all',array('Fields'=>['otp','id'],'conditions'=>array('id'=>$id)))->first();

			if (!empty($installerData)) {
				if ($this->request->is('post') || $this->request->is('put')) {

					if($otp == $installerData->otp) {
						
						if(!empty($installerData->otp_created_date))
						{
							$otp_created_date 	= strtotime($installerData->otp_created_date);
							$current_date 		= strtotime($this->NOW());
							$datediff 			= ($current_date - $otp_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$this->Installers->updateAll(array('otp_verified_status'=>1),array('id'=>$id));
						$this->Flash->set('OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						$ErrorMessage 	= "OTP Verified successfully.";
						$success 		= 1;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					} else {

						$ErrorMessage 	= "Error while otp verification.";
						$success 		= 0;
						$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
						$this->ApiToken->SetAPIResponse('success',$success);
					}
				}
			} else {
				$ErrorMessage 	= "Invalid Request. Please validate form details.";
				$success 		= 0;
				$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
				$this->ApiToken->SetAPIResponse('success',$success);
			}
		}
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	*
	* VerifyOtp
	*
	* Behaviour : public
	*
	* @param : id  : post insid and otp.
	*
	* @defination : Method is use to verify opt for installer .
	*
	*/
	public function VerifyOtp($installer_id='')
	{
		if(INSTALLER_REGISTRATION == 0) {
			return $this->redirect(URL_HTTP);
		}
		//$this->autoRender 	= false;
		$this->layout 			= 'frontend';
		$id 					= (isset($this->request->data['insid']) ? $this->request->data['insid'] : (!empty($installer_id) ? $installer_id : 0));
		$otp 					= (isset($this->request->data['otp'])?$this->request->data['otp']:'');
		$is_email 				= (isset($this->request->data['is_email'])?$this->request->data['is_email']:'');
		
		
		$encode_id 				= $id;
		$id 					= intval(decode($id));
		$installerData 			= $this->Installers->find('all',array('Fields'=>['otp','otp_email','id','otp_verified_status','otp_email_verified_status','registration_type'],'conditions'=>array('id'=>$id)))->first();
		
		if($installerData->otp_verified_status ==1 && $installerData->otp_email_verified_status ==1) {
			if($installerData->registration_type == 1){
				$this->setPaymentCustomerData($id);
				$this->Flash->success('Registration done successfully.');
                return $this->redirect(URL_HTTP.'installer-registration-kusum');
			} else {
				return $this->redirect(URL_HTTP.'installer-payment/'.encode($id));
				
			}
		}
		if (!empty($installerData)) {
			if ($this->request->is('post') || $this->request->is('put')) {
				if(empty($otp)) {
					$ErrorMessage 	= "Please Enter OTP.";
					$success 		= 0;
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
				} else {
					if($otp == $installerData->otp && $is_email==0) {
						if(!empty($installerData->otp_created_date))
						{
							$otp_created_date 	= strtotime($installerData->otp_created_date);
							$current_date 		= strtotime($this->NOW());
							$datediff 			= ($current_date - $otp_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$this->Installers->updateAll(array('otp_verified_status'=>1),array('id'=>$id));
						$this->Flash->set('Mobile OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						$ErrorMessage 	= "Mobile OTP Verified successfully.";
						$success 		= 1;
					} else if($otp == $installerData->otp_email && $is_email==1) {
						if(!empty($installerData->otp_email_created_date))
						{
							$otp_email_created_date = strtotime($installerData->otp_email_created_date);
							$current_date 			= strtotime($this->NOW());
							$datediff 				= ($current_date - $otp_email_created_date);
							if(($datediff/(60)) > OTP_VALIDITY_TIME)
							{
								$ErrorMessage 	= "OTP has been expired. Click on Resend OTP button in order to get new OTP.";
								$success 		= 0;
								$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
								$this->ApiToken->SetAPIResponse('success',$success);
								echo $this->ApiToken->GenerateAPIResponse();
								exit;
							}
						}
						$this->Installers->updateAll(array('otp_email_verified_status'=>1),array('id'=>$id));
						$this->Flash->set('Email OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
						$ErrorMessage 	= "Email OTP Verified successfully.";
						$success 		= 1;
					} else {
						$ErrorMessage 	= "Error while otp verification.";
						$success 		= 0;
					}
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
					$installerData 		= $this->Installers->find('all',array('Fields'=>['otp_verified_status','otp_email_verified_status','registration_type'],'conditions'=>array('id'=>$id)))->first();
					$redirect_payment 	= 0;
					if($installerData->otp_verified_status == 1 && $installerData->otp_email_verified_status == 1 && $installerData->registration_type != 1) {
						$redirect_payment 	= 1;
					}
					$this->ApiToken->SetAPIResponse('redirect_payment',$redirect_payment);
					echo $this->ApiToken->GenerateAPIResponse();
					exit;
				}
			}

		} else {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
		$this->set('pageTitle','OTP Verification');
		$this->set('installer_id',$encode_id);
		$this->set('installerData',$installerData);
		$this->set('company_id',encode($installerData->company_id));
		//echo $this->ApiToken->GenerateAPIResponse();
		//exit;
	}
	private function generateSendOTP($installer_id='',$is_email='')
	{
		if(!empty($installer_id)) {
			$InstallerEntity 	= $this->Installers->find('all',array('conditions'=>array('id'=>$installer_id,'payment_status !='=>1)))->first();
			if(!empty($InstallerEntity)) {
				$x 					= 5; // Amount of digits
				$min 				= pow(10,$x);
				$max 				= (pow(10,$x+1)-1);
				$activation_code    = rand($min, $max);
				$activation_email   = rand($min, $max);
				$sms_mobile 		= $InstallerEntity->mobile;
				if($is_email ==0 || $is_email == '') {
					//$sms_message 		= "Thank you for registering with ".PRODUCT_NAME.". Your activation code is ".$activation_code;
					$this->Installers->SendSMSActivationCode($InstallerEntity->id,$InstallerEntity->mobile,$activation_code);
					$this->Installers->updateAll(
						array("otp" => $activation_code,'otp_created_date' => $this->NOW(),'otp_verified_status'=>'0','modified'=>$this->NOW()),
						array("id" => $InstallerEntity->id)
					);
				}
				if($is_email == 1 || $is_email == '') {
					$to					= $InstallerEntity->email; //$project->customer['email'];
					//$to			= 'pravin.sanghani@yugtia.com'; //$project->customer['email'];
					$subject			= "Installer Registration OTP";
					$email 				= new Email('default');
					$email->profile('default');
					$email->viewVars(array('activation_code' => $activation_email,'URL_VERIFY'=>URL_HTTP.'verify-otp/'.encode($InstallerEntity->id),'installer_name'=>$InstallerEntity->installer_name));			
					$email->template('installer_registration', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
					$this->Installers->updateAll(
						array("otp_email" => $activation_email,'otp_email_created_date' => $this->NOW(),'otp_email_verified_status'=>'0','modified'=>$this->NOW()),
						array("id" => $InstallerEntity->id)
					);
				}
			}
		}
	}
	/**
	*
	* ResendOtp
	*
	* Behaviour : public
	*
	* @param : id  : post insid and otp.
	*
	* @defination : Method is use to resend opt for installer .
	*
	*/
	public function ResendOtp($is_email,$installer_id='')
	{
		$id 					= (!empty($installer_id) ? $installer_id : 0);
		
		$id 					= intval(decode($id));
		if(!empty($id))
		{
			$this->generateSendOTP($id,$is_email);
			$msg 				= (empty($is_email)) ? 'Mobile' : 'Email';
			$this->Flash->success($msg.' OTP has been resend successfully.');
			return $this->redirect(URL_HTTP.'verify-otp/'.encode($id));

		} else {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
			$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
			$this->ApiToken->SetAPIResponse('success',$success);
			echo $this->ApiToken->GenerateAPIResponse();
			exit;
		}
	}
	/**
	*
	* payment
	*
	* Behaviour : public
	*
	* @param : id  : post insid and otp.
	*
	* @defination : Method is use to view details and payment data .
	*
	*/
	public function payment($installer_id='')
	{
		if(INSTALLER_REGISTRATION == 0) {
			return $this->redirect(URL_HTTP);
		}
		$this->layout 			= 'frontend';
		$id 					= (!empty($installer_id) ? $installer_id : 0);
		
		$id 					= intval(decode($id));
		if(!empty($id))
		{
			$installerData 		= $this->Installers->find('all',array('conditions'=>array('id'=>$id)))->first();
			if($installerData->otp_verified_status != 1 || $installerData->otp_email_verified_status != 1) {
				return $this->redirect(URL_HTTP.'verify-otp/'.encode($id));
			}
			if($installerData->geda_approval == 1) {
				return $this->redirect(URL_HTTP.'installer-registration');
			}
		} else {
			return $this->redirect(URL_HTTP.'installer-registration');
		}
		$this->set('pageTitle','Installer Payment Information');
		$this->set('installerData',$installerData);
	}
	/**
	*
	* getDistrict
	*
	* Behaviour : public
	*
	* Parameter : discom
	*
	* @defination : Method is used to get district for perticular state.
	*
	*/
	public function getDistrict() {
		$this->autoRender 		= false;
		$state 					= isset($this->request->data['state'])?$this->request->data['state']:0;
		$data 					= array();
		if (!empty($state)) {
			$stateData 			= $this->States->find("all",['conditions'=>['statename'=>$state]])->first();
			if(!empty($stateData)) {
				$district 			= $this->DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>$stateData->id]]);
				$data['district'] 	= $district;
			}
			
			
		}
		$this->ApiToken->SetAPIResponse('msg', 'list of district');
		$this->ApiToken->SetAPIResponse('data', $data);
		$this->ApiToken->SetAPIResponse('type','ok');
		echo $this->ApiToken->GenerateAPIResponse();
		exit;
	}
	/**
	*
	* installer_registration_kusum
	*
	* Behaviour : public
	*
	* @defination : Method for solar calculator page.
	*
	*/
	public function installer_registration_kusum($company_id=null)
	{
		if(INSTALLER_REGISTRATION == 0) {
			return $this->redirect(URL_HTTP);
		}
		$this->layout 		= 'frontend';
		$this->commonRegistration($company_id,1);
		$this->render('installer_registration');
	}
	/**
	*
	* setPaymentCustomerData
	*
	* Behaviour : public
	*
	* @defination : Method for Customer Data for installer.
	*
	*/
	protected function setPaymentCustomerData($installer_id) {

		$InstallerCategoryMappingEntity 				= $this->InstallerCategoryMapping->newEntity();
		$InstallerCategoryMappingEntity->installer_id 	= $installer_id;
		$InstallerCategoryMappingEntity->category_id 	= 3;
		$InstallerCategoryMappingEntity->allowed_bands	= '["1","2","3","4"]';
		$InstallerCategoryMappingEntity->short_name 	= '';

		$this->InstallerCategoryMapping->save($InstallerCategoryMappingEntity);
		$InstallerDetails 				= $this->Installers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();
		$arrName 						= explode(" ",$InstallerDetails->contact_person);
	    $RandomPassword                 = strtolower($arrName[0]).'@2021';
		$arrEmail                       = explode(",",$InstallerDetails->email);
		$CustomerEmail                  = trim($arrEmail[0]);
		$customersEntity                = $this->Customers->newEntity();
		$customersEntity->mobile        = $InstallerDetails->mobile;
		$customersEntity->email         = $CustomerEmail;
		$customersEntity->name          = $InstallerDetails->contact_person;
		$customersEntity->password      = Security::hash(Configure::read('Security.salt') . $RandomPassword);
		$customersEntity->status        = $this->Customers->STATUS_INACTIVE;
		$customersEntity->customer_type = "installer";
		$customersEntity->state         = 4;
		$customersEntity->created       = $this->NOW();
		$customercnt                    = $this->Customers->find('all', array('conditions'=>array('email'=>$CustomerEmail)))->count();
		$IsInstallerCreated             = $this->Customers->find('all', array('conditions'=>array('installer_id'=>$installer_id)))->count();

		if ($this->Customers->save($customersEntity)) 
		{
			$insplanData                                    = $this->InstallerPlans->get($this->InstallerPlans->DEFAULT_PLAN_ID);
			$InstallerSubscriptionEntity                    = $this->InstallerSubscription->newEntity();
			$InstallerSubscriptionEntity->payment_status    = '';
			$InstallerSubscriptionEntity->installer_id      = $installer_id;
			$InstallerSubscriptionEntity->coupen_code       = '';
			$InstallerSubscriptionEntity->transaction_id    = '';
			$InstallerSubscriptionEntity->created           = $this->NOW();
			$InstallerSubscriptionEntity->modified          = $this->NOW();
			$InstallerSubscriptionEntity->payment_gateway   = '';
			$InstallerSubscriptionEntity->comment           = '100% Discount';
			$InstallerSubscriptionEntity->payment_data      = '';
			$InstallerSubscriptionEntity->amount            = '0';
			$InstallerSubscriptionEntity->coupen_id         = '0';
			$InstallerSubscriptionEntity->is_flat           = '0';
			$InstallerSubscriptionEntity->plan_name         = $insplanData->plan_name;
			$InstallerSubscriptionEntity->plan_price        = $insplanData->plan_price;
			$InstallerSubscriptionEntity->plan_id           = $this->InstallerPlans->DEFAULT_PLAN_ID;
			$InstallerSubscriptionEntity->user_limit        = $insplanData->user_limit;
			$InstallerSubscriptionEntity->start_date        = date('Y-m-d');
			$InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
			$InstallerSubscriptionEntity->status            = '1';
			$InstallerSubscriptionEntity->created_by        = $customersEntity->id;
			$InstallerSubscriptionEntity->modified_by       = $customersEntity->id;
			$this->InstallerSubscription->save($InstallerSubscriptionEntity);
			$insCodeArr = array();
			for ($i=0; $i < $insplanData->user_limit; $i++) {
				$activation_codes = $this->Installers->generateInstallerActivationCodes();
				$insCodeArr[]                                               = $activation_codes;
				$insCodedata['InstallerActivationCodes']['installer_id']    = $installer_id;
				$insCodedata['InstallerActivationCodes']['activation_code'] = $activation_codes;
				$insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
				$insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
				$insCodeEntity = $this->InstallerActivationCodes->newEntity($insCodedata);
				$this->InstallerActivationCodes->save($insCodeEntity);
			}
			$this->Customers->updateAll(['user_role'=>$this->Parameters->admin_role,'default_admin'=>1,'installer_id' => $installer_id,'modified' => $this->NOW()], ['id' => $customersEntity->id]);

			$PasswordInfo['InstallerCredendtials']['installer_id']  = $installer_id;
			$PasswordInfo['InstallerCredendtials']['password']      = $RandomPassword;
			$InstallerCredendtialsEnt 								= $this->InstallerCredendtials->newEntity($PasswordInfo);
			$this->InstallerCredendtials->save($InstallerCredendtialsEnt);
		}
	}
}
?>
