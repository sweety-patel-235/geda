<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Dompdf\Dompdf;

class StaticController extends AppController
{
	public function initialize()
    {
       // parent::initialize();
    	$this->loadComponent('Flash');
    	$this->loadComponent('PhpExcel');
    	$this->loadModel('Contactus');           
    	$this->loadModel('Subscribers');
    	$this->loadModel('SitesurveyProjectRequest');
    	$this->loadModel('Customers');
    	$this->loadModel('Projects');
    	$this->loadModel('SiteSurveys');
    	$this->loadModel('InstallerProjects');
    	$this->loadModel('SiteSurveysImages');
    	$this->loadModel('InstallerSubscription');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        //$this->Auth->allow(['register', 'logout','index']);
    }

  
	public function terms()
	{
		$this->layout = 'mobile';
        $this->view = 'terms';
		$this->set('pageTitle','Terms and Conditions');
	}
	public function mfaq()
	{
		$this->layout = 'mobile';
		$this->view = 'faq';
		$this->set('pageTitle','FAQ');
	}

	
}
?>