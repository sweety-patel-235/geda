<?php
namespace App\Model\Table;

use App\Model\Table\Entity;
use App\Model\Entity\User;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
//use App\Model\Table\Security;

use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
//use Cake\Event\Event;

/**
 * Short description for file
 * This Model use for Ticket table. It extends Table Class
 * @category  Class File
 * @Desc      Manage Ticket information
 * @author    Pravin Sanghani
 * @version   Solar 1.0
 * @since     File available since Solar 1.0
 */

class GhiDataTable extends AppTable
{
	/**
	 *
	 * The status of $STATUS_ACTIVE is universe
	 *
	 * Potential value are 1 (identify Admin User Active)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_ACTIVE = 1;
	/**
	 *
	 * The status of $STATUS_INACTIVE is universe
	 *
	 * Potential value are 0 (identify Admin User InActive/Deactive)
	 *
	 * @var Int
	 *
	 */
	var $STATUS_INACTIVE = 0;
	var $DEFAULT_USER_TIMEZONE='Asia/Kolkata';
	var $RETAILER_RECHARGE_ROLE_ID = '';
	public $validate		= array();
	public $validationSet	= "";
	var $table = 'ghi_data';
	public function initialize(array $config)
    {
        $this->table('ghi_data');
         
    }
	/**
	 *
	 * The status of $validate_timezone is universe
	 *
	 * Potential value are validate time zone
	 *
	 * @var Array
	 *
	 */
	var $validate_timezone =  array(
		/*
			'timezone' => array(
					'rule' => array('maxLength',5),
					'required' => true,
					'allowEmpty' => false,
					'message' => 'Please select valid Time zone.'
			)
			*/
	);
	public $validate_registration = array(
       
    );

	/**
	 *
	 *  identicalFieldValues
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field compared with Confirm password field.
	 *
	 */
    function identicalFieldValues( $field=array(), $compare_field=null )
    {
        foreach( $field as $key => $value ){
            $v1 = $value;
            $v2 = $this->data[$this->name][ $compare_field ];
            if($v1 !== $v2) {
                return FALSE;
            } else {
                continue;
            }
        }
        return TRUE;
    }
	/**
	 *
	 *  beforeSave
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field encrypted with Security salt
	 *
	 */
	//Public function beforeSave(Event $event,Entity $entity)
	public function beforeSave($event, $entity, $options)
	{
		if(isset($entity->password) && !empty($entity->password))
		{
			$entity->password = Security::hash(Configure::read('Security.salt') . $entity->password);
		}
		return TRUE;
	}
	/**
	 *
	 *  getGhiData
	 *
	 * Behaviour : Public
	 *
	 * @return : its return boolean
	 * @defination : befor saving data in User table Password field encrypted with Security salt
	 *
	 */
	
	public function getGhiData($long,$lat)
	{
		$latitude = getRoundLatLong($lat);
		$longitude = getRoundLatLong($long);
		$gridcode=str_replace(array('.',' '),'',$longitude.$latitude);
		$ghiData = $this->find('all',array('conditions'=>array("gridcode"=>$gridcode)))->first();	
		$n = array(0,17,47,75,106,135,166,198,228,258,288,318,344); // constant monthly
		if(!empty($ghiData)) {
			$GTIAnnual = 0;
			for($i=1;$i<=12;$i++) {
				$keyName 		= strtolower(date('M', mktime(0, 0, 0, $i, 10)))."_glo";
				$GHIVal 	= (isset($ghiData[$keyName])?$ghiData[$keyName]:0);
				
				/* Calculate GTI from GHI, lat, lng and constant n */
				if($GHIVal != 0) {
					$GTIVal  = $this->calculateGTI($GHIVal,$lat,$lat,$n[$i]);
					$ghiData[$keyName] = $GTIVal;
				}
				$GTIAnnual = $GTIAnnual + $ghiData[$keyName];
			}
			if($GTIAnnual > 0){
				$ghiData['ann_glo'] = ($GTIAnnual/12);
			}
		}
		return (isset($ghiData)?$ghiData:array());
	}

