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

class ProjectInvitationExpireShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('InstallerProjects');
    }
    public function main()
    {
        $Lead_Pendiing 				= 4001;
        $Lead_Expire 				= 4005;
        $Lead_Created 				= date("Y-m-d",strtotime("-3 days"));
        $Expire_Date 				= date("Y-m-d H:i:s");
        $conn 						= ConnectionManager::get('default');
        $ExpireProjectInvitation 	= "	UPDATE installer_projects SET 
        								expired_date 	= '".$Expire_Date."',
        								status 			= '".$Lead_Expire."',
        								updated 		= '".date("Y-m-d H:i:s")."'
        								WHERE created <= '".$Lead_Created." 00:00:00"."'
        								AND status  = ".$Lead_Pendiing;
        $stmt 						= $conn->execute($ExpireProjectInvitation);
        $affectedRows 				= $stmt->rowCount();
        echo "\r\n--".$affectedRows." leads marked as expired.";
    }
}