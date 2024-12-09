<?php
namespace App\Model\Table;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use Cake\Network\Email\Email;
use Cake\Utility\Security;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use Cake\Datasource\ConnectionManager;
class EInvoiceTable extends AppTable
{
	var $table          = 'e_invoice';
	var $apiResponse 	= array();
	 
	var $data               = array();
	public function initialize(array $config)
	{
		$this->table($this->table);     
		$this->APIURL 		= 'https://pro.mastersindia.co/';    
		//$this->APIURL 		= 'https://clientbasic.mastersindia.co/';    
	}
	public function getAccessToken($id='',$type='') {

		$ApplyOnlines 				= TableRegistry::get('ApplyOnlines');
		$Payumoney 					= TableRegistry::get('Payumoney');
		$Installers 				= TableRegistry::get('Installers');
		$InstallerPayment 			= TableRegistry::get('InstallerPayment');
		$ApplyOnlinesOthers 		= TableRegistry::get('ApplyOnlinesOthers');
		$ReApplicationPayment 		= TableRegistry::get('ReApplicationPayment');
		$Applications 				= TableRegistry::get('Applications');
		$Developers 				= TableRegistry::get('Developers');
		$DeveloperPayment 			= TableRegistry::get('DeveloperPayment');

		$gst 						= '';
		if($type == 'application') {
			$payumoneyDetails	= $Payumoney->find('all',array('conditions'=>array('application_id'=>$id,'payment_status'=>'success'),'order'=>array('id'=>'desc')))->first();
			$appDetails 		= $ApplyOnlines->find('all',array('conditions'=>array('id'=>$id,'gstno like '=>'24%')))->first();
			//$installerDetails 	= $Installers->find('all',array('conditions'=>array('id'=>$appDetails->installer_id)))->first();
			$document_number 	= $payumoneyDetails->receipt_no;
			$document_date 		= date('d/m/Y',strtotime($payumoneyDetails->payment_date));
			if(!empty($appDetails)) {
				$legal_name 		= $appDetails->name_of_consumer_applicant.' '.$appDetails->last_name.' '.$appDetails->third_name;
				$trade_name 		= $legal_name;
				$address1 			= $appDetails->address1;
				$address2 			= $appDetails->address2;
				$pincode 			= $appDetails->pincode;
				$gst 				= $appDetails->gstno;
				$productDescription = "Application Fees";
				$Fees 				= $appDetails->disCom_application_fee;
				$taxAmount 			= $appDetails->jreda_processing_fee;
				$taxPer 			= '18';
				$total_cgst_value 	= $taxAmount/2;
				$total_sgst_value 	= $taxAmount/2;
				$total_igst_value 	= 0;
				$hsn_code 			= 9983;
				$state_code 		= 24;
				$request_type 		= 'Application';
			}
			
		} elseif($type == 'installer') {
			$payumoneyDetails	= $InstallerPayment->find('all',array('conditions'=>array('installer_id'=>$id,'payment_status'=>'success'),'order'=>array('id'=>'desc')))->first();

			$installerDetails 	= $Installers->find('all',array('conditions'=>array('id'=>$id,'GST like '=>'24%','LOWER(state)'=>'gujarat')))->first();

			$document_number 	= $payumoneyDetails->receipt_no;
			$document_date 		= date('d/m/Y',strtotime($payumoneyDetails->payment_date));
			if(!empty($installerDetails)) {
				$legal_name 		= $installerDetails->installer_name;
				$trade_name 		= $legal_name;
				$address1 			= $installerDetails->address;
				$address2 			= $installerDetails->city;
				$pincode 			= $installerDetails->pincode;
				$gst 				= $installerDetails->GST;
				$productDescription = "Installer Fees";
				$Fees 				= 10000;
				$taxAmount 			= 1800;
				$taxPer 			= '18';
				$total_cgst_value 	= $taxAmount/2;
				$total_sgst_value 	= $taxAmount/2;
				$total_igst_value 	= 0;
				$hsn_code 			= 9983;
				$state_code 		= 24;
				$request_type 		= 'Installer';
			}
			
		} elseif($type == 'reapplication') {
			$ReApplicationPaymentDetails	= $ReApplicationPayment->find('all',array('conditions'=>array('application_id'=>$id,'payment_status'=>'success'),'order'=>array('id'=>'desc')))->first();
			$appDetails 		= $Applications->find('all',array('conditions'=>array('id'=>$id,'GST like '=>'24%')))->first();
		
			//$installerDetails 	= $Installers->find('all',array('conditions'=>array('id'=>$appDetails->installer_id)))->first();
			$document_number 	= $ReApplicationPaymentDetails->receipt_no;
			$document_date 		= date('d/m/Y',strtotime($ReApplicationPaymentDetails->payment_date));
			if(!empty($appDetails)) {


				$legal_name 		= $appDetails->name_of_applicant;
				$trade_name 		= $legal_name;
				$address1 			= $appDetails->address1.', '.$appDetails->taluka.", ".$appDetails->city." - ".$appDetails->pincode;
				$address2 			= $appDetails->address1;
				$pincode 			= $appDetails->pincode;
				$gst 				= $appDetails->GST;
				$productDescription = "RE Application Fees";
				$Fees 				= $appDetails->application_fee;
				$taxAmount 			= $appDetails->gst_fees;
				$taxPer 			= '18';
				$total_cgst_value 	= $taxAmount/2;
				$total_sgst_value 	= $taxAmount/2;
				$total_igst_value 	= 0;
				$hsn_code 			= 9983;
				$state_code 		= 24;
				$request_type 		= 'REApplication';
			}
			
		} elseif($type == 'developer') {
			$devPaymentDetails	= $DeveloperPayment->find('all',array('conditions'=>array('installer_id'=>$id,'payment_status'=>'success'),'order'=>array('id'=>'desc')))->first();
			$developerDetails 	= $Developers->find('all',array('conditions'=>array('id'=>$id,'GST like '=>'24%','LOWER(state)'=>'gujarat')))->first();
			$document_number 	= $devPaymentDetails->receipt_no;
			$document_date 		= date('d/m/Y',strtotime($devPaymentDetails->payment_date));
			if(!empty($developerDetails)) {
				$legal_name 		= $developerDetails->installer_name;
				$trade_name 		= $legal_name;
				$address1 			= $developerDetails->address;
				$address2 			= $developerDetails->city;
				$pincode 			= $developerDetails->pincode;
				$gst 				= $developerDetails->GST;
				$productDescription = "Developer Fees";
				$Fees 				= $developerDetails->developer_fee;
				$taxAmount 			= $developerDetails->gst_fees;
				$taxPer 			= '18';
				$total_cgst_value 	= $taxAmount/2;
				$total_sgst_value 	= $taxAmount/2;
				$total_igst_value 	= 0;
				$hsn_code 			= 9983;
				$state_code 		= 24;
				$request_type 		= 'Developer';
			}
			
		} 
		
		if(!empty($gst) && strlen(trim($gst)) == 15) {
			$arrData['username']		="support-geda-gnr@gujarat.gov.in";
			//$arrData['username']		="testeway@mastersindia.co";
			$arrData['password']		="Geda@123";
			//$arrData['password']		="!@#Demo!@#123";
			$arrData['client_id']		="HhnpTbAIShucuusAHH";
			//$arrData['client_id']		="fIXefFyxGNfDWOcCWnj";
			$arrData['client_secret']	="A9Tawv5AwA36iYfzkmDq6SZt";
			//$arrData['client_secret']	="QFd6dZvCGqckabKxTapfZgJc";
			$arrData['grant_type']		="password";


			$curl_url 	= 'oauth/access_token';
			$curl_url 	= $this->APIURL.'oauth/access_token';
			$ch 		= curl_init();
			$arrRequest = array();
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($ch, CURLOPT_URL, $curl_url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
			curl_setopt($ch, CURLOPT_HEADER, 0);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrData));
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$ch_result = curl_exec($ch);
			/*print curl_errno($ch);
			print curl_error($ch);
			echo '<pre>';
			echo "Result 	= ".$ch_result;*/
			$arrResponse 	= json_decode($ch_result,2);
			//print_r($arrResponse);
			$curl_error 	= curl_error($ch);
			curl_close($ch);

			$EinvoiceEntity = $this->newEntity();
			$EinvoiceEntity->request_id				= $id;
			$EinvoiceEntity->request_type			= $request_type;
			$EinvoiceEntity->request_data			= json_encode($arrData);
			$EinvoiceEntity->response_data			= json_encode($arrResponse);
			$EinvoiceEntity->api_url				= $curl_url;
			$EinvoiceEntity->created				= $this->NOW();
			$this->save($EinvoiceEntity);
			//$arrResponse['access_token'] 			= 'ebd9b2708eb117c5db24da9ab1302a41b4f3cc06'; //c6ec08c2b85e7047587bfebac071a58ec5a3829c
			if(isset($arrResponse['access_token']) && !empty($arrResponse['access_token'])) {
				$arrInvoiceData['access_token']			= isset($arrResponse['access_token']) ? $arrResponse['access_token'] : '';
				$arrInvoiceData['user_gstin']			= '24AAATG1858Q1ZA';
				$arrInvoiceData['data_source']			= 'erp';
				$arrInvoiceData['transaction_details']	= array('supply_type' 	=> 'B2B',
																'charge_type' 	=> 'N',
																'igst_on_intra' => 'N',
																'ecommerce_gstin'=> ''
															);
				$arrInvoiceData['document_details']		= array("document_type"		=> "INV",
																"document_number"	=> $document_number,
																"document_date" 	=> $document_date);
				$arrInvoiceData['seller_details']		= array("gstin"			=> "24AAATG1858Q1ZA",    //AAATG1858Q PAN
																"legal_name"	=> "GUJARAT ENERGY DEVELOPMENT AGENCY",
																"trade_name"	=> "GUJARAT ENERGY DEVELOPMENT AGENCY",
																"address1"		=> "11,12, 4, Udhyog Bhavan",
																"address2"		=> "Sector 11",
																"location" 		=> "Gandhinagar",
																"pincode"		=> 382017,
																"state_code"	=> $state_code,
																"phone_number"	=> '',
																"email"			=> "");
				$arrInvoiceData['buyer_details']		= array("gstin"				=> $gst,
																"legal_name"		=> $legal_name,
																"trade_name"		=> $trade_name,
																"address1"			=> $address1,
																"address2"			=> $address2,
																"location"			=> $address2,
																"pincode"			=> $pincode,
																"place_of_supply"	=> $state_code,
																"state_code"		=> $state_code,
																"phone_number"		=> "",
																"email"				=> "");
				$arrInvoiceData['dispatch_details']		= array("company_name"	=> $legal_name,
																"address1"		=> $address1,
																"address2"		=> $address2,
																"location"		=> $address2,
																"pincode"		=> $pincode,
																"state_code"	=> $state_code);
				$arrInvoiceData['ship_details']			= array("gstin"			=> $gst,
																"legal_name"	=> $legal_name,
																"trade_name"	=> $trade_name,
																"address1"		=> $address1,
																"address2"		=> $address2,
																"location"		=> $address2,
																"pincode"		=> $pincode,
																"state_code"	=> $state_code);
				$arrInvoiceData['export_details']		= array("ship_bill_number"	=> "",
																"ship_bill_date"	=> "",
																"country_code"		=> "",
																"foreign_currency"	=> "",
																"refund_claim"		=> "",
																"port_code"			=> "",
																"export_duty"		=> '');
				$arrInvoiceData['payment_details']		= array("bank_account_number"	=> "",
																"paid_balance_amount"	=> '',
																"credit_days"			=> 0,
																"credit_transfer"		=> "",
																"direct_debit"			=> "",
																"branch_or_ifsc"		=> "",
																"payment_mode"			=> "",
																"payee_name"			=> "",
																"outstanding_amount"	=> 0,
																"payment_instruction"	=> "",
																"payment_term"			=> "");
				/*"preceding_document_details" => array(array("reference_of_original_invoice"	 => "",
																	"preceding_invoice_date"		=> "",
																	"other_reference"				=> "")),*/
				$arrInvoiceData['reference_details']	= array("invoice_remarks" 		=>"",
																"document_period_details"=> array("invoice_period_start_date"=> "",
																								  "invoice_period_end_date"	 => ""),
																
																"contract_details" 			=> array(array(
																							"receipt_advice_number"		=> "",
																							"receipt_advice_date"		=> "",
																							"batch_reference_number"	=> "",
																							"contract_reference_number"	=> "",
																							"other_reference"			=> "",
																							"project_reference_number"	=> "",
																							"vendor_po_reference_number"=> "",
																							"vendor_po_reference_date"	=> "")));
				$arrInvoiceData['additional_document_details']	= array(array(	"supporting_document_url"	=> "",
																				"supporting_document"		=> "",
																				"additional_information"	=> ""));
				$arrInvoiceData['value_details']				= array("total_assessable_value"					=> $Fees ,
																		"total_cgst_value"							=> $total_cgst_value,
																		"total_sgst_value"							=> $total_sgst_value,
																		"total_igst_value"							=> $total_igst_value,
																		"total_cess_value"							=> 0,
																		"total_cess_value_of_state"					=> 0,
																		"total_discount"							=> 0,
																		"total_other_charge"						=> 0,
																		"total_invoice_value"						=> ($Fees + $taxAmount),
																		"round_off_amount"							=> 0,
																		"total_invoice_value_additional_currency"	=> 0);
				$arrInvoiceData['ewaybill_details']				= array("transporter_id"				=> "",
																		"transporter_name"				=> "",
																		"transportation_mode"			=> "",
																		"transportation_distance"		=> "0",
																		"transporter_document_number"	=> "",
																		"transporter_document_date"		=> "",
																		"vehicle_number"				=> "",
																		"vehicle_type"					=> "");
				$arrInvoiceData['item_list']				= array(array(	"item_serial_number"		=> $id,
																			"product_description"		=> $productDescription,
																			"is_service"				=> "Y",
																			"hsn_code"					=> $hsn_code,
																			"bar_code"					=> "",
																			"quantity"					=> 1,
																			"free_quantity"				=> 0,
																			"unit"						=> "NOS",
																			"unit_price"				=> $Fees,
																			"total_amount"				=> $Fees,
																			"pre_tax_value"				=> 0,
																			"discount"					=> 0,
																			"other_charge"				=> 0,
																			"assessable_value"			=> $Fees,
																			"gst_rate"					=> $taxPer,
																			"igst_amount"				=> $total_igst_value,
																			"cgst_amount"				=> $total_cgst_value,
																			"sgst_amount"				=> $total_sgst_value,
																			"cess_rate"					=> 0,
																			"cess_amount"				=> 0,
																			"cess_nonadvol_amount"		=> 0,
																			"state_cess_rate"			=> 0,
																			"state_cess_amount"			=> 0,
																			"state_cess_nonadvol_amount"=> 0,
																			"total_item_value"			=> ($Fees + $taxAmount),
																			"country_origin"			=> "",
																			"order_line_reference"		=> "",
																			"product_serial_number"		=> "",
																			"batch_details"				=> array(
																											"name"			=> "",
																											"expiry_date"	=> "",
																											"warranty_date"	=> ""),
																			"attribute_details"			=> array(array(
																									"item_attribute_details"=> "",
																			  						"item_attribute_value"	=> ""))
																		));
				$curl_url 	= $this->APIURL.'generateEinvoice';
				//echo 'API URL- '.$curl_url;
				//echo '<br>Request Data'.json_encode($arrInvoiceData);

				$ch 		= curl_init();
				$arrRequest = array();
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
				curl_setopt($ch, CURLOPT_URL, $curl_url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
				curl_setopt($ch, CURLOPT_HEADER, 0);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrInvoiceData));
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				$ch_result = curl_exec($ch);
				//print curl_errno($ch);
				//print curl_error($ch);
				//echo '<pre>';
				//echo "<br>Result 	= ".$ch_result;
				$arrResponse 	= json_decode($ch_result,2);
				//print_r($arrResponse);

				$curl_error 	= curl_error($ch);
				curl_close($ch);

				$EinvoiceEntity = $this->newEntity();
				$EinvoiceEntity->request_id				= $id;
				$EinvoiceEntity->request_type			= $request_type;
				$EinvoiceEntity->request_data			= json_encode($arrInvoiceData);
				$EinvoiceEntity->response_data			= json_encode($arrResponse);
				$EinvoiceEntity->api_url				= $curl_url;
				$EinvoiceEntity->created				= $this->NOW();
				$this->save($EinvoiceEntity);
				//pr($arrInvoiceData);
				if(isset($arrResponse['results']['message']['EinvoicePdf']) && !empty($arrResponse['results']['message']['EinvoicePdf'])) {
					if($type == 'application') {
						$ApplyOnlinesOthers->updateAll(array('e_invoice_url'=>$arrResponse['results']['message']['EinvoicePdf']),array('application_id'=>$id));
					} elseif($type == 'installer') {
						$Installers->updateAll(array('e_invoice_url'=>$arrResponse['results']['message']['EinvoicePdf']),array('id'=>$id));
					} elseif($type == 'reapplication') {
						$Applications->updateAll(array('e_invoice_url'=>$arrResponse['results']['message']['EinvoicePdf']),array('id'=>$id));
					} elseif($type == 'developer') {
						$Developers->updateAll(array('e_invoice_url'=>$arrResponse['results']['message']['EinvoicePdf']),array('id'=>$id));
					}
					
				}
			}
			
		}

	} 
	
	
}
?>