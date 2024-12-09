<?php

namespace App\Controller\Admin;

use App\Controller\AppController;
//use PHPExcel\PHPExcel;
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

class LeadsController extends AppController
{
    /**
     *
     * The status of $name is universe
     *
     * Potential value is name of class
     *
     * @public string
     *
     */

    public $user_department = array();

    /**
     *
     * The status of $FLAG is universe
     *
     * Potential value is name of class
     *
     * @public string
     *
     */

    public $FLAG = 1;

    var $helpers = array('Time', 'Html', 'Form', 'ExPaginator');
    public $arrDefaultAdminUserRights = array();
    public $PAGE_NAME = '';
    public $paginate = ['limit' => PAGE_RECORD_LIMIT];

    /*
	 * initialize controller
	 *
	 * @return void
	 */
    public function initialize()
    {
        // Always enable the CSRF component.
        parent::initialize();
        $this->loadComponent('Paginator');

        $this->loadModel('Users');
        $this->loadModel('Userrole');
        $this->loadModel('Userroleright');
        $this->loadModel('Adminaction');
        $this->loadModel('Projects');
        $this->loadModel('Customers');
        $this->loadModel('Department');
        $this->loadModel('UserDepartment');
        $this->loadModel('Admintrntype');
        $this->loadModel('Admintrnmodule');
        $this->loadModel('ApiToken');
        $this->loadModel('GhiData');
        $this->loadModel('CustomerProjects');
        $this->loadModel('FinancialIncentives');
        $this->loadModel('Installers');
        $this->loadModel('InstallerProjects');
        $this->loadModel('Parameters');
        $this->loadModel('Emaillog');
        $this->loadModel('StateSubsidy');
        $this->loadModel('SiteSurveys');
        $this->loadModel('SiteSurveysImages');
        $this->loadComponent('PhpExcel');
        $this->loadModel('Leads');
        $this->loadModel('LeadsNotes');
        $this->loadModel('LeadsDocs');
        $this->loadModel('BranchMasters');
        $this->loadModel('DiscomMaster');
        $this->loadModel('States');
        $this->set('Userright', $this->Userright);
    }

    /**
     * index()
     * Develop by Sachin
     * Purpose : Listing of leads
     * */

    public function index($id = null)
    {
        $source_lead = array('1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5' => 'SPIN', '6' => 'AHA!', '7' => 'Other');
        $status = array('still_a_lead' => 'Still a lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived');

        $this->set('category', $this->Parameters->GetParameterList(3));
        $this->set('source_lead', $source_lead);
        $this->set('status_lead', $status);

        $this->intCurAdminUserRight = $this->Userright->LIST_ADMIN_USER_ROLES;
        $this->setAdminArea();
        $arrAdminuserList = array();
        $arrUserType = array();
        $arrCondition = array();
        $this->SortBy = "Leads.id";
        $this->Direction = "DESC";
        $this->intLimit = 10;
        $option = array();
        $option['colName'] = array('id', 'project_name', 'avg_monthly_bill', 'avg_energy_consum', 'contract_load', 'source_lead_name', 'status_name', 'created', 'modified', 'action');
        $this->SetSortingVars('Leads', $option);

        $arrCondition = array();

        if (isset($this->request->data['source_lead']) && $this->request->data['source_lead'] != "") {
            $arrCondition['source_lead'] = $this->request->data['source_lead'];
        }

        if (isset($this->request->data['project_name']) && $this->request->data['project_name'] != '') {
            $arrCondition['project_name LIKE'] = '%' . $this->request->data['project_name'] . '%';
        }
        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $arrCondition['status'] = $this->request->data['status'];
        }
        if (isset($this->request->data['id']) && $this->request->data['id'] != '') {
            $arrCondition['id LIKE'] = '%' . $this->request->data['id'] . '%';
        }

        $this->paginate = array('conditions' => $arrCondition,
            'order' => array($this->SortBy => $this->Direction),
            'page' => $this->CurrentPage,
            'limit' => $this->intLimit);

