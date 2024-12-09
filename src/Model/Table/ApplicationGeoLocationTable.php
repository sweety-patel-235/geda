<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\View\View;
use Dompdf\Dompdf;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
use Cake\I18n\Date;
/**
 * @category  Class File
 * @author    Employee Code : -
 * @version   GED 1.0
 * @since     File available since GED
 */
class ApplicationGeoLocationTable extends AppTable
{
	/**
	 *
	 * The status of $name is universe
	 *
	 * Potential value are Class Name
	 *
	 * @var String
	 *
	 */
	var $table = 'application_geo_location';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public $ExportFields 	= array("sr_no","registration_no","installer_name","application_type","wtg_location","type_of_land","land_survey_no","land_area","wtg_validity_date","geo_district","geo_taluka","geo_village","zone","x_cordinate","y_cordinate","rlmm","wtg_model","wtg_capacity","wtg_rotor_dimension","wtg_hub_height","comment","wtg_verified","wtg_verified_by","wtg_verified_date","query_raised","query_raised_date","query_raised_by","query_raised_remark","approved","approved_by","approved_date","clashed_for","clashed_date","payment_status","payment_date","created_by","created_date");

	public $DefaultExportFields 	= array("sr_no","registration_no","installer_name","application_type","wtg_location","type_of_land","land_survey_no","land_area","wtg_validity_date","geo_district","geo_taluka","geo_village","zone","x_cordinate","y_cordinate","rlmm","wtg_model","wtg_capacity","wtg_rotor_dimension","wtg_hub_height","comment","wtg_verified","wtg_verified_by","wtg_verified_date","query_raised","query_raised_date","query_raised_by","query_raised_remark","approved","approved_by","clashed_for","clashed_date","approved_date","payment_status","payment_date","created_by","created_date");
	public $arrReportFields 		= array("sr_no"		     => "sr_no",
										"registration_no"	 => "registration_no",
										"installer_name"	 => "developer_name",
										"application_type" 	 => "application_type",
										"wtg_location"		 => "wtg_location",
										"type_of_land"		 => "type_of_land",
										"land_survey_no"	 => "land_survey_no",
										"land_area"			 => "land_area",
										"wtg_validity_date"	 => "wtg_validity_date",
										"geo_district"		 => "geo_district",
										"geo_taluka"		 => "geo_taluka",
										"geo_village"		 => "geo_village",
										"zone"				 => "zone",
										"x_cordinate"		 => "x_cordinate",
										"y_cordinate"		 => "y_cordinate",
										"rlmm" 				 => "rlmm",
										"wtg_model" 		 => "wtg_model",
										"wtg_capacity" 		 => "wtg_capacity",
										"wtg_rotor_dimension"=> "wtg_rotor_dimension",
										"wtg_hub_height" 	 => "wtg_hub_height",
										"comment" 			 => "comment",
										"wtg_verified" 		 => "wtg_verified",
										"wtg_verified_by" 	 => "wtg_verified_by",
										"wtg_verified_date"  => "wtg_verified_date",
										"query_raised" 		 => "query_raised",
										"query_raised_date"  => "query_raised_date",
										"query_raised_by" 	 => "query_raised_by",
										
										"query_raised_remark"=> "query_raised_remark",
										"approved" 			 => "approved",
										"approved_by" 		 => "approved_by",
										"approved_date" 	 => "approved_date",
										"clashed_for" 	 	 => "clashed_for",
										"clashed_date" 	 	 => "clashed_date",
										"payment_status" 	 => "payment_status",
										"payment_date" 		 => "payment_date",
										"created_by" 		 => "created_by",
										"created_date" 		 => "created_date"
									);
	public function save_data($application_id,$arr_modules,$customer_id,$application_type)
	{
		$ReCouchdb           									= TableRegistry::get('ReCouchdb'); 
		$saveapplication_data           						= TableRegistry::get('ApplicationGeoLocation'); 
		$saveapplication_data_entity    						= $saveapplication_data->newEntity(); 
		$saveapplication_data_entity->application_id   		 	= $application_id;
		$saveapplication_data_entity->application_type   		= $application_type;
		$saveapplication_data_entity->wtg_id   					= $arr_modules['wtg_id'];
		$saveapplication_data_entity->wtg_location   			= $arr_modules['wtg_location'];
		$saveapplication_data_entity->type_of_land   			= $arr_modules['type_of_land'];
		$saveapplication_data_entity->land_survey_no   			= $arr_modules['land_survey_no'];
		$saveapplication_data_entity->land_area   				= $arr_modules['land_area'];
		$saveapplication_data_entity->wtg_validity_date 		= $arr_modules['wtg_validity_date'];
		$saveapplication_data_entity->sub_lease_deed   			= $arr_modules['sub_lease_deed'];

		$saveapplication_data_entity->geo_village   			= $arr_modules['geo_village'];
		$saveapplication_data_entity->geo_taluka   				= $arr_modules['geo_taluka'];
		$saveapplication_data_entity->geo_district   			= $arr_modules['geo_district'];
		
		$saveapplication_data_entity->zone   					= $arr_modules['zone'];
		$saveapplication_data_entity->x_cordinate   			= $arr_modules['x_cordinate'];
		$saveapplication_data_entity->y_cordinate   			= $arr_modules['y_cordinate'];
		$saveapplication_data_entity->land_per_form   			= $arr_modules['land_per_form'];
		$saveapplication_data_entity->rlmm   					= $arr_modules['rlmm'];

		$saveapplication_data_entity->wtg_make   				= $arr_modules['wtg_make'];
		$saveapplication_data_entity->wtg_model   				= $arr_modules['wtg_model'];
		$saveapplication_data_entity->wtg_capacity   			= $arr_modules['wtg_capacity'];
		$saveapplication_data_entity->wtg_rotor_dimension   	= $arr_modules['wtg_rotor_dimension'];
		$saveapplication_data_entity->wtg_hub_height   			= $arr_modules['wtg_hub_height'];
		$saveapplication_data_entity->wtg_file   				= $arr_modules['wtg_file'];

		$saveapplication_data_entity->created_by   		 		= $customer_id;
		$saveapplication_data_entity->created_date           	= $this->NOW();
		$saveapplication_data->save($saveapplication_data_entity);
		$insertId = $saveapplication_data_entity->id;

		//upload_wtg_file - if rlmm is no
		if (isset($arr_modules['wtg_file']) && !empty($arr_modules['wtg_file'])) {
			
			$prefix_file 	= '';
			$name 			= $arr_modules['wtg_file']['name'];

			$ext 			= substr(strtolower(strrchr($name, '.')), 1);

			$file_name 		= $prefix_file.date('Ymdhms').rand();
			$uploadPath 	= WTG_PATH.$insertId.'/';
			if(!file_exists(WTG_PATH.$insertId)) {
				@mkdir(WTG_PATH.$insertId, 0777);
			}
			$file_location 	= WWW_ROOT.$uploadPath.'wtg_file'.'_'.$file_name.'.'.$ext;
			if(move_uploaded_file($arr_modules['wtg_file']['tmp_name'],$file_location))
			{

				 $couchdbId 		= $ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'wtg_file_'.$file_name.'.'.$ext,$customer_id,'wtg_file');
				
				$saveapplication_data->updateAll(array("wtg_file" =>'wtg_file'.'_'.$file_name.'.'.$ext,"wtg_file_type" =>'wtg_file','couchdb_id'=>$couchdbId),array("id" => $insertId));
				
			}
		}

