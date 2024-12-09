<?php
namespace App\Model\Table;
use App\Controller\AppController;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class LeadsDocsTable extends AppTable
{
	var $table = 'lead_documents';
	var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    /**
     * getAllDocument()
     * Develop by Sachin
     * Purpose : Get uploaded document list of particular leads
     * $leads_id : Lead id
     * Return   : LeadsDocumentID, FIle name and FileUrl for display
     **/

    public function getAllDocument($leadId=null){

        $documents = array();
        if($leadId !="") {
            $lead_documents = $this->find('all',[
                'conditions' =>array("leads_id" => $leadId),
                'order'=>array('created'=>'DESC')])->toArray();
            if(!empty($lead_documents)){
                foreach ($lead_documents as $key => $value){
                    if($value['filename'] !="") {
                        $documents[] = array(
                            'id' => $value['id'], 'filename' => URL_HTTP . LEADS_PATH . $value['filename'], 'filename_only' => $value['filename']
                        );
                    }
                }
            }
        }
        return $documents;
    }

    /**
     * uploadLeadsImages()
     * Develop by Sachin
     * Purpose : Upload Leads Document Mobile, Web Frontend and Backend
     * $images : Image request Parameter
     * $path    : Image path where to store /img/leads/
     * $cust_id : Uploaded customer id
     * $leads_id : Lead id
     **/

    public function uploadLeadsImages($images=array(),$path,$cust_id,$leads_id)
    {
        $lead_documents = $this->find('all',[
            'conditions' =>array("leads_id" => $leads_id),
            'order'=>array('created'=>'DESC')])->toArray();

        if(isset($lead_documents) && count($lead_documents) == 2){
            return;
        }

        $leadsImage = array();
        if (!empty($images)) {
            foreach ($images as $key => $image) {
                // $leadsImage[$image['leads_image']] = $this->file_upload($path, $image, true, 65, 65, $path, 'leads-'.rand());
                $leadsImage['filename'] = AppController::file_upload($path, $image, false, 65, 65, $path, 'leads-'.rand());
                $leadsImage['status']       = 1;
                $leadsImage['leads_id']     = $leads_id;
                $leadsImage['created_by']   = $cust_id;
                $leadsImage['modified_by']  = $cust_id;
                $leadsDocs                  = $this->newEntity($leadsImage);
                $leadsDocs->modified        =  AppController::NOW();
                $leadsDocs->created         =  AppController::NOW();
                $this->save($leadsDocs);
            }
        }
    }
}
?>