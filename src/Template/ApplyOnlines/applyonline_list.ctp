<link href="/js/datepicker/bootstrap-datepicker3.min.css" rel="stylesheet">
<script src="/js/datepicker/bootstrap-datepicker.min.js"></script>
<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<script src="/chosen/chosen.jquery.min.js"></script>
<link href="/chosen/chosen.min.css" rel="stylesheet">
<?php
/** Allow JERAD TO BYPASS THE DISCOM */
$JREDA_APPROVAL_ARRAY = array(  $MStatus->APPLICATION_SUBMITTED,
								$MStatus->FIELD_REPORT_SUBMITTED,
								$MStatus->WORK_STARTS,
								$FEASIBILITY_APPROVAL,
								$FUNDS_ARE_NOT_AVAILABLE,
								$FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE);
$ALLOWED_IPS                = array("203.88.147.186");
$IP_ADDRESS                 = (isset($this->request)?$this->request->clientIp():"");
$ShowFailResponse           = true;//(in_array($IP_ADDRESS,$ALLOWED_IPS)?true:false);
$ALLOWED_APPROVE_GEDAIDS    = ALLOW_ALL_ACCESS;
$AllowChangeDiscom 			= true;
?>
<?php  $this->Html->addCrumb($pageTitle);  ?>
<style>
.pad-5
{
	padding-left: 5px !important;
}
.pad-25
{
	padding-left: 29px !important;
}
.pad-20
{
	padding-left: 24px !important;
}
.action-row .dropdown .btn {
	background: #171717;
	color: white;
	border-radius: 4px !important;
	padding: 10px;
	box-shadow: 2px 2px 2px 1px #888888;
}
.action-row .dropdown .dropdown-menu {
	margin-top: 0px;
	padding: 10px;
}
.action-row .dropdown .dropdown-menu .dropdown-item {
	display: block;
	width: 100%;
	padding: .25rem 1.5rem;
	clear: both;
	font-weight: 400;
	color: #212529;
	text-align: inherit;
	white-space: nowrap;
	background-color: transparent;
	border: 0;
	text-decoration: none;
}
.modal-full-dialog {
  width: 90%;
  height: 95%;
  padding: 0;
}
</style>

