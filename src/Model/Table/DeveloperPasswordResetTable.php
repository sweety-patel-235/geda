<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class DeveloperPasswordResetTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'DeveloperPasswordReset';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'developer_password_reset';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
    public function SendSMSActivationCode($id,$mobile,$activation_code,$name,$type='')
    {
        $ConnectionManager  = ConnectionManager::get('default');
        $TEMPLATE_ID_SQL    = " SELECT sms_template_mapping.template_id
                                    FROM sms_template_mapping
                                    WHERE sms_template_mapping.sms_template ='".$type."'";
                                
        $TEMPLATE_ID_RES    = $ConnectionManager->execute($TEMPLATE_ID_SQL)->fetchAll('assoc');
        
        $TEMPLATE_ID        = isset($TEMPLATE_ID_RES[0]['template_id'])?$TEMPLATE_ID_RES[0]['template_id']:"";

        $MESSAGE            = urlencode("Dear " .$name. ", Your Reset password activation code is ".$activation_code .".");
        $FIND_ARRAY         = array("[SMS_USER]","[SMS_PASS]","[MESSAGE]","[MOBILE]","[TEMPLATE_ID]");
        $REPL_ARRAY         = array(SMS_USER,SMS_PASS,$MESSAGE,$mobile,$TEMPLATE_ID);
        $SMS_GATEWAY_URL    = str_replace($FIND_ARRAY,$REPL_ARRAY,SMS_GATWAY_URL);
        echo $SMS_GATEWAY_URL;
        exit;
        $SMS_CONTENT        = $this->ApiCall($SMS_GATEWAY_URL);

    }
    private function ApiCall($SMS_GATEWAY_URL)
    {
        $ch                 = curl_init($SMS_GATEWAY_URL);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
        curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
        $SMS_CONTENT        = curl_exec($ch);

        return $SMS_CONTENT;
    }
}   
?>