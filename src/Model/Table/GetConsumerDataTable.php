<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Datasource\ConnectionManager;

class GetConsumerDataTable extends AppTable
{
    var $table = 'get_consumer_data';
    var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);         
    }
    
    
}