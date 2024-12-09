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
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Datasource\ConnectionManager;

class GetUniqueVisitorShell extends Shell
{
	public function initialize()
    {
        parent::initialize();
        $this->loadModel('UniqueVisitorCount');
    }
    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
    	$RecordFound 	= true;
    	$LastAPPID 		= 0;
    	$connection     = ConnectionManager::get('default');
		$countvisit 	= $connection->execute("SELECT count(DISTINCT ip_address,created) as total_counter FROM `visitor_tracker`")->fetchAll('assoc');
    	$this->UniqueVisitorCount->updateAll(array('visitor_total_count'=>$countvisit[0]['total_counter']),array('id'=>1));
		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
}