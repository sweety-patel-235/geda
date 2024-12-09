<div>
    <?php if (!empty($ApplicationsMessage)) { ?>
        <?php foreach ($ApplicationsMessage as $Message) { ?>
            <div class="table-responsive">
                <table class="table table-bordered all-messages">
                <tr>
                    <th width="20%" valign="top" style="vertical-align:top !important">Message</th>
                    <td class="text-left"><?php echo $Message['message']?></td>
                </tr>
                <tr>
                    <th>Message From</th>
                    <td class="text-left"><?php echo $Message['comment_by']?></td>
                </tr>
                <tr>
                    <th>Message Date</th>
                    <td class="text-left"><?php echo $Message['created']?></td>
                </tr>
                <tr>
                    <th>IP Address</th>
                    <td class="text-left"><?php echo $Message['ip_address']?></td>
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