<div class="container ApplyOnline-leads">
	<?php echo $this->Form->create("form-main",['id' => 'form-main','method' => 'post','type' => 'post',"url" => "/apply-online-list"]); ?>
		<?php echo $this->Flash->render('cutom_admin'); ?>
		<?php
		if($quota_msg_disp!==true)
		{ /*
		?>
			<div class="message alert alert-danger">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><?php echo $quota_msg_disp;?>
			</div>
		<?php */
		}
		?>
		<div class="alert alert-warning">
			<strong>Notice!</strong>
			<ul>
				<?php /*<li>The Alloted Capacity of Category "A" Installer is over. So, no more application can be processed from Category A Installers.</li>
				<li style="font-size: 1.0em;">The CEI Portal shall be down between 6 PM, 18 January 2019 to 6 AM, 21 January 2019 for maintenance activity. So, no update will be received from the CEI server on Drawing Approval and Inspection. </li>
				Click on Apply Online to proceed further. Select category and social consumer checkbox in order to post application.
				*/?>
				<?php /*<li style="font-size: 1.0em;">Downtime schedule for maintenance activity at GUVNL  Data Center from 6th Sep 2019, 6:00PM  to  7th Sep 2019, 7:00AM.  Due to this API related service will not be available for the time.</li>
				<li style="font-size: 1.0em;">The Rooftop Solar Policy of Gujarat has expired and all actions on GEDA portal are stopped till further notification on Policy is declared by the Government of Gujarat. For further details, kindly contact GEDA office at Gandhinagar.</li>*/?>
				<li style="font-size: 1.0em;" class="text-danger">
					The payment of GEDA fees will can be made only once the Undertaking document is uploaded. Download Undertaking Format from here. The undertaking is to uploaded on a Notarized Rs. 300 stamp paper.
				</li>
				<li style="font-size: 1.0em;"><a href="/ssdsp-registration-return/" target="_blank">SSDSP Registration Fee Refund Form</a></li>
				<?php if(isset($is_member) && ($is_member == true)) { ?>
					<li style="font-size: 1.0em;"><a href="/Reduction-in-Capacity.pdf" target="_blank">Guidelines for DisCom to Reduce Capacity</a></li>
					<li style="font-size: 1.0em;"><a href="/Change-Discom-Data.pdf" target="_blank">Guidelines for DisCom to Update Discom Data</a></li>
				<?php } ?>
				
				<li style="font-size: 1.0em;">In MSME Applications, it is mandatory to upload the Board Resolution as per the format provided in the Notice section below.</li>
				<li style="font-size: 1.0em;">For Delete Application Request, it is mandatory to use the format of consent letters as per the below mentioned formats.</li>
				<li style="font-size: 1.0em;"><span style="color:#ff0000;">Formats - </span><a href="/Format-for-Board-Authorization-Letter.docx" target="_blank">Board Authorization Letter</a>, <a href="/Format-for-Consent-from-Consumer.docx" target="_blank">Consent from Consumer</a>, <a href="/Format-for-Consent-from-Installer.docx" target="_blank">Consent from Installer</a>.</li>
				<li style="font-size: 1.0em;"><span style="color:#ff0000;">Download Guidelines Document for </span><a href="/meter_installation_procedure.pdf" target="_blank">Meter Installation Procedure</a>, <a href="/inverter_phase_change.pdf" target="_blank">Correction in Inverter Phase</a>, <a href="/Update_DisCom_Data.pdf" target="_blank">Update DisCom Data</a>, <a href="/Reduction_Capacity.pdf" target="_blank">Reduction in Capacity</a>, <a href="/installer_manual.pdf" target="_blank">Installer Manual</a>, <a href="/non-residential.pdf" target="_blank">Non- Residential Solar PV system</a> and <a href="/Delete-application.pdf" target="_blank">Delete Application</a>.</li>

				<li style="font-size: 1.0em;">New Feature is activated for submission of applications for PV Enhancement.</li>
				
				<?php /*
				<li style="font-size: 1.0em;">The Portal is live for online applications of Commercial/Industrial/Social Sector from 2nd May 2019 under non-subsidy scheme.</li>



				<li style="font-size: 1.0em;">The receipt of the Application Fee made to GEDA shall be generated in the name of the Consumer who is the ultimate owner of the solar PV power system.</li> */?>
				<?php /*<li style="font-size: 1.0em;">The Unified portal is live for social / institutional sector applications.</li>
				<li style="font-size: 1.0em;">You can upload only pdf documents and the maximum size of upload shall be <strong>1MB</strong>. </li>*/?>
				<?php /*<li style="font-size: 1.0em;">The applications of rooftop solar PV systems between 1 kW to 1.3 kW is accepted only under the subsidy scheme and apart from these, all the applications for rooftop solar PV system shall be under non-subsidy scheme only. Further, the Subsidy Scheme for capacities above 1.3 kW is not allowed.</li>
				<li style="font-size: 1.0em;">All the applications submitted on the Unified Single Window Rooftop PV Portal between 1400 hours 26 December 2018 to 1400 hours on 28 December 2018 are reset to Application Form stage and it needs to be submitted only under the Non-Subsidy Scheme (except capacities between 1 kW and 1.3 kW).  Inconvenience caused is deeply regretted.</li>*/?>
				
				<?php
				if (BLOCK_APPLICATION == 1)
				{
				?>
					<li style="font-size: 1.0em;"><?php echo BLOCK_APPLICATION_MESSAGE;?></li>
				<?php
				}
				?>
				<?php
				if(date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-3 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-2 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3.'-1 day')) || date('Y-m-d')==date('Y-m-d',strtotime(DATE_STOP_1_1_3)))
				{
					?>
					<li style="font-size: 1.0em;">You can submit application till <strong><?php echo date('d-M-Y H:i:s',strtotime(DATE_STOP_1_1_3));?> PM</strong>.</li>
					<?php
				}
				?>

			</ul>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('status', $application_dropdown_status,array('label' => false,'class'=>'form-control','empty'=>'-Select status-','placeholder'=>'From Date')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('from_date', array('label' => false,'class'=>'form-control date-picker ','placeholder'=>'From Date','autocomplete'=>'off')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('to_date', array('label' => false,'class'=>'form-control date-picker','placeholder'=>'To Date','autocomplete'=>'off')); ?>
				</div>
				<?php if($member_type != $MemberTypeDiscom){ ?>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('discom_name', $discom_arr,array('label' => false,'class'=>'form-control','empty'=>'-Select Discom-','placeholder'=>'From Date')); ?>
				</div>
				<?php }?>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3">
					<?php echo $this->Form->input('consumer_no', array('label' => false,'class'=>'form-control','placeholder'=>'Consumer Number','autocomplete'=>'off')); ?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('application_search_no', array('label' => false,'class'=>'form-control','placeholder'=>'Application Number','autocomplete'=>'off')); ?>
				</div>
				<?php
				if(!empty($member_id))
				{
					?>
					<div class="col-md-3">
						<?php //echo $this->Form->input('installer_name', array('label' => false,'class'=>'form-control','placeholder'=>'Installer Name','autocomplete'=>'off'));
						echo $this->Form->select('installer_name',$Installers,array('label' => false,'class'=>'form-control chosen-select','id'=>'installer_name','data-placeholder'=>'-Select Installers-',"multiple"=>"multiple"));?>
					</div>
					<?php
				}
				?>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('payment_status', array('0'=>'Not Paid','1'=>'Paid'),array('label' => false,'class'=>'form-control','empty'=>'-Select Payment Status-','placeholder'=>'')); ?>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('order_by_form', array('ApplyOnlines.modified|DESC'=>'Modified Date Descending','ApplyOnlines.modified|ASC'=>'Modified Date Ascending','submitted_date|DESC'=>'Submitted Date Descending','submitted_date|ASC'=>'Submitted Date Ascending'),array('label' => false,'class'=>'form-control','placeholder'=>'')); ?>
				</div>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('disclaimer_subsidy', array('0'=>'Subsidy','1'=>'Non Subsidy'),array('label' => false,'class'=>'form-control','empty'=>'-Select Subsidy-','placeholder'=>'')); ?>
				</div>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('pcr_code', array('0'=>'PCR Not Generated','1'=>'PCR Generated','2'=>'PCR Not Submitted','3'=>'PCR Submitted'),array('label' => false,'class'=>'form-control','empty'=>'-Select PCR-','placeholder'=>'')); ?>
				</div>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('msme', array('1'=>'Yes','0'=>'No'),array('label' => false,'class'=>'form-control','empty'=>'-Select MSME Above 50%-','placeholder'=>'')); ?>
				</div>

			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('category',$customer_type_list, array('label' => false,'class'=>'form-control','empty'=>'-Select Category-','multiple'=>'multiple'));
					?>
				</div>
				<div class="col-md-3">
					<?php echo $this->Form->input('receipt_no', array('label' => false,'class'=>'form-control','placeholder'=>'Payment Receipt No.','autocomplete'=>'off')); ?>
				</div>
				<?php
				if($is_member==true)
				{
					?>
					<div class="col-md-3 form-group text">
						<?php echo $this->Form->select('is_enhancement', array('0'=>'New Applications','1'=>'PV Capacity Enhancement'),array('label' => false,'class'=>'form-control','empty'=>'-Select Applications-','placeholder'=>'')); ?>
					</div>
					<?php 
				}
				?>
				<?php
				if((in_array($member_id,$ALLOWED_APPROVE_GEDAIDS)))
				{
					?>
					<div class="col-md-3 form-group text">
						<?php echo $this->Form->select('inspection_status', array('0'=>'Inspection Not Done','1'=>'Inspection Done'),array('label' => false,'class'=>'form-control','empty'=>'-Select Inspection Status-','placeholder'=>'')); ?>
					</div>
					<div class="col-md-3 form-group text">
						<?php echo $this->Form->select('geda_letter_status', array('2'=>'GEDA Letter Issued','1'=>'GEDA Letter Not Issued'),array('label' => false,'class'=>'form-control','empty'=>'-Select GEDA Letter Status-','placeholder'=>'')); ?>
					</div>
					<div class="col-md-3 form-group text">
						<?php echo $this->Form->select('geda_approved_status', array('2'=>'Approved by GEDA - Payment Pending','3'=>'Approved by GEDA - Payment Success','1'=>'Not Approved by GEDA','4'=>'Rejected'),array('label' => false,'class'=>'form-control','empty'=>'-Select GEDA Approval Status-','placeholder'=>'')); ?>
					</div>
					<?php
				}
				?>
				<div class="col-md-3 form-group text">
					<?php echo $this->Form->select('msmeonly', array('1'=>'Yes','0'=>'No'),array('label' => false,'class'=>'form-control','empty'=>'-Select MSME-','placeholder'=>'')); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->Form->input('Search', array('label' => false,'type'=>'submit','name'=>'Search','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Search','div'=>false)); ?>
				</div>
				<div class="col-md-1">
					<?php echo $this->Form->input('Reset', array('label' => false,'type'=>'submit','name'=>'Reset','class'=>'next btn btn-primary btn-lg mb-xlg','value'=>'Reset','div'=>false)); ?>
				</div>
			</div>
		</div>
	<?php echo $this->Form->end();?>
	<div class="row">
		<?php if (!empty($ApplyOnlineLeads)) {

				?>

		<div class="col-md-6">
			<h5 style="margin: 10px;"><?=$this->Paginator->counter(['format' => 'Total Application found: {{count}} (']) ?><?php echo _FormatGroupNumberV2($TotalPvCapacity).' <span style="text-transform:none !important;">kWp</span>)';?></h5>
		</div>
		<div class="col-md-6">
			<div class="text-right">
				<ul class="pagination text-right" style="margin: 0px;">
				<?php
				$this->Paginator->options(array('url'=> array(  'controller' => 'ApplyOnlines',
																'action' => 'applyonline_list')));
				echo $this->Paginator->numbers(['before' => $this->Paginator->prev('Prev'),
												'after' => $this->Paginator->next('Next')]); ?>

				</ul>
			</div>
		</div>
		<?php } ?>
		<div class="col-md-12">
			<?php foreach($ApplyOnlineLeads as $ApplyOnlineLead): ?>
			<div class="row p-row">
				<div class="p-title">
					<div class="col-md-2">
						<a href="<?php echo URL_HTTP; ?>view-applyonline/<?php echo encode($ApplyOnlineLead->id); ?>" class="name_text_size">
							<?php echo trim((!empty($ApplyOnlineLead->customer_name_prefixed) ? $ApplyOnlineLead->customer_name_prefixed:'').' '.(!empty(trim($ApplyOnlineLead->name_of_consumer_applicant)) ? $ApplyOnlineLead->name_of_consumer_applicant : $ApplyOnlineLead->application_no)); ?>
						</a>
						<?php
						$str_append     = '';
						$approval = $MStatus->Approvalstage($ApplyOnlineLead->id);
						$undertakingDone 	= false;
						if(!empty($ApplyOnlineLead->apply_onlines_others['upload_undertaking'])) 
						{
							$undertakingDone 	= ($Couchdb->documentExist($ApplyOnlineLead->id,$ApplyOnlineLead->apply_onlines_others['upload_undertaking'])) ? 1 : 0;
						}
						$arrConsumerNoUndertaking 	= array('76204100742','511102','100414806','18120','27109101649','100487810','27505103768','30015102548','27324100333','100744630','100282054','100485678','100003844','74902020440','30004103181','27201133071','26623043071','25745102276','27204101443','27213014641','27209007296','100707482','149260','90565','2313853','100651702','27517002929','71919004726','25804104381','27319002027','26401001178','72077000058','27135001010','27205103428','25843102742','235373','100119012','235370','83104064210','83104029768','83104067236','83104072272','83104022500','80104061661','83104067317','83152042230','83331012653','83332087894','83371000036','84151080503','83701056307','83504020300','83451060566','83451060558','83851013840','85441013079','85337006010','85451116890','85102015290','85101000388','85222018563','85376009840','83962011757','88569005547','88575013726','88504017036','80041009762','88540011581','80015017419','61363052853','84126007334','83365013881','85468007320','85360005050','85301176935','83104069620','85472010470','84601015650','85415010874','85484013798','85468003270','85479022113','01106001877','01106001842','01106033809','01106033973','01106001346','04507052190','00906002303','04401059255','13851081218','04701067636','4072059','044180026402','04435000040','51403000662','4411003813','0443500040','04416002424','17215000761','04739004833','04707002021','50206003730','514070022011','50624015351','19612001090','01703044428','19613002642','09463001344','09416009962','50626006503','04711004025','50627000223','24501340010','77422340029','77422011572','77422011580','77422011599','77422340363','24501178124','23728105899','23601022907','29201051921','75802001429','29381002525','29346003510','24016200385','24433112380','71062101987','70445100702','70801102740','71458104346','75717201800','70451101260','75767201234','74373108560','70104038799','72463100699','72444102436','71804101850','73337101100','76908102823','75708700362','71105151441','71703101103','72236101058','70873103785','74307106067','70801015782','71501131516','72420100786','71452004080','73317100141','72528102933','70877100101','71062100034','70211008001','70219006032','71531002579','72419100549','73932102096','75416004266','72336006782','75602010793','75618002531','42425009493','40118007912','08814002142','03663003337','40726008249','84402171504','30504094629','30531007766','30302016988','36701003753','30403132398','37401090848','36703615977','37721016177','89274101813','37630063008','37662005674','36418101057','36419041643','60247153842','37802006929','37580610515','37532611566','89250080069','37047001955','37658064270','36402005959','37721017343','3774910828','37371611594','86929032131','86914010927','36206009491','61716024145','36202032650','36209034799','36342017220','36812012246','86932018551','61767100590','61738015289','86928012056','61761011448','3695249742','36903093389','36905251895','36905251887','36905242209','36903085505','61749045583','61749044412','36301062833','86921011895','86929028215','37913000082','03401039270','11465138200','11801018090','11712010000','02751004369','11701136104','50427113342','11701137275','50427116201','50454104820','11418006777','11442011254','11747102695','11711105651','11434017478','11465737378','11717005600','11418004758','11401015093','19077002278','09906122692','09983000792','19051021984','19832002470','11544029667','11544029675','11544015720','41401110487','41401110509','10701021799','10701028122','10701013621','10702005479','107010028157','1072703616','10727308246','10764006959','32822011478','32821011571','32806010977','89845007376','82626003891','82626003883','82426014806','33073010670','82401050035','85733010398','85701026019','82601212128','82603004565','33006010248','88916013712','88935006300','32001005164','85705001541','32002053510','26810107193','26810107584','26810107576','26810107592','26810107606','26810107614','26810107622','26810107630','26810107649','26810107657','26810107673','26810107665','952190','961476','100602277','100508458','20201512432','26801000373','74804010084','20438031776','954031','81405106379','89378008240','80103000178','32515009255','32515009271','85501001071','85501001241','85501001233','85501000083','80301026220','35501040689','88726036223','36001038457','36001004510','39451014560','35901003420','80501550810','80701093781','80601000030','36101000834','36002013970','36019012506','36118007055','36103004403','88727017389','35801008462','84827007845','35201001041','35231013625','35505012795','80231016646','80216008484','84254000227','84836019133','80618010530','80602014930','80717026086','80737020881','80728006286','35966014353','80901018643','80958010340','36017004476','35963005302','80416029329','80813015162','84221006110','35205007214','80311023371','80325003157','81433004534','39462005990','39407006140','39402006613','30806247487','80601027477','80301028605','34430','00208000259','00207084920','00208056017','00207080992','00201144042','00208056580','00208056599','00204079454','00208056793','00208056866','00208056807','00208056815','00208056823','00208056831','00208056840','00208056858','00208056874','00210000082','19705010897','50913002798','01432018094','19702015804','19708016551','19701006712','01433014181','19720006218','50958000530','01813013730','50955002613','19507023780','50906003199','50924002590','01816010499','02078034614','02076001247','02051000719','23751006508','50758000758','50805000755','04717016882','50851004580','19407000400','19411033954','19411031188','19403007257','26520101192','01362017353','19412006870','01325016985','01374004545','19442023748','19449002240','01301124010','77114005571','77130100451','06051007962','06002001964','50865007136','05041000336','04879008524','05080003405','04853008462','04865009426','52044004160','07201001566','04938003970','04933003246','04920000853','01642005908','04902010127','01302004093','26511003191','1404003363','01410000060','50955014590','01359006931','01301000655','19701016726','39108102414','39201087683','38501147150','38501147141','38501147664','38501147680','38501147133','38501156990','38501163228','31615','38409115840','38104081381','51159001952','51930002076','51904001038','51943000301','51934008125','51978000081','9826100714','51302000276','51325002070','51318002079','51336000384','51311003657','51314001191','51305003420','51328000605','51015002892','51333000758','20828004471','21445016486','21470002060','20603023096','20936002522','73628012686','88049004015','40895237520','11132012511','03801057445','03801057443','03801063178','03921006724','45532204845','45585204580','45585204599','11295207214','11241224196','42827301555','42827310023','13901067175','00424302624','42926039751','42812326042','06775005172','08001367169','00802386431','05433008398','08116007521','43066004520','683170102770','42155088600','06917002909','06803003809','06632005683','7501131422','09801313730','01008023973','10214109380','22204102369','73703203285','73102100708','73702035753','22201101736','73155101881','73132100951','73104101256','73123100472','73701004587','73702038892','73701201013','21801119597','73101106800','28857102556','28864101128','28534002339','73706023091','28802117837','35324009415','35331011128','35315003906','35329007453','35601003335','35612001630','35401049269','33725000867','34901087894','31103017586','34901002554','33703105275','81802041770','23103023308','29822005040','24117104446','76309103261','76301103122','76344006773','76352100899','29448114389','29462100586','29718006494','29718013237','29718105824','24233100222','29823102465','24236102246','74418108068','74417101639','74461005615','74455103678','74451006791','74458004220','74454001901','74446102141','74432112034','74426116945','74419101989','74415103464','24310101224','24310101305','29450100071','23238011430','23232100350','23224004363','23314008007','23318102873','23304143033','23211014195','23211107592','23208007973','23336100879','76511003353','76511100561','24351102586','76517103457','24387101830','23301116329','29611101520','29611106130','29629102790','29669007526','29658104177','29608106109','29622103766','29634118240','29617110210','29622009255','29638011009','29611004940','29663003243','29638010266','29639000213','29668004167','29668009193','29625005978','29623007086','29616005367','74439004369','29620008901','74436002500','74401101189','29649008233','29915100957','29924005333','29631163083','23103202148','74730104161','23821100460','74732103294','23801123251','74462010256','29901107237','23801060438','23801020010','23323102363','29712006581','23401215310','23403106330','23503101462','23504007079','23415100863','23428100085','23515100490','23514006121','23506102320','23508101053','23512012272','23409100687','23507100959','23524102379','23815004829','29220004780','29220004836','23505102474','18752025179','12176','06504028702','07101017630','03506085581','03506003933','03506003925','17602094662','12501043740','08201011694','43431099696','08612007824','18731003893','18126132426','07101004822','08204002068','06508007700','45001039053','15901004400','12902000740','18701002970','25103070731','27601028283','74643006676','25313005472','74660004821','74640100566','25359008563','25259007867','27647003879','72820002846','25067005599','25006002751','74219004076','73564004289','27608003561','27604002374','27750003541','27710004156','27749004203','25275009925','73560009766','25731000964','27610000132','74242008465','27701097244','27736005230','27704002155','25275004419','08372011010','10903008068','10903010755','10903010763','10903007452','10903007444','10903007436','13115003340','14739104695','08436104277','08420109878','08413205212','45485004881','14769210108','10951028553','13107004804','45452019804','40471004413','00103107730','08744002351','08743003028','10912001640','15406012940','15404026421','15413004443','15401500351','15413004435','07310500113','15529062032','14501512768','14508086330','14513152427','14508086101','14508086144','14508086136','14508086152','14508086128','14508086187','14508086160','14508086195','14508086179','14508086098','14509093802','14509101384','14509093810','09215021159','09115033082','09109024530','02886020770','0212447157','16701306454','04064000358','02801108057','02648002189','17844419822','45627406186','45601600813','16926010870','44739003015','11053003935','13502027595','42074409400','10622505009','06116202802','34901064223','89552006449','18019400494','14935003740','12804165892','34901171895','89552007887','81845002660','81818006006','89505008465','33540006451','10701028157','3801057453','44039007824','42155088600','88033014504','30748035630','30748100482','30748100474','81802041770','31405129360','33335008480','10727001616','14964015874','17414111792','17921484905','500999847','81802009973','51407002201','1116001314','8403109938','45673505000','87508028520','87508028473','16641067459','35723006274','35432007983','80801051754','25001092310','50857008331','50866008616','4717015150','50819003646','New building','25519003777','81745007180','28731001122','3340077','32601527846','1517012406','2601068078','1526004143','1518009549','1566007208','51904001033','50854012516','51015002392','13824018659','6013007225','6061009755','6010009235','83104061661','61269013548','35105038533','35328008430','60642019410','84601015650','77301006039 ','35611009603','35423004853','87508028511','87508028490','206011679','37755072657','32601090146','82301033376','82201079668','82142003834','32901129005','8000552','1011786','1008590','100017651','29453','100710976','23234004420','11132017963','82301006581','30403049474','06771001768','12501048814','8202010250','41604037580','32601035455','30403120659','8601042856','41604067519','36301068076','60101206399','82501089596','88301097981','82201041229','80104052430','34105109448','35601113793','35401051972','60201000784','36501515475','30403003369','11132016444','11240215053','13901083758','14038004244','18701007731','5905006121','500532374','7101020232','10951028626','13502226067','36905235580','61747926574','32002206465','25401323891','80301075816','85901012348','85919017678','37201097415','3802126670','10702009911','8001367118','39701036026','39501106012','33442027861','31405132590','17602046900','15009075482','8761032549','10601020804','10601020804','41604086378','5724144638','42001004168','71105287599','23801106888','27447122181','25603058943','71332131581','70401021637','73009048939','70301109990','29901112303','29631183777','23336105390','72301113892','73353115536','73009047584','70920104754','22776107706','61801182310','87001030384','87001028592','61801182833','38701060570','61268039918','39126000563','61801184623','86151105583','38201016556','38601010695','86551112382','38501097004','01008020044','25001022061','75045032771','24701102334','25104048160','08911027545','24701069205','01703025938','03201059439','08801002173',',00337026998','11701050579','02751039030','12028104368','07801128230','19826018031','10401125181',',07908018246','05001042208',',00203060237','09801305258','09801305266','10501112693','19148015628','03304077260','16325013069','16325013077','29220004224','21801119597','83104049394','19310018178','17323043182','00335003028','00335000525','40701015799','08911025380','03701039054','03401067532','09903008317','10309003431',',04801007074',',0020369242',',01902036956','51301116025','01001009142','52132021210','07408504303','10131132989','04101109397',',09101027859','15552069838','15552069838',',02803020394',',09309034572','03303029547','24903117421','25104024180','27701166351','29401130272','85451119643','85301225995','83104036063','83301001688','84051056767','83701056617','84151078983','03251017306','02515025099','41604054646','708149308','708130577','707185564','44640045026','707185599','51211032159','5709029610','27271','09101006460','100770651','100713975','21701159252','28309015607','100092246','100356500','100770651','72982','100258276','25001092310','71002005639','71002106273','71002101778','8000587','29451005366','29631149021','722540009052','74303000167','83707016323','70954000838','70954008847','71105291405','72301106055','74303105759','29631108155','22139101871','71747003646','11828010243','11816006661','71102106445','72101113562','23725101760','23801102521','30303210362','661438','29631148360','29020','29493','71062101995','70954011368','18731003885','03801057445','15701757781','26101180735','08761033200','01605015784','02216000167','2104024439','77514051295','72254009052','87001006963','70301109850','203069242','7908018246','74701069205','18701007734','8401043492','11179102339');
						if(in_array($ApplyOnlineLead->consumer_no,$arrConsumerNoUndertaking))
						{
							$undertakingDone = true;
						}
						if($ApplyOnlineLead->query_sent=='1' && !in_array($MStatus->APPLICATION_CANCELLED,$approval)){?>
							<span class="application-status">
							<small style="font-size:12px">
								<br />
								(<?php
									if($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA && ($ApplyOnlineLead->category!=$ApplyOnlines->category_residental || ($ApplyOnlineLead->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1)) && $ApplyOnlineLead->payment_status==0)
									{
										$str_append = ' - Payment Pending';
									}
									if(($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA || $ApplyOnlineLead->application_status == $MStatus->APPLICATION_SUBMITTED) && !$undertakingDone) {
										$str_append = ' - Pending Undertaking';
									}
									echo"Query Sent".$str_append;?>)
							</small>
						</span>
						<?php } else { ?>
						<span class="application-status">
							<small style="font-size:12px">
							<br />(<?php if(isset($application_status[$ApplyOnlineLead->application_status])) {
								$status_app_disp    = $application_status[$ApplyOnlineLead->application_status];
									$status_app_disp= str_replace(array('JREDA'), array('GEDA'), $status_app_disp);

									if($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA && ($ApplyOnlineLead->category!=$ApplyOnlines->category_residental || ($ApplyOnlineLead->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1) || ($ApplyOnlineLead->govt_agency==1 && GOVERMENT_AGENCY==1)) && $ApplyOnlineLead->payment_status==0)
									{
										$str_append = ' - Payment Pending';
									}
									if(($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA || $ApplyOnlineLead->application_status == $MStatus->APPLICATION_SUBMITTED) && !$undertakingDone) {
										$str_append = ' - Pending Undertaking';
									}
								echo $status_app_disp.$str_append;  } else { echo '-'; }  ?>)
							</small>
						</span>
						<?php }?>
						<?php
							$additional_capacity = (isset($ApplyOnlineLead->apply_onlines_others['is_enhancement']) && $ApplyOnlineLead->apply_onlines_others['is_enhancement']) ? 'Additional ' : '';
						?>
					</div>
					<div class="col-md-7">
						<span class="action-row action-btn">
							<?php
								$active                 = 1;
								$EnableFA               = false;
								$ApproveFA              = false;
								$ForwardToOther         = false;
								$Subsidy_Availability   = false;
								$EditApplication        = false;
								$PayApplication         = false;
								$RegistrationLink       = false;
								$MeterInstallation      = false;
								$WorkCompletionReport   = false;
								$InspectionFromCEI      = false;
								$DrawingFormOPEN        = false;
								$InspectionFormOPEN     = false;
								$InspectionFinalCEI     = false;
								$InspectionFromDisCom   = false;
								$InspectionFromJREDA    = false;
								$ReleaseSubsidy         = false;
								$ApproveDV              = false;
								$VarifyOtp              = false;
								$ShowGedaApproval_Letter= false;
								$ClaimSubsidy           = false;
								$WorkOrderDisplay       = false;
								$ExecutionDisplay       = false;
								$PaymentApproval        = false;
								$Self_Certificate       = false;
								$Download_Receipt       = false;
								$Download_Agreement     = false;
								$DownloadMeterInsallation= false;
								$DownloadSummarySheet   = false;
								$updateRequest          = false;
								$updateCapacity         = false;
								$downloadInspection     = false;
								$RemoveCommonMeter      = false;
								$DisplyEdit             = $MStatus->can_workstart($ApplyOnlineLead->application_status);
								//$ShowMeterInsallation   = $MStatus->ApprovedCEIStatus($ApplyOnlineLead->id);
								$ShowMeterInsallation   = $MStatus->ApprovedCEIInspectionStatus($ApplyOnlineLead->id);
								$DispDownloadFesibility = $MStatus->ApprovedfesibilityStatus($ApplyOnlineLead->id);
								$InspectionPDF          = $Inspectionpdf->IsInspectionDone($ApplyOnlineLead->id);
								$gedaLetterDate 		= $MStatus->getgedaletterStageDataMIS($ApplyOnlineLead->id);
								if(!empty($InspectionPDF) && (in_array($member_id,$ALLOWED_APPROVE_GEDAIDS)))
								{
									$downloadInspection = true;
								}
								$LastFailResponse 		= '';
								//$DownloadMeterInsallation= $MStatus->ApprovedCEIStatus($ApplyOnlineLead->id);
								//if (in_array($IP_ADDRESS,$ALLOWED_IPS)) {
								if(($ApplyOnlines->torent_ahmedabad == $ApplyOnlineLead->discom || $ApplyOnlines->torent_surat == $ApplyOnlineLead->discom))
								{
									if(!in_array($MStatus->SUBSIDY_AVAILIBILITY,$approval))
									{
										$LastFailResponse       = $ApiLogResponse->GetLatestGUVNLResponse($ApplyOnlineLead->id);
									}

								}
								elseif(!in_array($MStatus->METER_INSTALLATION,$approval))
								{
									$LastFailResponse       = $ApiLogResponse->GetLatestGUVNLResponse($ApplyOnlineLead->id);
								}
							   // }
								$LastFailSpinResponse   = $SpinLogResponse->GetLatestSPINResponse($ApplyOnlineLead->id);
								if(in_array($MStatus->WORK_EXECUTED,$approval))
								{
									$DownloadMeterInsallation = true;
								}
								//$DispCeiNumber           = $MStatus->ApprovedMeterInstallation($ApplyOnlineLead->id);
								$DispCeiNumber   = $MStatus->ApprovedCEIStatus($ApplyOnlineLead->id);
								$GetLastMessage          = $ApplyonlineMessage->GetLastMessageByApplication($ApplyOnlineLead->id,'0',$member_id);
								$applyOnlinesDataDoc= $applyOnlinesDataDocList->find("all",['conditions'=>['application_id'=>$ApplyOnlineLead->id,'doc_type'=>'Self_Certificate']])->first();
								$disp_query              = '';
								if(!empty($GetLastMessage))
								{
									$disp_query          = $GetLastMessage['message'];
								}
								$FesibilityData          = $FesibilityReport->getReportData($ApplyOnlineLead->id);
								$ChangeDiscom = true;
								if ($ApplyOnlineLead->section > 0) {
									$ChangeDiscom = false;
								}
								if(in_array($MStatus->APPROVED_FROM_GEDA,$approval) && $is_member==false)
								{
									$updateRequest  = true;
								}
								if(in_array($MStatus->APPROVED_FROM_GEDA,$approval) && $is_member==false)
								{
									$updateCapacity  = true;
								}

								if(!in_array($MStatus->APPLICATION_CANCELLED,$approval))
								{
									$ChangeDiscom = false;
									//&& $member_type == $MemberTypeDiscom
									if(isset($member_type)  && (in_array($ApplyOnlineLead->application_status,$MStatus->SHOWFESIBILITYLINK))) {
										$EnableFA           = true;
										if ($ApplyOnlineLead->subdivision <= 0) {
											$ForwardToOther  = true;
										}
									}
									if(in_array($MStatus->APPROVED_FROM_GEDA,$approval) && $str_append=='' && !$PayApplication) {
										$ShowGedaApproval_Letter           = true;
									}
									//$ApplyOnlineLead->application_status == $MStatus->APPLICATION_SUBMITTED ||
									if(isset($member_type) && $member_type == $MemberTypeDiscom && ($ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA) && (($ApplyOnlineLead->category==$ApplyOnlines->category_residental && ($ApplyOnlineLead->social_consumer==0 || SOCIAL_SECTOR_PAYMENT==0)) || (($ApplyOnlineLead->category!=$ApplyOnlines->category_residental || ($ApplyOnlineLead->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1)) && $ApplyOnlineLead->payment_status==1)))
									{
										$ApproveDV = true;
									}
									if ($ApplyOnlineLead->application_status == $MStatus->FIELD_REPORT_SUBMITTED)
									{
										$pv = $ApplyOnlineLead->pv_capacity;
										if (!empty($FesibilityData)) {
											$pv = $FesibilityData->recommended_capacity_by_discom;
										}
										//By Pass to Show To All Discom Members
										//&& $member_type == $DISCOM
										if(isset($member_type) ) {
											$ApproveFA  = true;
										}
										/*
										if ($ApplyOnlines->ApproveFesibilityMatrixV2($pv,$division,$area,$circle,$subdivision,$section)) {
											$ApproveFA  = true;
										}
										*/
									}
									if(isset($member_type) && $member_type == $JREDA && $ApplyOnlineLead->application_status == $MStatus->FEASIBILITY_APPROVAL) {
										$Subsidy_Availability = false;
									}
									if(isset($is_member) && ($is_member == false)) {
										if($is_member == false && ($ApplyOnlineLead->application_status == 4 && ($ApplyOnlineLead->application_status != 5 || $ApplyOnlineLead->application_status != 6))) {
											$RegistrationLink = false;
										}
										//(!in_array($MStatus->APPROVED_FROM_GEDA,$approval))
										$AllowedEditStatus = array($APPLICATION_GENERATE_OTP,$MStatus->APPLICATION_PENDING);
										if($is_member == false && in_array($ApplyOnlineLead->application_status,$AllowedEditStatus)) {
											$EditApplication = true;

										} else if($is_member == false && empty($ApplyOnlineLead->application_status)) {
											$EditApplication = true;
										}
										if($ApplyOnlineLead->payment_status != '1' && !empty($ApplyOnlineLead->category) && ($ApplyOnlineLead->category != $ApplyOnlines->category_residental || ($ApplyOnlineLead->social_consumer==1 && SOCIAL_SECTOR_PAYMENT==1) || ($ApplyOnlineLead->govt_agency==1 && GOVERMENT_AGENCY==1))  && $ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_GEDA)
										{
											$PayApplication = true;
										}
									}
									if($ApplyOnlineLead->payment_status==1)
									{
										$Download_Receipt   = true;
									}
									if($is_member==false && in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && $ApplyOnlineLead->disclaimer_subsidy==0)
									{
										$Download_Agreement = true;
									}
									if(isset($member_type) && $member_type == $JREDA && !in_array($MStatus->FEASIBILITY_APPROVAL,$approval) || ($member_id == 1405 && !in_array($MStatus->METER_INSTALLATION,$approval)))
									{
										$EditApplication = true;
									}
									else if($is_member == false && $ApplyOnlineLead->application_status == $APPLICATION_GENERATE_OTP)
									{
										$EditApplication = true;
									}
									if($is_member == false && $ApplyOnlineLead->query_sent == "1" && !in_array($MStatus->FEASIBILITY_APPROVAL,$approval) )
									{
										$EditApplication = true;
									}
									if($is_member == false && $ApplyOnlineLead->application_status == $APPLICATION_GENERATE_OTP) {
										$VarifyOtp = true;
									}
									//$is_member == false && $ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_JREDA
									if(in_array($MStatus->METER_INSTALLATION,$approval) && SUBSIDY_CLAIM==1 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlineLead->disclaimer_subsidy==0)
									{
										$ClaimSubsidy = true;
									}
									//$is_member == false &&
									if($ApplyOnlineLead->application_status == $MStatus->CLAIM_SUBSIDY)
									{
										$DownloadSummarySheet = true;
									}
									if(isset($member_type) && ($member_type == $CEI && ($ApplyOnlineLead->application_status == $MStatus->DRAWING_APPLIED))) {
										$InspectionFromCEI      = true;
									}
									if($is_member == false && in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !in_array($MStatus->APPROVED_FROM_CEI,$approval) && isset($FesibilityData->recommended_capacity_by_discom) && $FesibilityData->recommended_capacity_by_discom > '10') {
										$DrawingFormOPEN      = true;
									}
									//$MStatus->APPROVED_FROM_DISCOM
									if($is_member == false && $DispCeiNumber && !in_array($MStatus->CEI_INSPECTION_APPROVED,$approval) && isset($FesibilityData->recommended_capacity_by_discom) && $FesibilityData->recommended_capacity_by_discom > '10') {

										$InspectionFormOPEN      = true;
									}
									if(isset($member_type) && ($member_type == $CEI && ($ApplyOnlineLead->application_status == $MStatus->CEI_APP_NUMBER_APPLIED))) {
										$InspectionFinalCEI      = true;
									}
									if(isset($member_type) && ($member_type == $MemberTypeDiscom && $ApplyOnlineLead->application_status == $MStatus->APPROVED_FROM_CEI)) {
										//$InspectionFromDisCom = true;
									}
									if(isset($member_type) && ($member_type == $JREDA && (in_array($MStatus->APPROVED_FROM_DISCOM,$approval)  || in_array($MStatus->CEI_INSPECTION_APPROVED,$approval)) && !in_array($MStatus->APPROVED_FROM_JREDA, $approval))) {
										$InspectionFromJREDA = true;
									}
									if(isset($is_installer) && ($is_installer == true && $ApplyOnlineLead->application_status == $MStatus->REGISTRATION))
									{
										$WorkCompletionReport = true;
									} //$member_type == $MemberTypeDiscom && 
									if(isset($member_type) && ((
										$ShowMeterInsallation)) && !in_array($MStatus->METER_INSTALLATION,$approval)){
										$MeterInstallation = true;
									}
									if(isset($member_type) && ($member_type == $JREDA && $ApplyOnlineLead->application_status == $MStatus->METER_INSTALLATION)) {
										$ReleaseSubsidy = true;
									}

									if ($ApplyOnlineLead->application_status != $MStatus->APPLICATION_SUBMITTED) {
										$ChangeDiscom = false;
									}
									//isset($is_installer) && $is_installer == true &&
									if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData) && isset($FesibilityData->payment_approve) && $FesibilityData->payment_approve == 1)
									{
										$WorkOrderDisplay = true;
										$ExecutionDisplay = true;
									} //&& $member_type == $MemberTypeDiscom 
									if(isset($member_type) && in_array($MStatus->FIELD_REPORT_SUBMITTED,$approval) && !empty($FesibilityData) && isset($FesibilityData->payment_approve) && $FesibilityData->payment_approve == 0) {
										$PaymentApproval = true;
									}
									if(isset($member_type) && $member_type == $JREDA && $ApplyOnlineLead->common_meter == 1 && $authority_account == 1) {
										$RemoveCommonMeter = true;
									}
									//&&  $FesibilityData->payment_approve == 1
									if(isset($is_installer) && $is_installer == true && in_array($MStatus->SUBSIDY_AVAILIBILITY,$approval) && !empty($FesibilityData)  && isset($FesibilityData->payment_approve)  &&  isset($FesibilityData->recommended_capacity_by_discom) &&
										$FesibilityData->recommended_capacity_by_discom <= '10'
										&& empty($applyOnlinesDataDoc) )
									{
										$Self_Certificate =true;
									}
								}
								$arr_application_status = $MStatus->all_status_application($ApplyOnlineLead->id);
								switch ($ApplyOnlineLead->application_status) {
									case 1: {
										$active     = 1;
										break;
									}
									case 2: {
										$active = 3;
										break;
									}
									case 3:
									case 5:
									case 6:
									case 24:
									{
										$active = 4;
										break;
									}
									case 4:
									case 25:
									{
										$active = 6;
										break;
									}
									case 9:
									{
										$active = 5;
										break;
									}
									case 7:
									{
										$active = 6;
										break;
									}
									case 8:
									case 17:
									case 12:
									case 26:
									{
										$active = 7;
										break;
									}
									case 27:
									case 15:
									{
										$active = 8;
										break;
									}
									case 28:
									{
										$active = 9;
										break;
									}
									case 20:
									case 23:
									case 21:
									{
										$active = 3;
										break;
									}
								}
							?>
							<div class="col-md-4">
								<?php 
							
								$displayActions 	= ($login_type == 'installer' && !empty($ApplyOnlineLead->apply_onlines_others['map_installer_id'])) ? false : (($member_id==1435) ? false : true);
								
								if($displayActions) { ?>
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Application Actions <i class="fa fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
											<?php if($VarifyOtp) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#Varify_Otp" class="Varify_Otp dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Verify OTP
												</a>
												<a href="/ApplyOnlines/resend_otp/<?php echo encode($ApplyOnlineLead->id); ?>" class="Resend_Otp dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Resend OTP
												</a>
											<?php } ?>
											<?php
											$arrApplications = array(116636,117117,114022,116788,117291,117114,117115,117076,117093,117099,117082,117073,112506,117153,116716,117060,116744,115594,117339,117383,117380,117376,117374,114063,117113,115291,117164,116188,116510,116378,116234,116037,116909,117047,116834,116989,116943,115708,116022,116476,116309,116898,116703,117304,117175,116436,116279,114939,117201,116458,117386,117323,116715,117260,117292);

											if(in_array($ApplyOnlineLead->id,$arrApplications) && !$undertakingDone) {
												?>
												<a class="dropdown-item uploadUndertaking" data-toggle="modal" data-target="#uploadUndertaking" href="javascript:;" data-id="<?php echo encode($ApplyOnlineLead->id);?>" data-title="Upload Undertaking - Disclaimer" data-showtext="1">
													<i class="fa fa-check-square-o"></i> Upload Undertaking - Disclaimer
												</a> 
												<?php 
											} ?>
											
											<?php if(!$undertakingDone) { // && $PayApplication // && $ApplyOnlineLead->apply_onlines_others['scheme_id'] == 3?> 
												<a class="dropdown-item uploadUndertaking" data-toggle="modal" data-target="#uploadUndertaking" href="javascript:;" data-id="<?php echo encode($ApplyOnlineLead->id);?>" data-title="Upload Undertaking" data-showtext="0">
													<i class="fa fa-check-square-o"></i> Upload Undertaking
												</a>
											<?php } ?>
											
											<?php if ($ApproveDV) { ?>
												<?php
													$quota_msg = $ApplyOnlines->checked_total_capacity($ApplyOnlineLead->installer_id);
													/*if($quota_msg!==true)
													{
														?>
														<a class="dropdown-item" href="javascript:;" onclick="javascript:alert('<?php echo $quota_msg;?>');">
															<i class="fa fa-check-square-o"></i> Verify Document
														</a>
														<?php
													}
													else
													{*/
														echo $this->Form->postLink(
													'<i class="fa fa-check-square-o"></i> Verify Document',
													['action' => 'document_verify', encode($ApplyOnlineLead->id)],
													['confirm' => 'Are you sure you want to verify document for this application?','escape' => false,'class' => "dropdown-item"]);
													//}
												?>
											<?php } ?>
											<?php if ($EnableFA) { ?>
												<a href="/apply-onlines/fesibility/<?php echo encode($ApplyOnlineLead->id); ?>" class="feasibility_status dropdown-item">
													<i class="fa fa-check-square-o"></i> Feasibility Report
												</a>
											<?php } ?>
											<?php if ($ApproveFA) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#Approve_FA" class="Approve_FA dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Approve Fesibility Report
												</a>
											<?php } ?>
											<?php if ($is_member==true && ((isset($member_type) && $member_type == $MemberTypeDiscom   && $circle == 0) || $authority_account==1 || $member_type==$JREDA) && $AllowChangeDiscom ) { 
												$arrDetails = $ApplyOnlines->getDiscomDetails($ApplyOnlineLead->circle,$ApplyOnlineLead->division,$ApplyOnlineLead->subdivision,$ApplyOnlineLead->area);

												$arrDetails = explode(" / ",$arrDetails);
												?>
												<a href="#" data-toggle="modal" data-target="#Discom_FA" class="Discom_FA dropdown-item" data-area="<?php echo (isset($arrDetails[0])?$arrDetails[0]:'');?>" data-circle="<?php echo (isset($arrDetails[1])?$arrDetails[1]:'');?>" data-division="<?php echo (isset($arrDetails[2])?$arrDetails[2]:'');?>" data-subdivision="<?php echo (isset($arrDetails[3])?$arrDetails[3]:'');?>" data-address="<?php echo $ApplyOnlineLead->address1;?>" data-sanction="<?php echo $ApplyOnlineLead->sanction_load_contract_demand;?>" data-name="<?php echo $ApplyOnlineLead->name_of_consumer_applicant;?>" data-id="<?php echo encode($ApplyOnlineLead->id); ?>" data-existingCapacity="<?php echo $ApplyOnlineLead->apply_onlines_others['existing_capacity'];?>">
													<i class="fa fa-check-square-o"></i> Change Discom Data
												</a>
											<?php } ?>
											<?php if ($ClaimSubsidy) { ?>
											 <a href="/subsidy/<?php echo encode($ApplyOnlineLead->id); ?>" class="Claim_Subsidy dropdown-item" data-id="" target="_blank">
													<i class="fa fa-check-square-o"></i> Claim Subsidy
												</a>
												<?php
												/*
												<a href="javascript:;" data-toggle="modal" data-target="#Claim_Subsidy" class="Claim_Subsidy dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Claim Subsidy
												</a>*/?>
											<?php }
											?>
											<?php if ($WorkOrderDisplay) { ?>
												<a href="javascript:;" class="dropdown-item addDrying showModel" data-title="Work Order" data-url="<?php echo URL_HTTP; ?>project/workorder/<?php echo encode($ApplyOnlineLead->project_id)?>">
													<i class="fa fa-check-square-o"></i> Work Order
												</a>
											<?php } ?>
											<?php if ($ExecutionDisplay) { ?>
												<a href="javascript:;" class="dropdown-item addDrying showModel" data-title="Execution" data-url="<?php echo URL_HTTP; ?>project/execution/<?php echo encode($ApplyOnlineLead->project_id)?>">
													<i class="fa fa-check-square-o"></i> Execution
												</a>
											<?php } ?>
											<?php if ($Subsidy_Availability) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#discom_status" class="discom_status dropdown-item" data-id="<?php echo $ApplyOnlineLead->id; ?>">
													<i class="fa fa-check-square-o"></i> Subsidy Availability
												</a>
											<?php } ?>
											<?php if ($InspectionFromCEI) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#CEI_Status" class="CEI_Status dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Approval From CEI
												</a>
											<?php } ?>
											<?php if ($DrawingFormOPEN) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#DRAWING_Status" class="DRAWING_Status dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i>CEI Drawing Application Ref. No.
												</a>
											<?php } ?>
											<?php if ($InspectionFormOPEN) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#CEI_APP_Status_POPUP" class="CEI_APP_Status_POPUP dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> CEI Inspection Application Ref. No.
												</a>
											<?php } ?>
											<?php if ($InspectionFinalCEI) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#CEI_INS_Status" class="CEI_INS_Status dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> CEI Inspection Application Ref. No.
												</a>
											<?php } ?>
											<?php if ($InspectionFromDisCom) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#DISCOM_Approved_Status" class="DISCOM_Approved_Status dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Inspection and Approval From DisCom
												</a>
											<?php } ?>
											<?php if ($InspectionFromJREDA) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#JREDA_Status" class="JREDA_Status dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Inspection and Approval From GEDA
												</a>
											<?php } ?>
											<?php if ($ForwardToOther) { ?>
												<a href="javascript:;" id="forward_application" data-toggle="modal" data-target="#forword_popup_discom" data-id="<?php echo $ApplyOnlineLead->id; ?>" class="forward_application dropdown-item">
													<i class="fa fa-share-square-o"></i> Forward To other Division
												</a>
											<?php } ?>
											<?php if (($member_type == $MemberTypeDiscom) && $ChangeDiscom && !in_array($MStatus->APPLICATION_CANCELLED,$approval)) { ?>
												<?php
													$IsSubdivision  = ($discom_details['field'] == "subdivision")?true:false;
													$Button_Title    = (!$IsSubdivision)?"Assign Subdivision":"Assign Section";
												?>
												<a href="javascript:;" data-toggle="modal" data-target="#forword_popup_subdivision" data-id="<?php echo $ApplyOnlineLead->id; ?>" data-division="<?php echo $ApplyOnlineLead->division; ?>" data-subdivision="<?php echo $ApplyOnlineLead->subdivision; ?>" data-section="<?php echo $ApplyOnlineLead->section; ?>" class="forword_popup_subdivision dropdown-item">
													<i class="fa fa-check-square-o"></i> <?php echo $Button_Title?>
												</a>
											<?php } ?>
											<?php if ($RegistrationLink) { ?>
												<a href="/apply-onlines/do-registration/<?php echo encode($ApplyOnlineLead->id); ?>" class="registration dropdown-item">
													<i class="fa fa-check-square-o"></i> Registration
												</a>
											<?php } ?>
											<?php if ($MeterInstallation) { ?>
												<a href="/apply-onlines/chargingcertificate/<?php echo encode($ApplyOnlineLead->id); ?>" class="chargingcertificate dropdown-item">
													<i class="fa fa-check-square-o"></i> Meter Installation Report
												</a>
											<?php } ?>
											<?php if ($ReleaseSubsidy) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#ReleaseSubsidy" class="ReleaseSubsidy dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Release Of Subsidy
												</a>
											<?php } ?>
											<?php if ($WorkCompletionReport) { ?>
												<a href="/apply-onlines/workcompletion/<?php echo encode($ApplyOnlineLead->id); ?>" class="workcompletion dropdown-item">
													<i class="fa fa-check-square-o"></i> Work Completion Report
												</a>
											<?php } ?>
											<?php if ($EditApplication) { 
													if(empty($additional_capacity))
													{
														?>
														<a class="dropdown-item" href="/apply-onlines/manage/<?php echo encode($ApplyOnlineLead->id); ?>">
															<i class="fa fa-pencil-square-o"></i> Edit
														</a>
													<?php
													}
													else
													{	?>
														<a class="dropdown-item" href="/add-additional-capacity/manage/<?php echo encode($ApplyOnlineLead->id); ?>">
															<i class="fa fa-pencil-square-o"></i> Edit
														</a>
														<?php 
													}
												}
											//|| (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] == "203.88.138.46")
												
											?>
											<?php if ($PayApplication && ($payment_on) && $undertakingDone) { ?>
												<a class="dropdown-item" href="/payutransfer/make-payment/<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-rupee"></i> Pay Application Fee
												</a>
											<?php } ?>
											<?php if(isset($member_type) && !in_array($MStatus->APPLICATION_CANCELLED,$approval)) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#SendMessage" class="SendMessage dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-envelope" aria-hidden="true"></i> Send Message
												</a>
											<?php } ?>
											<?php if($is_member == false && $ApplyOnlineLead->query_sent=='1' && !in_array($MStatus->APPLICATION_CANCELLED,$approval)) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#ReplayMessage" class="ReplayMessage dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-envelope" aria-hidden="true"></i> Reply Message
												</a>
											<?php } ?>
											<?php if($PaymentApproval) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#approvedpayment" class="approvedpayment dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o" aria-hidden="true"></i> Approved Payment
												</a>
											<?php } ?>
											<?php if($ApplyOnlineLead->application_status==$MStatus->APPLICATION_PENDING) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#uploaddocument" class="uploaddocument dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upload Signed Application
												</a>
											<?php } ?>
											<?php if($RemoveCommonMeter) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#removeCommonMeter" class="removeCommonMeter dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Remove Common Meter
												</a>
											<?php } ?>
											<?php
											if (in_array($MStatus->DOCUMENT_VERIFIED,$approval) && empty($ApplyOnlineLead->pcr_code) && $is_member==true) {
												?>
												<a href="/ApplyOnlines/SendConsumerDetails/<?php echo encode($ApplyOnlineLead->id); ?>" class="Claim_Subsidy dropdown-item" data-id="" target="_blank">
													<i class="fa fa-check-square-o"></i> Send Consumer Details - API
												</a>
												<?php
											}
											if (in_array($MStatus->METER_INSTALLATION,$approval) && empty($ApplyOnlineLead->pcr_code) && $is_member==true) {
												?>
												<a href="javascript:;" data-toggle="modal" onclick="recallmeter('<?php echo encode($ApplyOnlineLead->id); ?>')" class=" dropdown-item">
													<i class="fa fa-check-square-o"></i> Recall Meter - API
												</a>
												<?php
											}
											?>
											<?php
											if(isset($member_type) && $member_type == $JREDA && ($ApplyOnlineLead->category != $ApplyOnlines->category_residental || $ApplyOnlineLead->social_consumer==1)  && ($ApplyOnlineLead->application_status == $MStatus->APPLICATION_SUBMITTED ||
												$ApplyOnlineLead->application_status == $MStatus->REJECTED_FROM_GEDA) && in_array($member_id,$ALLOWED_APPROVE_GEDAIDS))
											{
											?>
												<a href="javascript:;" data-toggle="modal" data-target="#approvegeda" class="approvegeda dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o" aria-hidden="true"></i> Approved GEDA
												</a>
											<?php } ?>
											<?php
											if(isset($member_type) && $member_type == $JREDA && in_array($MStatus->APPLICATION_CANCELLED,$approval))
											{
											?>
												<a href="javascript:;" data-toggle="modal" data-target="#reopenApplication" class="reopenApplication dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o" aria-hidden="true"></i> Reopen Application
												</a>
											<?php } ?>
											<?php
											if(isset($member_type) && $member_type == $JREDA && $ApplyOnlineLead->query_sent!='1' && !in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && (empty($FesibilityData) || (isset($FesibilityData->payment_approve) && $FesibilityData->payment_approve == 0)) && !in_array($MStatus->APPLICATION_CANCELLED,$approval))
											{
											?>
												<a href="javascript:;" data-toggle="modal" data-target="#resetApplication" class="resetApplication dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o" aria-hidden="true"></i> Reset Application
												</a>
											<?php } ?>
											<?php if($Self_Certificate) { ?>
												<a href="javascript:;" data-toggle="modal" data-target="#selfcertificate" class="selfcertificate dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o" aria-hidden="true"></i> Self-Certification
												</a>
											<?php } ?>
											<?php
											if ($updateRequest) { ?>
												<a href="javascript:;" class="dropdown-item addDrying showModel" data-title="Request to Update DisCom Data" data-url="<?php echo URL_HTTP; ?>ApplyOnlines/addUpdateRequest/<?php echo encode($ApplyOnlineLead->id)?>">
													<i class="fa fa-check-square-o"></i> Request to Update DisCom Data
												</a>
												<?php
											}
											if($updateCapacity) { ?>
												<a href="javascript:;" class="dropdown-item addDrying showModel" data-title="Request to Reduce the Registered Capacity" data-url="<?php echo URL_HTTP; ?>ApplyOnlines/addReductionRequest/<?php echo encode($ApplyOnlineLead->id)?>">
													<i class="fa fa-check-square-o"></i> Request to Reduce the Registered Capacity
												</a>
												<?php
												/*
												<a href="javascript:;" data-toggle="modal" data-target="#Claim_Subsidy" class="Claim_Subsidy dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-check-square-o"></i> Claim Subsidy
												</a>*/?>
											<?php } ?>
											<?php if(isset($is_installer) && $is_installer == true && in_array($MStatus->APPLICATION_SUBMITTED,$approval)){?>
												<a href="javascript:;" data-toggle="modal" data-target="#otherdocument" class="otherdocument dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
													<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Upload Document
												</a>
											<?php } ?>
											<?php if(isset($is_installer) && $is_installer == true && (in_array($MStatus->WAITING_LIST,$approval) || $ApplyOnlineLead->application_status==$MStatus->WAITING_LIST))
												{ ?>
												<a href="javascript:;" data-toggle="modal" onclick="removeapp('<?php echo encode($ApplyOnlineLead->id); ?>')" class="removeapp dropdown-item">
													<i class="fa fa-refresh" aria-hidden="true"></i> Re-Apply Application
												 </a>
											<?php } ?>
											<?php if(isset($is_installer) && $is_installer == true && ($ApplyOnlineLead->application_status== '' || $ApplyOnlineLead->application_status==$MStatus->APPLICATION_GENERATE_OTP || $ApplyOnlineLead->application_status==$MStatus->APPLICATION_PENDING))
												{ ?>
												<a href="javascript:;" data-toggle="modal" onclick="deleteapp('<?php echo encode($ApplyOnlineLead->id); ?>')" class="removeapp dropdown-item" >
													<i class="fa fa-trash" aria-hidden="true"></i> Delete Application
												 </a>
											<?php }
											else if(isset($member_type) && ($authority_account == 1 || in_array($member_id,$ALLOWED_APPROVE_GEDAIDS)) && CAN_DELETE_APPLICATION_MEMBER == 1)
											{
												$approvedApplication 	= $ApplicationRequestDelete->findLatestApprovedRequest($ApplyOnlineLead->id);
												if($approvedApplication == 1 && !in_array($MStatus->METER_INSTALLATION,$approval)) { ?>
													<a href="javascript:;" data-toggle="modal" data-target="#delete_application" class="delete_application dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
														<i class="fa fa-trash" aria-hidden="true"></i> Delete Application
													</a>
												<?php 
												}	
											}
											if(in_array($MStatus->APPLICATION_SUBMITTED,$approval) && !in_array($MStatus->METER_INSTALLATION,$approval) && !in_array($MStatus->APPLICATION_CANCELLED,$approval))
											{ 
												$approvedApplication 	= $ApplicationRequestDelete->findLatestApprovedRequest($ApplyOnlineLead->id);
												if($approvedApplication == 2 || $approvedApplication == 0) { ?>

													<a href="javascript:;" data-toggle="modal" data-target="#pre_delete_application_request" class="pre_delete_application_request dropdown-item" data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
														<i class="fa fa-trash" aria-hidden="true"></i> Delete Application Request
													</a>
												
													<a href="javascript:;" data-toggle="modal" data-target="#delete_application_request" class="delete_application_request dropdown-item hide"  data-id="<?php echo encode($ApplyOnlineLead->id); ?>">
														<i class="fa fa-trash" aria-hidden="true"></i> Delete Application Request
													</a>
												<?php 
												}
											} ?>
										</div>
									</div>
								<?php } ?>
							</div>
							<?php if(CAN_DOWNLOAD_PDF == 1) { 
								$meterInstalledStage 	= $MStatus->getmeterInstalledStageData($ApplyOnlineLead->id);
								$downloadCorrigendum 	= false;
								if((!isset($meterInstalledStage->created) || (isset($meterInstalledStage->created) && (strtotime($meterInstalledStage->created) >= strtotime(CORRIGENDUM_LETTER_DATE)))) && in_array($MStatus->APPLICATION_SUBMITTED,$approval)) {
									$downloadCorrigendum 	= true;
								}
								?>
								<div class="col-md-5">
									<div class="dropdown">
										<button class="btn btn-secondary dropdown-toggle" type="button" id="downloaddropdownMenu" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Download Application Document <i class="fa fa-chevron-down"></i>
										</button>
										<div class="dropdown-menu" aria-labelledby="downloaddropdownMenu">
											<a class="dropdown-item" href="/apply-onlines/view-application/<?php echo encode($ApplyOnlineLead->id); ?>" onclick="download_app()">
												<i class="fa fa-download"></i> Download Application
											</a>
											<?php if($downloadCorrigendum) { ?>
												<a href="/ApplyOnlines/corrigendum_letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Corrigendum Letter
												</a>
											<?php } ?>
											<?php if($ShowGedaApproval_Letter) { ?>
												<a href="/ApplyOnlines/geda_letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> GEDA Registration Letter
												</a>
											<?php } ?>
											<?php if($Download_Receipt) { ?>
												<a href="/ApplyOnlines/payment_receipt/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Receipt
												</a>
											<?php } ?>
											<?php if(isset($ApplyOnlineLead->apply_onlines_others['e_invoice_url']) && !empty($ApplyOnlineLead->apply_onlines_others['e_invoice_url'])) { ?>
												<a href="<?php echo $ApplyOnlineLead->apply_onlines_others['e_invoice_url'];?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download E-invoice
												</a>
											<?php } ?>
											<?php if($Download_Agreement) { ?>
												<a href="/ApplyOnlines/getAgreementLetter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Agreement Letter
												</a>
											<?php } ?>
											<?php if($DownloadMeterInsallation) {?>
												<a href="/ApplyOnlines/inspection_letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Joint Inspection Letter
												</a>
											<?php } ?>
											<?php if($DispDownloadFesibility) { ?>
												<a href="/ApplyOnlines/feasibility_report/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Fesibility Report
												</a>
											<?php } ?>
											<?php if ($InspectionFromJREDA) { ?>
												<a href="/apply-onlines/geda-inspection-letter/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Inspection and Approval From GEDA
												</a>
											<?php } ?>
											<?php if ($DownloadSummarySheet) { ?>
												<a href="/Subsidy/getSubsidySummarySheet/<?php echo encode($ApplyOnlineLead->id); ?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Summary Sheet
												</a>
											<?php } ?>
											<?php if ($downloadInspection) { ?>
												<a href="<?php echo $InspectionPDF;?>" target="_blank" class="dropdown-item">
													<i class="fa fa-download"></i> Download Inspection Report
												</a>
											<?php } ?>

										</div>
									</div>
								</div>
							<?php } ?>
							<div class="col-md-3 center">
								<span style="font-size:18px;color:<?php echo COLOR_ORANGE;?>"><strong>
									<?php
										echo ($ApplyOnlineLead->disclaimer_subsidy==1) ? '&nbsp;Non Subsidy' : '';

									echo !empty($ApplyOnlineLead->pcr_code) ? 'PCR: '.$ApplyOnlineLead->pcr_code : '';
									?></strong>
								</span>
							</div>
						</span>
					</div>
					<div class="col-md-3">
						<span class="p-date pull-right">
							<?php
							$application_date           = $ApplyOnlineLead->created;
							if(!empty($ApplyOnlineLead->modified))
							{
								$date_data=$MStatus->find('all',array('conditions'=>array('application_id'=>$ApplyOnlineLead->id),'order'=>array('id'=>'desc')))->first();
								?>
									<!-- <?php echo 'Modified'.$ApplyOnlineLead->modified;?> -->
									<?php
								$application_date=$ApplyOnlineLead->modified;
								if(empty($ApplyOnlineLead->application_status) && empty($ApplyOnlineLead->customer_name_prefixed) && empty($ApplyOnlineLead->api_response))
								{
									$application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
								}
								if(!empty($date_data))
								{
									//echo date('Y-m-d H:i',strtotime($date_data->created))."--".date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)-23400);
									?>
									<!-- <?php echo date('Y-m-d H:i',strtotime($date_data->created)) ."--".date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800);?>-->
									<?php
									if(date('Y-m-d H:i',strtotime($date_data->created))==date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800))
									{
										?>
									<!-- <?php echo "iffff";?>-->
									<?php
										$application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
									}
									$LastGUVNLResponse       = $ApiLogResponse->GetLatestGUVNLData($ApplyOnlineLead->id);
									if(!empty($LastGUVNLResponse))
									{
										?>

										<?php
										if(date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800)==date('Y-m-d H:i',strtotime($LastGUVNLResponse->created)))
											{
												$application_date=date('Y-m-d H:i:s',strtotime($ApplyOnlineLead->modified)+19800);
												?>
												<!-- <?php echo date('Y-m-d H:i',strtotime($ApplyOnlineLead->modified)+19800)."--".date('Y-m-d H:i',strtotime($LastGUVNLResponse->created));?>-->
												<?php
											}
									}


								}

							   //$application_date=$ApplyOnlineLead->modified;
							}
							/*if(in_array($MStatus->APPLICATION_SUBMITTED, $approval))
							{
								$application_stage  = $MStatus->getsubmittedStageData($ApplyOnlineLead->id);
								if(!empty($application_stage->created))
								{
									$application_date   = $application_stage->created;
								}
							}    */
							?>
							<?php  echo 'Modified '.(!empty($application_date) ? date(LIST_DATE_FORMAT,strtotime($application_date)) : ''); ?>
						</span>
						<br/>
						<span class="p-date pull-right">
						<?php echo (!empty($ApplyOnlineLead->submitted_date) ? 'Submitted '.date(LIST_DATE_FORMAT,strtotime($ApplyOnlineLead->submitted_date)) : '');?>
						</span>
						<?php
						if($memberApproved == 1 && ($ApplyOnlineLead->category != $ApplyOnlines->category_residental || $ApplyOnlineLead->social_consumer==1))
						{
							$arr_members = $MStatus->getApprovedBy($ApplyOnlineLead->id);

							?>
							<span class="p-date pull-right">Verified By <?php echo isset($arr_members->member['name']) ? $arr_members->member['name'] : '-';?></span>
							<?php
						}
						?>
						<span class="p-date pull-right">
						<?php echo (!empty($gedaLetterDate->created) ? 'GEDA Letter '.date(LIST_DATE_FORMAT,(strtotime($gedaLetterDate->created))) : '');?>
						</span>
					</div>
				</div>
				 <?php
						$Approved       = "";
						$pv_capacity    = (!empty($ApplyOnlineLead->pv_capacity) ? $ApplyOnlineLead->pv_capacity : '-');
						if (!empty($FesibilityData)) {
							if ($FesibilityData->approved == 1) {
								if ($FesibilityData->approved_by_subdivision) {
									$Approved = "<span class='text-info'>Approved by Sub-division</span>";
								}
							} else if ($ApplyOnlineLead->application_status == $MStatus->FIELD_REPORT_REJECTED) {
								$Reason     = isset($FesibilityReport->RejectReason[$FesibilityData->reason])?" - ".$FesibilityReport->RejectReason[$FesibilityData->reason]:"";
								if (!$FesibilityData->approved_by_subdivision) {
									$Approved = trim("<span class='text-danger'>Rejected by Sub-division</span> ".$Reason);
								}
							}
							if(in_array($MStatus->WORK_EXECUTED,$approval))
							{

							}
							elseif ($ApplyOnlineLead->application_status != $MStatus->FIELD_REPORT_SUBMITTED || $ApplyOnlineLead->application_status != $MStatus->FIELD_REPORT_REJECTED ) {
								$pv_capacity = min($FesibilityData->recommended_capacity_by_discom,$pv_capacity);
							}
						}
						?>
				<div class="clear"></div>
				<div class="row">
					<div class="col-lg-12">
						<div class="col-xs-4"><?php 
						$pvCapacityText = 'DC';
						$submitedStage 	= $MStatus->getsubmittedStageData($ApplyOnlineLead->id);
						if(strtotime($ApplyOnlineLead->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE) || (isset($submitedStage->created) && (strtotime($submitedStage->created) >= strtotime(APPLICATION_NEW_SCHEME_DATE)))) {
								$pvCapacityText = 'AC';
						}
						echo $additional_capacity;?>PV capacity (<?php echo $pvCapacityText;?>) to be installed (in kW)</div>
						<div class="col-xs-2 pad-5">
							<?php echo $pv_capacity; ?>
						</div>

						<?php
						if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData))
						{
						?>
							<div class="col-xs-2 pad-25">Quotation No.</div>
							<div class="col-xs-3 pad-20">
								<?php echo $FesibilityData->quotation_number;?>
							</div>
						<?php
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="col-xs-8">Application No.</div>
						<div class="col-xs-4">
							<?php echo $ApplyOnlineLead->application_no;?>
						</div>
					</div>
					<div class="col-lg-6">
						<?php
						if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData))
						{
						?>
							<div class="col-xs-6 col-sm-4 col-lg-4">Estimated Amount</div>
							<div class="col-xs-6 col-sm-8 col-lg-8">
								<?php echo $FesibilityData->estimated_amount;?>
							</div>
						<?php
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="col-xs-12 col-sm-8 col-lg-8">Consumer No.</div>
						<div class="col-xs-12 col-sm-4 col-lg-4">
							<?php echo !empty($ApplyOnlineLead->consumer_no)?$ApplyOnlineLead->consumer_no:'-';?>
						</div>
					</div>
					<div class="col-lg-6">
						<?php
						if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData))
						{
						?>
							<div class="col-xs-6 col-sm-4 col-lg-4">Estimated Due Date</div>
							<div class="col-xs-6 col-sm-8 col-lg-8">
								<?php if(!empty($FesibilityData->estimated_due_date))
								{
									$est_date = date('Y-m-d',strtotime($FesibilityData->estimated_due_date));
									$arr_data_date = explode(' ',$FesibilityData->estimated_due_date);
									if($arr_data_date[0]!='0000-00-00' && $est_date!='1970-01-01')
									{
										$data_date = explode(' ',date(LIST_DATE_FORMAT,strtotime($arr_data_date[0])));
										echo $data_date[0];
									}
								}?>
							</div>
						<?php
						}
						?>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-6">
						<div class="col-xs-12 col-sm-8 col-lg-8">Installer</div>
						<div class="col-xs-12 col-sm-4 col-lg-4">
							<?php
								echo !empty($ApplyOnlineLead->installer['installer_name'])?$ApplyOnlineLead->installer['installer_name']:'-';
							?>
						</div>
					</div>
					<div class="col-lg-6">
						<?php
						if(in_array($MStatus->FEASIBILITY_APPROVAL,$approval) && !empty($FesibilityData))
						{
						?>
							<div class="col-xs-6 col-sm-4 col-lg-4">Payment Status</div>
							<div class="col-xs-6 col-sm-8 col-lg-8">
								<?php
								if($FesibilityData->payment_approve==1)
								{
									echo 'Paid';
								}
								else
								{
									echo 'Not Paid';
								}
								?>
							</div>
						<?php
						}
						?>
					</div>
				</div>
				<?php if(isset($ApplyOnlineLead->developer_customers['name'])) { ?>
					<div class="row">
						<div class="col-lg-12 col-xs-12 col-sm-12">
							<div class="col-xs-4">Developer</div>
							<div class="col-xs-8 pad-5">
								<?php
									echo !empty($ApplyOnlineLead->developer_customers['name'])?$ApplyOnlineLead->developer_customers['name']:'-';
								?>
							</div>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-lg-12 col-xs-12 col-sm-12">
						<div class="col-xs-4">Feasibility Comment</div>
						<div class="col-xs-8 pad-5">
							<?php
								echo !empty($FesibilityData->message)?$FesibilityData->message:'-';
							?>
						</div>
					</div>
				</div>
				<?php
				if($memberApproved == 1)
				{
					?>
					<div class="row">
						<div class="col-lg-12 col-xs-12 col-sm-12">
							<div class="col-xs-4">JIR Unique Code</div>
							<div class="col-xs-8 pad-5">
								<?php
									echo !empty($ApplyOnlineLead->apply_onlines_others['jir_unique_code']) ? $ApplyOnlineLead->apply_onlines_others['jir_unique_code'] : '-';
								?>
							</div>
						</div>
					</div>
					<?php
				}
				if(!empty($additional_capacity))
				{
					?>
					<div class="row">
					<?php
						$existing_capacity = (isset($ApplyOnlineLead->apply_onlines_others['existing_capacity'])) ? $ApplyOnlineLead->apply_onlines_others['existing_capacity'] : '';
						?>
						<div class="col-lg-12 col-xs-12 col-sm-12">
							<div class="col-xs-4">Existing Capacity</div>
							<div class="col-xs-8 pad-5" >
								<?php echo $existing_capacity; ?>
							</div>
						</div>
					</div>
					<?php
				}
				?>
				<div class="row">
					<?php
					$disDetails = $ApplyOnlines->getDiscomDetails($ApplyOnlineLead->circle,$ApplyOnlineLead->division,$ApplyOnlineLead->subdivision,$ApplyOnlineLead->area);
					?>
					<div class="col-lg-12 col-xs-12 col-sm-12">
						<div class="col-xs-4">Discom</div>
						<div class="col-xs-8 pad-5" >
							<?php echo $disDetails; ?>
						</div>
					</div>
				</div>
				<?php if (isset($LastFailResponse) && !empty($LastFailResponse) && $ShowFailResponse) { ?>
				<div class="row">
					<div class="col-lg-12 col-xs-12 col-sm-12">
						<div class="col-xs-4">Discom API Response</div>
						<div class="col-xs-8 pad-5" >
							<?php echo $LastFailResponse; ?>
						</div>
					</div>
				</div>
				<?php } ?>
				<?php if (isset($LastFailSpinResponse) && !empty($LastFailSpinResponse) && $ShowFailResponse) {
						$displayUpload      = false;
						$arrFailedres       = explode("Not Valid Mime type.",$LastFailSpinResponse);
						$arrFailedresSize   = explode("Maximum allowed size for file is ",$LastFailSpinResponse);
						if(count($arrFailedres)>1 || count($arrFailedresSize)>1)
						{
							$displayUpload  = true;
						}
					?>
						<div class="row">
							<div class="col-lg-12 col-xs-12 col-sm-12">
								<div class="col-xs-4">Spin API Response</div>
								<div class="col-xs-8 pad-5" >
									<?php echo $LastFailSpinResponse; ?>
									<?php
									if(count($arrFailedres)>1)
									{
										?>
										<br>
										<span style="color:#8a6d3b;"><strong>The "Not Valid Mime type" is due to the incorrect format of Consumer photo. Kindly update the photo in .JPG format in the <a href="/subsidy/<?php echo encode($ApplyOnlineLead->id); ?>" class="Claim_Subsidy dropdown-item" data-id="" target="_blank">Subsidy Claim section</a>.</strong></span>
										<?php
									}
									if(count($arrFailedresSize)>1)
									{
										?>
										<br>
										<span style="color:#8a6d3b;"><strong>The "Maximum allowed size for file is 200 KB". Kindly update the photo in .JPG format upto 200 KB in the <a href="/subsidy/<?php echo encode($ApplyOnlineLead->id); ?>" class="Claim_Subsidy dropdown-item" data-id="" target="_blank">Subsidy Claim section</a>.</strong></span>
										<?php
									}
									?>
								</div>
							</div>
						</div>
				<?php
					} ?>
				<?php
					if (!empty($GetLastMessage)) {
						$LastMessageHtml    = "<div><span><b><u>Comment</u></b></span><br /><span>".str_replace("'","",$GetLastMessage['message'])."</span><br /><br /><span><b><u>Comment By</u></b></span><br /><span>".$GetLastMessage['comment_by']."</span><br /><br /><span><b><u>IP Address</u></b></span><br /><span>".$GetLastMessage['ip_address']."</span><br /><br /><span><b><u>Comment On</u></b></span><br /><span>".$GetLastMessage['created']."</span></div>";
						$LastMessageRender  = "<span data-toggle=\"popover\" title=\"Latest Comment\" data-html=\"true\" data-content=\"".htmlspecialchars($LastMessageHtml,ENT_QUOTES)."\"><b style=\"color:black;\">View Last Comment</b></span>";
						echo "<div class=\"row\"><div class=\"col-lg-12 col-xs-12 col-sm-12\"><div class=\"col-xs-12 col-sm-12 col-lg-12\"><a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#ViewMessage\" class=\"ViewMessage\" data-id=\"".encode($ApplyOnlineLead->id)."\"><b>View All</b></a> | ".$LastMessageRender;
					}
					if(empty($GetLastMessage) && ((isset($is_member) && ($is_member == true)))) {
						echo "<div class=\"row\"><div class=\"col-lg-12 col-xs-12 col-sm-12\"><div class=\"col-xs-12 col-sm-12 col-lg-12\">";
					}
					if((isset($is_member) && ($is_member == true))) {
						if(!empty($GetLastMessage)) { echo " | "; }
						echo "<a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#ViewLastResponse\" class=\"ViewLastResponse\" data-id=\"".encode($ApplyOnlineLead->id)."\"><b>View Last Response</b></a>";
						echo " | <a href=\"javascript:;\" data-toggle=\"modal\" data-target=\"#ViewCustomerResponse\" class=\"ViewCustomerResponse\" data-id=\"".encode($ApplyOnlineLead->id)."\"><b>View Customer API Response</b></a>";
					}
					if(!empty($GetLastMessage) || ((empty($GetLastMessage) && isset($is_member) && ($is_member == true)))) {
						echo '</div></div></div>';
					}
				?>
				<div class="row progressbar-container">
					<ul class="progressbar_guj">
					<?php
						foreach ($APPLY_ONLINE_MAIN_STATUS as $key => $value) {
							$IsActive           = array_key_exists($key, $arr_application_status)?"active":"";
							if(empty($arr_application_status))
							{
								$IsActive       = ($key==$ApplyOnlineLead->application_status)?"active":"";
							}

							if($key==9 && SHOW_SUBSIDY_EXECUTION==1 && $ApplyOnlineLead->disclaimer_subsidy==1)
							{

							}
							else
							{
								$text_apply     = '';
								$style          = '';
								if ($pv_capacity<=10 && ($key==5 || $key==7))
								{
									$text_apply = ' Self Certification';
									//$style      = "font-size:9px;";

									$value      = ($key==5) ? "Approval" : "Inspection";
								}
								if($str_append!='' && $key==2)
								{
									$IsActive   = '';
								}
								echo "<li class=\"".$IsActive."\" ><span style='".$style."'>".$value.$text_apply."</span></li>";
							}
						}
					?>
					</ul>
				</div>
			</div>
			<?php endforeach; ?>
			<?php if (!empty($ApplyOnlineLeads)) { ?>
			<!-- Paging Starts Here -->
			<div class="text-right">
				<ul class="pagination text-right">
				<?php
				echo $this->Paginator->numbers([
							'before' => $this->Paginator->prev('Prev'),
							'after' => $this->Paginator->next('Next')]); ?>
				</ul>
			</div>
			<?php } ?>
		</div>
	</div>
	<div id="Discom_FA" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Change Discom Data</h4>
				</div>
				<div class="modal-body">
					<?php echo $this->Form->create('ChangeDiscom',['name'=>'ChangeDiscom','id'=>'ChangeDiscom']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('appid',['id'=>'Discom_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_area_id',['id'=>'new_area_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_circle_id',['id'=>'new_circle_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_division_id',['id'=>'new_division_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_subdivision_id',['id'=>'new_subdivision_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_name_applicant',['id'=>'new_name_applicant','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_last_name',['id'=>'new_last_name','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_third_name',['id'=>'new_third_name','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_address',['id'=>'new_address','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_sanction_load',['id'=>'new_sanction_load','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('new_existing_capacity',['id'=>'new_existing_capacity','label' => true,'type'=>'hidden']); ?>
					<div class="row">
						<div class="col-md-8">
							<div class="row hide">
								<div class="col-md-4">DisCom : </div><div class="col-md-6" id="area_id"></div>
							</div>
							<div class="row">
								<div class="col-md-4">Circle : </div><div class="col-md-6" id="circle_id"></div>
							</div>
							<div class="row">
								<div class="col-md-4">Division : </div><div class="col-md-6" id="division_id"></div>
							</div>
							<div class="row">
								<div class="col-md-4">Sub-Division : </div><div class="col-md-6" id="subdivision_id"></div>
							</div>
							<div class="row">
								<div class="col-md-4">Name : </div><div class="col-md-6" id="name_id"></div>
							</div>
							<div class="row">
								<div class="col-md-4">Address : </div><div class="col-md-6" id="address_id"></div>
							</div>
							<div class="row">
								<div class="col-md-4">Sanction Load : </div><div class="col-md-6" id="sanction_id"></div>
							</div>
							<div class="row" id="row_existing_id">
								<div class="col-md-4">Existing Capacity : </div><div class="col-md-6" id="existing_id"></div>
							</div>
						</div>
						<div class="col-md-4">							
							<div class="row">
								<div class="col-md-12" id="fetch_data">
								<?php echo $this->Form->input('Fetch Latest Data',['type'=>'button','id'=>'fetch_l_data','label'=>false,'class'=>'btn btn-primary fetch_latest_data','data-form-name'=>'ChangeDiscom']); ?>
									
								</div>
								<div class="col-md-12" id="circle_new"></div>
							</div>
							<div class="row">
								<div class="col-md-12" id="division_new"></div>
							</div>
							<div class="row">
								<div class="col-md-12" id="subdivision_new"></div>
							</div>
							<div class="row">
								<div class="col-md-12" id="name_new"></div>
							</div>
							<div class="row">
								<div class="col-md-12" id="address_new"></div>
							</div>
							<div class="row">
								<div class="col-md-12" id="sanction_new"></div>
							</div>
							<div class="row" id="row_new_existing_id">
								<div class="col-md-12" id="existing_new"></div>
							</div>
						</div>
					</div>
						
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_discom','label'=>false,'class'=>'btn btn-primary change_discom','data-form-name'=>'ChangeDiscom']); ?>
						</div>
					</div>
					<?php echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="forword_popup_discom" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Forward Application </h4>
				</div>
				<div class="modal-body">
					<div id="messageBox"></div>
					<?php
					echo $this->Form->create('forward_application',['name'=>'forward_application','id'=>'forward_application']); ?>
					<div class="form-group text">
					<label>Select Division</label>
					<?php echo $this->Form->input('id',['id'=>'application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('member_assign_id',(isset($discomList)?$discomList:array()),['id'=>'discom_id',"class" =>"form-control",'label' => true,'empty'=>'-Select Division-']); ?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Forward',['type'=>'button','id'=>'login_btn_1','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="forword_popup_subdivision" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<?php
				$IsSubdivision      = false;
				if(isset($discom_details['field']))
				{
					$IsSubdivision  = ($discom_details['field'] == "subdivision")?true:false;
				}
					$class_1        = ($IsSubdivision)?"hide":"";
					$class_2        = ($IsSubdivision)?"":"hide";
					$Modal_Title    = (!$IsSubdivision)?"Assign Subdivision / Section":"Assign Section";
				?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo $Modal_Title;?></h4>
				</div>
				<div class="modal-body">
					<div id="assign_division_message"></div>
					<?php
					echo $this->Form->create('assign_division', ['id'=>'assign_division',
																'method'=>'post','type' => 'post'
															,'url' => '/apply-onlines/assign-division']);
					?>
					<div class="form-group text">
					<?php echo $this->Form->input('id',['id'=>'app_id','label' => false,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('division',['id'=>'division','label' => false,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('subdivision_id',['id'=>'subdivision_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('section_id',['id'=>'section_id','label' => false,'type'=>'hidden']); ?>
						<div class="col-md-12">
							<?php echo $this->Form->select('subdivision',(isset($divisionList)?$divisionList:array()),['id'=>'subdivision',"class" =>"form-control ".$class_1,'label' => false,'empty'=>'-Select Subdivision-']); ?>
						</div>
						<div class="col-md-12 <?php echo $class_1?>" style="padding-left: 40px;">
							<?php echo $this->Form->input('section_chk',['id'=>'section_chk','label' => false,'type'=>'checkbox']); ?><label for="section_chk">If you know the Section "Click Here" to assign or skip this to assign directly to Sub-division.</label>
						</div>
						<div class="col-md-12">
							<?php echo $this->Form->select('section',array(),['id'=>'section',"class" =>"form-control select-section ".$class_2,'label' => false,'empty'=>'-Select Section-']); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Assign',['type'=>'button','id'=>'assigndiscom','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="discom_status" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Subsidy Availability</h4>
				</div>
				<div class="modal-body">
					<div id="messageBox"></div>
					<?php
					echo $this->Form->create('sabsidy_availability',['name'=>'sabsidy_availability','id'=>'sabsidy_availability']); ?>
					<div class="form-group text">
					<?php echo $this->Form->input('id',['id'=>'sabsidy_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('member_assign_id',['id'=>'member_assign_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('application_status',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[$SUBSIDY_AVAILIBILITY=>'Yes, Subsidy available',$FUNDS_ARE_NOT_AVAILABLE=>'No, Funds are not available',$FUNDS_ARE_AVAILABLE_BUT_SCHEME_IS_NOT_ACTIVE=>'No, Funds are available but scheme is not active'],'label' => false]); ?>
					<span class="hide application_status_message"></span>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_3','label'=>false,'class'=>'btn btn-primary']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="CEI_Status" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approval From CEI</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('FCEI_Status',['name'=>'CEI_Status','id'=>'FCEI_Status']); ?>
					<div id="messageBox"></div>
					<div class="row">
						<div class="col-md-6">
							<label>CEI Drawing Application Ref. No.:</label>
						</div>
						<div class="col-md-6" id="drawing_app_no">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>CEI Drawing Application Status:
							<?php echo $this->Form->input('drawing_app_status',['label' => false,'type'=>'hidden','id'=>'drawing_app_status']); ?></label>
						</div>
						<div class="col-md-6" id="drawing_app_status_html"></div>
					</div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'CEI_approval_type','label' => true,'type'=>'hidden','value'=>'1']); ?>
					<?php echo $this->Form->input('appid',['id'=>'CEI_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('application_status',array("1"=>"Approved"),["class" =>"form-control application_status",'id'=>'CEI_application_status','label' => false]); ?><br />
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",
																'id'=>'FCEI_reason',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Comments, if any']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_4','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FCEI_Status']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="CEI_INS_Status" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Inspection From CEI</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('FCEI_INS_Status',['name'=>'CEI_Status','id'=>'FCEI_INS_Status']); ?>
					<div id="messageBox"></div>
					<div class="row">
						<div class="col-md-6">
							<label>CEI Application Number:</label>
						</div>
						<div class="col-md-6" id="cei_app_no">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>CEI Application Status:
							<?php echo $this->Form->input('cei_app_status',['label' => false,'type'=>'hidden','id'=>'cei_app_status']); ?></label>
						</div>
						<div class="col-md-6" id="cei_app_status_html"></div>
					</div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'CEI_INS_approval_type','label' => true,'type'=>'hidden','value'=>'5']); ?>
					<?php echo $this->Form->input('appid',['id'=>'CEI_INS_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('application_status',array("1"=>"Approved"),["class" =>"form-control application_status",'id'=>'CEI_INS_application_status','label' => false]); ?><br />
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",
																'id'=>'FCEI_INS_reason',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Comments, if any']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_11','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FCEI_INS_Status']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="DISCOM_Approved_Status" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Inspection From DisCom</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('FDISCOM_Status',['name'=>'DISCOM_Status','id'=>'FDISCOM_Status']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'DISCOM_approval_type','label' => true,'type'=>'hidden','value'=>'2']); ?>
					<?php echo $this->Form->input('appid',['id'=>'DISCOM_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('application_status',array("1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'DISCOM_application_status','label' => false]); ?><br />
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",
																'id'=>'DISCOM_reason',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Comments, if any']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_5','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FDISCOM_Status']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="Approve_FA" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approval from Division</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('FApprove_FA',['name'=>'Approve_FA','id'=>'FApprove_FA']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'Approve_FA_approval_type','label' => true,'type'=>'hidden','value'=>'4']); ?>
					<?php echo $this->Form->input('appid',['id'=>'Approve_FA_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->select('application_status',array("1"=>"Approved","2"=>"Rejected"),["class" =>"form-control application_status",'id'=>'JREDA_FA_application_status','label' => false]); ?><br />
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control reason",
																'id'=>'Approve_FA_reason',
																'cols'=>'50','rows'=>'5',
																'label' => false,
																'placeholder' => 'Comments, if any']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_7','label'=>false,'class'=>'btn btn-primary approval_btn','data-form-name'=>'FApprove_FA']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="SendMessage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Send Message</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('SendMessageForm',['name'=>'SendMessageForm','id'=>'SendMessageForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('appid',['id'=>'SendMessage_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->textarea('messagebox',[ "class" =>"form-control messagebox",
																	'id'=>'messagebox',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Message or Comment']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary sendmessage_btn','data-form-name'=>'SendMessageForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="delete_application" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Application</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('DeleteApplicationForm',['name'=>'DeleteApplicationForm','id'=>'DeleteApplicationForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'Delete_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->textarea('reason',[ "class" =>"form-control messagebox",
																	'id'=>'messagebox',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Reason']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Ok',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary deleteapplication_btn','data-form-name'=>'DeleteApplicationForm']); 
							?>
						</div>
						<div class="col-md-2">
							<?php 
						 	echo $this->Form->input('Cancel',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary ','data-dismiss'=>"modal"]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="pre_delete_application_request" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Application Request</h4>
				</div>
				<div class="modal-body">
					<?php echo $this->Form->input('application_id',['id'=>'pre_Delete_application_req_id','label' => true,'type'=>'hidden']); ?>
					Kindly use the format provided here to request for deletion. In case of any other format of letter, approval may not be provided. <a href="/Format-for-Consent-from-Consumer.docx">Consent from Consumer</a>, <a href="/Format-for-Consent-from-Installer.docx">Consent from Installer</a><br/><br/>
					<?php  echo $this->Form->input('Continue to Proceed',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary delete_application_popup','data-dismiss'=>"modal"]); ?>

				</div>
			</div>
		</div>
	</div>
	<div id="delete_application_request" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Delete Application Request</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('DeleteApplicationRequestForm',['name'=>'DeleteApplicationRequestForm','id'=>'DeleteApplicationRequestForm','type'=>'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
						<?php echo $this->Form->textarea('reason',[ "class" =>"form-control messagebox",
																		'id'=>'messagebox',
																		'cols'=>'50','rows'=>'5',
																		'label' => false,
																		'placeholder' => 'Reason']);
						?>
					</div>
					<?php if($member_type == $MemberTypeDiscom || $is_installer == true){ ?>
					<?php echo $this->Form->input('consent_not_available',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[0=>"I have Both"],'label' => false]); ?>
					<?php } else { ?>
					<?php echo $this->Form->input('consent_not_available',["class" =>"form-control",'type'=>'radio','text'=>'Approved','options'=>[1=>"I don't have Consumer Consent Letter",2=>"I don't have Installer Consent Letter",0=>"I have Both"],'label' => false]); ?>
					<?php }	?>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<lable class="col-md-4">Consumer Consent Letter</lable>
								<div class="col-md-8">
									<?php echo $this->Form->input('consumer_consent_letter', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'consumer_consent_letter')); ?>
									<div id="consumer_consent_letter_view"></div>
								</div>

							</div>
						</div>
					</div>
					<div class="row" style="margin-right: 2px;margin-left: -4px;">
						<div class="col-md-12"  id="consumer_consent_letter-file-errors"></div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="row">
								<lable class="col-md-4">Installer Consent Letter</lable>
								<div class="col-md-8">
									<?php echo $this->Form->input('vendor_consent_letter', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'vendor_consent_letter')); ?>
									<div id="vendor_consent_letter_view"></div>
								</div>
								

							</div>
						</div>
					</div>
					<div class="row" style="margin-right: 2px;margin-left: -4px;">
						<div class="col-md-12"  id="vendor_consent_letter-file-errors"></div>
					</div>
					
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('application_id',['id'=>'Delete_application_req_id','label' => true,'type'=>'hidden']);
							 echo $this->Form->input('delete_request_id',['id'=>'delete_request_id','label' => true,'type'=>'hidden']); ?>
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary deleteapp_request_btn','data-form-name'=>'DeleteApplicationRequestForm']); 
							?>
						</div>
						<div class="col-md-2">
							<?php 
						 	echo $this->Form->input('Cancel',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary ','data-dismiss'=>"modal"]); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="approvedpayment" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approve Payment</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ApprovePaymentForm',['name'=>'ApprovePaymentForm','id'=>'ApprovePaymentForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'payment_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">

										<div class="col-md-12"><label>Approve</label></div>
										<div class="col-md-12">

										   <?php echo $this->Form->select('payment_approve',array("1"=>"Yes","0"=>"No"),["class" =>"form-control payment_approve",'id'=>'payment_approve','label' => true]); ?> <br>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'approve_pay_file')); ?>
										</div>
										<div class="col-md-12" >
											<div class="" style="margin-right: 2px;margin-left: 0px;" id="approve_pay_file-file-errors"></div>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->textarea('message',[ "class" =>"form-control message",
																	'id'=>'message',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Message or Comment']);
											?>
										</div>
									</div>
								</div>
							</div> <br>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary approvepayment_btn','data-form-name'=>'ApprovePaymentForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="approvegeda" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Approve GEDA Letter</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ApproveGedaForm',['name'=>'ApproveGedaForm','id'=>'ApproveGedaForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('geda_id',['id'=>'geda_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12"><label>Approve</label></div>
							<div class="col-md-12">
								<?php echo $this->Form->select('geda_approve',array("1"=>"Yes","0"=>"No"),["class" =>"form-control payment_approve",'id'=>'payment_approve','label' => true]); ?> <br>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary approvegeda_btn','data-form-name'=>'ApproveGedaForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="ViewMessage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">View Messages</h4>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
	<div id="ViewLastResponse" class="modal fade" role="dialog">
		<div class="modal-dialog modal-full-dialog">
			<div class="modal-content modal-full-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Last Response</h4>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
	<div id="ViewCustomerResponse" class="modal fade" role="dialog">
		<div class="modal-dialog modal-full-dialog">
			<div class="modal-content modal-full-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Customer API Response</h4>
				</div>
				<div class="modal-body">
				</div>
			</div>
		</div>
	</div>
	<div id="Varify_Otp" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Verify OTP</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('VarifyOtpForm',['name'=>'VarifyOtpForm','id'=>'VarifyOtpForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('appid',['id'=>'VarifyOtp_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->input('otp',["class" =>"form-control",
																	'id'=>'otp',
																	'label' => false,
																	"placeholder" => "Enter OTP"
														]);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_OTP','label'=>false,'class'=>'btn btn-primary varifyotp_btn','data-form-name'=>'VarifyOtpForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="DRAWING_Status" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">CEI Drawing Application Ref. No.</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('DCEI_Status',['name'=>'DRAWING_Status','id'=>'DCEI_Status']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'DRAW_approval_type','label' => true,'type'=>'hidden','value'=>'51']); ?>
					<?php echo $this->Form->input('appid',['id'=>'DRAW_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">
						<div class="col-md-6">
						<?php echo $this->Form->input('drawing_app_no',['label' => false,'placeholder'=>'CEI drawing application number','id'=>'drawing_app_no_val']); ?>
						</div>
						<div class="col-md-6">
						<?php echo $this->Form->input('Fetch Status',['type'=>'button','label'=>false,'class'=>'btn btn-primary','id'=>'fetch_status_drawing','data-form-name'=>'DCEI_Status']); ?>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>CEI Drawing Application Status:
							<?php echo $this->Form->input('drawing_app_status',['label' => false,'type'=>'hidden','id'=>'drawing_app_status_frm']); ?></label>
						</div>
						<div class="col-md-6" id="drawing_app_status_html_frm"></div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary approval_btn_drawing','data-form-name'=>'DCEI_Status']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="CEI_APP_Status_POPUP" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">CEI Inspection Form</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('CEI_APP_Status_FORM',['name'=>'CEI_APP_Status_FORM','id'=>'CEI_APP_Status_FORM']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'CEI_app_approval_type','label' => true,'type'=>'hidden','value'=>'6']); ?>
					<?php echo $this->Form->input('appid',['id'=>'CEI_form_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row" id="cei_app_number_data">
						<div class="col-md-6">
					   <?php echo $this->Form->input('cei_app_no',['label' => false,'placeholder'=>'Application Ref No.','id'=>'cei_app_no_val']); ?>
						</div>
						<div class="col-md-6">
						<?php echo $this->Form->input('Fetch Status',['type'=>'button','label'=>false,'class'=>'btn btn-primary','id'=>'fetch_status_cei','data-form-name'=>'CEI_APP_Status_FORM']); ?>
						</div>
					</div>
					<div class="row" id="cei_app_status_data">
						<div class="col-md-6">
						<label>CEI Application Status:
							<?php echo $this->Form->input('cei_app_status',['label' => false,'type'=>'hidden','id'=>'cei_app_status_frm']); ?></label>
						</div>
						<div class="col-md-6" id="cei_app_status_html_frm"></div>
					</div>

					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary approval_btn_cei','data-form-name'=>'CEI_APP_Status_FORM']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="Claim_Subsidy" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Claim Subsidy</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('CLAIM_Status',['name'=>'Claim_Subsidy','id'=>'CLAIM_Status']); ?>
					<div id="messageBox"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('approval_type',['id'=>'CLAIM_approval_type','label' => true,'type'=>'hidden','value'=>'7']); ?>
					<?php echo $this->Form->input('appid',['id'=>'Claim_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">
						<div class="col-md-6">
						<label>Rooftop Area</label>
						</div>
						<div class="col-md-6" id="project_area">

						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>Avg. Monthly Electricity Consumption</label>

						</div>
						<div class="col-md-6" id="estimated_kwh_year"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>Avg. Monthly Electricity Bill</label>

						</div>
						<div class="col-md-6" id="avg_monthly_bill"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>Recomanded Capacity</label>

						</div>
						<div class="col-md-6" id="recommended_capacity"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>Cost of System (In Lacs)</label>

						</div>
						<div class="col-md-6" id="estimated_cost"></div>
					</div>
					<div class="row">
						<div class="col-md-6">
						<label>Subsidy Amount (In Lacs)</label>

						</div>
						<div class="col-md-6" id="estimated_cost_subsidy"></div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'','label'=>false,'class'=>'btn btn-primary approval_btn_claim','data-form-name'=>'CLAIM_Status']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="ReplayMessage" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reply Message</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ReplayMessageForm',['name'=>'ReplayMessageForm','id'=>'ReplayMessageForm']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<div id="reply_message" class="inquiry-right"></div>
					<?php echo $this->Form->input('app_id',['id'=>'ReplayMessage_application_id','label' => true,'type'=>'hidden']); ?>
					<?php echo $this->Form->textarea('message',[ "class" =>"form-control message",
																	'id'=>'message',
																	'cols'=>'50','rows'=>'5',
																	'label' => false,
																	'placeholder' => 'Message or Comment']);
					?>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary replaymessage_btn','data-form-name'=>'ReplayMessageForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="uploaddocument" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload Signed Document</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('UploadDocumentForm',['name'=>'UploadDocumentForm','id'=>'UploadDocumentForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'upload_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'upload_signed_file')); ?>
										</div>

									</div>
								</div>
							</div>
							<div class="row" style="margin-right: 2px;margin-left: -4px;">
								<div class="col-md-12"  id="upload_signed_file-file-errors"></div>
							</div>
							 <br>
					</div>
					<div class="row">
						<?php
						if($quota_msg_disp!==true)
						{
						?>
							<div class="message alert alert-danger">
								<button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button><?php echo $quota_msg_disp;?>
							</div>
						<?php
						}
						else
						{
							?>
							<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary uploaddocument_btn','data-form-name'=>'UploadDocumentForm']); ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="removeCommonMeter" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Remove Common Meter</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('RemoveCommonForm',['name'=>'RemoveCommonForm','id'=>'RemoveCommonForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('meter_application_id',['id'=>'meter_application_id','label' => true,'type'=>'hidden']); ?>

					</div>
					<div class="row">	
						<div class="col-md-2">
						<?php echo $this->Form->input('Remove',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary removeCommonMeter_btn','data-form-name'=>'UploadDocumentForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="selfcertificate" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Self Certification</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('SelfCertificationForm',['name'=>'SelfCertificationForm','id'=>'SelfCertificationForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'selfcertificate_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12">
								<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'self_cert_file')); ?>
							</div>
						</div>
						<div class="row" style="margin-right: 2px;margin-left: -4px;">
							<div class="col-md-12"  id="self_cert_file-file-errors"></div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary selfcertificate_btn','data-form-name'=>'SelfCertificationForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="reopenApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reopen Application</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ReopenForm',['name'=>'ReopenForm','id'=>'ReopenForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('reopen_application_id',['id'=>'reopen_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12"><label>Reopen</label></div>
							<div class="col-md-12">
								<?php echo $this->Form->textarea('message',
									[
										"class" =>"form-control reason",
										'id'=>'ReopenApplicationMessage',
										'cols'=>'50','rows'=>'5',
										'label' => false,
										'placeholder' => 'Comments, if any'
									]);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_reopen','label'=>false,'class'=>'btn btn-primary reopenApplication_btn','data-form-name'=>'ReopenForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="resetApplication" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Reset Application</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('ResetForm',['name'=>'ResetForm','id'=>'ResetForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('reset_application_id',['id'=>'reset_application_id','label' => true,'type'=>'hidden']); ?>
						<div class="row">
							<div class="col-md-12"><label>Reset</label></div>
							<div class="col-md-12">
								<?php echo $this->Form->textarea('message',
									[
										"class" =>"form-control reason",
										'id'=>'ResetApplicationMessage',
										'cols'=>'50','rows'=>'5',
										'label' => false,
										'placeholder' => 'Comments, if any'
									]);
								?>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_reopen','label'=>false,'class'=>'btn btn-primary resetApplication_btn','data-form-name'=>'ResetForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="otherdocument" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Upload Other Document</h4>
				</div>
				<div class="modal-body">
					<?php
					echo $this->Form->create('OtherDocumentForm',['name'=>'OtherDocumentForm','id'=>'OtherDocumentForm','type' => 'file']); ?>
					<div id="message_error"></div>
					<div class="form-group text">
					<?php echo $this->Form->input('other_application_id',['id'=>'other_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-12">
											<label>Document Type</label>
											<?php echo $this->Form->input('message',['id'=>'message','label' =>false,'type'=>'text']); ?>
										</div>
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'other_docfile')); ?>
										</div>

									</div>
									<div class="row" style="margin-right: 2px;margin-left: -4px;">
										<div class="col-md-12"  id="other_docfile-file-errors"></div>
									</div>
								</div>
							</div>
					</div>
					<div class="row">
						<div class="col-md-2">
							<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary otherdocument_btn','data-form-name'=>'OtherDocumentForm']); ?>
						</div>
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="uploadUndertaking" class="modal fade" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title title_upload_undertaking" >Disclaimer</h4>
				</div>
				<div class="modal-body">
					<div class="col-md-12 col-lg-12 col-sm-12 text_upload_undertaking" style="text-align: justify;" >
						<strong>Sub:</strong> Regarding consent / undertaking for opting Gujarat Renewable Energy policy-2023 and all provisions decided by the Gujarat Electricity Regulatory Commission (GERC) for project registration.<br>
I/We, [Name of Renewable Energy Generator], hereby undertake to comply with all the provisions of the Gujarat Renewable Energy policy-2023 and the GERC regulations issued from time to time. We understand that we are bound by all the provisions that are decided by the GERC.<br>
We further undertake that we are bound by all the provisions, rules, and regulations that may be decided by the Gujarat Electricity Regulatory Commission (GERC), even if those provisions, rules, and regulations impact the payback or return on investment of the project.<br>
					</div>
					<div class="col-md-12 col-lg-12 col-sm-12 text_upload_undertaking" style="margin-top: 10px;margin-bottom: 10px;text-align: justify;">
						Kindly note that Original copy of Declaration required to be sent to Concerned DISCOM office alongwith wheeling agreement

					</div>
					<div class="col-md-12 col-lg-12 col-sm-12" style="text-align: justify;margin-bottom: 10px" >
						The payment of GEDA fees will can be made only once the Undertaking document is uploaded. Download Undertaking Format from here. The undertaking is to uploaded on a Notarized Rs. 300 stamp paper.
					</div>
					<?php
					echo $this->Form->create('uploadUndertakingForm',['name'=>'uploadUndertakingForm','id'=>'uploadUndertakingForm','type' => 'file']); ?>
					
					<div class="form-group text">
					<?php echo $this->Form->input('application_id',['id'=>'undertaking_application_id','label' => true,'type'=>'hidden']); ?>
							<div class="row">
								<div class="col-md-12">
									<div class="row">
										<div class="col-md-1 col-lg-1 col-sm-1"></div>
										<div class="col-md-10 col-lg-10 col-sm-10" id="message_error"></div>
										<div class="col-md-1 col-lg-1 col-sm-1"></div>
										<div class="col-md-12">
											<?php echo $this->Form->input('file', array('label' => false,'type'=>'file','class'=>'form-control','placeholder'=>'Upload document','id'=>'fileupload_undertaking')); ?>
										</div>
										
									</div>
								</div>
							</div>
							<div class="row" style="margin-right: 2px;margin-left: -4px;">
								<div class="col-md-12"  id="upload_undertaking_file-file-errors"></div>
							</div>
							<div class="row" style="text-align: right;margin-right: 10px;">
								<a href="/undertaking" target="_blank" ><strong>[Download Undertaking Format]</strong></a>
							</div>
					</div>
					<div class="row">
						
						<div class="col-md-2">
						<?php echo $this->Form->input('Submit',['type'=>'button','id'=>'login_btn_8','label'=>false,'class'=>'btn btn-primary uploadUndertaking_btn','data-form-name'=>'uploadUndertakingForm']); ?>
						</div>
							
					</div>
					<?php
					echo $this->Form->end(); ?>
				</div>
			</div>
		</div>
	</div>
	<div id="JREDA_Status" class="modal fade" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title">Inspection From GEDA</h4>
				</div>
				<div class="modal-body">
					<?php echo $this->element('geda_approval_form',array('InspectionReport'=>$InspectionReport));?>
				</div>
			</div>
		</div>
	</div>

</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog2">
	<div class="modal-content">

	</div>
	<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<div id="Notice-model" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content" style="text-align: center;">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal">&times;</button> -->

			</div>
			<div class="modal-body">
				<div class="alert alert-warning">
					<strong>Warning!</strong>
					<ul>
						<li style="font-size: 1.0em;" class="text-danger">
							 During F.Y. 23-24, Those developers who have deducted TDS while making payment on RE portal and not deposited the same & also not filed E-TDS return, their login will soon become inactive. To avoid inconvenience, please pay TDS and file E-TDS return immediately. (PAN of GEDA : AAATG1858Q)
						</li>
					</ul>
					<button class="btn btn-secondary dropdown-toggle" type="button" > Close</button>
				</div>
				
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

$(document).ready(function() {
	 var isMember = <?php echo json_encode($is_member); ?>;

	if (isMember === false) {

		document_window = 780;
		document_height = 220;
		$('#Notice-model').modal('show');
		$('#Notice-model').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
		$('#Notice-model').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	}
	
	
});
$("#other_docfile").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#other_docfile-file-errors',
	maxFileSize: '1024',
});
$("#upload_signed_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#upload_signed_file-file-errors',
	maxFileSize: '1024',
});
$("#self_cert_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#self_cert_file-file-errors',
	maxFileSize: '1024',
});
$("#approve_pay_file").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#approve_pay_file-file-errors',
	maxFileSize: '1024',
});
$("#consumer_consent_letter").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#consumer_consent_letter-file-errors',
	maxFileSize: '1024',
});
$("#vendor_consent_letter").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#vendor_consent_letter-file-errors',
	maxFileSize: '1024',
});
$("#fileupload_undertaking").fileinput({
	showUpload: false,
	showPreview: false,
	dropZoneEnabled: false,
	mainClass: "input-group-lg",
	allowedFileExtensions: ["pdf"],
	elErrorContainer: '#upload_undertaking-file-errors',
	maxFileSize: '1024',
});
var status_messages = <?php echo json_encode($application_status); ?>;
$('a[rel="viewView"]').click(function(){
	$.fancybox({
		'autoDimensions' : true,
		'href'    : this.href,
		'width'   : 700,
		'type'    : 'iframe',
		'arrows'  : false,
		'scrolling':false,
		'autoSize':true,
		'mouseWheel':false
	});
	return false;
});
$('.date-picker').datepicker({format: 'dd/M/yyyy'});
$(".forward_application").click(function(){
	var application_id = $(this).attr("data-id");
	$("#application_id").val(application_id);
	$("#forward_application select").val('');
});
$("#section_chk").change(function() {
	if ($(".select-section").hasClass("hide")) {
		$(".select-section").removeClass("hide");
	} else {
		$(".select-section").addClass("hide");
	}
});

$(".show-hide-action").click(function(){
	if ($(".action-row").hasClass("hide")) {
		$(".action-row").removeClass("hide");
	} else {
		$(".action-row").addClass("hide");
	}
});
$(".forword_popup_subdivision").click(function(){
	var app_id          = $(this).attr("data-id");
	var division        = $(this).attr("data-division");
	var subdivision_id  = $(this).attr("data-subdivision");
	var section_id      = $(this).attr("data-section");
	$("#assign_division").find("#app_id").val(app_id);
	$("#assign_division").find("#division").val(division);
	$("#assign_division").find("#subdivision_id").val(subdivision_id);
	$("#assign_division").find("#section_id").val(section_id);
	$("#assign_division select").val('');
	$("#assign_division_message").html('');
	$("#assign_division_message").removeClass("alert");
	$("#assign_division_message").removeClass("alert-success");
	$("#assign_division_message").removeClass("alert-error");
	$.ajax({
			  type: "POST",
			  url: "/apply-onlines/getSubdivision",
			  data: {"division":division},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.data.subdivision != undefined) {
					$("#subdivision").html("");
					$("#subdivision").append($("<option />").val(0).text('-Select Subdivision-'));
					$.each(result.data.subdivision, function(index, title) {
						$("#subdivision").append($("<option />").val(index).text(title));
					});
				}
				if (result.data.section != undefined) {
					$("#section").html("");
					$("#section").append($("<option />").val(0).text('-Select Section-'));
					$.each(result.data.section, function(index, title) {
						$("#section").append($("<option />").val(index).text(title));
					});
				}
				if (subdivision_id > 0) {
					$("#subdivision").val(subdivision_id);
					$("#subdivision").trigger("change");
				}
				if (section_id > 0) $("#section").val(section_id);
			  }
		});
});
$("#subdivision").change(function(){
	$.ajax({
			  type: "POST",
			  url: "/apply-onlines/getSubdivision",
			  data: {"division":0,"subdivision":$(this).val()},
			  success: function(response) {
				var result = $.parseJSON(response);
				$("#section").html("");
				$("#section").append($("<option />").val(0).text('-Select Section-'));
				if (result.data.section != undefined) {
					$.each(result.data.section, function(index, title) {
						$("#section").append($("<option />").val(index).text(title));
					});
				}
			  }
		});
});
$(".discom_status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#sabsidy_application_id").val(application_id);
	$('input[name="application_status"]').prop('checked', false);
});
$("#assigndiscom").click(function(){
	if ($("#subdivision").val() <= 0) {
		alert("Subdivision is required field.");
		return false;
	} else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/assign-division",
			  data: $("#assign_division").serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-danger");
					$("#assign_division_message").html(result.msg);
				} else {
					$("#assign_division_message").addClass("alert alert-success");
					$("#assign_division_message").html(result.msg);
				}
			  }
		});
	}
	return false;
});
$(".CEI_Status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#CEI_application_id").val(application_id);
	$.ajax({
			type: "POST",
			url: "/ApplyOnlines/fetchceidata",
			data: {'app_id':application_id},
			success: function(response) {
			var result = $.parseJSON(response);
			if (result.type == "ok") {
				$("#drawing_app_no").html(result.response['drawing_app_no']);
				$("#drawing_app_status_html").html(result.response['drawing_app_status']);
				$("#drawing_app_status").val(result.response['drawing_app_status']);
				$("#fetch_status_drawing").hide();
			}
			// window.location.reload();
			}
		});
});
$(".CEI_INS_Status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#CEI_INS_application_id").val(application_id);
	$.ajax({
			  type: "POST",
			  url: "/ApplyOnlines/fetchceidata",
			  data: {'app_id':application_id},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok") {
					$("#cei_app_no").html(result.response['cei_app_no']);
					$("#cei_app_status_html").html(result.response['cei_app_status']);
					$("#cei_app_status").val(result.response['cei_app_status']);
					//$("#fetch_status_drawing").hide();
					//$("#cei_app_number_data").show();
				   // $("#cei_app_status_data").show();
				}
			   // window.location.reload();
			  }
		});
});
$(".DRAWING_Status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#DRAW_application_id").val(application_id);
	$.ajax({
			  type: "POST",
			  url: "/ApplyOnlines/fetchceidata",
			  data: {'app_id':application_id},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok") {
					$("#drawing_app_no_val").val(result.response['drawing_app_no']);
					$("#drawing_app_status_html_frm").html(result.response['drawing_app_status']);
					$("#drawing_app_status_frm").val(result.response['drawing_app_status']);
				}
			   // window.location.reload();
			  }
		});
});
$(".Claim_Subsidy").click(function(){
	var application_id = $(this).attr("data-id");
	$("#Claim_application_id").val(application_id);
	$.ajax({
			  type: "POST",
			  url: "/ApplyOnlines/fetchprojectdata",
			  data: {'app_id':application_id},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok") {
					$("#project_area").html(result.response['area']+" "+result.response['area_type_text']);
					$("#estimated_kwh_year").html(result.response['estimated_kwh_year']+" kWh");
					$("#avg_monthly_bill").html(result.response['avg_monthly_bill']+" Rs");
					$("#recommended_capacity").html(result.response['recommended_capacity']+" kW");
					$("#estimated_cost").html(result.response['estimated_cost']+" Rs");
					$("#estimated_cost_subsidy").html((result.response['estimated_cost_subsidy']/100000)+" Rs");
				}
			   // window.location.reload();
			  }
		});
});
$(".CEI_APP_Status_POPUP").click(function(){
	var application_id = $(this).attr("data-id");
	$("#CEI_form_application_id").val(application_id);
	$.ajax({
			  type: "POST",
			  url: "/ApplyOnlines/fetchceidata",
			  data: {'app_id':application_id},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok") {
					$("#cei_app_no_val").val(result.response['cei_app_no']);
					$("#cei_app_status_html_frm").html(result.response['cei_app_status']);
					$("#cei_app_status_frm").val(result.response['cei_app_status']);
				}
			   // window.location.reload();
			  }
		});
});
$(".DISCOM_Approved_Status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#DISCOM_application_id").val(application_id);
});

function CallInspectionData(application_id,formname,approval_type)
{
	$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: {"appid":application_id,"approval_type":approval_type,"show-prev-report":1},
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "ok" && result.inspection_data != "") {
					$.each(result.inspection_data, function(rowid, rowval) {
						if (rowid == 'application_status') {
							$("#"+formname).find(".application_status").val(rowval);
						} else if (rowid == 'reason') {
							$("#"+formname).find(".reason").val(rowval);
						} else {
							$("#"+formname).find(".que_"+rowid).val(rowval);
						}
					});
				}
			  }
		});
}
$(".JREDA_Status").click(function(){
	var application_id = $(this).attr("data-id");
	$("#JREDA_application_id").val(application_id);
	setTimeout(CallInspectionData,3000,application_id,"FJREDA_Status","3");
});

