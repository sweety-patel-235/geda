<tr class="mainRow">
	<td class="valignTop">
		<?php echo $this->Form->input('Workorder.work_no][', array('label' => false,'class'=>'form-control work_no_cls rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'work_no_'.$newRowCounter)); ?>
		
	</td>
	<td class="valignTop">
		<?php echo $this->Form->input('Workorder.capacity][', array('label' => false,'class'=>'form-control rfibox capacity_cls','placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'capacity_'.$newRowCounter)); ?>
		
	</td>
	<td class="valignTop" >
		<?php echo $this->Form->input('Workorder.workorder_date][',["type" => "text",'label'=>false,'id'=>'workorder_date_'.$newRowCounter,"class" => "form-control workorder_date_cls"]);?>
		
	</td>
	<td class="valignTop" >
			<div class="file-loading" >
				<?php echo $this->Form->input('Workorder.workorder_doc][', array('label' => false,'div' => false,'type'=>'file','id'=>'workorder_doc_'.$newRowCounter,"class" => "form-control workorder-documents",'templates' => ['inputContainer' => '{{content}}'],'accept'=>'.pdf')); ?>
			</div>
			<input type='hidden' id='workorder_doc_view_<?php echo $newRowCounter;?>' value='0' />
			<?php /*<input type="file" accept=".pdf" id='workorder_doc_<?php echo $newRowCounter;?>' name="Workorder[workorder_doc][<?php echo $newRowCounter;?>]" class="workorder_doc_cls">*/?>
		
		<?php /*<a href="/Undertaking_by_REG.docx" style="text-decoration: underline;"><strong>[Download Undertaking Format]</strong></a>*/?>
		<div class="workorder_doc_err_cls" id="workorder_doc_<?php echo $newRowCounter;?>_cls"></div>

			
	</td>
	<td class="valignTop lastrow" id="actionrange_<?php echo $newRowCounter;?>"><input style="margin-right:14px;"  class="btn green" type="button" onclick="javascript:AddChildWorkOrderRow(this);" id="" value="Assign Project" /></td>
</tr>
<tr class="subMainRow_<?php echo $newRowCounter;?> " ><?php //hide?>
	<td class="valignTop" colspan="5">
		<table id="tbl_child_workorder_info_<?php echo $newRowCounter;?>" class="table table-striped table-bordered table-hover custom-greenhead">
			<thead class="thead-dark">
				<th scope="col">Assign Project</th>
				<th scope="col">Set Capacity (in MW)</th>
				<th scope="col">Assign Developer</th>
				<th scope="col">RE Applications</th>
				<th scope="col">Action</th>
			</thead>
			<tbody>
				<tr>
					<td class="valignTop">
						<?php echo $this->Form->input('Workorder.child_work_no_'.$newRowCounter.'][', array('label' => false,'class'=>'form-control child_work_no_cls_'.$newRowCounter.' rfibox','placeholder'=>'Project','autocomplete'=>"false",'type'=>'text','id'=>'child_work_no_'.$newRowCounter.'_0')); ?>
						
					</td>
					<td class="valignTop">
						<?php echo $this->Form->input('Workorder.child_capacity_'.$newRowCounter.'][', array('label' => false,'class'=>'form-control rfibox child_capacity_cls_'.$newRowCounter,'placeholder'=>'Total Capacity','onkeypress'=>"return validateDecimal(event)",'autocomplete'=>"false",'type'=>'text','id'=>'child_capacity_'.$newRowCounter.'_0','onchange'=>"javascript:setTotalAll()")); ?>
						
					</td>
					<td class="valignTop">
						<?php echo $this->Form->select('Workorder.child_developer_'.$newRowCounter.'][', $arrDeveloper,array('label' => false,'class'=>'form-control rfibox child_developer_cls_'.$newRowCounter,'empty'=>'-Select Developer-','placeholder'=>'','id'=>'child_developer_'.$newRowCounter.'_0')); ?>	
					</td>
					<td class="valignTop" ></td>
					<td class="valignTop child_lastrow_<?php echo $newRowCounter;?>" ></td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>
<script type="text/javascript">
	$(".workorder_date_cls").datepicker({format:'dd-M-yyyy',autoclose: true});
	$("#workorder_doc_<?php echo $newRowCounter;?>").fileinput({
		showUpload: false,
		showPreview: false,
		dropZoneEnabled: false,
		mainClass: "input-group-md",
		allowedFileExtensions: ["pdf"],
		elErrorContainer: '#workorder_doc_<?php echo $newRowCounter;?>_cls',
		maxFileSize: '1024',
	});
</script>