        $arrAdminuserroleList = $this->paginate('Leads')->toArray();

        if (!empty($arrAdminuserroleList)) {
            foreach ($arrAdminuserroleList as $key => $value) {

                $value->source_lead_name = $source_lead[$value->source_lead];
                $value->status_name = $status[$value->status];
                $value->createdDate = date("d/m/Y", strtotime($value->created));
                $value->createdTime = date("h:i:a", strtotime($value->created));
                foreach ($this->Parameters->GetParameterList(3) as $cate_id => $cate_name) {
                    if ($cate_id == $value->category_id) {
                        $value->category_name = $cate_name;
                    }
                }
            }
        }

        $option['dt_selector'] = 'grid_table';
        $option['formId'] = 'formmain';
        $option['url'] = WEB_ADMIN_PREFIX . 'leads';
        $JqdTablescr = $this->JqdTable->create($option);
        $this->set('JqdTablescr', $JqdTablescr);
        $this->set('arrAdminuserroleList', $arrAdminuserroleList);
        $this->set('arrUserType', '');
        $this->set('period', "");
        $this->set('limit', $this->intLimit);
        $this->set("CurrentPage", $this->CurrentPage);
        $this->set("SortBy", $this->SortBy);
        $this->set("Direction", $this->Direction);
        $this->set("page_count", (isset($this->request->params['paging']['Leads']['pageCount']) ? $this->request->params['paging']['Leads']['pageCount'] : 0));
        $out = array();


        foreach ($arrAdminuserroleList as $key => $val) {
            $temparr = array();
            $project_url = "";
            foreach ($option['colName'] as $key) {
                if ($key == 'modified') {
                    $temparr['modified'] = ((!empty($val['modified'])) ? date('d-m-Y H:i:s', strtoTime($val['modified'])) : '00-00-0000 00:00:00');
                }
                if (isset($val[$key])) {
                    if ($key == 'created') {
                        $temparr['created'] = date('d-m-Y H:i:s', strtoTime($val['created']));
                    } else if ($key != 'modified') {
                        $temparr[$key] = $val[$key];
                    }
                }
                if ($key == 'action') {
                    $url = WEB_ADMIN_PREFIX . 'leads/add/' . encode($val['id']);
                    if ($val['projects_id'] != 0) {
                        $project_url = WEB_ADMIN_PREFIX . 'projects/view/' . encode($val['projects_id']);
                    }
                    $temparr['action'] = '';
                    $temparr['action'] .= '<a href="' . $url . '" rel="editRecord" alt="Edit Lead" title="" data-original-title="Edit Lead"><i class="fa fa-edit"></i></a>' . "&nbsp;";
                    if (($val['projects_id'] != 0) ? 'style="display: none"' : '') {
                        $temparr['action'] .= '<a href="' . $project_url . '" rel="editRecord" target="_blank" alt="View Project" title="" data-original-title="View Project"><i class="fa fa-eye"> </i></a>';
                    }

                }

            }
            $out[] = $temparr;
        }


