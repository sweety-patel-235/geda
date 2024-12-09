<?php
/**
 * Short description for file
 * This Model use for products. It extends AppModel Class
 * @author Kalpak Prajapati
 * @category  Class File
 * @Desc      Provides infomration related to products
 * @author    Kalpak Pajapati
 * @version   
 * @since     2015-10-26
 */

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;

class CustomerSubscriptionTable extends AppTable {
	/**
	 * The status of $name is universe
	 * Potential value are Class Name
	 * @public String
	 */
	public $name = "CustomerSubscription";
	
	/**
	* The status of $useDbConfig is universe
	* Potential value are Database Connection String
	* @var String
	*/
	public $useDbConfig	= "default";

	/**
	 * The status of $useTable is universe
	 * Potential value are Class Name
	 * @public String
	 */
	public $useTable	= "customer_subscription";

	public $belongsTo	= array('Customer' => array('className' => 'Customer','foreignKey' => 'customer_id'),
								'PaymentRequest' => array('className' => 'PaymentRequest','foreignKey' => 'payment_id'),
								'CustomerReward' => array('className' => 'CustomerReward','foreignKey' => 'customer_reward_id'));

	/**
	 * SaveCustomerWallet : get payment url
	 * Behaviour : Public
	 * @param  
	 * @throws 
	 * @return 
	 */
	public function SaveWalletDetails($data,$customer_reward_id=0)
	{
		$CustomerSubscription['installer_id']		= $data['customer_id'];
		$CustomerSubscription['amount']				= $data['amount'];
		$CustomerSubscription['payment_id']			= $data['id'];
		$CustomerSubscription['txn_date']			= $data['created'];
		$CustomerSubscription['customer_reward_id']	= $customer_reward_id;
		$CustomerSubscription['created']			= date("Y-m-d H:i:s");
		$newCustometSubscription = $this->newEntity($CustomerSubscription);
		$this->save($newCustometSubscription);
		return $newCustometSubscription->id;
	}
}
?>