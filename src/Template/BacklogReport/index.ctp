<?php
	$this->Html->addCrumb($pageTitle);
?>
<style type="text/css">
	fieldset
	{
		border: 1px solid #ddd !important;
		margin: 0;
		xmin-width: 0;
		padding: 10px;
		position: relative;
		border-radius:4px;
		background-color:#f5f5f5;
		padding-left:10px!important;
	}
	legend
	{
		font-size:14px;
		font-weight:bold;
		margin-bottom: 0px;
		width: 35%;
		border: 1px solid #ddd;
		border-radius: 4px;
		padding: 5px 5px 5px 10px;
		background-color: #ffffff;
	}
	#DisplayMonthwiseStats {
		display: block;
		margin: 0 auto;
	}
	#DisplayDaywiseCapacityStats {
		display: block;
		margin: 0 auto;
	}
	#DisplayDaywiseApplicationStats {
		display: block;
		margin: 0 auto;
	}
	.class_childtd {
		border-top: 1px solid white !important;
		background-color:#71bf57 !important;
		color:#ffffff;
	}
	.table thead tr th {
	    font-size: 10px !important;
	}
	.table {
		font-size: 10px !important;
	}
</style>
<?php /*
border-radius: 20px 0px !important;
*/?>
<div class="container">
	<div class="alert alert-info" role="alert" id="pre_message" style="margin-top: 10px; ">
		<strong>Note:</strong> A - Up to 04 Days, B - 05 Days to 15 Days, C - 16 Days to 45 Days, D - 46 Days to 90 Days, E - More than 90 Days
	</div>
	<div class="col-md-12">
		<div class="table-responsive portlet box blue-madison noborder">
			<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
				<thead>
					<tr>
						<th class="" rowspan="2" width="10%">Scheme Name</th>
						<th class="" rowspan="2" style="text-align: left;width: 30% !important;">Discom Name</th>
						<th class="" colspan="6">Backlog in Doc. Verification for Rooftop Solar Application under Query Agency Side</th>
						<th class="" colspan="6">Backlog in Doc. Verification</th>
						<th class="" colspan="6">Backlog in Issue of F.Q.(Estimate) after Documents are Verified </th>
						<th class="" colspan="6">Backlog in Verification of System installed </th>
						<th class="" colspan="6">Backlog in providing Bi-Directional Meter after intimation Approved</th>
						<th class="" rowspan="2">Grand Total</th>
					</tr>
					<tr class="text-bold">	
						<th class="text-center class_childtd" style="">A</th>
						<th class="text-center class_childtd" style="">B</th>
						<th class="text-center class_childtd" style="">C</th>
						<th class="text-center class_childtd" style="">D</th>
						<th class="text-center class_childtd" style="">E</th>
						<th class="text-center class_childtd" style="">Total</th>
						<th class="text-center class_childtd" style="">A</th>
						<th class="text-center class_childtd" style="">B</th>
						<th class="text-center class_childtd" style="">C</th>
						<th class="text-center class_childtd" style="">D</th>
						<th class="text-center class_childtd" style="">E</th>
						<th class="text-center class_childtd" style="">Total</th>
						<th class="text-center class_childtd" style="">A</th>
						<th class="text-center class_childtd" style="">B</th>
						<th class="text-center class_childtd" style="">C</th>
						<th class="text-center class_childtd" style="">D</th>
						<th class="text-center class_childtd" style="">E</th>
						<th class="text-center class_childtd" style="">Total</th>
						<th class="text-center class_childtd" style="">A</th>
						<th class="text-center class_childtd" style="">B</th>
						<th class="text-center class_childtd" style="">C</th>
						<th class="text-center class_childtd" style="">D</th>
						<th class="text-center class_childtd" style="">E</th>
						<th class="text-center class_childtd" style="">Total</th>
						<th class="text-center class_childtd" style="">A</th>
						<th class="text-center class_childtd" style="">B</th>
						<th class="text-center class_childtd" style="">C</th>
						<th class="text-center class_childtd" style="">D</th>
						<th class="text-center class_childtd" style="">E</th>
						<th class="text-center class_childtd" style="">Total</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$total_application  = array();
					$total_capacity     = 0;
					$srNo   			= 1;
					$total_scheme_sum 	= array();
					$grandTotal 		= array();
						foreach($result['arrOutput'] as $schemeID=>$val)
						{
							?>
							<tr>
								<td rowspan="<?php echo count($val)+1;?>">
									<?php echo isset($schemeArr[$schemeID]) ? $schemeArr[$schemeID] : '';?>
								</td>
							</tr>
							<?php
							foreach($val as $discomId=>$detailsData)
							{
								$branchArr 	= $result['branchArr'];
								?>
								<tr>
									<td style="text-align: left;"><?php echo isset($branchArr[$discomId]) ? $branchArr[$discomId] : '';?></td>
									<?php if(!empty($detailsData['query_agency_side'])) {
										$sum_query 			= 0;
										foreach($detailsData['query_agency_side'] as $k_data=>$v_data) { ?>
											<td>
												<?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/q_'.$schemeID.'_'.$discomId.'_'.$k_data.'">'.$v_data.'</a>';  
												$sum_query										= $sum_query + $v_data;
												if(!isset($total_scheme_sum[$schemeID.'_q_'.$k_data])) {
													$total_scheme_sum[$schemeID.'_q_'.$k_data]	= $v_data;
												} else  {
													$total_scheme_sum[$schemeID.'_q_'.$k_data]	= $total_scheme_sum[$schemeID.'_q_'.$k_data] + $v_data;
												}
												?>	
											</td>
										<?php } ?>
										<td><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/qt_'.$schemeID.'_'.$discomId.'_0">'.$sum_query.'</a>';?></td>
									<?php } if(!empty($detailsData['doc_pending'])) {
										$sum_doc_pending 		= 0;
										foreach($detailsData['doc_pending'] as $k_data=>$v_data) { ?>
											<td>
												<?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/d_'.$schemeID.'_'.$discomId.'_'.$k_data.'">'.$v_data.'</a>'; 
												$sum_doc_pending	= $sum_doc_pending+$v_data;
												if(!isset($total_scheme_sum[$schemeID.'_d_'.$k_data])) {
													$total_scheme_sum[$schemeID.'_d_'.$k_data]	= $v_data;
												} else  {
													$total_scheme_sum[$schemeID.'_d_'.$k_data]	= $total_scheme_sum[$schemeID.'_d_'.$k_data] + $v_data;
												}
												?>
											</td>
										<?php }  ?>
										<td><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/dt_'.$schemeID.'_'.$discomId.'_0">'.$sum_doc_pending.'</a>';?></td>
									<?php } ?>
									<?php if(!empty($detailsData['feasibility_pending'])) {
										$sum_feasibility_pending 	= 0;
										foreach($detailsData['feasibility_pending'] as $k_data=>$v_data) { ?>
											<td>
												<?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/f_'.$schemeID.'_'.$discomId.'_'.$k_data.'">'.$v_data.'</a>'; 
												$sum_feasibility_pending	= $sum_feasibility_pending+$v_data;
												if(!isset($total_scheme_sum[$schemeID.'_f_'.$k_data])) {
													$total_scheme_sum[$schemeID.'_f_'.$k_data]	= $v_data;
												} else  {
													$total_scheme_sum[$schemeID.'_f_'.$k_data]	= $total_scheme_sum[$schemeID.'_f_'.$k_data] + $v_data;
												}
												?>
											</td>
										<?php }  ?>
										<td><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/ft_'.$schemeID.'_'.$discomId.'_0">'.$sum_feasibility_pending.'</a>';?></td>
									<?php } 
									if(!empty($detailsData['intimation_approval_pending'])) {
										$sum_intimation_approval_pending 	= 0;
										foreach($detailsData['intimation_approval_pending'] as $k_data=>$v_data) { ?>
											<td>
												<?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/iap_'.$schemeID.'_'.$discomId.'_'.$k_data.'">'.$v_data.'</a>'; 
												$sum_intimation_approval_pending	= $sum_intimation_approval_pending+$v_data;
												if(!isset($total_scheme_sum[$schemeID.'_iap_'.$k_data])) {
													$total_scheme_sum[$schemeID.'_iap_'.$k_data]	= $v_data;
												} else  {
													$total_scheme_sum[$schemeID.'_iap_'.$k_data]	= $total_scheme_sum[$schemeID.'_iap_'.$k_data] + $v_data;
												}
												?>
											</td>
										<?php }  ?>
										<td><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/iapt_'.$schemeID.'_'.$discomId.'_0">'.$sum_intimation_approval_pending.'</a>';?></td>
									<?php } 
									if(!empty($detailsData['meter_pending'])) {
										$sum_meter_pending 	= 0;
										foreach($detailsData['meter_pending'] as $k_data=>$v_data) { ?>
											<td>
												<?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/m_'.$schemeID.'_'.$discomId.'_'.$k_data.'">'.$v_data.'</a>';  
												$sum_meter_pending	= $sum_meter_pending+$v_data;
												if(!isset($total_scheme_sum[$schemeID.'_m_'.$k_data])) {
													$total_scheme_sum[$schemeID.'_m_'.$k_data]	= $v_data;
												} else  {
													$total_scheme_sum[$schemeID.'_m_'.$k_data]	= $total_scheme_sum[$schemeID.'_m_'.$k_data] + $v_data;
												}
												?>
											</td>
										<?php }  ?>
										<td><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/mt_'.$schemeID.'_'.$discomId.'_0">'.$sum_meter_pending.'</a>';?></td>
									<?php } ?>
									<td><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/colDiscom_'.$schemeID.'_'.$discomId.'_0">'.($sum_query + $sum_doc_pending + $sum_feasibility_pending + $sum_intimation_approval_pending + $sum_meter_pending).'</a>';?></td>
									
								</tr>
								<?php
							} ?>
							<tr>
								<td style="text-align: left;" colspan="2"><strong><?php echo isset($schemeArr[$schemeID]) ? $schemeArr[$schemeID] : '';?></strong></td>
								<?php 
								
								if(!empty($detailsData['query_agency_side'])) {
									$total_scheme_sum_q 	= 0;
									foreach($detailsData['query_agency_side'] as $k_data=>$v_data) { ?>
										<td>
											<strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/q_'.$schemeID.'_0_'.$k_data.'">'.$total_scheme_sum[$schemeID.'_q_'.$k_data].'</a>';
											$total_scheme_sum_q 		= $total_scheme_sum_q + $total_scheme_sum[$schemeID.'_q_'.$k_data];
											if(!isset($grandTotal['_q_'.$k_data])) {
												$grandTotal['_q_'.$k_data]	= $total_scheme_sum[$schemeID.'_q_'.$k_data];
											} else {
												$grandTotal['_q_'.$k_data]	= $grandTotal['_q_'.$k_data] + $total_scheme_sum[$schemeID.'_q_'.$k_data];
											}
											?></strong>	
										</td>
									<?php } ?>
									<td><strong><?php echo  '<a href="'.URL_HTTP.'/BacklogReport/download/schemeqt_'.$schemeID.'_0_0">'.$total_scheme_sum_q.'</a>'; 
									//$grandTotal['_q_total']	= $grandTotal['_q_total'] + $total_scheme_sum_q;
									?></strong></td>
								<?php } if(!empty($detailsData['doc_pending'])) {
									$total_scheme_sum_d 	= 0;
									foreach($detailsData['doc_pending'] as $k_data=>$v_data) { ?>
										<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/d_'.$schemeID.'_0_'.$k_data.'">'.$total_scheme_sum[$schemeID.'_d_'.$k_data].'</a>';
											$total_scheme_sum_d = $total_scheme_sum_d + $total_scheme_sum[$schemeID.'_d_'.$k_data]; 
											if(!isset($grandTotal['_d_'.$k_data])) {
												$grandTotal['_d_'.$k_data]	= $total_scheme_sum[$schemeID.'_d_'.$k_data];
											} else {
												$grandTotal['_d_'.$k_data]	= $grandTotal['_d_'.$k_data] + $total_scheme_sum[$schemeID.'_d_'.$k_data];
											}
											?></strong>
										</td>
									<?php }  ?>
									<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/schemedt_'.$schemeID.'_0_0">'.$total_scheme_sum_d.'</a>';?></strong></td>
								<?php } if(!empty($detailsData['feasibility_pending'])) {
									$total_scheme_sum_f 	= 0;
									foreach($detailsData['feasibility_pending'] as $k_data=>$v_data) { ?>
										<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/f_'.$schemeID.'_0_'.$k_data.'">'.$total_scheme_sum[$schemeID.'_f_'.$k_data].'</a>';
											$total_scheme_sum_f = $total_scheme_sum_f + $total_scheme_sum[$schemeID.'_f_'.$k_data];
											if(!isset($grandTotal['_f_'.$k_data])) {
												$grandTotal['_f_'.$k_data]	= $total_scheme_sum[$schemeID.'_f_'.$k_data];
											} else {
												$grandTotal['_f_'.$k_data]	= $grandTotal['_f_'.$k_data] + $total_scheme_sum[$schemeID.'_f_'.$k_data];
											}
											?></strong>
										</td>
									<?php }  ?>
									<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/schemeft_'.$schemeID.'_0_0">'.$total_scheme_sum_f.'</a>';?></strong></td>
								<?php } if(!empty($detailsData['intimation_approval_pending'])) {
									$total_scheme_sum_iap 	= 0;
									foreach($detailsData['intimation_approval_pending'] as $k_data=>$v_data) { ?>
										<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/iap_'.$schemeID.'_0_'.$k_data.'">'.$total_scheme_sum[$schemeID.'_iap_'.$k_data].'</a>';
											$total_scheme_sum_iap 	= $total_scheme_sum_iap + $total_scheme_sum[$schemeID.'_iap_'.$k_data];
											if(!isset($grandTotal['_iap_'.$k_data])) {
												$grandTotal['_iap_'.$k_data]	= $total_scheme_sum[$schemeID.'_iap_'.$k_data];
											} else {
												$grandTotal['_iap_'.$k_data]	= $grandTotal['_iap_'.$k_data] + $total_scheme_sum[$schemeID.'_iap_'.$k_data];
											}
											?></strong>


										</td>
									<?php }  ?>
									<td><strong><?php echo  '<a href="'.URL_HTTP.'/BacklogReport/download/schemeiapt_'.$schemeID.'_0_0">'.$total_scheme_sum_iap.'</a>';?></strong></td>
								<?php } if(!empty($detailsData['meter_pending'])) {
									$total_scheme_sum_m 	= 0;
									foreach($detailsData['meter_pending'] as $k_data=>$v_data) { ?>
										<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/m_'.$schemeID.'_0_'.$k_data.'">'.$total_scheme_sum[$schemeID.'_m_'.$k_data].'</a>';
											$total_scheme_sum_m 	= $total_scheme_sum_m + $total_scheme_sum[$schemeID.'_m_'.$k_data];
											if(!isset($grandTotal['_m_'.$k_data])) {
												$grandTotal['_m_'.$k_data]	= $total_scheme_sum[$schemeID.'_m_'.$k_data];
											} else {
												$grandTotal['_m_'.$k_data]	= $grandTotal['_m_'.$k_data] + $total_scheme_sum[$schemeID.'_m_'.$k_data];
											} ?></strong>
										</td>
									<?php }  ?>
									<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/schememt_'.$schemeID.'_0_0">'.$total_scheme_sum_m.'</a>';?></strong></td>
								<?php } ?>
								<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/colDiscomSchemet_'.$schemeID.'_0_0">'.($total_scheme_sum_q + $total_scheme_sum_d + $total_scheme_sum_f + $total_scheme_sum_iap + $total_scheme_sum_m).'</a>';?></strong></td>
							</tr>
							<?php
							$srNo++;
						}

					?>
					<tr>
						<td style="text-align: left;" colspan="2"><strong>Grand Total</strong></td>
						<?php if(!empty($detailsData['query_agency_side'])) {
							$total_sum_q 			= 0;
							foreach($detailsData['query_agency_side'] as $k_data=>$v_data) { ?>
								<td>
									<strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/qschemeTotal_0_0_'.$k_data.'">'.$grandTotal['_q_'.$k_data].'</a>';
									$total_sum_q 	= $total_sum_q + $grandTotal['_q_'.$k_data];
									?></strong>	
								</td>
							<?php } ?>
							<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/qschemetTotal_0_0_0">'.$total_sum_q.'</a>';?></strong></td>
						<?php } if(!empty($detailsData['doc_pending'])) {
							$total_sum_d 			= 0;
							foreach($detailsData['doc_pending'] as $k_data=>$v_data) { ?>
								<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/dschemeTotal_0_0_'.$k_data.'">'.$grandTotal['_d_'.$k_data].'</a>';
									$total_sum_d 	= $total_sum_d + $grandTotal['_d_'.$k_data]; ?></strong>
								</td>
							<?php }  ?>
							<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/dschemetTotal_0_0_0">'.$total_sum_d.'</a>';?></strong></td>
						<?php } if(!empty($detailsData['feasibility_pending'])) {
							$total_sum_f 	= 0;
							foreach($detailsData['feasibility_pending'] as $k_data=>$v_data) { ?>
								<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/fschemeTotal_0_0_'.$k_data.'">'.$grandTotal['_f_'.$k_data].'</a>';
									$total_sum_f = $total_sum_f + $grandTotal['_f_'.$k_data]; ?></strong>
								</td>
							<?php }  ?>
							<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/fschemetTotal_0_0_0">'.$total_sum_f.'</a>';?></strong></td>
						<?php } if(!empty($detailsData['intimation_approval_pending'])) {
							$total_sum_iap 			= 0;
							foreach($detailsData['intimation_approval_pending'] as $k_data=>$v_data) { ?>
								<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/iapschemeTotal_0_0_'.$k_data.'">'.$grandTotal['_iap_'.$k_data].'</a>';
									$total_sum_iap 	= $total_sum_iap + $grandTotal['_iap_'.$k_data]; ?></strong>
								</td>
							<?php }  ?>
							<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/iapschemetTotal_0_0_0">'.$total_sum_iap.'</a>';?></strong></td>
						<?php } if(!empty($detailsData['meter_pending'])) {
							$total_sum_m 	= 0;
							foreach($detailsData['meter_pending'] as $k_data=>$v_data) { ?>
								<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/mschemeTotal_0_0_'.$k_data.'">'.$grandTotal['_m_'.$k_data].'</a>';
									$total_sum_m = $total_sum_m + $grandTotal['_m_'.$k_data]; ?></strong>
								</td>
							<?php }  ?>
							<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/mschemetTotal_0_0_0">'.$total_sum_m.'</a>';?></strong></td>
						<?php } ?>
						<td><strong><?php echo '<a href="'.URL_HTTP.'/BacklogReport/download/grandt_0_0_0">'.($total_sum_q + $total_sum_d + $total_sum_f + $total_sum_iap + $total_sum_m).'</a>';?></strong></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
