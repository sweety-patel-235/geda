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

class PCRFileSubmitShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('SpinWebserviceApi');
        $this->loadModel('CronApiProcess');
    }

    public function main()
    {
        ini_set("memory_limit","-1");
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $query_sent                 = 1;
        $GUJARAT_STATE              = 4;
        $CurrentHour                = date("H");
        $LastProcessedApplicationID = 0;
        $ApplicationID              = 0;

        $LIMIT_FILE_SENT    = 50;
        $ApplyOnlines       = $this->ApplyOnlines->find();
        $application_status = array($this->ApplyOnlineApprovals->CLAIM_SUBSIDY);
        $arrConditions      = [ 'application_status IN '=>$application_status,'pcr_code IS NOT NULL','pcr_submited IS NULL','apply_state'=>$GUJARAT_STATE];
        if (!empty($ApplicationID)) $arrConditions      = ['id'=>$ApplicationID];
        $arrApplications    = $this->ApplyOnlines->find('all',
                                                            [   'fields'        => ["id"],
                                                                'conditions'    => $arrConditions,
                                                                'limit'         => $LIMIT_FILE_SENT,
                                                                'order'         => 'ApplyOnlines.id ASC',
                                                            ]
                                                    );
        $FetchedRowCount    = $arrApplications->count();
        echo "Total FetchedRowCount For Files ==> ".$FetchedRowCount."\r\n";
        if (!empty($FetchedRowCount))
        {
            foreach($arrApplications as $arrApplication)
            {
                $LastProcessedApplicationID = $arrApplication->id;
                $this->SpinWebserviceApi->AddPcrFiles($arrApplication->id);
            }
        }
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}