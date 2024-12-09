<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @since         0.10.8
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
ini_set("memory_limit", "1024M");
/**
 * Configure paths required to find CakePHP + general filepath
 * constants
 */
require __DIR__ . '/paths.php';

// Use composer to load the autoloader.
require ROOT . DS . 'vendor' . DS . 'autoload.php';

/**
 * Bootstrap CakePHP.
 *
 * Does the various bits of setup that CakePHP needs to do.
 * This includes:
 *
 * - Registering the CakePHP autoloader.
 * - Setting the default application paths.
 */
require CORE_PATH . 'config' . DS . 'bootstrap.php';

// You can remove this if you are confident you have intl installed.
if (!extension_loaded('intl')) {
    trigger_error('You must enable the intl extension to use CakePHP.', E_USER_ERROR);
}

use Cake\Cache\Cache;
use Cake\Console\ConsoleErrorHandler;
use Cake\Core\App;
use Cake\Core\Configure;
use Cake\Core\Configure\Engine\PhpConfig;
use Cake\Core\Plugin;
use Cake\Database\Type;
use Cake\Datasource\ConnectionManager;
use Cake\Error\ErrorHandler;
use Cake\Log\Log;
use Cake\Network\Email\Email;
use Cake\Network\Request;
use Cake\Routing\DispatcherFactory;
use Cake\Utility\Inflector;
use Cake\Utility\Security;

/**
 * Read configuration file and inject configuration into various
 * CakePHP classes.
 *
 * By default there is only one configuration file. It is often a good
 * idea to create multiple configuration files, and separate the configuration
 * that changes from configuration that does not. This makes deployment simpler.
 */
try {
    Configure::config('default', new PhpConfig());
    Configure::load('app', 'default', false);
} catch (\Exception $e) {
    die($e->getMessage() . "\n");
}

// Load an environment local configuration file.
// You can use a file like app_local.php to provide local overrides to your
// shared configuration.
//Configure::load('app_local', 'default');

// When debug = false the metadata cache should last
// for a very very long time, as we don't want
// to refresh the cache while users are doing requests.
if (Configure::read('debug')) {
    Configure::write('Cache._cake_model_.duration', '+2 minutes');
    Configure::write('Cache._cake_core_.duration', '+2 minutes');
}
Configure::write('EMAIL_ENV','');
Configure::write('UPLOAD_PDF_LIMIT','5');
Configure::write('UPLOAD_IMAGE_LIMIT','2');
/* Payumoney Keys */
Configure::write('SERVER_MODE','PROD');
Configure::write('PAYU_SANDBOX',1);
Configure::write('PAYUMONEY_PAYMENT',1); //Enable payment mode 1 for on and 0 for off 

if (Configure::read("PAYU_SANDBOX") == 1) {
    Configure::write('PAYU_MERCHANT_KEY', 'BC50nb');
    Configure::write('PAYU_MERCHANT_SALT', 'Bwxo1cPe');
    Configure::write('MERCHANT_ID_1', '4825050');
    Configure::write('MERCHANT_ID_2', '4825051');
    // Configure::write('PAYU_MERCHANT_KEY', 'TZmLlBSs');
    // Configure::write('PAYU_MERCHANT_SALT', 'VKa7nCSYe8');
    // Configure::write('MERCHANT_ID_1', '6200195');
    Configure::write('PAYU_PAYMENT_URL', 'https://test.payu.in/_payment');
    Configure::write('HDFC_MERCHANT_KEY', '193106');
    Configure::write('HDFC_SALT', '89C2E26FBDE51C08E358BE73C41C9184');
    Configure::write('HDFC_ACCESS_CODE', 'ATGM80FJ87AJ09MGJA');
    Configure::write('HDFC_PAYMENT_URL', 'https://test.ccavenue.com');
} else {
    Configure::write('PAYU_MERCHANT_KEY', 'TZmLlBSs');
    Configure::write('PAYU_MERCHANT_SALT', 'VKa7nCSYe8');
    Configure::write('MERCHANT_ID_1', '6200195');
    Configure::write('PAYU_PAYMENT_URL', 'https://secure.payu.in/_payment');
    Configure::write('HDFC_MERCHANT_KEY', '193106');
    Configure::write('HDFC_SALT', '89C2E26FBDE51C08E358BE73C41C9184');
    Configure::write('HDFC_ACCESS_CODE', 'AVGM80FJ87AJ09MGJA');
    Configure::write('HDFC_PAYMENT_URL', 'https://secure.ccavenue.com');
}
Configure::write('PV_CAPACITY_GT50', '750');
Configure::write('PV_CAPACITY_LT50', '250');
Configure::write('APPLY_AMOUNT_GOVERNMENT', '1000');
Configure::write('APPLY_AMOUNT_NON_GOVERNMENT', '13000');
Configure::write('APPLY_AMOUNT_RESIDENTIAL', '0');
Configure::write('APPLY_AMOUNT_GOV_TAX', '18');
Configure::write('APPLY_AMOUNT_NON_GOV_TAX', '18');
Configure::write('APPLY_TAX_PERCENT', '%');
/* Payumoney Keys */