$(".Approve_FA").click(function(){
	var application_id = $(this).attr("data-id");
	$("#Approve_FA_application_id").val(application_id);
});
$(".Discom_FA").click(function(){
	var application_id 	= $(this).attr("data-id");
	var area 			= $(this).attr("data-area");
	var circle 			= $(this).attr("data-circle");
	var division 		= $(this).attr("data-division");
	var subdivision 	= $(this).attr("data-subdivision");
	var name 			= $(this).attr("data-name");
	var address 		= $(this).attr("data-address");
	var sanction 		= $(this).attr("data-sanction");
	var exist_capacity = $(this).attr("data-existingCapacity");
	$("#Discom_application_id").val(application_id);
	$("#area_id").html(area);
	$("#circle_id").html(circle);
	$("#division_id").html(division);
	$("#subdivision_id").html(subdivision);
	$("#name_id").html(name);
	$("#address_id").html(address);
	$("#sanction_id").html(sanction);
	$("#existing_id").html(exist_capacity);
	$("#fetch_data").show();
	$("#new_circle_id").val('');
	$("#new_division_id").val('');
	$("#new_subdivision_id").val('');
	$("#circle_new").html('');
	$("#division_new").html('');
	$("#subdivision_new").html('');
	$("#new_third_name").val('');
	$("#new_last_name").val('');
	$("#new_name_applicant").val('');
	$("#new_address").val('');
	$("#new_existing_capacity").val('');
	if(exist_capacity > 0) {
		$("#row_existing_id").show();
		$("#row_new_existing_id").show();
	} else {
		$("#row_existing_id").hide();
		$("#row_new_existing_id").hide();
	}

	$("#fetch_data").show();
	$("#login_btn_discom").hide();
});
$(".fetch_latest_data").click(function(){
	var fromobj = $(this).attr("data-form-name");
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-success");
	$.ajax({
		  type: "POST",
		  url: "/ApplyOnlines/fetchLatestConsumerData",
		  data: $("#"+fromobj).serialize(),
		  success: function(response) {
			var result = $.parseJSON(response);
			console.log(result);
			if (result.type == "error") {
				$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#"+fromobj).find("#messageBox").html(result.msg);
			} else {
				if(result.result['circle']!='') {
					$("#fetch_data").hide();
					$("#login_btn_discom").show();
				}
				
				$("#circle_new").html(result.result['circle']);
				$("#division_new").html(result.result['division']);
				$("#subdivision_new").html(result.result['subdivision']);
				$("#new_area_id").val(result.result['area_id']);
				$("#new_circle_id").val(result.result['circle_id']);
				$("#new_division_id").val(result.result['division_id']);
				$("#new_subdivision_id").val(result.result['subdivision_id']);
				$("#new_third_name").val(result.result['last_name']);
				$("#new_last_name").val(result.result['middle_name']);
				$("#new_name_applicant").val(result.result['first_name']);
				$("#new_address").val(result.result['address1']);
				$("#new_sanction_load").val(result.result['sanction_load']);
				if(result.result['is_enhancement'] == 1) {
					$("#new_existing_capacity").val(result.result['installed_capacity']);
				} else {
					$("#new_existing_capacity").val('');
				}
				$("#name_new").html(result.result['first_name']+" "+result.result['middle_name']+" "+result.result['last_name']);
				$("#address_new").html(result.result['address1']);
				$("#sanction_new").html(result.result['sanction_load']);
				$("#existing_new").html(result.result['installed_capacity']);
			}
		}
	});
	return false;
});
$(".change_discom").click(function(){
	var fromobj = $(this).attr("data-form-name");
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-success");
	$.ajax({
		  type: "POST",
		  url: "/ApplyOnlines/changediscomdata",
		  data: $("#"+fromobj).serialize(),
		  success: function(response) {
			var result = $.parseJSON(response);
			if (result.type == "error") {
				$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
				$("#"+fromobj).find("#messageBox").html(result.msg);
			} else {
				$("#"+fromobj).find("#messageBox").addClass("alert alert-success");
				$("#"+fromobj).find("#messageBox").html(result.msg);
				window.location.reload();
			}
		}
	});
	return false;
});
$(".Varify_Otp").click(function(){
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var application_id = $(this).attr("data-id");
	$("#VarifyOtp_application_id").val(application_id);
});
$(".approval_btn").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find(".application_status").val() == 2 && reason.length < 1) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("");
		$("#"+fromobj).find("#messageBox").html("Reason is required field.");
		return false;
	} else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			  }
		});
	}
	return false;
});


