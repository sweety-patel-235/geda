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
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

class SendEmailCustomerShell extends Shell
{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('Customers');
        $this->loadModel('Installers');
        $this->loadModel('Emaillog');
    }

    /**
     * now
     * Behaviour : Public
     * @return : date
     * @defination : Method is get the current date and time
     */
    public function NOW()
    {
        return date("Y-m-d H:i:s");
    }

    public function main()
    {
        echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $this->SendNotifictionEmailToInstallers();
        echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }

    public function SendGeneralEmailToInstallers()
    {
        $category_id = array(1000);
        $arrInstallers = $this->Installers->find('all',
                        [
                            'fields'=> ['Installers.id','Installers.installer_name','Installers.contact_person','Installers.email','customers.email','Installers.mobile'],
                            'join'=>[
                                        [   'table'=>'installer_category_mapping',
                                            'type'=>'INNER',
                                            'conditions'=>'installer_category_mapping.installer_id = Installers.id'
                                        ],
                                        [   'table'=>'customers',
                                            'type'=>'INNER',
                                            'conditions'=>'customers.installer_id = Installers.id'
                                        ],
                                        [   'table'=>'waitinglist_applications',
                                            'type'=>'INNER',
                                            'conditions'=>'waitinglist_applications.installer_id = Installers.id'
                                        ]

                                    ],
                            'conditions'=>['installer_category_mapping.category_id IN '=>$category_id],
                            'order'=>['Installers.id'=>'ASC']
                        ]
                    );
        $arrInstallers = $this->Installers->find();
        $arrInstallers->select(['Installers.id','Installers.installer_name','Installers.contact_person','Installers.email','CM.email','Installers.mobile']);
        $arrInstallers->innerJoin(['ICM' => 'installer_category_mapping'],['ICM.installer_id = Installers.id']);
        $arrInstallers->innerJoin(['CM' => 'customers'],['CM.installer_id = Installers.id']);
        $arrInstallers->innerJoin(['WAL' => 'waitinglist_applications'],['WAL.installer_id = Installers.id']);
        $arrInstallers->where(['ICM.category_id IN '=>$category_id]);
        $arrInstallers->order(['Installers.id'=>'ASC']);
        $arrInstallers->group('Installers.id');
        $arrInstallers->limit(0);

        if (!empty($arrInstallers))
        {
            foreach($arrInstallers as $rowid=>$arrInstaller)
            {
                echo "\r\n--".$rowid." -- ".$arrInstaller->id." -- ".$arrInstaller->email." -- ".$arrInstaller->CM['email']." -- ".$arrInstaller->mobile." --\r\n";
                $SendPasswordEmail = false;
                if ($SendPasswordEmail)
                {
                    $subject        = PRODUCT_NAME." Category B Installers Revised PV Capacity";
                    $subject        = "GEDA | Unified Single Window Rooftop PV Portal Category B Waiting List";
                    $email          = new Email('default');
                    $EmailTo        = $arrInstaller->CM['email'];
                    $email->profile('default');
                    $email->template('installer_general_email', 'default')
                            ->emailFormat('html')
                            ->from(array('info.geda@ahasolar.in' => PRODUCT_NAME))
                            ->to($EmailTo)
                            ->bcc('pulkitdhingra@gmail.com')
                            ->subject($subject);
                    $email->send();
                    $Emaillog                  = $this->Emaillog->newEntity();
                    $Emaillog->email           = $EmailTo;
                    $Emaillog->send_date       = $this->NOW();
                    $Emaillog->action          = "GEDA | Unified Single Window Rooftop PV Portal Category B Waiting List";
                    $Emaillog->description     = json_encode(array( 'EMAIL_ADDRESS' => $arrInstaller->CM['email']));
                    $this->Emaillog->save($Emaillog);
                }
            }
        }
    }

    public function SendNotifictionEmailToInstallers()
    {
        $arrInstallers = $this->Installers->find();
        $arrInstallers->select(['Installers.id','Installers.installer_name','Installers.contact_person','Installers.email','Installers.mobile']);
        $arrInstallers->order(['Installers.id'=>'ASC']);
        //$arrInstallers->limit(1);

        if (!empty($arrInstallers))
        {
            foreach($arrInstallers as $rowid=>$arrInstaller)
            {
                echo "\r\n--".$rowid." -- ".$arrInstaller->id." -- ".$arrInstaller->email." -- ".$arrInstaller->mobile." --\r\n";
                $SendPasswordEmail = false;
                if ($SendPasswordEmail && !empty($arrInstaller->email) && $arrInstaller->id > 586)
                {
                    $subject            = PRODUCT_NAME." : Maintenance Update";
                    $email              = new Email('default');
                    $EmailTo            = trim($arrInstaller->email);
                    $MESSAGE_CONTENT    = "Dear Installers,<br /><br />The Unified Single Window Rooftop PV Portal of GEDA Maintenance work is in progress and the portal shall be live for further application process from 11:00 AM on 4 December 2018. Inconvenience caused is deeply regretted.<br /><br />Thank you.<br /><br />Regards,<br /><br />Support Team";
                    $email->profile('default');
                    $email->viewVars(array( 'MESSAGE_CONTENT' => $MESSAGE_CONTENT));
                    $email->template('installer_notification_email', 'default')
                            ->emailFormat('html')
                            ->from(array('info.geda@ahasolar.in' => PRODUCT_NAME))
                            ->to($EmailTo)
                            ->bcc('pulkitdhingra@gmail.com')
                            ->subject($subject);
                    $email->send();
                    $Emaillog                  = $this->Emaillog->newEntity();
                    $Emaillog->email           = $EmailTo;
                    $Emaillog->send_date       = $this->NOW();
                    $Emaillog->action          = $subject;
                    $Emaillog->description     = json_encode(array( 'EMAIL_ADDRESS' => $EmailTo));
                    $this->Emaillog->save($Emaillog);
                }
            }
        }
    }
}