<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Couchdb\Couchdb;
class ReCouchdbTable extends AppTable
{
	var $table 		= 're_couchdb';
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
		$ApplicationsDocs	= TableRegistry::get('ApplicationsDocs');

		$CouchdbFailureLog	= TableRegistry::get('ReCouchdbFailureLog');
		
		require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
		$COUCHDB 		= new Couchdb();
		$applicationWTGPath 				= explode(WTG_PATH,$path);
		$applicationPath 					= explode(APPLICATIONS_PATH,$path);
		$applicationDeveloperPermissionPath = explode(APPLICATIONS_DEVELOPER_PERMISSION_PATH,$path);
		$applicationDeveloperWorkorderPath 	= explode(DEVELOPER_WORKORDER_PATH,$path);
		$storeModule 						= '';
		
		if(count($applicationPath) > 1) {
			$storeModule 	= 'Applications';
			$applicationPath= explode("/",$path);
			$applicationId 	= $applicationPath[count($applicationPath)-2];
		} else if(count($applicationDeveloperPermissionPath)>1){
			$storeModule 	= 'Applications Development Permission';
			$applicationPath= explode("/",$path);
			$applicationId 	= $applicationPath[count($applicationPath)-2];
		} else if(count($applicationDeveloperWorkorderPath)>1){
			$storeModule 	= 'Developer Workorder';
			$applicationPath= explode("/",$path);
			$applicationId 	= $applicationPath[count($applicationPath)-2];
		} else if(count($applicationWTGPath)>1){
			$storeModule 	= 'Geo WTG';
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
		
		if(!empty($EntryExist) && $access_type != 'others' && $access_type != 'STUstep1' && $access_type != 'STUstep2' && $access_type != 'CTUstep1' && $access_type != 'CTUstep2') {
			$this->deleteAll(['id'=>$EntryExist->id]);
			$COUCHDB->deleteDocument($EntryExist->document_id,$EntryExist->rev_id);
			$ApplicationsDocs->updateAll(['couchdb_id'=>''],['couchdb_id'=>$EntryExist->id]);
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