$(".approval_btn_cei").click(function(){
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find("#drawing_app_no").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	}
	else if($("#"+fromobj).find("#drawing_app_status").val() == '')
	{
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
		return false;
	}
	else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			  }
		});
	}
	return false;
});
$(".SendMessage").click(function(){
	var application_id = $(this).attr("data-id");
	$("#SendMessage_application_id").val(application_id);
});
$(".delete_application").click(function(){
	var application_id = $(this).attr("data-id");
	$("#Delete_application_id").val(application_id);
});
$(".delete_application_request").click(function(){
	var application_id = $(this).attr("data-id");
	//var reason = $("#"+fromobj).find(".reason").val();
	
	
	$("#Delete_application_req_id").val(application_id);
	$("#delete_request_id").val('');
	$("#consent-not-available-0").prop("checked", true);
	$("#consent-not-available-1").prop("checked", false);
	$("#consent-not-available-2").prop("checked", false);
	$("#consumer_consent_letter_view").html('');
	$("#vendor_consent_letter_view").html('');
	$("#DeleteApplicationRequestForm").find("#messagebox").val('');
	$("#delete_request_id").val('');
	$.ajax
	({
		type: "POST",
		url: "/ApplyOnlines/fetchDeleteAppRequest",
		data: {'application_id':application_id},
		success: function(response) {
			var result = $.parseJSON(response);
			if (result.type == "ok")
			{
				console.log(result.response['reject_reason']);
				if(result.response['id'] != '' && result.response['status']!=2) {
					$("#consumer_consent_letter_view").html(result.response['consumer_consent_letter']);
					$("#vendor_consent_letter_view").html(result.response['vendor_consent_letter']);
					$("#DeleteApplicationRequestForm").find("#messagebox").val(result.response['reason']);
					if(result.response['consent_not_available'] == 1) {
						$("#consent-not-available-1").prop("checked", true);
					} else if(result.response['consent_not_available'] == 2) {
						$("#consent-not-available-2").prop("checked", true);
					}
					else {
						$("#consent-not-available-0").prop("checked", true);
					}
					$("#delete_request_id").val(result.response['id']);
				}
			}
		}
	});
});
$(".pre_delete_application_request").click(function(){
	var application_id = $(this).attr("data-id");
	//var reason = $("#"+fromobj).find(".reason").val();
	
	
	$("#pre_Delete_application_req_id").val(application_id);
});
$(".ReplayMessage").click(function(){
	var applicationid = $(this).attr("data-id");
	$("#ReplayMessage_application_id").val(applicationid);
	$("#reply_message").html($("#send_application_msg_"+applicationid).html());
});
$(".uploaddocument").click(function(){
	var application_id = $(this).attr("data-id");
	$("#upload_application_id").val(application_id);
});
$(".removeCommonMeter").click(function(){
	var application_id = $(this).attr("data-id");
	$("#meter_application_id").val(application_id);
});
$(".approvedpayment").click(function(){
	var application_id = $(this).attr("data-id");
	$("#payment_application_id").val(application_id);
});
$(".approvegeda").click(function(){
	var application_id = $(this).attr("data-id");
	$("#geda_application_id").val(application_id);
});
$(".reopenApplication").click(function(){
	var application_id = $(this).attr("data-id");
	$("#reopen_application_id").val(application_id);
});
$(".resetApplication").click(function(){
	var application_id = $(this).attr("data-id");
	$("#reset_application_id").val(application_id);
});
$(".selfcertificate").click(function(){
	var application_id = $(this).attr("data-id");
	$("#selfcertificate_application_id").val(application_id);
});
$(".otherdocument").click(function(){
	var application_id = $(this).attr("data-id");
	$("#other_application_id").val(application_id);
});
$(".uploadUndertaking").click(function(){
	var application_id 	= $(this).attr("data-id");
	var title_modal 	= $(this).attr("data-title");
	var show_text 		= $(this).attr("data-showtext");
	$("#undertaking_application_id").val(application_id);
	$(".title_upload_undertaking").html(title_modal);
	if(show_text == 1) {
		$(".text_upload_undertaking").removeClass('hide');
	} else {
		$(".text_upload_undertaking").addClass('hide');
	}
});
$(".ViewMessage").click(function(){
	var application_id = $(this).attr("data-id");
	$.ajax({
			type: "POST",
			url: "/apply-onlines/GetMessages/"+$(this).attr("data-id"),
			success: function(response) {
				var result = $.parseJSON(response);
				if (result.html != '') {
					$("#ViewMessage").find(".modal-body").html(result.html);
				}
			}
		});
});
$(".ViewLastResponse").click(function(){
	var application_id = $(this).attr("data-id");
	$("#ViewLastResponse").find(".modal-body").html('');
	$.ajax({
			type: "POST",
			url: "/ApplyOnlines/GetLastResponse/"+$(this).attr("data-id"),
			success: function(response) {
				var result = $.parseJSON(response);
				if (result.html != '') {
					$("#ViewLastResponse").find(".modal-body").html(result.html);
				}
			}
		});
});
$(".ViewCustomerResponse").click(function(){
	var application_id = $(this).attr("data-id");
	$("#ViewCustomerResponse").find(".modal-body").html('');
	$.ajax({
			type: "POST",
			url: "/ApplyOnlines/GetCustomerResponse/"+$(this).attr("data-id"),
			success: function(response) {
				var result = $.parseJSON(response);
				if (result.html != '') {
					$("#ViewCustomerResponse").find(".modal-body").html(result.html);
				}
			}
		});
});
$(".sendmessage_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Message is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/apply-onlines/SendMessage",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#SendMessageForm").find(".messagebox").val('');
						$("#SendMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#SendMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".deleteapplication_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/RemoveApplicationMember",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#DeleteApplicationForm").find(".messagebox").val('');
						$("#DeleteApplicationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#DeleteApplicationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".deleteapp_request_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	var form = $('#'+fromobj);
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }

	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".messagebox").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Reason is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/DeleteApplicationRequest",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
						$("#"+fromobj).find(".messagebox").val('');
						$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						if(result.message == 'login') {
							window.location.reload();
						} else {
							$("#"+fromobj).find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						}
						
					}
				}
			});
	}
});