/* AhaLoginMasterPassword */
Configure::write('AHA_LOGIN_MASTER_PASSWORD',"donotshare2018@aha");
/* AhaLoginMasterPassword */

/*Google Captcha Keys*/
Configure::write('SITE_KEY', '6Le_81cUAAAAAPV0ndpsBU1DoBUVeltmqR0tMeML');
Configure::write('SECRET_KEY', '6Le_81cUAAAAAPvv8W94n5gp1hIN6wnMVfGZg7fW');


/******************HDFC Payment Transfer***********************/
Configure::write('HDFC_PAYMENT',1); //Enable HDFC NEFT transfer 1 for Production and 0 for uat 
if (Configure::read("HDFC_PAYMENT") == 1) {
   // Configure::write('GROUP_ID', 'FCAT_GUJEDA');
    Configure::write('GROUP_ID', 'GUJEDACX');
    //Configure::write('API_USER', 'APIUser@GUJEDA');
    //Configure::write('API_USER', 'APIUser@GUJEDACX');
    Configure::write('API_USER', 'APIUSER');
    Configure::write('CUSTOMER_ID', '67645125');
    Configure::write('TRANSFER_PAYMENT_URL', 'https://api.hdfcbank.com');
    Configure::write('ACCOUNT_NUMBER', '50100147616733');
    Configure::write('API_USERNAME', 'xBsiZb8E22ayBdqFR49X1j1ksKtPxpAFyvwUAqzEY1xFDU23');
    Configure::write('API_PASSWORD', 'tYLzrclB1XSismjdCViJsdFQGGU5SNPwNCkZiOo2gQ3XZhT9PLhsOYKidNeAnn7u');
} else {
    Configure::write('GROUP_ID', 'CBXMGRT3');
    Configure::write('API_USER', 'APIUSER@CBXMGRT3');
    Configure::write('CUSTOMER_ID', '10246013');
    Configure::write('TRANSFER_PAYMENT_URL', 'https://api-uat.hdfcbank.com');
    Configure::write('ACCOUNT_NUMBER', '00040350004239');
    Configure::write('API_USERNAME', 'wW09e0TOVCI3zrCuKaqKIslekkbobIModW8xWpFOLzHEB4Rv');
    Configure::write('API_PASSWORD', 'CzVTZT7aZ15W9SsTE6GqYEklv2jow2cZsUwLzRlBWux1ScM9fNYWBIyZk62GmjR5');
}
/******************HDFC Payment Transfer***********************/

/**
 * Set server timezone to UTC. You can change it to another timezone of your
 * choice but using UTC makes time calculations / conversions easier.
 */
date_default_timezone_set('Asia/Kolkata');

/**
 * Configure the mbstring extension to use the correct encoding.
 */
