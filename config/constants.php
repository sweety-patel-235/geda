<?php
/************************************************************
* File Name : constant.php 									*
* purpose	: Application constant file 					*
* @package  : 												*
* @author 	: Khushal Bhalsod								*
* @since 	: 28/03/2016									*
************************************************************/

###################### URL CONSTANT ######################
define('WEB_URL',URL_HTTP);
define('WEB_ADMIN_PREFIX','/admin/');
define('WEB_ADMIN_URL',URL_HTTP.'admin/');
define('IMAGE_URL',WEB_URL."img/");
define('REFERER_HOST',(isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:''));
define('SITE_ROOT_DIR_PATH',(isset($_SERVER['DOCUMENT_ROOT'])?$_SERVER['DOCUMENT_ROOT']:''));
define('WEB_PATH_PLUGIN',SITE_ROOT_DIR_PATH.'/plugins');
//define('ATTACHMENT_EMAIL_TEMP_FOLDER', SITE_ROOT_DIR_PATH."app/tmp/");

###################### ENVIRONMENT CONSTANT ######################
define("ENVIRONMENT","LIVE - ");
define('DATE_FORMAT','Y-m-d');
define('DB_TO_JS_DATE_FORMAT','d-M-Y');
define('JS_DATE_FORMAT','dd-M-yyyy');
define('DB_DATE_FORMAT','%d-%M-%Y');
define('TIME_FORMAT','g:i A');
define('LIST_DATE_FORMAT','j-M-Y g:i A');
define('HMAC_HASH_PRIVATE_KEY', '9XTMgu0h0R14lm8vO2cQYG8SFMe4A50j');

###################### MOBILE APPLICATION VERSION ######################
define('OLD_VERSION', '1.0');
define('CURRENT_VERSION', '1.0');

###################### TAX DETAILS ######################
define('VAT_TIN','24074203902');
define('CST_NO','24574203902');
define('SERVICE_TAX','212WQW2323W222');
define('PAN_NO','33434WEWED334343');

###################### EMAIL, SMS MANAGEMENT ######################
define('SEND_SMS', true);
define('SEND_EMAIL', true);
const SMS_USER			= "rtgeda";
const SMS_PASS			= "rtg@7842";
const SMS_GATWAY_URL	= 'http://msg.icloudsms.com/rest/services/sendSMS/sendGroupSms?AUTH_KEY=5ef69531c3dbeecd35dfc9ec6ec5c8&message=[MESSAGE]&senderId=RTGEDA&routeId=1&mobileNos=[MOBILE]&smsContentType=english&entityid=1701159178412478354&tmid=1478523690&templateid=[TEMPLATE_ID]';

###################### ERROR MESSAGE CONSTANT ######################
define('ERROR_MESSAGE', 'An error has occured, please try again later');

###################### COPY RIGHT CONSTANT USED IN FOOTER SECTION ######################
define('COPY_RIGHT_NAME',"Copyright &copy; ".date("Y")." Yugtia.com. All rights reserved");

###################### PROJECT MANAGEMENT ######################

define('COST_FOR_GUJARAT', '0.69');

define('COST_UPTO_10_KW', '0.70');
define('COST_FOR_10_TO_100_KW', '0.65');
define('COST_FOR_100_TO_500_KW', '0.60');
define('COST_FOR_500_TO_1000_KW', '0.55');
define('COST_FOR_1000_TO_10000_KW', '0.50');
define('COST_ABOVE_10000_KW', '0.45');



define('RUF_RESIDENT_FOOT_LIMIT', '861.11');
define('RUF_COMMERCE_FOOT_LIMIT', '3229.1');
define('RUF_RESIDENT_METER_LIMIT', '80');
define('RUF_COMMERCE_METER_LIMIT', '300');
define('RUF_RESIDENT', '30');
define('RUF_COMMERCE', '50');
define('RUF_INDUSTRIAL', '80');
define('PERFORMANCE_RATIO', '77'); //changed from 76.3 to 70 on 03-April-2017 as per mail, Changed from 70 to 77 and 1.1 is removed. ON 25-07-2017 
define('ASSUMPTION_INTEREST', '12');
define('LOAD_FECTORE', '30');
define('LOAD_FECTORE_INCREASE', '3');

