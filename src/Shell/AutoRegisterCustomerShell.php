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

class AutoRegisterCustomerShell extends Shell
{

	public function initialize()
	{
		parent::initialize();
		$this->loadModel('Customers');
		$this->loadModel('Installers');
		$this->loadModel('InstallerPlans');
		$this->loadModel('InstallerSubscription');
		$this->loadModel('InstallerActivationCodes');
		$this->loadModel('Parameters');
		$this->loadModel('InstallerCredendtials');
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

		$this->SendPasswordEmail();
		// $this->AutoRegisterInstaller();
		// $this->GetDBPass();

		echo "\r\n--EndTime::".date("Y-m-d H:i:s")."--\r\n";
	}

	public function AutoRegisterInstaller()
	{
		//$arrInstallerIds = array(1488,1489,1490,1491,1492,1493,1494,1495,1496,1497,1498,1499,1500,1501,1502,1503,1504,1505,1506,1507,1508,1509,1510,1511,1512,1513,1514,1515,1516,1517,1518,1519,1520,1521,1522,1523,1524,1525,1526,1527,1528,1529,1530,1531,1532,1533,1534,1535,1536,1537,1538,1539,1540,1541,1542,1543,1544,1545,1546,1547,1548,1549,1550,1551,1552,1553,1554,1555,1556,1557,1558,1559,1560,1561,1562,1563,1564,1565,1566,1567,1568,1569,1570,1571,1572,1573,1574,1575,1576,1577,1578,1579,1580,1581,1582,1583,1584,1585,1586,1587,1588,1589,1454,1415);
		// $arrInstallerIds = array(1590,1591,1592,1593,1594,1595,1418,1596,1597,1598,1599,1600,1601,1602,1603,1604,1605,1606,1607,1608,1609,1610);

		// $arrInstallerIds = array(1612,1613,1614,1615,1616);
		// $arrInstallerIds = array(1617,1618,1619,1620,1621,1622,1623,1624,1625,1626,1627,1628,1629,1630);
		// $arrInstallerIds = array(1631, 1632);
		// $arrInstallerIds = array(1633,1634,1635,1636,1637,1638,1639,1640);
		// $arrInstallerIds = array(1654,1655,1656,1657,1658,1659,1661,1662,1663,1664,1665,1666,1667,1668,1669);
		$arrInstallerIds = array(1670,1672,1673,1674,1675,1676,1677,1678,1679,1680,1681,1682,1683,1684);
		$arrInstallers = $this->Installers->find('all',['conditions'=>[ 'Installers.stateflg'=>4,'Installers.id IN'=>$arrInstallerIds]])->order(array('Installers.id'=>'ASC'));
		if (!empty($arrInstallers))
		{
			foreach($arrInstallers as $arrInstaller)
			{
				echo "\r\n--".$arrInstaller->id." -- ".$arrInstaller->email." -- ".$arrInstaller->mobile."--\r\n";
				$AutoRegister = false;
				if ($AutoRegister)
				{
					if ($arrInstaller->email == '' && $arrInstaller->mobile == '') {
						continue; //no email & mobile skip registration
					}
					$RandomPassword                 = $this->randomPassword();
					$arrEmail                       = explode(",",$arrInstaller->email);
					$CustomerEmail                  = trim($arrEmail[0]);
					$customersEntity                = $this->Customers->newEntity();
					$customersEntity->mobile        = $arrInstaller->mobile;
					$customersEntity->email         = $CustomerEmail;
					$customersEntity->name          = $arrInstaller->contact_person;
					$customersEntity->password      = $this->EncryptPassword($RandomPassword);
					$customersEntity->status        = $this->Customers->STATUS_ACTIVE;
					$customersEntity->customer_type = "installer";
					$customersEntity->state         = 4;
					$customersEntity->created       = $this->NOW();
					$customercnt                    = $this->Customers->find('all', array('conditions'=>array('email'=>$CustomerEmail)))->count();
					$IsInstallerCreated             = $this->Customers->find('all', array('conditions'=>array('installer_id'=>$arrInstaller->id)))->count();

					echo "\r\n--".$customercnt." -- ".$IsInstallerCreated."--\r\n";

					if($customercnt == 0 && $IsInstallerCreated == 0)
					{
						if ($this->Customers->save($customersEntity))
						{
							$arrInstaller->installer_plan_id                = $this->InstallerPlans->DEFAULT_PLAN_ID;
							$insplanData                                    = $this->InstallerPlans->get($this->InstallerPlans->DEFAULT_PLAN_ID);
							$InstallerSubscriptionEntity                    = $this->InstallerSubscription->newEntity();
							$InstallerSubscriptionEntity->payment_status    = '';
							$InstallerSubscriptionEntity->installer_id      = $arrInstaller->id;
							$InstallerSubscriptionEntity->coupen_code       = '';
							$InstallerSubscriptionEntity->transaction_id    = '';
							$InstallerSubscriptionEntity->created           = $this->NOW();
							$InstallerSubscriptionEntity->modified          = $this->NOW();
							$InstallerSubscriptionEntity->payment_gateway   = '';
							$InstallerSubscriptionEntity->comment           = '100% Discount';
							$InstallerSubscriptionEntity->payment_data      = '';
							$InstallerSubscriptionEntity->amount            = '0';
							$InstallerSubscriptionEntity->coupen_id         = '0';
							$InstallerSubscriptionEntity->is_flat           = '0';
							$InstallerSubscriptionEntity->plan_name         = $insplanData->plan_name;
							$InstallerSubscriptionEntity->plan_price        = $insplanData->plan_price;
							$InstallerSubscriptionEntity->plan_id           = $this->InstallerPlans->DEFAULT_PLAN_ID;
							$InstallerSubscriptionEntity->user_limit        = $insplanData->user_limit;
							$InstallerSubscriptionEntity->start_date        = date('Y-m-d');
							$InstallerSubscriptionEntity->expire_date       = date('Y-m-d',strtotime("+ 30 days"));
							$InstallerSubscriptionEntity->status            = '1';
							$InstallerSubscriptionEntity->created_by        = $customersEntity->id;
							$InstallerSubscriptionEntity->modified_by       = $customersEntity->id;
							$this->InstallerSubscription->save($InstallerSubscriptionEntity);
							$insCodeArr = array();
							for ($i=0; $i < $insplanData->user_limit; $i++) {
								$activation_codes = $this->Installers->generateInstallerActivationCodes();
								$insCodeArr[]                                               = $activation_codes;
								$insCodedata['InstallerActivationCodes']['installer_id']    = $arrInstaller->id;
								$insCodedata['InstallerActivationCodes']['activation_code'] = $activation_codes;
								$insCodedata['InstallerActivationCodes']['start_date']      = date('Y-m-d');
								$insCodedata['InstallerActivationCodes']['expire_date']     = date('Y-m-d',strtotime("+ 30 days"));
								$insCodeEntity = $this->InstallerActivationCodes->newEntity($insCodedata);
								$this->InstallerActivationCodes->save($insCodeEntity);
							}
							$this->Customers->updateAll(['user_role'=>$this->Parameters->admin_role,'default_admin'=>1,'installer_id' => $arrInstaller->id,'modified' => $this->NOW()], ['id' => $customersEntity->id]);

							$PasswordInfo['InstallerCredendtials']['installer_id']   = $arrInstaller->id;
							$PasswordInfo['InstallerCredendtials']['password']       = $RandomPassword;
							$InstallerCredendtials = $this->InstallerCredendtials->newEntity($PasswordInfo);
							$this->InstallerCredendtials->save($InstallerCredendtials);
						}
					}
				}
			}
		}
	}

