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

class TestFileShell extends Shell
{

	public function initialize()
    {
        parent::initialize();
        $this->loadModel('ApplyOnlines');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('SpinWebserviceApi');
    }

    public function main()
    {
    	echo "\r\n--StartTime::".date("Y-m-d H:i:s")."--\r\n";
        $url                            = SPIN_API_DEV_URL.'pcr_files/'.SPIN_API_DEV_TOKEN;
        $main_path                      = WWW_ROOT.SUBSIDY_PATH."40505/";
       
        $arrRequest['pcr_code']         = '38';
        $arrRequest['id_bene_details']              = '2';
        $arrRequest['id_proof']                     = 'Aadhaar Card';
        $arrRequest['file_inspection']              = $this->getCurlValue($main_path.'mou_20190123080234140394666.pdf','','mou_20190123080234140394666.pdf');//new \CURLFile($main_path.'mou_20190123080234140394666.pdf');
        $arrRequest['file_customer_plant']          = $this->getCurlValue($main_path.'pv_20190124113004150123037.jpg','','pv_20190124113004150123037.jpg');//new \CURLFile($main_path.'pv_20190124113004150123037.jpg');
        $arrRequest['file_customer']                = $this->getCurlValue($main_path.'pv_201901241130041501230371.jpg','','pv_201901241130041501230371.jpg');//new \CURLFile($main_path.'pv_201901241130041501230371.jpg');
        $arrRequest['file_id_proof']                = $this->getCurlValue($main_path.'aadhar_20190123080221998869348.pdf','','aadhar_20190123080221998869348.pdf');//new \CURLFile($main_path.'aadhar_20190123080221998869348.pdf');
        $arrRequest['file_discom']                  = $this->getCurlValue($main_path.'mod_cert_20190124113004898495092.pdf','','mod_cert_20190124113004898495092.pdf');//new \CURLFile($main_path.'mod_cert_20190124113004898495092.pdf');
        $arrRequest['file_module_capacity']         = '';
        $arrRequest['file_module_capacity_2']       = '';
        $arrRequest['file_module_capacity_3']       = '';
        $arrRequest['file_undertaking_consumer']    = '';
                
       // $arrRequest['file_inspection']  = new \CURLFile($main_path.'mou_20190123080234140394666.pdf');
        print_r($arrRequest);
        $ch             = curl_init($url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Forwarded-For: 103.233.170.222'));
        //curl_setopt($ch, CURLOPT_HEADER,0);             // DO NOT RETURN HTTP HEADERS
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$arrRequest);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);   // RETURN THE CONTENTS
        //curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));  
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT  ,0);
        $output         = curl_exec($ch);
        $Response       = json_decode($output,1);
        curl_close ($ch);
        print_r($Response);
        echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
    }
    public function getCurlValue($filename, $contentType, $postname)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('curl_file_create')) {
            return curl_file_create($filename, $contentType, $postname);
        }
     
        // Use the old style if using an older version of PHP
        $value = "@{$filename};filename=" . $postname;
        if ($contentType) {
            $value .= ';type=' . $contentType;
        }
     
        return $value;
    }
}