mb_internal_encoding(Configure::read('App.encoding'));

/**
 * Set the default locale. This controls how dates, number and currency is
 * formatted and sets the default language to use for translations.
 */
ini_set('intl.default_locale', 'en_US');

/**
 * Register application error and exception handlers.
 */
$isCli = php_sapi_name() === 'cli';
if ($isCli) {
    (new ConsoleErrorHandler(Configure::read('Error')))->register();
} else {
    (new ErrorHandler(Configure::read('Error')))->register();
}

// Include the CLI bootstrap overrides.
if ($isCli) {
    require __DIR__ . '/bootstrap_cli.php';
}

/**
 * Set the full base URL.
 * This URL is used as the base of all absolute links.
 *
 * If you define fullBaseUrl in your config file you can remove this.
 */
if (!Configure::read('App.fullBaseUrl')) {
    $s = null;
    if (env('HTTPS')) {
        $s = 's';
    }

    $httpHost = env('HTTP_HOST');
    if (isset($httpHost)) {
        Configure::write('App.fullBaseUrl', 'http' . $s . '://' . $httpHost);
    }
    unset($httpHost, $s);
}

Cache::config(Configure::consume('Cache'));
ConnectionManager::config(Configure::consume('Datasources'));
Email::configTransport(Configure::consume('EmailTransport'));
Email::config(Configure::consume('Email'));
Log::config(Configure::consume('Log'));
Security::salt(Configure::consume('Security.salt'));

/**
 * The default crypto extension in 3.0 is OpenSSL.
 * If you are migrating from 2.x uncomment this code to
 * use a more compatible Mcrypt based implementation
 */
// Security::engine(new \Cake\Utility\Crypto\Mcrypt());

/**
 * Setup detectors for mobile and tablet.
 */
Request::addDetector('mobile', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isMobile();
});
Request::addDetector('tablet', function ($request) {
    $detector = new \Detection\MobileDetect();
    return $detector->isTablet();
});

/**
 * Custom Inflector rules, can be set to correctly pluralize or singularize
 * table, model, controller names or whatever other string is passed to the
 * inflection functions.
 *
 * Inflector::rules('plural', ['/^(inflect)or$/i' => '\1ables']);
 * Inflector::rules('irregular', ['red' => 'redlings']);
 * Inflector::rules('uninflected', ['dontinflectme']);
 * Inflector::rules('transliteration', ['/å/' => 'aa']);
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. make sure you read the documentation on Plugin to use more
 * advanced ways of loading plugins
 *
 * Plugin::loadAll(); // Loads all plugins at once
 * Plugin::load('Migrations'); //Loads a single plugin named Migrations
 *
 */

Plugin::load('Migrations');

// Only try to load DebugKit in development mode
// Debug Kit should not be installed on a production system
if (Configure::read('debug')) {
    Plugin::load('DebugKit', ['bootstrap' => true]);
}

/* Load twitter bootstrap helpers */
Plugin::load('BootstrapUI', ['autoload' => true]);


/**
 * Connect middleware/dispatcher filters.
 */
DispatcherFactory::add('Asset');
DispatcherFactory::add('Routing');
DispatcherFactory::add('ControllerFactory');

/**
 * Enable default locale format parsing.
 * This is needed for matching the auto-localized string output of Time() class when parsing dates.
 */
Type::build('datetime')->useLocaleParser();
date_default_timezone_set('Asia/Kolkata');

######################################### FILE FOR WHITE LABLE CONFIGURATION ##############################
require_once("whitelable.php");
######################################### FILE FOR WHITE LABLE CONFIGURATION ##############################

######################################### COMPANY LEVEL CONSTANTS ##############################
require_once("constants.php");
######################################### COMPANY LEVEL CONSTANTS ##############################

######################################### COMMON FUNCTIONS ##############################
require_once('commonfunctions.php');
######################################### COMMON FUNCTIONS ##############################
