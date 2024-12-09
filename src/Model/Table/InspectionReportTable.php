<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for InspectionReportData. It extends Table Class
 * @category  Class File
 * @Desc      Manage InspectionReportData
 * @author    Kalpak Prajapati
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class InspectionReportTable extends AppTable
{
	public function initialize(array $config)
    {
        $this->table('application_inspection_report');
    }

    /**
     * saveInspectionReport
     * Behaviour : Public
     * @defination : Method is use to save Inspection Report.
     */
    public function saveInspectionReport($stage,$data,$Member_ID)
    {
        $appid                              = intval(decode($data['appid']));
        $conditions                         = array('application_id'=>$appid,'inspection_type'=>$stage);
        $InspectionReportRow                = $this->find('all',array('conditions'=>$conditions))->first();
        $data['que']['reason']              = isset($data['reason'])?$data['reason']:"";
        $data['que']['application_status']  = isset($data['application_status'])?$data['application_status']:"";
        if(!empty($InspectionReportRow))
        {
            $InspectionReport                   = $this->patchEntity($InspectionReportRow,array());
            $InspectionReport->inspection_data  = serialize($data['que']);
            $InspectionReport->updated          = date("Y-m-d H:i:s");
            $InspectionReport->updated_by       = $Member_ID;
            $this->save($InspectionReport);
        }
        else
        {
            $InspectionReport                           = $this->newEntity();
            $InspectionReport->application_id           = $appid;
            $InspectionReport->inspection_type          = $stage;
            $InspectionReport->inspection_report_date   = date("Y-m-d H:i:s");
            $InspectionReport->inspection_data          = serialize($data['que']);
            $InspectionReport->created                  = date("Y-m-d H:i:s");
            $InspectionReport->created_by               = $Member_ID;
            $InspectionReport->updated                  = date("Y-m-d H:i:s");
            $InspectionReport->updated_by               = $Member_ID;
            $this->save($InspectionReport);
        }
    }

    /**
     * getInspectionReport
     * Behaviour : Public
     * @defination : Method is use to get Inspection Report.
     */
    public function getInspectionReport($stage=0,$application_id="")
    {
        $appid                  = intval(decode($application_id));
        $conditions             = array('application_id'=>$appid,'inspection_type'=>$stage);
        $InspectionReportRow    = $this->find('all',array('conditions'=>$conditions))->first();
        return $InspectionReportRow;
    }
}
?>