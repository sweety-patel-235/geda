<?php
namespace App\Controller\Installer;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;


class ProjectsController extends InstallerAppController
{
    /*public $paginate = [
        'limit' => PAGE_RECORD_LIMIT,
        'order' => [
            'Events.event_title' => 'asc'
        ]
    ];*/

	public function initialize()
    {
        parent::initialize();

		$this->loadModel('Customers');
        $this->loadModel('Installers');
        $this->loadModel('Projects');
        $this->loadModel('ProjectLeads');
        $this->loadModel('Parameters');
        
    }

    
    /**
    *
    * List all installer projects
    */
    public function index($projectType = null)
    {
        $this->set("pageTitle","Projects");
        $projectType = intval(decode($projectType));
        $installerId = $this->Auth->user('installer_id');
        $projects = $this->ProjectLeads->getInstallerProjects($installerId,['projectType' => $projectType]);
         
        $projectTypeList = $this->Parameters->getProjectType();
        //prd($projectTypeList);

        $this->set('projectLeads',$this->paginate($projects));
        $this->set(compact("projectTypeList","projectType"));
    } 

    /**
    *
    * List all projects on which installer received leads
    */
    public function leads($type = 'pending')
    {
        $this->set("pageTitle","Customer Leads");
        $installerId = $this->Auth->user('installer_id');
        $projects = $this->ProjectLeads->getProjectLeads($installerId,$type);
        

        $this->set('projectLeads',$this->paginate($projects));
        $this->set(compact("type"));
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
        $installerId = $this->Auth->user('installer_id');
        
        $installersData = $this->Installers->find("all");
        $installers = $this->paginate($installersData);

        $this->set(compact("projectLead","installers"));
    }

    /**
    *
    * Project Details Page for installer
    *
    * @param : $id: Project Id 
    */
    public function view($id = null)
    {
        $installerId = $this->Auth->user('installer_id');
        $projectId    = intval(decode($id));
        $project  = $this->Projects->findById($projectId)->contain("Customers")->first();
        $this->set("pageTitle","Project: ".$project->name);
        
        $this->set(compact("project"));
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
	

}