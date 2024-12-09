<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\ORM\TableRegistry;

use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;



class DashboardController extends AppController
{

	/**
	 *
	 * The status of $uses is universe
	 *
	 * Potential value is array of models to be inherited
	 *
	 * @public array
	 *
	 */
    public $uses 	= array('Ticket');
	/**
	* index
	* Behaviour : Public
	* @defination : default method redirect to home()
	*/
	public $user_helper  = '';
	/*public function beforeRender(Event $event)
    {
		parent::beforeRender($event);
        $this->user_helper = $this->getView()->helpers(['Userright']);
		
		$this->intCurAdminUserRight = $this->user_helper->Userright->ADMIN_HOME;
		$this->setAdminArea();
	}*/

	
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Projects');
        $this->loadModel('ApplyOnlines');
        $this->loadModel('Customers');
        $this->loadModel('Installers');
		$this->set('Userright',$this->Userright);
    }
	
	public function index()
	{
		$this->intCurAdminUserRight = $this->Userright->ADMIN_HOME;
        $this->setAdminArea();
		$this->set('project_count',$this->Projects->find('all')->count());
		$this->set('customer_count',$this->Customers->find('all')->count());
		$this->set('installers_count',$this->Installers->find('all')->count());
		$this->set('TodaysCustomer',$this->getTodayRegisteredCustomers());
		$this->set('TodaysCustomerCount',$this->getTodayRegisteredCustomersCount());
		
		$this->set('TodaysAplication',$this->getTodayAplication());
		$this->set('TotalAplication',$this->ApplyOnlines->find('all')->count());
		$this->set('TodaysAplicationCount',$this->getTodayAplicationCount());
		$this->set('RegisteredProjectsCount',$this->getTodayRegisteredProjectsCount());

		$this->set('getTypewiseProjects',$this->getTypewiseProjects());
		$this->set('ProjectHistoryCurrentYear',$this->getProjectHistoryCurrentYear());
		$this->set('getApplHistoryCurrentYear',$this->getApplHistoryCurrentYear());
		$this->set('TodaysProjects',$this->getTodayRegisteredProjects());
	}

	private function getTodayRegisteredCustomers()
	{
		$TodaysCustomer = $this->Customers->find();
		$TodaysCustomer->hydrate(false);
		$TodaysCustomer->select(['name', 'email', 'mobile']);
		$TodaysCustomer->where(function ($exp,$q) {
	        $StartTime	= date("Y-m-d",strtotime($this->NOW()))." 00:00:00";
			$EndTime	= date("Y-m-d",strtotime($this->NOW()))." 23:59:59";
			return $exp->between('Customers.created', $StartTime, $EndTime);
	    });
        $TodaysCustomer->order(['id'=>'DESC']);
        $TodaysCustomer->limit('10');
		$resultArray = $TodaysCustomer->toList();
		return $resultArray;
	}
    private function getTodayRegisteredCustomersCount()
    {
        $TodaysCustomerCount = $this->Customers->find('all')->where(function ($exp,$q) {
            $StartTime  = date("Y-m-d",strtotime($this->NOW()))." 00:00:00";
            $EndTime    = date("Y-m-d",strtotime($this->NOW()))." 23:59:59";
            return $exp->between('Customers.created', $StartTime, $EndTime);
        })->count();
        return $TodaysCustomerCount;
    }
	private function getTodayAplication()
	{
		$TodaysApply = $this->ApplyOnlines->find();
		$TodaysApply->hydrate(false);
		$TodaysApply->select(['name_of_consumer_applicant','pv_capacity', 'email', 'mobile']);
		$TodaysApply->where(function ($exp,$q) {
	        $StartTime	= date("Y-m-d",strtotime($this->NOW()))." 00:00:00";
			$EndTime	= date("Y-m-d",strtotime($this->NOW()))." 23:59:59";
			return $exp->between('created', $StartTime, $EndTime);
	    });
        $TodaysApply->limit('10');
        $TodaysApply->order(['id'=>'DESC']);

		$resultArray = $TodaysApply->toList();
		return $resultArray;
	}
     private function getTodayAplicationCount()
    {
        $TodaysApplyCount = $this->ApplyOnlines->find()->where(function ($exp,$q) {
            $StartTime  = date("Y-m-d",strtotime($this->NOW()))." 00:00:00";
            $EndTime    = date("Y-m-d",strtotime($this->NOW()))." 23:59:59";
            return $exp->between('created', $StartTime, $EndTime);
        })->count();
        return $TodaysApplyCount;
    }

	private function getTypewiseProjects()
	{
		$resultArray 		= array();
		$TypewiseProjects 	= $this->Projects->find();
		$TypewiseProjects->hydrate(false);
		$TypewiseProjects->select(['Parameters.para_value','count' => $TypewiseProjects->func()->count('Projects.id')])->group('Projects.customer_type');
		$TypewiseProjects->join([
							        'table' => 'parameters',
							        'alias' => 'Parameters',
							        'type' => 'INNER',
							        'conditions' => 'Parameters.para_id = Projects.customer_type',
							    ]);
		$arrResult = $TypewiseProjects->toList();
		if (!empty($arrResult)) {
			foreach ($arrResult as $Row) {
				$resultArray[$Row['Parameters']['para_value']] = $Row['count'];
			}
		}
		return $resultArray;
	}

	private function getProjectHistoryCurrentYear()
	{
		$Projects = $this->Projects->find();
		$Projects->hydrate(false);
		$Projects->select(['Project_Month' => 'MONTH(Projects.created)','count' => $Projects->func()->count('Projects.id')])->group('Project_Month');
		$Projects->where(function ($exp,$q) {
	        $StartTime	= date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
			$EndTime	= date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
			return $exp->between('Projects.created', $StartTime, $EndTime);
	    });
	    $resultArray = $Projects->toList();
	    if (!empty($resultArray)) {
	    	$arrResult = array();
	    	foreach ($resultArray as $resultRow) {
	    		$MONTH 				= strtoupper(date("M",strtotime(date("Y",strtotime($this->NOW()))."-".$resultRow['Project_Month']."-01 00:00:00")));
	    		$arrResult[$MONTH] 	= $resultRow['count'];
	    	}
	    	$resultArray = $arrResult;
		}
		return $resultArray;
	}
	private function getApplHistoryCurrentYear()
	{
		$ApplyOnlines = $this->ApplyOnlines->find();
		$ApplyOnlines->hydrate(false);
		$ApplyOnlines->select(['Apply_Month' => 'MONTH(ApplyOnlines.created)','count' => $ApplyOnlines->func()->count('ApplyOnlines.id')])->group('Apply_Month');
		$ApplyOnlines->where(function ($exp,$q) {
	        $StartTime	= date("Y",strtotime($this->NOW()))."-01-01 00:00:00";
			$EndTime	= date("Y",strtotime($this->NOW()))."-12-31 23:59:59";
			return $exp->between('ApplyOnlines.created', $StartTime, $EndTime);
	    });
	    $resultArray = $ApplyOnlines->toList();
	    if (!empty($resultArray)) {
	    	$arrResult = array();
	    	foreach ($resultArray as $resultRow) {
	    		$MONTH 				= strtoupper(date("M",strtotime(date("Y",strtotime($this->NOW()))."-".$resultRow['Apply_Month']."-01 00:00:00")));
	    		$arrResult[$MONTH] 	= $resultRow['count'];
	    	}
	    	$resultArray = $arrResult;
		}else{
			$resultArray = array();
		}
		return $resultArray;
	}

	private function getTodayRegisteredProjects()
	{
		$resultArray 	= array();
		$Projects 		= $this->Projects->find();
		$Projects->hydrate(false);
		$Projects->select(['Projects.id','Projects.name','Customers.mobile','Projects.city','Parameters.para_value']);
		$Projects->join(['Parameters'=>[
								        'table' => 'parameters',
								        'type' => 'INNER',
								        'conditions' => 'Parameters.para_id = Projects.customer_type'],
						'CustomerProjects'=>[
								        'table' => 'customer_projects',
								        'type' => 'INNER',
								        'conditions' => 'CustomerProjects.project_id = Projects.id'],
						'Customers'=>[
								        'table' => 'customers',
								        'type' => 'INNER',
								        'conditions' => 'Customers.id = CustomerProjects.customer_id'],        
					    ]);
		$Projects->where(function ($exp,$q) {
	        $StartTime	= date("Y-m-d",strtotime($this->NOW()))." 00:00:00";
			$EndTime	= date("Y-m-d",strtotime($this->NOW()))." 23:59:59";
			return $exp->between('Projects.created', $StartTime, $EndTime);
	    });
        $project_count              = $Projects->toList();
        $Projects->limit('10');
		$arrResult 					= $Projects->toList();
		$blnEditAdminuserRights		= $this->Userright->checkadminrights($this->Userright->LIST_PROJECT);
		if (!empty($arrResult)) {
			foreach ($arrResult as $Row) {
				$ProjectName = $this->Userright->linkListProjects(constant('WEB_URL').constant('ADMIN_PATH').'projects/view/'.encode($Row['id']),$Row['name'],'','rel="viewRecord" target="_blank" ','title="View Project"');
				$resultArray[] = array(	"name" => (!empty($ProjectName)?$ProjectName:$Row['name']),
										"mobile"=> $Row['Customers']['mobile'],
										"city"=>$Row['city'],
										"customer_type"=>$Row['Parameters']['para_value']);
			}
		}
		return $resultArray;
	}

    private function getTodayRegisteredProjectsCount()
    {
        $resultCount    = array();
        $resultCount    = $this->Projects->find('all',['join'=>['Parameters'=>[
                                        'table' => 'parameters',
                                        'type' => 'INNER',
                                        'conditions' => 'Parameters.para_id = Projects.customer_type'],
                        'CustomerProjects'=>[
                                        'table' => 'customer_projects',
                                        'type' => 'INNER',
                                        'conditions' => 'CustomerProjects.project_id = Projects.id'],
                        'Customers'=>[
                                        'table' => 'customers',
                                        'type' => 'INNER',
                                        'conditions' => 'Customers.id = CustomerProjects.customer_id'],        
                        ]])->where(function ($exp,$q) {
                            $StartTime  = date("Y-m-d",strtotime($this->NOW()))." 00:00:00";
                            $EndTime    = date("Y-m-d",strtotime($this->NOW()))." 23:59:59";
                            return $exp->between('Projects.created', $StartTime, $EndTime);
                        })->count();
        //$resultCount;exit;
        return $resultCount;
    }
}
?>