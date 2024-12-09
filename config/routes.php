<?php
/**
 * Routes configuration
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Core\Plugin;
use Cake\Routing\Router;

/**
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 *
 */
Router::defaultRouteClass('Route');
Router::connect('/apply-online-list/:page', array(
	'controller' => 'ApplyOnlines',
	'action' => 'applyonline_list'
), array(
	'pass' => array(
		'page'
	),
	'page' => '[\d]+'
));
Router::connect('/projects/:page', array(
	'controller' => 'Projects',
	'action' => 'index'
), array(
	'pass' => array(
		'page'
	),
	'page' => '[\d]+'
));
Router::connect('/projects', array(
	'controller' => 'Projects',
	'action' => 'index'
));
Router::connect('/installers/:page', array(
	'controller' => 'Installers',
	'action' => 'index'
), array(
	'pass' => array(
		'page'
	),
	'page' => '[\d]+'
));
Router::connect('/apply-online-kusum-list/:page', array(
	'controller' => 'ApplyOnlinesKusum',
	'action' => 'applyonline_list'
), array(
	'pass' => array(
		'page'
	),
	'page' => '[\d]+'
));
Router::connect('/applications-list/:page', array(
	'controller' => 'Applications',
	'action' => 'list'
), array(
	'pass' => array(
		'page'
	),
	'page' => '[\d]+'
));