$(".delete_application_popup").click(function(){
	$("#pre_delete_application_request").modal('hide');
	$('.delete_application_request[data-id="' + $("#pre_Delete_application_req_id").val() + '"]').trigger('click');
});
$(".varifyotp_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$("#VarifyOtpForm").find("#message_error").html('');
	var otp_data = $("#otp").val();
	if (otp_data.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("OTP is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/VarifyOtp",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#VarifyOtpForm").find("#otp_data").val('');
						$("#VarifyOtpForm").find("#message_error").removeClass('alert-danger');
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						$("#Varify_Otp").modal('hide');
						window.location.reload();
					} else {
						$("#VarifyOtpForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$("input[name='application_status']").change(function(){
	if($(this).val()==3) {
		$("#member_assign_id").val("<?php echo $CEI; ?>");
		// status for for application
	} else {
		$("#member_assign_id").val("<?php echo $JREDA; ?>");
	}
	$(".application_status_message").html(status_messages[parseInt($(this).val())]);
});
$(function () {
  $('[data-toggle="popover"]').popover();
})
$("#fetch_status_drawing").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var drawing_number  = $("#"+fromobj).find("#drawing_app_no_val").val();
	var app_id          = $("#"+fromobj).find("#DRAW_application_id").val();
	if ($("#"+fromobj).find("#drawing_app_no_val").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	}
	else
	{
		$.ajax
		({
			type: "POST",
			url: "/ApplyOnlines/fetch_status_api",
			data: {'drawing_number':drawing_number,'app_id':app_id,'api_type':'drawing'},
			success: function(response) {
			var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				else
				{
					$("#"+fromobj).find("#drawing_app_status_html_frm").html(result.response);
					$("#"+fromobj).find("#drawing_app_status_frm").val(result.response);
				}
			}
		});
	}

});
$("#fetch_status_cei").click(function(){
	var fromobj = $(this).attr("data-form-name");
	var cei_number  = $("#"+fromobj).find("#cei_app_no_val").val();
	var app_id          = $("#"+fromobj).find("#CEI_form_application_id").val();
	if ($("#"+fromobj).find("#cei_app_no").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("Application Ref No. is required.");
		return false;
	}
	else
	{
		$.ajax
		({
			type: "POST",
			url: "/ApplyOnlines/fetch_status_api",
			data: {'cei_number':cei_number,'app_id':app_id,'api_type':'cei'},
			success: function(response) {
			var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				else
				{
					$("#"+fromobj).find("#cei_app_status_html_frm").html(result.response);
					$("#"+fromobj).find("#cei_app_status_frm").val(result.response);
				}
			}
		});
	}
});
$(".approval_btn_drawing").click(function(){
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if ($("#"+fromobj).find("#drawing_app_no").val() == '' ) {
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("CEI Drawing application number is required.");
		return false;
	}
	else if($("#"+fromobj).find("#drawing_app_status").val() == '')
	{
		$("#"+fromobj).find("#messageBox").addClass("alert alert-danger");
		$("#"+fromobj).find("#messageBox").html("Click on fetch status for drawing application status.");
		return false;
	}
	else {
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {
					$("#assign_division_message").addClass("alert alert-error");
					$("#assign_division_message").html(result.msg);
				}
				window.location.reload();
			  }
		});
	}
	return false;
});