	 /**
	 *
	 *  Calculate GTI from GHI
	 *
	 * @param : $GHI : ghi
	 * @param : $latitude : coordinate of user location 
	 * @param : $tilt : angle currently same as latitude, decimal not taken
	 * @param : $n : constant
	 * @return :  its returns calculated GTI
	 * @defination : this method is used to calculate GTI from GHI
	 *
	 */
	function calculateGTI($GHI,$latitude,$tilt,$n=82)
	{
		$latitude = deg2rad($latitude);
		$tilt = deg2rad($tilt);
		
		/* STEP 1: Calculate delta (Declination angle)*/
		$delta =  23.45 * sin(deg2rad((360/365)*($n-81)));
		$delta = deg2rad($delta);
		//prd($delta); 
		//prd(tan(deg2rad($delta)));
		/* STEP 2: Calculate Hsr */
		$Hsr = acos(-tan($latitude)*tan($delta));
	
		
		/* STEP 3: Calculate Hsrc */
		//prd(acos(-tan(8) * tan($delta)));
		$HsrVal1 = acos(-tan($latitude)*tan($delta));
		$HsrVal2 = acos(-tan($latitude - $tilt) * tan($delta));
		$Hsrc = min($HsrVal1,$HsrVal2);

		/* STEP 4: Calculate Io */
		$pie = 3.1415926536;
		$Io = (24/$pie) * 1.37 * (1 + (0.034 * cos(360 * $n / 365))) * ((cos($latitude) * cos($delta) * sin($Hsr)) + ($Hsr * sin($latitude) * sin($delta)));

		/* STEP 5: Calculate $Kt */
		$Kt = $GHI / $Io;

		/* STEP 6: Diffuse  Idh */
		$Idh = $GHI * (1.39 - (4.027 * $Kt) + (5.531 * pow($Kt,2)) - (3.108 * pow($Kt,3)));

		/* STEP 7: Beam Calculate Ibh */
		$Ibh = $GHI - $Idh;

		/* STEP 8: Diffuse @ Tilt, Idc */
		$Idc = $Idh * (1 + cos($tilt)) / 2;

		/* STEP 9: Calculate Irc */
		$albedo = 0.2;  // constant generally taken as 0.2
		$Irc = $albedo * $GHI * (1 - cos($tilt)) / 2;

		/* STEP 10: Calculate $Rb */
		$RbU = ((cos($latitude - $tilt) * cos($delta) * sin($Hsrc)) + ($Hsrc * sin($latitude -$tilt) * sin($delta)));
		$RbL = ((cos($latitude) * cos($delta) * sin($Hsr)) + ($Hsr * sin($latitude) * sin($delta)));
		$Rb = $RbU / $RbL;

		/* STEP 11: calculate Ibc  Ibh = beam, */
		$Ibc  = $Ibh * $Rb;

		/* STEP 12: Calculate GTI */
		$GTI = ($GHI * (1 - ($Idh/$GHI)) * $Rb) + ($Idh * (1 + cos($tilt)) / 2) + ($albedo * $GHI * (1 - cos($tilt)) / 2);

		/*$debugData = 
		[	'STEP1: $delta ' => $delta,
			'STEP2: $Hsr ' 	=> $Hsr,
			'STEP3: $Hsrc ' => $Hsrc,
			'STEP4: $Io ' 	=> $Io,
			'STEP5: $Kt ' 	=> $Kt,
			'STEP6: Diffuse $Idh ' => $Idh,
			'STEP7: Beam $Ibh ' => $Ibh,
			'STEP8: Diffuse @Tilt $Idc ' => $Idc,
			'STEP9: $Irc ' => $Irc,
			'STEP10: $Rb ' => $Rb,
			'STEP11: $Ibc ' => $Ibc,
			'STEP12: $GTI ' => $GTI,	
		];
		prd($debugData);
		*/
		return $GTI;

	}

}
?>