###################### FOR ASSUMPTION ######################
define('DEBT_FRATION', '70');
define('INTEREST_RATE_ON_LOAN', '12'); //changed from 13.5 to 12 on 03-April-2017 as per mail
define('O_AND_M_COST', '0.75');
define('RATE_DEPRECATION_FOR_10', '6');
define('INSURANCE_COST', '0.35');
define('RATE_DEPRECATION_NEXT_15', '2');
define('RATE_OF_ACCELERATED_DEPRE', '40');
define('O_AND_M_ESCLATION', '5.72');

###################### CONTACT EMAIL CONSTANT ######################
define("SEND_QUERY_EMAIL","info.geda@ahasolar.in");
define("SEND_QUERY_EMAIL_JHARKHAND","info.jreda@ahasolar.in");
define("FROM_ACTIVATION_EMAIL","jayshree.tailor@yugtia.com");//do-not-reply@ahasolar.in

define("SEND_QUERY_INSTALLER_BCC_EMAIL","projects@ahasolar.in");
define("SEND_QUERY_INSTALLER_SUBJECT","User query for installation of rooftop solar photovoltaic system");

###################### CONTACT EMAIL CONSTANT ######################
define('ANNUAL_DEGREDATION','1');
define('ROE','15');
define('DISCOUNT_FACTOR','10.81');
define('CAPITAL_SUBSIDY','0');
define('MORATORIUM_PERIOD',1);
define('LOAN_TENURE',10);
define('MIN_ALTERNATE_TAX_RATE','20.01');	
define('ALTERNATE_TAX_RATE',10);
define('CORPORATE_TAX_RATE','34.64');
define('RATE', '500'); // user/month

###################### Monthly Saving Constant ######################
define('BILL_INCREASE','3');
define('ENERGY_CON_INCREASE','3');
define('PV_GENERATION_DECREASE','1');

###################### Monthly Saving Constant ######################
define('INVERTER_ELECTRICITY_COST',10);
define('GENERATOR_ELECTRICITY_COST',15);

###################### Cost Subsidy Constant ######################
define('SUBSIDY_PERC',30);
define('OTHER_SUBSIDY',0);

###################### Admin Template Constant ######################
define('PAGE_RECORD_LIMIT', 10);
define('BLOCK_APPLICATION',0);

###################### STATE SPECIFIC Constant ######################
define("MEMBER_ALLOWED","4");
define("MEMBER_NOT_ALLOWED","-1");
define("MEMBER_NOT_ALLOWED_PORTAL_4","<a href='https://www.ahasolar.in/'>www.ahasolar.in</a>");
###################### STATE SPECIFIC Constant ######################

###################### SUBSIDY COVERLETTER ADDRESS ######################
define("SUBSIDY_COVER_LETTER_ADDRESS","To:<br />The Director<br />Gujarat Energy Development Agency (GEDA)<br />4th Floor, Block No. 11-12, Udyog Bhavan<br />Gandhinagar - 382 017, Gujarat, INDIA<br />");
###################### SUBSIDY COVERLETTER ADDRESS ######################