$(".approval_btn_claim").click(function(){
	var fromobj = $(this).attr("data-form-name");
	//var reason = $("#"+fromobj).find(".reason").val();
	$("#"+fromobj).find("#messageBox").html("");
	$("#"+fromobj).find("#messageBox").removeClass("alert alert-danger");
	if(window.confirm('Are you sure want to claim Subsidy?'))
	{
		$.ajax({
			  type: "POST",
			  url: "/apply-onlines/inspectionstage",
			  data: $("#"+fromobj).serialize(),
			  success: function(response) {
				var result = $.parseJSON(response);
				if (result.type == "error") {

				}
				window.location.reload();
			  }
		});
	}
	return false;
});
$(".replaymessage_btn").click(function() {
	var fromobj = $(this).attr("data-form-name");
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	var messagebox = $("#"+fromobj).find(".message").val();
	if (messagebox.length < 1) {
		$("#"+fromobj).find("#message_error").html("");
		$("#"+fromobj).find("#message_error").addClass("alert alert-danger");
		$("#"+fromobj).find("#message_error").html("Message is required field.");
		$("#"+fromobj).find("#message_error").removeClass("hide");
		return false;
	} else {
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ReplayMessage",
				data: $("#"+fromobj).serialize(),
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ReplayMessageForm").find(".message").val('');
						$("#ReplayMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						window.location.reload();
					} else {
						$("#ReplayMessageForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
	}
});
$(".uploaddocument_btn").click(function() {
	var form = $('#UploadDocumentForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$(".uploaddocument_btn").attr('disabled','disabled');
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/UploadDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#UploadDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#UploadDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".uploaddocument_btn").removeAttr('disabled');
					}

				}
			});

});
$(".uploadUndertaking_btn").click(function() {
	var form = $('#uploadUndertakingForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$(".uploadUndertaking_btn").attr('disabled','disabled');
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/UploadUndertaking",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#uploadUndertakingForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#uploadUndertakingForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".uploadUndertaking_btn").removeAttr('disabled');
					}

				}
			});

});
$(".removeCommonMeter_btn").click(function() {
	var form = $('#RemoveCommonForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
	$(".removeCommonMeter_btn").attr('disabled','disabled');
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/RemoveCommonMeter",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.type == 'ok') {
						$("#RemoveCommonForm").find("#message_error").html(result.response).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#RemoveCommonForm").find("#message_error").html(result.response).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".removeCommonMeter_btn").removeAttr('disabled');
					}

				}
			});

});
$(".approvepayment_btn").click(function() {
	var form = $('#ApprovePaymentForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ApprovePayment",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ApprovePaymentForm").find(".message").val('');
						$("#ApprovePaymentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ApprovePaymentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});

$(".selfcertificate_btn").click(function() {
	var form = $('#SelfCertificationForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	}
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/SelfCertification",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#SelfCertificationForm").find(".message").val('');
						$("#SelfCertificationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#SelfCertificationForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".approvegeda_btn").click(function() {
	var form = $('#ApproveGedaForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ApproveGeda",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ApproveGedaForm").find(".message").val('');
						$("#ApproveGedaForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ApproveGedaForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".reopenApplication_btn").click(function() {
	var form = $('#ReopenForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ReopenApplication",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ReopenForm").find(".message").val('');
						$("#ReopenForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ReopenForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".resetApplication_btn").click(function() {
	var form = $('#ResetForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ResetApplication",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#ResetForm").find(".message").val('');
						$("#ResetForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#ResetForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
					}
				}
			});
});
$(".otherdocument_btn").click(function() {
	var form = $('#OtherDocumentForm');
	var formdata = false;
	if (window.FormData) {
		formdata = new FormData(form[0]);
	 }
	$("#message_error").addClass("hide").removeClass("alert").removeClass("alert-success").removeClass("alert-error");
		$.ajax({
				type: "POST",
				url: "/ApplyOnlines/OtherDocument",
				data: formdata ? formdata : form.serialize(),
				cache: false,
				contentType: false,
				processData: false,
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						$("#OtherDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-success');
						location.reload();
					} else {
						$("#OtherDocumentForm").find("#message_error").html(result.message).removeClass("hide").addClass('alert').addClass('alert-danger');
						$(".otherdocument_btn").removeAttr('disabled');
					}
				}
			});

});

function removeapp(id){
	var application_id = id;
	swal({
  title: "Are you sure?",
  text: "You want to Re-open the file?",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Re-open it!",
  cancelButtonText: "No, cancel plx!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
	$.ajax({
				type: "POST",
				url: "/ApplyOnlines/ResetApplication",
				data: {'reset_application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Re-opened!", "Your Application file has been reopen.", "success");
						   window.location.reload();
					}
				}

				/*url: "/ApplyOnlines/RemoveApplication",
				data: {'application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Deleted!", "Your Application file has been deleted.", "success");
						   window.location.reload();
					}
				}*/
			});

  } else {
	swal("Cancelled", "Your Application file is safe :)", "error");
  }
});
}
function recallmeter(id){
	var application_id = id;
	swal({
  title: "Are you sure?",
  text: "You want to Recall Meter API?",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Re-call it!",
  cancelButtonText: "No, cancel!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
	$.ajax({
				type: "POST",
				url: "/ApplyOnlines/RecallMeter",
				data: {'recall_aplication_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Re-call!", "Application has been applied for Recall Meter API.", "success");
						   window.location.reload();
					}
				}

				/*url: "/ApplyOnlines/RemoveApplication",
				data: {'application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Deleted!", "Your Application file has been deleted.", "success");
						   window.location.reload();
					}
				}*/
			});

  } else {
	swal("Cancelled", "Your Application file is safe :)", "error");
  }
});
}

function deleteapp(id){
	var application_id = id;
	swal({
  title: "Are you sure?",
  text: "You want to delete the file?",
  type: "warning",
  showCancelButton: true,
  confirmButtonClass: "btn-danger",
  confirmButtonText: "Yes, Delete it!",
  cancelButtonText: "No, cancel please!",
  closeOnConfirm: false,
  closeOnCancel: false
},
function(isConfirm) {
  if (isConfirm) {
	$.ajax({
				type: "POST",
				url: "/ApplyOnlines/RemoveApplication",
				data: {'application_id':application_id},
				success: function(response) {
					var result = $.parseJSON(response);
					console.log(result.success);
					if (result.success == 1) {
						   swal("Deleted!", "Your Application file has been deleted.", "success");
						   window.location.reload();
					}
				}
			});

  } else {
	swal("Cancelled", "Your Application file is safe :)", "error");
  }
});
}

$(".showModel").click(function(){
	var modelheader = $(this).data("title");
	var modelUrl = $(this).data("url");
	document_window = $(window).width() - $(window).width()*0.05;
	document_height = $(window).height() - $(window).height() * 0.20;
	modal_body = '<div class="modal-header" style="min-height: 45px;">'+
	'<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
	'</div>'+
	'<div class="modal-body">'+
	'<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
	'</div>';

	$('#myModal').find(".modal-content").html(modal_body);
	$('#myModal').modal('show');
	$('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
	$('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
	return false;
});
window.closeModal = function(){ $('#myModal').modal('hide'); };

function download_app(){
	<?php
	if($is_member == false)
	{
		?>
		alert("ATTENTION : The SIGNATURE in the Application Uploaded at the time of registeration and that for all documents at the time of subsidy claim processing MUST be same.\n 1. It is strongly advised to review the details of AC and DC Capacity, amount to paid for the GEDA registration and other application details before submission of the application. The capacity can’t be changed once the application is submitted.\n 2. In order to change the Installer has to delete the application and re-apply. In such cases of cancellation or deletion of the application, there shall be no refund of charges paid at GEDA.\n 3. It is suggested to carefully look into the details before submission of the application and uploading the Signed Application Document.");
		<?php
	}
	?>
}
$(document).ready(function() {
	$('.chosen-select').chosen();
	$('.chosen-select-deselect').chosen({ allow_single_deselect: true });
});
</script>
