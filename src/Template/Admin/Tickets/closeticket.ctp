<?php
/**
 * Admin Ticket Details.  Displays Ticket Details
 * @package      app.View.Elements
 * @author       jaysinh Rajpoot
 * @since        01/04/2015
 */
if($is_ajax==false){
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h3>Close Ticket Status</h3>
</div>
<div class="modal-body">
<?php } ?>
    <?php echo $this->Form->create($Tickets,array('id'=>'ticket_remark','method'=>'POST','class'=>'form-horizontal form form-bordered')); ?>
    <!-- <from name="remark"> -->
        <div class="form-body">
            <div class="form-group">
                <label class="col-md-3 control-label">Remarks</label>
                <div class="col-md-9">
                    <div class="input text">
                    <?php echo $this->Form->input('Tickets.remarks',array('type'=>'textarea','div'=>false,'label'=>false ,'class'=>'form-control','tabindex'=>1,'rows'=>3));?>
                    </div>                                
                </div>
            </div>
        </div>
        <div class="form-actions text-center">
            <button type="button" class="submit btn blue" id="searchbtn" tabindex="2">Submit</button>
            <button type="button" class="btn default" class="close" data-dismiss="modal" tabindex="3">Close</button>
        </div>
    <!-- </from> -->
    <?php echo $this->Form->end(); 
if($is_ajax==false){
    ?>
</div>


<script type="text/javascript">
    
    $('document').ready(function(){
        var ajax_url = $('#ticket_remark').attr('action');
        /*alert(ajax_url);*/      
        $('.submit').live('click',function(){
            $.ajax({
                  url  : ajax_url,
                  type : "POST",
                  data : $('#ticket_remark').serialize(),
                  success: function( data ) { 
                    if($(data).hasClass('form')){
                        $('#ticket_remark').html($(data).html());
                    }else{
                        $('#ticket_remark').html(data);
                    }
                  }
            });
        });
    });
  </script>
<?php } ?>