<?php
/************************************************************
* File Name : UserrightComponent.php 						*
* purpose	: Use to provide rights base display of 
			  view,edit,delete links on listing and other 
			  espective pages 								*
* @package  : 												*
* @author 	: Khushal Bhalsod								*
* @since 	: 21/04/2016									*
************************************************************/

namespace App\Controller\Component;
use Cake\Controller\Component;

class UserrightComponent extends Component {

	/*Admin Module Transaction*/
	public $ANALYSTS_LIST 					= 1001;
	public $ANALYSTS_ADD 					= 1002;
	public $ANALYSTS_CHANGE_PROFILE 		= 1003;
	public $ANALYSTS_RIGHTS 				= 1004;
	public $ANALYSTS_EDIT 					= 1005;
	public $ANALYSTS_ADD_REMOVE_EMAIL_RIGHTS = 1006;
	public $ANALYSTS_LOG_REPORT 			= 1007;
	public $ADMIN_HOME 						= 1008;
	public $ANALYSTS_ENABLE 				= 1009;
	public $ANALYSTS_DISABLE 				= 1010;
	
	public $MASTER_USER_RIGHT 				= 2003;
	public $ASSIGN_SERVICE_TO_CONSULTANT 	= 1012;
	public $DELETE_ADMIN_USER_ROLE 			= 1015;
	
	/*Admin User Role Transaction*/
	public $LIST_ADMIN_USER_ROLES 			= 3001;
	public $EDIT_ADMIN_USER_ROLE 			= 3002;
	public $MANAGE_ADMIN_USER_ROLE_RIGHTS 	= 3003;
	public $ADD_ADMIN_USER_ROLE 			= 3004;
	
	/*Parameter Transaction*/
	public $LIST_PARAMETERS 				= 4001;
	public $ADD_PARAMETER_TYPE 				= 4002;
	public $EDIT_PARAMETER_TYPE 			= 4003;
	public $ADD_PARAMETER 					= 4004;
	public $EDIT_PARAMETER 					= 4005;
	
	/*Customer Transaction*/
	public $LIST_CUSTOMER 					= 5001;
	public $EDIT_CUSTOMER 					= 5003;
 

	/*Installer Plan Transaction*/
	public $LIST_INSTALLER_PLAN 			= 6001;
	public $ADD_INSTALLER_PLAN 				= 6002;
	public $EDIT_INSTALLER_PLAN 			= 6003;

	/*Financial Incentives Transaction*/
	public $LIST_FINANCIAL_INCENTIVES 		= 7001;
	public $ADD_FINANCIAL_INCENTIVES 		= 7002;
	public $EDIT_FINANCIAL_INCENTIVES 		= 7003;

	/* Installer Transaction*/
	public $LIST_INSTALLER			 		= 8001;
	public $ADD_INSTALLER			 		= 8002;
	public $EDIT_INSTALLER 			 		= 8003;
	public $IMPORT_INSTALLER 			 	= 8004;
	
	public $LIST_EMPANEL 			 		= 8005;
	public $ADD_EMPANEL 			 		= 8006;
	public $EDIT_EMPANEL 		 			= 8007;

	public $LIST_SUBSCRIPTION			 		= 8005;
	public $ADD_SUBSCRIPTION			 		= 8006;
	public $EDIT_SUBSCRIPTION	 			= 8007;



	/* Project Transaction*/
	public $LIST_PROJECT			 		= 9001;
	public $ADD_PROJECT				 		= 9002;
	public $EDIT_PROJECT			 		= 9003;
	public $VIEW_PROJECT			 		= 9005;
	
	/* Project Transaction*/
	public $LIST_COMPANY			 		= 10001;
	
	/* Contact Us View Admin*/
	public $LIST_CONTACTUS			 		= 11001;
	public $LIST_APPLYONLINES		 		= 12001;
	
	/*LIST_MEMBER */
	public $LIST_MEMBER 					= 13001;
	public $ADD_MEMBER 						= 13002;
	public $EDIT_MEMBER				 		= 13003;
	
	
	/*Financial Incentives Transaction*/
	public $LIST_BRANCH_MASTER 				= 14001;
	public $ADD_BRANCH_MASTER 				= 14002;
	public $EDIT_BRANCH_MASTER				= 14003;
	

