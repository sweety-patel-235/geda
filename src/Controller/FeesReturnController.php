<?php
namespace App\Controller;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', '-1');
use App\Controller\AppController;
use Cake\View\View;
use Cake\ORM\TableRegistry;
use Cake\ORM\Table;

use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Controller\Component;
use Cake\Utility\Security;

use Cake\Event\Event;
use Cake\View\Helper\PaginatorHelper;
use Cake\Validation\Validator;
use Cake\Datasource\ConnectionManager;
use Dompdf\Dompdf;
use PHPExcel\PHPExcel;
use AES\AES;
//use cryptlib\Crypt\Crypt_RSA;

class FeesReturnController extends FrontAppController
{
	public $user_department = array();
	public $arrDefaultAdminUserRights = array();
	public $helpers = array('Time','Html','Form','ExPaginator');
	public $PAGE_NAME = '';
	public $CUSTOMER_STATE_ID = 4;
	public $paginate = [
		'limit' => PAGE_RECORD_LIMIT,
		'order' => [
			'ApplyOnlines.id ' => 'desc'
		]
	];
	public $private_key		= '';
	public $certificateValue= '';

	/*
	 * initialize controller
	 *
	 * @return void
	 */
	public function initialize()
	{
		// Always enable the CSRF component.
		parent::initialize();
		$this->loadComponent('Paginator');
		$this->loadModel('ApiToken');
		$this->loadModel('ApplyOnlines');
		$this->loadModel('DiscomMaster');
		$this->loadModel('FesibilityReport');
		$this->loadModel('RegistrationScheme');
		$this->loadModel('RegistrationSchemeDocument');
		$this->loadModel('WorkCompletion');
		$this->loadModel('WorkCompletionDocument');
		$this->loadModel('ChargingCertificate');
		$this->loadModel('Users');
		$this->loadModel('Userrole');
		$this->loadModel('Userroleright');
		$this->loadModel('ApplyOnlineApprovals');
		$this->loadModel('Adminaction');
		$this->loadModel('Parameters');
		$this->loadModel('BranchMasters');
		$this->loadModel('Members');
		$this->loadModel('Installers');
		$this->loadModel('Customers');
		$this->loadModel('States');
		$this->loadModel('Sessions');
		$this->loadModel('ApplyonlinDocs');
		$this->loadModel('Projects');
		$this->loadModel('InstallerProjects');
		$this->loadModel('CustomerProjects');
		$this->loadModel('Payumoney');
		$this->loadModel('ApplyonlinePayment');
		$this->loadModel('ApplyonlineMessage');
		$this->loadModel('Installation');
		$this->loadModel('WorkCompletion');
		$this->loadModel('CeiApplicationDetails');
		$this->loadModel('ThirdpartyApiLog');
		$this->loadModel('InstallerCategory');
		$this->loadModel('InstallerCategoryMapping');
		$this->loadModel('ApplicationDeleteLog');
		$this->loadModel('SpinWebserviceApi');
		$this->loadModel('UpdateDetails');
		$this->loadModel('UpdateDetailsApplicationsLog');
		$this->loadModel('UpdateCapacity');
		$this->loadModel('UpdateCapacityApplicationsLog');
		$this->loadModel('UpdateCapacityProjectsLog');
		$this->loadModel('Subsidy');
		$this->loadModel('ApplyOnlinesOthers');
		$this->loadModel('Inspectionpdf');
		$this->loadModel('PreRegistration');
		$this->loadModel('ApplicationPayment');
		$this->loadModel('DistrictMaster');
		$this->loadModel('EnergyGenerationLog');
		$this->loadModel('MISReportData');
		$this->loadModel('Workorder');
		$this->loadModel('ApplyonlineReport');
		$this->loadModel('InstallerTotalCapacity');
		$this->loadModel('ApplicationPhasechangeLog');
		$this->loadModel('SolarTypeLog');
		$this->loadModel('SendRegistrationFailure');
		$this->loadModel('ApplyonlineUnReadMessage');
		$this->loadModel('MeterRecall');
		$this->loadModel('SubsidyRequest');
		$this->loadModel('SubsidyRequestApplication');
		$this->loadModel('UpdateDiscomDataLog');
		$this->loadModel('SchemeMaster');
		$this->loadModel('ApplyonlinePaymentDocs');
		$this->loadModel('FeesReturn');
		$this->loadModel('Couchdb');
		$this->loadModel('FeesReturnApiLog');
		$this->set('ApplyonlineMessage',$this->ApplyonlineMessage);
		$this->set('InspectionReport',$this->InspectionReport);
		$this->set('Userright',$this->Userright);
		/*$this->private_key = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQDTEmGRJsrzMqda
U1BV17F9LIksUlFcHgn0AuPFXUy9XjUBy+1WYLmawd674agDi6DLxbux6AYC/NTT
iNNF5fbKpSIi0/8qOzSM/WL75yC1WZCmImt0+KtQzum6KA5S2ooWyvFR6c6qthj1
H+2JZxXjomIk1u5DnZPvdcAbgAORZ3Eftb5SnGGoPW0iS0+ymOIey6qbS0JSNeqY
puaK5qpivAvcL2ESZ9yFBEEAzOsl2XQhhLY/tWxRppUjisMlj+KDY4Obh6R6SPr4
+Gmmj72ef61RsDONx2dsmQATngU0BgsdhB4zZtj1ljtaPABb5RGtXAVlk848kfl9
B7UNvKdlAgMBAAECggEAIJ1wVnG5njnKCALkC4JgX+NqsN6+yVlaQrUoRVOPlkVr
3cK0AmBriGEuYzbRHkx273Xhn8cmwqhsfza0etJi6lj4/OES8Tojz9N0S6YhH5S0
Qs+tGMPiE62ytRFtITAOyCCE3e+fKobSisdruOB6eT8azkQonhRyixKADwp0GAVY
EY7RRITP0kotem5TtZ8BtRQn2yEDyYLLO+0pAA7vUsiD+u2IG+xqB5fG2t7zDw0/
/baFqW/KJ1km+0tbQmT7zXvy/d9F5l9TrEe8L/jXibPG9+sQLhOg5v3f2hgva8rj
Lq8N9ol+dEXwOaHts410G5Aijz2w3kaLeSjO5nu/YwKBgQDYSGYVyI313PbI+tJ5
NqSePNP48CW2HXhTBF2SjcQykBZpwd/cfYULb2z/e8aS7xGgsKjOsj0Z74SNC9vj
2nb3N5dWtdXiFW6JQLMmEq49bdeFo/bubMBCmI2ZnQtleamPUm6v/liw1yqkzKkA
fYaXZZS5EQwqnlfVPPwUPKubMwKBgQD51QJotF87CksRF8hc+dpFCPKVw/uSymez
AYnDSdI+JHarjN416c6oWcUeRbE30614msWZPyBRXDjzoEE5zugByYBVaooJ4Ep8
R5gjlekwi9kzeqiYhr6Kwpcuqfkjy+FoSMRK6jiUdrPogLQAbwWqy5iLikncrKnx
F5qcbF7zBwKBgFz3MbonTK3j3sgg2Bt2G2hQ6SRVxT/0huXYOIhoG29Ic/nddeYG
pgt2R7nBcGd0D3WsucKu5oihZa5i7I+SNhSpdom0+0yEvdCNWPQCj5akAkHVaqyt
Xi7B+AuRb3acxv9uBVns0B6jPhc8SWCGlDW7WiP6aepfyY1E+22Pbov1AoGAVzKL
hrP90QOEs9CTNDBYiGPZF4Cx28gdbZMJ3El1wg7EBJhELpkOch/y9t/oPM366+9J
LHWl9/+yOQYj/eNDguwriKSIzW2lUb9DUJhQLYuCIb+b/LB67L+CON1GgcH1SIqt
SGB7owXTQUE6kjQtzDEHaxy3Lvhs0CMm6ZXBhh0CgYAUtjf6nl8Sr1w7pNAMOrQr
MoC0mHw7UfOYiDapCJClCoFki95IAI8JX/zMvsgLr/MB2+GEJPMtBSOPxDBCMe/a
HlwgwJt+t7sBDIgTooL44WinMDIN60wEnwfPBfOMSl+5S3gw4W4vTub6fObAF5q+
iyfwmQQXm8uupBEsmE3liQ==
-----END PRIVATE KEY-----
EOD;*/
//****** GEDA SERVER key ******/
$this->private_key = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvgIBADANBgkqhkiG9w0BAQEFAASCBKgwggSkAgEAAoIBAQCTMoc/CzB7uxOx
I8bopKNUvc/SvGNEshra0/YrbdKCy5ErWhlNYh/XOsZeaj2rPPxNhVMgQNqpdPYC
Iw5v9BbTM54N4x3qyEKW7T3xO9a9zv7js4BGlmca1K+WXPEn29sjt9zRl9KxTWpD
+f4TzWsb6RtacvZ2jWM0Q+LTnK5WiWgwXX0mT1QvAxfP1ErJ+azXhrrAovohPqfU
UmtvuR0kxaKJOzaH1sElyc2JNfaoWPmDyxFzr8eIbOLdQ6lihqqGvFUgyHqpRzHa
gFDu4lLeiJuILzNe6KUy1YUwJQpt+MRvb9zwQc6UDM1nm/PTICBkH84dD4ObF2cT
dOXvp4rNAgMBAAECggEABtTN/QmGZv+gItTvusVFRT4H4pZWnw4K/APhRVOz9NkA
tDvat8IpiyIRqbkRgpxycyCQYotP+pRQhHnfBigUVRnsqGVVcpt3p6x79vGZfjaY
krW2atA5GcAsI/TNRlXIPkieqWV75DmdCGmWNpIOef2gBNsYABmAtKC/6qU3XBGz
Yv0OlyKbBCliExchcpNGfZMxZ2FYpeLMKwZFcvubf4ldaZR4oKuh7F2muxwfVoA4
up7c64H4LTa70ts+GXXEqfwrFOaSMN2chE0VzsXVIM8httSq6uJMe5yROyNKdtWg
/Rqxzm/RcO5iuXPy8MyPhTE263ukcTQPx5RRddeh6wKBgQDPW/BQfyShP77b+Oq5
8paxNiqDXK/JrBkc7Un4T5Oa0V660M6oh1x3qi0GYNqVr2clDFhati0kjFawsMIS
Uoau5VnhlDRH4ry/XynfGQ/yzAaLudW4pHG5Cnc6cs6XDd0V0hDGbQHWtHfaoynw
ZDcewK7rj+FrgW4ptWTHBbwGfwKBgQC1udV0AxrcyUXQJcLt1Kyq1zaCrGX+UL+G
dNzk/B4PzT+Es0Hn1yD50Sn2m2LfzgNxqlzTlDwWaDWWfubu6voJ4DBSt6kbTDfC
1BXV91nwEtKmzvLbRBAJf4Vm1bPpaXFsT+DiUR3j3LwS5DReW2IDuKyCXy0ssUqN
EEhXoVEAswKBgCZ1W9XiLu4FP6XWvdotBwvpCuuANk5GMAYwcGawg6TULiih76JM
MLc1BdLIBeJ7PLsfVgfFAAxmRvHQZr41Niub+BahgSzP/cfUo5RwNogGlTQ3DE+J
mFoEeeaKQoy7koSoiFn0/8FNiWkwl+ew/pQiko64CcwBnmf377AF/UCLAoGBALP/
l+/LS4Y5To83d/a+2zB07ydLv9LBBJQXmNyu5M/eCvZT4AnVynHnvdroWm03z618
g2mGwGWpXrrsg61Ozc+OYg7sn/HL8sdl7yL6V/k1i7Vx8pdAuWnPB8GuFwAxUwln
rWY91o9miltj8oMrnM/20dhokYRdL2y+Hgm+XU+FAoGBAIWnhZ9kRovulmmBDkqB
EWmJ4dqjxDsc+Fcnlr3TSZ0CksBHv6B7OsgANJ+IF7DVEfsJ5wvLPcCAbb4DgszE
K3PlwLXZcOZHL/P7ho1q+V+5QgFuFjxkzKUensLDahJ259r2ihYB61CNtz+MrZQO
VCrVIg5vYA7Rg739XuCYPv3c
-----END PRIVATE KEY-----
EOD;

/*$this->certificateValue = <<<EOD
-----BEGIN CERTIFICATE-----
MIIG2jCCBcKgAwIBAgIQGiNejjo2gWMWMyXYjJJFlTANBgkqhkiG9w0BAQsFADCB
ujELMAkGA1UEBhMCVVMxFjAUBgNVBAoTDUVudHJ1c3QsIEluYy4xKDAmBgNVBAsT
H1NlZSB3d3cuZW50cnVzdC5uZXQvbGVnYWwtdGVybXMxOTA3BgNVBAsTMChjKSAy
MDEyIEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9yaXplZCB1c2Ugb25seTEuMCwG
A1UEAxMlRW50cnVzdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eSAtIEwxSzAeFw0y
MjA3MjEwNTUwNDBaFw0yMzA4MTkwNTUwNDBaMHYxCzAJBgNVBAYTAklOMRAwDgYD
VQQIEwdHdWphcmF0MRQwEgYDVQQHEwtHYW5kaGluYWdhcjEkMCIGA1UEChMbR3Vq
YXJhdCBJbmZvcm1hdGljcyBMaW1pdGVkMRkwFwYDVQQDDBAqLmd1amFyYXQuZ292
LmluMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA0xJhkSbK8zKnWlNQ
VdexfSyJLFJRXB4J9ALjxV1MvV41AcvtVmC5msHeu+GoA4ugy8W7segGAvzU04jT
ReX2yqUiItP/Kjs0jP1i++cgtVmQpiJrdPirUM7puigOUtqKFsrxUenOqrYY9R/t
iWcV46JiJNbuQ52T73XAG4ADkWdxH7W+UpxhqD1tIktPspjiHsuqm0tCUjXqmKbm
iuaqYrwL3C9hEmfchQRBAMzrJdl0IYS2P7VsUaaVI4rDJY/ig2ODm4ekekj6+Php
po+9nn+tUbAzjcdnbJkAE54FNAYLHYQeM2bY9ZY7WjwAW+URrVwFZZPOPJH5fQe1
DbynZQIDAQABo4IDHTCCAxkwDAYDVR0TAQH/BAIwADAdBgNVHQ4EFgQUz8+zzSOK
Gc8+7qog9W3SrEFH3LAwHwYDVR0jBBgwFoAUgqJwdN28Uz/Pe9T3zX+nYMYKTL8w
aAYIKwYBBQUHAQEEXDBaMCMGCCsGAQUFBzABhhdodHRwOi8vb2NzcC5lbnRydXN0
Lm5ldDAzBggrBgEFBQcwAoYnaHR0cDovL2FpYS5lbnRydXN0Lm5ldC9sMWstY2hh
aW4yNTYuY2VyMDMGA1UdHwQsMCowKKAmoCSGImh0dHA6Ly9jcmwuZW50cnVzdC5u
ZXQvbGV2ZWwxay5jcmwwKwYDVR0RBCQwIoIQKi5ndWphcmF0Lmdvdi5pboIOZ3Vq
YXJhdC5nb3YuaW4wDgYDVR0PAQH/BAQDAgWgMB0GA1UdJQQWMBQGCCsGAQUFBwMB
BggrBgEFBQcDAjBMBgNVHSAERTBDMDcGCmCGSAGG+mwKAQUwKTAnBggrBgEFBQcC
ARYbaHR0cHM6Ly93d3cuZW50cnVzdC5uZXQvcnBhMAgGBmeBDAECAjCCAX4GCisG
AQQB1nkCBAIEggFuBIIBagFoAHYAVYHUwhaQNgFK6gubVzxT8MDkOHhwJQgXL6Oq
HQcT0wwAAAGCH07mUQAABAMARzBFAiAsPEZ/HDhHMBYF/8fpEzCG3b+AyKU5oZQh
s0qBzIMkeQIhAIszsTssJxCL3MR9x5+8NqP4EV8Ze3eE7x9F620THFT8AHYAtz77
JN+cTbp18jnFulj0bF38Qs96nzXEnh0JgSXttJkAAAGCH07mRwAABAMARzBFAiEA
ubKggORmBNiesxLlWDP8iSdHOy/KQVBNV6Kl+6MmbqkCIF+ElHUSt1BQnzHAo+1B
iegSJoL1CeroN3wuPPusQeX/AHYArfe++nz/EMiLnT2cHj4YarRnKV3PsQwkyoWG
NOvcgooAAAGCH07mLQAABAMARzBFAiACz5hih7lZmvRRXy1pvO4/7OaY5nYKMjOd
DcdfNnFcWAIhAK1YWKg5bf54vUjibgeR14LpG7cG0yAy9Du4FGlqg8aOMA0GCSqG
SIb3DQEBCwUAA4IBAQAJwwNZYMtyAbMxncDjt5TDTbZvzJoNHr3+QZaWQAFMXlGp
f99p+tt2oqobWgKI8kh8YR1hsfjI7k6KUrzbzR2tVL1OM8AxACp7n1wHiehH3aib
myihxAHgndQqdEtF3Rw2ie4NZAoomUhxPkLWvT6IHxqM+BkVhKJ5EYVb9FCPhUmI
DOPf9oo11qMXRmyhzMiFUIO5jDCSA6mOE9liR2M6XzPMEqiyFWVFCwdsWAkEmckl
yst/UheTxUNzvyvpMCSISEBmM/+vh/No1xyQvBN2gBQNKucYtpHM99r+8NyKeicg
p7iYQ/euEfzAVpFOHohlEdC9rkVfomTeG+zQSQcP
-----END CERTIFICATE-----
EOD;*/
$this->certificateValue = <<<EOD
-----BEGIN CERTIFICATE-----
MIIG1jCCBb6gAwIBAgIQIlm0xMM8sLZp5PxWd/BUBzANBgkqhkiG9w0BAQsFADCB
ujELMAkGA1UEBhMCVVMxFjAUBgNVBAoTDUVudHJ1c3QsIEluYy4xKDAmBgNVBAsT
H1NlZSB3d3cuZW50cnVzdC5uZXQvbGVnYWwtdGVybXMxOTA3BgNVBAsTMChjKSAy
MDEyIEVudHJ1c3QsIEluYy4gLSBmb3IgYXV0aG9yaXplZCB1c2Ugb25seTEuMCwG
A1UEAxMlRW50cnVzdCBDZXJ0aWZpY2F0aW9uIEF1dGhvcml0eSAtIEwxSzAeFw0y
MzA4MDgxMjM3MzlaFw0yNDA5MDgxMjM3MzhaMHQxCzAJBgNVBAYTAklOMRQwEgYD
VQQHEwtHYW5kaGluYWdhcjE0MDIGA1UECgwrRGVwYXJ0bWVudCBvZiBTY2llbmNl
ICYgVGVjaG5vbG9neSwgR3VqYXJhdDEZMBcGA1UEAwwQKi5ndWphcmF0Lmdvdi5p
bjCCASIwDQYJKoZIhvcNAQEBBQADggEPADCCAQoCggEBAJMyhz8LMHu7E7Ejxuik
o1S9z9K8Y0SyGtrT9itt0oLLkStaGU1iH9c6xl5qPas8/E2FUyBA2ql09gIjDm/0
FtMzng3jHerIQpbtPfE71r3O/uOzgEaWZxrUr5Zc8Sfb2yO33NGX0rFNakP5/hPN
axvpG1py9naNYzRD4tOcrlaJaDBdfSZPVC8DF8/USsn5rNeGusCi+iE+p9RSa2+5
HSTFook7NofWwSXJzYk19qhY+YPLEXOvx4hs4t1DqWKGqoa8VSDIeqlHMdqAUO7i
Ut6Im4gvM17opTLVhTAlCm34xG9v3PBBzpQMzWeb89MgIGQfzh0Pg5sXZxN05e+n
is0CAwEAAaOCAxswggMXMAwGA1UdEwEB/wQCMAAwHQYDVR0OBBYEFGdP9nNYDJtn
KFCzK9ux4b1ROnUwMB8GA1UdIwQYMBaAFIKicHTdvFM/z3vU981/p2DGCky/MGgG
CCsGAQUFBwEBBFwwWjAjBggrBgEFBQcwAYYXaHR0cDovL29jc3AuZW50cnVzdC5u
ZXQwMwYIKwYBBQUHMAKGJ2h0dHA6Ly9haWEuZW50cnVzdC5uZXQvbDFrLWNoYWlu
MjU2LmNlcjAzBgNVHR8ELDAqMCigJqAkhiJodHRwOi8vY3JsLmVudHJ1c3QubmV0
L2xldmVsMWsuY3JsMCsGA1UdEQQkMCKCECouZ3VqYXJhdC5nb3YuaW6CDmd1amFy
YXQuZ292LmluMA4GA1UdDwEB/wQEAwIFoDAdBgNVHSUEFjAUBggrBgEFBQcDAQYI
KwYBBQUHAwIwTAYDVR0gBEUwQzA3BgpghkgBhvpsCgEFMCkwJwYIKwYBBQUHAgEW
G2h0dHBzOi8vd3d3LmVudHJ1c3QubmV0L3JwYTAIBgZngQwBAgIwggF8BgorBgEE
AdZ5AgQCBIIBbASCAWgBZgB1AD8XS0/XIkdYlB1lHIS+DRLtkDd/H4Vq68G/KIXs
+GRuAAABidUnJjUAAAQDAEYwRAIgEj/PkZzpFCJp9Z/5gMkVEedQ/T5fT7EOLX9N
Suy7DdkCIDPVfm/nizJuDy7RYh/ylky+PYAH3vWp8ecL4oFI6CzMAHUAdv+IPwq2
+5VRwmHM9Ye6NLSkzbsp3GhCCp/mZ0xaOnQAAAGJ1ScmJgAABAMARjBEAiBEVLN8
UDfD03xucbwTE5Nt+cWTDyOOIxQF4C0MHx+wSQIgfkHsBfzCFrbrwUsRYGoJaG4n
c6ttG85Z1ZKWIXGFFQIAdgDatr9rP7W2Ip+bwrtca+hwkXFsu1GEhTS9pD0wSNf7
qwAAAYnVJyYhAAAEAwBHMEUCIGbzUf2uYWTEO8rfuhp6YmoDczVgrtt/MNWgUNRX
cwNrAiEA8x1MQpRYCYrXNi0X+ahx7K3WO8N/hA9CmbN2lDCKQ9EwDQYJKoZIhvcN
AQELBQADggEBAE/RgTkMpXiCC+7PLcd0pp4bcp6CethQQrTmLL5A6ftg4glePXvC
5VJuPo55y9H1FWw8t2V0YORGYJuF5GRZOMLmBAnDTS1H0djaSy9L5bRJc/L1XiAg
MvgL69s4fGKNS9HkerkijC3PK2GSxtLQ8VHA2k4U0xfqjVbJCLM3GibWNb3kp9NC
XGenMeduJTYbIohdyXuqaKw0Wia1edbxista6TN16lYJhhDI/Px3+3yz0/8/36JU
j8xfx1rW+UMT6tiryFXuhgCgR3cY1w3oFiWhwq8XkMVc47kh3/KdazyVRA/5uHCh
M1vL3WuDmeyBZZIrF1/P3FhzfuZ9uLiWx5Y=
-----END CERTIFICATE-----
EOD;
//****** GEDA SERVER key ******/
	}
	/**
	 *
	 * return_form
	 * Behaviour : Public
	 * @defination : Method is use to add return form for installer side
	 *
	 */
	public function return_form($id= null,$view_status=0)
	{
		$customer_id 	= $this->Session->read('Customers.id');
		$member_id 		= $this->Session->read("Members.id");
		$is_member      = !empty($member_id) ? true : false;
		
		$main_branch_id = array();
		if(!empty($id)) {
			$id 		= intval(decode($id));
		} else if(isset($this->request->data['registration_no']) && !empty($this->request->data['registration_no'])) {
			$feesDetails= $this->FeesReturn->find('all',array('conditions'=>array('registration_no'=>trim($this->request->data['registration_no']))))->first();
			if(!empty($feesDetails)) {
				return $this->redirect(URL_HTTP.'ssdsp-registration-return/'.encode($feesDetails->id));
			}
		}
		$arrSessionInfo 	= array();
		if(!empty($customer_id)) {
			$arrSessionInfo['login_id'] = $customer_id;
		} elseif(!empty($member_id)) {
			$arrSessionInfo['login_id'] = $member_id;
		}

		
		$discom_list 	= array();
		$discom_list 	= $this->BranchMasters->find("list",['keyField'=>'id','valueField'=>'title','conditions'=>['BranchMasters.status'=>'1','BranchMasters.parent_id'=>'0','BranchMasters.state'=>$this->ApplyOnlines->gujarat_st_id]])->toArray();
		$division 		= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.type'=>3,'status'=>'1']])->toArray();
		$circle 			= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.type'=>2,'status'=>'1']])->toArray();
		$subdivision 		= $this->DiscomMaster->find("all",['fields'=>['title'],'conditions'=>['DiscomMaster.type'=>4,'status'=>'1']])->toArray();
		$BillCategoryList 	= $this->Parameters->GetParameterList(3);
		$FeesReturnEntity 	= $this->FeesReturn->newEntity();
		if(!empty($id)) {
			$FeesReturnData = $this->FeesReturn->get($id);
			if(!empty($FeesReturnData)) {
				$FeesReturnEntity 						= $this->FeesReturn->patchEntity($FeesReturnData,array());
				$FeesReturnEntity->registration_date 	= date('d-m-Y',strtotime($FeesReturnEntity->registration_date));
				$FeesReturnEntity->receipt_date 		= date('d-m-Y',strtotime($FeesReturnEntity->receipt_date));
				$FeesReturnEntity->draft_date 			= date('d-m-Y',strtotime($FeesReturnEntity->draft_date));
				$FeesReturnEntity->date_ppa_signed 		= date('d-m-Y',strtotime($FeesReturnEntity->date_ppa_signed));
				$FeesReturnEntity->date_ppa_term 		= date('d-m-Y',strtotime($FeesReturnEntity->date_ppa_term));
			}
		}

		if(isset($this->request->data) && !empty($this->request->data)) {
			if(!empty($id)) {
				$this->request->data['id'] 	= $id;
				if(empty($customer_id) && empty($member_id) && isset($FeesReturnData->registration_no)) {
					if($this->request->data['registration_no'] != $FeesReturnData->registration_no) {
						$this->Flash->set('You can not change registration number.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'error']]);
						return $this->redirect(URL_HTTP.'ssdsp-registration-return/'.encode($FeesReturnEntity->id));
					}
				}
			}

			$FeesReturnEntity 				= $this->FeesReturn->saveDetails($this->request->data,$arrSessionInfo);
			//$FeesReturnEntity 	= $this->FeesReturn->newEntity($this->request->data,['validate'=>'add']);
		}

		if(isset($FeesReturnEntity->submit) && $FeesReturnEntity->submit == 1) {
			$this->Flash->set('Your Fees Return form submitted successfully.',['key' => 'cutom_admin','element'=>'default','params'=>['type'=> 'success']]);
			return $this->redirect(URL_HTTP.'fees-return-success/'.encode($FeesReturnEntity->id));
		}
		$this->set('division',$division);
		$this->set('circle',$circle);
		$this->set('subdivision',$subdivision);
		$this->set('BillCategoryList',$BillCategoryList);
		//$this->set('ApplyOnlines',$applyOnlinesData);
		//$this->set('fesibility',$fesibility);
		$this->set('RejectReason',$this->FesibilityReport->RejectReason);
		$this->set("pageTitle","SSDSP Registration Charges Return");
		$this->set("discom_details",$main_branch_id);
		$this->set("Mstatus",$this->ApplyOnlineApprovals);
		//$this->set("member_type",$member_type);
		$this->set("DISCOM",$this->ApplyOnlines->DISCOM);
		$this->set("discom_list",$discom_list);
		$this->set("FeesReturn",$FeesReturnEntity);
		$this->set("FeesReturnErrors",$FeesReturnEntity->errors());
		$this->set('Couchdb',$this->Couchdb);
		$this->set('is_member',$is_member);
		$this->set('view_status',$view_status);
	}
	/**
	 *
	 * success
	 * Behaviour : Public
	 * @defination : Method is use to display success page
	 */
	public function success($id='')
	{
		if(isset($id) && !empty($id) && !empty(intval(decode($id)))) {
			$id 		= intval(decode($id));
			$fee_return = $this->FeesReturn->find('all',array('conditions'=>array('id'=>$id)))->first();

		} else {
			return $this->redirect(URL_HTTP);
		}
		$this->set("pageTitle","Fees Return Success");
		$this->set("fee_return",$fee_return);
		$this->set("id",$id);
	}
	/**
	 *
	 * download_letter
	 *
	 * Behaviour : Public
	 *
	 * @defination : Method is use to view corrigendum letter
	 *
	 */
	public function download_fees_return($id = null)
	{
		
		$this->layout 		= false;
		if(empty($id)) {
			$this->Flash->error('Please Select Valid Receipt.');             
			return $this->redirect('home');
		} else {
			$encode_id 					= $id;
			$id 						= intval(decode($id));
			$feesReturnData 			= $this->FeesReturn->find('all',array('conditions'=>array('id'=>$id)))->first();
			
			$feesReturnData->aid 		= "1".str_pad($id,7, "0", STR_PAD_LEFT);
			$LETTER_APPLICATION_NO 		= $feesReturnData->aid;
			$APPLICATION_DATE 			= date("d.m.Y",strtotime($feesReturnData->created));
			
			
			$discom_data		= array();
			$discom_name    	= "";
			$discom_short_name	= "";
			
		}
		$category_name 	= '';
		 
		$discom_details = $this->BranchMasters->find("all",['conditions'=>['id'=>$feesReturnData->discom]])->first();
		$discom_name 	= isset($discom_details->title) ? $discom_details->title : '';
		$this->set("APPLY_ONLINE_MAIN_STATUS",$this->ApplyOnlineApprovals->apply_online_main_status);
		$this->set("pageTitle","Fees-Return View");
		$this->set('FeesReturn',$feesReturnData);
		
		
		$this->set('LETTER_APPLICATION_NO',$LETTER_APPLICATION_NO);
		$this->set('APPLICATION_DATE',$APPLICATION_DATE);
		$this->set('discom_data',$discom_data);
		$this->set('discom_name',$discom_name);
		
		$this->set('category_name',$category_name);
		//$this->set("APPLY_ONLINE_GUJ_STATUS",$this->ApplyOnlineApprovals->apply_online_guj_status);
		$this->set('discom_short_name',$discom_short_name);
		

		/* Generate PDF for estimation of project */
		require_once ROOT . DS . 'vendor' . DS . 'dompdf/autoload.inc.php';
		$dompdf = new Dompdf($options = array());

		$dompdf->set_option("isPhpEnabled", true);
		$this->set('dompdf',$dompdf);
		$dompdf->set_base_path(SITE_ROOT_DIR_PATH.'/pdf/');
		
		
		

		$html = $this->render('/Element/fees_return');
			
		$html = str_replace(array('##RECEIPT_NO##'), array($feesReturnData->fees_return_no), $html);
		if(!empty($feesReturnData->jir_unique_code)) {
			$html = str_replace(array('##JIR_CODE##'), array(', Unique Code: '.$feesReturnData->jir_unique_code), $html);
		} else {
			$html = str_replace(array('##JIR_CODE##'), array(''), $html);
		}
		

		$dompdf->loadHtml($html,'UTF-8');
		$dompdf->setPaper('A4', 'portrait');
		@$dompdf->render();

		// Output the generated PDF to Browser
		/*if($isdownload){
			$dompdf->stream('corrigendum-'.$LETTER_APPLICATION_NO);
		}*/
		$output = $dompdf->output();
		header("Content-type:application/pdf");
		header("Content-Disposition:inline;filename=".$LETTER_APPLICATION_NO.".pdf");
		echo $output;
		die;
	}
	/**
	 * retun_form_list
	 * Behaviour : public
	 * @param : 
	 * @defination : Method is use to list return form for main member of GEDA
	 *
	 */
	public function return_form_list()
	{
		//$this->setCustomerArea();
		$member_id 				= $this->Session->read("Members.id");
		$is_member          	= false;
		if(empty($member_id))
		{
			return $this->redirect(URL_HTTP.'home');
		}
		$area 			= $this->Session->read('Members.area');
		$circle 		= $this->Session->read('Members.circle');
		$division 		= $this->Session->read('Members.division');
		$subdivision 	= $this->Session->read('Members.subdivision');
		$section 		= $this->Session->read('Members.section');
		$member_id 		= $this->Session->read('Members.id');
		$member_type 	= $this->Session->read('Members.member_type');
		$main_branch_id = array();
		if (!empty($member_id) && $member_type == $this->ApplyOnlines->DISCOM)
		{
			$field      = "area";
			$id         = $area;

			if (!empty($section)) {
				$field      = "section";
				$id         = $section;
			} else if (!empty($subdivision)) {
				$field      = "subdivision";
				$id         = $subdivision;
			} else if (!empty($division)) {
				$field      = "division";
				$id         = $division;
			} else if (!empty($circle)) {
				$field      = "circle";
				$id         = $circle;
			}
			$main_branch_id = array("field"=>$field,"id"=>$id);
		}

		if(!empty($member_id)){
			$is_member      	= true;
		}
		
		$from_date 				= isset($this->request->data['DateFrom'])?$this->request->data['DateFrom']:'';
		$end_date 				= isset($this->request->data['DateTo'])?$this->request->data['DateTo']:'';
		$status 				= isset($this->request->data['status'])?$this->request->data['status']:'';
		$fees_return_no 		= isset($this->request->data['fees_return_no'])?$this->request->data['fees_return_no']:'';
		$registration_no 		= isset($this->request->data['registration_no'])?$this->request->data['registration_no']:'';
		$geda_application_no 	= isset($this->request->data['geda_application_no'])?$this->request->data['geda_application_no']:'';
		$installer_name 		= isset($this->request->data['installer_name_multi'])?explode(",",$this->request->data['installer_name_multi']):'';
		$download_excel 		= (isset($this->request->data['download'])?$this->request->data['download']:0);
		$startOffset 			= (isset($this->request->data['start'])?$this->request->data['start']:0);
		$arrRequestList			= array();
		$arrCondition			= array('FeesReturn.id IS NOT NULL');
		

		

		$this->SortBy		= "FeesReturn.id";
		$this->Direction	= "ASC";
		$this->intLimit		= PAGE_RECORD_LIMIT;
		$this->CurrentPage  = 1;
		$option 			= array();
		$memberApproved 	= '0';
		$memberApproved 	= in_array($member_id, $this->ApplyOnlines->ALLOWED_APPROVE_GEDAIDS) ? '1' : '0';

		$option['colName']  = array('id','fees_return_no','created','spg_applicant','registration_no','registration_date','capacity','discom','receipt_no','status','action');
			
		
		$sortArr 			= array('id'				=> 'FeesReturn.id',
									'fees_return_no'	=> 'FeesReturn.fees_return_no',
									'created'			=> 'FeesReturn.created',
									'spg_applicant' 	=> 'FeesReturn.spg_applicant',
									'registration_no'	=> 'FeesReturn.registration_no',
									'registration_date'	=> 'FeesReturn.registration_date',
									'capacity'			=> 'FeesReturn.capacity',
									'discom'			=> 'discom_master.title',
									'receipt_no'		=> 'FeesReturn.receipt_no',
									'status' 			=> 'FeesReturn.status'
								);
		$this->SetSortingVars('FeesReturn',$option,$sortArr);

		$option['dt_selector']			='table-example';
		$option['formId']				='formmain';
		$option['url']					= '';
		$option['recordsperpage']		= PAGE_RECORD_LIMIT;
		//$option['allsortable']			= '-1';
		$option['total_records_data']	= 0;
		$option['bPaginate']			= 'true';
		$option['bLengthChange']		= 'false';
		$option['order_by'] 			= "order : [[0,'DESC']]";
		$this->paginate['limit'] 		= (isset($this->request->data['length']) && !empty($this->request->data['length'])) ? $this->request->data['length'] : PAGE_RECORD_LIMIT;
		$this->paginate['page'] 		= ($startOffset/$this->paginate['limit'])+1;
		$JqdTablescr 					= $this->JqdTable->create($option);
		$Joins 							= array([	'table'		=> 'branch_masters',
													'alias' 	=> 'BranchMasters',
													'type' 		=> 'LEFT',
													'conditions'=> 'BranchMasters.id=FeesReturn.discom'],
													[	'table'		=> 'discom_master',
													'alias' 	=> 'discom_master',
													'type' 		=> 'LEFT',
													'conditions'=> 'BranchMasters.discom_id=discom_master.id'],
													[	'table'		=> 'members',
													'alias' 	=> 'members',
													'type' 		=> 'LEFT',
													'conditions'=> 'FeesReturn.received_by=members.id']
												
												);
			$CountFields	= array('FeesReturn.id');
			$Fields 		= array('FeesReturn.id',
									'FeesReturn.fees_return_no',
									'FeesReturn.spg_applicant',
									'FeesReturn.registration_no',
									'FeesReturn.registration_date',
									'FeesReturn.capacity',
									'discom_master.title',
									'FeesReturn.receipt_no',
									'FeesReturn.created',
									'FeesReturn.status',
									'FeesReturn.referenceno',
									'FeesReturn.txtstatus',
									'members.name',
									'FeesReturn.received_date'
								);
			if ($fees_return_no != '') {
				$arrCondition['FeesReturn.fees_return_no LIKE '] = '%'.$fees_return_no.'%';
			}
			if ($status != '') {
				$arrCondition['FeesReturn.status'] = $status;
			} 
			if($member_id == 1330 && $status == '')
			{
				$arrCondition['FeesReturn.status in'] = array(1,3,4);
			}
			if ($registration_no != '') {
				$arrCondition['FeesReturn.registration_no like '] = '%'.$registration_no.'%';
			}
/*,
																		'page' 			=> $this->CurrentPage,
																		'limit' 		=> $this->intLimit*/
			$query_data 		= $this->FeesReturn->find('all',array(	'fields'		=> $Fields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
																		'order'			=> array($this->SortBy=>$this->Direction)));


			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "FeesReturn.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}
			$query_data_count 	= $this->FeesReturn->find('all',array('fields'		=> $CountFields,
																		'conditions' 	=> $arrCondition,
																		'join' 			=> $Joins,
															));
			if(!empty($from_date) && !empty($end_date))
			{
				$fields_date  	= "FeesReturn.created";
				$StartTime    	= date('Y-m-d H:i:s',strtotime($from_date.' 00:00:00'));
				$EndTime    	= date('Y-m-d H:i:s',strtotime($end_date.' 23:59:59'));
				$query_data_count->where([function ($exp, $q) use ($StartTime, $EndTime,$fields_date) {
					return $exp->between($fields_date, $StartTime, $EndTime);
				}]);
			}
		if ($this->request->is('ajax') && $download_excel == 0)
		{
			$total_query_records	= $query_data_count->count();
			$start_page 			= isset($this->request->data['start']) ? $this->request->data['start'] : 1;
			$this->paginate['limit']= PAGE_RECORD_LIMIT;
			$this->paginate['page']	= ($start_page/$this->paginate['limit'])+1;
			if(isset($this->request->data['page_no']) && !empty($this->request->data['page_no']))
			{
				$posible_page 				= $total_query_records/$this->paginate['limit'];
				if($posible_page < $this->request->data['page_no']) {
					$this->paginate['page'] = $posible_page;
				} else {
					$this->paginate['page'] = $this->request->data['page_no'];
				}
			}
			else
			{
				$this->paginate['page'] 	= ($start_page/$this->paginate['limit'])+1;
			}


			$arrRequestList	= $this->paginate($query_data);
			$out 			= array();
			$counter 		= 1;
			$page_mul 		= ($this->CurrentPage-1);
			foreach($arrRequestList->toArray() as $key=>$val)
			{
				$temparr 	= array();
				foreach($option['colName'] as $key) {
					if($key=='id') {
						$temparr[$key]=$counter + ($page_mul * $this->paginate['limit']);
					}
					else if($key=='fees_return_no')
					{
						$temparr[$key]='<a target="" href="'.URL_HTTP.'ssdsp-registration-return/'.encode($val->id).'/1">'.$val->fees_return_no.'</a>';
					}
					else if($key=='created')
					{
						$temparr[$key]=date('d-m-Y H:i a',strtotime($val->created));
					}
					else if($key=='registration_date')
					{
						$temparr[$key]=date('d-m-Y',strtotime($val->registration_date));
					}
					else if($key=='discom') {
						if(!is_null($val->discom_master['title']) && !empty($val->discom_master['title']))
						{
							$temparr[$key]	= $val->discom_master['title'];
						}
					}
					else if($key=='status') {
						/*$temparr[$key]	= ($val->status == 1) ? 'Approved' : (($val->status == 2) ? 'Rejected' : 'Pending');
						if($val->status == 1) {
							if(empty($val->referenceno) && !empty($val->txtstatus)) {
								$str_app 	= ucfirst($val->txtstatus);//'Payment Requested';
							} else if(!empty($val->referenceno)) {
								$str_app 	= 'Paid';
							} else {
								$str_app 	= 'Payment Request Pending';
							}
							$temparr[$key]	= $temparr[$key]." - ".$str_app;
						}*/
						if($val->status == 4) {
							$temparr[$key]	= 'Paid';
						} elseif($val->status == 3) {
							$temparr[$key]	= 'Payment Requested';
						} elseif($val->status == 2) {
							$temparr[$key]	= 'Rejected';
						} elseif($val->status == 1) {
							$temparr[$key]	= 'Approved';
						} else {
							$temparr[$key]	= 'Pending';
						}
						
					}
					else if($key=='action') {
						$temparr[$key]	= '<a href="'.URL_HTTP.'download-fees-return/'.encode($val->id).'" class="dropdown-item" target="_blank" ><i class="fa fa-download" aria-hidden="true"></i> Download</a>';
						if(empty($val->referenceno)) {
							$temparr[$key]	.= '<a href="'.URL_HTTP.'ssdsp-registration-return/'.encode($val->id).'" class="dropdown-item" target="_blank" ><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>';
						}
						if(empty($val->status)) {
							$temparr[$key]	.= '<a href="javascript:;" class="SubmitRequest approve_Status dropdown-item" data-id="'. encode($val->id) .'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Approve</a>';	
						}
						if($val->status>=1 && $member_id==1330) {
							$temparr[$key]	.= '<a href="javascript:;" class="SubmitRequest approve_Status dropdown-item" data-id="'. encode($val->id) .'" data-approved-by="'.$val->members['name'].'" data-approved-on="'.$val->received_date.'"><i class="fa fa-check-square-o" aria-hidden="true"></i> Payment Request</a>';	
						}
						$temparr['action']	= '	<span class="action-row action-btn">
													<div class="dropdown">
														<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															Actions <i class="fa fa-chevron-down"></i>
														</button>
														<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">'.$temparr['action'].'</div>
													</div>
												</span>';
					}
					else if (isset($val[$key])) {
						$temparr[$key]	= $val[$key];
					} else {
						$temparr[$key]	= "-";
					}
				}
				$counter++;
				$out[] = $temparr;
			}
			header('Content-type: application/json');
			echo json_encode(array(	"draw" 				=> intval($this->request->data['draw']),
									"recordsTotal"    	=> intval($this->request->params['paging']['FeesReturn']['count']),
									"recordsFiltered" 	=> intval($this->request->params['paging']['FeesReturn']['count']),
									"data"            	=> $out));
			die;
		} else {
			############# EXCEL DOWNLOAD ###########
			if($download_excel == 1) {
				
				$PhpExcel 			= $this->PhpExcel;
				$PhpExcel->createExcel();
				$objDrawing 		= new \PHPExcel_Worksheet_MemoryDrawing();
				$objDrawing->setCoordinates('A1');
				$objDrawing->setWorksheet($PhpExcel->getExcelObj()->getActiveSheet());
				$j 					= 1;
				$i 					= 1;
				$arrReportFields 	= array('sr_no'				=> "Sr no",
											'fees_return_no' 	=> 'Fees Return No.',
											'spg_applicant'		=> "Applicant Name",
											'mobile'			=> "Contact No.",
											'registration_no' 	=> "Registration No.",
											'registration_date'	=> "Registration Date",
											'capacity'			=> "Capacity",
											'discom'			=> "Discom Name",
											'name_getco'		=> "GETCO Name",
											'draft_no'			=> "Draft No.",
											'draft_date'		=> "Draft Date",
											'demand_bank_name' 	=> "Draft Bank Name",
											'demand_amount' 	=> "Draft Amount",
											'receipt_no' 		=> "Receipt No.",
											'receipt_date' 		=> "Receipt Date",
											'account_no' 		=> "Account No.",
											'bank_name' 		=> "Bank Name",
											'ifsc_code' 		=> "IFSC Code",
											'status' 			=> "Status",
											'received_by' 		=> "Last Action Taken by",
											'received_date' 	=> "Last Action Taken on",
											'received_msg' 		=> "Message",
											'created' 			=> "Created on"
											);
				//$arrSecond 			= array('ticket_closed'=>'Ticket Closed','last_response'=>'Last Response','2nd_last_response'=> '2nd Last Response','3rd_last_response'=> '3rd Last Response','4th_last_response'=> '4th Last Response','5th_last_response'=> '5th Last Response');
				foreach ($arrReportFields as $key=>$Field_Name) {
					$RowName 	= $this->FeesReturn->GetExcelColumnName($i);

					$ColTitle  	= $Field_Name;
					$PhpExcel->writeCellValue($RowName.$j,$ColTitle);
					$PhpExcel->getExcelObj()->getActiveSheet()->getColumnDimension($RowName)->setAutoSize(true);
					$i++;
				}

				$j++;
				$i = 1;
				
				
				$FeesData 	= $query_data->toArray();
				if(!empty($FeesData)){
					foreach($FeesData as $key=>$val) {
						$created 	=  (!empty($val['created'])) 	? date('Y-m-d H:i:s',strtotime($val['created'])) : "";
						
						if($val['status'] == 4) {
							$status	= 'Paid';
						} elseif($val['status'] == 3) {
							$status	= 'Payment Requested';
						} elseif($val['status'] == 2) {
							$status	= 'Rejected';
						} elseif($val['status'] == 1) {
							$status	= 'Approved';
						} else {
							$status	= 'Pending';
						}

						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$j-1);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->fees_return_no);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->spg_applicant);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->mobile);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->registration_no);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,date('d-m-Y',strtotime($val->registration_date)));
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->capacity);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->discom_master['title']);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->name_getco);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->draft_no);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,date('d-m-Y',strtotime($val->draft_date)));
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->demand_bank_name);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->demand_amount);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->receipt_no);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->receipt_date);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->account_no);
						$i++;						
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->bank_name);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->ifsc_code);
						$i++;
						//$status  = ($val->status == 1) ? 'Approved' : (($val->status == 2) ? 'Rejected' : 'Pending');
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$status);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->received_by);
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,date('d-m-Y',strtotime($val->received_date)));
						$i++;
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->writeCellValue($RowName.$j,$val->received_msg);
						$i++;	
						$RowName = $this->FeesReturn->GetExcelColumnName($i);
						$PhpExcel->getExcelObj()->getActiveSheet()->getStyle($RowName.$j)->getAlignment()->setWrapText(true);
						$PhpExcel->writeCellValue($RowName.$j,date('d-m-Y H:i a',strtotime($val->created)));
						$i++;

			
						$i=1;
						$j++;
					}
				}
				$PhpExcel->downloadFile(time());
				exit;
			}
			################ EXCEL DOWNLOAD ###########
		}
		$installers_list= array();
		$REQUEST_STATUS = array("0"=>"Pending","1"=>"Approved","2"=>"Rejected","3"=>"Payment Requested","4"=>"Paid");
		if($member_id == 1330) {
			$REQUEST_STATUS 	= array("1"=>"Approved","3"=>"Payment Requested","4"=>"Paid");
		}
		$RECEVIED_STATUS 	= array("1"=>"YES","0"=>"NO");
		$installers_list 	= $this->Installers->getInstallerListReport();
		$this->set('arrRequestList',$arrRequestList);
		$this->set('JqdTablescr',$JqdTablescr);
		$this->set('period',$this->period);
		$this->set('limit',$this->intLimit);
		$this->set("CurrentPage",$this->CurrentPage);
		$this->set("SortBy",$this->SortBy);
		$this->set("Direction",$this->Direction);
		$this->set("REQUEST_STATUS",$REQUEST_STATUS);
		$this->set("RECEVIED_STATUS",$RECEVIED_STATUS);
		$this->set("pagetitle",'SSDSP Return List');
		$this->set("page_count",0);
		$this->set("is_member",$is_member);
	
		$this->set("memberApproved",$memberApproved);
	}
	/**
	 *
	 * ApproveRequest
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to approved or rejected status of request.
	 *
	 */
	public function ApproveRequest()
	{
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['requestid']) ? decode($this->request->data['requestid']) : 0);
		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$FeesReturnData  		= $this->FeesReturn->find("all",['conditions'=>['id'=>$id]])->first();
			$FeesReturn_Data        = $this->FeesReturn->get($FeesReturnData->id);
			if (!empty($FeesReturn_Data)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					if(empty($this->request->data['received_msg']) && $this->request->data['return_status']==2) {
						$ErrorMessage   = "Message is required.";
						$success        = 0;
					} else {
						$FeesReturnEntity= $this->FeesReturn->patchEntity($FeesReturn_Data,$this->request->data);

						$FeesReturnEntity->received_msg 		= strip_tags((isset($this->request->data['received_msg'])?$this->request->data['received_msg']:''));
						$FeesReturnEntity->refunded_amount 		= (isset($this->request->data['refunded_amount'])?$this->request->data['refunded_amount']:'');
						$FeesReturnEntity->received_by 			= $memberId;
						$FeesReturnEntity->received_ip_address 	= $this->request->clientIp();
						$FeesReturnEntity->received_date		= $this->NOW();
						$FeesReturnEntity->status 				= $this->request->data['return_status'];
						$FeesReturnEntity->modified 			= $this->NOW();
						$FeesReturnEntity->modified_by 			= $memberId;
						if($this->FeesReturn->save($FeesReturnEntity)) {
							$ErrorMessage   	= "Request Status Updated Sucessfully.";
							$success        	= 1;
						} else {
							$ErrorMessage   	= "Error while sending message.";
							$success        	= 0;
						}
					}
				}
			}else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
	}
	/**
	 * fetchFeesRequest
	 * Behaviour : public
	 * @defination : Method is use to fetch fees request data.
	 */
	public function fetchFeesRequest()
	{
		$this->autoRender       = false;
		$response 				= '';
		$member_id 				= $this->Session->read("Members.id");
		$requestid            	= intval(decode($this->request->data['requestid']));
		$requestid_fetchData   	= $this->FeesReturn->find("all",['fields'=>array('id','status','received_msg','demand_amount','capacity','refunded_amount','refundable_amount','txtstatus','payment_transfer_completed','referenceno','payment_transfer_completed','spg_applicant','account_no','ifsc_code','bank_name','referenceno'),'conditions'=>['id'=>$requestid]])->first();
		if(!empty($requestid_fetchData))
		{
			$response   		= $requestid_fetchData;
			if($response->status>=1 && strtolower($response->txtstatus) !='accepted' && ($member_id == 1330)) {
				$response->payment_button ='<button type="button" id="" class="btn btn-primary " data-form-name="approve_request" templatevars="" onClick="javascript:ClickPay(\''.$this->request->data['requestid'].'\');">Pay</button>';
			} else if(strtolower($response->txtstatus) =='accepted' && empty($response->payment_transfer_completed) && ($member_id == 1330)) {
				$response->payment_button ='<button type="button" id="" class="btn btn-primary " data-form-name="approve_request" templatevars="" onClick="javascript:ClickGetStatus(\''.$this->request->data['requestid'].'\');">Fetch Status</button>';
			} else if(!empty($response->referenceno)) {
				$response->payment_button = 'Reference No. - '.$response->referenceno. ' Completed on '.$response->payment_transfer_completed;
			}
		}
		echo json_encode(array('type'=>'ok','response'=>$response));
		exit;
	}
