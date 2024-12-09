<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class CronApiProcessTable extends AppTable
{
	var $table  = 'cron_api_process';
    
    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    public function saveAPILog($application_id,$action) 
    {
        $conditions = array('action'=>$action);
        $Count      = $this->find('all',['conditions'=>$conditions])->count();
        if ($Count == 0)
        {
            $newEntity                      = $this->newEntity();
            $newEntity->application_id      = $application_id;
            $newEntity->action              = $action;
            $newEntity->created             = $this->NOW();
            return $this->save($newEntity);
        } else {
            $arrData = array("application_id"=>$application_id,'updated'=>$this->NOW());
            $this->updateAll($arrData,$conditions);
        }
    }

    public function GetLastRowID($action) 
    {
        $LastRowID      = 0;
        $conditions     = array('action'=>$action);
        $GetLastRowID   = $this->find('all',['conditions'=>$conditions])->first();
        if (!empty($GetLastRowID))
        {
            return $GetLastRowID->application_id;
        }
        return $LastRowID;
    }
}
?>