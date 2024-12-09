<?php
namespace App\Controller;

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
use Dompdf\Dompdf;


class LeadsController extends FrontAppController
{
    public $paginate = [
        'limit' => PAGE_RECORD_LIMIT,
        'order' => [
            'Projects.id ' => 'desc'
        ]
    ];
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Paginator');
        $this->loadComponent('PhpExcel');
        $this->loadModel('Customers');
        $this->loadModel('Installers');
        $this->loadModel('Projects');
        $this->loadModel('ProjectLeads');
        $this->loadModel('CustomerProjects');
        $this->loadModel('Parameters');
        $this->loadModel('GhiData');
        $this->loadModel('Commercial');
        $this->loadModel('ProjectNotes');
        $this->loadModel('SiteSurveys');
        $this->loadModel('SiteSurveysImages');
        $this->loadModel('ApplyOnlines');
        $this->loadModel('Commissioning');
        $this->loadModel('Installation');
        $this->loadModel('InstallerUsers');
        $this->loadModel('Workorder');
        $this->loadModel('ProjectDocument');
        $this->loadModel('Leads');
        $this->loadModel('Proposal');
        $this->loadModel('ApiToken');
        $this->loadModel('LeadsNotes');
        $this->loadModel('LeadsDocs');
        $this->loadModel('ProjectAssignBd');
        $this->set('Userright',$this->Userright);
    }
    /**
     *
     * List all installer projects
     */
    public function index($leadstatus="",$page = '1')
    {

        $this->setCustomerArea();
        $source_lead = array('0' => '', '1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5'=>'SPIN', '6'=>'AHA!','7'=>'Other');
        $status = array('still_a_lead' => 'Still a Lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived');

        if($this->ismobile()){
            $this->autoRender 	    = false;
            $cust_id	            = $this->ApiToken->customer_id;
            $this->paginate['page'] = $this->request->data['page'];
            if(empty($cust_id)) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('message', 'User Not Found!');
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        }else{
            $cust_id = $this->Session->read('Customers.id');
            $this->paginate['page'] = $page;
            if(empty($cust_id)){
                return $this->redirect("/");
            }
        }


        $this->set("pageTitle","My Leads");

        $condition = array();
        $condition[] = ['Leads.customer_id'=>$cust_id];
        if(isset($leadstatus) && $leadstatus !="" && $leadstatus=="archived"){
            $condition[] = ['Leads.status'=>'archived'];
            $this->set('leadstatus',$leadstatus);
        }else{
            $condition[] = ['Leads.status !='=>'convert_enquiry'];
        }

        $lead  = $this->Leads->find('all')
            ->where($condition)
            ->order(['Leads.id'=>'DESC']);

        $lead = $this->paginate($lead);

        if(!empty($lead)){
            foreach($lead as $key => $value){
                $value->source_lead_name    = $source_lead[$value->source_lead];
                $value->status_name         = $status[$value->status];
                $value->createdDate         = date("d/m/Y", strtotime($value->created));
                $value->createdTime         = date("h:i:a", strtotime($value->created));
                foreach ($this->Parameters->GetParameterList(3) as $cate_id =>$cate_name){
                    if($cate_id == $value->category_id){
                        $value->category_name = $cate_name;
                    }
                }
            }
        }

        $count = count($lead->toArray());

        if($this->ismobile()){
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('result', $lead);
            $this->ApiToken->SetAPIResponse('perpage', '10');
            $this->ApiToken->SetAPIResponse('total_record',$count );
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        }


        $this->set("leads",$lead);
        $this->set('categories',$this->Parameters->GetParameterList(3));
        $this->set('source_lead', $source_lead);
        $this->set('status_lead', $status);

    }


    /**
     *
     * List all projects on which installer received leads
     */
    public function add($id="")
    {
        $this->setCustomerArea();
        $type = 'pending';
        $this->set("pageTitle","Customer Leads");
        $this->set(compact("type"));

        $areaTypeArr = $this->Parameters->getAreaType();

        $cust_id     = $this->Session->read('Customers.id');
        if(empty($cust_id)){
            return $this->redirect("/");
        }

        $lead = $this->Leads->newEntity();
        if(isset($id) && $id!=""){
            $lead            = $this->Leads->get(decode($id));
            $lead->allnotes  = $this->LeadsNotes->getAllNotes($lead->id);
            $lead->leads_doc = $this->LeadsDocs->getAllDocument($lead->id);
        }

        if(!empty($this->request->data)){
            $this->request->data['customer_id'] = $cust_id;
            $this->request->data['project_type'] = $this->request->data['category_id'];

            $this->Leads->data = $this->request->data;
            $lead = $this->Leads->patchEntity($lead,$this->request->data, ['validate' => 'lead']);

            if(empty($lead->errors())){

                $locationdata                               = GetLocationByLatLong($this->request->data['latitude'],$this->request->data['longitude']);
                $this->request->data['address']				= (isset($locationdata['address'])?$locationdata['address']:'');
                $this->request->data['city']				= (isset($locationdata['city'])?$locationdata['city']:'');
                $this->request->data['state']				= (isset($locationdata['state'])?$locationdata['state']:'');
                $this->request->data['state_short_name']	= (isset($locationdata['state_short_name'])?$locationdata['state_short_name']:'');
                $this->request->data['country']				= (isset($locationdata['country'])?$locationdata['country']:'');
                $this->request->data['pincode']				= (isset($locationdata['postal_code'])?$locationdata['postal_code']:'');

                $leads                  = $this->Leads->patchEntity($lead, $this->request->data);
                $Customer = TableRegistry::get('Customers');
                $installerdata = $Customer->find('all', array('conditions'=>array('id'=>$cust_id)))->first();
                $installerId            = (isset($installerdata['installer_id'])?$installerdata['installer_id']:0);

                $leads->installer_id = $installerId;
                if(isset($id) && $id!=""){
                    $leads->modified_by = $cust_id;
                    $leads->modified =  $this->NOW();
                }else{
                    $leads->created_by = $cust_id;
                    $leads->created =  $this->NOW();
                }

                $flash = "Lead Successfully Saved!";
                if($this->request->data['status'] == 'convert_enquiry'){
                    $projects_id =  $this->CreateOrUpdateMyProject($cust_id,$installerId,$leads->projects_id);
                    $this->ProjectAssignBd->assignBD($projects_id,$installerId, $cust_id);
                    $leads->projects_id = $projects_id;
                    $flash = "Lead is moved to My Project";
                }

                $image_path = LEADS_PATH;
                if(!file_exists(LEADS_PATH)){
                    @mkdir(LEADS_PATH, 0777,true);
                }

                if ($lead_save = $this->Leads->save($leads)) {
                    if(isset($this->request->data['new_notes']) &&  $this->request->data['new_notes'] !="") {
                        $leadsnotes             = $this->LeadsNotes->newEntity();
                        $leadsnotes->notes      = $this->request->data['new_notes'];
                        $leadsnotes->leads_id   = $lead_save->id;
                        $leadsnotes->created_by = $cust_id;
                        $leadsnotes->created    = $this->NOW();
                        $this->LeadsNotes->save($leadsnotes);
                    }

                    if(isset($this->request->data['leads_image']) && !empty($this->request->data['leads_image'])){
                        $this->LeadsDocs->uploadLeadsImages($this->request->data['leads_image'], $image_path, $cust_id, $lead_save->id);
                    }

                    $this->Flash->success($flash);
                    $this->redirect(['action' => 'add', $id]);
                }
            }else{
                $this->Flash->success('Fields are Required!');
            }
        }

        $this->set('areaTypeArr',$areaTypeArr);
        $this->set("leads",$lead);
        $this->set('ProjectLeadsErrors',$lead->errors());
        $this->set('category',$this->Parameters->GetParameterList(3));
        $this->set('source_lead',array('1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5'=>'SPIN', '6'=>'AHA!','7'=>'Other'));
        $this->set('status_lead',array('still_a_lead' => 'Still a Lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived'));

    }


    public function CreateOrUpdateMyProject($customerId="", $installerId="", $projects_id="")
    {
        $CreateMyProject = true;
        $this->request->data['backup_type']="0";
        $this->request->data['usage_hours']="0";
        $this->request->data['project_source'] = $this->Parameters->sourceExternalLead;


        if(isset($projects_id) && $projects_id == "0"){
            $this->request->data['proj_name']            = $this->request->data['project_name'];
            $this->request->data['energy_con'] = $this->request->data['avg_energy_consum'];

        }else{
            $this->request->data['Projects']['id'] = $projects_id;
            $this->request->data['proj_name']            = $this->request->data['project_name'];
            $this->request->data['energy_con'] = $this->request->data['avg_energy_consum'];
        }

        $savedData = $this->Projects->getprojectestimationV2($this->request->data,$customerId,$CreateMyProject);
        return  $savedData['proj_id'];

    }

    public function removeLeadsImages(){
        if ($this->request->is(array('ajax'))){
           if(isset($this->request->data['id']) && $this->request->data['id'] !=""){
               $leadDoc = $this->LeadsDocs->get(decode($this->request->data['id']));
               if(isset($leadDoc) && !empty($leadDoc)){
                   if($leadDoc->filename !="" && file_exists(LEADS_PATH.$leadDoc->filename)) {
                       unlink(LEADS_PATH.$leadDoc->filename);
                   }
                   if($this->LeadsDocs->delete($leadDoc)){
                       $this->ApiToken->SetAPIResponse('type', 'success');
                       $this->ApiToken->SetAPIResponse('msg', 'Lead Image deleted!');
                       $this->Flash->success('Lead Image deleted!');
                   }else{
                       $this->ApiToken->SetAPIResponse('type', 'error');
                       $this->ApiToken->SetAPIResponse('msg', 'something is wrong');
                       $this->Flash->error('something is wrong');
                   }
               }
           }else{
               $this->ApiToken->SetAPIResponse('type', 'error');
               $this->ApiToken->SetAPIResponse('msg', 'something is wrong');
           }
        }
        echo $this->ApiToken->GenerateAPIResponse();
        exit;
    }

}
