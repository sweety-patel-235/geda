<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;
class ApplyOnlinesErrorsLogTable extends AppTable
{
    var $table  = 'apply_onlines_errors_log'; 
    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
}