##################### OTP MESSAGE CONSTANT ##########################
define('OTP_VERIFICATION','Thank you for applying for Solar PV System at GEDA.Your One Time Password(OTP) ##ACTIVATION_CODE## for final submission of Application No. ##application_no##.The OTP will be valid for 30 minutes. – Team GEDA');
define('OTP_RESEND','Your activation code is ##ACTIVATION_CODE## for Application No. ##application_no##.- GEDA ');
define('APPLICATION_SUBMITED','Your Application no. ##application_no## for a 
Rooftop Solar PV (RTPV) system has been submitted successfully. The installer selected by you is ##installer_name##. Thank you. - GEDA ');
define('GEDA_APPROVAL','GEDA has approved the application no. - ##application_no## of the Rooftop Solar PV (RTPV) system. Kindly make the payment on GEDA portal. For more details, kindly check at https://geda.ahasolar.in/ or contact GEDA office. Thank you. - GEDA ');
define('GEDA_REJECTED','GEDA has rejected the application no. - ##application_no## of the Rooftop Solar PV (RTPV) system. For more details, kindly check at https://geda.ahasolar.in/ or contact GEDA office. Thank you. - GEDA ');

define('DOC_VERIFIED','Your Application No. ##application_no## for a Rooftop Solar PV (RTPV) system has been verified by the DisCom. Thank you. - GEDA ');

define('DRAWING_APPLIED','CEI Drawing Application Ref. No. for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system is enterned on GEDA Portal by your installer. Thank you. - GEDA ');
define('CEI_APP_NUMBER_APPLIED','CEI Application Number is entered in GEDA Portal for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system. Thank you. - GEDA ');
define('FEASIBILITY_APPROVAL','Technical Feasibility for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been done by the DisCom. For more details, kindly check at www.geda.ahasolar.in. Thank you.');
define('FIELD_REPORT_REJECTED','Technical Feasibility for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been rejected by the DisCom. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');
define('APPROVED_FROM_CEI','CEI Drawing for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been approved by the CEI. Thank you. - GEDA ');

define('METER_INSTALLATION','METER INSTALLATION  for the Registration No.  ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been done by the DisCom. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');

define('CEI_INSPECTION_APPROVED','Inspection From CEI  for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been done by the CEI. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');
define('WORK_START','Work Started for a Rooftop Solar PV (RTPV) system having Registration No. ##geda_application_no## by your installer ##installer_name##. Thank you. - GEDA ');
define('WORK_EXECUTION','Work Execution details for Your Registration No. ##geda_application_no## of a Rooftop Solar PV (RTPV) system has been entered by installer ##installer_name##. Thank you. - GEDA ');
define('CLAIM_SUBSIDY','Subsidiy Claimed for the Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been done. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');
define('CANCELLED_REOPEN','Registration No. ##geda_application_no## of the Rooftop Solar PV (RTPV) system has been reopened by GEDA. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');
define('OTP_VERIFICATION_RESET','Your Application has been reset by GEDA.Your One Time Password(OTP) ##ACTIVATION_CODE## for Application No. ##application_no##. The OTP will be valid for 30 minutes. - GEDA ');

define('DELETE_APPLICATION_APPROVAL','Delete request for the Application No. ##application_no## of the Rooftop Solar PV (RTPV) system has been approved by GEDA. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');
define('DELETE_APPLICATION_REJECTION','Delete request for the Application No. ##application_no## of the Rooftop Solar PV (RTPV) system has been rejected by GEDA. For more details, kindly check at www.geda.ahasolar.in. Thank you. - GEDA ');

define('OTP_VALIDITY_TIME','30'); //'30' minutes
define('GOOGLE_MAP_API_KEY','AIzaSyD2eOQS5UOkDNZQX6OY0YBcBSsfiErrjh0');
define('RESIDENTIAL_CAT_PER','85');
define('SOCIAL_CAT_PER','12');
define('RESIDENTIAL_TOTAL_CAPACITY','165'); //capacity in kW
define('GUVNL_USAGE_MINUTES','25');
define('API_MAINTENANCE_MODE','0');
define('PAYMENT_METHOD','hdfc'); //hdfc for ccavnue and payu for payumoney
//define('DATE_STOP_CATEGORYB',date('2018-12-15 23:59:59'));
define('DATE_STOP_CATEGORYB','2018-12-18 23:59:59');
define('BLOCK_APPLICATION_MESSAGE','The Unified Single Window Rooftop PV Portal of GEDA Maintenance work is in progress and the portal shall be live for further application process from 11:00 AM on 4 December 2018. Inconvenience caused is deeply regretted.');

define('SOCIAL_SECTOR',1); //Social sector true or false
define('GOVERMENT_AGENCY',1); // Government agency with dropdown categories
define('SUBSIDY_CLAIM',1); // Subsidy claim feature enable or not
define('SHOW_SUBSIDY_EXECUTION',1); //if disclamer subsidy true in apply online then subsidy will display otherwise won't display
define('SOCIAL_SECTOR_PAYMENT',1); //social sector payment on or off 0 for off and 1 for on
define('MINIMUM_CAPACITY','1.00'); //minimum pv capacity allowed for category A
define('MAXIMUM_CAPACITY','1.30'); //maximum pv capacity allowed for category A
define('APPLICATION_ID_START',41868); //application start number from which between capacity validation execute
define('DATE_STOP_1_1_3',date('2019-01-15 23:59:59')); // Date to stop 1 and 1.3 also
define('SPIN_PRODUCTTION','1'); // 0 for spin developement environment and 1 for spin live environment
define('SPIN_API_DEV_URL','https://solarrooftop.gov.in/spin-web-service-testing/'); // Spin development URL
define('SPIN_API_DEV_TOKEN','0657a'); // Spin development token
define('SPIN_API_LIVE_URL','https://solarrooftop.gov.in/spin-web-service/'); // Spin live URL
define('SPIN_API_LIVE_TOKEN','4064b'); // Spin live token
define('SPIN_APPROVAL_ID','357'); // Spin geda approval id
define('SPIN_APPROVAL_NO','318/20/2018-GCRT (Part-1)'); // Spin geda approval no
define('SPIN_FIN_YEAR','2017-2018'); // Spin geda fin year
define('SPIN_APPROVED_CAPACITY','95000'); // Spin geda approved capacity
define('SPIN_APPROVAL_DATE','15-02-2018'); // Spin geda approval date
define('SPIN_UNIQUE_ID_APPEND',''); // Spin unique id  prepend string
define('CATEGORY_A_TOTAL_CAPACITY','290'); // total capacity for category A for which deduction 0
define('CATEGORY_B_TOTAL_CAPACITY','165'); // total capacity for category B for which deduction 0
define('DEDUCTION_PERCENTAGE','5'); // Deduction percentage

define('GOOGLE_MAP_KEY','AIzaSyD2eOQS5UOkDNZQX6OY0YBcBSsfiErrjh0'); // Google map key for gujarat

define('NON_RES_SOCIAL_SECTOR','1'); // 1 for non residential compulsary select social sector 0 for non resiential no need to check social sector
define('IS_CAPTIVE_OPEN','1'); // 1 for captive changes related projects and apply onlines radio button will work.

define("CAPTCHA_KEY","6Le_81cUAAAAAPV0ndpsBU1DoBUVeltmqR0tMeML"); //Google capcha 
define("CAPTCHA_SECRET_KEY","6Le_81cUAAAAAPvv8W94n5gp1hIN6wnMVfGZg7fW"); //Google capcha 
define("GEDA_PAYMENTRECIEPT_MAIL","manager-aa@geda.org.in"); //payment reciept mail goes to 
define('AUTHORITY_EMAIL_ACCOUNT','admin@ahasolar.in'); //Main account which having application Delete and remove common meter feature.
define('CAN_DELETE_APPLICATION_MEMBER','1'); //1 for display delete applications for member subdivision

define('STOP_ADD_APPLICATION_MSG','New registration of Rooftop Solar PV application is not allowed.');

define('OPEN_NEW_QUATA','2020-07-13 00:00:00');

define('INSTALLER_PAYMENT_FEES', 10000);
define('INSTALLER_GST_AMOUNT', 1800);
define('INSTALLER_REGISTRATION', 1); //on/off installe registartion, varify otp and payment
define('INSTALLER_OTP','The One Time Password (OTP) for Mobile no. verification is ##OTP##. Kindly enter the details at ##SITE_URL## to verify and complete the registration process at GEDA Rooftop Solar Portal. Thank you.');


define('APPLICATION_NEW_SCHEME_DATE','2021-01-01 00:00:00');

define('GOVERMNET_AGENCY_DOCUMENTNOT','2021-02-02 14:30:00');

define('PRICE_PER_KW_GT1MW','26'); //Price per kW when pv capacity > 1000

/**************** Couchdb Config ******************/
define('COUCHDB_HOST','127.0.0.1'); //192.168.1.38
define('COUCHDB_PORT',5984);
define('COUCHDB_USER','AHA');
define('COUCHDB_PASSWORD','123');
define('COUCHDB_DATABASE','geda');
define('COUCHDB_HTTP','http');
/**************** Couchdb Config ******************/
define('CAN_DOWNLOAD_PDF','1');
define('ALLOW_ALL_ACCESS',array('1323','1324','1325','1326','1327','1328','1359','1361','1334','1336','1409','1410','1405','1329'));

define('CAPTCHA_DISPLAY','0'); // 0 for not display captcha, 1 for display captcha
define('CORRIGENDUM_LETTER_DATE','2021-08-21 17:30:00'); //After this time CORRIGENDUM_LETTER should be downloaded.
define('BEARER_TOKEN','eyJ4NXQiOiJNell4TW1Ga09HWXdNV0kwWldObU5EY3hOR1l3WW1NNFpUQTNNV0kyTkRBelpHUXpOR00wWkdSbE5qSmtPREZrWkRSaU9URmtNV0ZoTXpVMlpHVmxOZyIsImtpZCI6Ik16WXhNbUZrT0dZd01XSTBaV05tTkRjeE5HWXdZbU00WlRBM01XSTJOREF6WkdRek5HTTBaR1JsTmpKa09ERmtaRFJpT1RGa01XRmhNelUyWkdWbE5nX1JTMjU2IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiJnc2RjYXBpZ3dzdSIsImF1dCI6IkFQUExJQ0FUSU9OIiwiYXVkIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsIm5iZiI6MTYyNzM4NTkxMiwiYXpwIjoiZGtqbTJ6Mm92NlBibDZEOWplR0dRdUV5R29jYSIsInNjb3BlIjoiZGVmYXVsdCIsImlzcyI6Imh0dHBzOlwvXC9nc2RjYXBpZ2F0ZXdheWV4dC5ndWphcmF0Lmdvdi5pbjo5NDQzXC9vYXV0aDJcL3Rva2VuIiwiZXhwIjoxOTg3Mzg1OTEyLCJpYXQiOjE2MjczODU5MTIsImp0aSI6IjFlYWU0MzNjLTVhOGQtNDE2MS05NGIzLWY2OThmNTdmYjU3MiJ9.c1N4EbVdEbdF9fgt3CvBeVtE4vIhavADdtJ3CFt17jf7TAw4LW__IB4tJi6CcgrVC6uVs3_7Wncp5h_tGF2XCc8PSqpeujiHAxtkzGhXMmNjlrBQkyhZccy8zPFQQDjUODvRWKLqfY5Q7qffFgoJz-DQCteP7eXjox3P8Rjm9MwLo0dRjrjHSvCYFjDdZRfqKxByyxxuWBluwGDvQJlsuRZeWCQ_37xkwDN79iq-DFhPn6qcrPiaFw8f4pRUseXLTq9DQeckZRiZKHiN0I3CeAVsXL9sJJ3G1NTv7syyuINeCEQ9i669L5QlhQkaKZWTFOgpwijp59Y7w_-AzqTQDA'); //bearer token required for third party request

define('KUSUM_FEES','5'); //Price per kW
define('KUSUM_GST_PER','18'); // GST percentage
define('PAYUMONEY_KUSUM',1); // Payumoney flag

define('REFUNDED_AMOUNT','5'); //Price per kW fees return
define('REFUNDED_GST_PER','18');  // GST percentage fees return

define('ALLOW_DELETE_APPLICATION_ACCESS',array('1323','1324','1325','1326','1327','1328','1359','1361','1334','1336','1409','1410','1405','493','494','495','496','497','498'));


define('GUVNL_API_URL', 'https://epaydg.guvnl.in:8001/outer_client_json.php');
define('GUVNL_SALT_KEY', 'bKmfEMWNi4slwgiD');
define('GUVNL_API_URL_TEST', 'http://202.8.125.26:8002/guvnl_api_test/outer_client_json.php');
define('GUVNL_SALT_KEY_TEST', 'JK4qUVCEWkuQvBYE');
define("API_MODE",1);
if (API_MODE == 0) {
	define('USR_NM', 'SURYA_GUJARAT');
	define('USR_PWD', 'SurY@gUj@Guv');
} else {
	define('USR_NM', 'SURYA_GUJARAT');
	define('USR_PWD', 'SurY@gUj@Guj@T');
}
define("TPL_API_URL","https://connect.torrentpower.com/tplwss/geda/call_geda.php");
define("TPL_SALT_KEY","test");
define("TPL_API_URL_TEST","https://connect.torrentpower.com/tplwss/geda/call_geda.php");
define("TPL_SALT_KEY_TEST","test");
define("INVALID_API_MESSAGE","You're not authorized to access this page.");
define("INVALID_API_AUTH_MESSAGE","Authorized token invalid.");

define('STOP_ADD_APPLICATION','2023-10-04 18:00:00');
define('ALLOW_DEVELOPERS_ALL_ACCESS',array(1485,1486,1487,1488,1489,1490,1454,1323,1330));

define('COLOR_BLUE','#307FE2');
define('COLOR_ORANGE','#FF6A39');
define('COLOR_GREEN','#4CC972');
define('COLOR_YELLOW','#FFB81C');


define('RE_SHORT_NAME','RE');

define("New_updates",'2024-04-01');