	public function GetDBPass()
	{
		$Passwords = array("hJYfodiz","lY8A40XI","k3ywJMiT","626QCORK","X548HddY");
		foreach ($Passwords as $Password) {
			$EncryptPassword = $this->EncryptPassword($Password);
			echo $Password." -- ".$EncryptPassword."\n";
		}
	}

	public function randomPassword()
	{
		$alphabet       = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
		$pass           = array(); //remember to declare $pass as an array
		$alphaLength    = strlen($alphabet) - 1; //put the length -1 in cache
		for ($i = 0; $i < 8; $i++) {
			$n = rand(0, $alphaLength);
			$pass[] = $alphabet[$n];
		}
		return implode($pass); //turn the array into a string
	}

	public function SendPasswordEmail()
	{
		// $arrInstallerIds = array(2185,2184,2182,2181,2180,2178,2176,2174,2173,2170,2169,2168,2167,2165,2164,2163,2162,2161,2160,2159,2158,2157,2155,2154);
		// $arrInstallerIds = array(2220,2219,2218,2217,2216,2215,2214,2213,2212,2211,2210,2209,2208,2207,2206,2205,2204,2203,2202,2201,2200,2199,2197,2196,2195,2194,2193,2192,2191,2190,2189,2188,2187,21862220,2219,2218,2217,2216,2215,2214,2213,2212,2211,2210,2209,2208,2207,2206,2205,2204,2203,2202,2201,2200,2199,2197,2196,2195,2194,2193,2192,2191,2190,2189,2188,2187,2186);
		// $arrInstallerIds = array(2261,2260,2259,2258,2257,2256,2255,2254,2252,2251,2250,2249,2248,2247,2246,2245,2244,2243,2242,2241,2240,2239,2238,2237,2234,2233,2232,2231,2230,2229,2228,2227,2226,2225,2224,2223,2222);
		// $arrInstallerIds = array(2284,2283,2282,2281,2280,2279,2277,2276,2273,2272,2271,2270,2269,2268,2267,2266,2263,2262);
		// $arrInstallerIds = array(2308,2309,2310,2311,2313,2314);
		// $arrInstallerIds = array(2331,2330,2329,2328,2327,2326,2325,2324,2323,2322,2320,2319,2318,2317,2316,2315);
		// $arrInstallerIds = array(2384,2383,2382,2381,2380,2379,2378,2377,2376,2374,2373,2372,2371,2370,2369,2368);
		// $arrInstallerIds = array(2423,2422,2421,2420,2419,2418,2417,2416,2415,2414,2413,2412,2411,2410,2409,2408,2407,2406,2405,2404,2403,2400,2399,2398,2397,2396,2392,2390,2389,2388,2386);
		// $arrInstallerIds = array(2454,2453,2452,2448,2446,2445,2443,2442,2441,2440,2439,2438,2437,2436,2435,2434,2433,2432,2431,2430,2429,2428,2427,2426,2425,2424);
		// $arrInstallerIds = array(2468,2467,2466,2465,2464,2463,2462,2461,2460,2459,2458,2457,2456,2455);
		// $arrInstallerIds = array(2479,2478,2477,2476,2475,2474,2473,2472,2471,2470,2469);
		// $arrInstallerIds = array(2480,2481,2482,2483,2484,2485,2486,2487,2489,2490,2491,2492,2493,2494,2496,2497,2498,2499,2501,2503,2504,2505,2506,2507,2509,2510,2511,2512,2513,2514,2515,2516,2517,2519,2520,2521,2522);
		// $arrInstallerIds = array(2558,2557,2555,2554,2551,2549,2547,2546,2545,2543,2542,2541,2539,2538,2537,2536,2535,2534,2533,2532,2530,2529,2528,2527,2526,2525,2523);
		$arrInstallerIds = array(2567,2566,2565,2564,2563,2562,2561,2560,2559);
		$arrInstallers = $this->Installers->find('all',
						[
							'fields'=> ['Installers.id','Installers.email','customers.email','Installers.mobile','installer_passwords.password'],
							'join'=>[
										[   'table'=>'installer_passwords',
											'type'=>'INNER',
											'conditions'=>'installer_passwords.installer_id = Installers.id'
										],
										[   'table'=>'customers',
											'type'=>'INNER',
											'conditions'=>'customers.installer_id = Installers.id'
										]
									],
							'conditions'=>['Installers.id IN '=>$arrInstallerIds],
							'order'=>['Installers.id'=>'ASC']
						]
					);
		if (!empty($arrInstallers))
		{
			foreach($arrInstallers as $arrInstaller)
			{
				echo "\r\n--".$arrInstaller->id." -- ".$arrInstaller->email." -- ".$arrInstaller->customers['email']." -- ".$arrInstaller->mobile." -- ".$arrInstaller->installer_passwords['password']."--\r\n";
				$SendPasswordEmail = false;
				if ($SendPasswordEmail)
				{
					$subject        = PRODUCT_NAME." Login Details";
					$email          = new Email('default');
					$EmailTo        = $arrInstaller->customers['email'];
					$email->profile('default');
					$email->viewVars(array( 'EMAIL_ADDRESS' => $arrInstaller->customers['email'],
											'PASSWD' => $arrInstaller->installer_passwords['password'],
											'URL_HTTP'=>URL_HTTP));
					$email->template('installer_passowrd_email', 'default')
							->emailFormat('html')
							->from(array('info.geda@ahasolar.in' => PRODUCT_NAME))
							->to($EmailTo)
							->bcc('pulkitdhingra@gmail.com')
							->subject($subject);
					$email->send();
					$Emaillog                  = $this->Emaillog->newEntity();
					$Emaillog->email           = $EmailTo;
					$Emaillog->send_date       = $this->NOW();
					$Emaillog->action          = "Password Information";
					$Emaillog->description     = json_encode(array( 'EMAIL_ADDRESS' => $arrInstaller->customers['email'],
																	'PASSWD' => $arrInstaller->installer_passwords['password'],
																	'URL_HTTP'=>URL_HTTP));
					$this->Emaillog->save($Emaillog);
				}
			}
		}
	}

	public function EncryptPassword($Password="")
	{
		$NewPassword = Security::hash(Configure::read('Security.salt') . $Password);
		return $NewPassword;
	}
}
