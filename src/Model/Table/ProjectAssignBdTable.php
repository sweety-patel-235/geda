<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Controller\AppController;

class ProjectAssignBdTable extends AppTable
{
	var $table = 'project_assign_bd';
	var $data  = array();

    public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    /* Created by Sachin Patel
     * Projectid
     * customer id
     * Purpose : If custormer already selected previosly, the will return 0 or 1
     * */

    public function getSelectedCustomer($projecId=null,$customerId=null){
        $selected = 0;
        if($projecId !="" && $customerId !="") {
            $getselected = $this->find('all', array('conditions' => array("customers_id" => $customerId,"projects_id"=>$projecId)))->count();
            if(isset($getselected) && $getselected > 0 ){
                $selected = 1;
            }

        }
        return $selected;
    }

    /* Created by Sachin Patel
     * Purpose : Leads After convert project enquiry
     * Project ID : Project id assign to BD
     * installerId : Login user Installer Id
     * CustomerId : Login user id
     * */

    public function assignBD($projectID=null,$installerId=null, $CustomerId=null){
        $this->Customers = TableRegistry::get('Customers');
        $this->Parameters = TableRegistry::get('Parameters');

        if($installerId !=0){
            $getdata = $this->find('all', array('conditions' => array("projects_id"=>$projectID)))->count();
            if(empty($getdata)) {
                $assingBD = array();
                $allcustomer = $this->Customers->find('all', array('conditions' => ['installer_id' => $installerId, 'Customers.user_role Like' => "%" . $this->Parameters->bd_role . "%"]))->toArray();
                if(!empty($allcustomer)){
                    foreach ($allcustomer as $key => $customer){
                            $assingBD['projects_id']    = $projectID;
                            $assingBD['customers_id']   = $customer->id;
                            $assingBD['created_by']     = $CustomerId;
                            $abd                        = $this->newEntity($assingBD);
                            $abd->created               =  AppController::NOW();
                            $this->save($abd);
                    }
                }
            }
        }
    }
}
?>