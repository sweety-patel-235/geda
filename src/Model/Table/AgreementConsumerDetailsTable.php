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
class AgreementConsumerDetailsTable extends AppTable
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
	var $table = 'agreement_consumer_details';
	
	public function initialize(array $config)
	{
		$this->table($this->table);
	}
	public function save_data($application_id,$arr_modules,$customer_id)
	{
		$ReCouchdb           									= TableRegistry::get('ReCouchdb'); 
		$saveapplication_data           						= TableRegistry::get('AgreementConsumerDetails'); 
		$saveapplication_data_entity    						= $saveapplication_data->newEntity(); 
		$saveapplication_data_entity->application_id   		 	= $application_id;
		$saveapplication_data_entity->agreement_id 				= $arr_modules['agreement_id'];
		$saveapplication_data_entity->consumer_no   			= $arr_modules['consumer_no'];
		$saveapplication_data_entity->consumer_name   			= $arr_modules['consumer_name'];
		$saveapplication_data_entity->discom_name   			= $arr_modules['discom_name'];
		$saveapplication_data_entity->geo_location_id   		= $arr_modules['geo_location_id'];
		$saveapplication_data_entity->wtg_location   			= $arr_modules['wtg_location'];
		$saveapplication_data_entity->wtg_capacity   			= $arr_modules['wtg_capacity'];
		$saveapplication_data_entity->percentage_share 			= $arr_modules['percentage_share'];
		$saveapplication_data_entity->capacity_allocated   		= $arr_modules['capacity_allocated'];

		$saveapplication_data_entity->whelling_agree_doc   		= $arr_modules['whelling_agree_doc'];
		$saveapplication_data_entity->transmission_agree_doc   	= $arr_modules['transmission_agree_doc'];

		$saveapplication_data_entity->created_by   		 		= $customer_id;
		$saveapplication_data_entity->created_date           	= $this->NOW();
		$saveapplication_data->save($saveapplication_data_entity);
		$insertId = $saveapplication_data_entity->id;

		


		//upload_wtg_file - if rlmm is no
		if (isset($arr_modules['transmission_agree_doc']) && !empty($arr_modules['transmission_agree_doc'])) {
			
			$prefix_file 	= '';
			$name 			= $arr_modules['transmission_agree_doc']['name'];

			$ext 			= substr(strtolower(strrchr($name, '.')), 1);

			$file_name 		= $prefix_file.date('Ymdhms').rand();
			$uploadPath 	= WTG_PATH.$application_id.'/';
			if(!file_exists(WTG_PATH.$application_id)) {
				@mkdir(WTG_PATH.$application_id, 0777);
			}
			$file_location 	= WWW_ROOT.$uploadPath.'transmission_agree_doc'.'_'.$file_name.'.'.$ext;
			if(move_uploaded_file($arr_modules['transmission_agree_doc']['tmp_name'],$file_location))
			{

				 $couchdbId 		= $ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'transmission_agree_doc_'.$file_name.'.'.$ext,$customer_id,'transmission_agree_doc');
				
				$saveapplication_data->updateAll(array("transmission_agree_doc" =>'transmission_agree_doc'.'_'.$file_name.'.'.$ext,"transmission_agree_doc_type" =>'transmission_agree_doc','couchdb_id'=>$couchdbId),array("application_id" => $application_id));
				
			}
		}

		//upload_land_per_form 
		if (isset($arr_modules['whelling_agree_doc']) && !empty($arr_modules['whelling_agree_doc'])) {
			
			$prefix_file 	= '';
			$name 			= $arr_modules['whelling_agree_doc']['name'];

			$ext 			= substr(strtolower(strrchr($name, '.')), 1);

			$file_name 		= $prefix_file.date('Ymdhms').rand();
			$uploadPath 	= WTG_PATH.$application_id.'/';
			if(!file_exists(WTG_PATH.$application_id)) {
				@mkdir(WTG_PATH.$application_id, 0777);
			}
			$file_location 	= WWW_ROOT.$uploadPath.'whelling_agree_doc'.'_'.$file_name.'.'.$ext;
			if(move_uploaded_file($arr_modules['whelling_agree_doc']['tmp_name'],$file_location))
			{

				 $whelling_agree_doc_couchdbId 		= $ReCouchdb->saveData($uploadPath,$file_location,$prefix_file,'whelling_agree_doc_'.$file_name.'.'.$ext,$customer_id,'whelling_agree_doc');
				
				$saveapplication_data->updateAll(array("whelling_agree_doc" =>'whelling_agree_doc'.'_'.$file_name.'.'.$ext,"whelling_agree_doc_type" =>'whelling_agree_doc','whelling_agree_doc_couchdb_id'=>$whelling_agree_doc_couchdbId),array("application_id" => $application_id));
				
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
					$arr_modules['payment_status']			= NULL;
					$arr_modules['payment_amount']			= NULL;
					$arr_modules['payment_date']			= '0000-00-00 00:00:00';
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
						$arr_modules['payment_status']					= NULL;
						$arr_modules['payment_amount']					= NULL;
						$arr_modules['payment_date']					= '0000-00-00 00:00:00';
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
								$arr_modules['payment_status']					= NULL;
								$arr_modules['payment_amount']					= NULL;
								$arr_modules['payment_date']					= '0000-00-00 00:00:00';
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
			$GeoApplicationClashedData  = TableRegistry::get('GeoApplicationClashedData');
			$applicationDetails 		= $Applications->viewDetailApplication($id);
			//$applicationDetails		= $Applications->find('all',array('conditions'=>array('id'=>$id)))->first();
			$geo_application_data		= $ApplicationGeoLocation->find('all',array('conditions'=>array('application_id'=>$id)))->first();
			//$geo_application_data_array	= $ApplicationGeoLocation->find('all',array('conditions'=>array('application_id'=>$id)))->toArray();
			

			$geo_application_data_array = $this->find('all',
                                            [ 'fields'=>['wtg_location','approved','geo_village','geo_taluka','geo_district','x_cordinate','y_cordinate','wtg_model','geo_application_clashed_data.clashed_for'],
                                                'join'=>[['table'=>'geo_application_clashed_data','type'=>'left','conditions'=>'ApplicationGeoLocation.id = geo_application_clashed_data.clashed_geo_id']],
                                                'conditions'=>['ApplicationGeoLocation.application_id'=>$id ,'OR'=>['ApplicationGeoLocation.approved is NOT NULL','geo_application_clashed_data.clashed_for is NOT NULL']]
                                            ])->toArray();


			//echo"<pre>"; print_r($geo_application_data_array); die();
			if(!empty($applicationDetails->discom)){
				$discom_name  		= $BranchMasters->find("all",['conditions'=>['id'=>$applicationDetails->discom]])->first();
				$discom_short_name  = $DiscomMaster->find("all",['conditions'=>['id'=>$discom_name->discom_id]])->first();
			}
			$district 			= $DistrictMaster->find("list",['keyField'=>'id','valueField'=>'name','conditions'=>['state_id'=>4]])->toArray();
			$geo_application_clashed_data	= $GeoApplicationClashedData->find("list",['keyField'=>'clashed_geo_id','valueField'=>'clashed_for'])->toArray();
			$view->set("pageTitle","GeoApplication");
			//$view->set('clashtext',$clashtext);
			$view->set('applicationDetails',$applicationDetails);
			$view->set('geo_application_data',$geo_application_data);
			$view->set('geo_application_data_array',$geo_application_data_array);
			$view->set('district',$district);
			$view->set('geo_application_clashed_data',$geo_application_clashed_data);
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

			$dompdf->setPaper('A4', 'landscape');
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
								$arr_modules['payment_status']					= NULL;
								$arr_modules['payment_amount']					= NULL;
								$arr_modules['payment_date']					= '0000-00-00 00:00:00';
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
								'ApplicationGeoLocation.approved_date',
								'ApplicationGeoLocation.payment_status',
								'ApplicationGeoLocation.payment_date',
								'ApplicationGeoLocation.created_by',
								'ApplicationGeoLocation.created_date',
								'applications.installer_id',
								'applications.application_no',
								'developer_customers.name',
								'district_master.name',
								'developers.installer_name'
							  ];
		
								
		$arrOrderBy     = explode("|",$arrRequestData['order_by_form']);
		$join_arr  		= [	'application_category'	=> ['table'=>'application_category','type'=>'left','conditions'=>'ApplicationGeoLocation.application_type=application_category.id'],
						 	'district_master'		=> ['table'=>'district_master','type'=>'left','conditions'=>'ApplicationGeoLocation.geo_district=district_master.id'],
						 	'applications'	=> ['table'=>'applications','type'=>'left','conditions'=>'ApplicationGeoLocation.application_id = applications.id']];
		
		// if(!empty($arrRequestData['customer_id'])) {
			
			$condition_arr  	= array();
			array_push($join_arr,['table'=>'developer_customers','type'=>'left','conditions'=>'developer_customers.id = applications.customer_id']);
			array_push($join_arr,['table'=>'developers','type'=>'left','conditions'=>'developers.id = applications.installer_id']);
			$str_group_by 		= '1';
			$flag_stages_table 	= 0;
			
		 	$condition_arr['OR'] = array('ApplicationGeoLocation.payment_status'=>1,'ApplicationGeoLocation.approved'=>2);
			if(isset($arrRequestData['application_type']) && !empty($arrRequestData['application_type'])) {
				$condition_arr['ApplicationGeoLocation.application_type']			= $arrRequestData['application_type'];
			}
			if(isset($arrRequestData['installer_name']) && !empty($arrRequestData['installer_name'])) {
				$condition_arr['developers.installer_name like ']	= '%'.$arrRequestData['installer_name'].'%';
			}
			if(isset($arrRequestData['payment_status']) && $arrRequestData['payment_status']!='') {
				$condition_arr['ApplicationGeoLocation.payment_status']	= $arrRequestData['payment_status'];
			}
			if(isset($arrRequestData['application_search_no']) && $arrRequestData['application_search_no']!='') {
				$condition_arr['application_no like ']	= '%'.$arrRequestData['application_search_no'].'%';
			}
			if(isset($arrRequestData['x_cordinate']) && $arrRequestData['x_cordinate']!='') {
				$condition_arr['x_cordinate like ']	= $arrRequestData['x_cordinate'];
			}
			if(isset($arrRequestData['y_cordinate']) && $arrRequestData['y_cordinate']!='') {
				$condition_arr['y_cordinate like ']	= $arrRequestData['y_cordinate'];
			}

			if(isset($arrRequestData['application_status']) && $arrRequestData['application_status']!='') {
				if($arrRequestData['application_status'] == 3){
					$condition_arr['OR'] = array('ApplicationGeoLocation.approved is NULL','ApplicationGeoLocation.approved'=>3);
				} else if($arrRequestData['application_status'] == 5){
					$condition_arr['approved'] = 5;
				}else{
					$condition_arr['ApplicationGeoLocation.approved ']	= $arrRequestData['application_status'];
				}
				
			}
			
			if(isset($arrRequestData['DateFrom']) && !empty($arrRequestData['DateFrom']) && isset($arrRequestData['DateTo']) && !empty($arrRequestData['DateTo'])){
				//$where_data     = ['ApplicationGeoLocation.id IS NOT'=>NULL];
				$condition_arr  = ["ApplicationGeoLocation.created_date BETWEEN :start AND :end"];
			}else{
				$condition_arr['ApplicationGeoLocation.id IS NOT']  = NULL;
			}
			
			$ApplyOnlinesList   = $this->find("all",[
				'fields'		=> $fields,
				'join'   		=> $join_arr,
				'conditions'	=> $condition_arr,
				//'order'			=> [$arrOrderBy[0]=>$arrOrderBy[1],'ApplicationGeoLocation.created_date'=>$arrOrderBy[1]]]
				//'order'=>[$SortBy=>$Direction]
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