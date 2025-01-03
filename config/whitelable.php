<?php
/************************************************************
* File Name : whitelabel.php 								*
* purpose	: Application whitelabel constant file 			*
* @package  : 												*
* @author 	: Khushal Bhalsod								*
* @since 	: 28/03/2016									*
************************************************************/

###################### URL CONSTANT ######################
define("URL_HTTP","http://dev.geda.in/");
define("ADMIN_PATH","admin/");
define('SECRET_ENC_KEY','!7l@S*3h7_s54P-e543lp');

###################### EMAIL CONSTANT ######################
define("ERROR_EMAIL","kalpak@yugtia.com");
define("ERROR_EMAIL_FROM","kalpak@yugtia.com");
define('DEFAULT_ADMIN_EMAIL','info.geda@ahasolar.in');

###################### COMAPNY CONSTANT ######################
define('COMAPNY_WEBSITE','https://www.solar.com');
define('COMPANY_NAME','AHASOLAR PRIVATE LIMITED');
define('COMPANY_PRIVACY_POLICY',URL_HTTP.'privacy');
define('COMPANY_LOGO','yugtia_logo.png');
define('ADMIN_COMPANY_NAME','GEDA-ADMINISTRATOR-PANEL');
define('PAGE_TITLE',ADMIN_COMPANY_NAME);
define('CUSTOMER_CARE_PHONE_NO','');
define('COMPANY_WEBSITE_LINK','https://www.yugtia.com');
define('COMPANY_LOGO_IMAGE_LINK','<img src="/img/yugtia_logo.png" alt="Yugtia" height="55" border="0" title="Yugtia" />');

############################################## WHITELABELING CONFIGURATION ###################################
define('PRODUCT_NAME','GEDA | Unified Single Window Rooftop PV Portal');
define('PRODUCT_DOMAIN',URL_HTTP);
define('PRODUCT_INFO_EMAIL','info.geda@ahasolar.in');
define('COMPANY_INFO_EMAIL','info.geda@ahasolar.in');
define('COMPANY_CONTACT_NO','');
define('COMPANY_ADDRESS','Gujarat Energy Development Agency,<br />4th Floor, Block No. 11-12, Udyog Bhawan,<br />Gandhinagar - 382 017, Gujarat');
############################################## WHITELABELING CONFIGURATION ###################################

###################### FOLDER PATH CONSTANT ######################
define('PROFILE_PATH','img/profile/');
define('PROFILE_URL',URL_HTTP.'img/profile/');
define('DOWNLOAD_PATH','img/download_files/');
define('DOWNLOAD_URL',URL_HTTP.'img/download_files/');
define('INSTALLER_PROFILE_PATH','img/installer_profile/');
define('INSTALLER_PROFILE_URL',URL_HTTP.'img/installer_profile/');
define('MNRE_IMG_URL',URL_HTTP.'img/mnre_logo.png');
define('GEDA_IMG_URL',URL_HTTP.'img/geda.png');
define('CREST_IMG_URL',URL_HTTP.'img/crest.png');
define('HAREDA_IMG_URL',URL_HTTP.'img/hareda.png');
define('INSTALLER_TERMS_PATH','data/installer_terms/');
define('INSTALLER_TERMS_URL',URL_HTTP.'data/installer_terms/');
define('SITE_SURVEY_PATH','data/site_survey/');
define('SITE_SURVEY_URL',URL_HTTP.'data/site_survey/');
define('REPORT_DATA_PATH','data/report_data/');

define('COMMISSIONING_DATA_PATH','data/commissioning_data/');
define('DEFAULT_IMAGES',URL_HTTP.'img/mnre_logo.png');

define('APPLYONLINE_PATH','img/applyonline_docs/');
define('APPLYONLINE_URL',URL_HTTP.'img/applyonline_docs/');
define('EXECUTION_PATH','data/execution_data/');
define('EXECUTION_URL',URL_HTTP.'data/execution_data/');
define('FEASIBILITY_PATH','data/feasibility_data/');
define('FEASIBILITY_URL',URL_HTTP.'data/feasibility_data/');
define('APPLYONLINE_DATA_PATH','img/applyonline_docs/');

define('APPLYONLINE_RFID_PATH','img/applyonline_rfid_docs/');
define('APPLYONLINE_RFID_URL',URL_HTTP.'img/applyonline_rfid_docs/');
###################### ERROR DIRECTORY CONSTANT ######################
define('ERROR_URL', '/errors/');
define('LEADS_PATH','img/leads/');
define('WORKORDER_PATH','data/workorder_data/');
define('WORKORDER_URL',URL_HTTP.'data/workorder_data/');
define('SUBSIDY_PATH','data/subsidy_data/');
define('SUBSIDY_URL',URL_HTTP.'data/subsidy_data/');
define('UPDATEDETAILS_PATH','data/updatedetails_data/');
define('UPDATEDETAILS_URL',URL_HTTP.'data/updatedetails_data/');

define('INSPECTION_PATH','data/inspection_docs/');
define('INSPECTION_URL',URL_HTTP.'data/inspection_docs/');

define('MISREPORT_PATH','data/mis_report/');
define('MISREPORT_URL',URL_HTTP.'data/mis_report/');

define('APPLYONLINE_KUSUM_PATH','data/applyonline_kusum_docs/');
define('APPLYONLINE_KUSUM_URL',URL_HTTP.'data/applyonline_kusum_docs/');

define('FEES_RETURN_PATH','data/fees_return/');
define('FEES_RETURN_URL',URL_HTTP.'data/fees_return/');

define('DEVELOPER_PROFILE_PATH','img/developer_profile/');
define('DEVELOPER_PROFILE_URL',URL_HTTP.'img/developer_profile/');

define('APPLICATIONS_PATH','img/applications/');
define('APPLICATIONS_URL',URL_HTTP.'img/applications/');

define('APPLICATIONS_DEVELOPER_PERMISSION_PATH','img/applications_developer_permission/');

define('DEVELOPER_WORKORDER_PATH','img/developer_workorder/');
define('DEVELOPER_WORKORDER_URL',URL_HTTP.'img/developer_workorder/');

define('WTG_PATH','img/wtg_file/');
define('WTG_URL',URL_HTTP.'img/wtg_file/');

define('AGREEMENT_PATH','img/agreement_file/');
define('AGREEMENT_URL',URL_HTTP.'img/agreement_file/');

define('Internal_Clashed_PATH','img/Internal_clashed_uploadfile/');
define('Internal_Clashed_URL',URL_HTTP.'img/Internal_clashed_uploadfile/');


define('Member_Approve',1);
define('Member_Reject',2);
define('Developer_clashing',3);

define('Developer_Accept',1);
define('Developer_Reject',2);
define('Developer_Applicable',3);