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
use PHPExcel\PHPExcel;
class DeveloperRegistrationsController extends FrontAppController
{
	//public $helpers = ['Session'];
	public function initialize()
    {
       	parent::initialize();
    	$this->loadComponent('Flash'); 
    	$this->loadModel('Projects');
    	$this->loadModel('Developers');
    	$this->loadModel('Parameters');
    	$this->loadModel('States');
    	$this->loadModel('DeveloperCompany');
    	$this->loadModel('InstallerStates');
    	$this->loadModel('DeveloperAgencyRating');
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
    	$this->loadModel('DeveloperSubscription');
    	$this->loadModel('DeveloperActivationCodes');
    	$this->loadModel('DeveloperCredendtials');
    	$this->loadModel('FeesReturn');
    	$this->loadModel('DeveloperMessage');
    	$this->loadModel('DeveloperCustomers');
    	$this->loadModel('ApplicationCategory');
    	$this->loadModel('DeveloperApplicationCategoryMapping');
    	$this->loadModel('DeveloperPayment');
    	$this->loadModel('ThirdpartyApiLog');
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
	*/
	public function developer_registration($company_id=null)
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
		$extTitle 		= 'Login Form - Developer';
		$this->set('pageTitle',$extTitle);
		$this->set('east_states',$this->States->getSteates(1));
		$this->set('north_states',$this->States->getSteates(2));
		$this->set('south_states',$this->States->getSteates(3));
		$this->set('north_east_states',$this->States->getSteates(4));
		$this->set('central_states',$this->States->getSteates(5));
		$this->set('west_states',$this->States->getSteates(6));
		$setURL 	= 'developer-registration';
		if(isset($this->request->data['longitude']))
		{
			$this->request->data['Developers']['longitude'] = $this->request->data['longitude'];
		}
		if(isset($this->request->data['latitude'])) 
		{
			$this->request->data['Developers']['latitude'] 	= $this->request->data['latitude'];
		}
		if(isset($this->request->data['landmark'])) 
		{
			$this->request->data['Developers']['address'] 	= $this->request->data['landmark'];
		}
		if(isset($this->request->data['Developers']) && empty($company_id)) {
			$installerDetails 	= $this->Developers->find('all',array('conditions'=>array('pan'=>$this->request->data['Developers']['pan'],'email'=>$this->request->data['Developers']['email'],'mobile'=>$this->request->data['Developers']['mobile'],'OR'=>array('payment_status !='=>1,'geda_approval'=>2))))->first();
			$company_id 		= (isset($installerDetails->company_id) && !empty($installerDetails)) ? $installerDetails->company_id : 0;
			$this->request->data['Developers']['company_id'] = $company_id;
			if(!empty($company_id)) {
				return $this->redirect('/'.$setURL.'/'.encode($company_id));
			}
			
		}
		
		$installer_details 		= $this->Developers->find('all',array('conditions'=>array('company_id'=>$company_id)))->toArray();
		$this->Developers->data = $this->request->data;
		$ins_rate_details 		= array();
		$arr_states 			= array();
		if(!empty($installer_details) && $company_id>0)
		{
			$installerData    	= $this->Developers->get($installer_details[0]['id']);
			$this->Developers->dataRecord 	= $installerData;
			
			$ins_rate_details 	= $this->DeveloperAgencyRating->find('all',array('conditions'=>array('installer_id'=>$installer_details[0]['id'])))->toArray();
			$arr_states 		= $this->InstallerStates->find('list',array('keyField'=>'id','valueField'=>'state_id','conditions'=>array('installer_id'=>$installer_details[0]['id'])))->toArray();
		}

		if($company_id>0) 
		{

			$InstallerEntity 				= $this->Developers->patchEntity($installerData, $this->request->data);
			if(!empty($this->request->data))
			{
				$InstallerEntity 			= $this->Developers->patchEntity($installerData, $this->request->data,['validate' => 'FronInstallerRegister']);
				$InstallerEntity->modified	= $this->NOW();
				$flashmsg 					= 'Developer Details Updated Successfully.';
			}
		}
		else
		{
			$InstallerEntity 	= $this->Developers->newEntity($this->request->data,['validate' => 'FronInstallerRegister']); 
			$InstallerEntity->stateflg	= 4;
			$InstallerEntity->created	= $this->NOW();
			$InstallerEntity->modified	= $this->NOW();
			$flashmsg 					= 'Developer Details Saved Successfully.';
		}
		if(!empty($this->request->data))
		{
			$this->request->data['Developers']['registration_type']	= $registration_type;
			
			
			if(!$InstallerEntity->errors()) 
    		{
    			$InstallerEntity->registration_type = $registration_type;
    			if((isset($this->request->data['Developers']['company_id']) && empty($this->request->data['Developers']['company_id'])) || $this->request->data['Developers']['company_id'] == '0')
				{
					$data['DeveloperCompany']['company_name'] 	= ucwords($this->request->data['Developers']['installer_name']);
					$companyEntity 						= $this->DeveloperCompany->newEntity($data);
					$companyEntity->created				= $this->NOW();
					$companyEntity->updated				= $this->NOW();
					$this->DeveloperCompany->save($companyEntity);
					$InstallerEntity->company_id 		= $companyEntity->id;
					$company_id 						= $companyEntity->id;

				}
				elseif(isset($this->request->data['Developers']['company_id']) && $company_id>0)
				{
					$companyData                     	= $this->DeveloperCompany->get($company_id);
					$data['DeveloperCompany']['company_name'] 	= ucwords($this->request->data['Developers']['installer_name']);
					$companyEntity 						= $this->DeveloperCompany->patchEntity($companyData,$data);
					$companyEntity->updated				= $this->NOW();
					$this->DeveloperCompany->save($companyEntity);
				}
				if(isset($InstallerEntity->district) && !empty($InstallerEntity->district)) {
					$DistrictMasterDetails 				= $this->DistrictMaster->find('all',array('conditions'=>array('id'=>$InstallerEntity->district)))->first();
					$InstallerEntity->district_code 	= $DistrictMasterDetails->district_code;
				}
				if(isset($this->request->data['Developers']['reply']) && !empty($this->request->data['Developers']['reply']))
				{
					$InstallerEntity->geda_approval 		= 3;
					$arrMessage     = $this->DeveloperMessage->find('all',
                                            [
                                                'fields'=>['DeveloperMessage.id','DeveloperMessage.message','DeveloperMessage.user_id'],
                                                'conditions'=>['DeveloperMessage.installer_id'=>$InstallerEntity->id],
                                                'order'=>['DeveloperMessage.id'=>'DESC']
                                            ])->first();
					
					$reply_msg_id  	= (!empty($arrMessage) && isset($arrMessage->id)) ? $arrMessage->id : 0;
					
					$browser 								= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
					$DeveloperMessageEntity					= $this->DeveloperMessage->newEntity();
					$DeveloperMessageEntity->installer_id 	= $InstallerEntity->id;
					$DeveloperMessageEntity->message 		= strip_tags($this->request->data['Developers']['reply']);
					$DeveloperMessageEntity->user_type 		= 0;
					$DeveloperMessageEntity->user_id 		= 0;
					$DeveloperMessageEntity->reply_msg_id 	= $reply_msg_id;
					$DeveloperMessageEntity->ip_address 	= $this->IP_ADDRESS;
					$DeveloperMessageEntity->created 		= $this->NOW();
					$DeveloperMessageEntity->browser_info 	= json_encode($browser);
					$this->DeveloperMessage->save($DeveloperMessageEntity);

					$membersData 							= $this->Members->find('all',array('conditions'=>array('id'=>$arrMessage->user_id)))->first();
					
					if(!empty($membersData)) {
						$EmailTo 		= $membersData->email;
							
						$subject 		= $InstallerEntity->installer_name." Developer Replied";
						$EmailVars 		= array('CONTACT_NAME' 	=> $membersData->name,
												'QUERY_RAISED' 	=> $arrMessage->message,
												'REPLIED_BY' 	=> $InstallerEntity->installer_name,
												'QUERY_REPLIED' => $this->request->data['Developers']['reply']);
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
								'QUERY_REPLIED' => $this->request->data['Developers']['reply']));
						$this->Emaillog->save($Emaillog);
					}
				}
				$InstallerEntity->installer_name 	= ucwords($InstallerEntity->installer_name);
				$InstallerEntity->contact_person 	= ucwords($InstallerEntity->contact_person);
				$InstallerEntity->designation 		= ucwords($InstallerEntity->designation);
				$this->Developers->save($InstallerEntity);
				if(isset($this->request->data['Developers']['selected_category']) && !empty($this->request->data['Developers']['selected_category']))
				{
					$this->DeveloperApplicationCategoryMapping->deleteAll(['installer_id'=>$InstallerEntity->id]);

					$arrSelectedCategory 	= explode(",", $this->request->data['Developers']['selected_category']);
					$totalDeveloperCharges 	= 0;
					$totalDeveloperGst 		= 0;
					$totalDeveloperAmount 	= 0;
					foreach($arrSelectedCategory as $val) {
						$ApplicationCategoryDetails 			= $this->ApplicationCategory->find('all',array('conditions'=>array('id'=>$val)))->first();
						$developer_charges 						= isset($ApplicationCategoryDetails->developer_charges) ? $ApplicationCategoryDetails->developer_charges : 0;
						$gst_fees 								= isset($ApplicationCategoryDetails->developer_tax_percentage) ? (($developer_charges*$ApplicationCategoryDetails->developer_tax_percentage)/100) : 0;
						$developer_total_charges 				= $developer_charges+$gst_fees;
						$devMapEntity 							= $this->DeveloperApplicationCategoryMapping->newEntity();
						$devMapEntity->installer_id 			= $InstallerEntity->id;
						$devMapEntity->application_category_id 	= $val;
						$devMapEntity->developer_fee 			= $developer_charges;
						$devMapEntity->gst_fees 				= $gst_fees;
						$devMapEntity->developer_total_fee 		= $developer_total_charges;
						$this->DeveloperApplicationCategoryMapping->save($devMapEntity);
						
						$totalDeveloperCharges 					= $totalDeveloperCharges + $developer_charges;
						$totalDeveloperGst 						= $totalDeveloperGst + $gst_fees;
						$totalDeveloperAmount 					= $totalDeveloperAmount + $developer_total_charges;
					}
					$this->Developers->updateAll(['developer_fee'=>$totalDeveloperCharges,'gst_fees'=>$totalDeveloperGst,'developer_total_fee'=>$totalDeveloperAmount],['id'=>$InstallerEntity->id]);
				}

				if($InstallerEntity->payment_status !=1) {
					$this->generateSendOTP($InstallerEntity->id);
				}

				if(isset($this->request->data['Developers']['f_upload_undertaking']['tmp_name']) && !empty($this->request->data['Developers']['f_upload_undertaking']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Developers']['f_upload_undertaking'],'ut',$InstallerEntity->id,'upload_undertaking','d_upload_undertaking',$InstallerEntity->id);
					$this->Developers->updateAll(
						array("upload_undertaking" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Developers']['f_pan_card']['tmp_name']) && !empty($this->request->data['Developers']['f_pan_card']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Developers']['f_pan_card'],'pc',$InstallerEntity->id,'pan_card','d_pan_card',$InstallerEntity->id);
					$this->Developers->updateAll(
						array("pan_card" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Developers']['f_gst_certificate']['tmp_name']) && !empty($this->request->data['Developers']['f_gst_certificate']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Developers']['f_gst_certificate'],'gst',$InstallerEntity->id,'gst_certificate','d_gst_certificate',$InstallerEntity->id);
					$this->Developers->updateAll(
						array("gst_certificate" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Developers']['f_registration_document']['tmp_name']) && !empty($this->request->data['Developers']['f_registration_document']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Developers']['f_registration_document'],'rd',$InstallerEntity->id,'registration_document','d_registration_document',$InstallerEntity->id);
					$this->Developers->updateAll(
						array("registration_document" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}

				if(isset($this->request->data['Developers']['dfile_board']['tmp_name']) && !empty($this->request->data['Developers']['dfile_board']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Developers']['dfile_board'],'dfb',$InstallerEntity->id,'file_board','d_file_board',$InstallerEntity->id);
					$this->Developers->updateAll(
						array("d_file_board" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(isset($this->request->data['Developers']['d_msme']['tmp_name']) && !empty($this->request->data['Developers']['d_msme']['tmp_name']))
				{
					$file_name 								= $this->imgfile_upload($this->request->data['Developers']['d_msme'],'dmm',$InstallerEntity->id,'d_msme','d_msme',$InstallerEntity->id);
					$this->Developers->updateAll(
						array("d_msme" => $file_name),
						array("id" => $InstallerEntity->id)
					);
				}
				if(!empty($installer_details))
				{
					/*$sql 					= "delete from installer_agency_rating WHERE installer_id = '".$installer_details[0]['id']."'";
					$this->conn->execute($sql);
					$sql 					= "delete from installer_region_states WHERE installer_id = '".$installer_details[0]['id']."'";
					$this->conn->execute($sql);*/
				}
				/*if(isset($this->request->data['installer_state']) && !empty($this->request->data['installer_state']))
				{
					foreach ($this->request->data['installer_state'] as $key => $value) {
						if($value != '0')
						{
							$sql = "INSERT INTO `installer_region_states`(`installer_id`, `state_id`, `updated`) VALUES ('".$InstallerEntity->id."','".$value."',NOW()) ON DUPLICATE KEY UPDATE `updated` ='NOW()' ";
							$this->conn->execute($sql);
						}
					}
					
				}*/

				/*if(isset($this->request->data['installer_state'])) {
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
						$newARLead 				= $this->DeveloperAgencyRating->newEntity($data);
						$this->DeveloperAgencyRating->save($newARLead);
					}
					$statesArr 	= $this->States->find('all')->where(['id IN' => $this->request->data['installer_state']])->toArray();
					$to			= SEND_QUERY_EMAIL; //$project->customer['email'];
				//$to			= 'pravin.sanghani@yugtia.com'; //$project->customer['email'];
					$subject	= "Installer Registration Alert";
					$email 		= new Email('default');
					$email->profile('default');
					$email->viewVars(array('project_detail' => $this->request->data,'stateArr'=>$statesArr));			
					$email->template('send_developer_reg_query', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
				}*/
				$this->Flash->success($flashmsg);
				//$company_id=1264;
				if(isset($InstallerEntity->id) && $InstallerEntity->id>0) 
				{   
					if($InstallerEntity->payment_status !=1) {
						return $this->redirect('/developer-verify-otp/'.encode($InstallerEntity->id));
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
		$applicationCategoryData 	= $this->ApplicationCategory->find('all',array('conditions'=>array('status'=>'1' ,'id IN' => [2, 3, 4, 5])))->toArray();
		// /echo"<pre>"; print_r($applicationCategoryData); die();
		$mapDetails 				= $this->DeveloperApplicationCategoryMapping->find('all',array('conditions'=>array('installer_id'=>$InstallerEntity->id)))->toArray();
		$arrMap 					= isset($this->request->data['Developers']['selected_category']) ? explode(",",$this->request->data['Developers']['selected_category']) :  array();
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
		$type_of_applicant 	= $this->ApiToken->arrFirmDropdown;
		$designation 		= $this->ApiToken->arrDesignation;
		$this->set('ins_rate_details',$ins_rate_details);			
		$this->set('installer_detail',$InstallerEntity);
		$this->set('company_id',$pass_comp_id);
		$this->set('developer', $InstallerEntity);	
		$this->set('arr_states',$arr_states);
		$this->set('arrStateData',$arrStateData);
		$this->set('arrDistictData',array());
		$this->set('InstallerErrors',$InstallerEntity->errors());
		$this->set('member_id',$member_id);
		$this->set('Couchdb',$this->Couchdb);
		$this->set('RouteURL',$setURL);
		$this->set('registration_type',$registration_type);
		$this->set('applicationCategoryData',$applicationCategoryData);
		$this->set('designation',$designation);
		$this->set('type_of_applicant',$type_of_applicant);
	}
	public function companylist($char='')
	{
		$this->autoRender = false;

		$result = $this->DeveloperCompany->companylist($char);
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

    	$getinstaller = $this->Developers->find('all',array('conditions'=>array('company_id'=>$this->request->data['company_id'])))->first();

            if(isset($getinstaller) && !empty($getinstaller))
            {
                $x = 4; // Amount of digits
                $min = pow(10,$x);
                $max = (pow(10,$x+1)-1);
                $activation_code	= rand($min, $max);

                $this->Developers->updateAll(
                    array(array("otp" => $activation_code)),
                    array("id" => $getinstaller->id)
                );
                $this->Developers->SendSMSActivationCode($getinstaller->id,$getinstaller->mobile,$activation_code);
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
        $company_list = $this->DeveloperCompany->find('all', array('conditions'=>$condition))->toArray();
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
            $getinstallerdata = $this->Developers->find('all',array('conditions'=>array('company_id'=>$this->request->data['company_id'])))->first();
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
     * developers_update_database
     *
     * Behaviour : public
     *
     * @defination :In order to update databse for installer for state_id
     */
    public function developers_update_database(){
    	$this->autoRender 	= false;
        $all_installer 		= $this->Developers->find('all')->toArray();
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
		$path 			= WWW_ROOT.DEVELOPER_PROFILE_PATH.$installer_id.'/';
		if(!file_exists(DEVELOPER_PROFILE_PATH.$installer_id)){
			@mkdir(DEVELOPER_PROFILE_PATH.$installer_id, 0777,true);
		}
		$updateRequestData 	= $this->Developers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();
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
			$installerData 			= $this->Developers->find('all',array('Fields'=>['otp','id'],'conditions'=>array('id'=>$id)))->first();

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
						$this->Developers->updateAll(array('otp_verified_status'=>1),array('id'=>$id));
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
		$installerData 			= $this->Developers->find('all',array('Fields'=>['otp','otp_email','id','otp_verified_status','otp_email_verified_status','registration_type'],'conditions'=>array('id'=>$id)))->first();
		
		if($installerData->otp_verified_status ==1 && $installerData->otp_email_verified_status ==1) {
			return $this->redirect(URL_HTTP.'developer-payment/'.encode($id));
			/*if($installerData->registration_type == 1){
				$this->setPaymentCustomerData($id);
				$this->Flash->success('Registration done successfully.');
                return $this->redirect(URL_HTTP.'installer-registration-kusum');
			} else {
				return $this->redirect(URL_HTTP.'developer-payment/'.encode($id));
				
			}*/
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
						$this->Developers->updateAll(array('otp_verified_status'=>1),array('id'=>$id));
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
						$this->Developers->updateAll(array('otp_email_verified_status'=>1),array('id'=>$id));
						$this->Flash->set('Email OTP Verified successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);

						$ErrorMessage 	= "Email OTP Verified successfully.";
						$success 		= 1;
					} else {
						$ErrorMessage 	= "Error while otp verification.";
						$success 		= 0;
					}
					
					$this->ApiToken->SetAPIResponse('message',$ErrorMessage);
					$this->ApiToken->SetAPIResponse('success',$success);
					$installerData 		= $this->Developers->find('all',array('Fields'=>['otp_verified_status','otp_email_verified_status','registration_type'],'conditions'=>array('id'=>$id)))->first();
				
					$redirect_payment 	= 0;
					if($installerData->otp_verified_status == 1 && $installerData->otp_email_verified_status == 1 && $installerData->registration_type != 1) {
							
						$redirect_payment 	= 1;
						//$this->Developers->saveDeveloperDetails($id);
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
			$InstallerEntity 	= $this->Developers->find('all',array('conditions'=>array('id'=>$installer_id,'payment_status !='=>1)))->first();
			if(!empty($InstallerEntity)) {
				$x 					= 5; // Amount of digits
				$min 				= pow(10,$x);
				$max 				= (pow(10,$x+1)-1);
				$activation_code    = rand($min, $max);
				$activation_email   = rand($min, $max);
				$sms_mobile 		= $InstallerEntity->mobile;
				if($is_email ==0 || $is_email == '') {
					//$sms_message 		= "Thank you for registering with ".PRODUCT_NAME.". Your activation code is ".$activation_code;
					$this->Developers->SendSMSActivationCode($InstallerEntity->id,$InstallerEntity->mobile,$activation_code);
					$this->Developers->updateAll(
						array("otp" => $activation_code,'otp_created_date' => $this->NOW(),'otp_verified_status'=>'0','modified'=>$this->NOW()),
						array("id" => $InstallerEntity->id)
					);
				}
				if($is_email == 1 || $is_email == '') {
					$to					= $InstallerEntity->email; //$project->customer['email'];
					//$to			= 'pravin.sanghani@yugtia.com'; //$project->customer['email'];
					$subject			= "Developer Registration OTP";
					$email 				= new Email('default');
					$email->profile('default');
					$email->viewVars(array('activation_code' => $activation_email,'URL_VERIFY'=>URL_HTTP.'developer-verify-otp/'.encode($InstallerEntity->id),'installer_name'=>$InstallerEntity->installer_name));			
					$email->template('developer_registration', 'default')
							->emailFormat('html')
							->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
							->to($to)
							->subject(Configure::read('EMAIL_ENV').$subject)
							->send();
					$this->Developers->updateAll(
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
			return $this->redirect(URL_HTTP.'developer-verify-otp/'.encode($id));

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
			$installerData 		= $this->Developers->find('all',array('conditions'=>array('id'=>$id)))->first();
			if($installerData->otp_verified_status != 1 || $installerData->otp_email_verified_status != 1) {
				return $this->redirect(URL_HTTP.'developer-verify-otp/'.encode($id));
			}
			if($installerData->geda_approval == 1) {
				return $this->redirect(URL_HTTP.'developer-registration');
			}
		} else {
			return $this->redirect(URL_HTTP.'developer-registration');
		}
		$this->set('pageTitle','Developer Information');
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
		$InstallerDetails 				= $this->Developers->find('all',array('conditions'=>array('id'=>$installer_id)))->first();
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
				$activation_codes = $this->Developers->generateInstallerActivationCodes();
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
	/**
	 *
	 * new_registration
	 *
	 * Behaviour : public
	 *
	 * @param : 
	 * @defination : Method is use to list installer list payment done but approval pending
	 *
	 */
	public function new_registration()
	{
		$this->setMemberArea();
		$member_type 				= $this->Session->read('Members.member_type');
		$member_id 					= $this->Session->read("Members.id");
		$ses_customer_type 			= $this->Session->read('Members.member_type');
		$authority_account 			= $this->Session->read('Members.authority_account');
		$is_installer 				= false;
		$ALLOWED_APPROVE_GEDAIDS    = ALLOW_DEVELOPERS_ALL_ACCESS;
		$newInstallerRegistration   = ($member_id > 0 && in_array($member_id,$ALLOWED_APPROVE_GEDAIDS))?true:false;
		
		if(empty($member_id) || !$newInstallerRegistration) {
			return $this->redirect(URL_HTTP);
		}
		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$request_status 		= isset($this->request->data['status'])?$this->request->data['status']:'';
		$request_no 			= isset($this->request->data['request_no'])?$this->request->data['request_no']:'';
		$geda_approval_status 	= isset($this->request->data['geda_approval_status'])?$this->request->data['geda_approval_status']:'';
		$installer_name 		= isset($this->request->data['installer_name'])?$this->request->data['installer_name']:'';
		$arrRequestList			= array();
		$arrCondition			= array();
		$download_excel			= isset($this->request->data['download']) ? $this->request->data['download'] : 0;
		$receipt_no 			= isset($this->request->data['receipt_no']) ? $this->request->data['receipt_no'] : '';
		$payment_status 		= isset($this->request->data['payment_status']) ? $this->request->data['payment_status'] : (($authority_account == 1) ? '' : 1);
	       
		
		//$arrCondition['ApplyOnlines.pcr_submited IS '] 			= NULL;

		$this->SortBy		= "Developers.id";
		$this->Direction	= "DESC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$memberApproved 	= '0';
		
		$memberApproved 	= in_array($member_id, ALLOW_DEVELOPERS_ALL_ACCESS) ? '1' : '0';
		if($authority_account == 1) {
			$option['colName']  = array('id','installer_name','city','payment','payment_status','application_category','geda_approval','created','action');
			
			$sortArr 			= array('id'			=> 'Developers.id',
										'installer_name'=> 'Developers.installer_name',
										'city' 			=> 'Developers.city',
										'payment'		=> 'Developers.developer_total_fee',
										'payment_status'=> 'Developers.payment_status',
										'geda_approval'	=> 'geda_approval',
										'created'		=> 'Developers.created');
		} else {
			$option['colName']  = array('id','installer_name','city','payment','application_category','geda_approval','created','action');
			
			$sortArr 			= array('id'			=> 'Developers.id',
									'installer_name'=> 'Developers.installer_name',
									'city' 			=> 'Developers.city',
									'payment'		=> 'Developers.developer_total_fee',
									'geda_approval'	=> 'geda_approval',
									'created'		=> 'Developers.created');
		}
		
		$this->SetSortingVars('Developers',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[0,'DESC']]";
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins 							= array();
		$CountFields	= array('UpdateDetails.id');
		$Fields 		= array('Developers.id',
								'Developers.installer_name',
								'Developers.city',
								'payment'=>'Developers.developer_total_fee',
								'application_category' =>"((CASE WHEN 1=1 THEN(select group_concat(application_category_id) from developer_application_category_mapping where developer_application_category_mapping.installer_id= Developers.id) END))",
								'Developers.payment_status',
								'Developers.created',
								'Developers.geda_approval',
								'Developers.company_id',
								'Developers.e_invoice_url',
								);
		
		//$arrCondition['Developers.status'] 						= 0;
		//$arrCondition['Developers.payment_status'] 				= 1;
		$arrCondition['Developers.id !='] 							= 0;
		if ($installer_name != '') {
			$arrCondition['Developers.installer_name LIKE '] 	= '%'.$installer_name.'%';
		}
		if ($geda_approval_status != '') {
			$arrCondition['Developers.geda_approval'] 			= $geda_approval_status;
		}
		if ($payment_status != '') {
			$arrCondition['Developers.payment_status'] 			= $payment_status;
		}
		$this->paginate['limit'] 	= $this->intLimit;
		$this->paginate['page'] 	= $this->CurrentPage;
		if(!empty($receipt_no))
		{
			array_push($Joins,['table'=>'developer_payment','alias'=>'developer_payment','type'=>'left','conditions'=>'developer_payment.installer_id = Developers.id']);
			array_push($arrCondition,array('developer_payment.payment_status' => 'success','developer_payment.receipt_no like ' => '%'.$receipt_no.'%' ));
		}
		$query_data 	= $this->Developers->find('all',array(	'fields'		=> $Fields,
																	'conditions' 	=> $arrCondition,
																	'join' 			=> $Joins,
																	'order'			=> array($this->SortBy=>$this->Direction)));

		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "Developers.created";
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
			$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
			}]);
		}
		
		$query_data_count 	= $this->Developers->find('all',array('fields'		=> $CountFields,
																	'conditions' 	=> $arrCondition,
																	'join' 			=> $Joins,
														));
	
		if(!empty($from_date) && !empty($end_date))
		{
			$fields_date  	= "Developers.created";
			$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
			$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
			$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
				return $exp->between($fields_date, $StartTime, $EndTime);
			}]);
		}
		if ($this->request->is('ajax') && $download_excel==0)
		{
			
			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);

			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			$arrCategory 	= array();
			$ApplicationCategoryDetails = $this->ApplicationCategory->find('all')->toArray();
			if(!empty($ApplicationCategoryDetails)) {
				foreach($ApplicationCategoryDetails as $categoryData) {
					$arrCategory[$categoryData->id] 	= $categoryData->category_name;
				}
			}
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				
				foreach($option['colName'] as $key) {
					if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else if($key=='created') {
						$temparr[$key]	= date('d-m-Y H:i a',strtotime($val->created));
					}
					else if($key=='installer_name') {
						$temparr[$key]	= '<a href="/developer-registration/'.encode($val->company_id).'" target="_blank">'.$val->installer_name.'</a>';
					}
					else if($key=='geda_approval') {
						$temparr[$key]=($val->geda_approval == 1) ? 'Approved' : (($val->geda_approval == 2) ? 'Query Raised' : (($val->geda_approval == 3) ? 'Installer Replied' : 'Pending'));
					}
					else if($key=='payment_status') {
						$temparr[$key]=($val->payment_status == 1) ? 'Paid' : 'Not Paid';
					}
					else if($key=='application_category') {
						$mappedCategory = explode(",",$val->application_category);
						$strCategory 	= '';
						if(!empty($mappedCategory)) {
							foreach($mappedCategory as $cat_val) {
								$category_name 	= isset($arrCategory[$cat_val]) ? $arrCategory[$cat_val] : '';
								$strCategory 	= $strCategory." ".'<img src="/img/'.$cat_val.'_category.jpeg" width="20px" title="'.$category_name.'" >';
							}
						}
						$temparr[$key] = $strCategory;
					}
					else if($key=='action') {
						
							$temparr[$key]	= '';
						
							if($val->geda_approval != 1 && $val->payment_status == 1) {
								$temparr[$key]	= '<a href="javascript:;" class="dropdown-item SubmitRequest approve_Status" data-id="'. encode($val->id) .'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</a>';
							}
							if($val->payment_status == 1) {

								$PaymentReceiptDetails = $this->DeveloperPayment->find('all', array('conditions' => array('installer_id' => $val->id,'payment_status'=>'success')))->toArray();
								if(!empty($PaymentReceiptDetails)) {
									foreach($PaymentReceiptDetails as $pay_k=>$paymentData) {
										$receiptName 		= '';
										if(!empty($pay_k)) {
											$receiptName 	= $pay_k+1;
										}
										$temparr[$key]	.= '<a href="/download-developer-payment-receipt/'. encode($paymentData->id) .'" target="_blank" class="dropdown-item">
												<i class="fa fa-download"></i> Download Receipt '.$receiptName.'
											</a>';
									}
								}
								/*$temparr[$key]	.= '<a href="/developer-payment-receipt/'. encode($val->id) .'" target="_blank" class="dropdown-item">
												<i class="fa fa-download"></i> Download Receipt
											</a>';*/
							}
							if(isset($val->e_invoice_url) && !empty($val->e_invoice_url)) {
								$temparr[$key]	.= '<a href="'.$val->e_invoice_url.'" target="_blank" class="dropdown-item">
												<i class="fa fa-download"></i> Download E-invoice
											</a>';
							}
							$temparr[$key]	.= '<a href="/developer-registration/'.encode($val->company_id).'" target="_blank" class="dropdown-item">
												<i class="fa fa-eye"></i> View Details
											</a>';
						
						$temparr['action']	= '	<span class="action-row action-btn">
													<div class="dropdown">
														<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Actions <i class="fa fa-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$temparr['action'].'</div>
													</div>
												</span>';
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['Developers']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['Developers']['count']),
									"data"            	=> $out));
			die;
		}

		if($download_excel == 1) {
			
			$PhpExcel 			= $this->PhpExcel;
			$PhpExcel->createExcel();
			
			$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
			$objDrawing->setCoordinates('A1');
			$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
			$j 					= 1;
			$i 					= 1;
			$arrReportFields 	= array('sr_no'			=> "Sr no",
										'installer_name'=> 'Installer Name',
										'email'			=> "Email",
										'city'			=> "City",
										'payment_status'=> "Payment Status",
										'created'		=> "Created Date"
										);
			
			//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
			foreach ($arrReportFields as $key=>$Field_Name) {
				$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

				$ColTitle  	= $Field_Name;
				$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
				$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
				$i++;
			}

			$j++;
			$i = 1;
			
			
			$InsData 	= $query_data->toArray();

			if(!empty($InsData)){
				foreach($InsData as $key=>$val) {

					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$j-1);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->installer_name);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->email);
					$i++;
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$val->city);
					$i++;
					
					$InstallerStatus 	= ($val->geda_approval == 1) ? 'Approved' : (($val->geda_approval == 2) ? 'Query Raised' : (($val->geda_approval == 3) ? 'Installer Replied' : 'Pending'));
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$InstallerStatus);
					$i++;
					$createdDate	= date('m-d-Y H:i a',strtotime($val->created));
					$RowName = $this->FeesReturn->GetExcelColumnName($i);
					$PhpExcel->writeCellValue($RowName.$j,$createdDate);
					$i++;

					$i=1;
					$j++;
				}
			}
			$PhpExcel->downloadFile(time());
			exit;
		}
		$REQUEST_STATUS 	= array("0"=>"Pending","1"=>"Approved","2"=>"Query Raised","3"=>"Developer Replied");
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		$PAYMENT_STATUS 	= array("1"=>"Paid","0"=>"Not Paid");
		
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("PAYMENT_STATUS",$PAYMENT_STATUS);
		$this->set("pagetitle",'New Developers - Rooftop');
		$this->set("page_count",0);
		$this->set("memberApproved",$memberApproved);
		$this->set("authority_account",$authority_account);
	}
		/**
	 *
	 * ApproveRegistration
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of installer registration.
	 *
	 */
	public function ApproveRegistration()
	{
		//echo"<pre>"; print_r($this->request->data); die();
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['insid']) ? (decode($this->request->data['insid'])) : 0);
		$geda_approval    	= (isset($this->request->data['geda_approval']) ? $this->request->data['geda_approval'] : 0);
		$reject_reason    	= (isset($this->request->data['reject_reason']) ? $this->request->data['reject_reason'] : 0);


		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$ErrorMessage 		= '';
		$success        	= 0;
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$DevelopersData  	= $this->Developers->find("all",['conditions'=>['id'=>$id]])->first();

			if (!empty($DevelopersData)) {
				if ($this->request->is('post') || $this->request->is('put')) {

					$arrDeveloper = $this->Developers->find('all',
						[
							'fields'=> ['Developers.id','Developers.company_id','Developers.email','developer_customers.email','Developers.mobile','developer_passwords.password','Developers.contact_person'],
							'join'=>[
										[   'table'=>'developer_passwords',
											'type'=>'INNER',
											'conditions'=>'developer_passwords.installer_id = Developers.id'
										],
										[   'table'=>'developer_customers',
											'type'=>'INNER',
											'conditions'=>'developer_customers.installer_id = Developers.id'
										]
									],
							'conditions'=>['Developers.id'=>$id],
							'order'=>['Developers.id'=>'ASC']
						]
					)->first();

					
					if (!empty($arrDeveloper))
					{
						if($geda_approval == 1) {
							//echo "\r\n--".$arrDeveloper->id." -- ".$arrDeveloper->email." -- ".$arrDeveloper->developer_customers['email']." -- ".$arrDeveloper->mobile." -- ".$arrDeveloper->developer_passwords['password']."--\r\n";
							
							$regNo 					= str_pad($arrDeveloper->id,5, "0", STR_PAD_LEFT);
							$financialyear  		= $this->GetGenerateFinancialYear(date('Y-m-d'));
							$registration_no 		= 'GUJ/DEV/'.$financialyear.'/'.$regNo;
							$template_name	= 'developer_registration_login';
							$EmailVars 	= array( 'EMAIL_ADDRESS' 	=> $arrDeveloper->developer_customers['email'],
												'PASSWD' 			=> $arrDeveloper->developer_passwords['password'],
												'CONTACT_NAME' 		=> $arrDeveloper->contact_person,
												'TRANSACTION_NO'	=> '',
												'REGISTRATION_NO'	=>$registration_no,
												'URL_HTTP'			=> URL_HTTP);
							
							$subject        = "Unified Single Window ".RE_SHORT_NAME." Portal Login Details";
							
							$EmailTo        = $arrDeveloper->developer_customers['email'];
							
							
							$email 		= new Email('default');
							$email->profile('default');
							$email->viewVars($EmailVars);
							$message_send = $email->template($template_name, 'default')
								->emailFormat('html')
								->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
								->to($EmailTo)
								->bcc('pulkitdhingra@gmail.com')
								->subject(Configure::read('EMAIL_ENV').$subject)
								->send();

							
							$Emaillog           	= $this->Emaillog->newEntity();
							$Emaillog->email  		= $EmailTo;
							$Emaillog->send_date  	= $this->NOW();
							$Emaillog->action 		= "Developer Password Information";
							$Emaillog->description  = json_encode(array( 
									'EMAIL_ADDRESS' => $arrDeveloper->developer_customers['email'],
									'PASSWD' 		=> $arrDeveloper->developer_passwords['password'],
									'CONTACT_NAME' 	=> $arrDeveloper->contact_person,
									'TRANSACTION_NO'=> isset($paymentData->installer_payment['transaction_id']) ? $paymentData->installer_payment['transaction_id'] : '',
									'REGISTRATION_NO'=> $registration_no,
									'URL_HTTP'		=>URL_HTTP));
							$this->Emaillog->save($Emaillog);
							
							$this->Developers->updateAll(array('status'=>$this->Customers->STATUS_ACTIVE,'modified'=>$this->NOW()),array('id'=>$arrDeveloper->id));
							$this->DeveloperCustomers->updateAll(array('status'=>$this->Customers->STATUS_ACTIVE,'developer_registration_no'=>$registration_no),array('installer_id'=>$arrDeveloper->id));
							$this->send_developer_data($id);
						}
						else if($geda_approval == 2) {
							$EmailTo        = $arrDeveloper->developer_customers['email'];
							
							$subject        = PRODUCT_NAME." Login Details";
							$EmailVars 		= array( 'CONTACT_NAME' => $arrDeveloper->contact_person,
													'URL_HTTP'		=> URL_HTTP,
													'QUERY_RAISED' => $reject_reason,
													'LINK_URL' 		=> URL_HTTP.'developer-registration/'.encode($arrDeveloper->company_id));
							//->bcc('pulkitdhingra@gmail.com')
									
							$email 		= new Email('default');
							$email->profile('default');
							$email->viewVars($EmailVars);
							$message_send = $email->template('developer_registration_rejection', 'default')
								->emailFormat('html')
								->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
								->to($EmailTo)
								->subject(Configure::read('EMAIL_ENV').$subject)
								->send();
							
							$member_id          					= $this->Session->read("Members.id");
							$member_type 							= $this->Session->read('Members.member_type');
							$browser 								= isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:"-";
							$DeveloperMessageEntity					= $this->DeveloperMessage->newEntity();
							$DeveloperMessageEntity->installer_id 	= $arrDeveloper->id;
							$DeveloperMessageEntity->message 		= strip_tags($reject_reason);
							$DeveloperMessageEntity->user_type 		= $member_type;
							$DeveloperMessageEntity->user_id 		= $member_id;
							$DeveloperMessageEntity->ip_address 	= $this->IP_ADDRESS;
							$DeveloperMessageEntity->created 		= $this->NOW();
							$DeveloperMessageEntity->browser_info 	= json_encode($browser);
							
							$this->DeveloperMessage->save($DeveloperMessageEntity);

							$Emaillog                  = $this->Emaillog->newEntity();
							$Emaillog->email           = $EmailTo;
							$Emaillog->send_date       = $this->NOW();
							$Emaillog->action          = "Query Raised for Developer";
							$Emaillog->description     = json_encode(array( 
									'CONTACT_NAME' 	=> $arrDeveloper->contact_person,
									'URL_HTTP'		=>URL_HTTP.'developer-registration/'.encode($arrDeveloper->company_id)));
							$this->Emaillog->save($Emaillog);
						}
						$ErrorMessage   	= "Registration Status Updated Sucessfully.";
						$success        	= 1;
						//echo"<pre>"; print_r($success); die();
						$this->Developers->updateAll(array('geda_approval'=>$geda_approval,
							'approved_by'=>$memberId,'reject_reason'=>$reject_reason,'modified'=>$this->NOW()),array('id'=>$arrDeveloper->id));
					}
				}
			} else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
	}
	/**
	 *
	 * fetchDeveloper
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch installer data.
	 *
	 */
	public function fetchDeveloper()
	{
		$this->autoRender   = false;
		$response 			= '';
		$developerid 		= intval(decode($this->request->data['developerid']));
		$dev_fetchData 		= $this->Developers->find("all",['conditions'=>['id'=>$developerid]])->first();
		if(!empty($dev_fetchData))
		{
			$latest_stage 					= $dev_fetchData->geda_approval;
			$dev_fetchData->geda_approval 	= ($dev_fetchData->geda_approval==3) ? 2 : $dev_fetchData->geda_approval;
			$response						= $dev_fetchData;
			$response->latest_stage			= $latest_stage;

		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
	public function GetGenerateFinancialYear($date='')
	{
		$Month   	= date("m",strtotime($date));
		$Year   	= date("Y",strtotime($date));
		$ChallanNo  = "";
		if (intval($Month) >= 1 && intval($Month) <= 3) {
		$ChallanNo  .= ($Year-1)."-".date("y",strtotime($date));
		} else {
		$ChallanNo  .= $Year."-".(date("y",strtotime($date))+1);
		}
		//$ChallanNo .= str_pad($recipt_no,4,"0",STR_PAD_LEFT);
		return $ChallanNo;
	}
	/**
	 *
	 * fetchDeveloperDetails
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to fetch developer details data.
	 *
	 */
	public function fetchDeveloperDetails()
	{
		$this->autoRender   = false;
		$response 			= array();
		$developerid 		= intval(decode($this->request->data['developer_id']));
		$dev_fetchData 		= $this->Developers->find("all",['conditions'=>['id'=>$developerid]])->first();
		if(!empty($dev_fetchData))
		{
			$response['mobile']					= $dev_fetchData->mobile;
			$response['email']					= $dev_fetchData->email;
			$response['developer_name']			= $dev_fetchData->installer_name;
			$response['developer_contact_name']	= $dev_fetchData->contact_person;
			$response['state']					= $dev_fetchData->state;
			$response['city']					= $dev_fetchData->city;
			$type 								= 'ok';
			$msg 								= 'Success';
		} else {
			$type 								= 'error';
			$msg 								= 'Details not found';
		}
		echo json_encode(array('type'=>$type,'msg'=>$msg,'response'=>$response));
		exit;
	}
	/**
	* downloadDeveloperPayemntPdf
	* Behaviour : Public
	* @defination : Method is use to view installer
	*/
	public function downloadDeveloperPaymentPdf($id = null)
	{
		if(!empty($this->Session->read('Members.member_type')))
		{
			$customerId = $this->Session->read("Members.id");
		}
		else
		{
			$customerId = $this->Session->read("Customers.id");
		}

		if(empty($customerId))
		{
			return $this->redirect('/home');
		}
		$developer_data = $this->Developers->generateDeveloperReceiptPdf($id,true);
		
		if(empty($developer_data)) {
			$this->Flash->error('Please Select Valid Application.');
			return $this->redirect('home');
		}
	}
	public function send_developer_data($id){


		$data = $this->Developers->find('all',
						[ 'fields'=> ['Developers.id','Developers.district','developer_companies.company_name','developer_customers.password','developer_customers.developer_registration_no','Developers.installer_name','Developers.contact_person','Developers.designation','Developers.address','Developers.address1','Developers.taluka','Developers.pincode','Developers.city','Developers.state','district_master.name','Developers.district_code','Developers.type_of_applicant','Developers.contact1','Developers.mobile','developer_customers.email','Developers.website','Developers.pan','Developers.status','Developers.GST','Developers.upload_undertaking','Developers.msme','Developers.d_msme','Developers.name_director','Developers.type_director','Developers.type_director_others','Developers.director_whatsapp','Developers.director_mobile','Developers.director_email','Developers.name_authority','Developers.type_authority','Developers.type_authority_others','Developers.d_file_board','Developers.authority_whatsapp','Developers.authority_mobile','Developers.authority_email','Developers.pan_card','Developers.gst_certificate','Developers.registration_document','Developers.geda_approval','Developers.approved_by','Developers.e_invoice_url','Developers.stateflg'],
							'join'=>[[   'table'=>'developer_companies',
											'type'=>'left',
											'conditions'=>'developer_companies.id = Developers.company_id'
										],[   'table'=>'developer_customers',
											'type'=>'left',
											'conditions'=>'developer_customers.installer_id = Developers.id'
										],[   'table'=>'district_master',
											'type'=>'left',
											'conditions'=>'district_master.id = Developers.district'
										]],'conditions'=>['Developers.id'=>$id],'order'=>['Developers.id'=>'ASC']])->first();
		//echo"<pre>"; print_r($data); 
		if(!empty($data['id'])){
				//$apiUrl = 'https://akshayurjasetu.guvnl.com/API/saveApprovedDeveloperData.php';
				$apiUrl = 'https://devakshayurjasetu.guvnl.com/API/saveApprovedDeveloperData.php';
				//$apiUrl = Configure::read('serviceFeasibilityCheckApplicationDetails');
				//curl request
				//[{"key":"Authorization","value":"PsPuH#GvLUn^2005","description":"","type":"text","enabled":true}]
				$conn           			= curl_init($apiUrl);
				$arrRequest = array();
				//$arrRequest['Authorization']				= 'PsPuH#GvLUn^2005';
				$arrRequest['id']							= $data['id'];
				$arrRequest['company_name']					= $data['developer_companies']['company_name'];
				$arrRequest['password']						= $data['developer_customers']['password'];
				$arrRequest['developer_registration_no']	= $data['developer_customers']['developer_registration_no'];
				$arrRequest['installer_name']				= $data['installer_name'];
				$arrRequest['contact_person']				= $data['contact_person'];

				$arrRequest['designation']					= $data['designation'];
				$arrRequest['address']						= $data['address'];
				$arrRequest['address1']						= $data['address1'];
				$arrRequest['taluka']						= $data['taluka'];
				$arrRequest['pincode']						= $data['taluka'];
				$arrRequest['city']							= $data['city'];
				$arrRequest['state']						= $data['state'];

				$arrRequest['District']						= $data['district_master']['name'];
				$arrRequest['district_code']				= $data['district_code'];
				$arrRequest['type_of_applicant']			= $data['type_of_applicant'];
				$arrRequest['contact1']						= $data['contact1'];
				$arrRequest['mobile']						= $data['mobile'];
				$arrRequest['email']						= $data['email'];
				$arrRequest['website']						= $data['website'];

				$arrRequest['pan']							= $data['pan'];
				$arrRequest['status']						= $data['status'];
				$arrRequest['GST']							= $data['GST'];
				$arrRequest['upload_undertaking']			= $data['upload_undertaking'];
				$arrRequest['msme']							= $data['msme'];
				$arrRequest['d_msme']						= $data['d_msme'];
				$arrRequest['name_director']				= $data['name_director'];

				$arrRequest['type_director']				= $data['type_director'];
				$arrRequest['type_director_others']			= $data['type_director_others'];
				$arrRequest['director_whatsapp']			= $data['director_whatsapp'];
				$arrRequest['director_mobile']				= $data['director_mobile'];
				$arrRequest['director_email']				= $data['director_email'];
				$arrRequest['name_authority']				= $data['name_authority'];
				$arrRequest['type_authority']				= $data['type_authority'];

				$arrRequest['type_authority_others']		= $data['type_authority_others'];
				$arrRequest['d_file_board']					= $data['d_file_board'];
				$arrRequest['authority_whatsapp']			= $data['authority_whatsapp'];
				$arrRequest['authority_mobile']				= $data['authority_mobile'];
				$arrRequest['authority_email']				= $data['authority_email'];
				$arrRequest['pan_card']						= $data['pan_card'];
				$arrRequest['pan_card']						= $data['pan_card'];
				
				$arrRequest['registration_document']		= $data['registration_document'];
				$arrRequest['geda_approval']				= $data['geda_approval'];
				$arrRequest['approved_by']					= $data['approved_by'];
				$arrRequest['e_invoice_url']				= $data['e_invoice_url'];
				$arrRequest['stateflg']						= $data['stateflg'];
				

				$conn    = curl_init($apiUrl);

				curl_setopt($conn, CURLOPT_CONNECTTIMEOUT, 300);
				curl_setopt($conn, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($conn, CURLOPT_SSL_VERIFYHOST, 0);
				curl_setopt($conn, CURLOPT_HTTPHEADER, [
				    "Authorization: PsPuH#GvLUn^2005"
				]);
				curl_setopt($conn, CURLOPT_FOLLOWLOCATION, true);
				curl_setopt($conn, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($conn, CURLOPT_POSTFIELDS, http_build_query($arrRequest));
			
				$response = curl_exec($conn);
				//echo"<pre>"; print_r($response); die();
				curl_close ($conn);
				if(!empty($response)){
					
					$id 								= intval(decode($id));
					$thirdpartyEntity                   = $this->ThirdpartyApiLog->newEntity(); 
					$thirdpartyEntity->application_id   = $id;
					$thirdpartyEntity->project_id       = 0; 
					$thirdpartyEntity->request_data     = json_encode($arrRequest);
					$thirdpartyEntity->response_data    = $response;
					$thirdpartyEntity->api_url          = $apiUrl;
					$thirdpartyEntity->created          = $this->NOW();
					$this->ThirdpartyApiLog->save($thirdpartyEntity);
				}
				return true;
				//exit;//return $response;
			}
		
			return true;

	}
}
?>
