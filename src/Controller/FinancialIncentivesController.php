<?php
namespace App\Controller;
use App\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;

class FinancialincentivesController extends FrontAppController 
{	
	
	
	var $helpers = array('Time','Html','Form','ExPaginator');

    /*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
    {
      	parent::initialize();
		
    }

	/*
	 * Displays a index
	 *
	 * @param mixed What page to display
	 *
	 * @return void
	 */
	public function index() {
		
		
		$this->set('pageTitle','Financial Incentives Listing');
		
	}

	
}