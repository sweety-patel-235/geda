<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Dompdf\Dompdf;

class StaticController extends FrontAppController
{
	public function initialize()
    {
        parent::initialize();
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

    public function isAuthorized($user)
    {  
        // Default deny
        return true;
    }
	
	public function about_us()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','About Us');
	}

	public function terms()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','Terms and Conditions');
	}

	public function whatit_cost()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','Important Documents');
	}

	public function solar_installer()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','Solar PV Installers');
	}

	public function faq()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','FAQ');
	}

	public function mfaq()
	{
		$this->layout = 'mobile';
		$this->view = 'faq';
		$this->set('pageTitle','FAQ');
	}

	public function contact_us()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','Contact Us');
	
		$contactusEntity = $this->Contactus->newEntity($this->request->data,['validate' => 'Contactus']);
		$arrAdminDefaultRights = array();
		$timezone = '';
		$arrError = array();
		if(!$contactusEntity->errors() && !empty($this->request->data)) {
			$contactusEntity->created 		= $this->NOW();
			/*Upload Product Image*/
			if ($this->Contactus->save($contactusEntity)) { 
				
				$name 			= (isset($contactusEntity->name)?$contactusEntity->name:'');
				$emailId 		= (isset($contactusEntity->email)?$contactusEntity->email:'');
				$mobile 		= (isset($contactusEntity->mobile)?$contactusEntity->mobile:'');
				$message 		= (isset($contactusEntity->message)?$contactusEntity->message:'');
				
				$to			=  explode(',',SEND_QUERY_EMAIL);
				//$to			=  'pravin.sanghani@yugtia.com';
				$subject	= PRODUCT_NAME." Contact Us";
				$email 		= new Email('default');
				$email->profile('default');
				$email->viewVars(array('name' => $name,'emailId' => $emailId,'mobile' =>$mobile, 'message' =>$message));
				$email->template('contact_us', 'default')
				->emailFormat('html')
				->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
			    ->to($to)
			    ->subject(Configure::read('EMAIL_ENV').$subject)
			    ->send();
				$this->Flash->success('Your message has been successfully sent. We will contact you very soon!..');
				return $this->redirect('contact-us');
            }
		}
		$this->set('contactusEntity', $contactusEntity);
	}

	public function news()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','News');
		$keyword = "Solar Energy in India";
		$news = simplexml_load_file('https://news.google.co.in/news?pz=1&q='.$keyword.'&scoring=n&cf=all&sort=newest&num=30&output=rss');
		//$news = simplexml_load_file('https://news.google.com/news/rss/search/section/q/$keyword/$keyword?hl=en-IN&gl=IN&ned=in');
		$this->set(compact("news"));

	}

	/**
	 *
	 * saveSubscriber
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use save subscriber email.
	 *
	 */
	public function saveSubscriber()
	{
		$this->response->header('Access-Control-Allow-Origin', '*');
		$this->autoRender = false;
		
		$subscriberEntity = $this->Subscribers->newEntity();
   		$this->request->data['email'] 	= $this->request->query['email'];
        if(!empty($this->request->data['email']) && isValidEmail($this->request->data['email'])) {
        	$subscribercnt	= $this->Subscribers->find('all', array('conditions'=>array('email'=>$this->request->data['email'])))->count();
          	if($subscribercnt == 0) {
           		$this->Subscribers->patchEntity($subscriberEntity, $this->request->data);
	            $subscriberEntity->created = $this->NOW();
	            if($this->Subscribers->save($subscriberEntity)) {
	                echo "Thank you for your subscription";
	            }
	            else {
	                echo "Error in subscription";
	            }
	        } else {
	        	echo "You have already subscribe with us";
	        }
        } else {
        	echo "Please enter valid email.";
        }        
	}

	/**
	 *
	 * importantDocument
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use important document api.
	 *
	 */
	public function importantDocument() {
		$this->layout = 'empty';
	}

	public function applyonline()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','How to Apply Online');
	}

	public function mapplyonline()
	{
		$this->layout = 'mobile';
		$this->view = 'applyonline';
		$this->set('pageTitle','How to Apply Online');
	}
	/**
	 *
	 * cron_send_survey
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to send mail to customer with pdf or excel file attachment
	 *
	 */
	public function cron_send_survey()
	{
		$this->autoRender 		= false;
		$arr_request_customer 	= $this->SitesurveyProjectRequest->find('all',array('conditions' => array('send_status'=>'0')))->toArray();
		$customer_count 		= 0;
		foreach($arr_request_customer as $val)
		{
			$arr_customer= $this->Customers->find('all',array('conditions'=>array('id'=>$val['requested_by'])))->first();
			$arr_project = $this->Projects->find('all',array('conditions'=>array('id'=>$val['project_id'])))->first();
			if($val['project_id'] > 0)
			{
				if($val['request_file']=='pdf')
				{
					$pdfPath 	= $this->genratesurveyPDFreport($val['project_id'],false,1);
				}	
				if($val['request_file']=='excel')
				{
					$result_data=$this->SiteSurveys->find('all',array('conditions' => array('project_id'=>$val['project_id'])))->toArray();
			        $arr_projets_data=$this->Projects->find('all',array('fields'=>array('Projects.name'),
			            'conditions'=>array('id'=>$val['project_id'])))->first();
			        $project_name='';
			        if(!empty($arr_projets_data))
			        {
			            $project_name 	= $arr_projets_data['name'];
			            $arr_installer 	= $this->InstallerProjects->find('all',array('conditions' => array('project_id'=>$val['project_id'])))->toarray();
			        }
			        $all_area_types     = $this->SiteSurveys->AREA_PARAMS;
			        $all_area_type_smp  = $this->SiteSurveys->AREA_PARAMS_SMP;
			        $all_load           = $this->SiteSurveys->LOAD_PARAMS;
			        $all_meter          = $this->SiteSurveys->ALL_METER_TYPE;
			        $all_meter_accuracy = $this->SiteSurveys->ALL_METER_ACCURACY_CLASS;
			        $all_roof           = $this->SiteSurveys->ALL_ROOF_TYPE;
			        $all_roof_st        = $this->SiteSurveys->ALL_ROOF_STRENGTH;
			        $all_billing        = $this->SiteSurveys->ALL_BILLING_CYCLE;
			        $pdfPath = $this->survey_download_xls($result_data, $project_name, $all_area_types, $all_area_type_smp, $all_load, $all_meter, $all_meter_accuracy, $all_roof, $all_roof_st, $all_billing,'1');
				}
		        $to			= $arr_customer['email'];
				$subject	= "Site survey report for project ".$arr_project['name']." - Id : ".$arr_project['id'];
				$email 		= new Email('default');
			 	$email->profile('default');

				$email->viewVars(array('CUSTOMER_NAME' => $arr_customer['name'],'PROJECT_NAME' => $arr_project['name'],'PROJECT_ID' => $arr_project['id']));			
				$email->template('sitesurvey_project_survey', 'default')
						->emailFormat('html')
						->from(array('info@ahasolar.in' => PRODUCT_NAME))
					    ->to($to)
					    ->attachments($pdfPath)
					    ->subject(Configure::read('EMAIL_ENV').$subject)
					    ->send();
				$this->SitesurveyProjectRequest->updateAll(array('send_status'=>'1'),array('id'=>$val['id']));
				$customer_count++;
			}
		}
		echo 'Mail sent to total '.$customer_count.' customer';
		exit;
	}
	public function privacy_policy()
	{
		$this->layout = 'frontend';
		$this->set('pageTitle','Privacy Policy');
	}
}
?>