Router::scope('/', function ($routes) {
	/**
	 * Here, we are connecting '/' (base path) to a controller called 'Pages',
	 * its action called 'display', and we pass a param to select the view file
	 * to use (in this case, src/Template/Pages/home.ctp)...
	 */
	$routes->connect('/', ['controller' => 'Users', 'action' => 'index']);
	$routes->connect('index', ['controller' => 'Users', 'action' => 'index']);
	$routes->connect('home', ['controller' => 'Users', 'action' => 'index']);

	/* Static Pages Routings */
	$routes->connect('privacy-policy', ['controller' => 'Static', 'action' => 'privacy_policy']);
	$routes->connect('about-us', ['controller' => 'Static', 'action' => 'about_us']);
	//$routes->connect('what-it-cost', ['controller' => 'Static', 'action' => 'whatit_cost']);
	$routes->connect('important-documents', ['controller' => 'Static', 'action' => 'whatit_cost']);
	$routes->connect('solar-pv-installer', ['controller' => 'Static', 'action' => 'solar_installer']);
	$routes->connect('faq', ['controller' => 'Static', 'action' => 'faq']);
	$routes->connect('contact-us', ['controller' => 'Static', 'action' => 'contact_us']);
	$routes->connect('terms-conditions', ['controller' => 'Static', 'action' => 'terms']);
	$routes->connect('solar-calculator', ['controller' => 'SolarCalculators', 'action' => 'solar_calculator']);
	$routes->connect('installer-registration/*', ['controller' => 'InstallerRegistrations', 'action' => 'installer_registration']);
	$routes->connect('developer-registration/*', ['controller' => 'DeveloperRegistrations', 'action' => 'developer_registration']);
	$routes->connect('developer-verify-otp/*', ['controller' => 'DeveloperRegistrations', 'action' => 'VerifyOtp']);
	$routes->connect('developer-resend-otp/*', ['controller' => 'DeveloperRegistrations', 'action' => 'ResendOtp']);
	$routes->connect('developer-payment/*', ['controller' => 'DeveloperRegistrations', 'action' => 'payment']);
	$routes->connect('verify-otp/*', ['controller' => 'InstallerRegistrations', 'action' => 'VerifyOtp']);
	$routes->connect('resend-otp/*', ['controller' => 'InstallerRegistrations', 'action' => 'ResendOtp']);
	$routes->connect('installer-payment/*', ['controller' => 'InstallerRegistrations', 'action' => 'payment']);
	$routes->connect('payutransfer/installer-payment/:id', array('controller' => 'Payutransfer', 'action' => 'installer_payment'),['pass' => ['id']]);
	$routes->connect('solar-calculator-result', ['controller' => 'SolarCalculators', 'action' => 'solar_calculator_result']);
	$routes->connect('news', ['controller' => 'Static', 'action' => 'news']);
	$routes->connect('important-document', ['controller' => 'Static', 'action' => 'importantDocument']);
	$routes->connect('apply-onlines/fesibility/:id', ['controller' => 'ApplyOnlines', 'action' => 'fesibility'],['pass' => ['id']]);
	$routes->connect('apply-onlines/do-registration/:id', ['controller' => 'ApplyOnlines', 'action' => 'doregistration'],['pass' => ['id']]);
	$routes->connect('apply-onlines/chargingcertificate/:id', ['controller' => 'ApplyOnlines', 'action' => 'chargingcertificate'],['pass' => ['id']]);
	$routes->connect('apply-onlines/workcompletion/:id', ['controller' => 'ApplyOnlines', 'action' => 'workcompletion'],['pass' => ['id']]);
	$routes->connect('apply-onlines/manage/:id', ['controller' => 'ApplyOnlines', 'action' => 'index'],['pass' => ['id']]);
	$routes->connect('apply-onlines/getSubdivision', ['controller' => 'ApplyOnlines', 'action' => 'getSubdivision']);
	$routes->connect('apply-onlines/assign-division', ['controller' => 'ApplyOnlines', 'action' => 'assigndiscom']);
	$routes->connect('apply-onlines/inspectionstage', ['controller' => 'ApplyOnlines', 'action' => 'inspectionstage']);
	$routes->connect('apply-onlines/send_approval_letter/:id', ['controller' => 'ApplyOnlines', 'action' => 'send_approval_letter'],['pass' => ['id']]);
	$routes->connect('apply-onlines/view-application/:id', array('controller' => 'ApplyOnlines', 'action' => 'downloadpdf'),['pass' => ['id']]);
	$routes->connect('apply-onlines/SendMessage', ['controller' => 'ApplyOnlines', 'action' => 'SendMessage']);
	$routes->connect('apply-onlines/GetMessages/:id', ['controller' => 'ApplyOnlines', 'action' => 'GetMessages'],['pass' => ['id']]);
	$routes->connect('apply-onlines/*', ['controller' => 'ApplyOnlines', 'action' => 'index']);
	$routes->connect('apply-online-list/*', ['controller' => 'ApplyOnlines', 'action' => 'applyonline_list']);
	$routes->connect('view-applyonline/:id', ['controller' => 'ApplyOnlines', 'action' => 'view'],['pass' => ['id']]);
	$routes->connect('admin/ApplyOnlines/view-applyonline-app', ['controller' => 'Admin/ApplyOnlines', 'action' => 'view_api'],['pass' => ['id']]);
	$routes->connect('apply-onlines/geda-inspection-letter/:id', ['controller' => 'ApplyOnlines', 'action' => 'geda_inspection_letter'],['pass' => ['id']]);
	$routes->connect('installer-list/*', ['controller' => 'Installers', 'action' => 'index']);
	$routes->connect('installer-dashboard', ['controller' => 'Installers', 'action' => 'dashboard']);
	$routes->connect('dashboard', ['controller' => 'Member', 'action' => 'index']);

	$routes->connect('project', ['controller' => 'Projects','action' => 'index']);
	$routes->connect('project/:page',array('controller' => 'Projects','action' => 'index'),array('pass' => array('page'),'page' => '[\d]+'));
	$routes->connect('project/leads', ['controller' => 'Projects','action' => 'leads']);
	$routes->connect('project/leads/:type', array('controller' => 'Projects','action' => 'leads'),['pass' => ['type']]);
	$routes->connect('project/leads/:page', array('controller' => 'Projects','action' => 'leads'), array('pass' => array('page'),'page' => '[\d]+'));
	$routes->connect('project/dashboard/:id', array('controller' => 'Projects','action' => 'dashboard'),['pass' => ['id']]);
	$routes->connect('project/sitesurvey/:id', array('controller' => 'Projects','action' => 'sitesurvey'),['pass' => ['id']]);
	$routes->connect('project/commercial/:id', array('controller' => 'Projects','action' => 'commercial'),['pass' => ['id']]);
	$routes->connect('project/termscondition/:id', array('controller' => 'Projects','action' => 'termscondition'),['pass' => ['id']]);
	$routes->connect('project/proposal/:id', array('controller' => 'Projects','action' => 'proposal'),['pass' => ['id']]);
	$routes->connect('project/workorder/:id', array('controller' => 'Projects','action' => 'workorder'),['pass' => ['id']]);
	$routes->connect('project/execution/:id', array('controller' => 'Projects','action' => 'execution'),['pass' => ['id']]);
	$routes->connect('project/commissioning/:id', array('controller' => 'Projects','action' => 'commissioning'),['pass' => ['id']]);
	$routes->connect('project/saveProjectNote', array('controller' => 'Projects','action' => 'saveProjectNote'));
	$routes->connect('project/reportdata/:id', array('controller' => 'Projects','action' => 'reportdata'),['pass' => ['id']]);
	$routes->connect('project/businessdeveloperlist/:id', array('controller' => 'Projects','action' => 'businessDeveloperList'),['pass' => ['id']]);
	$routes->connect('payutransfer/make-payment/:id', array('controller' => 'Payutransfer', 'action' => 'index'),['pass' => ['id']]);
	$routes->connect('installer-verification', ['controller' => 'InstallerRegistrations', 'action' => 'installer_reg_verification']);
	
	$routes->connect('users/forgot-password', ['controller' => 'Users', 'action' => 'forgot_password']);
	$routes->connect('users/verify_customerotp/:id',['controller'=> 'Users', 'action' => 'verify_customerotp'],['pass' => ['id']]);
	$routes->connect('users/change_customer_password/:id',['controller'=> 'Users', 'action' => 'change_customer_password'],['pass' => ['id']]);


	//Develop by Vishal
	//$routes->connect('developer/change_developer_password',['controller'=> 'Developer', 'action' => 'change_developer_password']);
	//end

	$routes->connect('subsidy/:id', ['controller' => 'Subsidy', 'action' => 'index'],['pass' => ['id']]);
	$routes->connect('claimsubsidy', ['controller' => 'Subsidy', 'action' => 'claimsubsidy']);
	$routes->connect('subsidyclaims', ['controller' => 'Subsidy', 'action' => 'subsidyclaims']);
	$routes->connect('savesubsidyclaims', ['controller' => 'Subsidy', 'action' => 'savesubsidyclaims']);
	$routes->connect('approvesubsidyclaims', ['controller' => 'Subsidy', 'action' => 'approvesubsidyclaims']);
	$routes->connect('subsidypaymentreport', ['controller' => 'Subsidy', 'action' => 'subsidypaymentreport']);
	$routes->connect('app-docs/:type/:id', ['controller' => 'ApplyOnlines', 'action' => 'download_document'],['pass' => ['type','id']]);
	$routes->connect('apply-onlines/track-application', ['controller' => 'ApplyOnlines', 'action' => 'trackapplication']);
	$routes->connect('subsidy/coverletter/:id', ['controller' => 'Subsidy', 'action' => 'generateSubsidyCoverLetterPDF'],['pass' => ['id']]);
	$routes->connect('updaterequest', ['controller' => 'ApplyOnlines', 'action' => 'updaterequest']);
	$routes->connect('capacityrequest', ['controller' => 'ApplyOnlines', 'action' => 'capacityrequest']);
	$routes->connect('apply-onlines/map-view', ['controller' => 'ApplyOnlines', 'action' => 'mapview']);
	$routes->connect('apply-onlines/getlastsubsidymessage', ['controller' => 'ApplyOnlines', 'action' => 'getlastsubsidymessage']);
	$routes->connect('apply-onlines/ReplyToMessage', ['controller' => 'ApplyOnlines', 'action' => 'ReplyToMessage']);
	$routes->connect('apply-onlines/unreadmessages', ['controller' => 'ApplyOnlines', 'action' => 'GetUnreadMessages']);
	$routes->connect('apply-onlines/markasread/:id', ['controller' => 'ApplyOnlines', 'action' => 'MarkAsRead'],['pass' => ['id']]);
	$routes->connect('get-consumer-generation-data', ['controller' => 'ApplyOnlines', 'action' => 'getConsumerGenerationData']);
	$routes->connect('mnre-report', ['controller' => 'MnreReport', 'action' => 'index']);
	$routes->connect('member/forgot-password', ['controller' => 'Member', 'action' => 'forgotpassword']);
	$routes->connect('application-payment-report', ['controller' => 'Reports', 'action' => 'getapplicationpaymentreport']);
	$routes->connect('add-additional-capacity/*', ['controller' => 'ApplyOnlines', 'action' => 'AdditionalCapacity']);
	$routes->connect('add-additional-capacity/manage/:id', ['controller' => 'ApplyOnlines', 'action' => 'AdditionalCapacity'],['pass' => ['id']]);
	$routes->connect('installer-registration-kusum/*', ['controller' => 'InstallerRegistrations', 'action' => 'installer_registration_kusum']);
	$routes->connect('new-registration-kusum', ['controller' => 'Installers', 'action' => 'new_registration_kusum']);
	$routes->connect('apply-onlines-kusum/*', ['controller' => 'ApplyOnlinesKusum', 'action' => 'index']);
	$routes->connect('apply-online-kusum-list/*', ['controller' => 'ApplyOnlinesKusum', 'action' => 'applyonline_list']);
	$routes->connect('apply-onlines-kusum/manage/:id', ['controller' => 'ApplyOnlinesKusum', 'action' => 'index'],['pass' => ['id']]);
	$routes->connect('apply-onlines-kusum/view-application/:id', array('controller' => 'ApplyOnlinesKusum', 'action' => 'downloadpdf'),['pass' => ['id']]);
	$routes->connect('view-applyonline-kusum/:id', ['controller' => 'ApplyOnlinesKusum', 'action' => 'view'],['pass' => ['id']]);
	
	$routes->connect('payutransfer/make-payment-kusum/:id', array('controller' => 'PayutransferKusum', 'action' => 'index'),['pass' => ['id']]);


	$routes->connect('ssdsp-registration-return/*', ['controller' => 'FeesReturn', 'action' => 'return_form']);
	$routes->connect('fees-return-success/*', ['controller' => 'FeesReturn', 'action' => 'success']);
	$routes->connect('download-fees-return/*', ['controller' => 'FeesReturn', 'action' => 'download_fees_return']);
	$routes->connect('fees-return-list/*', ['controller' => 'FeesReturn', 'action' => 'return_form_list']);
	$routes->connect('undertaking', ['controller' => 'DownloadPdfs', 'action' => 'undertaking']);
	$routes->connect('GroundMounted/*', ['controller' => 'Applications', 'action' => 'index'],['pass' => ['id']]);
	$routes->connect('Wind/*', ['controller' => 'Applications', 'action' => 'index'],['pass' => ['id']]);
	$routes->connect('Hybrid/*', ['controller' => 'Applications', 'action' => 'index'],['pass' => ['id']]);
	$routes->connect('applications-list/*', ['controller' => 'Applications', 'action' => 'list']);
	$routes->connect('view-applications/:id', ['controller' => 'Applications', 'action' => 'view'],['pass' => ['id']]);
	$routes->connect('RePayment/make-payment/:id', array('controller' => 'ReApplicationPayment', 'action' => 'index'),['pass' => ['id']]);
	$routes->connect('RePayment/cancel', array('controller' => 'ReApplicationPayment', 'action' => 'cancel'));
	$routes->connect('RePayment/success', array('controller' => 'ReApplicationPayment', 'action' => 'success'));

	$routes->connect('developerPayment/make-payment/:id', array('controller' => 'DeveloperPayment', 'action' => 'index'),['pass' => ['id']]);
	$routes->connect('developerPayment/cancel', array('controller' => 'DeveloperPayment', 'action' => 'cancel'));
	$routes->connect('developerPayment/success', array('controller' => 'DeveloperPayment', 'action' => 'success'));
	$routes->connect('developer-payment-receipt/*', ['controller' => 'DeveloperRegistrations', 'action' => 'downloadDeveloperPaymentPdf']);
	
	//Vishal
	$routes->connect('developer-dashboard', ['controller' => 'Developer', 'action' => 'dashboard']);
	$routes->connect('download-developer-payment-receipt/:paymentId', ['controller' => 'Developer', 'action' => 'downloadDeveloperPaymentReceiptPdf'],['pass' => ['paymentId']]);
	$routes->connect('developer/cancel', array('controller' => 'Developer', 'action' => 'cancel'));
	$routes->connect('developer/success', array('controller' => 'Developer', 'action' => 'success'));

	
	

	$routes->connect('open-access-permission/*', ['controller' => 'ApplicationDeveloperPermission', 'action' => 'OpenAccessDeveloperPermission'],['pass' => ['id']]);
	$routes->connect('wind-permission/*', ['controller' => 'ApplicationDeveloperPermission', 'action' => 'WindDeveloperPermission'],['pass' => ['id','dev_app_id']]);
	$routes->connect('hybrid-permission/*', ['controller' => 'ApplicationDeveloperPermission', 'action' => 'WindDeveloperPermission'],['pass' => ['id','dev_app_id']]);

	$routes->connect('developer-permission-payment/:id/:app_dev_id/:app_type',['controller' => 'ApplicationDeveloperPermission', 'action' => 'payment'],['pass' => ['id','app_dev_id','app_type']]);
	$routes->connect('developer-permission-payment/cancel', array('controller' => 'ApplicationDeveloperPermission', 'action' => 'cancel'));
	$routes->connect('developer-permission-payment/success', array('controller' => 'ApplicationDeveloperPermission', 'action' => 'success'));
	$routes->connect('download-developer-permission-payment-receipt/:id/:app_type',['controller' => 'ApplicationDeveloperPermission', 'action' => 'generateApplicationDeveloperPermissionReceiptPdf'],['pass' => ['id','app_type']]); 

	//$routes->connect('applications_geo_location/:id', ['controller' => 'Applications', 'action' => 'geo_location'],['pass' => ['id']]);
	$routes->connect('applications_agreement/:id', ['controller' => 'ApplicationsAgreement', 'action' => 'applications_agreement'],['pass' => ['id']]);
	$routes->connect('applications_geo_location/:id', ['controller' => 'GeoApplications', 'action' => 'geo_location'],['pass' => ['id']]);
	$routes->connect('applications_wtg_shifting/:id', ['controller' => 'GeoShiftingApplication', 'action' => 'wtg_shifting'],['pass' => ['id']]);
	$routes->connect('applications_wtg_modify_make/:id', ['controller' => 'GeoApplications', 'action' => 'modify_make'],['pass' => ['id']]);
	$routes->connect('applications_wtg_delete/:id', ['controller' => 'GeoApplications', 'action' => 'wtg_delete'],['pass' => ['id']]);

	//$routes->connect('download/:filename', ['controller' => 'Downloads', 'action' => 'download']);
	$routes->connect('GeoPayment/make-payment/:id', array('controller' => 'GeoApplicationPayment', 'action' => 'index'),['pass' => ['id']]);
	$routes->connect('GeoPayment/cancel', array('controller' => 'GeoApplicationPayment', 'action' => 'cancel'));
	$routes->connect('GeoPayment/success', array('controller' => 'GeoApplicationPayment', 'action' => 'success'));

	$routes->connect('GeoShiftingPayment/make-payment/:id', array('controller' => 'GeoShiftingApplicationPayment', 'action' => 'index'),['pass' => ['id']]);
	$routes->connect('GeoShiftingPayment/cancel', array('controller' => 'GeoShiftingApplicationPayment', 'action' => 'cancel'));
	$routes->connect('GeoShiftingPayment/success', array('controller' => 'GeoShiftingApplicationPayment', 'action' => 'success'));
	// $routes->connect('view-applications-geo-location/:id', ['controller' => 'GeoApplications', 'action' => 'view_coordinates_details'],['pass' => ['id']]);
	$routes->connect('GeoApplications/view_coordinates_details/:id', array('controller' => 'GeoApplications','action' => 'view_coordinates_details'),['pass' => ['id']]);
	$routes->connect('GeoApplications/view_shifted_coordinates_details/:id', array('controller' => 'GeoShiftingApplication','action' => 'view_shifted_coordinates_details'),['pass' => ['id']]);

	$routes->connect('apply_onlines_rfid_data/', ['controller' => 'Reports', 'action' => 'rfid_data']);
	$routes->connect('applications_cei_drawing/:id', ['controller' => 'Applications', 'action' => 'cei_drawing'],['pass' => ['id']]);
	$routes->connect('applications_cei_inspection/:id', ['controller' => 'Applications', 'action' => 'cei_inspection'],['pass' => ['id']]);
	$routes->connect('applications_bpta/:id', ['controller' => 'Applications', 'action' => 'bpta'],['pass' => ['id']]);
	$routes->connect('applications_bpta_approval/:id', ['controller' => 'Applications', 'action' => 'bpta_approval'],['pass' => ['id']]);
	$routes->connect('applications_wheeling/:id', ['controller' => 'Applications', 'action' => 'wheeling'],['pass' => ['id']]);
	$routes->connect('applications_wheeling_approval/:id', ['controller' => 'Applications', 'action' => 'wheeling_approval'],['pass' => ['id']]);
	$routes->connect('applications_meter/:id', ['controller' => 'Applications', 'action' => 'meter'],['pass' => ['id']]);
	$routes->connect('applications_meter_approval/:id', ['controller' => 'Applications', 'action' => 'meter_approval'],['pass' => ['id']]);
	$routes->connect('applications_power_injection/:id', ['controller' => 'Applications', 'action' => 'power_injection'],['pass' => ['id']]);
	$routes->connect('applications_power_injection_approval/:id', ['controller' => 'Applications', 'action' => 'power_injection_approval'],['pass' => ['id']]);
	$routes->connect('applications_intimation/:id', ['controller' => 'Applications', 'action' => 'intimation'],['pass' => ['id']]);
	$routes->connect('applications_project_commissioning/:id', ['controller' => 'Applications', 'action' => 'project_commissioning'],['pass' => ['id']]);
	//$routes->connect('ApplyOnlines/rfid_upload_docs/:id', array('controller' => 'ApplyOnlines','action' => 'rfid_upload_docs'),['pass' => ['id']]);
	//end
	
	//$routes->connect('/admin', array('controller' => 'adminusers', 'action' => 'login', 'admin' => true));
	//$routes->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);

	/**
	 * ...and connect the rest of 'Pages' controller's URLs.
	 */
	//$routes->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);
	$routes->connect('/admin', ['controller' => 'Admin/Users', 'action' => 'index', 'admin' => true]);
	//$routes->connect('/api', ['controller' => 'Api/GetcoApi', 'action' => 'validate_login', 'api' => true]);
 //   $routes->connect('/api', ['controller' => 'Api/Customers', 'action' => 'index', 'api' => true]);

	/**
	 * Connect catchall routes for all controllers.
	 *
	 * Using the argument `InflectedRoute`, the `fallbacks` method is a shortcut for
	 *    `$routes->connect('/:controller', ['action' => 'index'], ['routeClass' => 'InflectedRoute']);`
	 *    `$routes->connect('/:controller/:action/*', [], ['routeClass' => 'InflectedRoute']);`
	 *
	 * Any route class can be used with this method, such as:
	 * - DashedRoute
	 * - InflectedRoute
	 * - Route
	 * - Or your own route class
	 *
	 * You can remove these routes once you've connected the
	 * routes you want in your application.
	 */
	$routes->fallbacks('InflectedRoute');
});

Router::prefix('admin', function($routes) {
	// All routes here will be prefixed with ‘/admin‘
	// And have the prefix => admin route element added.
	$routes->connect('/', array('controller' => 'Users', 'action' => 'login', 'admin' => true));
	$routes->connect('/fetchDetailsRegistrationNumber', array('controller' => 'Inspection', 'action' => 'fetchDetailsRegistrationNumber', 'admin' => true));
	$routes->fallbacks('InflectedRoute');
});


Router::prefix('api', function($routes) {
	// All routes here will be prefixed with ‘/api‘
	// And have the prefix => api route element added.
	$routes->connect('/', array('controller' => 'Users', 'action' => 'login', 'api' => true));
	$routes->fallbacks('InflectedRoute');
});

Router::prefix('installer', function($routes) {
	// All routes here will be prefixed with ‘/api‘
	// And have the prefix => api route element added.
	$routes->connect('/', array('controller' => 'Users', 'action' => 'login', 'installer' => true));
	$routes->fallbacks('InflectedRoute');
});

/**
 * Load all plugin routes.  See the Plugin documentation on
 * how to customize the loading of plugin routes.
 */
Plugin::routes();