/*	
	public function ApproveRequest()
	{
		$this->autoRender   = false;
		$id                 = (isset($this->request->data['requestid']) ? decode($this->request->data['requestid']) : 0);
		$memberId         	= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		if(empty($id)) {
			$ErrorMessage   = "Invalid Request. Please validate form details.";
			$success        = 0;
		} else {
			$FeesReturnData  		= $this->FeesReturn->find("all",['conditions'=>['id'=>$id]])->first();
			$FeesReturn_Data        = $this->FeesReturn->get($FeesReturnData->id);
			if (!empty($FeesReturn_Data)) {
				if ($this->request->is('post') || $this->request->is('put')) {
					if(empty($this->request->data['received_msg']) && $this->request->data['return_status']==2) {
						$ErrorMessage   = "Message is required.";
						$success        = 0;
					} else {
						$FeesReturnEntity= $this->FeesReturn->patchEntity($FeesReturn_Data,$this->request->data);

						$FeesReturnEntity->received_msg 		= strip_tags((isset($this->request->data['received_msg'])?$this->request->data['received_msg']:''));
						$FeesReturnEntity->refunded_amount 		= (isset($this->request->data['refunded_amount'])?$this->request->data['refunded_amount']:'');
						$FeesReturnEntity->payment_request_by 	= $memberId;
						$FeesReturnEntity->payment_ip_address 	= $this->request->clientIp();
						$FeesReturnEntity->payment_request_date	= $this->NOW();
						$FeesReturnEntity->status 				= 2;
						$FeesReturnEntity->modified 			= $this->NOW();
						$FeesReturnEntity->modified_by 			= $memberId;
						if($this->FeesReturn->save($FeesReturnEntity)) {
							$ErrorMessage   	= "Payment Request Added Sucessfully.";
							$success        	= 1;
						} else {
							$ErrorMessage   	= "Error while sending message.";
							$success        	= 0;
						}
					}
				}
			}else {
				$ErrorMessage   			= "Invalid Request. Please validate form details.";
				$success        			= 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success));
		exit;
	}*/
	/**
	 *
	 * PaymentRequestProcess
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to proccess payment.
	 *
	 */
	public function PaymentRequestProcess()
	{	
		$this->autoRender   = false;
		$id 				= (isset($this->request->data['request_id']) ? decode($this->request->data['request_id']) : 0);
		$memberId 			= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$ErrorMessage 		= "";
		$success 			= 1;
		$approved_on		= '';
		$approved_by		= '';

		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
		} else if(empty($memberId)) {
			$ErrorMessage 	= "Login";
			$success 		= 0;
		} else if($memberId != 1330) {
			$ErrorMessage 	= "You are not authorized to access this request.";
			$success 		= 0;
		} else {
			$fees_return_id 	= $id;
			$feesDetails 		= $this->FeesReturn->find('all',array('conditions'=>array('id'=>trim($fees_return_id))))->first();
			if(!empty($feesDetails)) {
				$memberDetails 		= $this->Members->find('all',array('conditions'=>array('id'=>trim($feesDetails->received_by))))->first();
				$approved_on 		= $feesDetails->received_date;
				$approved_by 		= isset($memberDetails->name) ? $memberDetails->name : '-';
				$initialize_vector 	= getRandomString(16);//'SRh2SnMeXq9N6iBt';
				$symmetric_key 		= getRandomString(32);//'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';
				$requestURIId 		= generateGUID();
				$amount 			= (isset($feesDetails->refunded_amount) && !empty($feesDetails->refunded_amount)) ? $feesDetails->refunded_amount : 0;
				$remitter_address_1 = 'HDFC Bank Ltd. Retail Assets';
				$remitter_address_2 = 'Chandivali';
				$remitter_address_3 = 'Mumbai - 400072';
				$bene_ifsc_code 	= $feesDetails->ifsc_code;//'CITI0000001';
				$bene_account_no 	= $feesDetails->account_no;//'041131210001';
				$bene_name 			= $feesDetails->spg_applicant;//'SRINIVAS MOTORS';
				$remit_information_1= 'API BTG WBO';
				$bene_email_id 		= $feesDetails->email;//'jayshree.tailor@ahasolar.in';
				$txndesc 			= 'BTG WBO API';
				$FeesReturn_sel   	= $this->FeesReturn->find();
				$FeesReturn_sel->hydrate(false);
				$max_return_data 	= $FeesReturn_sel->select(['max' => $FeesReturn_sel->func()->max('FeesReturn.max_return_number')])->first();
				if(empty($feesDetails->max_return_number)) {
					$this->FeesReturn->updateAll(['max_return_number' => $max_return_data['max']+1],['id' => $fees_return_id]);
				}
				$max_number 		= empty($feesDetails->max_return_number) ? str_pad(($max_return_data['max']+1),5, "0", STR_PAD_LEFT) : str_pad($feesDetails->max_return_number,5, "0", STR_PAD_LEFT);
				$batch_num_ext 		= "1".$max_number;
				$payment_ref_no 	= 'NEFT'.$max_number;
				$xmlBasicData 		= '<faxml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="CO_NEF.xsd" Id="'.$requestURIId.'">
				   <header>
				      	<extsysname>COAPI</extsysname>
						<datpost>'.date('Y-m-d').'</datpost>
						<batchnumext>'.$batch_num_ext.'</batchnumext>
						<idtxn>CO_NEF</idtxn>
						<codcurr>INR</codcurr>
						<iduser>'.Configure::read('API_USER').'</iduser>
						<idcust>'.Configure::read('CUSTOMER_ID').'</idcust>
						<groupid>'.Configure::read('GROUP_ID').'</groupid>
						<reqdatetime>'.date('Y-m-d').'T'.date('H:i:s').'</reqdatetime>
				   </header>
				   <summary>
				      <orgsumpmt>'.$amount.'</orgsumpmt>
				      <orgcountpmt>1</orgcountpmt>
				   </summary>
				   <paymentlist>
				      <payment>
				         <stanext>1</stanext>
				         <paymentrefno>'.$payment_ref_no.'</paymentrefno>
				         <CustId>'.Configure::read('CUSTOMER_ID').'</CustId>
				         <Amount>'.$amount.'</Amount>
				         <RemitterName>HDFC Bank Ltd</RemitterName>
				         <RemitterAccount>'.Configure::read('ACCOUNT_NUMBER').'</RemitterAccount>
				         <RemitterAccountType>10</RemitterAccountType>
				         <Remitter_Address_1>'.$remitter_address_1.'</Remitter_Address_1>
				         <Remitter_Address_2>'.$remitter_address_2.'</Remitter_Address_2>
				         <Remitter_Address_3>'.$remitter_address_3.'</Remitter_Address_3>
				         <Remitter_Address_4 />
				         <BeneIFSCCODE>'.$bene_ifsc_code.'</BeneIFSCCODE>
				         <BeneAccountType>11</BeneAccountType>
				         <BeneAccountNumber>'.$bene_account_no.'</BeneAccountNumber>
				         <BeneName>'.$bene_name.'</BeneName>
				         <BeneAddress_1 />
				         <BeneAddress_2 />
				         <BeneAddress_3 />
				         <BeneAddress_4 />
				         <RemitInformation_1>'.$remit_information_1.'</RemitInformation_1>
				         <RemitInformation_2 />
				         <RemitInformation_3 />
				         <RemitInformation_4 />
				         <RemitInformation_5 />
				         <RemitInformation_6 />
				         <ContactDetailsID />
				         <ContactDetailsDETAIL />
				         <codcurr>INR</codcurr>
				         <refstan>2</refstan>
				         <forcedebit>N</forcedebit>
				         <txndesc>'.$txndesc.'</txndesc>
				         <beneid />
				         <emailid>'.$bene_email_id.'</emailid>
				         <advice1 />
				         <advice2 />
				         <advice3 />
				         <advice4 />
				         <advice5 />
				         <advice6 />
				         <advice7 />
				         <advice8 />
				         <advice9 />
				         <advice10 />
				         <addnlfield1 />
				         <addnlfield2 />
				         <addnlfield3 />
				         <addnlfield4 />
				         <addnlfield5 />
				      </payment>
				   </paymentlist>
				</faxml>';
				$SignedPayload 		= generateXMLSignature($xmlBasicData,$this->private_key,$this->certificateValue,$requestURIId);
				$SignedPayload 		= str_replace(array('</faxml>','<Signature '), array('','</faxml><Signature '), $SignedPayload);
				
				$payloadWithSigned 	= $initialize_vector.'<?xml version="1.0" encoding="UTF-8"?><request>'.$SignedPayload.'</request>';

				$RequestSignatureEncryptedValue = cryptAES($payloadWithSigned,$symmetric_key);
				$SymmetricKeyEncryptedValue 	= cryptRSA($symmetric_key);

				$member_id 		= $this->Session->read("Members.id");
				$OAuthTokenV 	='';
				$getOAuthToken	= $this->getOAuthToken($fees_return_id,$member_id);
				$arrRequest 	= array();
				if(isset($getOAuthToken['access_token'])) {
					$arrRequest['RequestSignatureEncryptedValue'] 	= $RequestSignatureEncryptedValue;
					$arrRequest['SymmetricKeyEncryptedValue'] 		= $SymmetricKeyEncryptedValue;
					$arrRequest['Scope'] 							= Configure::read('GROUP_ID');
					$arrRequest['TransactionId'] 					= encode($feesDetails->id);
					$arrRequest['OAuthTokenValue'] 					= isset($getOAuthToken['access_token']) ? $getOAuthToken['access_token'] : '';
					$arrResponse 									= $this->setCallPaymentData($arrRequest,$fees_return_id,$member_id,$payloadWithSigned);
					if(isset($arrResponse['Status']) && $arrResponse['Status'] == 'SUCCESS')
					{
						if(isset($arrResponse['GWSymmetricKeyEncryptedValue']) && !empty($arrResponse['GWSymmetricKeyEncryptedValue'])) {
							$GWSymmetricKeyDecryptedValue 	= dcryptRSA($arrResponse['GWSymmetricKeyEncryptedValue']);
							
							if(isset($arrResponse['ResponseSignatureEncryptedValue']) && !empty($arrResponse['ResponseSignatureEncryptedValue'])) {
								
								$ResponseSignatureDecryptedValue 	= decryptAES($arrResponse['ResponseSignatureEncryptedValue'],$GWSymmetricKeyDecryptedValue);
								
								$validateSingnature 	= validateSignature(trim($ResponseSignatureDecryptedValue));
								if($validateSingnature == 1) {
									$xmlOutputData 		= simplexml_load_string(trim($ResponseSignatureDecryptedValue));
									$json 				= json_encode($xmlOutputData);
									$ResponseXML 		= json_decode($json,true);
									if(isset($arrResponse['log_id']) && !empty($arrResponse['log_id']))
									{
										$this->FeesReturnApiLog->updateAll(['response_xml_payload'=>json_encode($ResponseXML)],['id'=>$arrResponse['log_id']]);
									}
									if(isset($ResponseXML['faxml']['header']))
									{
										$outResponse 					= $ResponseXML['faxml']['header'];
										$arrFeesReturn['txtstatus'] 	= isset($outResponse['txtstatus']) ? $outResponse['txtstatus'] : '';
										$arrFeesReturn['batchnum'] 		= isset($outResponse['batchnum']) ? $outResponse['batchnum'] : ''; 
										$arrFeesReturn['batchnumext'] 	= isset($outResponse['batchnumext']) ? $outResponse['batchnumext'] : ''; 
										$arrFeesReturn['codstatus'] 	= isset($outResponse['codstatus']) ? $outResponse['codstatus'] : ''; 
										$arrFeesReturn['datpost'] 		= isset($outResponse['datpost']) ? $outResponse['datpost'] : ''; 
										$arrFeesReturn['paymentrefno'] 	= isset($ResponseXML['faxml']['paymentlist']['payment']['paymentrefno']) ? $ResponseXML['faxml']['paymentlist']['payment']['paymentrefno'] : ''; 
										if(isset($ResponseXML['faxml']['header']['txtstatus']) && strtolower($ResponseXML['faxml']['header']['txtstatus']) == 'accepted') {
											$arrFeesReturn['payment_init_date']	= $this->NOW();
											$arrFeesReturn['status'] 			= 3;
											$ErrorMessage 	= "Payment Accepted at HDFC Bank";
											$success 		= 1;
										} else {
											$ErrorMessage 	= $ResponseXML['faxml']['header']['txtstatus'];
											$success 		= 0;
										}
										$this->FeesReturn->updateAll($arrFeesReturn,['id'=>$feesDetails->id]);
										
									}
								} else {
									$ErrorMessage 	= "Invalid Signature";
									$success 		= 0;
								}
							
							} else {
								$ErrorMessage 	= "Response is not coming properly";
								$success 		= 0;
							}
						}
					}
				} else {
					$ErrorMessage 	= "Invalid Token";
					$success 		= 0;
				}
			} else {
				$ErrorMessage   = "Request Not Found.";
				$success        = 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success,'approved_on'=>$approved_on,'approved_by'=>$approved_by));
		exit;
	}
	/**
	 *
	 * GetPaymentUpdateDetails
	 *
	 * Behaviour : public
	 *
	 * @defination : Method is use to get payment details.
	 *
	 */
	public function GetPaymentUpdateDetails()
	{	
		$this->autoRender   = false;
		$id 				= (isset($this->request->data['request_id']) ? decode($this->request->data['request_id']) : 0);
		$memberId 			= $this->Session->read("Members.id");
		$member_type 		= $this->Session->read('Members.member_type');
		$ErrorMessage 		= "";
		$success 			= 1;
		$approved_on		= '';
		$approved_by		= '';
		if(empty($id)) {
			$ErrorMessage 	= "Invalid Request. Please validate form details.";
			$success 		= 0;
		} else if(empty($memberId)) {
			$ErrorMessage 	= "Login";
			$success 		= 0;
		} else if($memberId != 1330) {
			$ErrorMessage 	= "You are not authorized to access this request.";
			$success 		= 0;
		} else {
			$fees_return_id 	= $id;
			$feesDetails 		= $this->FeesReturn->find('all',array('conditions'=>array('id'=>trim($fees_return_id))))->first();
			if(!empty($feesDetails)) {
				$memberDetails 		= $this->Members->find('all',array('conditions'=>array('id'=>trim($feesDetails->received_by))))->first();
				$approved_on 		= $feesDetails->received_date;
				$approved_by 		= isset($memberDetails->name) ? $memberDetails->name : '-';
				if(!empty($feesDetails->payment_init_date) && !empty($feesDetails->paymentrefno)) {
					$initialize_vector 	= getRandomString(16);//'SRh2SnMeXq9N6iBt';
					$symmetric_key 		= getRandomString(32);//'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';
					$requestURIId 		= generateGUID();
					$xmlBasicData 		= '<faml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="CO_NEF.xsd" Id="'.$requestURIId.'">
										<header>
											<extsysname>COAPI</extsysname>
											<datpost>'.date('Y-m-d').'</datpost>
											<batchnumext>'.$feesDetails->batchnumext.'</batchnumext>
											<idtxn>CO_NEF</idtxn>
											<iduser>'.Configure::read('API_USER').'</iduser>
											<idcust>'.Configure::read('CUSTOMER_ID').'</idcust>
											<inqcount>1</inqcount>
											<groupid>'.Configure::read('GROUP_ID').'</groupid>
											<reqdatetime>'.date('Y-m-d').'T'.date('H:i:s').'</reqdatetime>
										</header>
										<inqlist>
											<payment>
												<paymentrefno>'.$feesDetails->paymentrefno.'</paymentrefno>
												<dattxn>'.date('Y-m-d',strtotime($feesDetails->payment_init_date)).'</dattxn>
											</payment>
										</inqlist>
										</faml>';
					$SignedPayload 		= generateXMLSignature($xmlBasicData,$this->private_key,$this->certificateValue,$requestURIId);
					$SignedPayload 		= str_replace(array('</faml>','<Signature '), array('','</faml><Signature '), $SignedPayload);
					
					$payloadWithSigned 	= $initialize_vector.'<?xml version="1.0" encoding="UTF-8"?><request>'.$SignedPayload.'</request>';
					$RequestSignatureEncryptedValue = cryptAES($payloadWithSigned,$symmetric_key);
					$SymmetricKeyEncryptedValue 	= cryptRSA($symmetric_key);
					
					$member_id 		= $this->Session->read("Members.id");
					$OAuthTokenV 	='';
					$getOAuthToken	= $this->getOAuthToken($fees_return_id,$member_id);
					$arrRequest 	= array();
					if(isset($getOAuthToken['access_token'])) {
						
						$arrRequest['RequestSignatureEncryptedValue'] 	= $RequestSignatureEncryptedValue;
						$arrRequest['SymmetricKeyEncryptedValue'] 		= $SymmetricKeyEncryptedValue;
						$arrRequest['Scope'] 							= Configure::read('GROUP_ID');//'CBXMGRT3';
						$arrRequest['TransactionId'] 					= encode($fees_return_id);
						$arrRequest['OAuthTokenValue'] 					= isset($getOAuthToken['access_token']) ? $getOAuthToken['access_token'] : '';
						$arrResponse 									= $this->setCallInquiryStatusData($arrRequest,$fees_return_id,$member_id,$payloadWithSigned);
						
						if(isset($arrResponse['Status']) && $arrResponse['Status'] == 'SUCCESS')
						{
							if(isset($arrResponse['GWSymmetricKeyEncryptedValue']) && !empty($arrResponse['GWSymmetricKeyEncryptedValue'])) {
								$GWSymmetricKeyDecryptedValue 	= dcryptRSA($arrResponse['GWSymmetricKeyEncryptedValue']);
								if(isset($arrResponse['ResponseSignatureEncryptedValue']) && !empty($arrResponse['ResponseSignatureEncryptedValue'])) {
									$ResponseSignatureDecryptedValue 	= decryptAES($arrResponse['ResponseSignatureEncryptedValue'],$GWSymmetricKeyDecryptedValue);
									$validateSingnature 	= validateSignature(trim($ResponseSignatureDecryptedValue));
									if($validateSingnature == 1) {
										$xmlOutputData 		= simplexml_load_string(trim($ResponseSignatureDecryptedValue));
										$json 				= json_encode($xmlOutputData);
										$ResponseXML 		= json_decode($json,true);
										if(isset($arrResponse['log_id']) && !empty($arrResponse['log_id']))
										{
											$this->FeesReturnApiLog->updateAll(['response_xml_payload'=>json_encode($ResponseXML)],['id'=>$arrResponse['log_id']]);
										}
										
										if(isset($ResponseXML['faml']['inqlist']['payment']))
										{
											$outResponse 						= $ResponseXML['faml']['inqlist']['payment'];
											$arrFeesReturn['referenceno'] 		= isset($outResponse['referenceno']) ? $outResponse['referenceno'] : ''; 
											$arrFeesReturn['codstatus_inquiry'] = isset($outResponse['codstatus']) ? $outResponse['codstatus'] : ''; 
											$arrFeesReturn['txtreason'] 		= isset($outResponse['txtreason']) ? $outResponse['txtreason'] : '';

											if(isset($arrFeesReturn['txtreason']) && strtolower($arrFeesReturn['txtreason']) == 'executed') {
												$arrFeesReturn['payment_transfer_completed']	= $this->NOW();
												$arrFeesReturn['status'] 						= 4;
												$ErrorMessage 	= "Payment Tranfer process is completed.";
												$success 		= 1;
											} else {
												$ErrorMessage 	= $arrFeesReturn['txtreason'];
												$success 		= 0;
											}
											$this->FeesReturn->updateAll($arrFeesReturn,['id'=>$feesDetails->id]);
										}
									} else {
										$ErrorMessage 	= "Invalid Signature";
										$success 		= 0;
									}
									
								} else {
									$ErrorMessage 	= "Response is not coming properly";
									$success 		= 0;
								}
							}
						}
					} else {
						$ErrorMessage 	= "Invalid Token";
						$success 		= 0;
					}
				}
			} else {
				$ErrorMessage   = "Request Not Found.";
				$success        = 0;
			}
		}
		echo json_encode(array('message'=>$ErrorMessage,'success'=>$success,'approved_on'=>$approved_on,'approved_by'=>$approved_by));
		exit;
	}
	public function createDataForNeft()
	{
		//require_once(ROOT . DS .'vendor' . DS . 'cryptlib' . DS . 'Crypt' . DS . 'AES.php');
		//require_once(ROOT . DS .'vendor' . DS .'cryptlib' . DS . 'Crypt' . DS . 'RSA.php');
		//echo "1111";
		
		//$rsa = new Crypt_RSA();
		//$aes = new Crypt_AES();
		$fees_return_id 	= isset($this->request->data['fees_return_id']) ? $this->request->data['fees_return_id'] : 1;
		$feesDetails 		= $this->FeesReturn->find('all',array('conditions'=>array('id'=>trim($fees_return_id))))->first();
		if(!empty($feesDetails)) {
			$initialize_vector 	= getRandomString(16);//'SRh2SnMeXq9N6iBt';
			$symmetric_key 		= getRandomString(32);//'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';
			$requestURIId 		= generateGUID();
			$amount 			= 1;
			$remitter_address_1 = 'HDFC Bank Ltd. Retail Assets';
			$remitter_address_2 = 'Chandivali';
			$remitter_address_3 = 'Mumbai - 400072';
			$bene_ifsc_code 	= $feesDetails->ifsc_code;//'CITI0000001';
			$bene_account_no 	= $feesDetails->account_no;//'041131210001';
			$bene_name 			= $feesDetails->spg_applicant;//'SRINIVAS MOTORS';
			$remit_information_1= 'API BTG WBO';
			$bene_email_id 		= $feesDetails->email;//'jayshree.tailor@ahasolar.in';
			$txndesc 			= 'BTG WBO API';
			$FeesReturn_sel   	= $this->FeesReturn->find();
			$FeesReturn_sel->hydrate(false);
			$max_return_data 	= $FeesReturn_sel->select(['max' => $FeesReturn_sel->func()->max('FeesReturn.max_return_number')])->first();
			if(empty($feesDetails->max_return_number)) {
				$this->FeesReturn->updateAll(['max_return_number' => $max_return_data['max']+1],['id' => $fees_return_id]);
			}
			$max_number 		= empty($feesDetails->max_return_number) ? str_pad(($max_return_data['max']+1),5, "0", STR_PAD_LEFT) : str_pad($feesDetails->max_return_number,5, "0", STR_PAD_LEFT);
			
			$batch_num_ext 		= "1".$max_number;
			$payment_ref_no 	= 'NEFT'.$max_number;
			$xmlBasicData 		= '<faxml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="CO_NEF.xsd" Id="'.$requestURIId.'">
			   <header>
			      	<extsysname>COAPI</extsysname>
					<datpost>'.date('Y-m-d').'</datpost>
					<batchnumext>'.$batch_num_ext.'</batchnumext>
					<idtxn>CO_NEF</idtxn>
					<codcurr>INR</codcurr>
					<iduser>'.Configure::read('API_USER').'</iduser>
					<idcust>'.Configure::read('CUSTOMER_ID').'</idcust>
					<groupid>'.Configure::read('GROUP_ID').'</groupid>
					<reqdatetime>'.date('Y-m-d').'T'.date('H:i:s').'</reqdatetime>
			   </header>
			   <summary>
			      <orgsumpmt>'.$amount.'</orgsumpmt>
			      <orgcountpmt>1</orgcountpmt>
			   </summary>
			   <paymentlist>
			      <payment>
			         <stanext>1</stanext>
			         <paymentrefno>'.$payment_ref_no.'</paymentrefno>
			         <CustId>'.Configure::read('CUSTOMER_ID').'</CustId>
			         <Amount>'.$amount.'</Amount>
			         <RemitterName>HDFC Bank Ltd</RemitterName>
			         <RemitterAccount>'.Configure::read('ACCOUNT_NUMBER').'</RemitterAccount>
			         <RemitterAccountType>10</RemitterAccountType>
			         <Remitter_Address_1>'.$remitter_address_1.'</Remitter_Address_1>
			         <Remitter_Address_2>'.$remitter_address_2.'</Remitter_Address_2>
			         <Remitter_Address_3>'.$remitter_address_3.'</Remitter_Address_3>
			         <Remitter_Address_4 />
			         <BeneIFSCCODE>'.$bene_ifsc_code.'</BeneIFSCCODE>
			         <BeneAccountType>11</BeneAccountType>
			         <BeneAccountNumber>'.$bene_account_no.'</BeneAccountNumber>
			         <BeneName>'.$bene_name.'</BeneName>
			         <BeneAddress_1 />
			         <BeneAddress_2 />
			         <BeneAddress_3 />
			         <BeneAddress_4 />
			         <RemitInformation_1>'.$remit_information_1.'</RemitInformation_1>
			         <RemitInformation_2 />
			         <RemitInformation_3 />
			         <RemitInformation_4 />
			         <RemitInformation_5 />
			         <RemitInformation_6 />
			         <ContactDetailsID />
			         <ContactDetailsDETAIL />
			         <codcurr>INR</codcurr>
			         <refstan>2</refstan>
			         <forcedebit>N</forcedebit>
			         <txndesc>'.$txndesc.'</txndesc>
			         <beneid />
			         <emailid>'.$bene_email_id.'</emailid>
			         <advice1 />
			         <advice2 />
			         <advice3 />
			         <advice4 />
			         <advice5 />
			         <advice6 />
			         <advice7 />
			         <advice8 />
			         <advice9 />
			         <advice10 />
			         <addnlfield1 />
			         <addnlfield2 />
			         <addnlfield3 />
			         <addnlfield4 />
			         <addnlfield5 />
			      </payment>
			   </paymentlist>
			</faxml>';
			$SignedPayload 		= generateXMLSignature($xmlBasicData,$this->private_key,$this->certificateValue,$requestURIId);
			$SignedPayload 		= str_replace(array('</faxml>','<Signature '), array('','</faxml><Signature '), $SignedPayload);
			
			$payloadWithSigned 	= $initialize_vector.'<?xml version="1.0" encoding="UTF-8"?><request>'.$SignedPayload.'</request>';
			//echo 'SignedPayload ------->'.$payloadWithSigned.'<br>';

			
			$RequestSignatureEncryptedValue = cryptAES($payloadWithSigned,$symmetric_key);
			$SymmetricKeyEncryptedValue 	= cryptRSA($symmetric_key);



			$fees_return_id = 1;
			$member_id 		= $this->Session->read("Members.id");
			$OAuthTokenV 	='';
			//echo $RequestSignatureEncryptedValue;
			//echo '<br>'.$SymmetricKeyEncryptedValue;
			$getOAuthToken					= $this->getOAuthToken($fees_return_id,$member_id);
			//echo $_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt';
			$arrRequest 					= array();
			if(isset($getOAuthToken['access_token'])) {
				$arrRequest['RequestSignatureEncryptedValue'] 	= $RequestSignatureEncryptedValue;
				$arrRequest['SymmetricKeyEncryptedValue'] 		= $SymmetricKeyEncryptedValue;
				$arrRequest['Scope'] 							= Configure::read('GROUP_ID');
				$arrRequest['TransactionId'] 					= encode($feesDetails->id);
				$arrRequest['OAuthTokenValue'] 					= isset($getOAuthToken['access_token']) ? $getOAuthToken['access_token'] : '';
				$arrResponse 									= $this->setCallPaymentData($arrRequest,$fees_return_id,$member_id,$payloadWithSigned);
				//pr($arrRequest);
				
				if(isset($arrResponse['Status']) && $arrResponse['Status'] == 'SUCCESS')
				{
					if(isset($arrResponse['GWSymmetricKeyEncryptedValue']) && !empty($arrResponse['GWSymmetricKeyEncryptedValue'])) {
						$GWSymmetricKeyDecryptedValue 	= dcryptRSA($arrResponse['GWSymmetricKeyEncryptedValue']);
						//echo 'RSA-->'.$GWSymmetricKeyDecryptedValue.'<br>';
					
						if(isset($arrResponse['ResponseSignatureEncryptedValue']) && !empty($arrResponse['ResponseSignatureEncryptedValue'])) {
							//echo $arrResponse['ResponseSignatureEncryptedValue'].'<br>';
							
							$ResponseSignatureDecryptedValue 	= decryptAES($arrResponse['ResponseSignatureEncryptedValue'],$GWSymmetricKeyDecryptedValue);
							//echo '<br>AES-->'.trim($ResponseSignatureDecryptedValue);
							$validateSingnature 	= validateSignature(trim($ResponseSignatureDecryptedValue));
							if($validateSingnature == 1) {
								$xmlOutputData 		= simplexml_load_string(trim($ResponseSignatureDecryptedValue));
								//print_r($xmlOutputData);
								$json 				= json_encode($xmlOutputData);
								$ResponseXML 		= json_decode($json,true);
								if(isset($arrResponse['log_id']) && !empty($arrResponse['log_id']))
								{
									$this->FeesReturnApiLog->updateAll(['response_xml_payload'=>json_encode($ResponseXML)],['id'=>$arrResponse['log_id']]);
								}
								if(isset($ResponseXML['faxml']['header']))
								{
									$outResponse 					= $ResponseXML['faxml']['header'];
									$arrFeesReturn['txtstatus'] 	= isset($outResponse['txtstatus']) ? $outResponse['txtstatus'] : '';
									$arrFeesReturn['batchnum'] 		= isset($outResponse['batchnum']) ? $outResponse['batchnum'] : ''; 
									$arrFeesReturn['batchnumext'] 	= isset($outResponse['batchnumext']) ? $outResponse['batchnumext'] : ''; 
									$arrFeesReturn['codstatus'] 	= isset($outResponse['codstatus']) ? $outResponse['codstatus'] : ''; 
									$arrFeesReturn['datpost'] 		= isset($outResponse['datpost']) ? $outResponse['datpost'] : ''; 
									$arrFeesReturn['paymentrefno'] 	= isset($ResponseXML['faxml']['paymentlist']['payment']['paymentrefno']) ? $ResponseXML['faxml']['paymentlist']['payment']['paymentrefno'] : ''; 
									if(isset($ResponseXML['faxml']['header']['txtstatus']) && strtolower($ResponseXML['faxml']['header']['txtstatus']) == 'accepted') {

										$arrFeesReturn['payment_init_date']	= $this->NOW();
									}
									$this->FeesReturn->updateAll($arrFeesReturn,['id'=>$feesDetails->id]);
								}
							} else {
								//$msg 		= 	$validateSingnature;
							}
							
							//print_r($xmlOutputData->Signature->SignatureValue);
						//	print_r($xmlOutputData->Signature);
							//print_r($xmlOutputData->SignatureValue);
							exit;
						

						
						}
					}
				}
			} else {
				//print_r($getOAuthToken);
			}
		} else {
			//echo json_encode(value)
		}

		
		
		exit;
		/*$rsa->loadKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtUlK8MdCzJb5ROqmfW6B
		/KnXsAhWaHM8JNV3XmY0yyzZw4QsQKaqGoAvujKSwQeS1Uq+uJGcRXvmoWrMlqWA
		cLeGxswGCCVptS/gu2JP/hQ+r3bo7Xv9Jb4KdVQN7IGJUt9BZ4lb9tWRjgseSTNx
		sicFUpVj68Xw+ZWYZXdhARm3TtkhYmNKuMstVe9rA7dTQdAj9D/MJFZ7r+axC9n0
		uj6M6I2QdS5EoV+Bvoerb669duen6dvgFBRJSp93dO0WpotJT+z9oeCbJEUIxgK/
		Td/mjUWgD0+DbR8KIkZ9OLCB2rFXH0UzkLCEpooWeGW7ZA8nmsU7/eQrPBcx3EdU
		xwIDAQAB'); // public key

		$plaintext = 'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';

		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$ciphertext = $rsa->encrypt($plaintext);
		echo base64_encode($ciphertext);*/
		
	}
	public function getStatusForNeft()
	{
		$initialize_vector 	= getRandomString(16);//'SRh2SnMeXq9N6iBt';
		$symmetric_key 		= getRandomString(32);//'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';
		$requestURIId 		= generateGUID();
		$fees_return_id 	= isset($this->request->data['fees_return_id']) ? $this->request->data['fees_return_id'] : 1;
		$feesDetails 		= $this->FeesReturn->find('all',array('conditions'=>array('id'=>trim($fees_return_id))))->first();
		if(!empty($feesDetails->payment_init_date) && !empty($feesDetails->paymentrefno)) {

			$xmlBasicData 		= '<faml xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:noNamespaceSchemaLocation="CO_NEF.xsd" Id="'.$requestURIId.'">
								<header>
									<extsysname>COAPI</extsysname>
									<datpost>'.date('Y-m-d').'</datpost>
									<batchnumext>'.$feesDetails->batchnumext.'</batchnumext>
									<idtxn>CO_NEF</idtxn>
									<iduser>'.Configure::read('API_USER').'</iduser>
									<idcust>'.Configure::read('CUSTOMER_ID').'</idcust>
									<inqcount>1</inqcount>
									<groupid>'.Configure::read('GROUP_ID').'</groupid>
									<reqdatetime>'.date('Y-m-d').'T'.date('H:i:s').'</reqdatetime>
								</header>
								<inqlist>
									<payment>
										<paymentrefno>'.$feesDetails->paymentrefno.'</paymentrefno>
										<dattxn>'.date('Y-m-d',strtotime($feesDetails->payment_init_date)).'</dattxn>
									</payment>
								</inqlist>
								</faml>';
			$SignedPayload 		= generateXMLSignature($xmlBasicData,$this->private_key,$this->certificateValue,$requestURIId);
			$SignedPayload 		= str_replace(array('</faml>','<Signature '), array('','</faml><Signature '), $SignedPayload);
			
			$payloadWithSigned 	= $initialize_vector.'<?xml version="1.0" encoding="UTF-8"?><request>'.$SignedPayload.'</request>';
			//echo 'SignedPayload ------->'.$payloadWithSigned.'<br>';

			
			$RequestSignatureEncryptedValue = cryptAES($payloadWithSigned,$symmetric_key);
			$SymmetricKeyEncryptedValue 	= cryptRSA($symmetric_key);



			//$fees_return_id = 1;
			$member_id 		= $this->Session->read("Members.id");
			$OAuthTokenV 	='';
			//echo $RequestSignatureEncryptedValue;
			//echo '<br>'.$SymmetricKeyEncryptedValue;
			$getOAuthToken					= $this->getOAuthToken($fees_return_id,$member_id);
			//echo $_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt';
			$arrRequest 					= array();
			if(isset($getOAuthToken['access_token'])) {
				
				$arrRequest['RequestSignatureEncryptedValue'] 	= $RequestSignatureEncryptedValue;
				$arrRequest['SymmetricKeyEncryptedValue'] 		= $SymmetricKeyEncryptedValue;
				$arrRequest['Scope'] 							= Configure::read('GROUP_ID');//'CBXMGRT3';
				$arrRequest['TransactionId'] 					= encode($fees_return_id);
				$arrRequest['OAuthTokenValue'] 					= isset($getOAuthToken['access_token']) ? $getOAuthToken['access_token'] : '';
				$arrResponse 									= $this->setCallInquiryStatusData($arrRequest,$fees_return_id,$member_id,$payloadWithSigned);
				//pr($arrRequest);
				pr($arrResponse);
				
				if(isset($arrResponse['Status']) && $arrResponse['Status'] == 'SUCCESS')
				{
					if(isset($arrResponse['GWSymmetricKeyEncryptedValue']) && !empty($arrResponse['GWSymmetricKeyEncryptedValue'])) {
						$GWSymmetricKeyDecryptedValue 	= dcryptRSA($arrResponse['GWSymmetricKeyEncryptedValue']);
						echo 'RSA-->'.$GWSymmetricKeyDecryptedValue.'<br>';
					
						if(isset($arrResponse['ResponseSignatureEncryptedValue']) && !empty($arrResponse['ResponseSignatureEncryptedValue'])) {
							//echo $arrResponse['ResponseSignatureEncryptedValue'].'<br>';
							
							$ResponseSignatureDecryptedValue 	= decryptAES($arrResponse['ResponseSignatureEncryptedValue'],$GWSymmetricKeyDecryptedValue);
							echo '<br>AES-->'.trim($ResponseSignatureDecryptedValue);
							$validateSingnature 	= validateSignature(trim($ResponseSignatureDecryptedValue));
							if($validateSingnature == 1) {
								$xmlOutputData 		= simplexml_load_string(trim($ResponseSignatureDecryptedValue));
								//print_r($xmlOutputData);
								$json 				= json_encode($xmlOutputData);
								$ResponseXML 		= json_decode($json,true);
								if(isset($arrResponse['log_id']) && !empty($arrResponse['log_id']))
								{
									$this->FeesReturnApiLog->updateAll(['response_xml_payload'=>json_encode($ResponseXML)],['id'=>$arrResponse['log_id']]);
								}
								
								if(isset($ResponseXML['faml']['inqlist']['payment']))
								{
									$outResponse 						= $ResponseXML['faml']['inqlist']['payment'];
									$arrFeesReturn['referenceno'] 		= isset($outResponse['referenceno']) ? $outResponse['referenceno'] : ''; 
									$arrFeesReturn['codstatus_inquiry'] = isset($outResponse['codstatus']) ? $outResponse['codstatus'] : ''; 
									$arrFeesReturn['txtreason'] 		= isset($outResponse['txtreason']) ? $outResponse['txtreason'] : '';

									if(isset($arrFeesReturn['txtreason']) && strtolower($arrFeesReturn['txtreason']) == 'executed') {
										$arrFeesReturn['payment_transfer_completed']	= $this->NOW();
									}
									$this->FeesReturn->updateAll($arrFeesReturn,['id'=>$feesDetails->id]);
								}
								print_r($ResponseXML);
							} else {
								//$msg 		= 	$validateSingnature;
							}
							
							//print_r($xmlOutputData->Signature->SignatureValue);
						//	print_r($xmlOutputData->Signature);
							//print_r($xmlOutputData->SignatureValue);
							exit;
						

						
						}
					}
				}
			} else {
				print_r($getOAuthToken);
			}
		}
		
		
		
		exit;
		/*$rsa->loadKey('MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtUlK8MdCzJb5ROqmfW6B
		/KnXsAhWaHM8JNV3XmY0yyzZw4QsQKaqGoAvujKSwQeS1Uq+uJGcRXvmoWrMlqWA
		cLeGxswGCCVptS/gu2JP/hQ+r3bo7Xv9Jb4KdVQN7IGJUt9BZ4lb9tWRjgseSTNx
		sicFUpVj68Xw+ZWYZXdhARm3TtkhYmNKuMstVe9rA7dTQdAj9D/MJFZ7r+axC9n0
		uj6M6I2QdS5EoV+Bvoerb669duen6dvgFBRJSp93dO0WpotJT+z9oeCbJEUIxgK/
		Td/mjUWgD0+DbR8KIkZ9OLCB2rFXH0UzkLCEpooWeGW7ZA8nmsU7/eQrPBcx3EdU
		xwIDAQAB'); // public key

		$plaintext = 'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';

		$rsa->setEncryptionMode(CRYPT_RSA_ENCRYPTION_PKCS1);
		$ciphertext = $rsa->encrypt($plaintext);
		echo base64_encode($ciphertext);*/
		echo "11111";
		exit;
	}
	private function getOAuthToken($fees_return_id,$member_id)
	{

		$username 	= Configure::read('API_USERNAME');
		$password 	= Configure::read('API_PASSWORD');
		$curl_url 	= Configure::read('TRANSFER_PAYMENT_URL').'/auth/oauth/v2/token?grant_type=client_credentials&scope='.Configure::read('GROUP_ID');//CBXMGRT3';
		$ch 		= curl_init();
		$arrRequest = array();
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/x-www-form-urlencoded','Authorization: Basic '. base64_encode("$username:$password")));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($arrRequest));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		//echo $_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt';
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '1');
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt');
		//curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/key_data/gujarat_gov_in.key');
		//curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/key_data/newCertificate.crt');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/key_data/govapi.key');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		$ch_result = curl_exec($ch);
		//print curl_errno($ch);
		//print curl_error($ch);
		//echo '<pre>';
	//	echo "Result = ".$ch_result;
		$arrResponse 	= json_decode($ch_result,2);
		//print_r($arrResponse);
		$curl_error 	= curl_error($ch);
		curl_close($ch);
		/*echo '<br>Response:';
		print_r($arrResponse);*/
		$EntityFeesReturnApiLog 						= $this->FeesReturnApiLog->newEntity();
		$EntityFeesReturnApiLog->fees_return_id 		= $fees_return_id;
		$EntityFeesReturnApiLog->request_xml_payload 	= '';
		$EntityFeesReturnApiLog->request_data 			= '';
		$EntityFeesReturnApiLog->response_data 			= json_encode($arrResponse);
		$EntityFeesReturnApiLog->response_xml_payload	= '';
		$EntityFeesReturnApiLog->api_url	 			= $curl_url;
		$EntityFeesReturnApiLog->created	 			= $this->NOW();
		$EntityFeesReturnApiLog->created_by	 			= $member_id;
		//$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
		if(empty($arrResponse)) 
		{
			$EntityFeesReturnApiLog->response_data 		= !empty($curl_error) ? $curl_error : $ch_result;
			$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
			return $curl_error;
		}
		$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
		return $arrResponse;
	}
	private function setCallPaymentData($postData,$fees_return_id,$member_id,$payloadWithSigned)
	{

		$username 	= Configure::read('API_USERNAME');
		$password 	= Configure::read('API_PASSWORD');
		$curl_url 	= Configure::read('TRANSFER_PAYMENT_URL').'/API/NEFTPayment_CBX';
		$ch 		= curl_init();
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','apiKey:'.Configure::read('API_USERNAME'),'Authorization: Basic '. base64_encode("$username:$password")));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '1');
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt');
		//curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/key_data/gujarat_gov_in.key');
		//curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/key_data/newCertificate.crt');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/key_data/govapi.key');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		$ch_result = curl_exec($ch);
		//print curl_errno($ch);
		//print curl_error($ch);
		//echo '<pre>';
		//echo "Result = ".$ch_result;
		$arrResponse 	= json_decode($ch_result,2);
		//print_r($arrResponse);
		$curl_error 	= curl_error($ch);
		curl_close($ch);
		$EntityFeesReturnApiLog 						= $this->FeesReturnApiLog->newEntity();
		$EntityFeesReturnApiLog->fees_return_id 		= $fees_return_id;
		$EntityFeesReturnApiLog->request_xml_payload 	= $payloadWithSigned;
		$EntityFeesReturnApiLog->request_data 			= json_encode($postData);
		$EntityFeesReturnApiLog->response_data 			= json_encode($arrResponse);
		$EntityFeesReturnApiLog->response_xml_payload	= '';
		$EntityFeesReturnApiLog->api_url	 			= $curl_url;
		$EntityFeesReturnApiLog->created	 			= $this->NOW();
		$EntityFeesReturnApiLog->created_by	 			= $member_id;
		/*echo '<br>Response:';
		print_r($arrResponse);*/
		if(empty($arrResponse)) 
		{
			$EntityFeesReturnApiLog->response_data 		= !empty($curl_error) ? $curl_error : $ch_result;
			$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
			return $curl_error;
		}
		$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
		$arrResponse['log_id'] 							= $EntityFeesReturnApiLog->id;
		return $arrResponse;
	}
	private function setCallInquiryStatusData($postData,$fees_return_id,$member_id,$payloadWithSigned)
	{

		$username 	= Configure::read('API_USERNAME');
		$password 	= Configure::read('API_PASSWORD');
		$curl_url 	= Configure::read('TRANSFER_PAYMENT_URL').'/API/NEFTInquiry_CBX';
		$ch 		= curl_init();
		
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_URL, $curl_url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','apiKey:'.Configure::read('API_USERNAME'),'Authorization: Basic '. base64_encode("$username:$password")));
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postData));
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '1');
		curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/key_data/ServerCertificate.crt');
		//curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/key_data/gujarat_gov_in.key');
		//curl_setopt($ch, CURLOPT_SSLCERT, $_SERVER['DOCUMENT_ROOT'].'/key_data/newCertificate.crt');
		curl_setopt($ch, CURLOPT_SSLKEY, $_SERVER['DOCUMENT_ROOT'].'/key_data/govapi.key');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);


		$ch_result = curl_exec($ch);
		//print curl_errno($ch);
		//print curl_error($ch);
		//echo '<pre>';
		//echo "Result = ".$ch_result;
		$arrResponse 	= json_decode($ch_result,2);
		//print_r($arrResponse);
		$curl_error 	= curl_error($ch);
		curl_close($ch);
		/*echo '<br>Response:';
		print_r($arrResponse);*/
		$EntityFeesReturnApiLog 						= $this->FeesReturnApiLog->newEntity();
		$EntityFeesReturnApiLog->fees_return_id 		= $fees_return_id;
		$EntityFeesReturnApiLog->request_xml_payload 	= $payloadWithSigned;
		$EntityFeesReturnApiLog->request_data 			= json_encode($postData);
		$EntityFeesReturnApiLog->response_data 			= json_encode($arrResponse);
		$EntityFeesReturnApiLog->response_xml_payload	= '';
		$EntityFeesReturnApiLog->api_url	 			= $curl_url;
		$EntityFeesReturnApiLog->created	 			= $this->NOW();
		$EntityFeesReturnApiLog->created_by	 			= $member_id;
		if(empty($arrResponse)) 
		{
			$EntityFeesReturnApiLog->response_data 		= !empty($curl_error) ? $curl_error : $ch_result;
			$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
			return $curl_error;
		}
		$this->FeesReturnApiLog->save($EntityFeesReturnApiLog);
		$arrResponse['log_id'] 							= $EntityFeesReturnApiLog->id;
		return $arrResponse;
	}
	public function dataDecrypt()
	{
		$decryptText= 'FAjZYN8lD0H/XRGv9Vn25z6kloQzCPp85UGPg5yoglArAjY8Y5PawXgoSGqTS1GzOMGZQeuu9YaaEhfTQdXaILmULDnU2ysEskJwDle0AgC5JgFY8joBrGcqGwlkEDW8IV/JXLr2DCirj3UOD2jhLhMJTCe/bQ4sTsmGqsOxCQt7cEHGdob3zmhzcr1pFpt8xb0h4sDBNheye4xPZ1BC3x0C//MVZozMWmwvZzWJ1WpV32FP7vx5IsNdqXJud4RNxq8MjkY2oXU7/rylXt62YWsHme65XOh8oCuLmEVfKZVhtquwmXZV9Ojp4YTL9Uq0iLpLhg3X+KQ49+X/6uyBlw==';
		$decoded 	= dcryptRSA($decryptText);
		echo $decoded;
		exit;
	}
	public function dataAESDecrypt()
	{

		$payloadWithSigned='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';
		$symmetric_key 						= 'jf1baLl1rbzRFjOlSlpGfBFIcxIhAlZy';
		
		$ResponseSignatureDecryptedValue 	= decryptAES($payloadWithSigned,$symmetric_key);
		echo $ResponseSignatureDecryptedValue;
		exit;
	}
}