<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Dompdf\Dompdf;

use Cake\Utility\Security;
use Cake\Auth\DefaultPasswordHasher;


class MemberController extends FrontAppController
{
	public function initialize()
	{
		parent::initialize();
		$this->loadComponent('Flash');
		$this->loadModel('Contactus');
		$this->loadModel('Projects');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('BranchMasters');
		$this->loadModel('Customers');
		$this->loadModel('Installers');
		$this->loadModel('Members');
		$this->loadModel('Subscribers');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('ApplyonlineUnReadMessage');
		$this->loadModel('ApplicationRequestDelete');
		if($this->request->params['action']=='changepassword' || $this->request->params['action']=='updateprofile')
		{
			//$this->loadComponent('Csrf');
		}
		$this->set('Userright',$this->Userright);

	}

	public function beforeFilter(Event $event)
	{
		//parent::beforeFilter($event);
		// Allow users to register and logout.
		// You should not add the "login" action to allow list. Doing so would
		// cause problems with normal functioning of AuthComponent.
		//$this->Auth->allow(['register', 'logout','index']);
	}

	public function index()
	{
		$pageTitle          = "Dashboard";
		$this->setMemberArea();
		$main_branch_id     = '';

		$member_id          = $this->Session->read("Members.id");
		$member_type 	    = $this->Session->read('Members.member_type');
		$area 			    = $this->Session->read('Members.area');
		$circle 		    = $this->Session->read('Members.circle');
		$division 		    = $this->Session->read('Members.division');
		$subdivision 	    = $this->Session->read('Members.subdivision');
		$section 		    = $this->Session->read('Members.section');
		$authority_account 	= $this->Session->read('Members.authority_account');

		$field      = "area";
		$id         = $area;
		if (!empty($section)) {
			$field      = "section";
			$id         = $section;
		} else if (!empty($subdivision)) {
			$field      = "subdivision";
			$id         = $subdivision;
		} else if (!empty($division)) {
			$field      = "division";
			$id         = $division;
		} else if (!empty($circle)) {
			$field      = "circle";
			$id         = $circle;
		}
		$DonwloadDiscomMIS  = false;
		$isDonwloadMIS  	= false;
		$loginArea          = '';
		$main_branch_id     = array("field"=>$field,"id"=>$id,"member_type"=>$member_type);
		$customerSession    = $this->Session->read('Members');
		$MembersData        = $this->Members->get($member_id);
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->JREDA)
		{
			$isDonwloadMIS  = true;
		}
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			if(empty($MembersData->circle) && empty($MembersData->division) && empty($MembersData->subdivision))
			{
				$DonwloadDiscomMIS  = true;
				$loginArea          = "_".$MembersData->area;
				$discom_short_name  = $this->DiscomMaster->find("all",['conditions'=>['id'=>$MembersData->area]])->first();
				$loginArea          = "_".$MembersData->area;
				if(!empty($discom_short_name))
				{
					//$loginArea      = "_".$discom_short_name->title;
					$loginArea      = "_".str_replace(array(" "),array("_"),$discom_short_name->title);
				}
			}
			
		}

		$this->set('getProjectClusterData',$this->getProjectClusterData());

		$IndividualStatus = array($this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED);

		//$this->set('TotalApplicationSubmitted_new',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));
		$this->set('TotalApplicationSubmitted',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED));
		$this->set('TotalSubmittedPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1));

		$this->set('TotalApplicationGEDALetter',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA));
		$this->set('TotalGEDALetterPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,1));

		$this->set('TotalApplicationVerified',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED));
		$this->set('TotalVerifiedPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,1));

		$this->set('TotalApplicationNotVerified',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED));
		$this->set('TotalNotVerifiedPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,1));

		$this->set('TotalApplicationRejected',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED));
		$this->set('TotalRejectedPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_CANCELLED,1));

		$this->set('TotalApplicationMeterInstalled',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->METER_INSTALLATION));
		$this->set('TotalMeterInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->METER_INSTALLATION,1));

		//$this->set('TotalApplicationNonSubsidy',$this->ApplyOnlines->TotalApplicationBySubsidy($customerSession['state'],$main_branch_id,'1'));
		//$this->set('TotalNonSubsidyInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationNonSubsidyPVCapacity($customerSession['state'],$main_branch_id,'1'));
		$arrPassRequest 						= array();
		$arrPassRequest['disclaimer_subsidy']	= 1;
		$this->set('TotalApplicationNonSubsidy',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest));
		$this->set('TotalNonSubsidyInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest));

		/*$this->set('TotalApplicationPCR',$this->ApplyOnlines->TotalApplicationByPCR($customerSession['state'],$main_branch_id));
		$this->set('TotalPCRInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationPcrPVCapacity($customerSession['state'],$main_branch_id));*/
		$arrPassRequest 			= array();
		$arrPassRequest['pcr_code']	= 1;
		$this->set('TotalApplicationPCR',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest));
		$this->set('TotalPCRInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest));

		/*$this->set('TotalApplicationPCRSubmitted',$this->ApplyOnlines->TotalApplicationByPCRSubmitted($customerSession['state'],$main_branch_id));
		$this->set('TotalPCRInstalledPVCapacitySubmitted',$this->ApplyOnlines->TotalApplicationPcrSubmittedPVCapacity($customerSession['state'],$main_branch_id));*/
		$arrPassRequest 				= array();
		$arrPassRequest['pcr_submited']	= 1;
		$this->set('TotalApplicationPCRSubmitted',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest));
		$this->set('TotalPCRInstalledPVCapacitySubmitted',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest));

		/*$this->set('TotalApplicationSocial',$this->ApplyOnlines->TotalApplicationBySocial($customerSession['state'],$main_branch_id));
		$this->set('TotalSocialInstalledPVCapacity',$this->ApplyOnlines->TotalApplicationSocialPVCapacity($customerSession['state'],$main_branch_id));*/

		$arrPassRequest 					= array();
		$arrPassRequest['social_consumer']	= 1;
		$this->set('TotalApplicationSocial',0); //$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest)
		$this->set('TotalSocialInstalledPVCapacity',0); //$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest)

		/*$this->set('TotalApplicationResidential',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_residental));
		$this->set('TotalResidentialPVCapacity',_FormatGroupNumberV2($this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_residental)));

		$this->set('TotalApplicationInsCom',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_industrial)+$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_commercial));
		$InsCapacity   = $this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_industrial);
		$ComCapacity    = $this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_commercial);
		$this->set('TotalInsComPVCapacity',_FormatGroupNumberV2($InsCapacity+$ComCapacity));

		$this->set('TotalApplicationHT',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_ht_indus));
		$this->set('TotalHTPVCapacity',_FormatGroupNumberV2($this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_ht_indus)));

		$this->set('TotalApplicationOthers',$this->ApplyOnlines->TotalApplicationByCategory($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_others));
		$this->set('TotalOthersPVCapacity',_FormatGroupNumberV2($this->ApplyOnlines->TotalApplicationCategoryCapacity($customerSession['state'],$main_branch_id,$this->ApplyOnlines->category_others)));*/
		$arrPassRequest 				= array();
		$arrPassRequest['category']	= $this->ApplyOnlines->category_residental;
		$this->set('TotalApplicationResidential',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest));
		$this->set('TotalResidentialPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest));

		/*$arrPassRequest 				= array();
		$arrPassRequest['category']		= $this->ApplyOnlines->category_industrial;
		$industrialCount	 			= $this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest);
		$industrialCapacity	 			= $this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest);*/
		//pr($industrialCapacity);

		$arrPassRequest 				= array();
		$arrPassRequest['category']		= array($this->ApplyOnlines->category_commercial,$this->ApplyOnlines->category_industrial);
		$commercialIndustrialCount	 	= $this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest);
		$commercialIndustrialCapacity	= $this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest);
		//pr($commercialCapacity);

		$this->set('TotalApplicationInsCom',$commercialIndustrialCount);
		$this->set('TotalInsComPVCapacity',$commercialIndustrialCapacity);

		$arrPassRequest 				= array();
		$arrPassRequest['category']		= $this->ApplyOnlines->category_ht_indus;
		$this->set('TotalApplicationHT',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest));
		$this->set('TotalHTPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest));

		$arrPassRequest 				= array();
		$arrPassRequest['category']		= $this->ApplyOnlines->category_others;
		$this->set('TotalApplicationOthers',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,0,$arrPassRequest));
		$this->set('TotalOthersPVCapacity',$this->ApplyOnlines->TotalApplicationByStatusView($customerSession['state'],$main_branch_id,$this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,1,$arrPassRequest));

		$this->set('TotalApplicationInspection',$this->ApplyOnlines->TotalApplicationByInspection($customerSession['state'],$main_branch_id));
		$this->set('TotalInspectionPVCapacity',$this->ApplyOnlines->TotalApplicationInspectionCapacity($customerSession['state'],$main_branch_id));

		$this->set('TotalMSMEPending',$this->ApplyOnlines->TotalApplicationByMsme($customerSession['state'],$main_branch_id));
		$this->set('TotalMSMEPendingPVCapacity',$this->ApplyOnlines->TotalApplicationByMsme($customerSession['state'],$main_branch_id,1));

		$this->set('TotalADRPending',$this->ApplicationRequestDelete->TotalApplicationDeleteRequest($customerSession['state'],$main_branch_id));
		$this->set('TotalADRPendingPVCapacity',$this->ApplicationRequestDelete->TotalApplicationDeleteRequest($customerSession['state'],$main_branch_id,1));

		$CHART_HEADER           = "'Month', 'Application Submitted', 'Registration', 'Documents Verified', 'Docs. Verification Pending','Application Cancelled','Meter Installed'";
		$arrStatus              = array($this->ApplyOnlineApprovals->APPLICATION_SUBMITTED,
										$this->ApplyOnlineApprovals->APPROVED_FROM_GEDA,
										$this->ApplyOnlineApprovals->DOCUMENT_VERIFIED,
										$this->ApplyOnlineApprovals->DOCUMENT_NOT_VERIFIED,
										$this->ApplyOnlineApprovals->APPLICATION_CANCELLED,
										$this->ApplyOnlineApprovals->METER_INSTALLATION);
		$MonthWiseStatistics    = $this->ApplyOnlines->MonthwiseApplicationStatistics($customerSession['state'],$main_branch_id,$arrStatus,$IndividualStatus);
		$this->set('CHART_HEADER',$CHART_HEADER);
		$this->set('arrStatusCode',$this->ApplyOnlines->arrDashboardBlocks);
		$this->set('MonthWiseStatistics',$MonthWiseStatistics);
		$this->set('member_id',$member_id);
		$this->set('UnReadMessages',$this->ApplyonlineUnReadMessage->getUnreadMessageCount($member_id));
		$this->set("DonwloadDiscomMIS",$DonwloadDiscomMIS);
		$this->set("loginArea",$loginArea);
		$this->set("isDonwloadMIS",$isDonwloadMIS);
		$this->set(compact('pageTitle','customerSession'));
		$this->set("authority_account",$authority_account);
	}

	private function getTypewiseProjects()
	{
		$resultArray        = array();
		$TypewiseProjects   = $this->Projects->find();
		$TypewiseProjects->hydrate(false);
		$TypewiseProjects->select(['Parameters.para_value','count' => $TypewiseProjects->func()->count('Projects.id')])->group('Projects.customer_type');
		$TypewiseProjects->join([
									'table' => 'parameters',
									'alias' => 'Parameters',
									'type' => 'INNER',
									'conditions' => 'Parameters.para_id = Projects.customer_type',
								]);
		$arrResult = $TypewiseProjects->toList();
		if (!empty($arrResult)) {
			foreach ($arrResult as $Row) {
				$resultArray[$Row['Parameters']['para_value']] = $Row['count'];
			}
		}
		return $resultArray;
	}

	public function updateprofile()
	{
		$this->setMemberArea();
		$pageTitle = "Update Profile";
		$customerId = $this->Session->read('Members.id');
		if(!isset($customerId) || empty($customerId))
		{
			return $this->redirect(URL_HTTP.'home');
		}
		$user = $this->Members->find('all')
				->where(['Members.id' => $customerId])
				->first();
		$userEntity = $this->Members->patchEntity($user, $this->request->data,['validate' => 'customer']);
		if (!$userEntity->errors() && !empty($this->request->data)) {
			$this->Members->patchEntity($user, $this->request->data);
			if($this->Members->save($user)) {
				$this->Flash->success(__('Your profile has been updated.'));
				return $this->redirect(['action' => 'index']);
			}
			$this->Flash->error(__('Unable to update your profile.'));
		}

		$this->set(compact('user','pageTitle'));
	}

	public function changepassword()
	{
		$this->setMemberArea();
		if($this->request->is('mobile')) {
			$this->autoRender = false;
			$this->SetVariables($this->request->data);
			$oldpassword	= $this->request->data('pass');
			$new_password	= $this->request->data('new_pass');
			$cus_id			= $this->ApiToken->customer_id;
			$user =$this->Members->get($cus_id);

		$custData 		= $this->Members->get($cus_id);
		} else {
			$pageTitle = "Change Password";
			$user =$this->Members->get($this->Session->read('Members.id'));
		}
		if (!empty($this->request->data)) {
			$this->request->data['old_password']= $this->convert_pass($this->request->data['old_password']);
			$this->request->data['password1']   = $this->convert_pass($this->request->data['password1']);
			$this->request->data['password2']   = $this->convert_pass($this->request->data['password2']);
			$user = $this->Members->patchEntity($user, [
					'old_password'  => $this->request->data['old_password'],
					'password'      => $this->request->data['password1'],
					'password1'     => $this->request->data['password1'],
					'password2'     => $this->request->data['password2']
				],
				['validate' => 'password']
			);
			if(!$user->errors()){
				if(!empty($user) && $this->Members->ChangeMemberPassword($user,$this->request->data['password1'])) {
					if($this->request->is('mobile')) {
						$this->ApiToken->SetAPIResponse('type', 'ok');
						$this->ApiToken->SetAPIResponse('msg', 'Password changed successfully.');
					} else {
						$this->Flash->success('The password is successfully changed');
						$this->redirect(URL_HTTP.'member/changepassword');
					}
				} else {
					if($this->request->is('mobile')) {
						$this->ApiToken->SetAPIResponse('type', 'error');
						$this->ApiToken->SetAPIResponse('msg', 'User not found.');
					} else {
						$this->Flash->error('Error while password changed!');
					}
				}
			}
		}
		if(!$this->request->is('mobile')) {
			$this->set(compact('user','pageTitle'));
		}
	}

	private function random_color_marker($map_icons)
	{
		$found      = true;
		$counter    = 0;
		$img_name   = "pin56_[RAND_NUM].png";
		while ($found) {
			$RAND_NUM   = rand(0,21);
			$color_name = str_replace("[RAND_NUM]",$RAND_NUM,$img_name);
			if (!in_array($color_name,$map_icons)) {
				$found = false;
			}
			if ($counter == 5) {
				$found = false;
			}
			$counter++;
		}
		return $color_name;
	}

   /**
	* Function Name : getProjectClusterData
	* @param
	* @return
	* @author Kalpak Prajapati
	*/
	function getProjectClusterData()
	{
		$arrResult['data']      = array();
		$arrResult['map_style'] = '';
		$arrResult['map_icons'] = '';
		$resultArray            = array();
		$TypewiseProjects       = $this->Projects->find();
		$TypewiseProjects->hydrate(false);
		$TypewiseProjects->select(['Parameters.para_value','Projects.customer_type','Projects.latitude','Projects.longitude']);
		$TypewiseProjects->join(    [
										[
											'table' => 'parameters',
											'alias' => 'Parameters',
											'type' => 'INNER',
											'conditions' => 'Parameters.para_id = Projects.customer_type',
										],
										[
											'table' => 'apply_onlines',
											'alias' => 'ApplyOnlines',
											'type' => 'INNER',
											'conditions' => 'ApplyOnlines.project_id = Projects.id',
										]
									]
								);
		$TypewiseProjects->where(['ApplyOnlines.application_status NOT IN '=> array(22,29,30,99),'ApplyOnlines.application_status > ' => 0]);
		$arrResult      = $TypewiseProjects->toList();
		$map_icons      = array();
		$map_style      = array();
		$Counter        = 0;
		$Prev_Group_Id  = 0;
		if (!empty($arrResult)) {
			foreach ($arrResult as $Row) {
				$TAG = preg_replace("/[^0-9a-z]/i","",strtolower($Row['Parameters']['para_value']));
				if (!isset($map_icons[$Row['customer_type']])) {
					$COLOR_CODE     = $this->random_color_marker($map_style);
					array_push($map_style,$COLOR_CODE);
					$map_icons[$Row['customer_type']]   = array("group"=>$Row['Parameters']['para_value'],
																"lbl"=>$TAG,
																"count"=>0,
																"icon"=>$COLOR_CODE);

					if ($Prev_Group_Id > 0) {
						$map_icons[$Prev_Group_Id]['count'] = $Counter;
						$Counter = 0;
					}
					$Prev_Group_Id = $Row['customer_type'];
				}
				$arrResult['data'][] = array(   "lat"=>$Row['latitude'],
												"lng"=>$Row['longitude'],
												"options"=>array("icon"=>"/img/mapIcons/pins/".$map_icons[$Row['customer_type']]['icon']),
												"tag"=>$TAG);

				$Counter++;
			}
			if ($Prev_Group_Id > 0) {
				$map_icons[$Prev_Group_Id]['count'] = $Counter;
				$Counter = 0;
			}
		}
		$arrResult['map_icons'] = $map_icons;
		return $arrResult;
	}
	/**
	 *
	 * convert_pass
	 *
	 * Behaviour : Public
	 * @param : pass encrypted password to this function.
	 * @defination : Method is use to decrypt the javascript encoded password
	 *
	 */
	public function convert_pass($subpass)
	{
		if(isset($subpass) && !empty($subpass)) {
			$first 		= substr($subpass,10);
			$last 		= substr($subpass,-10);

			$subpass 	= base64_decode(str_replace(array($last), array(''), $first));

		}
		return $subpass;
	}
	/**
	 *
	 * changepasswordforce
	 *
	 * Behaviour : Public
	 * @param :
	 * @defination : Method is use to change Password of member login who have initial password.
	 *
	 */
	public function changepasswordforce()
	{
		$pageTitle  = "Change Password";
		if(empty($this->Session->read('Members.idPass'))) {
			return $this->redirect(URL_HTTP.'home');
		}
		
		$user       = $this->Members->get($this->Session->read('Members.idPass'));
		if (!empty($this->request->data)) {
			$this->request->data['old_password']= $this->convert_pass($this->request->data['old_password']);
			$this->request->data['password1']   = $this->convert_pass($this->request->data['password1']);
			$this->request->data['password2']   = $this->convert_pass($this->request->data['password2']);

			$user = $this->Members->patchEntity($user, [
					'old_password'  => $this->request->data['old_password'],
					'password'      => $this->request->data['password1'],
					'password1'     => $this->request->data['password1'],
					'password2'     => $this->request->data['password2']
				],
				['validate' => 'password']
			);

			if(!$user->errors()){
				
				if(!empty($user) && $this->Members->ChangeMemberPassword($user,$this->request->data['password1'])) {
					$this->Flash->success('The password is successfully changed');
					$this->Session->destroy();
					$this->redirect(URL_HTTP);
				} else {
					$this->Flash->error('Error while password changed!');
				}
			}
		}
		$this->set('pageTitle',$pageTitle);
		$this->set('user',$user);
	}

	/**
	 *
	 * forgotpassword
	 *
	 * Behaviour : Public
	 * @param :
	 * @defination : Method is use to change Password of member login.
	 *
	 */
	public function forgotpassword()
	{
		$pageTitle  = "Forgot Password";
		$customerId = $this->Session->read('Members.id');
		$hdnaction	= "";
		if(!empty($customerId)) {
			return $this->redirect(URL_HTTP.'home');
		}
		if (!empty($this->request->data)) {
			$MemberID 	= isset($this->request->data['LOGIN_ID'])?$this->request->data['LOGIN_ID']:"";
			$OTP 		= isset($this->request->data['code'])?$this->request->data['code']:"";
			$hdnaction 	= isset($this->request->data['hdnaction'])?$this->request->data['hdnaction']:"";
			if (!empty($MemberID) && $hdnaction == "send_otp") {
				$captchaValidation = $this->captchaValidation();

				if($captchaValidation =='0')
				{
					$status 			= 'error';
					$error				= 'Incorrect Captcha';
					$this->Flash->error($error);
					return $this->redirect(URL_HTTP."member/forgot-password");

				} elseif ($captchaValidation =='2') {
					$status 			= 'error';
					$error				= 'Not Validated Captcha';
					$this->Flash->error($error);
					return $this->redirect(URL_HTTP."member/forgot-password");
				}
				$Member 	= $this->Members->findByMemberID($MemberID);

				if($Member) {
					$EmailID = $this->Members->SendOTPForChangePassword($Member);

					if($EmailID) {
						$this->Flash->success('OTP for Change Password sent on '.$EmailID.'.');
						$this->redirect(URL_HTTP."member/forgot-password");
					} else {
						$this->Flash->error('Error while generating OTP for change password!');
					}
				} else {
					$this->Flash->error('Please enter valid LOGIN ID.');
				}
			} else if (!empty($OTP) && $hdnaction == "reset_pass") {
				$Member = $this->Members->findByMemberOTP($OTP);
				if($Member) {
					$this->request->data['password1']   = $this->convert_pass($this->request->data['password1']);
					$this->request->data['password2']   = $this->convert_pass($this->request->data['password2']);
					$Member = $this->Members->patchEntity($Member, [
							'password'      => $this->request->data['password1'],
							'password1'     => $this->request->data['password1'],
							'password2'     => $this->request->data['password2']
						],
						['validate' => 'ChangePassword']
					);
					if(!$Member->errors()) {
						if(!empty($Member) && $this->Members->ChangeMemberPassword($Member,$this->request->data['password1'],false)) {
							$this->Flash->success('The password is successfully changed.');
							$this->redirect(URL_HTTP."member/forgot-password");
						} else {
							$this->Flash->error('Error while password changed!');
						}
					}
				} else {
					$this->Flash->error('Please enter valid OTP.');
				}
			} else {
				if ($hdnaction == "send_otp") {
					$this->Flash->error('LOGIN ID IS REQUIRED FIELD.');
				} else {
					$this->Flash->error('OTP IS REQUIRED FIELD.');
				}
			}
		}
		$this->set('pageTitle',$pageTitle);
		$this->set('hdnaction',$hdnaction);
	}
	/**
    *
    * captchaValidation
    *
    * Behaviour : public
    *
    * @defination : Method is use to checked where clicked on captcha or not.
    *
    */
    public function captchaValidation(){
    	if(CAPTCHA_DISPLAY == 1) {
		    if($this->request->data['g-recaptcha-response'] == ""){
	            return 0;
	        }else {
	                $secret = CAPTCHA_SECRET_KEY;//Configure::read('SECRET_KEY');
	                $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$this->request->data['g-recaptcha-response']}");
	                $captcha_success = json_decode($verify);
	                if ($captcha_success->success == false) {
	                    $this->Flash->error('Not Validated Captcha');
	                    return 0;
	                } else if ($captcha_success->success == true) {
	                    return 1;
	                }
	        }
	    }
        return 1;
    }
}
?>