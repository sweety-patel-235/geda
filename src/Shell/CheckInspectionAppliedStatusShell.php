<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     3.0.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Shell;

use App\Controller\AppController;
use Cake\Console\Shell;
use Cake\Network\Email\Email;
use Dompdf\Dompdf;
use Cake\Core\Configure;

class CheckInspectionAppliedStatusShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
        $this->loadModel('CeiApplicationDetails');
        $this->loadModel('ThirdpartyApiLog');
		$this->loadModel('Members');
    }

    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent 		= 1;
        $GUJARAT_STATE 		= 4;
        $LIMIT_QUERY_SENT	= 3;
        $application_status = array($this->ApplyOnlineApprovals->CEI_APP_NUMBER_APPLIED);
        $arrApplications 	= $this->ApplyOnlines->find('all',
										[	'fields'  		=> ["id","division"],
											'conditions'	=> ['application_status IN'=>$application_status,'apply_state'=>$GUJARAT_STATE]
										]
									);

        if (!empty($arrApplications))
        {
			foreach($arrApplications as $arrApplication)
			{
                $exist_cei          = $this->CeiApplicationDetails->find('all',array('conditions'=>array('application_id'=>$arrApplication->id)))->first();
                if(!empty($exist_cei))
                {
                    $cei_app_no     = $exist_cei->cei_app_no;
                    $cei_app_status = $this->ThirdpartyApiLog->third_party_call($arrApplication->id,$cei_app_no, 'cei');
                    if(strtolower($cei_app_status) == 'completed')
                    {
                        $status         = $this->ApplyOnlineApprovals->CEI_INSPECTION_APPROVED;
                        $members_data   = $this->Members->find('all',array('conditions'=>array('division'=>$arrApplication->division,'subdivision'=>'0')))->first();
                        $this->ThirdpartyApiLog->SetApplicationStatus($status,$arrApplication->id,'',$members_data->id);
                    }
                }
				
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}