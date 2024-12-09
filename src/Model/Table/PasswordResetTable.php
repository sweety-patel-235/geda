<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
/**
 * Short description for file
 * This Model use for Ticket table. It extends AppModel Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    jaysinh Rajpoot
 * @version   RR
 * @since     File available since RR 1.0
 */
class PasswordResetTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @var String
	 */
	public $Name 	= 'PasswordReset';

    /**
     * The status of $useTable is universe
     * Potential value are Database Table name
     * @var String
     */
    public $useTable = 'password_reset';
    public function initialize(array $config)
    {
        $this->table($this->useTable);
    }
    public function SendSMSActivationCode($id,$mobile,$activation_code,$name)
    {
        $MESSAGE            = urlencode("Dear " .$name. ", Your Reset password activation code is ".$activation_code .".");
        $FIND_ARRAY         = array("[SMS_USER]","[SMS_PASS]","[MESSAGE]","[MOBILE]");
        $REPL_ARRAY         = array(SMS_USER,SMS_PASS,$MESSAGE,$mobile);
        $SMS_GATEWAY_URL    = str_replace($FIND_ARRAY,$REPL_ARRAY,SMS_GATWAY_URL);

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