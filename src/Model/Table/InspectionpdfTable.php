<?php

namespace App\Model\Table;
use App\Model\Table\Entity;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

use Cake\Core\Configure;
use Cake\Event\Event;

class InspectionpdfTable extends AppTable
{
	var $table = 'inspection_pdf';
	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }

    /**
     * IsInspectionDone
     * Behaviour : public
     * @param : $ApplicationId
     * @defination : Method is use to find weather inspection done or not for gievn application id
     */
    public function IsInspectionDone($ApplicationId) 
    {
    	$InspectionPDF 		= '';
    	$arrInspectionData 	= $this->find('all',array('conditions'=>array('application_id'=>$ApplicationId)))->first();
    	if(!empty($arrInspectionData))
    	{
    		if(!empty($arrInspectionData->pdf_file))
    		{
    			$DOCUMENT_PATH  	= WWW_ROOT.INSPECTION_PATH.$ApplicationId."/".$arrInspectionData->pdf_file;
    			if(file_exists($DOCUMENT_PATH))
    			{
    				$InspectionPDF = INSPECTION_URL.$ApplicationId."/".$arrInspectionData->pdf_file;
    			}
    		}
    	}
    	return $InspectionPDF;
    }
}
?>