<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Email\Email;

class TermsController extends AppController
{

	public function initialize()
    {
        parent::initialize();
    }
	
	public function index()
	{
		$this->layout = 'empty';
	}
}
?>