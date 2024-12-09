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

use Cake\Console\Shell;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Auth\DefaultPasswordHasher;

class HelloShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('Installers');
    }
    public function main()
    {
    	$pass = "ovtd8yvw";
    	$userpassword = Security::hash(Configure::read('Security.salt').($pass));

    	echo "\r\n--".$userpassword."--\r\n";
    	die;
    }
}