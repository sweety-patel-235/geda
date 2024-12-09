<?php
//echo $this->Html->css('bootstrap.min');
//echo $this->fetch('css');
?>
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script> -->
<style type="text/css">
    .bs-example{
        margin: 20px;
    }
</style>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Ticket History</h3>
</div>
<div class="modal-body">
    <div class="panel-group accordion">
    <?php 
    if(isset($data) && !empty($data)){
        $id=0;
        foreach ($data as $sdata){ 
            ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <a class="accordion-toggle accordion-toggle-styled collapsed" aria-expanded="true" data-toggle="collapse" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $id;?>"><?php echo $sdata['subject'];?>
                    <span class="spanright"><?php echo date('d-M-Y h:i:s A',strtotime($sdata['created']));?></span>  
                    </a>
                </h4>
            </div>
            <div id="collapse<?php echo $id;?>" class="panel-collapse collapse">
                <div class="panel-body">
                    <p class="atext"><?php echo $sdata['message'];?></p>
                    <p><span class='spanleft atext'><b>From-<i><?php echo $sdata->user['username']; ?></i></b></span></p>
                </div>
            </div>
        </div>
    <?php $id++;}
    } else {
        echo '<p class="csmsg">&nbsp;<b>No Record Found.</b></p>';
    } ?>
    </div>
</div>
            