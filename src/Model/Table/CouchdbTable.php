<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Couchdb\Couchdb;
class CouchdbTable extends AppTable
{
	var $table 		= 'couchdb';
	var $couchdbid 	= '';
	public function initialize(array $config)
	{
		$this->table($this->table);         
	}
	/**
	 * saveData
	 * Behaviour : public
	 * @param : $application id
	 * @defination : Method is use to store data in couchdb table
	 */
	public function saveData($path='',$fileLocation='',$prefixFile='',$passFileName='',$createdBy='',$access_type='',$MimeType='')
	{
		$ApplyonlinDocs		= TableRegistry::get('ApplyonlinDocs');
		$CouchdbFailureLog	= TableRegistry::get('CouchdbFailureLog');
		require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
		$COUCHDB 		= new Couchdb();
		$arrPath	 	= explode(APPLYONLINE_PATH,$path);
		$feaPath	 	= explode(FEASIBILITY_PATH,$path);
		$workPath	 	= explode(WORKORDER_PATH,$path);
		$exePath	 	= explode(EXECUTION_PATH,$path);
		$updatePath	 	= explode(UPDATEDETAILS_PATH,$path);
		$subsidyPath	= explode(SUBSIDY_PATH,$path);
		$inspectionPath	= explode(INSPECTION_PATH,$path);
		$installerPath	= explode(INSTALLER_PROFILE_PATH,$path);
		$kusumPath		= explode(APPLYONLINE_KUSUM_PATH,$path);
		$feesPath		= explode(FEES_RETURN_PATH,$path);
		$developerPath	= explode(DEVELOPER_PROFILE_PATH,$path);
		$applicationPath= explode(APPLICATIONS_PATH,$path);
		$storeModule 	= '';
		
		if(count($arrPath) > 1) {
			$storeModule 	= 'ApplyOnlines';
			$arrPath 		= explode("/",$path);
			$applicationId 	= $arrPath[count($arrPath)-2];
		} elseif(count($feaPath) > 1) {
			$storeModule 	= 'Feasibility';
			$feaPath 		= explode("/",$path);
			$applicationId 	= $feaPath[count($feaPath)-3];
		}  elseif(count($workPath) > 1) {
			$storeModule 	= 'WorkOrder';
			$workPath 		= explode("/",$path);
			$applicationId 	= $workPath[count($workPath)-2];
		} elseif(count($exePath) > 1) {
			$storeModule 	= 'Execution';
			$exePath 		= explode("/",$path);
			$applicationId 	= $exePath[count($exePath)-3];
		} elseif(count($updatePath) > 1) {
			$storeModule 	= 'UpdateRequest';
			$updatePath 	= explode("/",$path);
			$applicationId 	= $updatePath[count($updatePath)-2];
		} elseif(count($subsidyPath) > 1) {
			$storeModule 	= 'Subsidy';
			$subsidyPath 	= explode("/",$path);
			$applicationId 	= $subsidyPath[count($subsidyPath)-2];
		} elseif(count($inspectionPath) > 1) {
			$storeModule 	= 'Inspection';
			$inspectionPath = explode("/",$path);
			$applicationId 	= $inspectionPath[count($inspectionPath)-2];
		} elseif(count($installerPath) > 1) {
			$storeModule 	= 'Installer';
			$installerPath 	= explode("/",$path);
			$applicationId 	= $installerPath[count($installerPath)-2];
		} elseif(count($kusumPath) > 1) {
			$storeModule 	= 'ApplyOnlinesKusum';
			$kusumPath 		= explode("/",$path);
			$applicationId 	= $kusumPath[count($kusumPath)-2];
		} elseif(count($feesPath) > 1) {
			$storeModule 	= 'FeesReturn';
			$feesPath 		= explode("/",$path);
			$applicationId 	= $feesPath[count($feesPath)-2];
		} elseif(count($developerPath) > 1) {
			$storeModule 	= 'Developer';
			$developerPath 	= explode("/",$path);
			$applicationId 	= $developerPath[count($developerPath)-2];
		} elseif(count($applicationPath) > 1) {
			$storeModule 	= 'Applications';
			$applicationPath= explode("/",$path);
			$applicationId 	= $applicationPath[count($applicationPath)-2];
		}
		
		$docType 		= $prefixFile;
		$docMimeType 	= !empty($MimeType) ? $MimeType : $COUCHDB->get_mime_type($fileLocation);
		$Document 		= array(
							'action_type' 	=> $docType,
							'application_id'=> $applicationId,
							'storeModule'	=> $storeModule,
							'created'		=> $this->NOW()
						);
		
		$EntryExist	= $this->find('all',array('conditions'=>array('application_id'=>$applicationId,'store_module'=>$storeModule,'access_type'=>$access_type,'action_type'=>$docType)))->first();
		
		if(!empty($EntryExist) && $storeModule != 'Execution' && $access_type != 'others') {
			$this->deleteAll(['id'=>$EntryExist->id]);
			$COUCHDB->deleteDocument($EntryExist->document_id,$EntryExist->rev_id);
			$ApplyonlinDocs->updateAll(['couchdb_id'=>''],['couchdb_id'=>$EntryExist->id]);
		}
		$DocId 			= md5($applicationId.rand());
		$response 		= $COUCHDB->addDcoument($DocId,$Document,$path,$passFileName,$MimeType);

		$arrResponse 	= json_decode($response,2);
		if(isset($arrResponse['ok']) && $arrResponse['ok']==true) {
			$couchdbEntity 	= $this->newEntity();
			$couchdbEntity->application_id 	= $applicationId;
			$couchdbEntity->store_module 	= $storeModule;
			$couchdbEntity->action_type 	= $docType;
			$couchdbEntity->access_type 	= $access_type;
			$couchdbEntity->doc_mime_type 	= $docMimeType;
			$couchdbEntity->customer_id 	= $createdBy;
			$couchdbEntity->file_attached 	= $passFileName;
			$couchdbEntity->document_id 	= $arrResponse['id'];
			$couchdbEntity->rev_id 			= $arrResponse['rev'];
			$couchdbEntity->created_by 		= $createdBy;
			$couchdbEntity->created 		= $this->NOW();
			$this->save($couchdbEntity);
			$this->couchdb_id 				= $couchdbEntity->id;
			return $couchdbEntity->id;
		} else {
			$couchdbEntity 					= $CouchdbFailureLog->newEntity();
			$couchdbEntity->application_id 	= $applicationId;
			$couchdbEntity->store_module 	= $storeModule;
			$couchdbEntity->action_type 	= $docType;
			$couchdbEntity->access_type 	= $access_type;
			$couchdbEntity->doc_mime_type 	= $docMimeType;
			$couchdbEntity->customer_id 	= $createdBy;
			$couchdbEntity->file_attached 	= $passFileName;
			$couchdbEntity->document_id 	= '';
			$couchdbEntity->rev_id 			= '';
			$couchdbEntity->response_data 	= $response;
			$couchdbEntity->created_by 		= $createdBy;
			$couchdbEntity->created 		= $this->NOW();
			$CouchdbFailureLog->save($couchdbEntity);
			$this->couchdb_id 				= '';
			return $couchdbEntity->id;
		}
	}
	/**
	 * documentExist
	 * Behaviour : public
	 * @param : $applicationId,$fileName
	 * @defination : Method is use to store data in couchdb table
	 */
	public function documentExist($applicationId,$fileName)
	{
		/*$recordExist 	= $this->find('all',array('conditions'=>array('application_id'=>$applicationId,'file_attached'=>$fileName)))->first();
		if(!empty($recordExist)) {
			require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
			$COUCHDB 		= new Couchdb();
			if($COUCHDB->documentExist($recordExist->document_id)) {
				return true;
			}
		}*/
		return true;
	}
}
