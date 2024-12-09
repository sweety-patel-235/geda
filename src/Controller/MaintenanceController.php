<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Event\Event;
use Cake\Utility\Security;

class MaintenanceController extends FrontAppController
{
	public function initialize()
    {
        parent::initialize();
    }
	public function index()
	{
		$this->layout = 'frontend';
		$this->set('page_title','GEDA | Unified Single Window Rooftop PV Portal');
	}
}