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

class CheckDrawingAppliedStatusShell extends Shell
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
        $application_status = array($this->ApplyOnlineApprovals->DRAWING_APPLIED);
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
                    $drawing_number     = $exist_cei->drawing_app_no;
                    $drawing_app_status = $this->ThirdpartyApiLog->third_party_call($arrApplication->id,$drawing_number, 'drawing');
                    if(strtolower($drawing_app_status) == 'completed')
                    {
                        $status         = $this->ApplyOnlineApprovals->APPROVED_FROM_CEI;
                        $members_data   = $this->Members->find('all',array('conditions'=>array('division'=>$arrApplication->division,'subdivision'=>'0')))->first();
                        
                        $this->ThirdpartyApiLog->SetApplicationStatus($status,$arrApplication->id,'',$members_data->id);
                    }
                }
				
			}
		}
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}