<html>
<head>
<title> Non-Seamless-kit</title>
</head>
<body>
<center>

<?php include('Crypto.php');?>
<?php 

	error_reporting(0);
	
	$merchant_data='';
	$working_key='89C2E26FBDE51C08E358BE73C41C9184';//Shared by CCAVENUES
	$access_code='AVGM80FJ87AJ09MGJA';//Shared by CCAVENUES
	echo '<pre>';
	//print_r($_POST);
	foreach ($_POST as $key => $value){
		$merchant_data.=$key.'='.$value.'&';
	}
	/*tid=1541397456568&merchant_id=193106&order_id=123654789&amount=1.00¤cy=INR&redirect_url=http://localhost/Non_Seamless_kit/ccavResponseHandler.php&cancel_url=http://localhost/Non_Seamless_kit/ccavResponseHandler.php&language=EN&billing_name=Charli&billing_address=Room no 1101, near Railway station Ambad&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Chaplin&delivery_address=room no.701 near bus stand&delivery_city=Hyderabad&delivery_state=Andhra&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=additional Info.&merchant_param2=additional Info.&merchant_param3=additional Info.&merchant_param4=additional Info.&merchant_param5=additional Info.&promo_code=&customer_identifier=&*/
	$merchant_data = "order_id=76019054&merchant_id=193106&currency=INR&amount=2050&redirect_url=https://geda.ahasolar.in/payutransfer/success&cancel_url=https://geda.ahasolar.in/payutransfer/cancel&tid=1541397456568&language=EN&billing_name=Charli&billing_address=Room no 1101, near Railway station Ambad&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Chaplin&delivery_address=room no.701 near bus stand&delivery_city=Hyderabad&delivery_state=Andhra&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=additional Info.&merchant_param2=additional Info.&merchant_param3=additional Info.&merchant_param4=additional Info.&merchant_param5=additional Info.&promo_code=&customer_identifier=&";
//echo $merchant_data;
	//$encrypted_data=encrypt($merchant_data,$working_key); // Method for encrypting the data.
	//echo $encrypted_data;
	$merchant_data='tid=1616737126587&merchant_id=193106&order_id=123654789&amount=1.00&currency=INR&redirect_url=https://geda.ahasolar.in/Payutransfer/success&cancel_url=https://geda.ahasolar.in/Payutransfer/cancel&language=EN&billing_name=Charli&billing_address=Room no 1101&billing_city=Indore&billing_state=MP&billing_zip=425001&billing_country=India&billing_tel=9876543210&billing_email=test@test.com&delivery_name=Chaplin&delivery_address=room &delivery_city=Hyderabad&delivery_state=Andhra&delivery_zip=425001&delivery_country=India&delivery_tel=9876543210&merchant_param1=additional Info.&merchant_param2=additional Info.&merchant_param3=additional Info.&merchant_param4=additional Info.&merchant_param5=additional Info.&promo_code=&customer_identifier=&';
$encrypted_data='38300cf5c6be87f1d604186c14ab28bcf04944ff7caad309e07ef78859514f9eb69f52a1b0e776583a5219e4fb94fbea8f85423fe98d37c7654c672b688ac5f0903cdbc490b3230cad1b91a88047c41667eeab4eed6354fb1c74c3175d896dbdac26ce907fd4a667ae945a5ce043084310cbf85549dd919b555f6e0313c211e171567d68935c6475e11308125b7e86a5b11785a1f3d766e1a584a009191e118945fd08ec6b095a62462c1fe0d21139a05d1e1d140417b09f843df6cabdc58f6e86b023182b89cce098dff230dc7f19ba9f35aef4c0c30102fc3a8750a842b0d574989bd414c2307fd24b266e265c8e79a0f51e08b22470d10a8a07af8a4ed4de4e34bfed85b0d4804f2d030f2ed85c06a4220143df8a92d9f5b1795f65147a84faf612c9a17bc64177cd6a2a9adf8745830be040abf37c4c030ebdc22e383c8cee0d632e4e7e2f49bd4780cdf8c9f9b8c77efef98a98982a92646882ef1cdc96c84adcc7da68f95841d03b1583c60f2c45e34774289e9e3898c0f1f582c0e89512716a278bda90feec4afa0cc8b605bd54494d911d1c6aa38e4adb5ca09dea3945ac4415881f3b6179dcf045b26077c9ac1673c9996dbef066c24b12316656a53a8fe509a637f9c7853849b183cc52b3cf1257daac1d214d24dcfb76a8c22fa63c687f7eef74702cda9a8eb7cb4269d59029dcdc0ce43d008757124be61b129cf42a5e8dfbdae07045f59f8bc6af1d0497e6b5c827c6884faf03a8fdb70aa04f422dcd90d8004568c0abe412bb31d3fba42b350a6fb0700980bb7f139d25317477b534d1af54eaa1c32eb5857cdece7cf56965289e6721cdddd759946568e6f8abcbed8dbbde5665bd39399b57b1e407a32ef3d47f152acdd3143ad65237b80e8d01cb9f4650354731b390e2431541358d78089b66bd33fd323d0f061034514b8f34dd3ca8fa480973677f33aa6b0ee60fce166d2106536ed786f830c8e2c1a3b76204e3d37e06329eb365f62e2cac82234b2f7b198f665842657e3531b4bd9cfea3754aaceecd7c8a6fdf094b305574';
echo $encrypted_data;

	//exit;
?>
<form method="post" name="redirect" action="https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction"> 
<?php
echo '<input type=hidden name=encRequest value="'.$encrypted_data.'">';
echo '<input type=hidden name=access_code value="'.$access_code.'">';
?>
</form>
</center>
<script language='javascript'>//document.redirect.submit();</script>
</body>
</html>