		//upload_land_per_form 
		if (isset($arr_modules['land_per_form']) && !empty($arr_modules['land_per_form'])) {
			
			$prefix_file 	= '';
			$name 			= $arr_modules['land_per_form']['name'];

			$ext 			= substr(strtolower(strrchr($name, '.')), 1);

			$file_name 		= $prefix_file.date('Ymdhms').rand();
			$uploadPath 	= WTG_PATH.$insertId.'/';
			if(!file_exists(WTG_PATH.$insertId)) {
				@mkdir(WTG_PATH.$insertId, 0777);
			}
			$file_location 	= WWW_ROOT.$uploadPath.'land_per_form'.'_'.$file_name.'.'.$ext;
			if(move_uploaded_file($arr_modules['land_per_form']['tmp_name'],$file_location))
			{

				 $land_per_form_couchdbId 		= $ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'land_per_form_'.$file_name.'.'.$ext,$customer_id,'land_per_form');
				
				$saveapplication_data->updateAll(array("land_per_form" =>'land_per_form'.'_'.$file_name.'.'.$ext,"land_per_form_type" =>'land_per_form','land_per_form_couchdb_id'=>$land_per_form_couchdbId),array("id" => $insertId));
				
			}
		}


	}

	// public function wtg_file_upload($file, $prefix_file = '', $id, $file_field, $access_type = '',$customer_id)
	// {
	// 	$ApplicationGeoLocation           			= TableRegistry::get('ApplicationGeoLocation'); 
	// 	$ReCouchdb           						= TableRegistry::get('ReCouchdb'); 
	// 	$customerId 	= $customer_id;

	// 	$name 			= $file['name'];

	// 	$path 			= WTG_PATH. $id . '/';
	// 	if (!file_exists(WTG_PATH. $id)) {
	// 		@mkdir(WTG_PATH. $id, 0777, true);
	// 	}

	// 	$updateRequestData 	= $ApplicationGeoLocation->find('all', array('conditions' => array('id' => $id)))->first();

	// 	if (!empty($updateRequestData->$file_field) && file_exists($path . $updateRequestData->$file_field)) {
	// 		@unlink($path . $updateRequestData->$file_field);
	// 	}
	// 	$ext    		= substr(strtolower(strrchr($file['name'], '.')), 1);
	// 	$file_name   	= $prefix_file . date('YmdHis') . rand();
	// 	$file_location  = $path . $file_name . '.' . $ext;

	// 	move_uploaded_file($file['tmp_name'], $file_location);

	// 	$passFileName 	= $file_name . '.' . $ext;
	// 	$couchdbId 		= $ReCouchdb->saveData($path, $file_location, $prefix_file, $passFileName, $customerId, $access_type);

	// 	return $file_name . '.' . $ext;
	// }

	public function fetchdata($application_id,$application_type)
	{
		$fetchapplication_data        = TableRegistry::get('ApplicationGeoLocation'); 
		$fetchapplication_dataDetails = $this->find('all',array('fields'=> array('capacity_type','nos_mod_inv','mod_inv_capacity','mod_inv_total_capacity','mod_inv_make','application_id','id'),'conditions'=>array('application_id'=>$application_id,'capacity_type'=>$capacity_type)))->toArray();
		
		return $fetchapplication_dataDetails;
	}
	public function internal_clashed_docs($geo_id)
    {
        $fetchgeoapplication_data        = TableRegistry::get('GeoApplicationClashedData'); 
        $fetchgeoapplication_dataDetails = $fetchgeoapplication_data->find('all',array('fields'=> array('id','application_id','clashed_geo_id','uploadfile','uploadfile_type'),'conditions'=>array('clashed_geo_id'=>$geo_id,'clashed_for'=>2)))->first();
       	return $fetchgeoapplication_dataDetails;
    }
	public function count_of_application($application_id,$application_type)
	{
		$count_of_application_data        = TableRegistry::get('ApplicationGeoLocation'); 
		$count_of_application_dataDetails = $this->find('all',array('fields'=> array('capacity_type','nos_mod_inv','mod_inv_capacity','mod_inv_total_capacity','mod_inv_make','application_id','id'),'conditions'=>array('application_id'=>$application_id,'capacity_type'=>$capacity_type)))->toArray();
		
		return $count_of_application_dataDetails;
	}

	public function CheckValidityData($id,$application_id)
	{
		
		$ApplicationGeoLocation           = TableRegistry::get('ApplicationGeoLocation');
		$GeoApplicationRejectLog          = TableRegistry::get('GeoApplicationRejectLog');
		$WindWtgDetail          		  = TableRegistry::get('WindWtgDetail');
		$GeoApplicationClashedData        = TableRegistry::get('GeoApplicationClashedData');

		$approved_application =  $ApplicationGeoLocation->find("all",['conditions'=>['id'=>$id,'approved'=>1]])->first();

		if(!empty($approved_application)){
			
			$geo_developer_payment_sucess = $WindWtgDetail->find('all',['fields'=>array('WindWtgDetail.app_geo_loc_id'),'join'=>[
						'dpp' => [
							'table' => 'developer_permission_payment',
							'type' => 'Left',
							'conditions' => ['dpp.dev_per_app_id = WindWtgDetail.app_geo_loc_id']
						]]])->where(['WindWtgDetail.app_geo_loc_id' => $id])->first();

			if(empty($geo_developer_payment_sucess)){
				$current_date 			= $this->NOW();
				$approved_date 			= $approved_application->approved_date;
				$threeMonthsDate 		= date('Y-m-d', strtotime("+90 days", strtotime($approved_date)));
				
				$Days 					= (strtotime(date('Y-m-d')) - strtotime($threeMonthsDate))/(24*60*60);
				$Days_without_dashes 	= str_replace("-", "", $Days);
				if($threeMonthsDate<$current_date){
					//$browser 					   		= isset($SERVER)?$SERVER:"-";
					$rejectentity                  		= $GeoApplicationRejectLog->newEntity();
					$rejectentity->geo_application_id  	= $id;
					$rejectentity->application_id  		= $application_id;
					$rejectentity->member_id     		= 0;
					//$rejectentity->ip_address      		= $remoteaddress;
					$rejectentity->reject_reason      	= "Validity Over";
					//$rejectentity->browser_info	   		= json_encode($browser);
					//$rejectentity->application_data		= json_encode($value);
					$rejectentity->created 		   		= $this->NOW();
					$GeoApplicationRejectLog->save($rejectentity);

					$arr_modules['approved']				= 2;
					$arr_modules['payment_status']			= 2;
					//$arr_modules['payment_date']			= '0000-00-00 00:00:00';
					$arr_modules['approved_by']				= NULL;
					$arr_modules['approved_date']			= '0000-00-00 00:00:00';
					$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id));

					$geo_clashed_data   = $GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$id,'approved'=>3)))->first();
					if(!empty($geo_clashed_data)){
						$arr_moduless['status']				= 3;
						$GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$geo_clashed_data->clashed_geo_id));
					}

					$geo_clashed_data   = $GeoApplicationClashedData->find('all',array('conditions'=>array('clashed_geo_id'=>$id,'approved'=>1)))->first();
					if(!empty($geo_clashed_data)){
						$arr_moduless['approved']			= 2;
						$arr_moduless['status']				= 2;
						$GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$geo_clashed_data->clashed_geo_id));
					}

				}
				if($Days_without_dashes <= 10){
					return $Days_without_dashes;	
					
				}
				
				//return $Days;
			}

		}
	}
	
	public function CheckClashData($x,$y,$id)
	{
		
		$ApplicationGeoLocation     = TableRegistry::get('ApplicationGeoLocation');
		$GeoApplicationRejectLog    = TableRegistry::get('GeoApplicationRejectLog');
		//$approved_application 		=  $ApplicationGeoLocation->find("all",['conditions'=>['x_cordinate'=>$x,'y_cordinate'=>$y,'approved'=>1]])->first();
		$first_application 			=  $ApplicationGeoLocation->find("all",['conditions'=>['x_cordinate'=>$x,'y_cordinate'=>$y,'payment_status'=>1],'order' => ['payment_date' => 'ASC']])->first();
		//$arr_modules  	= array();
		if(!empty($first_application)){
			if($id != $first_application->id){
				$clashtext 				 = 'Clashing';
				
				$arr_modules['approved'] = '3';
				
				$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id,'OR'=>['approved is NULL','approved is NOT'=>2] ));
				return $clashtext;
			}
			elseif($id == $first_application->id && $first_application->approved == 3){

				//$arr_modules['approved'] = NULL;

				$arr_modules['developer_action_status'] 		= 3;
				$arr_modules['developer_action_status_date'] = $this->NOW();
				$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id,'developer_action_status IS NULL'));

				$application 			=  $ApplicationGeoLocation->find("all",['fields'=>['id','application_id','developer_action_status','developer_action_status_date'],'conditions'=>['x_cordinate'=>$x,'y_cordinate'=>$y,'developer_action_status'=>3]])->first();
				if(!empty($application)){
					$current_date 			 = $this->NOW();
					$developer_action_status_date = $application->developer_action_status_date;
					$TenDaysDate 		 	 = date('Y-m-d', strtotime("+10 days", strtotime($developer_action_status_date)));
					
					$Days 					 = (strtotime(date('Y-m-d')) - strtotime($TenDaysDate))/(24*60*60);
					$Days_without_dashes 	 = str_replace("-", "", $Days);
					
					if($TenDaysDate<$current_date){
						//$browser 					   		= isset($SERVER)?$SERVER:"-";
						$rejectentity                  		= $GeoApplicationRejectLog->newEntity();
						$rejectentity->geo_application_id  	= $id;
						$rejectentity->application_id  		= $application->application_id;
						$rejectentity->member_id     		= 0;
						//$rejectentity->ip_address      		= $remoteaddress;
						$rejectentity->reject_reason      	= "Validity Over Because Not Accept Application";
						//$rejectentity->browser_info	   		= json_encode($browser);
						//$rejectentity->application_data		= json_encode($value);
						$rejectentity->created 		   		= $this->NOW();
						$GeoApplicationRejectLog->save($rejectentity);

						$arr_modules['approved']						= 2;
						$arr_modules['developer_action_status']			= NULL;
						$arr_modules['developer_action_status_date']	= NULL;
						$arr_modules['payment_status']					= 2;
						//$arr_modules['payment_date']					= '0000-00-00 00:00:00';
						$arr_modules['approved_by']						= NULL;
						$arr_modules['approved_date']					= '0000-00-00 00:00:00';
						$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id));
					}	
				}
				
			}
		}
		// if(!empty($approved_application)){
		// 	$clashtext = 'Clashing';

		// 	return $clashtext;
		// }else
		
	
	}
	public function FirstComeFirst($x,$y,$id)
	{
		
		$ApplicationGeoLocation     = TableRegistry::get('ApplicationGeoLocation');

		$first_application 		=  $ApplicationGeoLocation->find("all",['conditions'=>['x_cordinate'=>$x,'y_cordinate'=>$y,'payment_status'=>1],'order' => ['payment_date' => 'ASC']])->first();
		if($id != $first_application->id){
			$clashtext = 'Clashing';
			return $clashtext;
		}
	}
	public function Member_ClashData($id)
	{
		//$ApplicationGeoLocation     = TableRegistry::get('ApplicationGeoLocation');
		$GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
		$clashed_application 		=  $GeoApplicationClashedData->find("all",['conditions'=>['clashed_geo_id'=>$id,'clashed_for'=>1],'order' => ['created' => 'ASC']])->first();
		
		if(!empty($clashed_application)){
			
			$clashtext 				 = 'Clashing';
		
			return $clashtext;
		}
	
	
	}

	public function Member_CheckClashData_old($id)
	{
		
		$ApplicationGeoLocation     = TableRegistry::get('ApplicationGeoLocation');
		$GeoApplicationRejectLog    = TableRegistry::get('GeoApplicationRejectLog');
		$GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
		
		$ClashedData 				=  $GeoApplicationClashedData->find("all",['conditions'=>['clashed_geo_id'=>$id,'status IS NULL']])->first();
		$clashtext 				 = 'Clashing';
		if(!empty($ClashedData)){

			$approved_application 	=  $ApplicationGeoLocation->find("all",['conditions'=>['id'=>$ClashedData->approved_geo_id,'approved'=>1]])->first();
			
			$first_application 		=  $GeoApplicationClashedData->find("all",['conditions'=>['approved_geo_id'=>$ClashedData->approved_geo_id,'clashed_for'=>1,'OR'=>['approved is NULL','approved NOT IN'=>array(1,2)]],'order' => ['created' => 'ASC']])->first();

			if(isset($first_application) && $id == $first_application->clashed_geo_id){
					
			
					if(!empty($approved_application) && !empty($first_application) && $id == $first_application->clashed_geo_id){
						// $arr_modules['approved'] = '3';
					
						// $ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id,'OR'=>['approved is NULL','approved is NOT'=>2] ));

						$arr_modules['approved'] = '3';

						$GeoApplicationClashedData->updateAll($arr_modules,array('clashed_geo_id'=>$id,'OR'=>['approved is NULL','approved is NOT'=>2]));
					}
					//
					if(empty($approved_application) && $first_application->approved == 3 ){
						//$arr_modules['approved'] = NULL;
						//&& $id == $first_application->clashed_geo_id
						
						$arr_modules['developer_action_status'] 		= 3;
						$arr_modules['developer_action_status_date'] = $this->NOW();
						$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id,'developer_action_status IS NULL'));

						$arr_moduless['approved'] = 1;
						$GeoApplicationClashedData->updateAll($arr_moduless,array('clashed_geo_id'=>$id,'OR'=>['approved is NULL','approved is NOT'=>2,'approved'=>3]));

						$application 			=  $ApplicationGeoLocation->find("all",['fields'=>['id','application_id','developer_action_status','developer_action_status_date'],'conditions'=>['developer_action_status'=>3],'id'=>$id])->first();

						if(!empty($application)){
							$current_date 			 = $this->NOW();
							$developer_action_status_date = $application->developer_action_status_date;
							$TenDaysDate 		 	 = date('Y-m-d', strtotime("+10 days", strtotime($developer_action_status_date)));
							
							$Days 					 = (strtotime(date('Y-m-d')) - strtotime($TenDaysDate))/(24*60*60);
							$Days_without_dashes 	 = str_replace("-", "", $Days);
							
							if($TenDaysDate<$current_date){
								//$browser 					   		= isset($SERVER)?$SERVER:"-";
								$rejectentity                  		= $GeoApplicationRejectLog->newEntity();
								$rejectentity->geo_application_id  	= $id;
								$rejectentity->application_id  		= $application->application_id;
								$rejectentity->member_id     		= 0;
								//$rejectentity->ip_address      		= $remoteaddress;
								$rejectentity->reject_reason      	= "Validity Over Because Not Accept Application cla";
								//$rejectentity->browser_info	   		= json_encode($browser);
								//$rejectentity->application_data		= json_encode($value);
								$rejectentity->created 		   		= $this->NOW();
								$GeoApplicationRejectLog->save($rejectentity);

								$arr_modules['approved']						= 2;
								$arr_modules['developer_action_status']			= NULL;
								$arr_modules['developer_action_status_date']	= NULL;
								$arr_modules['payment_status']					= 2;
								//$arr_modules['payment_date']					= '0000-00-00 00:00:00';
								$arr_modules['approved_by']						= NULL;
								$arr_modules['approved_date']					= '0000-00-00 00:00:00';
								$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id));
							}	
						}
					}

				
					
			}
			return $clashtext;
		}
	}
	

	/**
	 * generateGEOLetterPdf
	 * Behaviour : public
	 * @param : id  : id is use to generate applications, $isdownload=true
	 * @defination : Method is use to download .pdf file from download application
	 *
	 */
	public function generateGEOLetterPdf($id,$isdownload=false)
	{
		
		if(empty($id)) {
			return 0;
		} else {
			$view = new View();
			$view->layout 			= false;
			$id 					= decode($id);
			$ApplicationStages 		= TableRegistry::get('ApplicationStages');
			$ApplicationGeoLocation = TableRegistry::get('ApplicationGeoLocation');
			$Applications 			= TableRegistry::get('Applications');
			$BranchMasters 			= TableRegistry::get('BranchMasters');
			$DiscomMaster 			= TableRegistry::get('DiscomMaster');
			
			$applicationDetails 	= $Applications->viewDetailApplication($id);
			//$applicationDetails		= $Applications->find('all',array('conditions'=>array('id'=>$id)))->first();
			$geo_application_data	= $ApplicationGeoLocation->find('all',array('conditions'=>array('application_id'=>$id,'approved'=>1)))->first();
			
			if(!empty($applicationDetails->discom)){
				$discom_name  		= $BranchMasters->find("all",['conditions'=>['id'=>$applicationDetails->discom]])->first();
				$discom_short_name  = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
			}
			$view->set("pageTitle","GeoApplication");
			$view->set('applicationDetails',$applicationDetails);
			$view->set('geo_application_data',$geo_application_data);
			//$view->set('geda_application_no',$applicationDetails->geda_application_no);
			
			$PDFFILENAME = getRandomNumber();
			$LETTER_APPLICATION_NO 	= decode($id);
			$LETTER_APPLICATION_NO 	= $applicationDetails->application_no;

			/* Generate PDF for estimation of project */
			require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
			$dompdf = new Dompdf($options = array());
			$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
			$dompdf->set_option("isPhpEnabled", true);
			$view->set('dompdf',$dompdf);
			
			$html = $view->render('/Element/geo_application/download_GeoLetter_application');
			$dompdf->loadHtml($html,'UTF-8');

			$dompdf->setPaper('A4', 'portrait');
			$dompdf->render();
			if($isdownload) {

				$dompdf->stream('applyonline-'.$LETTER_APPLICATION_NO);	
			}
			$output = $dompdf->output();
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=".$LETTER_APPLICATION_NO.".pdf");
			echo $output;
			die;
		}
	}
	/**
	 * generateApplicationPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from modal popup of applyonline listing
	 *
	 */
	public function generateGeoApplicationPdf($id,$isdownload=true,$mobile=false)
	{
		if(empty($id)) {
			return 0;
		} else {
			$view = new View();
			$view->layout 				= false;
			$id 						= decode($id);
			$ApplicationStages 			= TableRegistry::get('ApplicationStages');
			$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');
			$Applications 				= TableRegistry::get('Applications');
			$BranchMasters 				= TableRegistry::get('BranchMasters');
			$DiscomMaster 				= TableRegistry::get('DiscomMaster');
			$DistrictMaster 			= TableRegistry::get('DistrictMaster');
			$TalukaMaster 				= TableRegistry::get('TalukaMaster');
			$Developers 				= TableRegistry::get('Developers');
			$GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
			$ManufacturerMaster  		= TableRegistry::get('ManufacturerMaster');
			$members  					= TableRegistry::get('members');
			$applicationDetails 		= $Applications->viewDetailApplication($id);
			$InstallersData  			= $Developers->find('all', array('conditions' => array('id' => $applicationDetails->installer_id)))->first();
			
			$developer_name = $InstallersData->installer_name;
			$developer_address = $InstallersData->address . ", " . $InstallersData->taluka . " ".$InstallersData->pincode. " ". $InstallersData->state ;
			
			$geo_application_data		= $ApplicationGeoLocation->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			$members  					= $members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$geo_application_data->approved_by]])->first();
			
			//$geo_application_data_array	= $ApplicationGeoLocation->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
			

			//$geo_application_data_array = $this->find('all',
		    //                                        [ 'fields'=>['id','wtg_location','approved','geo_village','comment','geo_taluka','zone','land_survey_no','geo_district','x_cordinate','y_cordinate','wtg_model','geo_application_clashed_data.clashed_for'],
		    //                                            'join'=>[['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id']],
		    //                                            'conditions'=>['ApplicationGeoLocation.application_id'=>$id ,'OR'=>['ApplicationGeoLocation.approved is NOT NULL','geo_application_clashed_data.clashed_for is NOT NULL']]
		    //                                        ])->toArray();

			$geo_application_data_array = $this->find('all',
                                            [ 'fields'=>['id','wtg_location','approved','geo_village','comment','geo_taluka','zone','land_survey_no','geo_district','x_cordinate','y_cordinate','wtg_make','wtg_capacity','wtg_rotor_dimension','wtg_hub_height','geo_application_clashed_data.clashed_for','geo_application_reject_log.reject_reason','members.address1'],
                                                'join'=>[['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id'],
                                                ['table'=>' geo_application_reject_log','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_reject_log.geo_application_id'],
                                            	['table'=>'members','type'=>'left','conditions'=>'ApplicationGeoLocation.approved_by = members.id']],
                                                'conditions'=>['ApplicationGeoLocation.application_id'=>$id ,'OR'=>['ApplicationGeoLocation.approved is NOT NULL','geo_application_clashed_data.clashed_for is NOT NULL']]
                                            ])->toArray();
			
			if(!empty($applicationDetails->discom)){
				$discom_name  		= $BranchMasters->find("all",['conditions'=>['id'=>$applicationDetails->discom]])->first();
				$discom_short_name  = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
			}
			$district 			= $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
			$taluka 			= $TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
			$wtg_make 			= $ManufacturerMaster->find("list",['keyField'=>'id','valueField'=>'name'])->toArray();
			$district 			= $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
			$geo_application_clashed_data	= $GeoApplicationClashedData->find("list",['keyField'=>'clashed_geo_id','valueField'=>'clashed_for'])->toArray();
			$view->set("pageTitle","GeoApplication");
			//$view->set('clashtext',$clashtext);
			$view->set('applicationDetails',$applicationDetails);
			$view->set('geo_application_data',$geo_application_data);
			$view->set('geo_application_data_array',$geo_application_data_array);
			$view->set('taluka',$taluka);
			$view->set('district',$district);
			$view->set('wtg_make',$wtg_make);
			$view->set('geo_application_clashed_data',$geo_application_clashed_data);
			$view->set('developer_name',$developer_name);
			$view->set('developer_address',$developer_address);
			$view->set('members',$members);
			//$view->set('geda_application_no',$applicationDetails->geda_application_no);
			
			$PDFFILENAME = getRandomNumber();
			$LETTER_APPLICATION_NO 	= decode($id);
			$LETTER_APPLICATION_NO 	= $applicationDetails->application_no;

			/* Generate PDF for estimation of project */
			require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
			$dompdf = new Dompdf($options = array());
			$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
			$dompdf->set_option("isPhpEnabled", true);
			$view->set('dompdf',$dompdf);
			
			$html = $view->render('/Element/geo_application/download_geo_location');
			$dompdf->loadHtml($html,'UTF-8');

			//$dompdf->setPaper('A4', 'portrait');
			$dompdf->setPaper('A4', 'landscape');
			//$dompdf->render();
			$dompdf->render();
			if($isdownload) {

				$dompdf->stream('applyonline-'.$LETTER_APPLICATION_NO);	
			}
			$output = $dompdf->output();
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=".$LETTER_APPLICATION_NO.".pdf");
			echo $output;
			die;
		}
	}
	/**
	 * generateApplicationPdf
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from modal popup of applyonline listing
	 *
	 */
	public function generateGeoApplicationVerifiedPdf($id,$isdownload=true,$mobile=false)
	{
		if(empty($id)) {
			return 0;
		} else {
			$view = new View();
			$view->layout 				= false;
			$id 						= decode($id);
			$GeoApplicationVerification = TableRegistry::get('GeoApplicationVerification');
			$get_data					= $GeoApplicationVerification->find('all',array('conditions'=>array('id'=>$id)))->first();
			
			$ApplicationStages 			= TableRegistry::get('ApplicationStages');
			$ApplicationGeoLocation 	= TableRegistry::get('ApplicationGeoLocation');
			$Applications 				= TableRegistry::get('Applications');
			$BranchMasters 				= TableRegistry::get('BranchMasters');
			$DiscomMaster 				= TableRegistry::get('DiscomMaster');
			$DistrictMaster 			= TableRegistry::get('DistrictMaster');
			$TalukaMaster 				= TableRegistry::get('TalukaMaster');
			$Developers 				= TableRegistry::get('Developers');
			$GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
			$ManufacturerMaster  		= TableRegistry::get('ManufacturerMaster');
			$members  					= TableRegistry::get('members');
			$applicationDetails 		= $Applications->viewDetailApplication($get_data->application_id);
			
			$InstallersData  			= $Developers->find('all', array('conditions' => array('id' => $applicationDetails->installer_id)))->first();
			
			$developer_name = $InstallersData->installer_name;
			$developer_address = $InstallersData->address . ", " . $InstallersData->taluka . " ".$InstallersData->pincode. " ". $InstallersData->state ;
			
			$geo_application_data		= $ApplicationGeoLocation->find('all',array('conditions'=>array('application_id'=>$get_data->application_id)))->first();
			$members  					= $members->find("all",['fields'=>['id','address1','name'],'conditions'=>['id'=>$geo_application_data->approved_by]])->first();
			$geo_ids= explode(',', $get_data->geo_id);
			$clashed_data		= $GeoApplicationClashedData->find('all',array('conditions'=>array('application_id'=>$get_data->application_id)))->first();
			if(empty($clashed_data)){
				$geo_application_data_array = $this->find('all',
                                            [ 'fields'=>['id','wtg_location','wtg_verified_date','approved','geo_village','comment','geo_taluka','zone','land_survey_no','geo_district','x_cordinate','y_cordinate','wtg_make','wtg_capacity','wtg_rotor_dimension','wtg_hub_height','geo_application_clashed_data.clashed_for','geo_application_reject_log.reject_reason','members.address1'],
                                                'join'=>[['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id'],
                                                ['table'=>' geo_application_reject_log','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_reject_log.geo_application_id'],
                                            	['table'=>'members','type'=>'left','conditions'=>'ApplicationGeoLocation.approved_by = members.id']],
                                                'conditions'=>['ApplicationGeoLocation.application_id'=>$get_data->application_id ,'ApplicationGeoLocation.id IN'=>$geo_ids,'OR'=>['ApplicationGeoLocation.approved is NOT NULL','geo_application_clashed_data.clashed_for is NOT NULL']]
                                            ])->toArray();	
			}else{
				$geo_application_data_array = $this->find('all',
                                            [ 'fields'=>['id','wtg_location','wtg_verified_date','approved','geo_village','comment','geo_taluka','zone','land_survey_no','geo_district','x_cordinate','y_cordinate','wtg_make','wtg_capacity','wtg_rotor_dimension','wtg_hub_height','geo_application_clashed_data.clashed_for','geo_application_reject_log.reject_reason','members.address1'],
                                                'join'=>[['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id'],
                                                ['table'=>' geo_application_reject_log','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_reject_log.geo_application_id'],
                                            	['table'=>'members','type'=>'left','conditions'=>'ApplicationGeoLocation.approved_by = members.id']],
                                                'conditions'=>['ApplicationGeoLocation.application_id'=>$get_data->application_id ,'ApplicationGeoLocation.id IN'=>$geo_ids,'OR'=>['ApplicationGeoLocation.approved is NOT NULL','geo_application_clashed_data.clashed_for is NOT NULL']]
                                            ])->group('geo_application_clashed_data.clashed_geo_id')->toArray();	
			}
			foreach ($geo_application_data_array as $key => $value) {
				$verified_date[] = $value['wtg_verified_date'];
			}
			$wtg_verified_date =$verified_date[0];
			//echo"<pre>"; print_r($wtg_verified_date); die();
			if(!empty($applicationDetails->discom)){
				$discom_name  		= $BranchMasters->find("all",['conditions'=>['id'=>$applicationDetails->discom]])->first();
				$discom_short_name  = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
			}
			$district 			= $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
			$taluka 			= $TalukaMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_code'=>24]])->toArray();
			$wtg_make 			= $ManufacturerMaster->find("list",['keyField'=>'id','valueField'=>'name'])->toArray();
			$district 			= $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
			$geo_application_clashed_data	= $GeoApplicationClashedData->find("list",['keyField'=>'clashed_geo_id','valueField'=>'clashed_for'])->toArray();
			$view->set("pageTitle","GeoApplication");
			//$view->set('clashtext',$clashtext);
			$view->set('applicationDetails',$applicationDetails);
			$view->set('geo_application_data',$geo_application_data);
			$view->set('geo_application_data_array',$geo_application_data_array);
			$view->set('taluka',$taluka);
			$view->set('district',$district);
			$view->set('wtg_make',$wtg_make);
			$view->set('geo_application_clashed_data',$geo_application_clashed_data);
			$view->set('developer_name',$developer_name);
			$view->set('developer_address',$developer_address);
			$view->set('members',$members);
			$view->set('wtg_verified_date',$wtg_verified_date);
			//$view->set('geda_application_no',$applicationDetails->geda_application_no);
			
			$PDFFILENAME = getRandomNumber();
			$LETTER_APPLICATION_NO 	= decode($id);
			$LETTER_APPLICATION_NO 	= $applicationDetails->application_no;

			/* Generate PDF for estimation of project */
			require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
			$dompdf = new Dompdf($options = array());
			$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
			$dompdf->set_option("isPhpEnabled", true);
			$view->set('dompdf',$dompdf);
			
			$html = $view->render('/Element/geo_application/download_geo_location_verified');
			$dompdf->loadHtml($html,'UTF-8');

			//$dompdf->setPaper('A4', 'portrait');
			$dompdf->setPaper('A4', 'landscape');
			//$dompdf->render();
			$dompdf->render();
			if($isdownload) {

				$dompdf->stream('applyonline-'.$LETTER_APPLICATION_NO);	
			}
			$output = $dompdf->output();
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=".$LETTER_APPLICATION_NO.".pdf");
			echo $output;
			die;
		}
	}
	public function Member_CheckClashData($id)
	{
		
		$ApplicationGeoLocation     = TableRegistry::get('ApplicationGeoLocation');
		$GeoApplicationRejectLog    = TableRegistry::get('GeoApplicationRejectLog');
		$GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
		
		$ClashedData 				=  $GeoApplicationClashedData->find("all",['conditions'=>['clashed_geo_id'=>$id,'OR'=>['approved is NULL','approved !='=>4,'approved !='=>2]]])->first();
		
		if(isset($ClashedData->clashed_for) && $ClashedData->clashed_for == 2){
			$clashtext 				 = 'Internal Clashing';
		} else{
			$clashtext 				 = 'Clashing';
		}
		
		if(!empty($ClashedData)){

			$approved_application 	=  $ApplicationGeoLocation->find("all",['conditions'=>['id'=>$ClashedData->approved_geo_id,'approved'=>1]])->first();
			
			$first_application 		=  $GeoApplicationClashedData->find("all",['conditions'=>['approved_geo_id'=>$ClashedData->approved_geo_id,'clashed_for'=>1,'OR'=>['status NOT IN'=>array(1,2),'approved  NOT IN'=>array(2,4)]],'order' => ['created' => 'ASC']])->first();
			
			if(isset($first_application) && $id == $first_application->clashed_geo_id){
				 
					if($first_application->status == 0){
						
						$arr_modules['approved'] = '3';

						$GeoApplicationClashedData->updateAll($arr_modules,array('clashed_geo_id'=>$id,'OR'=>['approved is NULL','approved is NOT'=>2]));
					}
					
					if(empty($approved_application) && $first_application->approved == 3 ){
						
						$arr_modules['developer_action_status'] 		= 3;
						$arr_modules['developer_action_status_date'] = $this->NOW();
						$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id,'developer_action_status IS NULL'));

						$application 			=  $ApplicationGeoLocation->find("all",['fields'=>['id','application_id','developer_action_status','developer_action_status_date'],'conditions'=>['developer_action_status'=>3,'id'=>$id]])->first();
						
						if(!empty($application)){
							$current_date 			 		= $this->NOW();
							$developer_action_status_date 	= $application->developer_action_status_date;
							$TenDaysDate 		 	 		= date('Y-m-d', strtotime("+10 days", strtotime($developer_action_status_date)));
							
							$Days 					 = (strtotime(date('Y-m-d')) - strtotime($TenDaysDate))/(24*60*60);
							$Days_without_dashes 	 = str_replace("-", "", $Days);
							
							if($TenDaysDate<$current_date){
								$rejectentity                  		= $GeoApplicationRejectLog->newEntity();
								$rejectentity->geo_application_id  	= $id;
								$rejectentity->application_id  		= $application->application_id;
								$rejectentity->member_id     		= 0;
								$rejectentity->reject_reason      	= "Validity Over Because Not Accept Applicationvv";
								$rejectentity->created 		   		= $this->NOW();
								$GeoApplicationRejectLog->save($rejectentity);

								$arr_modules['approved']						= 2;
								$arr_modules['developer_action_status']			= NULL;
								$arr_modules['developer_action_status_date']	= NULL;
								$arr_modules['payment_status']					= 2;
								//$arr_modules['payment_date']					= '0000-00-00 00:00:00';
								$arr_modules['approved_by']						= NULL;
								$arr_modules['approved_date']					= '0000-00-00 00:00:00';
								$ApplicationGeoLocation->updateAll($arr_modules,array('id'=>$id));
							}	
						}
					}		
			}
			return $clashtext;
		}
	}

	/**
	* getDataApplications
	* Behaviour : public
	* Parameter : arrRequestData all parameter of request as well as session passed to this variable
	* @defination : Listing of applications.
	*/
	public function getGeoLocationData($arrRequestData=array(),$SortBy,$Direction) 
	{
		$fields             = [	'ApplicationGeoLocation.id',
								'ApplicationGeoLocation.application_id',
								'ApplicationGeoLocation.application_type',
								'ApplicationGeoLocation.wtg_location',
								'ApplicationGeoLocation.type_of_land',
								'ApplicationGeoLocation.land_survey_no',
								'ApplicationGeoLocation.land_area',
								'ApplicationGeoLocation.wtg_validity_date',
								'ApplicationGeoLocation.geo_village',
								'ApplicationGeoLocation.geo_taluka',
								'ApplicationGeoLocation.geo_district',
								'ApplicationGeoLocation.zone',
								'ApplicationGeoLocation.x_cordinate',
								'ApplicationGeoLocation.y_cordinate',
								'ApplicationGeoLocation.rlmm',
								'ApplicationGeoLocation.wtg_make',
								'ApplicationGeoLocation.wtg_model',
								'ApplicationGeoLocation.wtg_capacity',
								'ApplicationGeoLocation.wtg_rotor_dimension',
								'ApplicationGeoLocation.wtg_rotor_dimension',
								'ApplicationGeoLocation.wtg_file',
								'ApplicationGeoLocation.wtg_file_type',
								'ApplicationGeoLocation.couchdb_id',
								'ApplicationGeoLocation.developer_action_status',
								'ApplicationGeoLocation.developer_action_status_date',
								'ApplicationGeoLocation.approved',
								'ApplicationGeoLocation.approved_by',
								'ApplicationGeoLocation.approved_date',
								'ApplicationGeoLocation.payment_status',
								'ApplicationGeoLocation.payment_date',
								'ApplicationGeoLocation.created_by',
								'ApplicationGeoLocation.created_date',
								'ApplicationGeoLocation.query_raised',
								'ApplicationGeoLocation.query_raised_by',
								'ApplicationGeoLocation.query_raised_date',
								'ApplicationGeoLocation.query_raised_remark',
								'ApplicationGeoLocation.wtg_verified',
								'ApplicationGeoLocation.wtg_verified_by',
								'ApplicationGeoLocation.wtg_verified_date',
								'ApplicationGeoLocation.wtg_hub_height',
								'ApplicationGeoLocation.comment',
								'applications.installer_id',
								'developer_customers.name',
								'district_master.name',
								'applications.registration_no',
								'ApplicationGeoLocation.wtg_verified',
								//'geo_application_clashed_data.clashed_for',
								//'geo_application_clashed_data.created',
								'members.address1',
								'developers.installer_name'
							  ];
		
								
		$arrOrderBy     = explode("|",$arrRequestData['order_by_form']);
		$join_arr  		= [	'application_category'	=> ['table'=>'application_category','type'=>'left','conditions'=>'ApplicationGeoLocation.application_type=application_category.id'],
						 	'district_master'		=> ['table'=>'district_master','type'=>'left','conditions'=>'ApplicationGeoLocation.geo_district=district_master.id'],
						 	'applications'	=> ['table'=>'applications','type'=>'left','conditions'=>'ApplicationGeoLocation.application_id = applications.id'],
						 	//'geo_application_clashed_data'	=> ['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id'],
						 	'members'	=> ['table'=>'members','type'=>'left','conditions'=>'ApplicationGeoLocation.approved_by = members.id']];
		
		// if(!empty($arrRequestData['customer_id'])) {
			
			$condition_arr  	= array();
			array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = applications.customer_id']);
			array_push($join_arr,['table'=>'developers','type'=>'left','conditions'=>'developers.id = applications.installer_id']);
			//$str_group_by 		= 'ApplicationGeoLocation.id';
			$str_group_by 		= 1;
			$flag_stages_table 	= 0;
			
		 	
		 	// $condition_arr['OR'] = array('ApplicationGeoLocation.payment_status'=>1,'ApplicationGeoLocation.payment_status'=>2);
			if(isset($arrRequestData['application_type']) && !empty($arrRequestData['application_type'])) {
				$condition_arr['ApplicationGeoLocation.application_type']			= $arrRequestData['application_type'];
			}
			if(isset($arrRequestData['geo_taluka']) && !empty($arrRequestData['geo_taluka'])) {
				$condition_arr['ApplicationGeoLocation.geo_taluka']			= $arrRequestData['geo_taluka'];
			}
			if(isset($arrRequestData['geo_district']) && !empty($arrRequestData['geo_district'])) {
				$condition_arr['ApplicationGeoLocation.geo_district']			= $arrRequestData['geo_district'];
			}
			if(isset($arrRequestData['wtg_location']) && !empty($arrRequestData['wtg_location'])) {
				$condition_arr['ApplicationGeoLocation.wtg_location']			= $arrRequestData['wtg_location'];
			}
			if(isset($arrRequestData['action_by']) && !empty($arrRequestData['action_by'])) {
				$condition_arr['members.address1']			= $arrRequestData['action_by'];
			}
			if(isset($arrRequestData['wtg_verified']) && ($arrRequestData['wtg_verified']!='') ) {
				$condition_arr['ApplicationGeoLocation.wtg_verified']			= $arrRequestData['wtg_verified'];
			}
			if(isset($arrRequestData['installer_name']) && !empty($arrRequestData['installer_name'])) {
				$condition_arr['developers.installer_name like ']	= '%'.$arrRequestData['installer_name'].'%';
			}
			if(isset($arrRequestData['payment_status']) && $arrRequestData['payment_status']!='') {
				$condition_arr['ApplicationGeoLocation.payment_status']	= $arrRequestData['payment_status'];
			}
			if(isset($arrRequestData['provisional_search_no']) && $arrRequestData['provisional_search_no']!='') {
				$condition_arr['applications.registration_no like ']	= '%'.$arrRequestData['provisional_search_no'].'%';
			}
			if(isset($arrRequestData['payment_date']) && $arrRequestData['payment_date']!='') {
				$arrRequestData['payment_date'] = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['payment_date'])));
				$condition_arr['ApplicationGeoLocation.payment_date like ']	= '%'.$arrRequestData['payment_date'].'%';
			}
			if(isset($arrRequestData['x_cordinate']) && $arrRequestData['x_cordinate']!='') {
				$condition_arr['ApplicationGeoLocation.x_cordinate like ']	= $arrRequestData['x_cordinate'];
			}
			if(isset($arrRequestData['y_cordinate']) && $arrRequestData['y_cordinate']!='') {
				$condition_arr['ApplicationGeoLocation.y_cordinate like ']	= $arrRequestData['y_cordinate'];
			}

			// if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
			// 	if($arrRequestData['application_status'] == 3){
			// 		$condition_arr['OR'] = array('ApplicationGeoLocation.approved is NULL','ApplicationGeoLocation.approved'=>3);
			// 	} else if($arrRequestData['application_status'] == 5){
			// 		$condition_arr['ApplicationGeoLocation.approved'] = 5;
			// 	}else{
			// 		$condition_arr['ApplicationGeoLocation.approved']	= $arrRequestData['application_status'];
			// 	}
				
			// }
			
			if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
				if($arrRequestData['application_status'] == 3){

					array_push($join_arr,['table'=>'geo_application_clashed_data','alias'=>'geo_application_clashed_data','type'=>'left','conditions'=>'geo_application_clashed_data.clashed_geo_id = ApplicationGeoLocation.id']);
					$condition_arr['geo_application_clashed_data.clashed_for']	= 1;
				} else if($arrRequestData['application_status'] == 5){
					array_push($join_arr,['table'=>'geo_application_clashed_data','alias'=>'geo_application_clashed_data','type'=>'left','conditions'=>'geo_application_clashed_data.clashed_geo_id = ApplicationGeoLocation.id']);
					$condition_arr['geo_application_clashed_data.clashed_for']	= 2;
				}else if($arrRequestData['application_status'] == 1){
					$condition_arr['ApplicationGeoLocation.approved']	= 1;
				}else if($arrRequestData['application_status'] == 2){
					$condition_arr['ApplicationGeoLocation.approved']	= 2;
				}
				
			}
			if(isset($arrRequestData['DateFrom']) && !empty($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateTo'])){
				//$where_data     = ['ApplicationGeoLocation.id IS NOT'=>NULL];
				$condition_arr  = ["ApplicationGeoLocation.created_date BETWEEN :start AND :end"];
				// $condition_arr['ApplicationGeoLocation.payment_status ']	= 1;
				
			}else{
				$condition_arr['ApplicationGeoLocation.id IS NOT']  = NULL;
			}
			$condition_arr['OR'] = array('ApplicationGeoLocation.payment_status'=>1,'ApplicationGeoLocation.approved'=>2);
			$ApplyOnlinesList   = $this->find("all",[
				'fields'		=> $fields,
				'join'   		=> $join_arr,
				'conditions'	=> $condition_arr,
				//'group'			=> $str_group_by,
				//'order'			=> [$arrOrderBy[0]=>$arrOrderBy[1],'ApplicationGeoLocation.created_date'=>$arrOrderBy[1]]]
				//'order'=>[$SortBy=>$Direction]
			//'group_by' 		=> 'ApplicationGeoLocation.id';
				'order'			=> [$SortBy=>$Direction]]
			);
			if(isset($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateFrom']) && !empty($arrRequestData['DateTo'])){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['DateFrom'])))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $arrRequestData['DateTo'])))." 23:59:59";
				$ApplyOnlinesList->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
			}

			$arrResult['list']              = $ApplyOnlinesList;
			return $arrResult;
		// } 
	}

	/**
	* getDataApplications
	* Behaviour : public
	* Parameter : arrRequestData all parameter of request as well as session passed to this variable
	* @defination : Listing of applications.
	*/
	public function getGeoLocationData_Downloadxl($arrRequestData=array(),$SortBy,$Direction) 
	{
		$fields             = [	'ApplicationGeoLocation.id',
								'ApplicationGeoLocation.application_id',
								'ApplicationGeoLocation.application_type',
								'ApplicationGeoLocation.wtg_location',
								'ApplicationGeoLocation.type_of_land',
								'ApplicationGeoLocation.land_survey_no',
								'ApplicationGeoLocation.land_area',
								'ApplicationGeoLocation.wtg_validity_date',
								'ApplicationGeoLocation.geo_village',
								'ApplicationGeoLocation.geo_taluka',
								'ApplicationGeoLocation.geo_district',
								'ApplicationGeoLocation.zone',
								'ApplicationGeoLocation.x_cordinate',
								'ApplicationGeoLocation.y_cordinate',
								'ApplicationGeoLocation.rlmm',
								'ApplicationGeoLocation.wtg_make',
								'ApplicationGeoLocation.wtg_model',
								'ApplicationGeoLocation.wtg_capacity',
								'ApplicationGeoLocation.wtg_rotor_dimension',
								'ApplicationGeoLocation.wtg_rotor_dimension',
								'ApplicationGeoLocation.wtg_file',
								'ApplicationGeoLocation.wtg_file_type',
								'ApplicationGeoLocation.couchdb_id',
								'ApplicationGeoLocation.developer_action_status',
								'ApplicationGeoLocation.developer_action_status_date',
								'ApplicationGeoLocation.approved',
								'ApplicationGeoLocation.approved_by',
								'ApplicationGeoLocation.approved_date',
								'ApplicationGeoLocation.payment_status',
								'ApplicationGeoLocation.payment_date',
								'ApplicationGeoLocation.created_by',
								'ApplicationGeoLocation.created_date',
								'ApplicationGeoLocation.query_raised',
								'ApplicationGeoLocation.query_raised_by',
								'ApplicationGeoLocation.query_raised_date',
								'ApplicationGeoLocation.query_raised_remark',
								'ApplicationGeoLocation.wtg_verified',
								'ApplicationGeoLocation.wtg_verified_by',
								'ApplicationGeoLocation.wtg_verified_date',
								'ApplicationGeoLocation.wtg_hub_height',
								'ApplicationGeoLocation.comment',
								'applications.installer_id',
								'developer_customers.name',
								'district_master.name',
								'applications.registration_no',
								'ApplicationGeoLocation.wtg_verified',
								'geo_application_clashed_data.clashed_for',
								'geo_application_clashed_data.created',
								'members.address1',
								'developers.installer_name'
							  ];
		
								
		$arrOrderBy     = explode("|",$arrRequestData['order_by_form']);
		$join_arr  		= [	'application_category'	=> ['table'=>'application_category','type'=>'left','conditions'=>'ApplicationGeoLocation.application_type=application_category.id'],
						 	'district_master'		=> ['table'=>'district_master','type'=>'left','conditions'=>'ApplicationGeoLocation.geo_district=district_master.id'],
						 	'applications'	=> ['table'=>'applications','type'=>'left','conditions'=>'ApplicationGeoLocation.application_id = applications.id'],
						 	'geo_application_clashed_data'	=> ['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id'],
						 	'members'	=> ['table'=>'members','type'=>'left','conditions'=>'ApplicationGeoLocation.approved_by = members.id']];
		
		// if(!empty($arrRequestData['customer_id'])) {
			
			$condition_arr  	= array();
			array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = applications.customer_id']);
			array_push($join_arr,['table'=>'developers','type'=>'left','conditions'=>'developers.id = applications.installer_id']);
			$str_group_by 		= 'ApplicationGeoLocation.id';
			//$str_group_by 		= 1;
			$flag_stages_table 	= 0;
			
		 	
		 	// $condition_arr['OR'] = array('ApplicationGeoLocation.payment_status'=>1,'ApplicationGeoLocation.payment_status'=>2);
			if(isset($arrRequestData['application_type']) && !empty($arrRequestData['application_type'])) {
				$condition_arr['ApplicationGeoLocation.application_type']			= $arrRequestData['application_type'];
			}
			if(isset($arrRequestData['geo_taluka']) && !empty($arrRequestData['geo_taluka'])) {
				$condition_arr['ApplicationGeoLocation.geo_taluka']			= $arrRequestData['geo_taluka'];
			}
			if(isset($arrRequestData['geo_district']) && !empty($arrRequestData['geo_district'])) {
				$condition_arr['ApplicationGeoLocation.geo_district']			= $arrRequestData['geo_district'];
			}
			if(isset($arrRequestData['wtg_location']) && !empty($arrRequestData['wtg_location'])) {
				$condition_arr['ApplicationGeoLocation.wtg_location']			= $arrRequestData['wtg_location'];
			}
			if(isset($arrRequestData['action_by']) && !empty($arrRequestData['action_by'])) {
				$condition_arr['members.address1']			= $arrRequestData['action_by'];
			}
			if(isset($arrRequestData['wtg_verified']) && ($arrRequestData['wtg_verified']!='') ) {
				$condition_arr['ApplicationGeoLocation.wtg_verified']			= $arrRequestData['wtg_verified'];
			}
			if(isset($arrRequestData['installer_name']) && !empty($arrRequestData['installer_name'])) {
				$condition_arr['developers.installer_name like ']	= '%'.$arrRequestData['installer_name'].'%';
			}
			if(isset($arrRequestData['payment_status']) && $arrRequestData['payment_status']!='') {
				$condition_arr['ApplicationGeoLocation.payment_status']	= $arrRequestData['payment_status'];
			}
			if(isset($arrRequestData['provisional_search_no']) && $arrRequestData['provisional_search_no']!='') {
				$condition_arr['applications.registration_no like ']	= '%'.$arrRequestData['provisional_search_no'].'%';
			}
			if(isset($arrRequestData['payment_date']) && $arrRequestData['payment_date']!='') {
				$arrRequestData['payment_date'] = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['payment_date'])));
				$condition_arr['ApplicationGeoLocation.payment_date like ']	= '%'.$arrRequestData['payment_date'].'%';
			}
			if(isset($arrRequestData['x_cordinate']) && $arrRequestData['x_cordinate']!='') {
				$condition_arr['ApplicationGeoLocation.x_cordinate like ']	= $arrRequestData['x_cordinate'];
			}
			if(isset($arrRequestData['y_cordinate']) && $arrRequestData['y_cordinate']!='') {
				$condition_arr['ApplicationGeoLocation.y_cordinate like ']	= $arrRequestData['y_cordinate'];
			}

			// if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
			// 	if($arrRequestData['application_status'] == 3){
			// 		$condition_arr['OR'] = array('ApplicationGeoLocation.approved is NULL','ApplicationGeoLocation.approved'=>3);
			// 	} else if($arrRequestData['application_status'] == 5){
			// 		$condition_arr['ApplicationGeoLocation.approved'] = 5;
			// 	}else{
			// 		$condition_arr['ApplicationGeoLocation.approved']	= $arrRequestData['application_status'];
			// 	}
				
			// }
			
			if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
				if($arrRequestData['application_status'] == 3){

					array_push($join_arr,['table'=>'geo_application_clashed_data','alias'=>'geo_application_clashed_data','type'=>'left','conditions'=>'geo_application_clashed_data.clashed_geo_id = ApplicationGeoLocation.id']);
					$condition_arr['geo_application_clashed_data.clashed_for']	= 1;
				} else if($arrRequestData['application_status'] == 5){
					array_push($join_arr,['table'=>'geo_application_clashed_data','alias'=>'geo_application_clashed_data','type'=>'left','conditions'=>'geo_application_clashed_data.clashed_geo_id = ApplicationGeoLocation.id']);
					$condition_arr['geo_application_clashed_data.clashed_for']	= 2;
				}else if($arrRequestData['application_status'] == 1){
					$condition_arr['ApplicationGeoLocation.approved']	= 1;
				}else if($arrRequestData['application_status'] == 2){
					$condition_arr['ApplicationGeoLocation.approved']	= 2;
				}
				
			}
			if(isset($arrRequestData['DateFrom']) && !empty($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateTo'])){
				//$where_data     = ['ApplicationGeoLocation.id IS NOT'=>NULL];
				$condition_arr  = ["ApplicationGeoLocation.created_date BETWEEN :start AND :end"];
				// $condition_arr['ApplicationGeoLocation.payment_status ']	= 1;
				
			}else{
				$condition_arr['ApplicationGeoLocation.id IS NOT']  = NULL;
			}
			$condition_arr['OR'] = array('ApplicationGeoLocation.payment_status'=>1,'ApplicationGeoLocation.approved'=>2);
			$ApplyOnlinesList   = $this->find("all",[
				'fields'		=> $fields,
				'join'   		=> $join_arr,
				'conditions'	=> $condition_arr,
				'group'			=> $str_group_by,
				//'order'			=> [$arrOrderBy[0]=>$arrOrderBy[1],'ApplicationGeoLocation.created_date'=>$arrOrderBy[1]]]
				//'order'=>[$SortBy=>$Direction]
			//'group_by' 		=> 'ApplicationGeoLocation.id';
				'order'			=> [$SortBy=>$Direction]]
			);
			if(isset($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateFrom']) && !empty($arrRequestData['DateTo'])){
				$StartTime  = date("Y-m-d",strtotime(str_replace('/', '-',$arrRequestData['DateFrom'])))." 00:00:00";
				$EndTime    = date("Y-m-d",strtotime(str_replace('/', '-', $arrRequestData['DateTo'])))." 23:59:59";
				$ApplyOnlinesList->bind(':start',$StartTime,'date')->bind(':end',$EndTime,'date')->count();
			}

			$arrResult['list']              = $ApplyOnlinesList;
			return $arrResult;
		// } 
	}
}