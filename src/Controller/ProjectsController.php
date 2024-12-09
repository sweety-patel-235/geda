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
use Cake\Datasource\ConnectionManager;
use Couchdb\Couchdb;

class ProjectsController extends FrontAppController
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
        $this->loadModel('InstallerTerms');
        $this->loadModel('ProjectAssignBd');
        $this->loadModel('CommissioningData');
        $this->loadModel('CommissioningImage');
        $this->loadModel('ProjectInstallationPhotos');
        $this->loadModel('InstallerProjects');
        $this->loadModel('WorkCompletion');
        $this->loadModel('States');
        $this->loadModel('ApplyOnlineApprovals');
        $this->loadModel('FesibilityReport');
        $this->loadModel('ChargingCertificate');
        $this->loadModel('Couchdb');
        $this->set('Userright',$this->Userright);
        
    }
    /**
     *
     * List all installer projects
     */
    public function index($page = '1',$projectType = null)
    {
        $this->setCustomerArea();
        $this->paginate['page'] = $page;
        $this->set("pageTitle","Projects");
        $projects       = array();
        $projectType    = intval(decode($projectType));
        $requestData    = array();

        if(isset($this->request->data['search_project']) && !empty($this->request->data['search_project'])){
            $this->Session->write("ProjectsSearch",$this->request->data);
        } else {
            if($this->Session->check("ProjectsSearch")) {
                $this->request->data = $this->Session->read("ProjectsSearch");
            }
        }
        if($this->Session->check('Customers')) {
            $customerId = $this->Session->read('Customers.id');
            $requestData['customer_id'] = $customerId;
            if(!empty($this->request->data)) {
                $requestData = $this->request->data;
                $requestData['customer_id'] = $customerId;
            }
        }
        $customerdata       = $this->Customers->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$customerId)))->first();
        $installer_id       = isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
        
        $requestData['installer_id'] = $installer_id;
        $projects           = $this->CustomerProjects->getProjectListByCondition($requestData);

        $projectTypeList    = $this->Parameters->getProjectType();
        $project_all_data   = $this->paginate($projects)->toArray();
        foreach($project_all_data as $k=>$pr_data)
        {
            $project_all_data[$k]['projects']['is_apply_disp']='1';
            $is_apply = '1';
            $arr_apply_online = $this->ApplyOnlines->find("all",['conditions'=>['ApplyOnlines.project_id'=>$pr_data['projects']['id'],'ApplyOnlines.application_status Is Not null']])->first();
            if(!empty($arr_apply_online))
            {
                $project_all_data[$k]['projects']['is_apply_disp']='0';
            }
        }
        if (!empty($projects)) {
            $this->set('projectLeads',$project_all_data);
        } else {
            $this->set('projectLeads',array());
        }
        $this->set(compact("projectTypeList","projectType"));
        $projectSourceList = $this->Parameters->getProjectSource();
        $this->set(compact("projectSourceList")); //,"projectSource"
    }
    public function listproject($projectType = null)
    {
        $this->set("pageTitle","Projects");
        $projects = array();
        $projectType = intval(decode($projectType));
        $customerId = $this->Session->read('Customers.id');

        $requestData['customer_id'] = $customerId;
        if(!empty($this->request->data)) {
            $requestData = $this->request->data;
            $requestData['customer_id'] = $customerId;
        }

        $projects = $this->CustomerProjects->getProjectListByCondition($requestData);
        $projectTypeList = $this->Parameters->getProjectType();

        $this->set('projectLeads',$this->paginate($projects));
        $this->set(compact("projectTypeList","projectType"));
    }

    public function dashboard($id = null)
    {
        $customerId     = $this->Session->read('Customers.id');
        if(empty($customerId)) {
            return $this->redirect(array('controller'=>'users','action'=>'index'));
        }
        $projectId      = intval(decode($id));
        $project        = $this->CustomerProjects->findByProjectId($projectId)->contain("Projects","Customers")->first();

        $customerData   = $this->Customers->findById($customerId)->first();

        /** Display State Name */
        if (ctype_digit($customerData->state)) {
            $State = $this->States->findById($customerData->state)->first();
            $customerData->state = $State->statename;
        }
        /** Display State Name */

        $noteDataArr    = $this->ProjectNotes->findByProjectId($projectId)->first();

        $requestData = array();
        $requestData['energy_con']          = $project['project']->estimated_kwh_year;
        $requestData['area_type']           = $project['project']->area_type;
        $requestData['area']                = $project['project']->area;
        $requestData['latitude']            = $project['project']->latitude;
        $requestData['longitude']           = $project['project']->longitude;
        $requestData['avg_monthly_bill']    = $project['project']->avg_monthly_bill;
        $requestData['backup_type']         = $project['project']->backup_type;
        $requestData['usage_hours']         = $project['project']->usage_hours;
        $requestData['project_type']        = $project['project']->project_type;

        $areaTypeArr    = $this->Parameters->getAreaType();
        $custTypeArr    = $this->Parameters->getProjectType();
        $backupTypeArr  = $this->Projects->backupTypeArr;
        $resultArr      = $this->Projects->getprojectestimation($requestData);
        $projectInstallers = $this->InstallerProjects->getProjectwiseInstallerList($id);

        /* Energy and Month Saving Data */
        $solarRediationData     = $this->GhiData->getGhiData($project['project']->longitude,$project['project']->latitude);
        $energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project['project']->recommended_capacity,$project['project']->avg_monthly_bill,$project['project']->estimated_kwh_year);

        /* Solar PV Chart Data */
        $monthSavinData     = (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
        $monthly_saving     = array_sum($monthSavinData);
        $estimated_cost_subsidy = isset($project['project']->estimated_cost_subsidy)?round(($project['project']->estimated_cost_subsidy/100000),2):$project['project']->estimated_cost;
        $payBackGraphData   = $this->Projects->GetPaybackChartData($estimated_cost_subsidy, $monthly_saving);


        $this->set(compact('noteDataArr','project','resultArr','projectInstallers','id','customerData','areaTypeArr','custTypeArr','paybackGraphImg','backupTypeArr'));
        $this->set("pageTitle","Project: ".$project['project']->name);
        $this->set("payBackGraphData",$payBackGraphData);
        $this->set('project_en_id',$id);
    }
    /**
     *
     * List all projects on which installer received leads
     */
    public function leads($type = 'pending')
    {
        $this->set("pageTitle","Customer Leads");
        $cus_id             = $this->Session->read('Customers.id');
        $customerdata       = $this->Customers->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$cus_id)))->first();
        $installerId        = isset($customerdata['installer_id'])?$customerdata['installer_id']:0;
        $condition          = array();
        $condition[0]       = 'projects.name is not null';
        $condition['projects.name != '] = '';
        if($installerId != 0)
        {
            if($type == 'accepted')
            {
                $condition[]        = array('InstallerProjects.installer_id = ' =>$installerId,'InstallerProjects.status'=>'4002');
            }
            elseif($type == 'rejected')
            {
                $condition[]        = array('InstallerProjects.installer_id = ' =>$installerId,'InstallerProjects.status'=>'4003');
            }
            elseif($type == 'forwarded')
            {
                $condition[]        = array('InstallerProjects.installer_id = ' =>$installerId,'InstallerProjects.status'=>'4004');
            }
            else
            {
                $condition[]        = array('InstallerProjects.installer_id = ' =>$installerId,'InstallerProjects.status'=>'4001');
            }
        }

        if(!empty($installerId) && $installerId != 0) {
            $JoinTables = array('projects'   => array(
                                                    'table' => 'projects',
                                                    'type' => 'LEFT',
                                                    'conditions' => ['InstallerProjects.project_id=projects.id']),
                                'parameters' => array(
                                                    'table' => 'parameters',
                                                    'type' => 'LEFT',
                                                    'conditions' => ['parameters.para_id = projects.customer_type'])
                            );
            $customerdata       = $this->Customers->find('all', array('fields'=>['id','installer_id','user_role'],'conditions'=>array('id'=>$cus_id)))->first();
            $user_roles = explode(",",$customerdata->user_role);
            $InstallerProjectsT = TableRegistry::get('InstallerProjects');
            
        }
        $query              = $InstallerProjectsT->find('all',
                                array('fields'  => ['projects.id','projects.name','projects.address','projects.city','projects.state','projects.state_short_name','projects.country','projects.pincode','projects.landmark','projects.created','projects.solar_radiation','projects.area','projects.area_type','projects.customer_type','projects.capacity_kw','projects.recommended_capacity','projects.latitude','projects.longitude','parameters.para_value'],
                                'join'      => $JoinTables,
                                'conditions'=> $condition,
                                'order'     => array('projects.id'=>'DESC')));
        $this->set('projectLeads',$this->paginate($query));
        $this->set(compact("type"));


        //$projects       = $this->ProjectLeads->getProjectLeads($installerId,$type);
        
    }



    /**
     *
     * List all projects on which installer received leads
     */
    public function forward($id = null)
    {
        $projectLeadId = intval(decode($id));
        $this->set("pageTitle","Forword");
        $projectLead  = $this->ProjectLeads->findById($projectLeadId)->contain("Projects")->first();
        //prd($projectData);
        $installerId = $this->Session->read('Customers.id');
        $installersData = $this->Installers->find("all");
        $installers = $this->paginate($installersData);
        $this->set(compact("projectLead","installers"));
    }
    public function documentupload()
    {
        $this->layout =false;
        $this->autoRander = false;
        echo "<pre>";
        print_r($_FILE);
        print_r($_POST);
        echo "</pre>";
    }

    /**
     *
     * Project Details Page for installer
     *
     * @param : $id: Project Id
     */
    public function view($id = null)
    {
        $projectId    = intval(decode($id));
        $customerId = $this->Session->read('Customers.id');
        $project  = $this->CustomerProjects->findById($projectId)->contain("Projects","Customers")->first();
        $noteDataArr = $this->ProjectNotes->findByProjectId($projectId)->first();

        $requestData =array();
        $requestData['energy_con'] = $project['project']->estimated_kwh_year;
        $requestData['area_type'] = $project['project']->area_type;
        $requestData['area'] = $project['project']->area;
        $requestData['latitude'] = $project['project']->latitude;
        $requestData['longitude'] = $project['project']->longitude;
        $requestData['avg_monthly_bill'] = $project['project']->avg_monthly_bill;
        $requestData['backup_type'] = $project['project']->backup_type;
        $requestData['usage_hours'] = $project['project']->usage_hours;
        $requestData['project_type'] = $project['project']->project_type;
        $areaTypeArr = $this->Parameters->getAreaType();
        $custTypeArr = $this->Parameters->getProjectType();
        $resultArr = $this->Projects->getprojectestimation($requestData);
        $customerArr = $this->Customers->getCustomerByProjectid($id);
        $projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
        /* Energy and Month Saving Data */
        $solarRediationData = $this->GhiData->getGhiData($project['project']->longitude,$project['project']->latitude);
        $energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project['project']->recommended_capacity,$project['project']->avg_monthly_bill,$project['project']->estimated_kwh_year);

        /* Solar PV Chart Data */
        $monthSavinData     = (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
        $monthly_saving     = array_sum($monthSavinData);
        $estimated_cost_subsidy = isset($project->estimated_cost_subsidy)?round(($project->estimated_cost_subsidy/100000),2):$project->estimated_cost;
        $payBackGraphData   = $this->Projects->GetPaybackChartData($estimated_cost_subsidy, $monthly_saving);

        $this->set(compact('noteDataArr','project','resultArr','projectInstallers','id','customerArr','areaTypeArr','custTypeArr','paybackGraphImg'));
        $this->set("pageTitle","Project: ".$project['project']->name);
        $this->set("payBackGraphData",$payBackGraphData);

    }

    public function downloadreport()
    {
        $this->autoRander = false;
        $this->genrateReportMain();
    }

    /**
     *
     * Reject the project lead
     *
     * @param : $id: Id is use to identify lead
     * @defination : Method is use to Reject the project lead
     *
     */
    function rejectlead($id = null) {
        if(!$this->request->is('post')){
            return $this->redirect(array('action'=>'leads'));
        }
        $id    = intval(decode($id));

        $projectLead = $this->ProjectLeads->get($id);
        $projectLead->status = 'rejected';

        if($this->ProjectLeads->save($projectLead))
        {
            $this->Flash->success('Project Lead has been rejected.');
            return $this->redirect(array('action'=>'leads'));
            exit;
        }
        else
        {
            $this->Flash->error('Project Lead Rejection Error! Contact Administrator.');
            exit;
        }

    }

    /**
     *
     * Accept project lead
     *
     * @param : $id: Id is use to identify lead
     * @defination : Method is use to Accept the project lead
     *
     */
    function acceptlead($id = null) {
        if(!$this->request->is('post')){
            return $this->redirect(array('action'=>'leads'));
        }
        $id    = intval(decode($id));

        $projectLead = $this->ProjectLeads->get($id);
        $projectLead->status = 'accepted';

        if($this->ProjectLeads->save($projectLead))
        {
            $this->Flash->success('Project Lead has been accepted.');
            return $this->redirect(array('action'=>'leads'));
            exit;
        }
        else
        {
            $this->Flash->error('Project Lead Accept Error! Contact Administrator.');
            exit;
        }

    }

    /**
     *
     * Forword project lead
     *
     * @param : $id: Id is use to identify lead
     * @defination : Method is use to Forward the project lead
     *
     */
    function forwordlead($projectLeadId,$installerId) {
        if(!$this->request->is('post')){
            return $this->redirect(array('action'=>'leads'));
        }

        $projectLeadId = intval(decode($projectLeadId));
        $installerId = intval(decode($installerId));

        $projectLead = $this->ProjectLeads->get($projectLeadId);
        $projectLead->status = 'forwarded';

        if($this->ProjectLeads->save($projectLead))
        {
            /* Save new forwarded lead */
            $newLead = $this->ProjectLeads->newEntity();
            $newLead->project_id = $projectLead->project_id;
            $newLead->installer_id = $installerId;
            $newLead->suggested_installer = $this->Auth->user('installer_id');
            $newLead->created = $this->NOW();
            $this->ProjectLeads->save($newLead);

            $this->Flash->success('Project Lead has been forwarded.');
            return $this->redirect(array('action'=>'leads'));
            exit;
        }
        else
        {
            $this->Flash->error('Project Lead Forward Error! Contact Administrator.');
            exit;
        }
    }
     /**
     *
     * reportdata
     *
     * Behaviour : Public
     *
     * @defination : Method is used to add and edit projectnote.
     *
     */
    public function reportdata($porj_id = null)
    {
         $this->autoRander = false;
         $project_id     = intval(decode($porj_id));
         $project        = $this->CustomerProjects->findByProjectId($project_id)->contain("Projects","Customers")->first();

         $ProjectNotes = $this->ProjectNotes->find('all')
            ->where(['project_id' => $project_id])
            ->first();

        if (!empty($ProjectNotes)) {
            $projectEntity = $this->ProjectNotes->patchEntity($ProjectNotes, $this->request->data);
        } else {
            $projectEntity = $this->ProjectNotes->newEntity($this->request->data());
        }
        
        if(isset($this->request->data['reportdata'])){
            foreach($this->request->data['reportdata'] as $key=>$report_data)
            {
                if(isset($report_data['files']['tmp_name']) && !empty($report_data['files']['tmp_name']) )
                {
                  //  echo $report_data['name'];exit;
                    $path=WWW_ROOT.REPORT_DATA_PATH."report_file/".$project_id.'/';
                    if(!is_dir($path))
                    {
                        mkdir($path,0755);
                    }
                    $type_img="report_file";
                    $save= $this->imgfile_upload ($report_data['files'],$path);
                    $file_name = $save;
                    $file_save=$report_data['name'];
                    $projectEntity->$file_save = $file_name;
                   
                }
            }
        } 
        $dropdown=array(
            "electricity_bill"=>'',
            "single_line_document"=>'',
            "cable_distribution_document"=>'',
            "earthing_document"=>'',
            "pv_reports_document"=>'',
            "energy_generation_document"=>'',
            "technical_details_document"=>'',
            "prepred_for_logo"=>'',
            "prepred_by_logo1"=>'',
            "prepred_by_logo2"=>'',
            "prepred_by_logo3"=>'');

        if(!empty($this->request->data)) {
             $this->ProjectNotes->save($projectEntity);
            $this->Flash->success('Project Note has been saved.');
        }
        $this->set("proj_id",$porj_id);
        $this->set("dropdown",$dropdown);
        $this->set("project_id",$project_id);
        $this->set('ProjectNotes',$projectEntity);
        $this->set("pagetitle",'Project : <a href="/project/dashboard/'.$porj_id.'">'.$project['project']->name.'</a>');
     }
     public function genratereport($id = null)
     {
        $projectId    = intval(decode($id));
        $customerId = $this->Session->read('Customers.id');
        $project  = $this->CustomerProjects->findById($projectId)->contain("Projects","Customers")->first();
        $noteDataArr = $this->ProjectNotes->findByProjectId($projectId)->first();

        $requestData = array();
        $requestData['energy_con'] = $project['project']->estimated_kwh_year;
        $requestData['area_type'] = $project['project']->area_type;
        $requestData['area'] = $project['project']->area;
        $requestData['latitude'] = $project['project']->latitude;
        $requestData['longitude'] = $project['project']->longitude;
        $requestData['avg_monthly_bill'] = $project['project']->avg_monthly_bill;
        $requestData['backup_type'] = $project['project']->backup_type;
        $requestData['usage_hours'] = $project['project']->usage_hours;
        $requestData['project_type'] = $project['project']->project_type;

        $areaTypeArr = $this->Parameters->getAreaType();
        $custTypeArr = $this->Parameters->getProjectType();
        $resultArr = $this->Projects->getprojectestimation($requestData);
        $customerArr = $this->Customers->getCustomerByProjectid($id);
        $projectInstallers =$this->InstallerProjects->getProjectwiseInstallerList($id);
        /* Energy and Month Saving Data */
        $solarRediationData = $this->GhiData->getGhiData($project['project']->longitude,$project['project']->latitude);
        $energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationData,$project['project']->recommended_capacity,$project['project']->avg_monthly_bill,$project['project']->estimated_kwh_year);

        /* Solar PV Chart Data */
        $monthSavinData     = (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
        $monthly_saving     = array_sum($monthSavinData);
        $estimated_cost_subsidy = isset($project->estimated_cost_subsidy)?round(($project->estimated_cost_subsidy/100000),2):$project->estimated_cost;
        $payBackGraphData   = $this->Projects->GetPaybackChartData($estimated_cost_subsidy, $monthly_saving);

        $this->set(compact('noteDataArr','project','resultArr','projectInstallers','id','customerArr','areaTypeArr','custTypeArr','paybackGraphImg'));
        $this->set("pageTitle","Project: ".$project['project']->name);
        $this->set("payBackGraphData",$payBackGraphData);
        $this->set("project_id",$$project->project_id);
     }

    public function proposal($project_id = null,$id=null)
    {
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $project_id         = decode($project_id);
        $cus_id             = $this->Session->read('Customers.id');
        $customerData       = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);

        $status="error";
        $newProposal = $this->Proposal->newEntity();
        $proposal = $this->Proposal->find('all')
            ->where(['project_id' => $project_id])
            ->first();

        if (!empty($proposal)) {
            $newProposal = $this->Proposal->get($proposal['id']);
        }
        if(!empty($cus_id) && !empty($project_id) && !empty($this->request->data)) {
            if (!empty($proposal)) {
                $dataGet = $this->Proposal->get($proposal['id']);
                $newProposal = $this->Proposal->patchEntity($dataGet, $this->request->data());
                $newProposal->installer_id = $cus_id;
            } else {
                $newProposal = $this->Proposal->newEntity($this->request->data());
                $newProposal->installer_id = $cus_id;
            }
            $emailData = array();
            if (!empty($this->request->data['email'])) {
                $emailsArr = $this->request->data['email'];
                foreach ($emailsArr as $key => $value) {
                    if($value !=""){
                        $emailData[$key]= array('email'=>$value);
                    }
                }
                if (!empty($emailData)) {
                    $newProposal->email   = serialize($emailData);
                }
            }
            if (!empty($project_id)) {

                $project = $this->Projects->find('all', ['join' => [
                    'c' => [
                        'table' => 'customer_projects',
                        'type' => 'LEFT',
                        'conditions' => ['c.project_id = Projects.id']
                    ],
                    'customer' => [
                        'table' => 'customers',
                        'type' => 'LEFT',
                        'conditions' => ['customer.id = c.customer_id']
                    ],
                    'custtype' => [
                        'table' => 'parameters',
                        'type' => 'LEFT',
                        'conditions' => ['custtype.para_id = Projects.customer_type']
                    ]],
                    'fields' => array('custtype.para_value', 'customer.name', 'customer.email', 'customer.mobile', 'customer.city', 'customer.state'),
                    'conditions' => ['Projects.id' => $project_id],
                    'order' => array('Projects.id' => 'DESC')])->autoFields(true)->first();

                //$filePath = $this->genratePDFSiteSurveyreport($project_id, $project, false, 1);
                if (!empty($newProposal->email_customer)) {
                    $customerEmail = $this->CustomerProjects->findByProjectId($project_id)->contain('Customers')->first();
                    $customerEmail = (isset($customerEmail['customer']['email']) ? $customerEmail['customer']['email'] : '');
                    $cusEmail['email'] = $customerEmail;

                    array_push($emailData, $cusEmail);
                }
                if (!empty($newProposal->email_team)) {
                    $teamEmail = $this->InstallerUsers->GetInstallerUserEmail($cus_id);
                }
                $emailListArr = (!empty($teamEmail) ? array_merge($emailData, $teamEmail) : $emailData);
                if (!empty($emailListArr)) {
                    foreach ($emailListArr as $key => $value) {
                        if (!empty($value['email'])) {
                            $Email = new Email('default');
                            $Email->profile('default');
                            $Email->viewVars(array('project_detail' => $project));
                            $Email->template('send_installers_query_report', 'default')
                                ->emailFormat('html')
                                ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                                ->to($value['email'])
                                ->subject(Configure::read('EMAIL_ENV').'Survey Report')
                                ->attachments(array())
                                ->send();
                        }
                    }
                }
              
                $status = 'ok';
                $error = 'Proposal sent successfully';
                if ($this->request->data) {
                    $this->Proposal->save($newProposal);
                    $this->Flash->set('Proposal saved successfully.');
                    return $this->redirect('projects/proposal/' . encode($project_id));
                }
            }
        }
        $this->set('proposal',$newProposal);
    }
    public function commissioning($porj_id = null,$id = null)
    {
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $project_id         = intval(decode($porj_id));
        $cus_id             = $this->Session->read('Customers.id');
        $project            = $this->CustomerProjects->findByProjectId($project_id)->contain("Projects","Customers")->first();
        $customerData       = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id             = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $submit             = (isset($this->request->data['submit'])?$this->request->data['submit']:0);
        $request_status     = (isset($this->request->data['request_receive'])?$this->request->data['request_receive']:0);
        $message            = array('installer_id','project_id','project_name','location','capacity','contact_number');
        if(!empty($cus_id) && !empty($project_id)) {
            $CommData = $this->Commissioning->find('all',['conditions'=>['project_id'=>$project_id,'installer_id'=>$cus_id]])->first();
            if(empty($CommData))
            {
                $imagePatchEntity                                           = $this->Commissioning->newEntity($this->request->data);
            }
            else
            {
                 $imagePatchEntity                                           = $this->Commissioning->patchEntity($CommData,$this->request->data);
            }
            if(!empty($this->request->data)) {
                if(!empty($CommData)){

                    $imagePatchEntity->request_receive=  1;
                    $imagePatchEntity->certificate_no =(isset($this->request->data['certificate_no'])?$this->request->data['certificate_no']:0);
                    $imagePatchEntity->modified                                 = $this->NOW();
                    $mode                                                       = 'updated';
                }else{
                    $imagePatchEntity->installer_id   =  $cus_id;
                    $imagePatchEntity->project_id     =  $project_id;
                    $imagePatchEntity->request_receive=  1;
                    $imagePatchEntity->certificate_no =(isset($this->request->data['certificate_no'])?$this->request->data['certificate_no']:0);
                    $imagePatchEntity->created                                  = $this->NOW();
                    $mode                                                       = 'added';
                }

                foreach($this->request->data['document']['certificate'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.COMMISSIONING_DATA_PATH."certifictae_img/".$project_id.'/';
                             if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="certifictae_img";
                            $save= $this->imgfile_upload ($place_inv,$path);
                            $imagePatchEntity->certificate_photo = $save;

                        }
                    }
                 $this->Commissioning->save($imagePatchEntity);
                  $this->set("data",$imagePatchEntity);
                  $comm_id=$imagePatchEntity->id;
                 foreach($this->request->data['document']['image_type'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.COMMISSIONING_DATA_PATH."commissioning_img/".$project_id.'/';
                             if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="commissioning_img";
                            $save= $this->imgfile_upload ($place_inv,$path);
                            
                            $this->upload_commissioning_image($save,$type_img,$comm_id);

                        }
                    }
                    
                
               
               
                $this->Flash->success("Commissioning has been ".$mode." successfully.");
                $this->redirect('project/commissioning/'.encode($project_id));

           } else {
                $message['installer_id']    = $cus_id;
                $message['project_id']      = $project_id;
                $message['project_name']    = $project['project']['name'];
                $message['location']        = $project['project']['address'];
                $message['capacity']        = $project['project']['recommended_capacity'];
                $message['contact_number']  = (isset($project['customers']['mobile'])?$project['customers']['mobile']:0);    
            }
            $data_capacity = $this->Workorder->find('all')
                            ->where(['project_id' => $project_id])
                             ->first();
            $capacity='';
            if(!empty($data_capacity))
            {
                    $capacity= $data_capacity->Capacity;
            }
            $data_number = $this->SiteSurveys->find('all')
                            ->where(['project_id' => $project_id])
                             ->first();
            $mobile_num='';
            if(!empty($data_number))
            {
                    $mobile_num= $data_number->mobile;
            }

            $result_photo_data=array();
            $result_photo_data = $this->Commissioning->find('all', ['join' => [
                    'c' => [
                        'table' => 'project_commissioning_photo',
                        'type' => 'LEFT',
                        'conditions' => ['c.commissioning_id = Commissioning.id']
                    ]],
                    'fields' => array('c.photo','c.type','c.id','certificate_photo'),
                    'conditions' => ['Commissioning.project_id' => $project_id]
                ])->toArray();
              
            $this->set("imagePatchEntity",$imagePatchEntity);
            $this->set("all_photo_data",$result_photo_data);
            $this->set("data",$message);
            $this->set('project_id',$project_id);
            $this->set('capacity',$capacity);
            $this->set('mobile_num',$mobile_num);

        }
    }
    private function _generateProjectSurveySearchCondition($id)
    {
        $arrCondition	= array();
        $blnSinCompany	= true;
        $this->request->data['SiteSurveys']['id'] = $id;
        if(isset($this->request->data) && count($this->request->data)>0)
        {
            if(isset($this->request->data['SiteSurveys']['id']) && trim($this->request->data['SiteSurveys']['id'])!='') {
                $arrCondition['SiteSurveys.project_id'] = $this->request->data['SiteSurveys']['id'];

            }
        }
        return $arrCondition;
    }
    public function sitesurveylist($porj_id = null)
    {

        $project_id     = intval(decode($porj_id));
        $project        = $this->CustomerProjects->findByProjectId($project_id)->contain("Projects","Customers")->first();

        $customerId     = $this->Session->read('Customers.id');
        if(empty($customerId)) {
            return $this->redirect(array('controller'=>'users','action'=>'index'));
        }
        $arrAdminuserList	= array();
        $arrUserType		= array();
        $arrCondition		= array();
        $this->SortBy		= "SiteSurveys.id";
        $this->Direction	= "ASC";
        $this->intLimit		= PAGE_RECORD_LIMIT;
        $this->CurrentPage  = 1;
        $option=array();

        $option['colName']  = array('id','building_name','contact_name','designation','address1','address2','address3','mobile','surveyer_name', 'action');
        $sortArr=array();
        $this->SetSortingVars('SiteSurveys',$option,$sortArr);
        $arrCondition		= $this->_generateProjectSurveySearchCondition($project_id);
        $query_data=$this->SiteSurveys->find('all',array(
            'fields'=>array('SiteSurveys.id','SiteSurveys.building_name','SiteSurveys.contact_name','SiteSurveys.designation', 'SiteSurveys.address1','SiteSurveys.address2','SiteSurveys.address3','SiteSurveys.mobile', 'SiteSurveys.surveyer_name'),
            'conditions' => $arrCondition,
            'order'=>array($this->SortBy=>$this->Direction),
            'page'=> $this->CurrentPage,
            'limit' => $this->intLimit));

        $start_page=isset($this->request->data['start']) ? $this->request->data['start'] : 1;
        $this->paginate['limit'] = PAGE_RECORD_LIMIT;
        $this->paginate['page'] = ($start_page/$this->paginate['limit'])+1;
        $arrAdminuserList	= $this->paginate($query_data);

        $usertypes = array();
        $option['dt_selector']	='table-example';
        $option['formId']		='formmain';
        $option['url']			= '';

        $total_survey=$this->SiteSurveys->find('all',array('conditions'=>array('project_id'=>$project_id)))->toArray();
        $JqdTablescr = $this->JqdTable->create($option);
        $this->set('arrAdminuserList',$arrAdminuserList->toArray());
        $this->set('JqdTablescr',$JqdTablescr);
        $this->set('period',$this->period);
        $this->set('limit',$this->intLimit);
        $this->set("CurrentPage",$this->CurrentPage);
        $this->set("SortBy",$this->SortBy);
        $this->set("Direction",$this->Direction);
        $this->set("proj_id",$porj_id);
       // $this->set("pagetitle","Project: ".$project['project']->name);
        $this->set("pagetitle",'Project : <a href="/project/dashboard/'.$porj_id.'">'.$project['project']->name.'</a>');
        $this->set("page_count",(isset($this->request->params['paging']['ProjectSurvey']['pageCount'])?$this->request->params['paging']['ProjectSurvey']['pageCount']:0));
        $this->set("total_survey",count($total_survey));
        $out=array();

        foreach($arrAdminuserList->toArray() as $key=>$val) {
            $temparr=array();
            foreach($option['colName'] as $key) {
                if(isset($val[$key])){
                    $temparr[$key]=$val[$key];
                }
                if($key=='action') {
                    $temparr['action']='';
                    $temparr['action'].='<a href="'.constant('WEB_URL').'projects/viewsurveyreport/'.encode($val['id']).'"><i class="fa fa-download" id=""> </i></a>';
                    //edit button of sitesurveylist
                    $temparr['action'].='<a href="#" class="showModel" data-id="'.encode($val['id']).'" data-title="Site Survey" data-url="'.URL_HTTP.'projects/sitesurvey/'.encode($project->project_id).'/'.encode($val['id']).' "><i class="fa fa-edit" style="margin-left: 20px;"> </i></a>';
                }
            }
            $out[]=$temparr;
        }

        if ($this->request->is('ajax'))
        {
            header('Content-type: application/json');
            echo json_encode(array('condi'=>$arrCondition,"draw" => intval($this->request->data['draw']),
                "recordsTotal"    => intval( $this->request->params['paging']['SiteSurveys']['count']),
                "recordsFiltered" => intval( $this->request->params['paging']['SiteSurveys']['count']),
                "data"            => $out));
            die;
        }
    }
    public function execution($porj_id = null)
    {
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $cus_id             = $this->Session->read('Customers.id');
        $customerId         = $this->Session->read('Customers.id');
        $readOnlyBiDate     = '0';
        $readOnlyMeter      = '0';
        $readOnlySolar      = '0';
        $project_id         = intval(decode($porj_id));
        $project_data       = $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
        $app_details        = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
        $inscommData           = $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$project_id)))->first();
        if(empty($inscommData))
        {
            $arr_project_data['proj_name']              = $project_data->name;
            $arr_project_data['latitude']               = $project_data->latitude;
            $arr_project_data['longitude']              = $project_data->longitude;
            $arr_project_data['customer_type']          = $project_data->customer_type;
            $arr_project_data['project_type']           = $project_data->customer_type;
            $arr_project_data['area']                   = $project_data->area;
            $arr_project_data['area_type']              = $project_data->area_type;
            $arr_project_data['bill']                   = $project_data->avg_monthly_bill;
            $arr_project_data['avg_monthly_bill']       = $project_data->avg_monthly_bill;
            $arr_project_data['backup_type']            = $project_data->backup_type;
            $arr_project_data['usage_hours']            = $project_data->usage_hours;
            $arr_project_data['energy_con']             = $project_data->estimated_kwh_year;
            $arr_project_data['recommended_capacity']   = $app_details->pv_capacity;
            $arr_project_data['address']                = $project_data->address;
            $arr_project_data['city']                   = $project_data->city;
            $arr_project_data['state']                  = $project_data->state;
            $arr_project_data['state_short_name']       = $project_data->state_short_name;
            $arr_project_data['country']                = $project_data->country;
            $arr_project_data['postal_code']            = $project_data->pincode;
            $arr_project_data['Projects']['id']         = $project_data->id;
            $result                                     = $this->Projects->getprojectestimationV2($arr_project_data,$app_details->customer_id,true);
        }
        else
        {
            $m_data     = isset($inscommData->modules_data) ? unserialize($inscommData->modules_data) : '';
            $i_data     = isset($inscommData->inverter_data) ? unserialize($inscommData->inverter_data) : '';
            $total_commulative= 0;
            for($i=1;$i<=3;$i++)
            {
                $row            = $i-1;
                $m_capacity     = '';
                $m_make         = '';
                $m_modules      = '';
                $m_type_modules = '';
                $m_type_other   = '';
                if (isset($m_data[$row])) 
                {
                    $m_capacity         = $m_data[$row]['m_capacity'];
                    $m_make             = $m_data[$row]['m_make'];
                    $m_modules          = $m_data[$row]['m_modules'];
                    $m_type_modules     = $m_data[$row]['m_type_modules'];
                    $m_type_other       = $m_data[$row]['m_type_other'];
                    $total_commulative  = $total_commulative + (floatval($m_data[$row]['m_capacity']) * floatval($m_data[$row]['m_modules']));
                }
            }
            if ($total_commulative > 0) 
            {
                $total_commulative  = round(($total_commulative/1000),3);
            }
            $total_commulative_i  = 0;
            for($i=1;$i<=3;$i++)
            {
                $row                  = $i-1;
                $i_capacity           = '';
                $i_make               = '';
                $i_make_other         = '';
                $i_modules            = '';
                $i_type_modules       = '';
                $i_type_other         = '';
                $i_phase              = '';
                if (isset($i_data[$row])) 
                {
                    $i_capacity         = $i_data[$row]['i_capacity'];
                    $i_make             = $i_data[$row]['i_make'];
                    $i_make_other       = $i_data[$row]['i_make_other'];
                    $i_modules          = $i_data[$row]['i_modules'];
                    $i_type_modules     = $i_data[$row]['i_type_modules'];
                    $i_type_other       = $i_data[$row]['i_type_other'];
                    if(isset($i_data[$row]['i_phase']))
                    {
                      $i_phase          = $i_data[$row]['i_phase'];
                    }
                    $total_commulative_i= $total_commulative_i + (floatval($i_data[$row]['i_capacity'])*floatval($i_data[$row]['i_modules']));
                }
            }
            if ($total_commulative_i > 0) 
            {
                $total_commulative_i  = round(($total_commulative_i),3);
            }
            $min_cap = min($total_commulative,$total_commulative_i,$app_details->pv_capacity);
            $arr_project_data['proj_name']              = $project_data->name;
            $arr_project_data['latitude']               = $project_data->latitude;
            $arr_project_data['longitude']              = $project_data->longitude;
            $arr_project_data['customer_type']          = $project_data->customer_type;
            $arr_project_data['project_type']           = $project_data->customer_type;
            $arr_project_data['area']                   = $project_data->area;
            $arr_project_data['area_type']              = $project_data->area_type;
            $arr_project_data['bill']                   = $project_data->avg_monthly_bill;
            $arr_project_data['avg_monthly_bill']       = $project_data->avg_monthly_bill;
            $arr_project_data['backup_type']            = $project_data->backup_type;
            $arr_project_data['usage_hours']            = $project_data->usage_hours;
            $arr_project_data['energy_con']             = $project_data->estimated_kwh_year;
            $arr_project_data['recommended_capacity']   = $min_cap;
            $arr_project_data['address']                = $project_data->address;
            $arr_project_data['city']                   = $project_data->city;
            $arr_project_data['state']                  = $project_data->state;
            $arr_project_data['state_short_name']       = $project_data->state_short_name;
            $arr_project_data['country']                = $project_data->country;
            $arr_project_data['postal_code']            = $project_data->pincode;
            $arr_project_data['Projects']['id']         = $project_data->id;
            $result                                     = $this->Projects->getprojectestimationV2($arr_project_data,$app_details->customer_id,true);
            //$this->CreateMyProject($app_details->id,true,$min_cap);
        }
        $modules_data       = '';
        $inverter_data      = '';
        $total_commulative  = 0;
        $total_commulative_i= 0;
        $project            = $this->CustomerProjects->findByProjectId($project_id)->contain("Projects","Customers")->first();
        $member_id          = $this->Session->read("Members.id");
        $member_area        = $this->Session->read("Members.area");
        $is_member          = false;
        if(!empty($member_id)){
            $is_member      = true;
        }
        if($is_member==true)
        {
            $app_details        = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
            $cus_id             = (isset($app_details->installer_id)?$app_details->installer_id:0);
        }
        else
        {
            $customerData       = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
            $cus_id             = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        }

        if(!empty($cus_id)) {
            $commData           = $this->Installation->find('all',array('conditions'=>array('Installation.project_id'=>$project_id)))->first();

            $can_start_work     = false;
            $fesibility_flag    = false;
            $application_data   = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
            if(!empty($application_data))
            {
                $all_stage                  = $this->ApplyOnlineApprovals->Approvalstage($application_data->id);
                if(in_array($this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,$all_stage))
                {
                    $fesibility_flag        = true;
                    $FesibilityData         = $this->FesibilityReport->getReportData($application_data->id);
                    if($FesibilityData->payment_approve==1)
                    {
                        $can_start_work     = true;
                    }
                }
                else
                {
                    $fesibility_flag        = false;
                }
            }

            if(!empty($commData)) {
                
                $result['project_id']       =   $project['project']['id'];
                $result['project_name']     =   $project['project']['name'];
                $result['location']         =   $project['project']['address'];
                $result['capacity']         =   $project['project']['recommended_capacity'];
                $result['contact']          =   isset($project['customers']['mobile']) ? $project['customers']['mobile'] : '';
                $result['installer_id']     =   $cus_id;
                $result['start_date']       =   '';
                $result['end_date']         =   '';
                $result['bi_date']         =   '';
                if($commData['start_date']!='0000-00-00' && $commData['start_date']!=null)
                {
                    $result['start_date']   =   $commData['start_date']->format('d-m-Y');
                }
                if($commData['end_date']!='0000-00-00' && $commData['end_date']!=null)
                {
                    $result['end_date']     =   $commData['end_date']->format('d-m-Y');
                }
                if($commData['bi_date']!='0000-00-00' && $commData['bi_date']!=null)
                {
                    $result['bi_date']      =   $commData['bi_date']->format('d-m-Y');
                }
                $result['modules_data']     = unserialize($commData['modules_data']);
                $result['inverter_data']    = unserialize($commData['inverter_data']);
            } else if(!empty($project)) {
                $result['project_name']     =   $project['project']['name'];
                $result['location']         =   $project['project']['address'];
                $result['capacity']         =   $project['project']['recommended_capacity'];
                $result['contact']          =   isset($project['Customers']['mobile']) ? $project['Customers']['mobile'] : '';
                $result['installer_id']     =   $cus_id;
                $result['start_date']       =   '';
                $result['end_date']         =   '';
                $result['bi_date']         =   '';
                $result['modules_data']     =   '';
                $result['inverter_data']    =   '';
                
            }
            if(isset($this->request->data) && !empty($this->request->data))
            {
                $this->Installation->data                   = $this->request->data;
            }
            else
            {
                $this->Installation->data['start_date']     = '';
                $this->Installation->data['end_date']       = '';
                $this->Installation->data['bi_date']       = '';
                $this->Installation->data['m_capacity']     = array();
                $this->Installation->data['i_capacity']     = array();
                
            }
            $this->Installation->data['cumulative_module']  = isset($this->request->data['cumulative_module']) ? $this->request->data['cumulative_module'] : 0;
            $this->Installation->data['cumulative_inverter']= isset($this->request->data['cumulative_inverter']) ? $this->request->data['cumulative_inverter'] : 0;
            $this->Installation->data['approved_capacity']  = $app_details->pv_capacity;
            $this->Installation->data['applicationCategory']= $app_details->category;

            if(empty($commData)) {
                    $commEntity             = $this->Installation->newEntity($this->request->data(),['validate'=>'tab']);
                }
             else {
                    $instData               = $this->Installation->get($commData['id']);

                    $commEntity             = $this->Installation->patchEntity($instData,$this->request->data(),['validate'=>'tab']);
                }
            if(isset($this->request->data) && !empty($this->request->data) && !$commEntity->errors() ){
                if(empty($commData)) {
                    $commEntity->created    = $this->NOW(); 
                    $mode                   = 'added';
                } else {
                    $commEntity->modified   = $this->NOW();  
                    $mode                   = 'edited';
                }
                if (empty($this->request->data['m_capacity'])) {
                    $commEntity->m_capacity = 0;
                }
                if (empty($this->request->data['m_modules'])) {
                    $commEntity->m_modules = 0;
                }
                if (empty($this->request->data['i_capacity'])) {
                    $commEntity->i_capacity = 0;
                }
                if (empty($this->request->data['i_modules'])) {
                    $commEntity->i_modules = 0;
                }


                $startdate  = (isset($this->request->data['start_date'])?$this->request->data['start_date']:$this->NOW());
                $enddate    = (isset($this->request->data['end_date'])?$this->request->data['end_date']:$this->NOW());
                $bidate     = (isset($this->request->data['bi_date'])?$this->request->data['bi_date']:'0000-00-00');
                $commEntity->installer_id   = $cus_id;
                $commEntity->start_date     = date('Y-m-d',strtotime($startdate)); 
                $commEntity->end_date       = date('Y-m-d',strtotime($enddate)); 
                $commEntity->bi_date        = !empty($this->request->data['bi_date'])?date('Y-m-d',strtotime($bidate)):'0000-00-00'; 
                $arr_modules                = array();
                $arr_work_modules           = array();
                foreach($this->request->data['m_capacity'] as $key=>$val)
                {
                    $arr_modules[$key]['m_capacity']        = $val;
                    $arr_modules[$key]['m_make']            = $this->request->data['m_make'][$key];
                    $arr_modules[$key]['m_modules']         = $this->request->data['m_modules'][$key];
                    $arr_modules[$key]['m_type_modules']    = $this->request->data['m_type_modules'][$key];
                    $arr_modules[$key]['m_type_other']      = $this->request->data['m_type_other'][$key];
                    $arr_work_modules[$key][0]              = $val;
                    $arr_work_modules[$key][1]              = $this->request->data['m_modules'][$key];
                    $arr_work_modules[$key][2]              = $this->request->data['m_type_modules'][$key];
                }
                $arr_inverters      = array();
                $arr_inv_modules    = array();
                foreach($this->request->data['i_capacity'] as $key=>$val)
                {
                    $arr_inverters[$key]['i_capacity']      = $val;
                    $arr_inverters[$key]['i_make']          = $this->request->data['i_make'][$key];
                    $arr_inverters[$key]['i_make_other']    = $this->request->data['i_make_other'][$key];
                    $arr_inverters[$key]['i_modules']       = $this->request->data['i_modules'][$key];
                    $arr_inverters[$key]['i_type_modules']  = $this->request->data['i_type_modules'][$key];
                    $arr_inverters[$key]['i_type_other']    = $this->request->data['i_type_other'][$key];
                    $arr_inverters[$key]['i_phase']         = $this->request->data['i_phase'][$key];
                    $arr_inv_modules[$key][0]               = $val;
                    $arr_inv_modules[$key][1]               = $this->request->data['i_modules'][$key];
                    $arr_inv_modules[$key][2]               = $this->request->data['i_type_modules'][$key];
                    $arr_inv_modules[$key][3]               = $this->request->data['i_make'][$key];    
                }
                $commEntity->modules_data   = serialize($arr_modules);
                $commEntity->inverter_data  = serialize($arr_inverters);
                if($this->Installation->save($commEntity))
                {
                    $comm_id=$commEntity->id;
                    //,$project_data->recommended_capacity
                    $capacity_updated = min($this->request->data['cumulative_module'],$this->request->data['cumulative_inverter'],$app_details->pv_capacity);
                    
                    $this->CreateMyProject($application_data->id,true,$capacity_updated);
                }
                foreach($this->request->data['document']['modules_img'] as $key=>$place_inv)
                {
                    if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                    {
                        $name=$place_inv['name'];
                        $path=WWW_ROOT.EXECUTION_PATH.$project_id.'/'.'modules'.'/';
                         if(!file_exists(EXECUTION_PATH.$project_id.'/'.'modules')){
                            @mkdir(EXECUTION_PATH.$project_id.'/'.'modules', 0777,true);
                        }
                        $type_img="modules";
                        $save= $this->imgfile_upload ($place_inv,$path,'',$customerId,'modules');
                        $this->upload_execution_image($save,$type_img,$comm_id);
                       
                    }
                }
                foreach($this->request->data['document']['inverter_img'] as $key=>$place_inv)
                {

                    if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                    {
                        $name=$place_inv['name'];
                        $path=WWW_ROOT.EXECUTION_PATH.$project_id.'/'.'inverters'.'/';
                         if(!file_exists(EXECUTION_PATH.$project_id.'/'.'inverters')){
                            @mkdir(EXECUTION_PATH.$project_id.'/'.'inverters', 0777,true);
                        }
                        $type_img="inverters";
                        $save= $this->imgfile_upload ($place_inv,$path,'',$customerId,'inverters');
                       $this->upload_execution_image($save,$type_img,$comm_id);
                        
                    }
                }
                foreach($this->request->data['document']['other_img'] as $key=>$place_inv)
                {

                    if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                    {
                        $name=$place_inv['name'];
                        $path=WWW_ROOT.EXECUTION_PATH.$project_id.'/'.'others'.'/';
                         if(!file_exists(EXECUTION_PATH.$project_id.'/'.'others')){
                            @mkdir(EXECUTION_PATH.$project_id.'/'.'others', 0777,true);
                        }
                        $type_img="others";
                        $save= $this->imgfile_upload ($place_inv,$path,'',$customerId,'others');
                       $this->upload_execution_image($save,$type_img,$comm_id);
                        
                    }
                }
                $arr_apply                              = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
                if(!empty($arr_apply))
                {
                    $WorkCompletion                     = $this->WorkCompletion->getReportData($arr_apply->id);
                    if(!empty($WorkCompletion))
                    {
                        $WorkCompletion                 = $this->WorkCompletion->patchEntity($WorkCompletion,$this->request->data,['validate'=>'Add']);
                    }
                    else
                    {
                        $WorkCompletion                 = $this->WorkCompletion->newEntity($this->request->data,['validate'=>'Add']);
                    }
                    $WorkCompletion->application_id     = $arr_apply->id;
                    $WorkCompletion->created            = $this->NOW();
                    $WorkCompletion->created_by         = $this->Session->read('Customers.id');
                    $WorkCompletion->techspec           = serialize($arr_work_modules);
                    $WorkCompletion->invertors          = serialize($arr_inv_modules);
                    $WorkCompletion->modified           = $this->NOW();
                    $WorkCompletion->modified_by        = $this->Session->read('Customers.id');
                    $this->WorkCompletion->save($WorkCompletion);
                }
                $application_data                       =  $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
                if(!empty($application_data))
                {
                    if ($this->ApplyOnlineApprovals->can_workstart($application_data->application_status)) 
                    {
                        $NextStatus                         = $this->ApplyOnlineApprovals->WORK_EXECUTED;
                        $customer_id                        = $this->Session->read('Customers.id');
                        $applyOnlinesData                   = $this->ApplyOnlines->viewApplication($application_data->id);
                        if ($this->ApplyOnlineApprovals->validateNewStatus($NextStatus,$applyOnlinesData->application_status)) 
                        {
                                $arrData = array("application_status"=>$NextStatus);
                                $this->ApplyOnlines->updateAll($arrData,['id' => $application_data->id]);
                       
                            $INSTATTER_NAME     = $applyOnlinesData->installer['installer_name'];
                            $CUSTOMER_NAME      = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
                            $EmailVars          = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,
                                                "INSTATTER_NAME"=>$INSTATTER_NAME,
                                                "APPLCATION_NUMBER"=>$applyOnlinesData->geda_application_no);
                            $template_include   = 'work_executed';
                            $subject            = "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Work Executed";
                            $sms_text =str_replace(array('##geda_application_no##','##installer_name##'),array($applyOnlinesData->geda_application_no,$applyOnlinesData->installer['installer_name']),WORK_EXECUTION);
                            if(!empty($applyOnlinesData->consumer_mobile))
                            {
                                $this->ApplyOnlines->sendSMS($applyOnlinesData->id,$applyOnlinesData->consumer_mobile,$sms_text,'WORK_EXECUTION');
                            }
                            if(!empty($applyOnlinesData->installer_mobile))
                            {
                                //$this->ApplyOnlines->sendSMS($applyOnlinesData->id,$applyOnlinesData->installer_mobile,$sms_text);
                            }
                            $email      = new Email('default');
                            $email->profile('default');
                            $email->viewVars($EmailVars);
                            $message_send = $email->template($template_include, 'default')
                                    ->emailFormat('html')
                                    ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                                    ->to(trim($applyOnlinesData->installer_email))
                                    ->subject(Configure::read('EMAIL_ENV').$subject)
                                    ->send();
                            $to     = $applyOnlinesData->consumer_email;
                            if(empty($to))
                            {
                                $to = $applyOnlinesData->email;
                            }
                           if(!empty($to))
                            {
                                $email          = new Email('default');
                                $email->profile('default');
                                $email->viewVars($EmailVars);
                                $message_send   = $email->template($template_include, 'default')
                                    ->emailFormat('html')
                                    ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                                    ->to(trim($to))
                                    ->subject(Configure::read('EMAIL_ENV').$subject)
                                    ->send();
                            } 
                        }
                    $this->ApplyOnlineApprovals->saveStatus($application_data->id,$NextStatus,$customer_id,'');
                    }
                }
                $this->Flash->success("Execution has been ".$mode." successfully.");
                $this->redirect('project/execution/'.encode($project_id)); 
            }
            else
            {
                $ChargingCertificate_data   = $this->ChargingCertificate->find('all',array('conditions'=>array('application_id'=>$application_data->id)))->first();
                if(!empty($ChargingCertificate_data))
                {
                    if(!empty($ChargingCertificate_data->meter_installed_date) && $ChargingCertificate_data->meter_installed_date!='0000-00-00')
                    {
                        $readOnlyBiDate                     = '1';
                        $commEntity->bi_date                = $ChargingCertificate_data->meter_installed_date->format('d-m-Y');
                        $result['bi_date']                 = $ChargingCertificate_data->meter_installed_date->format('d-m-Y');
                        
                    }
                    if(!empty($ChargingCertificate_data->solar_meter))
                    {
                        $readOnlySolar                      = '1';
                        $commEntity->solar_meter_serial_no  = $ChargingCertificate_data->solar_meter;
                    }
                    if(!empty($ChargingCertificate_data->bi_directional_meter))
                    {
                        $readOnlyMeter                      = '1';
                        $commEntity->meter_serial_no        = $ChargingCertificate_data->bi_directional_meter;
                    }
                } 
            }
            $data_capacity = $this->Workorder->find('all')
                            ->where(['project_id' => $project_id])
                             ->first();

            $capacity= $app_details->pv_capacity;
            /*$capacity='';if(!empty($data_capacity))
            {
                    //$capacity= $data_capacity->Capacity;
                    
            }*/
            $data_number = $this->SiteSurveys->find('all')
                            ->where(['project_id' => $project_id])
                             ->first();
            $mobile_num='';
            if(!empty($data_number))
            {
                    $mobile_num= $data_number->mobile;
            }
            $applyOnlinesData = $this->ApplyOnlines->find('all')
                            ->where(['project_id' => $project_id])
                             ->first();
            $aadhar_no='';
            if(!empty($applyOnlinesData))
            {
                    $aadhar_no= passdecrypt($applyOnlinesData->aadhar_no_or_pan_card_no);
            }
            if(empty($commEntity->latitude))
            {
                $commEntity->latitude= $project['project']['latitude'];
            }
            if(empty($commEntity->longitude))
            {
                $commEntity->longitude= $project['project']['longitude'];
            }

            $type_modules            = $this->Installation->TYPE_MODULES ;
            $type_inverters          = $this->Installation->TYPE_INVERTERS ;
            $make_inverters          = $this->Installation->MAKE_INVERTERS ;
            $inv_phase               = $this->Installation->INV_PHASE_TYPE ;

            unset($type_modules['']); 
            unset($type_inverters['']); 
            unset($make_inverters['']); 
            $result_photo_data=array();

            $result_photo_data = $this->Installation->find('all', ['join' => [
                    'c' => [
                        'table' => ' project_installation_photos',
                        'type' => 'LEFT',
                        'conditions' => ['c.project_installation_id  = Installation.id']
                    ]],
                    'fields' => array('c.photo','c.type','c.id'),
                    'conditions' => ['Installation.project_id' => $project_id]
            ])->toArray();
           $capitalCost    = $this->Projects->calculatecapitalcost($project['project']['recommended_capacity'],$project['project']['state'],$project['project']['customer_type']);
            $calculate_subsidy = $project['project']['estimated_cost'];
            if(!empty($applyOnlinesData) && $applyOnlinesData->disclaimer_subsidy==1 && SHOW_SUBSIDY_EXECUTION==1)
            {
                $calculate_subsidy  = 0;
            }

            $subsidy_data = $this->Projects->calculatecapitalcostwithsubsidy($project['project']['recommended_capacity'],$calculate_subsidy,$project['project']['state'],$project['project']['customer_type'],true,$applyOnlinesData->social_consumer);
            
            if (isset($subsidy_data['state_subcidy_type']) && $subsidy_data['state_subcidy_type'] == 0) {
                $STATE_SUBSIDY          = $subsidy_data['state_subsidy']."%";
                $STATE_SUBSIDY_AMOUNT   = ($subsidy_data['state_subsidy_amount'] > 0)?$this->get_money_indian_format($subsidy_data['state_subsidy_amount']):"-";
            } else {
                $STATE_SUBSIDY          = (isset($subsidy_data['state_subsidy']) && ($subsidy_data['state_subsidy'] > 0))?$this->get_money_indian_format($subsidy_data['state_subsidy']):"-";
                $STATE_SUBSIDY_AMOUNT   = (isset($subsidy_data['state_subsidy_amount']) && ($subsidy_data['state_subsidy_amount'] > 0))?$this->get_money_indian_format($subsidy_data['state_subsidy_amount']):"-";
            }
            if($applyOnlinesData->social_consumer==1 || $applyOnlinesData->common_meter==1)
            {
                $subsidy_data['state_subsidy_amount']   = 0;
                $STATE_SUBSIDY                          = 0;
                $STATE_SUBSIDY_AMOUNT                   = '-';
            }
            if (isset($subsidy_data['central_subcidy_type']) && $subsidy_data['central_subcidy_type'] == 0) {
                $CENTRAL_SUBSIDY            = $subsidy_data['central_subsidy']."%";
                $CENTRAL_SUBSIDY_AMOUNT     = ($subsidy_data['central_subsidy_amount'] > 0)?$this->get_money_indian_format($subsidy_data['central_subsidy_amount']):"-";
            } else {
                $CENTRAL_SUBSIDY            = (isset($subsidy_data['central_subsidy']) && ($subsidy_data['central_subsidy'] > 0))?$this->get_money_indian_format($subsidy_data['central_subsidy']):"-";
                $CENTRAL_SUBSIDY_AMOUNT     = (isset($subsidy_data['central_subsidy_amount']) && ($subsidy_data['central_subsidy_amount'] > 0))?$this->get_money_indian_format($subsidy_data['central_subsidy_amount']):"-";
            } 

            $this->set("all_photo_data",$result_photo_data);
            $this->set("type_modules",$type_modules);
            $this->set("type_inverters",$type_inverters);
            $this->set("make_inverters",$make_inverters);
            $this->set("commEntity",$commEntity);
            $this->set("data",$result);
            $this->set("project_id",$project_id);
            $this->set("capacity",$capacity);
            $this->set("mobile_num",$mobile_num);
            $this->set("aadhar_no",$aadhar_no);
            $this->set('WorkorderErrors',$commEntity->errors());
            $this->set('can_start_work',$can_start_work);
            $this->set('fesibility_flag',$fesibility_flag);
            $this->set('applyOnlinesData',$applyOnlinesData);
            $this->set('projectData',$project);
            $this->set('subsidyData',$subsidy_data);
            $this->set('MNRE_subsidy_amount',$CENTRAL_SUBSIDY_AMOUNT);
            $this->set('state_subsidy_amount',$STATE_SUBSIDY_AMOUNT);
            $this->set('inv_phase',$inv_phase);
            $this->set('is_member',$is_member);
            $this->set('readOnlyBiDate',$readOnlyBiDate);
            $this->set('readOnlyMeter',$readOnlyMeter);
            $this->set('readOnlySolar',$readOnlySolar);
            $this->set('Couchdb',$this->Couchdb);
            $this->set('member_area',$member_area);
        }
    }
    public function workorder($porj_id = null)
    {
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $commData           = array();
        $member_id          = $this->Session->read("Members.id");
        $member_area        = $this->Session->read("Members.area");
        $project_id         = intval(decode($porj_id));
        $is_member          = false;
        if(!empty($member_id)){
            $is_member      = true;
        }
        if($is_member==true)
        {
            $app_details        = $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
            $cus_id             = (isset($app_details->installer_id)?$app_details->installer_id:0);
        }
        else
        {
            $cus_id             = $this->Session->read('Customers.id');
            $customerData       = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
            $cus_id             = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        }  
        $project            = $this->CustomerProjects->findByProjectId($project_id)->contain("Projects","Customers")->first();
        $workorder_number   = (isset($this->request->data['workorder_number'])?$this->request->data['workorder_number']:0);
        $can_start_work     = false;
        $fesibility_flag    = false;

        if(!empty($cus_id) && !empty($project_id)) {
            $application_data                   =  $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
            if(!empty($application_data))
            {
                $all_stage                  = $this->ApplyOnlineApprovals->Approvalstage($application_data->id);
                if(in_array($this->ApplyOnlineApprovals->FIELD_REPORT_SUBMITTED,$all_stage))
                {
                    $fesibility_flag        = true;
                    $FesibilityData         = $this->FesibilityReport->getReportData($application_data->id);
                    if($FesibilityData->payment_approve==1)
                    {
                        $can_start_work     = true;
                    }
                }
                else
                {
                    $fesibility_flag        = false;
                }
            }
            $project_data = $this->Projects->find('all',array('conditions'=>array('id'=>$project_id)))->first();
            $this->Workorder->data                          = $this->request->data;
            $this->Workorder->data['recommended_capacity']  = $project_data->recommended_capacity;
            if(empty($this->request->data))
            {
                $this->Workorder->data['Capacity']          = '0';
            }         
            $commData   = $this->Workorder->find('all',array("conditions"=>array('Workorder.project_id'=>$project_id,'Workorder.installer_id'=>$cus_id)))->first();
            $date       = (isset($this->request->data['work_date'])?$this->request->data['work_date']:date('Y-m-d'));
            if(!empty($commData)) {
                    $saveData                   = $this->Workorder->get($commData['id']);
                    $commEntity                 = $this->Workorder->patchEntity($saveData,$this->request->data(),['validate'=>'tab']);
                    $mode                       = 'updated';
            } else {
                $commEntity                     = $this->Workorder->newEntity($this->request->data(),['validate'=>'tab']);
                $mode                           = 'added';
            }
            if(!empty($this->request->data) && !$commEntity->errors() ) {
            if(!empty($commData)) {
                $commEntity->modified           = $this->NOW(); 
            } else {
                $commEntity->created            = $this->NOW(); 
            }
            $commEntity->project_id             = $project_id;
            $commEntity->installer_id           = $cus_id;
            $commEntity->workorder_number       = $workorder_number;
            $commEntity->workorder_date         = date('Y-m-d',strtotime($date)); 
            if(isset($this->request->data['workorder_image']['tmp_name']) && !empty($this->request->data['workorder_image']['tmp_name']))
            {
                $name=$this->request->data['workorder_image']['name'];
                $path=WWW_ROOT.WORKORDER_PATH.$project_id.'/';
                 if(!file_exists(WORKORDER_PATH.$project_id)){
                    @mkdir(WORKORDER_PATH.$project_id, 0777,true);
                }
                $file_name                      = $this->imgfile_upload ($this->request->data['workorder_image'],$path,'',$this->Session->read('Customers.id'),'workorder_data');
                $commEntity->attached_doc       = $file_name;
               
            }
            $this->Workorder->save($commEntity); 
            $application_data                   =  $this->ApplyOnlines->find('all',array('conditions'=>array('project_id'=>$project_id)))->first();
            if(!empty($application_data))
            {
                if ($this->ApplyOnlineApprovals->can_workstart($application_data->application_status)) {
                    $NextStatus                 = $this->ApplyOnlineApprovals->WORK_STARTS;
                    $customer_id                = $this->Session->read('Customers.id');
                    $applyOnlinesData           = $this->ApplyOnlines->viewApplication($application_data->id);
                    if ($this->ApplyOnlineApprovals->validateNewStatus($NextStatus,$applyOnlinesData->application_status)) {
                        $arrData = array("application_status"=>$NextStatus);
                        $this->ApplyOnlines->updateAll($arrData,['id' => $application_data->id]);

                        $INSTATTER_NAME         = $applyOnlinesData->installer['installer_name'];
                        $CUSTOMER_NAME          = trim($applyOnlinesData->customer_name_prefixed." ".$applyOnlinesData->name_of_consumer_applicant." ".$applyOnlinesData->last_name." ".$applyOnlinesData->third_name);
                        $EmailVars              = array("CUSTOMER_NAME"=>$CUSTOMER_NAME,
                                                    "INSTATTER_NAME"=>$INSTATTER_NAME,
                                                    "APPLCATION_NUMBER"=>$applyOnlinesData->geda_application_no);
                        $template_include       = 'work_starts';
                        $subject                = "[REG: GEDA Application No. ".$applyOnlinesData->geda_application_no."] Work Starts";
                        $sms_text = str_replace(array('##geda_application_no##','##installer_name##'),array($applyOnlinesData->geda_application_no,$applyOnlinesData->installer['installer_name']),WORK_START);
                        if(!empty($applyOnlinesData->consumer_mobile))
                        {
                            $this->ApplyOnlines->sendSMS($applyOnlinesData->id,$applyOnlinesData->consumer_mobile,$sms_text,'WORK_START');
                        }
                        if(!empty($applyOnlinesData->installer_mobile))
                        {
                            //$this->ApplyOnlines->sendSMS($applyOnlinesData->id,$applyOnlinesData->installer_mobile,$sms_text);
                        }
                        $email      = new Email('default');
                            $email->profile('default');
                            $email->viewVars($EmailVars);
                            $message_send = $email->template($template_include, 'default')
                                    ->emailFormat('html')
                                    ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                                    ->to($applyOnlinesData->installer_email)
                                    ->subject(Configure::read('EMAIL_ENV').$subject)
                                    ->send();
                        $to     = $applyOnlinesData->consumer_email;
                        if(empty($to))
                        {
                            $to = $applyOnlinesData->email;
                        }
                        if(!empty($to))
                        {
                            $email          = new Email('default');
                            $email->profile('default');
                            $email->viewVars($EmailVars);
                            $message_send   = $email->template($template_include, 'default')
                                    ->emailFormat('html')
                                    ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                                    ->to($to)
                                    ->subject(Configure::read('EMAIL_ENV').$subject)
                                    ->send();
                        }
                    }
                    $this->ApplyOnlineApprovals->saveStatus($application_data->id,$NextStatus,$customer_id,'');
                    $this->FesibilityReport->Cei_All_Stage_APProved($application_data->id,'second');
                }
            }
                $this->Flash->success("Work order has been ".$mode." successfully.");
                $this->redirect('project/workorder/'.encode($project_id));
            }
            $capacity='';
            if($mode=='added')
            {
                //$commEntity->Capacity   = $project_data->recommended_capacity;
                $commEntity->Capacity   = $application_data->pv_capacity;
            }
            $commData['project_name']   = $project['project']['name'];
            $commData['wo_date']        = (isset($commData['workorder_date'])?$commData['workorder_date']->format('d-m-Y'):date('d-m-Y'));
            $this->set("commdata",$commEntity);
            $this->set("wo_date",$commData['wo_date']);
            $this->set("project_name",$commData['project_name']);
            $this->set('project_id',$project_id);
            $this->set('WorkorderErrors',$commEntity->errors());
            $this->set('capacity',$capacity);
            $this->set('can_start_work',$can_start_work);
            $this->set('fesibility_flag',$fesibility_flag);
            $this->set('is_member',$is_member);
            $this->set('Couchdb',$this->Couchdb);
            $this->set('member_area',$member_area);
        }
    }
    public function sitesurvey($porj_id = null,$sur_id = null)
    {
        $result             =NULL;
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $porj_id            = decode($porj_id);
        $survey_id          = decode($sur_id);
        $cus_id             = $this->Session->read('Customers.id');
        $customerData       = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id             = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $action             = (isset($this->request->data['action'])?$this->request->data['action']:'');
        $siteSurvey         = $this->SiteSurveys->find('all')
            ->where(['project_id'=> $porj_id])
            ->first();
        if($survey_id > 0)
        {
            $siteSurveyData                     = $this->SiteSurveys->get($survey_id);
            $this->SiteSurveys->data = $this->request->data;
            if(isset($this->request->data) && !empty($this->request->data) && isset($this->request->data['step_1']))
            {
                $siteSurveyEntity                   = $this->SiteSurveys->patchEntity($siteSurveyData,$this->request->data,['validate'=>'tab1']);
            }
            if(isset($this->request->data) && !empty($this->request->data) && isset($this->request->data['step_2']))
            {
                $siteSurveyEntity                   = $this->SiteSurveys->patchEntity($siteSurveyData,$this->request->data,['validate'=>'tab2']);
            }
            elseif(isset($this->request->data) && !empty($this->request->data) && isset($this->request->data['step_4']))
            {
                $siteSurveyEntity                   = $this->SiteSurveys->patchEntity($siteSurveyData,$this->request->data,['validate'=>'tab4']);
            }
            else
            {
                $siteSurveyEntity                   = $this->SiteSurveys->patchEntity($siteSurveyData,$this->request->data);
            }
            $result_photo_data   = $this->SiteSurveysImages->find('all',array('conditions' => array('building_id'=>$siteSurveyEntity->building_id, 'project_id' => $siteSurveyEntity->project_id)))->toArray();
            $arr_reading_details                = array();
            $arr_genset_details                 = array();
            $arr_inverter_details               = array();
            $arr_month_details                  = array();
            if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_1']) || isset($this->request->data['step_2']) || isset($this->request->data['step_3']) || isset($this->request->data['step_4'])))
            {
                if(isset($this->request->data['step_2']))
                {
                    foreach($this->request->data['document']['place_inverter'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.SITE_SURVEY_PATH."place_inverter/".$porj_id.'/';
                             if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="place_inverter";
                            $save= $this->imgfile_upload($place_inv,$path);
                            $this->upload_image($save,$type_img);
                        }
                    }
                    foreach($this->request->data['document']['place_battery'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.SITE_SURVEY_PATH."place_battery/".$porj_id.'/';
                             if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="place_battery";
                            $save= $this->imgfile_upload($place_inv,$path);
                            $this->upload_image($save,$type_img);
                        }
                    }
                    foreach($this->request->data['document']['place_for_ac_distribution_box'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.SITE_SURVEY_PATH."place_for_ac_distribution_box/".$porj_id.'/';
                             if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="place_for_ac_distribution_box";
                            $save= $this->imgfile_upload($place_inv,$path);
                            $this->upload_image($save,$type_img);
                        }
                    }
                    foreach($this->request->data['document']['metering_box'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.SITE_SURVEY_PATH."metering_box/".$porj_id.'/';
                             if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="metering_box";
                            $save= $this->imgfile_upload($place_inv,$path);
                            $this->upload_image($save,$type_img);
                        }
                    }
                    foreach($this->request->data['document']['take_photographs'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.SITE_SURVEY_PATH."take_photographs/".$porj_id.'/';
                              if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="take_photographs";
                            $save= $this->imgfile_upload($place_inv,$path);
                            $this->upload_image($save,$type_img);
                        }
                    }
                }
                if(isset($this->request->data['step_3']))
                {
                    foreach($this->request->data['r_phase'] as $key=>$val)
                    {
                        $arr_reading_details[$key]['r_phase'] = $val;
                        $arr_reading_details[$key]['r_phase_ry'] = $this->request->data['r_phase_ry'][$key];
                        $arr_reading_details[$key]['r_phase_rn'] = $this->request->data['r_phase_rn'][$key];
                        $arr_reading_details[$key]['y_phase'] = $this->request->data['y_phase'][$key];
                        $arr_reading_details[$key]['y_phase_yb'] = $this->request->data['y_phase_yb'][$key];
                        $arr_reading_details[$key]['y_phase_yn'] = $this->request->data['y_phase_yn'][$key];
                        $arr_reading_details[$key]['b_phase'] = $this->request->data['b_phase'][$key];
                        $arr_reading_details[$key]['b_phase_rb'] = $this->request->data['b_phase_rb'][$key];
                        $arr_reading_details[$key]['b_phase_bn'] = $this->request->data['b_phase_bn'][$key];
                    }
                    if(count($arr_reading_details)>0)
                    {
                        $arr_r_details                      = array("ReadingDetails" => $arr_reading_details);
                        $siteSurveyEntity->reading_details  = serialize($arr_r_details);
                    }
                    foreach($this->request->data['capacity'] as $key=>$val)
                    {
                        $arr_genset_details[$key]['kva']    = $val;
                        $arr_genset_details[$key]['hours']  = $this->request->data['usage'][$key];
                    }
                    if(count($arr_genset_details)>0)
                    {
                        $arr_gen_details                    = array("GensetDetails" => $arr_genset_details);
                        $siteSurveyEntity->genset_details   = serialize($arr_gen_details);
                    }
                    foreach($this->request->data['capacity_i'] as $key=>$val)
                    {
                        $arr_inverter_details[$key]['kva']    = $val;
                        $arr_inverter_details[$key]['hours']  = $this->request->data['usage_i'][$key];
                    }
                    if(count($arr_inverter_details)>0)
                    {
                        $arr_inv_details                      = array("InverterDetails" => $arr_inverter_details);
                        $siteSurveyEntity->inverter_details   = serialize($arr_inv_details);
                    }
                    foreach($this->request->data['document']['electricity_bill'] as $key=>$place_inv)
                    {
                        if(isset($place_inv['tmp_name']) && !empty($place_inv['tmp_name']))
                        {
                            $name=$place_inv['name'];
                            $path=WWW_ROOT.SITE_SURVEY_PATH."electricity_bill/".$porj_id.'/';
                              if(!is_dir($path)){
                                 mkdir($path,0755);
                            }
                            $type_img="electricity_bill";
                            $save= $this->imgfile_upload ($place_inv,$path);
                            $this->upload_image($save,$type_img);
                        }
                    }
                }
                if(isset($this->request->data['step_4']))
                {
                    foreach($this->request->data['year'] as $key=>$val)
                    {
                        $j = $key+2;
                        if($key <= 9)
                        {
                            $j = '0'.$key+2;
                        }
                        $arr_month_details[$key]['month'] = date('F',mktime('0', '0', '0', $j, '0', '0'));
                        $arr_month_details[$key]['year']  = $val;
                        $arr_month_details[$key]['power_consume'] = $this->request->data['power_consume'][$key];
                        $arr_month_details[$key]['bill_amount'] = $this->request->data['bill_amount'][$key];
                    }
                    if(count($arr_month_details)>0)
                    {
                        $arr_mon_details                    = array("ElectricityBillDetails" => $arr_month_details);
                        $siteSurveyEntity->month_details    = serialize($arr_mon_details);
                    }
                }

            }
            $siteSurveyEntity->modified = $this->NOW();
            $result_photo_data          = $this->SiteSurveysImages->find('all',array('conditions' => array('building_id'=>$siteSurveyEntity->building_id, 'project_id' => $siteSurveyEntity->project_id)))->toArray();
            $this->set('siteSurvey',$siteSurveyEntity);
            $this->set('building_id',$siteSurveyEntity->building_id);

            $this->set('all_photo_data',$result_photo_data);
            $mode = 'Edit';
        }
        else
        {
            $project_data                           = $this->Projects->find('all',array('conditions' => array('id' => $porj_id)))->toArray();
            $siteSurveyEntity                       = $this->SiteSurveys->newEntity($this->request->data, ['validate' => 'tab1']);
            $siteSurvey_data                        = $this->SiteSurveys->find('all')->where(['project_id'=> $porj_id])->toArray();
            $siteSurveyEntity->installer_id         = $cus_id;
            $siteSurveyEntity->project_name         = $project_data[0]['name'];
            $siteSurveyEntity->area_type            = $project_data[0]['area_type'];
            $siteSurveyEntity->is_snaction          = '0';
            $siteSurveyEntity->is_overall           = '2001';
            $siteSurveyEntity->is_shadow_free       = '2001';
            $siteSurveyEntity->road_to_site         = '0';
            $siteSurveyEntity->ladder_to_roof       = '0';
            $siteSurveyEntity->is_height_of_parapet = '2001';
            $siteSurveyEntity->is_dc_cable_distance = '2001';
            $siteSurveyEntity->voltage_pahse_level  = '3ph.';
            $mode                                   = 'Add';
            $siteSurveyEntity->created              = $this->NOW();
            $siteSurveyEntity->modified             = $this->NOW();
            $this->set('siteSurvey',$siteSurveyEntity);
            $this->set('building_id',count($siteSurvey_data)+1);
            $this->set('all_photo_data',array());
        }
        if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_1']) || isset($this->request->data['step_2']) || isset($this->request->data['step_3']) || isset($this->request->data['step_4'])))
        {
            if(!$siteSurveyEntity->errors())
            {
                $this->SiteSurveys->save($siteSurveyEntity);
                if($mode == 'Add')
                {
                    $survey_id  = $siteSurveyEntity->id;
                    $mode       = 'Edit';
                }
            }
        }
        $tab_active = 'step1';
        if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_1'])) && !$siteSurveyEntity->errors())
        {
            $tab_active = 'step2';
        }

        if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_2'])) && !$siteSurveyEntity->errors())
        {
            $tab_active = 'step3';
        }
        elseif(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_2'])) && $siteSurveyEntity->errors())
        {
            $tab_active = 'step2';
        }
        if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_3'])) && !$siteSurveyEntity->errors())
        {
            $tab_active = 'step4';
        }
        if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_4'])) && $siteSurveyEntity->errors())
        {
            $tab_active = 'step4';
        }
        if(isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_4'])) && !$siteSurveyEntity->errors())
        {
            $this->Flash->set('Site survey has been '.strtolower($mode).'ed successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
            return $this->redirect('projects/sitesurvey/'.encode($porj_id).'/'.encode($survey_id));
        }
        $arr_roof_type        = $this->SiteSurveys->ALL_ROOF_TYPE;
        $arr_roof_type[0]     = 'Type of Roof';
        $arr_roof_st          = $this->SiteSurveys->ALL_ROOF_STRENGTH;
        $arr_roof_st[0]       = 'Roof Strength';
        $arr_area             = $this->SiteSurveys->AREA_PARAMS;
        $arr_area['2001']     = ' '.$arr_area['2001'].'&nbsp;&nbsp;';
        $arr_area['2002']     = ' '.$arr_area['2002'].'  ';
        $arr_area_smp         = $this->SiteSurveys->AREA_PARAMS_SMP;
        $arr_area_smp['2001'] = ' '.$arr_area_smp['2001'].'&nbsp;&nbsp;';
        $arr_area_smp['2002'] = ' '.$arr_area_smp['2002'].'  ';
        $arr_meter_type       = $this->SiteSurveys->ALL_METER_TYPE;
        $arr_meter_type[0]    = 'Meter Type';
        $arr_meter_acc        = $this->SiteSurveys->ALL_METER_ACCURACY_CLASS;
        $arr_meter_acc[0]     = 'Meter Accuracy';
        $arr_load_param       = $this->SiteSurveys->LOAD_PARAMS;
        $arr_load_param['0']  = ' '.$arr_load_param['0'].'&nbsp;&nbsp;';
        $arr_load_param['1']  = ' '.$arr_load_param['1'].'  ';
        $all_cust_type        = $this->SiteSurveys->ALL_CUSTOMER_TYPE;
        $arr_billing_cycle    = $this->SiteSurveys->ALL_BILLING_CYCLE;
        unset($arr_meter_acc['']);
        unset($arr_meter_type['']);
        unset($arr_area_smp[0]);
        unset($arr_area_smp['']);
        unset($arr_area[0]);
        unset($arr_area['']);
        unset($arr_roof_st['']);
        unset($arr_roof_type['']);
        unset($arr_billing_cycle[0]);
        unset($arr_billing_cycle['']);
        $this->set('arr_roof_type',$arr_roof_type);
        $this->set('arr_roof_st',$arr_roof_st);
        $this->set('arr_area',$arr_area);
        $this->set('arr_area_smp',$arr_area_smp);
        $this->set('arr_meter_type',$arr_meter_type);
        $this->set('arr_meter_acc',$arr_meter_acc);
        $this->set('arr_load_param',$arr_load_param);
        $this->set('all_cust_type',$all_cust_type);
        $this->set('arr_billing_cycle',$arr_billing_cycle);
        $this->set('proj_id',encode($porj_id));
        $this->set('sur_id',encode($survey_id));
        $this->set('mode',$mode);
        $this->set('SiteSurveysErrors',$siteSurveyEntity->errors());
        $this->set('tab_active',$tab_active);
        if(!empty($action)) {
            $params['customer_id']  = $cus_id;
        }
    }
    public function commercial($porj_id = null,$id=null)
    {
        $result = NULL;
        $this->autoRander = false;
        $this->layout = 'popup';
        $porj_id = decode($porj_id);
        $tab_active = '';
        $cus_id = $this->Session->read('Customers.id');
        $customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $tab_active = 'step1';
        if (isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_1']))) {
            $tab_active = 'step2';
        }
        if (isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_2']))) {
            $tab_active = 'step3';
        }
        if (isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_3']))) {
            $tab_active = 'step4';
        }
         if (isset($this->request->data) && !empty($this->request->data) && (isset($this->request->data['step_4']))) {
            $tab_active = 'step5';
        }
        $Commercial = $this->Commercial->find('all')
            ->where(['project_id' => $porj_id])
            ->first();

        if (!empty($Commercial)) {
            $commEntity = $this->Commercial->patchEntity($Commercial, $this->request->data);
            $commEntity->installer_id = $cus_id;
        } else {
            $commEntity = $this->Commercial->newEntity($this->request->data());
            $commEntity->installer_id = $cus_id;
        }
        if(isset($this->request->data['step_1'])) {

            if (empty($this->request->data['pv_cost'])) {
                $commEntity->pv_cost = 0;
            }
            if (empty($this->request->data['pv_qty'])) {
                $commEntity->pv_qty = 0;
            }
            if (empty($this->request->data['inverter_cost'])) {
                $commEntity->inverter_cost = 0;
            }
            if (empty($this->request->data['inverter_qty'])) {
                $commEntity->inverter_qty = 0;
            }
            if (empty($this->request->data['bos_cost'])) {
                $commEntity->bos_cost = 0;
            }
            if (empty($this->request->data['bos_qty'])) {
                $commEntity->bos_qty = 0;
            }
            if (empty($this->request->data['other_cost'])) {
                $commEntity->other_cost = 0;
            }
            if (empty($this->request->data['other_qty'])) {
                $commEntity->other_qty = 0;
            }
            if (empty($this->request->data['taxes'])) {
                $commEntity->taxes = 0;
            }
            if (empty($this->request->data['total_cost'])) {
                $commEntity->total_cost = 0;
            }
            if (empty($this->request->data['pv_rating'])) {
                $commEntity->pv_rating = 0;
            }
            if (empty($this->request->data['inverter_rating'])) {
                $commEntity->inverter_rating = 0;
            }
            if (empty($this->request->data['bos_rating'])) {
                $commEntity->bos_rating = 0;
            }
        }
        if (isset($this->request->data['step_2'])) {
            
                if (empty($this->request->data['om_cost'])) {
                    $commEntity->om_cost = 0;
                }
                if (empty($this->request->data['escalation_om'])) {
                    $commEntity->escalation_om = 0;
                }
                if (empty($this->request->data['debt'])) {
                    $commEntity->debt = 0;
                }
                if (empty($this->request->data['interest'])) {
                    $commEntity->interest = 0;
                }
                if (empty($this->request->data['insurance_cost'])) {
                    $commEntity->insurance_cost = 0;
                }
                if (empty($this->request->data['deprecation'])) {
                    $commEntity->deprecation = 0;
                }
                if (empty($this->request->data['rate_of_desp_15'])) {
                    $commEntity->rate_of_desp_15 = 0;
                }
                if (empty($this->request->data['rate_of_acc_desp'])) {
                    $commEntity->rate_of_acc_desp = 0;
                }
                if (empty($this->request->data['cuf'])) {
                    $commEntity->cuf = 0;
                }
                if (empty($this->request->data['loanternure'])) {
                    $commEntity->loanternure = 0;
                }
            
        }
        $arr_energy_charge_details = array();
        if (isset($this->request->data['step_3'])) {
            foreach ($this->request->data['eccharges_upto'] as $key => $val) {
                $arr_energy_charge_details[$key]['eccharges_upto'] = $val;
                $arr_energy_charge_details[$key]['eccharges_upto_rs'] = $this->request->data['eccharges_upto_rs'][$key];
                $arr_energy_charge_details[$key]['ecbetween1_from'] = $this->request->data['ecbetween1_from'][$key];
                $arr_energy_charge_details[$key]['ecbetween1_to'] = $this->request->data['ecbetween1_to'][$key];
                $arr_energy_charge_details[$key]['ecbetween1_to_rs'] = $this->request->data['ecbetween1_to_rs'][$key];
                $arr_energy_charge_details[$key]['ecbetween2_from'] = $this->request->data['ecbetween2_from'][$key];
                $arr_energy_charge_details[$key]['ecbetween2_to'] = $this->request->data['ecbetween2_to'][$key];
                $arr_energy_charge_details[$key]['ecbetween2_to_rs'] = $this->request->data['ecbetween2_to_rs'][$key];
                $arr_energy_charge_details[$key]['ecmorethen'] = $this->request->data['ecmorethen'][$key];
                $arr_energy_charge_details[$key]['ecmorethen_rs'] = $this->request->data['ecmorethen_rs'][$key];
            }
            if (count($arr_energy_charge_details) > 0) {
                $arr_e_details = array("energy_charge" => $arr_energy_charge_details);
                $commEntity->energy_charge_details = serialize($arr_e_details);
            }
            if (empty($this->request->data['fc_charge_rs'])) {
                $commEntity->fc_charge_rs = 0;
            }
            if (empty($this->request->data['fc_charge_kw'])) {
                $commEntity->fc_charge_kw = 0;
            }
            if (empty($this->request->data['vc_charge'])) {
                $commEntity->vc_charge = 0;
            }
            if (empty($this->request->data['note3'])) {
                $commEntity->note3 = 0;
            }
            if (empty($this->request->data['note3'])) {
                $commEntity->note3 = 0;
            }
        }
        if(!empty($porj_id))
        {
            $projects = $this->Projects->get(['id' => $porj_id]);
            /* calculation of Recommended Capacity of techo commercial data*/
            $solarPenalArea = 0;
            $monthly_saving = 0;
            $area_type=$projects->area_type;
            $area=$projects->area;
            $latitude=$projects->latitude;
            $longitude=$projects->longitude;
            if($area_type == $this->Projects->AREA_TYPE_FOOT) {
                    $solarPenalArea = $this->Projects->calculatePvInFoot($area);
            } elseif($area_type == $this->Projects->AREA_TYPE_METER) { 
                    $solarPenalArea = $this->Projects->calculatePvInMeter($area); 
             }
            $energy_con=$projects->estimated_kwh_year;
            $solarPvInstall                                     = ceil($solarPenalArea/12);
            $solarRediationData                                 = $this->Projects->getSolarRediation($latitude,$longitude);
            $annualTotalRad                                     = ($solarRediationData['ann_glo']*365);
            $contractLoad                                       = round(((($energy_con*12)/((24*365*LOAD_FECTORE)/100))));
            $capacityAcualEnrgyCon                              = round(((($energy_con*12)/$annualTotalRad)));
            $recommendedSolarPvInstall                          = min($solarPvInstall, $contractLoad, $capacityAcualEnrgyCon);
            /* calculation of Payback in Years of techo commercial data*/
            $cost_solar             = 0.0;
            $unitRate               = 0;    
            $avg_month_bill=$projects->avg_monthly_bill;
            $backup_type=$projects->backup_type;
            $usage_hours=$projects->usage_hours;
            $capitalCost            = $this->Projects->calculatecapitalcost($recommendedSolarPvInstall,$projects->state,$projects->customer_type);

            $capitalCostsubsidy     = $this->Projects->calculatecapitalcostwithsubsidy($recommendedSolarPvInstall,$capitalCost,$projects->state,$projects->customer_type);

            $averageEnrgyGenInYear  = round(((($recommendedSolarPvInstall*$annualTotalRad)*PERFORMANCE_RATIO)/100));
            if(!empty($avg_month_bill) && !empty($avg_month_bill) && (!empty($energy_con))) {
                 $unitRate               = (($avg_month_bill/$energy_con)-0.5);
                }

              $solarChart             = $this->Projects->getBillingChart($contractLoad,$unitRate,$averageEnrgyGenInYear,($capitalCostsubsidy/100000),$backup_type,$usage_hours);
        
              $payBack                = (isset($solarChart['breakEvenPeriod'])?$solarChart['breakEvenPeriod']:0);

             /* calculation to display Paybackchart in techo commercial data */
            $recommended_capacity=$projects->recommended_capacity;
            $estimated_cost=$projects->estimated_cost;
            $avg_monthly_bill=$projects->avg_monthly_bill;
            $solarRediationDataNew   = $this->GhiData->getGhiData($longitude,$latitude);
            $energyAndSavingDataArr = $this->Projects->getMonthEnergyAndSavingData($solarRediationDataNew,$recommended_capacity,$avg_monthly_bill,$energy_con);

         
            $monthSavinData     = (!empty($energyAndSavingDataArr['saving_data'])?$energyAndSavingDataArr['saving_data']:array());
            $monthly_saving     = array_sum($monthSavinData);
            $estimated_cost_subsidy = isset($projects->estimated_cost_subsidy)?round(($projects->estimated_cost_subsidy/100000),2):$projects->estimated_cost;
            $payBackGraphData   = $this->Projects->GetPaybackChartData($estimated_cost_subsidy, $monthly_saving);
           
            /*calculation of avg_monthly_generation*/
            $averageEnrgyGenInMonth = ($averageEnrgyGenInYear/12);
            $averageEnrgyGenInMonthdata = round($averageEnrgyGenInMonth,2);
             /*calculation of savings*/
             $averageEnrgyGenInDay   = (((($recommendedSolarPvInstall*$solarRediationData['ann_glo'])*PERFORMANCE_RATIO)/100));

             $montly_pv_generation   = ($averageEnrgyGenInDay * 30);
         
            if(!empty($energy_con) && !empty($avg_month_bill)) { 
                 $monthly_saving         = ($avg_month_bill - ($energy_con - $montly_pv_generation) * (($avg_month_bill/$energy_con)-0.5)); 
            }
            $savings=_FormatGroupNumberV2($monthly_saving);
        }

        if(!empty($this->request->data)) {

            $this->Commercial->save($commEntity);
        }
        $this->set('Commercial',$commEntity);
        $this->set('payBack',$payBack);
        $this->set('recommendedSolarPvInstall',$recommendedSolarPvInstall);
        $this->set('tab_active', $tab_active);
        $this->set("payBackGraphData",$payBackGraphData);
        $this->set("averageEnrgyGenInMonthdata",$averageEnrgyGenInMonthdata);
        $this->set("savings",$savings);
    }
    public function termscondition($porj_id = null)
    {
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $cus_id             = $this->Session->read('Customers.id');

        $customerData       = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id             = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $action             = $this->request->data("action") ? $this->request->data("action") :'upload_terms';
        $termsArray         = array();
        $result             = array();
        if(!empty($cus_id)) 
        {      
        $termsArray     = $this->InstallerTerms->find('all',array('conditions'=>array('installer_id'=>$cus_id)))->toArray();
            foreach($termsArray as $val)
            {
                $result[$val['id']]['termspath']        = $val['termspath'];
                $result[$val['id']]['position_order']   = $val['position_order'];
                $result[$val['id']]['is_default']       = $val['is_default'];
            }
            $terms_key_data = $this->InstallerTerms->find('list',array('keyField' => 'id', 'valueField' => 'position_order', 'conditions' => array('installer_id'=>$cus_id)))->toArray();
        }
        $this->set('termsdata', $result);
        $this->set('terms_key_data', $terms_key_data);
        $this->set('cus_id',$cus_id);
    }
    public function create_xls()
    {
        $this->autoRender = false;
        $pr_id=decode($this->request->data('project_id'));

        $result_data=$this->SiteSurveys->find('all',array('conditions' => array('project_id'=>$pr_id)))->toArray();
        $arr_projets_data=$this->Projects->find('all',array('fields'=>array('Projects.name'),
            'conditions'=>array('id'=>$pr_id)))->first();
        $project_name='';
        if(!empty($arr_projets_data))
        {
            $project_name = $arr_projets_data['name'];
            $arr_installer = $this->InstallerProjects->find('all',array('conditions' => array('project_id'=>$pr_id)))->toarray();
        }
        $all_area_types     = $this->SiteSurveys->AREA_PARAMS;
        $all_area_type_smp  = $this->SiteSurveys->AREA_PARAMS_SMP;
        $all_load           = $this->SiteSurveys->LOAD_PARAMS;
        $all_meter          = $this->SiteSurveys->ALL_METER_TYPE;
        $all_meter_accuracy = $this->SiteSurveys->ALL_METER_ACCURACY_CLASS;
        $all_roof           = $this->SiteSurveys->ALL_ROOF_TYPE;
        $all_roof_st        = $this->SiteSurveys->ALL_ROOF_STRENGTH;
        $all_billing        = $this->SiteSurveys->ALL_BILLING_CYCLE;
        $this->survey_download_xls($result_data, $project_name, $all_area_types, $all_area_type_smp, $all_load, $all_meter, $all_meter_accuracy, $all_roof, $all_roof_st, $all_billing);
    }

    /**
     *
     * viewsurveyreport
     *
     * Behaviour : public
     *
     * @param : id  : Id is use to identify for which site PDF file should be downlaoded
     *
     * @defination : Method is use to download .pdf file using genratesurveyPDFreport()
     *
     */
    public function viewsurveyreport($id = null)
    {
        $this->layout = false;
        if(empty($id)) {
            $this->Flash->error('Please Select Valid Project.');
            return $this->redirect('/project/index/');
        } else {
            $id=intval(decode($id));
        }
        $pdfPath = $this->genratesurveyPDFreport($id,true);
    }
    /**
     *
     * viewprojectsurveyreport
     *
     * Behaviour : public
     *
     * @param : id  : Id is use to identify for which Project all site PDF file should be downlaoded
     *
     * @defination : Method is use to download .pdf file using genratesurveyPDFreport()
     *
     */
    public function viewprojectsurveyreport($id = null)
    {
        $this->layout = false;
        if(empty($id)) {
            $this->Flash->error('Please Select Valid Project.');
            return $this->redirect('/project/index/');
        } else {
            $id=intval(decode($id));
        }
        $pdfPath = $this->genratesurveyPDFreport($id,true,1);
    }
    /**
    *
    * upload_image
    *
    * Behaviour : public
    *
    * @param : id  : $img is store the image to be saved in the database and $path_img is use to identify the type of image.
    *
    * @defination : Method is use to save the image in database.
    *
    */
    public function upload_image($img,$path_img)
    {
        $cus_id         = $this->Session->read('Customers.id');
        $customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id         = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $proj_id        = (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
        if(!empty($cus_id)) 
        {
            $type = (!empty($this->request->data['image_type']) ? $this->request->data['image_type'] : $path_img);
            $imagePatchEntity = $this->SiteSurveysImages->newEntity($this->request->data);
            $imagePatchEntity->type = $type;
            $imagePatchEntity->building_id = (isset($this->request->data['building_id']) ? $this->request->data['building_id'] : 1);
            $imagePatchEntity->project_id = $proj_id;
            $imagePatchEntity->installer_id = $cus_id;
            $file_name = $img;
            $imagePatchEntity->photo = $file_name;
            $this->SiteSurveysImages->save($imagePatchEntity);
        }
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
    public function imgfile_upload($file,$path,$prefix_file='',$customerId='',$access_type='')
    {
        $ext    = substr(strtolower(strrchr($file['name'], '.')), 1);
        $file_name   = $prefix_file.date('Ymdhms').rand();
        $file_location  = $path.$file_name.'.'.$ext;
        move_uploaded_file($file['tmp_name'],$file_location);
        $passFileName   = $file_name.'.'.$ext;
        $couchdbId      = $this->Couchdb->saveData($path,$file_location,$prefix_file,$passFileName,$customerId,$access_type);
        $this->Couchdb->couchdbid  = $couchdbId;
        return $file_name.'.'.$ext;
    }
    /**
    *
    * delete_survey_image
    *
    * Behaviour : public
    *
    * @param : survey_photo_id  : is use to identify for which image should be delete
    *
    * @defination : Method is use to delete survey image from database and in file folder .
    *
    */
    public function delete_survey_image()
    {
        $this->autoRender = false;
        $photo_id = decode($this->request->data['photo_id']);
        $entity = $this->SiteSurveysImages->get($photo_id);
        $name=$entity['photo'];
        $path=WWW_ROOT.SITE_SURVEY_PATH.$entity['type']."/".$entity['project_id']."/".$name;
        unlink($path);
        //$sql = "DELETE FROM  `project_survey_photos` WHERE `id` = '".$photo_id."'";
        //$this->conn->execute($sql);
        $result = $this->SiteSurveysImages->delete($entity);
        echo "1";
        exit;
    }

    /**
     *
     * buisnessDeveloperList
     *
     * Behaviour : Public
     *
     * @defination : Method used for get business developer listing
     *
     * Create by : Sachin Patel
     */

    public function businessDeveloperList($project_id=""){

        $this->autoRander   = false;
        $this->layout       = 'popup';
        $customer_id = $this->Session->read('Customers.id');
        if(isset($project_id) && $project_id !=""){
            $project_id  = decode($project_id);
        }
        $allcustomer    = array();
        $condition      = array();
        $Customerlist   = $this->Customers->find('all',array('fields'=>['installer_id'],'conditions'=>['id' => $customer_id ]))->first();

        if(!empty($Customerlist))
        {
            $allcustomer = $this->Customers->find('all',array('conditions'=>['installer_id' => $Customerlist->installer_id,'Customers.user_role Like' =>"%".$this->Parameters->bd_role."%"]))->toArray();
            if(isset($allcustomer) && !empty($allcustomer)){

                if(isset($project_id) && $project_id !=""){
                    foreach ($allcustomer as $key => $customer){
                        $customer->selected = $this->ProjectAssignBd->getSelectedCustomer($project_id,$customer->id);
                    }

                }
            }

        }
        $this->set('bdlists',$allcustomer);

    }

    /**
     *
     * projectAssignBd
     *
     * Behaviour : Public
     *
     * @defination : Assign Business Developer and Remove it.
     *
     * Create by : Sachin Patel
     */

    public function projectAssignBd(){
        $error	= '';
        $this->autoRander   = false;
        $this->layout       = 'popup';
        $customer_id        = $this->Session->read('Customers.id');
        if(isset($customer_id) && $customer_id !=""){
            if(isset($this->request->data['projects_id']) && $this->request->data['projects_id'] !=""){
                $this->ProjectAssignBd->deleteAll(['ProjectAssignBd.projects_id'=>decode($this->request->data['projects_id'])]);
                if(isset($this->request->data['assign_customer_ids']) && $this->request->data['assign_customer_ids'] !=""){
                    $customersArray = $this->request->data['assign_customer_ids'];
                    if(!empty($customersArray)) {
                        foreach ($customersArray as $customerId) {
                            $this->request->data['customers_id'] = $customerId;
                            $this->request->data['created_by'] = $customer_id;
                            $assign = $this->ProjectAssignBd->newEntity($this->request->data);
                            $assign->projects_id = decode($this->request->data['projects_id']);
                            $assign->created = $this->NOW();
                            if ($this->ProjectAssignBd->save($assign)) {
                                $this->Flash->success('Project Assign Sucessfully');
                                return $this->redirect(array('action'=>'businessDeveloperList',$this->request->data['projects_id']));
                            }
                        }
                    }
                }
                else
                {
                    $this->Flash->success('Project removed Sucessfully');
                    return $this->redirect(array('action'=>'businessDeveloperList',$this->request->data['projects_id']));
                }
            }else {
                $error = 'project_id not found';
            }
        }else {
            $error = "Customer Not Found";
        }
        if($error!=""){
            $this->Flash->error($error);
        }
        return $this->redirect(array('action'=>'businessDeveloperList',$this->request->data['projects_id']));
        exit;
    }
    /**
     *
     * remove_data
     *
     * Behaviour : Public
     *
     * @defination :Method is used to remove existing document while edit the data of reportdata.
     *
     */
    public function remove_data(){
        $data=$this->ProjectNotes->get($_REQUEST['id']);
        $projectEntity= $this->ProjectNotes->patchEntity($data,$this->request->data);
        $a=$_REQUEST['docval'];
        $projectEntity->$a='';
        if($this->ProjectNotes->save($projectEntity)){
            echo "1";
        }else{
            echo "0";
        }
        exit;
    }
    /**
     *
     * uploadterms
     *
     * Behaviour : public
     *
     * @defination : Method used to upload installer terms.
     *
     */
    public function uploadterms()
    {  
        $cus_id         = $this->Session->read('Customers.id');
        $customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id   = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $action         = $this->request->data("action") ? $this->request->data("action") :'upload_terms';
        $mode           = '';
        if(!empty($cus_id)) 
        { 
            for($i=1;$i<=4;$i++)
            {
                $originalTerms = '';
                if(isset($this->request->data['termspath_'.$i]['name']) && !empty($this->request->data['termspath_'.$i]['name']) && empty($this->request->data['termspath_'.$i]['error']))
                {
                    if(!empty($this->request->data("is_default"))) {
                    $this->InstallerTerms->updateAll(['is_default' => 0], ['installer_id' =>$cus_id]);
                    }
                    $position_order = $i;
                    if(!empty($this->request->data("term_id_".$i)))
                    {
                        $insTermData        = $this->InstallerTerms->get($this->request->data("term_id_".$i));
                        $originalTerms      = INSTALLER_TERMS_PATH.$cus_id.'/'.$insTermData['termspath'];
                        $imagePatchEntity   = $this->InstallerTerms->patchEntity($insTermData, $this->request->data);
                        $mode               = 'updated';
                    }
                    else
                    {
                        $imagePatchEntity   = $this->InstallerTerms->newEntity($this->request->data);
                        $mode               = 'added';
                    }   
                    $image_path                         = INSTALLER_TERMS_PATH.$cus_id.'/';
                    if(!file_exists(INSTALLER_TERMS_PATH.$cus_id))
                        mkdir(INSTALLER_TERMS_PATH.$cus_id, 0755,true);
                    $file_name                          = $this->imgfile_upload($this->request->data['termspath_'.$i],$image_path);
                    $imagePatchEntity->termspath        = $file_name;
                    $imagePatchEntity->installer_id     = $cus_id;
                    /*if($this->request->data("is_default") == $i)
                    {
                        $imagePatchEntity->is_default   = '1';
                    }
                    else
                    {
                        $imagePatchEntity->is_default   = '0';
                    }*/
                    $imagePatchEntity->status           = 1;
                    $imagePatchEntity->position_order   = $position_order;
                    $imagePatchEntity->created          = $this->NOW();
                    $imagePatchEntity->created_by       = $this->Session->read('Customers.id');
                    $termsRes                           = $this->InstallerTerms->save($imagePatchEntity);
                    if(file_exists($originalTerms)) 
                    {
                            unlink($originalTerms);
                    }   
                }
            }
            if(!empty($this->request->data("is_default")) && $this->request->data("action") == "update_default_status") {

                $this->InstallerTerms->updateAll(['is_default' => 0], ['installer_id' =>$cus_id]);
                $this->InstallerTerms->updateAll(['is_default' => 1], ['id' => $this->request->data("is_default"),'installer_id' =>$cus_id]);
                
                $this->Flash->success('Default has been set successfully.');
                return $this->redirect('projects/termscondition');
                exit;
            }
            $this->Flash->success('Terms and conditions have been '.$mode.' successfully.');
            return $this->redirect('projects/termscondition');
        }
    }
     /**
     *
     * commissionig_check_data
     *
     * Behaviour : public
     *
     * @defination : Method is used to check commissioning data is exists or not.
     *
     */
     public function commissionig_check_data()
     { 
            $data   = $this->CommissioningData->find('all',array('conditions' => array('project_id'=>$_REQUEST['pr_id'], 'request_receive' =>1,'type'=>$_REQUEST['c_name'])))->first();

            if(!empty($data)){
                $date_data=date(LIST_DATE_FORMAT,strtotime($data->created)); ;
                 echo json_encode(array('success'=>'1','msg'=>'send','pr_id'=>$_REQUEST['pr_id'],'c_name'=>$_REQUEST['c_name'],'c_value'=>$_REQUEST['c_value'],'date'=>$date_data));
            }
            else{
                echo json_encode(array('success'=>'0','msg'=>'send','pr_id'=>$_REQUEST['pr_id'],'c_name'=>$_REQUEST['c_name'],'c_value'=>$_REQUEST['c_value']));
            }
         
        exit;
     }
      /**
     *
     * commissionig_data_save
     *
     * Behaviour : public
     *
     * @defination : Method is used to save commissioning data.
     *
     */
    public function commissionig_data_save()
    {
        if(isset($_REQUEST['co_value'])){
            $data   = $this->CommissioningData->find('all',array('conditions' => array('project_id'=>$_REQUEST['proj_id'], 'type'=>$_REQUEST['co_name'])))->first();
                
            $type = (!empty($_REQUEST['co_name']) ? $_REQUEST['co_name']:"");
            
            if(!empty($data)){
                $dataGet = $this->CommissioningData->get($data['id']);
                $newcommission = $this->CommissioningData->patchEntity($dataGet,$this->request->data());
             }
            else{
                $newcommission = $this->CommissioningData->newEntity($this->request->data());
            }
            $newcommission->type = $type;
            $newcommission->request_receive   = 1;
            $newcommission->project_id =$_REQUEST['proj_id'] ;
            $date = date('Y-m-d H:i:s');
            $newcommission->created =$date;
        }

        if($this->CommissioningData->save($newcommission)){
          echo json_encode(array('success'=>'1','msg'=>'send'));
        }
        exit;
    }
    /**
    *
    * upload_commissioning_image
    *
    * Behaviour : public
    *
    * @param : $img is store the image to be saved in the database and $path_img is use to identify the type of image $comm_id is used to identify the commissioning id.
    *
    * @defination : Method is use to save the image in database.
    *
    */
    public function upload_commissioning_image($img,$path_img,$comm_id)
    {
        if(!empty($comm_id)) 
        {
            $type = (!empty($this->request->data['image_type']) ? $this->request->data['image_type'] : $path_img);
            $imagePatchEntity = $this->CommissioningImage->newEntity($this->request->data);
            $imagePatchEntity->type = $type;
            $imagePatchEntity->commissioning_id = $comm_id;
            $file_name = $img;
            $imagePatchEntity->photo = $file_name;
            $this->CommissioningImage->save($imagePatchEntity);
        }
    }
      /**
    *
    * delete_survey_image
    *
    * Behaviour : public
    *
    * @param : photo_id  : is use to identify for which image should be delete
    *
    * @defination : Method is use to delete commissioning image from database and in file folder .
    *
    */
    public function delete_commissioning_image()
    {
        $this->autoRender = false;
        $photo_id = decode($this->request->data['photo_id']);
        $entity = $this->CommissioningImage->get($photo_id);
        $name=$entity['photo'];
        $path=WWW_ROOT.COMMISSIONING_DATA_PATH.$entity['type']."/".$_REQUEST['proj_id'].'/'.$name;
        unlink($path);
        $result = $this->CommissioningImage->delete($entity);
        echo "1";
        exit;
    }
     /**
    *
    * upload_execution_image
    *
    * Behaviour : public
    *
    * @param : $img is store the image to be saved in the database and $path_img is use to identify the type of image $comm_id is used to identify the execution id.
    *
    * @defination : Method is use to save the image in database.
    *
    */
    public function upload_execution_image($img,$path_img,$comm_id)
    {
        $cus_id         = $this->Session->read('Customers.id');
        $customerData   = $this->Customers->find('all', array('conditions'=>array('id'=>$cus_id)))->first();
        $cus_id         = (isset($customerData['installer_id'])?$customerData['installer_id']:0);
        $proj_id        = (isset($this->request->data['project_id'])?$this->request->data['project_id']:0);
        if(!empty($cus_id)) 
        {
            $type = (!empty($this->request->data['image_type']) ? $this->request->data['image_type'] : $path_img);
            $imagePatchEntity = $this->ProjectInstallationPhotos->newEntity($this->request->data);
            $imagePatchEntity->type = $type;
            $imagePatchEntity->project_installation_id =$comm_id;
            $imagePatchEntity->project_id = $proj_id;
            $imagePatchEntity->couchdb_id = isset($this->Couchdb->couchdbid) ? $this->Couchdb->couchdbid : '';
            $file_name = $img;
            $imagePatchEntity->photo = $file_name;
            $this->ProjectInstallationPhotos->save($imagePatchEntity);
        }
    }
    /**
    *
    * delete_execution_image
    *
    * Behaviour : public
    *
    * @param : photo_id  : is use to identify for which image should be delete
    *
    * @defination : Method is use to delete execution image from database and in file folder .
    *
    */
    public function delete_execution_image()
    {
        $this->autoRender = false;
        $photo_id = decode($this->request->data['photo_id']);
        $entity = $this->ProjectInstallationPhotos->get($photo_id);
        $name=$entity['photo'];
        $type=$entity['type'];
        $path=WWW_ROOT.EXECUTION_PATH.$entity['project_id'].'/'.$type.'/'.$name;
        unlink($path);
        $result = $this->ProjectInstallationPhotos->deleteAll(['id'=>$entity->id]);
        $EntryExist = $this->Couchdb->find('all',array('conditions'=>array('application_id'=>$entity['project_id'],'store_module'=>'Execution','access_type'=>$entity['type'],'action_type'=>'')))->first();
        if(isset($EntryExist->document_id) && isset($EntryExist->rev_id)) {
            require_once(ROOT . DS . 'vendor' . DS . 'couchdb' . DS . 'couchdb.php');
            $COUCHDB        = new Couchdb();
            $this->Couchdb->deleteAll(['id'=>$EntryExist->id]);
            $COUCHDB->deleteDocument($EntryExist->document_id,$EntryExist->rev_id);
        }
        echo "1";
        exit; 
    }
    /*
     * get_money_indian_format
     * @param mixed $amount
     * @param boolean $suffix
     * @return mixed $thecash
     */
    private function get_money_indian_format($amount, $suffix = 1)
    {
        $explrestunits = "";
        $num = $amount;
        if(strlen($num)>3) {
            $lastthree = substr($num, strlen($num)-3, strlen($num));
            $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
            $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
            $expunit = str_split($restunits, 2);
            for($i=0; $i<sizeof($expunit); $i++) {
                // creates each of the 2's group and adds a comma to the end
                if($i==0) {
                    $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
                } else {
                    $explrestunits .= $expunit[$i].",";
                }
            }
            $thecash = $explrestunits.$lastthree;
        } else {
            $thecash = $num;
        }
        if(!$suffix) {
            return $thecash;
        } else {
            //return 'Rs. '. $thecash.'/-';
            setlocale(LC_MONETARY, 'en_IN');
            if (function_exists('money_format')) {
                $amount = money_format('%!i', $amount);
            } else {
               $amount  = number_format($amount,"2",".",",");
            }
            
            return 'Rs. '.$amount.'/-';
        }
    }
    /**
    *
    * CreateMyProject
    *
    * Behaviour : private
    *
    * @param : application_id, CreateMyProject, $set_capacity 
    *
    * @defination : Method is use to update capacity in project and apply online table.
    *
    */
    private function CreateMyProject($application_id=0,$CreateMyProject=true,$set_capacity='')
    {
        if (!empty($application_id)) 
        {
            $applyOnlinesData = $this->ApplyOnlines->get($application_id);
            $applyOnlinesData->aid  = $this->ApplyOnlines->GenerateApplicationNo($applyOnlinesData);
            $proj_name      = "APPLICATION - ".$applyOnlinesData->aid;
            $lat            = $applyOnlinesData->lattitue;
            $lon            = $applyOnlinesData->longitude;
            $roof_area      = ($applyOnlinesData->pv_capacity * 12);
            $c_type         = $applyOnlinesData->category;
            $energy_con     = !empty($applyOnlinesData->energy_con)?$applyOnlinesData->energy_con:0;
            $area_type      = '2002';
            $bill           = $applyOnlinesData->bill;
            $backup_type    = 0;
            $hours          = 0;
            $location_flag  = 'auto';
            $customer_id    = $applyOnlinesData->customer_id;
            $installer_id   = $applyOnlinesData->installer_id;
            $address            = $applyOnlinesData->address1;
            $city               = $applyOnlinesData->city;
            $state              = $applyOnlinesData->state;
            $state_short_name   = $applyOnlinesData->state;
            $pincode            = $applyOnlinesData->pincode;
            $country            = $applyOnlinesData->country;
            $SendQuery          = true;
            $this->request->data['Projects']['name']    = $proj_name;
            if (!empty($applyOnlinesData->project_id)) {
                $this->request->data['Projects']['id']  = $applyOnlinesData->project_id;
                $project_details= $this->Projects->find('all',array('conditions'=>array('id'=>$applyOnlinesData->project_id)))->first();
                $this->request->data['proj_name']       = $project_details->name;
                $SendQuery                              = false;
            }
            $pv_app_capacity    = $applyOnlinesData->pv_capacity;
            if($set_capacity != '')
            {
                $pv_app_capacity= $set_capacity;
            }
            $this->request->data['latitude']                        = $lat;
            $this->request->data['Projects']['latitude']            = $lat;
            $this->request->data['longitude']                       = $lon;
            $this->request->data['Projects']['longitude']           = $lon;
            $this->request->data['customer_type']                   = $c_type;
            $this->request->data['project_type']                    = $c_type;
            $this->request->data['Projects']['customer_type']       = $c_type;
            $this->request->data['area']                            = $roof_area;
            $this->request->data['Projects']['area']                = $roof_area;
            $this->request->data['area_type']                       = $area_type;
            $this->request->data['bill']                            = $bill;
            $this->request->data['avg_monthly_bill']                = $bill;
            $this->request->data['backup_type']                     = $backup_type;
            $this->request->data['usage_hours']                     = $hours;
            $this->request->data['Projects']['usage_hours']         = $hours;
            $this->request->data['energy_con']                      = $energy_con;
            $this->request->data['Projects']['estimated_kwh_year']  = $energy_con;
            $this->request->data['recommended_capacity']            = $pv_app_capacity;
            $this->request->data['Projects']['recommended_capacity']= $pv_app_capacity;
            $this->request->data['address']                         = $address;
            $this->request->data['city']                            = $city;
            $this->request->data['state']                           = $state;
            $this->request->data['state_short_name']                = $state_short_name;
            $this->request->data['country']                         = $country;
            $this->request->data['postal_code']                     = $pincode;
            $result                                                 = $this->Projects->getprojectestimationV2($this->request->data,$customer_id,$CreateMyProject);

            /** Update Project Ref. No in Table */
            if (empty($applyOnlinesData->project_id)) {
                $arrData    = array("project_id"=>$result['proj_id']);
                $this->ApplyOnlines->updateAll($arrData,['id' => $application_id]);
            }
            //$this->ApplyOnlines->updateAll(array('pv_capacity'=>$pv_app_capacity),['id' => $application_id]);
            
            /** Update Project Ref. No in Table */
            
            /** Send Query to Installer */
           // if ($SendQuery) $this->SendQueryToInstaller($result['proj_id'],$installer_id);
            /** Send Query to Installer */

            return $result;
        }
    }
    public function change_capacity()
    {
        $this->autoRender = false;
       // $application_id ="6,9,16,17,36,39,47,54,76,88,99,104,108,112,119,122,145,147,151,154,156,168,170,201,216,217,225,230,232,238,242,244,249,256,268,272,281,287,289,300,304,318,326,327,332,336,340,341,342,346,347,349,352,353,358,362,381,383,384,385,386,389,396,404,412,418,420,423,426,428,433,436,441,445,464,469,473,486,519,521,527,529,535,544,545,552,562,582,584,586,594,596,597,620,629,632,633,637,641,642,643,644,650,660,661,662,666,670,676,686,687,688,696,697,712,735,772,785,801,820,824,834,842,844,849,863,889,899,914,930,933,936,985,998,999,1002,1015,1032,1036,1042,1051,1077,1101,1108,1114,1123,1127,1129,1177,1200,1209,1217,1228,1233,1234,1237,1250,1266,1267,1298,1319,1344,1348,1355,1393,1398,1399,1415,1428,1437,1440,1451,1481,1501,1504,1511,1537,1546,1549,1582,1584,1602,1606,1610,1611,1635,1637,1645,1659,1665,1688,1690,1694,1697,1744,1745,1749,1750,1756,1763,1772,1773,1775,1778,1782,1797,1800,1829,1832,1838,1841,1845,1850,1858,1863,1879,1886,1901,1908,1933,1945,1948,2035,2037,2073,2078,2083,2091,2097,2100,2107,2110,2141,2150,2158,2162,2191,2227,2243,2255,2278,2304,2323,2334,2342,2356,2400,2411,2413,2416,2417,2436,2437,2441,2442,2445,2450,2473,2475,2486,2513,2521,2523,2546,2548,2566,2596,2646,2658,2677,2685,2687,2689,2711,2729,2748,2753,2766,2775,2779,2780,2789,2792,2805,2863,2888,2921,2951,2963,3020,3062,3088,3111,3120,3137,3143,3154,3157,3162,3190,3206,3226,3228,3244,3281,3292,3303,3326,3336,3363,3373,3406,3420,3429,3447,3467,3485,3494,3649,3766,3851,3882,3894,3944,3950,3951,3959,3996,4016,4050,4152,4176,4201,4214,4281,4341,4375,4441,4558,4634,4849,4857,4941,4949,4954,4966,4988,5154,5205,5212,5244,5253,5257,5262,5263,5265,5280,5287,5292,5308,5316,5364,5500,5636,5650,5796,5827,5850,5860,5869,5874,5955,5961,5987,6012,6035,6056,6073,6127,6173,6376,6710,6721,6833,6835,6848,6863,6866,7007,7026,7099,7102,7513,7645,7739,7752,7761,7771,7777,7799,7820,7871,7885,7890,7894,7897,8021,8053,8056,8080,8098,8460,8461,8463,8473,8484,8490,8512,8526,8536,8579,8608,8630,8659,8766,8838,8851,8869,8871,8887,8888,8891,8918,8927,8934,8948,8949,8974,8997,9018,9056,9068,9069,9094,9136,9142,9161,9209,9257,9283,9306,9333,9335,9381,9390,9442,9444,9498,9522,9543,9544,9545,9549,9550,9556,9568,9600,9619,9626,9630,9657,9678,9696,9708,9786,9796,9823,9877,9924,9951,10001,10190,10863,10876,10945,10966,11111,11116,11318,11581,11591,11841,11949,12127,12527,12545,12623,12649,12825,12833,13123,13481,13630,13748,13764,13772,13854,14375,14379,14816,15092,15494,15812,15890,16170,16232,16353,17110,17337,17382,18119,19377,21086,22235,22246";
        $application_id = "17337,17382,17543,17568,17888,17890,17898,17909,17916,17940,17961,17972,17997,18119,18127,18260,18301,18678,18810,18852,18906,19213,19216,19240,19248,19263,19372,19377,19390,19542,19558,19645,19726,19745,19943,20042,20047,20275,20492,20510,20619,21086,21927,22235,22246,22272,22273,22295,22316,23114,23383,23395,23896,23968,23973,23984,23987,24159,24201,24669,24930,25368,25379,25394,25486,25505,25521,25538,25564,25576,25585,25594,25605,25615,25626,25883,26347,27246,27393,27568,27613,28159,28166,28177,30443,30652,32171,32376,32418,32616,32768,35269,37591,37768,40264,41884";
        //$application_id         = "166";
        $sql="SELECT * from apply_onlines where id in ($application_id)";
        $connection             = ConnectionManager::get('default');
        $applicationData_output = $connection->execute($sql)->fetchAll('assoc');
        foreach($applicationData_output as $application)
        {
            $feasibility_data   = $this->FesibilityReport->find('all',array('conditions'=>array('application_id'=>$application['id'])))->first();
            $Project_ins        = $this->Installation->find('all',array('conditions'=>array('project_id'=>$application['project_id'])))->first();
            $capacity_rec       = $feasibility_data->recommended_capacity_by_discom;
            $modules_data       = unserialize($Project_ins->modules_data);
            $inverter_data      = unserialize($Project_ins->inverter_data);
            $total_commulative  = 0;
            $total_commulative_i= 0;
            for($i=1;$i<=3;$i++)
            {
                $row            = $i-1;
                $m_capacity     = '';
                $m_make         = '';
                $m_modules      = '';
                $m_type_modules = '';
                $m_type_other   = '';
                if (isset($modules_data[$row])) 
                {
                    $m_capacity         = $modules_data[$row]['m_capacity'];
                    $m_make             = $modules_data[$row]['m_make'];
                    $m_modules          = $modules_data[$row]['m_modules'];
                    $m_type_modules     = $modules_data[$row]['m_type_modules'];
                    $m_type_other       = $modules_data[$row]['m_type_other'];
                    $total_commulative  = $total_commulative + ($modules_data[$row]['m_capacity'] * $modules_data[$row]['m_modules']);
                }
            }
            for($i=1;$i<=3;$i++)
            {
                $row                  = $i-1;
                $i_capacity           = '';
                $i_make               = '';
                $i_make_other         = '';
                $i_modules            = '';
                $i_type_modules       = '';
                $i_type_other         = '';
                $i_phase              = '';
                if (isset($inverter_data[$row])) 
                {
                    $i_capacity         = $inverter_data[$row]['i_capacity'];
                    $i_make             = $inverter_data[$row]['i_make'];
                    $i_make_other       = $inverter_data[$row]['i_make_other'];
                    $i_modules          = $inverter_data[$row]['i_modules'];
                    $i_type_modules     = $inverter_data[$row]['i_type_modules'];
                    $i_type_other       = $inverter_data[$row]['i_type_other'];
                    if(isset($inverter_data[$row]['i_phase']))
                    {
                        $i_phase       = $inverter_data[$row]['i_phase'];
                    }
                    $total_commulative_i= $total_commulative_i + ($inverter_data[$row]['i_capacity']*$inverter_data[$row]['i_modules']);
                }
            }
            if ($total_commulative > 0) 
            {
                $total_commulative  = round(($total_commulative/1000),3);
            }
            if ($total_commulative_i > 0) 
            {
                $total_commulative_i  = round(($total_commulative_i),3);
            }
            //echo $application['id']."-->".$capacity_rec." - ".$total_commulative." - ".$total_commulative_i.'<br/>';
            $min_cap = min($capacity_rec,$total_commulative,$total_commulative_i);
            echo $application['id']."-->".$capacity_rec." - ".$total_commulative." - ".$total_commulative_i.'---'.$min_cap.'<br/>';
            $this->CreateMyProject($application['id'],true,$min_cap);
        }
        exit;
    }
}
