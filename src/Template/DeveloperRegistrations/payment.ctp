<script src="/plugins/bootstrap-fileinput/fileinput.js"></script>
<link rel="stylesheet" href="/plugins/bootstrap-fileinput/fileinput.css">
<?php
	$this->Html->addCrumb($pageTitle); 
?>
<style type="text/css">
 /* http://docs.jquery.com/UI/Autocomplete#theming*/
.ui-autocomplete { position: absolute; cursor: default; background:#CCC }   

/* workarounds */
html .ui-autocomplete { width:1px; } /* without this, the menu expands to 100% in IE6 */
.ui-menu {
	list-style:none;
	padding: 2px;
	margin: 0;
	display:block;
	float: left;
}
.ui-menu .ui-menu {
	margin-top: -3px;
}
.ui-menu .ui-menu-item {
	margin:0;
	padding: 0;
	zoom: 1;
	float: left;
	clear: left;
	width: 100%;
}
.ui-menu .ui-menu-item a {
	text-decoration:none;
	display:block;
	padding:.2em .4em;
	line-height:1.5;
	zoom:1;
}
.ui-menu .ui-menu-item a.ui-state-hover,
.ui-menu .ui-menu-item a.ui-state-active {
	font-weight: normal;
	margin: -1px;
}
.check-box-address{
	margin-top: 20px !important;
}
.checkbox input[type="checkbox"]{
    width: 18px;
    float: left;
    margin-top: -37px !important;
}
</style>
	<div class="container">
		<div class="row">
			<div class="col-md-12"><h4><strong><?php echo $pageTitle;?></strong></h4></div>
		</div>
		<div class="content">
			<div class="row portlet box blue-madison applyonline-viewmain" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
				<div class="col-md-12 portlet-body form">
					<div class="form-body">
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<div class="greenbox">
			                            <h4>Developer Details</h4>
			                        </div>
			                    </div>
			                </div>
							<div class="form-group">
								<div class="col-md-12">
									<label>Name of RE Developer </label>
									<?php echo $installerData->installer_name;?>
								</div>
								
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>Mobile No</label>
									<?php echo $installerData->mobile;?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>Email </label>
									<?php echo $installerData->email;?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>GST No. </label>
									<?php echo $installerData->GST;?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>PAN Number </label>
									<?php echo $installerData->pan;?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<label>Office Address </label>
									<?php echo $installerData->address;?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="form-group">
								<div class="col-md-12">
									<div class="greenbox">
			                            <h4>Payment Details</h4>
			                        </div>
			                    </div>
			                </div>
			                <div class="form-group">
								<div class="col-md-12">
									<table class="table-responsive table-bordered"> 
		                                <tbody>
		                                    <tr>
		                                        <td>Processing Fee</td>
		                                        <td><?php echo number_format($installerData->developer_fee,2); ?></td>
		                                    </tr>
		                                    <tr>
		                                        <td>GST at 18%</td>
		                                        <td><?php echo number_format($installerData->gst_fees,2); ?></td>
		                                    </tr>
		                                    <tr>
		                                        <td><b>Total Fee</b></td>
		                                        <td><b><?php echo number_format($installerData->developer_total_fee,2);?></b></td>
		                                    </tr>
		                                </tbody>
	                            	</table>
	                            	<br>
	                            	<?php if($installerData->payment_status != 1) { ?>
		                            	<a href="/developerPayment/make-payment/<?php echo encode($installerData->id);?>">
											<span class="next btn btn-primary btn-lg mb-xlg ">
												<i class="fa fa-rupee"></i> Pay Registration Fee
											</span>
										</a>
									<?php  } ?>
								</div>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script language="javascript">

</script>
