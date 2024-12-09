<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

class LeadsNotesTable extends AppTable
{
	var $table = 'leads_notes';
	var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    public function getAllNotes($leadId=null){
        $notesArray = array();
        if($leadId !="") {
            $notes = $this->find('all',['join' => [
                        'customers' => [
                            'table' => 'customers',
                            'type' => 'LEFT',
                            'conditions' => ['customers.id = LeadsNotes.created_by']
                        ]],
                        'fields' => array('customers.name', 'LeadsNotes.notes', 'LeadsNotes.created'),
                        'conditions' =>array("leads_id" => $leadId),
                        'order'=>array('LeadsNotes.created'=>'DESC')])->toArray();

            if(!empty($notes)){
                foreach ($notes as $key => $value){
                    $notesArray[] = array('notes'=>$value['notes'], 'created_date'=>date("d-m-Y h:i A", strtotime($value['created'])),'created_name'=>$value['customers']['name']);
                }
            }
        }
        return $notesArray;
    }

    public function getLatestNotes($leadId=null,$limit=5){
        $notesArray = array();
        if($leadId !="") {
            $notes = $this->find('all',['join' => [
                        'customers' => [
                            'table' => 'customers',
                            'type' => 'LEFT',
                            'conditions' => ['customers.id = LeadsNotes.created_by']
                        ]],
                        'fields' => array('customers.name', 'LeadsNotes.notes', 'LeadsNotes.created'),
                        'conditions' =>array("leads_id" => $leadId),
                        'order'=>array('LeadsNotes.created'=>'DESC'),
                        'limit'=>$limit
                    ])->toArray();

            if(!empty($notes)){
                foreach ($notes as $key => $value){
                    $notesArray[] = array('notes'=>$value['notes'], 'created_date'=>date("d-m-Y h:i A", strtotime($value['created'])),'created_name'=>$value['customers']['name']);
                }
            }
        }
        return $notesArray;
    }
}
?>