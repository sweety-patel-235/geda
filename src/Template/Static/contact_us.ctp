<?php
	$this->Html->addCrumb($pageTitle);
	$ALLOWED_IPS                = array("203.88.138.46");
	$IP_ADDRESS                 = (isset($this->request)?$this->request->clientIp():"");
	$ShowNodalAgencyTable       = (in_array($IP_ADDRESS,$ALLOWED_IPS)?true:true);
	echo $this->Form->create($contactusEntity,array('class'=>'form-horizontal','id'=>'contactForm'));
?>
<div class="container">
	<?php if ($ShowNodalAgencyTable) { ?>
	<div class="row">
		<div class="col-md-12">
			<h2 class="mb-sm mt-sm">List of Nodal Officer of DISCOMs for GEDA</h2>
		</div>
	</div>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet box blue-madison noborder table-responsive">
				<table class="table table-striped table-bordered table-hover custom-greenhead frontlayout-activesort" id="table-example">
					<thead>
						<tr>
							<th class="">Sr.No.</th>
							<th class="">Company</th>
							<th class="">Name of Nodal Officer</th>
							<th class="">Designation</th>
							<th class="">Nodal level</th>
							<th class="">Contact Number</th>
							<th class="">E-mail Address</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>1</td>
							<td rowspan="3">DGVCL</td>
							<td>CUSTOMER CARE</td>
							<td>Dy.Engg/Jr.Engg</td>
							<td>Level III</td>
							<td>1800 233 3003 & 19123</td>
							<td></td>
						</tr>
						<tr>
							<td>2</td>
							<td>Shri K B Vasava</td>
							<td>DE-Com</td>
							<td>Level II</td>
							<td>6357605313</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'decomm.dgvcl@gmail.com');?></td>
						</tr>
						<tr>
							<td>3</td>
							<td>Shri J S Kedaria</td>
							<td>Supt.Engg.</td>
							<td>Level I</td>
							<td>0261-2506176</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'secom.dgvcl@gmail.com');?></td>
						</tr>
						<tr>
							<td>4</td>
							<td rowspan="3">MGVCL</td>
							<td>CUSTOMER CARE</td>
							<td>Dy.Engg/Jr.Engg</td>
							<td>Level III</td>
							<td>1800 233 2670 & 19124</td>
							<td></td>
						</tr>
						<tr>
							<td>5</td>
							<td>N K Makwana</td>
							<td>Dy.Engg</td>
							<td>Level II</td>
							<td>6359960334</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'dedsm1.mgvcl@gebmail.com');?></td>
						</tr>
						<tr>
							<td>6</td>
							<td>A V SAXENA</td>
							<td>Supt.Engg.</td>
							<td>Level I</td>
							<td>6359960334</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'sedsm.mgvcl@gebmail.com');?></td>
						</tr>
						<tr>
							<td>7</td>
							<td rowspan="3">PGVCL</td>
							<td>CUSTOMER CARE</td>
							<td>Dy.Engg/Jr.Engg</td>
							<td>Level III</td>
							<td>1800 233 155333 & 19122</td>
							<td></td>
						</tr>
						<tr>
							<td>8</td>
							<td>Smt.D.A.Bhatt</td>
							<td>Dy.Engg</td>
							<td>Level II</td>
							<td>0281-2380425</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'dedsmcell.pgvcl@gebmail.com');?></td>
						</tr>
						<tr>
							<td>9</td>
							<td>H.M.Mankad</td>
							<td>Supt.Engg.</td>
							<td>Level I</td>
							<td>0281-2380427</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'ser_c.pgvcl@gebmail.com');?></td>
						</tr>
						<tr>
							<td>10</td>
							<td rowspan="3">UGVCL</td>
							<td>CUSTOMER CARE</td>
							<td>Dy.Engg/Jr.Engg</td>
							<td>Level III</td>
							<td>1800 233 155335 & 19121</td>
							<td></td>
						</tr>
						<tr>
							<td>11</td>
							<td>Shri K D Barot</td>
							<td>Dy.Engg</td>
							<td>Level II</td>
							<td>02762-222080</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'desolar1@ugvcl.com');?></td>
						</tr>
						<tr>
							<td>12</td>
							<td>Shri V H Thaker</td>
							<td>Supt.Engg.</td>
							<td>Level I</td>
							<td>02762-222081</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'sesolar@ugvcl.com');?></td>
						</tr>
						<tr>
							<td>13</td>
							<td rowspan="3">Torrent-Ahemedabad</td>
							<td>CUSTOMER CARE</td>
							<td>AM(DE)/AGM(SE)</td>
							<td>Level III</td>
							<td>1800 233 9129 & 19129</td>
							<td></td>
						</tr>
						<tr>
							<td>14</td>
							<td>Neeraj Tiwari</td>
							<td>AM(DE)</td>
							<td>Level II</td>
							<td>079 27492222 (Ext. 5934)</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'neerajtiwari@torrentpower.com');?></td>
						</tr>
						<tr>
							<td>15</td>
							<td>Nikhil Shah</td>
							<td>AGM(SE)</td>
							<td>Level I</td>
							<td>079 27492222 (Ext. 5952)</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'nikhilshah@torrentpower.com');?></td>
						</tr>
						<tr>
							<td>16</td>
							<td rowspan="3">Torrent-Surat</td>
							<td>CUSTOMER CARE</td>
							<td>AM(DE)/AGM(SE)</td>
							<td>Level III</td>
							<td>1800 233 9129 & 19128</td>
							<td></td>
						</tr>
						<tr>
							<td>17</td>
							<td>Nayan Bhadesia</td>
							<td>AM(DE)</td>
							<td>Level II </td>
							<td>0261 2400240 (Ext. 3447)</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'nayanbhadesia@torrentpower.com');?></td>
						</tr>
						<tr>
							<td>18</td>
							<td>Tejas Tailor</td>
							<td>Manager (EE)</td>
							<td>Level I</td>
							<td>0261 2400240 (Ext. 3421)</td>
							<td><?php echo str_replace(array('@','.com'),array('[at]','[dot]com'),'tejastailor@torrentpower.com');?></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php } ?>
	<div class="row">
		<div class="col-md-6">
			<h2 class="mb-sm mt-sm"><strong>Write</strong> us in case of any query or issue</h2>
				<div class="row">
					<div class="form-group">
						<div class="col-md-6">
							<label>Your name *</label>
							 <?php echo $this->Form->input('Contactus.name', array('label' => false,'class'=>'form-control','placeholder'=>'Your name')); ?>
						</div>
						<div class="col-md-6">
							<label>Your email address *</label>
							 <?php echo $this->Form->input('Contactus.email', array('label' => false,'class'=>'form-control','placeholder'=>'Your email address')); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<label>Subject *</label>
							 <?php echo $this->Form->input('Contactus.subject', array('label' => false,'class'=>'form-control','placeholder'=>'Enter the subject')); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<label>Mobile *</label>
							 <?php echo $this->Form->input('Contactus.mobile', array('label' => false,'class'=>'form-control','placeholder'=>'Enter the Mobile','maxlength'=>15)); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="form-group">
						<div class="col-md-12">
							<label>Message *</label>
							 <?php echo $this->Form->input('Contactus.message', array('type'=>'textarea','rows'=>2,'label' => false,'class'=>'form-control','placeholder'=>'Enter message')); ?>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
					<?php echo $this->Form->submit('Send Message',array('label' => false,'class'=>'btn btn-primary btn-lg mb-xlg cbtnsendmsg' ,'value'=>'Send Message')); ?>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-6">&nbsp;</div>
	</div>
</div>