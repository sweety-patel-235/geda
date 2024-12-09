<div>
	<?php if (!empty($ApplyonlineMessage)) { ?>
		<?php foreach ($ApplyonlineMessage as $Message) { ?>
			<div class="table-responsive">
				<table class="table table-bordered all-messages">
				<tr>
					<th width="20%" valign="top" style="vertical-align:top !important">Query</th>
					<td class="text-left"><?php echo nl2br($Message['Msg_Query'])?></td>
				</tr>
				<tr>
					<th width="20%" valign="top" style="vertical-align:top !important">Reply</th>
					<td class="text-left"><?php echo nl2br($Message['ApplyonlineMessage']['message'])?></td>
				</tr>
				<tr>
					<th>Message Date</th>
					<td class="text-left"><?php echo $Message['ApplyonlineMessage']['created']?></td>
				</tr>
				<tr>
					<th>IP Address</th>
					<td class="text-left">
						<?php echo $Message['ApplyonlineMessage']['ip_address']?>
						<div class="action-row pull-right">
							<button type="button" class="btn btn-xs green MarkAsRead" data-id="<?php echo encode($Message['Unread_ID']);?>">Mark As Read</button>
						</div>
					</td>
				</tr>
				</table>
			</div>
		<?php } ?>
	<?php } else { ?>
		<div class="table-responsive">
			<table class="table table-bordered">
			<tr>
				<td class="text-danger"><b>No Message !!!</b></td>
			</tr>
			</table>
		</div>
	<?php } ?>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		$(".MarkAsRead").click(function() {
			var btnObj = $(this).parent();
			$.ajax({
					type: "POST",
					url: "/apply-onlines/markasread/"+$(this).data("id"),
					success: function(response) {
						var result = $.parseJSON(response);
						if (result.success == 1) {
							$(btnObj).remove();
						}
					}
				});
		});
	});
</script>