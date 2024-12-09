<?php
namespace App\Controller;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
use App\Controller\AppController;
use Cake\View\View;
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
use Cake\Datasource\ConnectionManager;
use Dompdf\Dompdf;


class DownloadPdfsController extends FrontAppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array();
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	public $CUSTOMER_STATE_ID = 4;
	public $paginate = [
		'limit' => PAGE_RECORD_LIMIT,
		'order' => [
			'ApplyOnlines.id ' => 'desc'
		]
	];

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

		$this->loadModel('ApiToken');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('DiscomMaster');
		$this->loadModel('FesibilityReport');
		$this->loadModel('RegistrationScheme');
		$this->loadModel('RegistrationSchemeDocument');
		$this->loadModel('WorkCompletion');
		$this->loadModel('WorkCompletionDocument');
		$this->loadModel('ChargingCertificate');
		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Adminaction');
		$this->loadModel('Parameters');
		$this->loadModel('BranchMasters');
		$this->loadModel('Members');
		$this->loadModel('Installers');
		$this->loadModel('Customers');
		$this->loadModel('States');
		$this->loadModel('Sessions');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('CustomerProjects');
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyonlinePayment');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('InstallerCategory');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('ApplicationDeleteLog');
		$this->loadModel('SpinWebserviceApi');
		$this->loadModel('UpdateDetails');
		$this->loadModel('UpdateDetailsApplicationsLog');
		$this->loadModel('UpdateCapacity');
		$this->loadModel('UpdateCapacityApplicationsLog');
		$this->loadModel('UpdateCapacityProjectsLog');
		$this->loadModel('Subsidy');
		$this->loadModel('ApplyOnlinesOthers');
		$this->loadModel('Inspectionpdf');
		$this->loadModel('Emaillog');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('ApplyonlineUnReadMessage');
		$this->loadModel('EnergyGenerationLog');
		$this->loadModel('ApplicationRequestDelete');
		$this->loadModel('SolarTypeLog');
		$this->loadModel('SendRegistrationFailure');
		$this->loadModel('Couchdb');
		$this->loadModel('Developers');
		$this->loadModel('DeveloperCustomers');
		$this->loadModel('UpdateDiscomDataLog');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->set('Userright',$this->Userright);

		$customer_type 	= $this->Session->read('Customers.customer_type');
		$this->set("customer_type",$customer_type);

		$member_type 	= $this->Session->read('Members.member_type');
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');

		$this->set("JREDA",$this->ApplyOnlines->JREDA);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("CEI",$this->ApplyOnlines->CEI);
		$this->set("MStatus",$this->ApplyOnlineApprovals);
		$this->set("member_type",$member_type);
		$this->set("area",$area);
		$this->set("circle",$circle);
		$this->set("division",$division);
		$this->set("subdivision",$subdivision);
		$this->set("section",$section);

		$is_installer = false;
		if ($customer_type == "installer") {
			$is_installer = true;
		}
		$this->set("is_installer",$is_installer);
		$this->set("customer_types",array("customer","installer"));

		if (BLOCK_APPLICATION == 1)
		{
			if ($customer_type == "installer") {
				$this->Flash->set(BLOCK_APPLICATION_MESSAGE,['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
				return $this->redirect('installer-dashboard');
			} else if ($member_type == $this->ApplyOnlines->JREDA) {
				return $this->redirect('member');
			}  else if ($member_type == $this->ApplyOnlines->DISCOM) {
				return $this->redirect('member');
			} else if ($member_type == $this->ApplyOnlines->CEI) {
				return $this->redirect('member');
			} else {
				return $this->redirect('home');
			}
		}

		if($this->request->params['action']=='index' || $this->request->params['action']=='trackapplication')
		{
			//$this->loadComponent('Csrf');
		}
	}
	/**
	 * undertaking
	 * Behaviour : public
	 * @param : id  : Id is use to identify for which site PDF file should be downlaoded, $isdownload=true
	 * @defination : Method is use to download .pdf file from modal popup of applyonline listing
	 *
	 */
	public function undertaking()
	{

		$this->layout 		= false;
		$isdownload  = 0;
		
		/* Generate PDF for estimation of project */

		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';

		//$options = new Options();
		//$options->set('enable_html5_parser', true);
		//$options['enable_html5_parser'] = true;
		$dompdf = new Dompdf($options = array());
		
		//$dompdf = new Dompdf($options = array());
		$dompdf->set_option("isPhpEnabled", true);
		//$dompdf->set_option("enable_html5_parser", true);

		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		$html = $this->render('/Element/undertaking');
		
		$dompdf->loadHtml($html,'UTF-8');

		$dompdf->setPaper('A4', 'portrait');

		@$dompdf->render();

		// Output the generated PDF to Browser
		if($isdownload){
			$dompdf->stream('undertaking');	
		}
		$output = $dompdf->output();
		
			header("Content-type:application/pdf");
			header("Content-Disposition:inline;filename=undertaking.pdf");
			echo $output;
			die;
		
		
	}
}