	/**
	  *
	  * checkadminrights
	  *
	  * Behaviour : Public
	  *
	  * @param :  $rightname  : Name of right to be checked for admin
	  * @return :  True if admin (current logged in) have permission for rightname passed as argument else Fasle
	  * @defination : Method is use to check right base on right passed in argument
	  *
	  */  
	public function checkadminrights($rightname)
	{	
		$arrUserRights = $this->request->session()->read('User.userrights');
		if(is_array($arrUserRights) && in_array($rightname,$arrUserRights)) return true;
		return false;
    }

	/**
	 *
	 * linkEditAdminuser
	 *
	 * Behaviour : Public
	 *
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target :If not empty, Will define wether on click particular link it should open in new page or in self i.e _blank etc
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 *
	 */
	public function linkEditAdminuser($strLink,$strValue='Edit',$target='',$rel='editRecord',$other='')
	{
		$strReturn = "";
		$rightname = $this->ANALYSTS_EDIT;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			//$lnkImg		= "<img src=\"".IMAGE_URL."edit.png\" alt=\"".$strValue."\" title=\"".$strValue."\" />";
			return "<a href=\"".$strLink."\"".$strTarget." rel='".$rel."'".$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	/**
	 * linkEditAdminuserrole
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target :If not empty, Will define wether on click particular link it should open in new page or in self i.e _blank etc
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 */
	public function linkEditAdminuserrole($strLink,$strValue='Edit',$target='',$rel='editRecord',$other='')
	{
		$strReturn = "";
		$rightname = $this->EDIT_ADMIN_USER_ROLE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			//$lnkImg		= "<img src=\"".IMAGE_URL."edit.png\" alt=\"".$strValue."\" title=\"".$strValue."\" />";
			return "<a href=\"".$strLink."\"".$strTarget." rel='".$rel."'".$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/**
	 *
	 * linkDisableAdminuser
	 *
	 * Behaviour : Public
	 *
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target :If not empty, Will define wether on click particular link it should open in new page or in self i.e _blank etc
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 *
	 */
	public function linkDisableAdminuser ($strLink,$strValue='Disable',$target='',$rel='actionRecord',$other='')
	{
		$strReturn ='';
		$rightname = $this->ANALYSTS_DISABLE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			//$lnkImg		= "<img src=\"".IMAGE_URL."inactive.png\" alt=\"".$strValue."\" title=\"".$strValue."\" />";
			return "<a id='test1' href=\"".$strLink."\"".$strTarget.' rel="'.$rel.'"'.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/**
	 * linkDeleteAdminuserrole
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target :If not empty, Will define wether on click particular link it should open in new page or in self i.e _blank etc
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 */
	 public function linkDeleteAdminuserrole($strLink,$strValue='Edit',$target='',$rel='editRecord',$other='')
	{
		$strReturn = "";
		$rightname = $this->DELETE_ADMIN_USER_ROLE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			return "<a href=\"".$strLink."\"".$strTarget." rel='".$rel."'".$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/**
	 * linkManageAdminuserroleRights
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target :If not empty, Will define wether on click particular link it should open in new page or in self i.e _blank etc
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 */
	public function linkManageAdminuserroleRights($strLink,$strValue='Edit',$target='',$rel='editRecord',$other='')
	{
		$strReturn = "";
		$rightname = $this->MANAGE_ADMIN_USER_ROLE_RIGHTS;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			//$lnkImg		= "<img src=\"".IMAGE_URL."edit.png\" alt=\"".$strValue."\" title=\"".$strValue."\" />";
			return "<a href=\"".$strLink."\"".$strTarget." rel='".$rel."'".$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	/*
	 * linkAddCompany
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target : boolean true - new windown / false
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 *
	 */
	public function makeLink($Right="",$strLink,$strValue='New_Window',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $Right;
		if(!empty($rightname) && $this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"".preg_replace("/[^A-Z]/i","_",$strValue)."\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/**
	 *
	 * linkEnableAdminuser
	 *
	 * Behaviour : Public
	 *
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target :If not empty, Will define wether on click particular link it should open in new page or in self i.e _blank etc
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else return blank
	 *
	 */
	public function linkEnableAdminuser($strLink,$strValue='Enable',$target='',$rel='actionRecord',$other='')
	{
		$strReturn ='';
		$rightname = $this->ANALYSTS_ENABLE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			//$lnkImg		= "<img src=\"".IMAGE_URL."active.png\" alt=\"".$strValue."\" title=\"".$strValue."\" />";
			return "<a id='test1' href=\"".$strLink."\"".$strTarget.' rel="'.$rel.'"'.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

 	public function linkAddAdminuser($strLink,$strValue='Add Admin User',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ANALYSTS_ADD;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"Delete_Recharge_Retailer\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	public function linkAddAdminuserroll($strLink,$strValue='Add Admin User',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_ADMIN_USER_ROLE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"Delete_Recharge_Retailer\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/*
	 * linkParametersList
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target : boolean true - new windown / false
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else 	 return blank
	 *
	 */
	public function linkParametersList($strLink,$strValue='parameter list',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->LIST_PARAMETERS;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"parameter_list\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/*
	 * linkAddParameterType
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target : boolean true - new windown / false
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else 	 return blank
	 *
	 */
	public function linkAddParameterType($strLink,$strValue='Add Parameter Type',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_PARAMETER_TYPE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"add_parameter_type\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/*
	 * linkEditParameterType
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target : boolean true - new windown / false
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else 	 return blank
	 *
	 */
	public function linkEditParameterType($strLink,$strValue='Edit Parameter Type',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->EDIT_PARAMETER_TYPE;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"edit_parameter_type\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	/*
	 * linkAddParameter
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target : boolean true - new windown / false
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else 	 return blank
	 *
	 */
	public function linkAddParameter($strLink,$strValue='Add Parameter',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_PARAMETER;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"add_parameter\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	
	/*
	 * linkEditParameter
	 * Behaviour : Public
	 * @param : $strLink : Link as string to be displayed if permitted
	 * @param : $strValue : Value to be displayed eg: View Scanlog, View client info etc
	 * @param : $target : boolean true - new windown / false
	 * @param : $other : If any other styles or property to be set can be passed as other
	 * @return : Return final string to be displayed if have proper rights else its return blank
	 * @defination : Method is use to display link base on passed argument, if permission for same then with clickable link to redirect proper page else 	 return blank
	 *
	 */
	public function linkEditParameter($strLink,$strValue='Edit Parameter',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->EDIT_PARAMETER;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"edit_parameter\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	public function linkChangeUserPassword($strLink,$strValue='Change Password',$target='',$rel='changepassword',$other='')
	{
		$strReturn = "";
		$rightname = $this->ANALYSTS_EDIT;
		if($this->checkadminrights($rightname)) {
			$strTarget	= ($target!="")?" target=\"".$target."\"":"";
			return "<a href=\"".$strLink."\"".$strTarget." data-toggle='modal' data-target='#myModal' rel='".$rel."'".$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	public function linkAddCustomer($strLink,$strValue='Add Customer',$target=true,$other="")
	{
		$strReturn = "";
		return $strReturn;
	}

	/*Financial Incentives Action Links*/
	public function linkAddFinancialIncentive($strLink,$strValue='Add Financial Incentives',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_FINANCIAL_INCENTIVES;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"add_financial_incentives\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	public function linkEditFinancialIncentive($strLink,$strValue='Edit Financial Incentives',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->EDIT_FINANCIAL_INCENTIVES;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"edit_financial_incentives\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	public function linkListProjects($strLink,$strValue='List Project',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->LIST_PROJECT;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	public function linkViewProjects($strLink,$strValue='View Project',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->VIEW_PROJECT;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	public function linkAddEmpanelled($strLink,$strValue='Add Empanelled',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_EMPANEL;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"add_empanelled\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}

	public function linkViewApplyOnlines($strLink,$strValue='View Project',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->LIST_APPLYONLINES;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	
	public function linkEditMember($strLink,$strValue='View Project',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->EDIT_MEMBER;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	public function linkAddMember($strLink,$strValue='Add Member',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_MEMBER;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	
	public function linkEditBranchMaster($strLink,$strValue='View Project',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->EDIT_BRANCH_MASTER;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
	public function linkAddBranchMaster($strLink,$strValue='Add Member',$target=true,$other="")
	{
		$strReturn = "";
		$rightname = $this->ADD_BRANCH_MASTER;
		if($this->checkadminrights($rightname))
		{
			$strTarget	= ($target)?" target=\"list_project\"":"";
			return "<a href=\"".$strLink."\"".$strTarget.$other.">".$strValue."</a>";
		}
		return $strReturn;
	}
}
?>