        if ($this->request->is('ajax')) {

            header('Content-type: application/json');
            echo json_encode(array("draw" => intval($this->request->data['draw']),
                "recordsTotal" => intval($this->request->params['paging']['Leads']['count']),
                "recordsFiltered" => intval($this->request->params['paging']['Leads']['count']),
                "data" => $out));
            die;
        }
    }

    /**
    * add()
    * Develop by Sachin
    * Purpose : Create and Update leads for MOBILE API & WEB
    * $id : Lead Id which want to Edit/Update
    * */

    public function add($id = "")
    {
        $customer_id = '';
        $customer_name = '';
        $this->set("pageTitle", "Customer Leads");
        $this->set(compact("type"));

        if ($this->ismobile()) {
            $this->autoRender = false;
            $cust_id = $this->ApiToken->customer_id;
            if (empty($cust_id)) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'User Not Found!');
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        } else {
            $cust_id = $this->Session->read('User.id');

            if (empty($cust_id)) {
                return $this->redirect("/");
            }
        }

        $lead = $this->Leads->newEntity();
        if ($this->ismobile()) {
            if (isset($this->request->data['id']) && $this->request->data['id'] != "") {
                $id = $this->request->data['id'];
            }
        }

        if (isset($id) && $id != "") {
            if (!$this->ismobile()) {
                $id = decode($id);
            }
            $lead = $this->Leads->get($id);
            $lead->allnotes = $this->LeadsNotes->getAllNotes($lead->id);
            $lead->leads_doc = $this->LeadsDocs->getAllDocument($lead->id);
            $arrCondition = array("Customers.id" => $lead->customer_id);
            $Customer = $this->Customers->find('all', ['conditions' => $arrCondition])->first();
            if (!empty($Customer)) {
                $lead->customer_name = $Customer->name;
                $lead->customer_email = $Customer->email;
            }
        }
        if (!empty($this->request->data)) {
            $this->request->data['project_type'] = $this->request->data['category_id'];
            $locationdata = GetLocationByLatLong($this->request->data['latitude'], $this->request->data['longitude']);
            $this->request->data['address'] = (isset($locationdata['address']) ? $locationdata['address'] : '');
            $this->request->data['city'] = (isset($locationdata['city']) ? $locationdata['city'] : '');
            $this->request->data['state'] = (isset($locationdata['state']) ? $locationdata['state'] : '');
            $this->request->data['state_short_name'] = (isset($locationdata['state_short_name']) ? $locationdata['state_short_name'] : '');
            $this->request->data['country'] = (isset($locationdata['country']) ? $locationdata['country'] : '');
            $this->request->data['pincode'] = (isset($locationdata['postal_code']) ? $locationdata['postal_code'] : '');
            $this->Leads->data = $this->request->data;
            $lead = $this->Leads->patchEntity($lead, $this->request->data, ['validate' => 'lead']);

            if (empty($lead->errors())) {

                $leads = $this->Leads->patchEntity($lead, $this->request->data);
                $customerid = "";
                if (!$this->ismobile()) {
                    $customerid = $this->request->data['customer_id'];
                } else {
                    $customerid = $cust_id;
                }
                $Customer = TableRegistry::get('Customers');
                $installerdata = $Customer->find('all', array('conditions' => array('id' => $customerid)))->first();
                $installerId = (isset($installerdata['installer_id']) ? $installerdata['installer_id'] : 0);
                $leads->installer_id = $installerId;

                if (isset($id) && $id != "") {
                    $leads->modified_by = $cust_id;
                    $leads->modified = $this->NOW();
                } else {
                    $leads->created_by = $cust_id;
                    $leads->created = $this->NOW();
                }

                if ($this->ismobile()) {
                    $leads->customer_id = $customerid;
                }

                $image_path = LEADS_PATH;
                if (!file_exists(LEADS_PATH)) {
                    @mkdir(LEADS_PATH, 0777, true);
                }

                $sucess = "Lead Successfully Saved!";
                if ($this->request->data['status'] == 'convert_enquiry') {
                    $sucess = "Lead is moved to My Project";
                    $projects_id = $this->CreateOrUpdateMyProject($customerid, $installerId, $leads->projects_id);
                    $leads->projects_id = $projects_id;
                }

                if ($lead_save = $this->Leads->save($leads)) {
                    if (isset($this->request->data['new_notes']) && $this->request->data['new_notes'] != "") {
                        $leadsnotes = $this->LeadsNotes->newEntity();
                        $leadsnotes->notes = $this->request->data['new_notes'];
                        $leadsnotes->leads_id = $lead_save->id;
                        $leadsnotes->created_by = $cust_id;
                        $leadsnotes->created = $this->NOW();
                        $this->LeadsNotes->save($leadsnotes);
                    }


                    if (isset($this->request->data['leads_image']) && !empty($this->request->data['leads_image'])) {
                        $this->LeadsDocs->uploadLeadsImages($this->request->data['leads_image'], $image_path, $cust_id, $lead_save->id);
                    }


                    if ($this->ismobile()) {
                        $this->ApiToken->SetAPIResponse('type', 'ok');
                        $this->ApiToken->SetAPIResponse('msg', $sucess);
                        $this->ApiToken->SetAPIResponse('location', $locationdata);
                        echo $this->ApiToken->GenerateAPIResponse();
                        exit;
                    } else {
                        $this->Flash->set('Lead Successfully Saved.', ['key' => 'cutom_admin', 'element' => 'default', 'params' => ['type' => 'success']]);
                        if (isset($id) && $id != "") {
                            $this->redirect(['action' => 'add', encode($id)]);
                        } else {
                            $this->redirect(['action' => 'add']);
                        }
                    }
                }
            } else {

                if (isset($this->request->data['customer_id']) && $this->request->data['customer_id'] != "") {
                    $arrCondition = array("Customers.id" => $this->request->data['customer_id']);
                    $Customer = $this->Customers->find('all', ['conditions' => $arrCondition])->first();
                    if (!empty($Customer)) {
                        $lead->customer_name = $Customer->name;
                        $lead->customer_email = $Customer->email;
                    }
                }

                if ($this->ismobile()) {
                    $this->ApiToken->SetAPIResponse('type', 'error');
                    $this->ApiToken->SetAPIResponse('msg', 'Fields are required!');
                    echo $this->ApiToken->GenerateAPIResponse();
                    exit;
                }
            }
        }

        $this->set("leads", $lead);
        $this->set("cust_id", $cust_id);
        $this->set("customer_id", $customer_id);
        $this->set("customer_name", $customer_name);
        $this->set('ProjectLeadsErrors', $lead->errors());
        $this->set('category', $this->Parameters->GetParameterList(3));
        $this->set('source_lead', array('1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5' => 'SPIN', '6' => 'AHA!', '7' => 'Other'));
        $this->set('status_lead', array('still_a_lead' => 'Still a Lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived'));
        $this->set('areaTypeArr', array('sq ft' => 'sq ft', 'sq mt' => 'sq mt'));
    }

    /*
    * CreateOrUpdateMyProject()
    * Develop by Sachin
    * Purpose : Create and Update project when Lead status is convert to enquiry
    * */


    public function CreateOrUpdateMyProject($customerId = "", $installerId = "", $projects_id = "")
    {
        unset($this->request->data['id']);
        $CreateMyProject = true;
        $this->request->data['backup_type'] = "0";
        $this->request->data['usage_hours'] = "0";
        $this->request->data['project_source'] = $this->Parameters->sourceExternalLead;

        if (isset($projects_id) && $projects_id == "0") {
            $this->request->data['proj_name'] = $this->request->data['project_name'];
            $this->request->data['energy_con'] = $this->request->data['avg_energy_consum'];
        } else {
            $this->request->data['Projects']['id'] = $projects_id;
            $this->request->data['proj_name'] = $this->request->data['project_name'];
            $this->request->data['energy_con'] = $this->request->data['avg_energy_consum'];
        }
        $savedData = $this->Projects->getprojectestimationV2($this->request->data, $customerId, $CreateMyProject);
        return $savedData['proj_id'];
    }


    /**
    * singleLead()
    * Develop by Sachin
    * Purpose : Get Single Leads for Mobile App
    * */

    public function singleLead()
    {
        $source_lead = array('1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5' => 'SPIN', '6' => 'AHA!', '7' => 'Other');
        $status = array('still_a_lead' => 'Still a lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived');
        if ($this->ismobile()) {
            $this->autoRender = false;
            $installerId = $this->ApiToken->customer_id;
            if (empty($installerId)) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'User Not Found!');
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        }

        if ($this->ismobile()) {
            if (isset($this->request->data['id'])) {
                $lead = $this->Leads->get($this->request->data['id'])->toArray();
                if (!empty($lead)) {
                    unset($lead['created']);
                    $lead['source_lead_name'] = $source_lead[$lead['source_lead']];
                    $lead['status_name'] = $status[$lead['status']];
                    foreach ($this->Parameters->GetParameterList(3) as $cate_id => $cate_name) {
                        if ($cate_id == $lead['category_id']) {
                            $lead['category_name'] = $cate_name;
                        }
                    }

                    $lead['allnotes'] = $this->LeadsNotes->getAllNotes($lead['id']);
                    $this->ApiToken->SetAPIResponse('type', 'ok');
                    $this->ApiToken->SetAPIResponse('msg', 'Lead SuccessFully Fetched!');
                    $this->ApiToken->SetAPIResponse('result', $lead);
                    echo $this->ApiToken->GenerateAPIResponse();
                    exit;
                } else {
                    $this->ApiToken->SetAPIResponse('type', 'error');
                    $this->ApiToken->SetAPIResponse('msg', 'User Not Found!');
                    echo $this->ApiToken->GenerateAPIResponse();
                    exit;
                }
            } else {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'User Not Found!');
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        }
    }

    /**
    * allleads()
    * Develop by Sachin
    * Purpose : For Listing of All Leads with pagination For Mobile
    * */

    public function allleads($page = '1')
    {
        $source_lead = array('0' => '', '1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5' => 'SPIN', '6' => 'AHA!', '7' => 'Other');
        $status = array('still_a_lead' => 'Still a lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived');

        if ($this->ismobile()) {
            $this->autoRender = false;
            $cust_id = $this->ApiToken->customer_id;
            $this->paginate['page'] = $this->request->data['page'];
            if (empty($cust_id)) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'User Not Found!');
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        } else {
            $cust_id = $this->Session->read('Customers.id');
            $this->paginate['page'] = isset($this->request->data['page']) ? $this->request->data['page'] : 1;
            if (empty($cust_id)) {
                return $this->redirect("/");
            }
        }
        $this->set("pageTitle", "All Leads");
        $Customer = TableRegistry::get('Customers');
        $installerdata = $Customer->find('all', array('conditions' => array('id' => $cust_id)))->first();
        $installerId = (isset($installerdata['installer_id']) ? $installerdata['installer_id'] : 0);

        $condition = array();
        $installerdata_coworker  = $this->Customers->find('all')
                                    ->leftJoin(['installers' => 'installers'],['installers.id = Customers.installer_id'])
                                    ->where(['Customers.installer_id' => $installerId ])
                                    ->where(['Customers.id NOT IN '=> $cust_id ])
                                    ->order(['Customers.id'=>'desc'])
                                    ->toArray();
                                    
        $arr_cust = array();
        foreach($installerdata_coworker as $data_co)
        {
            $arr_cust[] = $data_co['id'];   
        }

        if(count($arr_cust)>0)
        {
            $condition[] = ['OR' => [
        
            'Leads.customer_id' => $cust_id,
            'Leads.customer_id in '=>$arr_cust
            ]];
        }
        else
        {
            $condition[] = ['Leads.customer_id' => $cust_id];
        }
        if (isset($this->request->data['status_lead']) && $this->request->data['status_lead'] != "") {
            $condition[] = ['Leads.status' => $this->request->data['status_lead']];
        } else {
            //$condition[] = ['Leads.status !=' => 'convert_enquiry'];
        }
        $lead = $this->Leads->find('all')
            ->where($condition)
            ->order(['Leads.id' => 'DESC']);
       
        $pageCount = 0;
        if (count($lead->toArray()) > 0) {
            $pageCount = count($lead->toArray()) / 10;
        }
        $lead = $this->paginate($lead);

        if (!empty($lead)) {
            foreach ($lead as $key => $value) {
                $arr_customer_name = $this->Customers->find('all',array('conditions'=>array('id'=>$value->created_by)))->first();
                $value->creator_name= $arr_customer_name['name'];
                $value->source_lead_name = $source_lead[$value->source_lead];
                $value->status_name = $status[$value->status];
                $value->createdDate = date("d/m/Y", strtotime($value->created));
                $value->createdTime = date("h:i:a", strtotime($value->created));
                $value->allnotes = $this->LeadsNotes->getAllNotes($value->id);
                $value->leadDoc = $this->LeadsDocs->getAllDocument($value->id);
                foreach ($this->Parameters->GetParameterList(3) as $cate_id => $cate_name) {
                    if ($cate_id == $value->category_id) {
                        $value->category_name = $cate_name;
                    }
                }
            }
        }

        if ($this->ismobile()) {
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('result', $lead);
            $this->ApiToken->SetAPIResponse('msg', "Successfully Fetched!");
            $this->ApiToken->SetAPIResponse('CurrentPage', $this->paginate['page']);
            $this->ApiToken->SetAPIResponse('page_count', $pageCount);
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        }

        $this->set("leads", $this->paginate($lead));
    }


    /**
    * get_customer_list()
    * Develop by Sani
    * Purpose : Ajax function, For get customer list on Add / Edit lead section
    * */

    public function get_customer_list()
    {

        $Customers = TableRegistry::get('customers');
        $this->intLimit = 30;
        $arrCondition = array();
        if (isset($this->request->data['name']) && $this->request->data['name'] != "") {
            $arrCondition['name LIKE'] = '%' . $this->request->data['name'] . '%';
        }

        if (isset($this->request->data['email']) && $this->request->data['email'] != '') {
            $arrCondition['email LIKE'] = '%' . $this->request->data['email'] . '%';
        }
        $Customers = $Customers->find('all', array('conditions' => $arrCondition, 'limit' => $this->intLimit))->toArray();

        $data = "<table style='margin-top: 12px;margin-bottom: 12px;border-collapse: collapse;border-spacing: 0; width: 100%;'>
                <tr> 
                    <th style='padding: 10px;text-align: left;background-color: #578ebe;color: white;border: 1px solid #ddd;'>Add</th> 
                    <th style='padding: 10px;text-align: left;background-color: #578ebe;color: white;border: 1px solid #ddd;'>Customer Name</th> 
                    <th style='padding: 10px;text-align: left;background-color: #578ebe;color: white;border: 1px solid #ddd;'>Email</th>
                </tr>";
        foreach ($Customers as $key => $value) {
            $data .= "<tr> <td style='text-align: left;padding: 10px; border: 1px solid #ddd;'> <input type='radio' name='customer_id' value='$value->id' data-name='$value->name' data-email='$value->email'>  </td>";
            $data .= "<td id='cusname $value->id' style='text-align: left;padding: 10px;  border: 1px solid #ddd;'>" . $value->name . "</td>";
            $data .= "<td id='cusemail $value->id' style='text-align: left;padding: 10px; border: 1px solid #ddd;'>" . $value->email . "</td> </tr>";
        }
        $data .= "</table>";
        echo $data;
        exit;
    }


    /**
    * leadsdropdown()
    * Develop by Sachin
    * Purpose : Mobile Purpose only
    * */

    public function leadsdropdown(){

        $category = array();
        foreach ($this->Parameters->GetParameterList(3) as $key => $value) {
            $category[] = array('id' => $key, 'name' => $value);
        }

        $areaTypeArr = array();
        foreach ($this->Parameters->getAreaType() as $key => $value) {
            $areaTypeArr[] = array('id' => $key, 'name' => $value);
        }

        if ($this->ismobile()) {
            $this->ApiToken->SetAPIResponse('type', 'ok');
            $this->ApiToken->SetAPIResponse('areaType', $areaTypeArr);
            $this->ApiToken->SetAPIResponse('category', $category);
            $this->ApiToken->SetAPIResponse('source_lead', [['id' => '1', 'name' => 'Cold-Calling'], ['id' => '2', 'name' => 'Reference'], ['id' => '3', 'name' => 'Blind Visit'], ['id' => '4', 'name' => 'Toll-free Number'], ['id' => '5', 'name' => 'SPIN'], ['id' => '6', 'name' => 'AHA!'], ['id' => '7', 'name' => 'Other']]);
            $this->ApiToken->SetAPIResponse('status_lead', [['id' => 'still_a_lead', 'name' => 'Still a Lead'], ['id' => 'convert_enquiry', 'name' => 'Convert to Enquiry'], ['id' => 'archived', 'name' => 'Archived']]);
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        }
    }

    /**
     * leadsall()
     * Develop by Sachin
     * Purpose : Display Listing of leads in Admin Datatable
     * */

    public function leadsall($id = null)
    {

        $source_lead = array('1' => 'Cold-Calling', '2' => 'Reference', '3' => 'Blind Visit', '4' => 'Toll-free Number', '5' => 'SPIN', '6' => 'AHA!', '7' => 'Other');
        $status = array('still_a_lead' => 'Still a lead', 'convert_enquiry' => 'Convert to Enquiry', 'archived' => 'Archived');

        $this->set('category', $this->Parameters->GetParameterList(3));
        $this->set('source_lead', $source_lead);
        $this->set('status_lead', $status);

        $this->intCurAdminUserRight = $this->Userright->LIST_ADMIN_USER_ROLES;
        $this->setAdminArea();
        $arrAdminuserList   = array();
        $arrUserType        = array();
        $arrCondition       = array();
        $this->SortBy       = "Leads.id";
        $this->Direction    = "DESC";
        $this->intLimit     = 10;
        $option = array();
        $option['colName'] = array('id', 'project_name', 'avg_monthly_bill', 'avg_energy_consum', 'contract_load', 'source_lead_name', 'status_name', 'created', 'modified', 'action');
        $this->SetSortingVars('Leads', $option);

        $arrCondition = array();
        if (isset($this->request->data['source_lead']) && $this->request->data['source_lead'] != "") {
            $arrCondition['source_lead'] = $this->request->data['source_lead'];
        }

        if (isset($this->request->data['project_name']) && $this->request->data['project_name'] != '') {
            $arrCondition['project_name LIKE'] = '%' . $this->request->data['project_name'] . '%';
        }
        if (isset($this->request->data['status']) && $this->request->data['status'] != '') {
            $arrCondition['status'] = $this->request->data['status'];
        }
        if (isset($this->request->data['id']) && $this->request->data['id'] != '') {
            $arrCondition['id LIKE'] = '%' . $this->request->data['id'] . '%';
        }

        $this->paginate = array('conditions' => $arrCondition,
            'order' => array($this->SortBy => $this->Direction),
            'page' => $this->CurrentPage,
            'limit' => $this->intLimit);

        $arrAdminuserroleList = $this->paginate('Leads')->toArray();

        if (!empty($arrAdminuserroleList)) {
            foreach ($arrAdminuserroleList as $key => $value) {

                $value->source_lead_name    = $source_lead[$value->source_lead];
                $value->status_name         = $status[$value->status];
                $value->createdDate         = date("d/m/Y", strtotime($value->created));
                $value->createdTime         = date("h:i:a", strtotime($value->created));
                foreach ($this->Parameters->GetParameterList(3) as $cate_id => $cate_name) {
                    if ($cate_id == $value->category_id) {
                        $value->category_name = $cate_name;
                    }
                }
            }
        }

        $option['dt_selector']  = 'grid_table';
        $option['formId']       = 'formmain';
        $option['url']          = WEB_ADMIN_PREFIX . 'projects/leadsall';
        $JqdTablescr = $this->JqdTable->create($option);
        $this->set('JqdTablescr', $JqdTablescr);
        $this->set('arrAdminuserroleList', $arrAdminuserroleList);
        $this->set('arrUserType', '');
        $this->set('period', "");
        $this->set('limit', $this->intLimit);
        $this->set("CurrentPage", $this->CurrentPage);
        $this->set("SortBy", $this->SortBy);
        $this->set("Direction", $this->Direction);
        $this->set("page_count", (isset($this->request->params['paging']['Leads']['pageCount']) ? $this->request->params['paging']['Leads']['pageCount'] : 0));
        $out = array();


        foreach ($arrAdminuserroleList as $key => $val) {
            $temparr = array();
            $project_url = "";
            foreach ($option['colName'] as $key) {
                if ($key == 'modified') {
                    $temparr['modified'] = ((!empty($val['modified'])) ? date('d-m-Y H:i:s', strtoTime($val['modified'])) : '00-00-0000 00:00:00');
                }
                if (isset($val[$key])) {
                    if ($key == 'created') {
                        $temparr['created'] = date('d-m-Y H:i:s', strtoTime($val['created']));
                    } else if ($key != 'modified') {
                        $temparr[$key] = $val[$key];
                    }
                }
                if ($key == 'action') {
                    $url = WEB_ADMIN_PREFIX . 'projects/leads/' . encode($val['id']);
                    if ($val['projects_id'] != 0) {
                        $project_url = WEB_ADMIN_PREFIX . 'projects/view/' . encode($val['projects_id']);
                    }
                    $temparr['action'] = '';
                    $temparr['action'] .= '<a href="' . $url . '" rel="editRecord" alt="Edit Lead" title="" data-original-title="Edit Lead"><i class="fa fa-edit"></i></a>' . "&nbsp;";
                    if (($val['projects_id'] != 0) ? 'style="display: none"' : '') {
                        $temparr['action'] .= '<a href="' . $project_url . '" rel="editRecord" target="_blank" alt="View Project" title="" data-original-title="View Project"><i class="fa fa-eye"> </i></a>';
                    }
                }
            }
            $out[] = $temparr;
        }


        if ($this->request->is('ajax')) {
            header('Content-type: application/json');
            echo json_encode(array("draw" => intval($this->request->data['draw']),
                "recordsTotal" => intval($this->request->params['paging']['Leads']['count']),
                "recordsFiltered" => intval($this->request->params['paging']['Leads']['count']),
                "data" => $out));
            die;
        }

    }

    /**
     * removeLeadsImages()
     * Develop by Sachin
     * Purpose : Remove Leads Document for Mobile and Web Both
     * */

    public function removeLeadsImages()
    {
        if ($this->ismobile()) {
            $this->autoRender = false;
            $cust_id = $this->ApiToken->customer_id;
            if (empty($cust_id)) {
                $this->ApiToken->SetAPIResponse('type', 'error');
                $this->ApiToken->SetAPIResponse('msg', 'User Not Found!');
                echo $this->ApiToken->GenerateAPIResponse();
                exit;
            }
        }else{
            $this->request->data['lead_doc_id'] = decode($this->request->data['lead_doc_id']);
        }

        $leadDoc = $this->LeadsDocs->get($this->request->data['lead_doc_id']);
        if (isset($leadDoc) && !empty($leadDoc)) {
            if ($leadDoc->filename != "" && file_exists(LEADS_PATH . $leadDoc->filename)) {
                unlink(LEADS_PATH . $leadDoc->filename);
            }
            if ($this->LeadsDocs->delete($leadDoc)) {
                $this->ApiToken->SetAPIResponse('type', 'ok');
                $this->ApiToken->SetAPIResponse('msg', 'Lead Image deleted!');
                echo $this->ApiToken->GenerateAPIResponse();
            }

        } else {
            $this->ApiToken->SetAPIResponse('type', 'error');
            $this->ApiToken->SetAPIResponse('msg', 'Id Not Found!');
            echo $this->ApiToken->GenerateAPIResponse();
            exit;
        }

        exit;
        //$result = $this->Articles->delete($entity);
    }

}
