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
use Cake\Datasource\ConnectionManager;

class ProcessFailureRegistrationShell extends Shell
{

	public function initialize()
	{
		parent::initialize();
		$this->loadModel('SendRegistrationFailure');
		$this->loadModel('CronApiProcess');
	}

	public function main()
	{
		echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";

		/** KP */
        $ConnectionManager = ConnectionManager::get('default');
        $ConnectionManager->execute("SET @@SESSION.sql_mode='NO_ENGINE_SUBSTITUTION'");
        /** KP */
		$query_sent 		          = 1;
		$GUJARAT_STATE 		          = 4;
		$CurrentHour                  = date("H");
		if ($CurrentHour > 8 && $CurrentHour < 20) {
			$LIMIT_QUERY_SENT         = 5000; 
		} else {
			$LIMIT_QUERY_SENT         = 12000;
		}
		$LastProcessedApplicationID   = 0;
		$arrConditions                = [];
		if (!empty($LastRowID)) 
		{
			
		} else {
			
		}
		//'conditions'    => $arrConditions,
																	
		$arrApplications    = $this->SendRegistrationFailure->find('all',
																[   'fields'        => ["id","application_id"],
																	'join'     		=> ['apply_onlines'=>['table'=>'apply_onlines','conditions'=>['SendRegistrationFailure.application_id=apply_onlines.id']]],
																	'conditions' 	=> array('apply_onlines.area not in'=>array(470,471,472)),
																	'limit'         => $LIMIT_QUERY_SENT,
																	'order'         => 'SendRegistrationFailure.id ASC',
																]
														)->toArray();
		$FetchedRowCount 	= count($arrApplications);
		$counter 			= 0;
		echo "\r\n".$FetchedRowCount.' Fetched'."\r\n";
		if (!empty($FetchedRowCount))
		{
			foreach($arrApplications as $arrApplication)
			{
				$LastProcessedApplicationID = $arrApplication->application_id;
				$this->SendRegistrationFailure->fetchApiSendRegistration($arrApplication->application_id);
				$counter++;
			}

		} else {
		}
		
		echo "\r\n".$counter.' Proccessed'."\r\n";
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}
}