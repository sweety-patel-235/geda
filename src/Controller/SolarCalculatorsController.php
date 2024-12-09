<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;

class SolarCalculatorsController extends FrontAppController
{
	public $helpers = ['Session'];
	public function initialize()
    {
       	//parent::initialize();
    	$this->loadComponent('Flash'); 
    	$this->loadModel('Projects');
    	$this->loadModel('Parameters');
    	$this->session = $this->request->session();
    }

    /**
	*
	* solar_calculator
	*
	* Behaviour : public
	*
	* @defination : Method for solar calculator page.
	*
	* Author : Khushal Bhalsod
	*/
	public function solar_calculator()
	{
		$this->layout = 'frontend';
		$customerId = 0;
		$customerId = $this->session->read('Customers.id');	
		$this->set('pageTitle','Solar Calculator');
		$this->set('projectTypeArr',$this->Parameters->getProjectType());
		$this->set('backupTypeArr',$this->Projects->backupTypeArr);
		$this->set('areaTypeArr',$this->Parameters->getAreaType());
		$this->set('customerId',$customerId);
		
		if($this->session->check('solarCalculatorRes')) {
			$this->session->delete('solarCalculatorRes');
			$this->set('result',$this->session->read('solarCalculatorRes'));
		}
		if(!empty($this->request->data)) {
			$resultArr = $this->Projects->getprojectestimation($this->request->data,$customerId);			
			$this->session->write('solarCalculatorRes',$resultArr);
			$this->redirect(['controller' => 'SolarCalculators', 'action' => 'solar_calculator_result/'.encode($resultArr['proj_id'])]);
		}		
	}

	/**
	*
	* solar_calculator_result
	*
	* Behaviour : public
	*
	* @defination : Method for display solar calculator result.
	*
	* Author : Khushal Bhalsod
	*/
	public function solar_calculator_result($project_id=0)
	{
		$this->layout 	= 'frontend';
		$this->set('pageTitle','Solar Calculator');	
		$customerId 	= 0;
		$customerId 	= $this->session->read('Customers.id');
		$this->set('customerId',$customerId);
		$this->set('project_id',$project_id);
		$projectData 	= array();
		if(decode($project_id)>0)
		{
		$projectData 	= $this->Projects->get(decode($project_id));
		}
 		$ProjectsErrors = '';
		if(isset($this->request->data['submit']))
		{
			if(empty($this->request->data['project_name']))
			{
				$ProjectsErrors 	= '<div class="help-block">Project name can not be blank.</div>';
			}
			else
			{
				$projectEntity  	= $this->Projects->patchEntity($projectData,$this->request->data);
 				$projectEntity->name= $this->request->data['project_name'];
 				$this->Projects->save($projectEntity);
 				$this->Flash->success('Project Added Successfully.');
				$this->redirect(['controller' => 'SolarCalculators', 'action' => 'solar_calculator_result/'.$project_id]);
			}
		}
		$this->set('ProjectsErrors',$ProjectsErrors);
		$this->set('projectData',$projectData);
		if($this->session->check('solarCalculatorRes')) {
			$this->set('result',$this->session->read('solarCalculatorRes'));
		} else {
			$this->redirect(['controller' => 'SolarCalculators', 'action' => 'solar_calculator']);
		}
	}
}
?>