<?php
/************************************************************
* File Name : SubscriptionTable.php 						*
* purpose	: Manage Database Opration of Subscription us User*
* @package  : 												*
* @author 	: Pravin Sanghani								*
* @since 	: 23/04/2016									*
************************************************************/

namespace App\Model\Table;
use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;

class InstallerSubscriptionTable extends AppTable
{
	var $table = 'installer_subscription';
	var $data  = array();
	public function initialize(array $config)
    {
        $this->table($this->table);       	
    }
    /**
     * Add validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    /**
    *
    * validationaddsub
    *
    * Behaviour : public
    *
    * @defination : Method is used to check validation when subscription added by admin
    *
    */
    public function validationaddsub(Validator $validator)
    {   
        $validator->notEmpty('start_date', 'Start date can not be blank.');
        $validator->notEmpty('expire_date', 'Expire date can not be blank.');
        if($this->data['plan_id'] == '0')
        {
            $validator->add("plan_id", [
                    "_empty" => [
                        "rule" => [$this, "customFunction"],
                        "message" => "Plan must be select."
                    ]
                        ]
                );
        }
        if(strtotime($this->data['start_date'])>strtotime($this->data['expire_date']))
        {
            $validator->add("start_date", [
                    "_empty" => [
                        "rule" => [$this, "customFunctionDate"],
                        "message" => "Start date must be less than expire date."
                    ]
                        ]
                );
        }
        return $validator;
    }
    /**
    *
    * customFunction
    *
    * Behaviour : public
    *
    * @defination : Method is used to check drop down select or not
    *
    */
    public function customFunction($value, $context) {
        
        if($value == 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }
    /**
    *
    * customFunctionDate
    *
    * Behaviour : public
    *
    * @defination : Method is used to when start date greater than expire date 
    *
    */
    public function customFunctionDate($value, $context) {
            return false;
    }
    /**
    *
    * saveinstaller_success
    *
    * Behaviour : public
    *
    * @defination : Method is used to when successful payment done by installer
    *
    */
    public function saveinstaller_success($arr_request_data,$is_mobile=0)
    {
        $InstallerSubscriptionEntity                    = $this->newEntity();
        $InstallerSubscriptionEntity->payment_status    = $arr_request_data['status'];
        $InstallerSubscriptionEntity->installer_id      = decode($arr_request_data['udf1']);
        $InstallerSubscriptionEntity->coupen_code       = $arr_request_data['udf2'];
        $InstallerSubscriptionEntity->transaction_id    = $arr_request_data['txnid'];
        $InstallerSubscriptionEntity->created           = $arr_request_data['addedon'];
        $InstallerSubscriptionEntity->modified          = $arr_request_data['addedon'];
        $InstallerSubscriptionEntity->payment_gateway   = 'payumoney';
        $InstallerSubscriptionEntity->comment           = 'Paid by Customer';
        $InstallerSubscriptionEntity->payment_data      = json_encode($arr_request_data);
        $installerTable                                 = TableRegistry::get('Installers');
        $arr_ins_details                                = $installerTable->find('all',array('conditions'=>array('id'=>$InstallerSubscriptionEntity->installer_id)))->toArray();
        $plan_id                                        = $arr_ins_details[0]['installer_plan_id'];
        $installerplanTable                             = TableRegistry::get('InstallerPlans');
        $arr_plan_details                               = $installerplanTable->find('all',array('conditions'=>array('id'=>$plan_id)))->toArray();
        $InstallerSubscriptionEntity->amount            = '0';
        $InstallerSubscriptionEntity->coupen_id         = '0';
        $InstallerSubscriptionEntity->is_flat           = '0';
        if($arr_request_data['udf2']!='')
        {
            $installerCoupenTable                       = TableRegistry::get('InstallersCoupan');
            $installerCupn                              = $installerCoupenTable->find('all', array('conditions'=>array('coupan_code'=>$arr_request_data['udf2'])))->toArray();
            if(!empty($installerCupn)){
                $coupanObj                              = $installerCupn[0];
                $InstallerSubscriptionEntity->amount    = $coupanObj->amount;
                $InstallerSubscriptionEntity->coupen_id = $coupanObj->id;
                $InstallerSubscriptionEntity->is_flat   = $coupanObj->is_flat;
            }
        }
        $InstallerSubscriptionEntity->plan_name         = $arr_plan_details[0]['plan_name'];
        $InstallerSubscriptionEntity->plan_price        = $arr_plan_details[0]['plan_price'];
        $InstallerSubscriptionEntity->plan_id           = $plan_id;
        $InstallerSubscriptionEntity->user_limit        = $arr_plan_details[0]['user_limit'];
        $InstallerSubscriptionEntity->start_date        = date('Y-m-d');
        $InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
        $InstallerSubscriptionEntity->status            = '1';
        $InstallerSubscriptionEntity->created_by        = $arr_ins_details[0]['customer_id'];
        $InstallerSubscriptionEntity->modified_by       = $arr_ins_details[0]['customer_id'];
        if ($this->save($InstallerSubscriptionEntity)) 
        {
            if($InstallerSubscriptionEntity->installer_id!='')
            {
                $InstallerActivationCodes               = TableRegistry::get('InstallerActivationCodes');
                if(isset($InstallerSubscriptionEntity->user_limit) && $InstallerSubscriptionEntity->user_limit > 0) 
                { 
                    $insCodeArr = array();
                    for ($i=0; $i < $InstallerSubscriptionEntity->user_limit; $i++) {
                        $activation_codes                                           = $installerTable->generateInstallerActivationCodes();
                        $insCodeArr[]                                               = $activation_codes;
                        $insCodedata['InstallerActivationCodes']['installer_id']    = $InstallerSubscriptionEntity->installer_id;
                        $insCodedata['InstallerActivationCodes']['activation_code'] = $activation_codes;
                        $insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
                        $insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
                        $insCodeEntity                                              = $InstallerActivationCodes->newEntity($insCodedata);
                        $InstallerActivationCodes->save($insCodeEntity);
                    }
                    $CustomerTable               = TableRegistry::get('Customers');
                    $CustomerTable->updateAll(['installer_id' => $InstallerSubscriptionEntity->installer_id,'modified' => $this->NOW()], ['id' => $arr_ins_details[0]['customer_id']]);
                    echo $this->SendProfessionalRegistrationNotificationEmail($InstallerSubscriptionEntity->installer_id, $insCodeArr);
                }
            }
            if($is_mobile==0)
            {
                if($InstallerSubscriptionEntity->installer_id!='')
                {
                    return 1;
                }
                else
                {
                     return 0;
                }
            }
            else
            {
                return 1;
            }
                  
        }
    }
    /**
    * SendProfessionalRegistrationNotificationEmail
    * Behaviour : public
    * @defination : Method is used to send registration email.
    * Author : Khushal Bhalsod
    */
    public function SendProfessionalRegistrationNotificationEmail($insId, $insCodeArr)
    {
        if(!empty($insId) && !empty($insCodeArr)) {
            $installerTable = TableRegistry::get('Installers');
            $insData        = $installerTable->get($insId);

            if(!empty($insData['email'])) {     
                $to         = $insData['email'];
                $bcc        = "jayshree.tailor@yugtia.com";
                $subject    = PRODUCT_NAME." Registration";
                $email      = new Email('default');
                $email->profile('default');
                $email->viewVars(array('insData' => $insData,'insCodeArr'=>$insCodeArr));           
                $email->template('professional_registration', 'default')
                        ->emailFormat('html')
                        ->from(array(FROM_ACTIVATION_EMAIL => PRODUCT_NAME))
                        ->to($to)
                        ->bcc($bcc)
                        ->subject(Configure::read('EMAIL_ENV').$subject)
                        ->send();
                return 1;
            }
        }
    }
    /**
    *
    * saveinstaller_failure
    *
    * Behaviour : public
    *
    * @defination : Method is used to when successful payment done by installer
    *
    */
    public function saveinstaller_failure($arr_request_data,$is_mobile=0)
    {
        $InstallerSubscriptionEntity                    = $this->newEntity();
        $InstallerSubscriptionEntity->payment_status    = $arr_request_data['status'];
        $InstallerSubscriptionEntity->installer_id      = decode($arr_request_data['udf1']);
        $InstallerSubscriptionEntity->transaction_id    = $arr_request_data['txnid'];
        $InstallerSubscriptionEntity->created           = $arr_request_data['addedon'];
        $InstallerSubscriptionEntity->modified          = $arr_request_data['addedon'];
        $InstallerSubscriptionEntity->payment_gateway   = 'payumoney';
        $InstallerSubscriptionEntity->comment           = 'Paid by Customer';
        $InstallerSubscriptionEntity->payment_data      = json_encode($arr_request_data);
        $installerTable                                 = TableRegistry::get('Installers');
        $arr_ins_details                                = $installerTable->find('all',array('conditions'=>array('id'=>$InstallerSubscriptionEntity->installer_id)))->toArray();
        $plan_id                                        = $arr_ins_details[0]['installer_plan_id'];
        $installerplanTable                             = TableRegistry::get('InstallerPlans');
        $arr_plan_details                               = $installerplanTable->find('all',array('conditions'=>array('id'=>$plan_id)))->toArray();
        $InstallerSubscriptionEntity->plan_name         = $arr_plan_details[0]['plan_name'];
        $InstallerSubscriptionEntity->plan_price        = $arr_plan_details[0]['plan_price'];
        $InstallerSubscriptionEntity->plan_id           = $plan_id;
        $InstallerSubscriptionEntity->user_limit        = $arr_plan_details[0]['user_limit'];
        $InstallerSubscriptionEntity->status            = '0';
        $InstallerSubscriptionEntity->created_by        = $arr_ins_details[0]['customer_id'];
        $InstallerSubscriptionEntity->modified_by       = $arr_ins_details[0]['customer_id'];
        if ($this->save($InstallerSubscriptionEntity)) 
        {
            if($is_mobile==0)
            {
                if($InstallerSubscriptionEntity->installer_id!='')
                {
                    return 1;
                }
                else
                {
                     return 0;
                }
            }
            else
            {
                return 1;
            }
                  
        }
    }
}
?>