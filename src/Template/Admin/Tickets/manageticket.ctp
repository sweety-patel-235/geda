    
<div class="grid_12">
    <div class="box">
        <div class="content">
        <?php echo $this->Form->create($Ticket,array('id'=>'formmain','class'=>'form-horizontal'));?>
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> <?php echo $title;?>
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label">Department</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->select('Ticket.department',$department,array('label' => false,'div'=>false,'empty'=>'-Select Department-','style'=>'width:250px;','class'=>'form-control','id'=>'TicketDepartment','placeholder'=>'Department'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Assign To</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->select('Ticket.to_id',array(), array('label' => false,'div'=>false,'empty'=>'-Select Assign To-','style'=>'width:250px;','class'=>'form-control select2','placeholder'=>'Department','id'=>'ticket_id','multiple'=>true));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Subject</label>
                                <div class="col-md-9">
                                    <?php echo $this->Form->input("Ticket.subject", array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control','placeholder'=>'Subject'));?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label">Message</label>
                                <div class="col-md-9">
                                   <?php echo $this->Form->textarea("Ticket.message", array('label' => false,'div'=>false,'type'=>'text','rows'=>3,'class'=>'form-control','placeholder'=>'Message'));?>
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="form-actions">
                        <button type="submit" class="btn blue">Submit</button>
                        <button type="button" class="btn default" onclick="goback()">Cancel</button>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    </div>
</div>	
<?php echo $this->Form->end();?>
<script type="text/javascript">
    $(document).ready(function(){
        $('#TicketDepartment').change(function(){
            var cid = this.value;
            var data = "id="+ cid;
            $.ajax({
                type: 'GET',
                url: WEB_ADMIN_URL+'users/departmentwiseuserlist/'+cid,
                datatype:'json',
                success: function(data,textStatus,xhr)
                {
                    var arr=JSON.parse(data);
                    var htmldata='';
                    $("#ticket_id").parent('div').find('input[type="hidden"]').remove();
                    $("#ticket_id").parent('div').find('.select2-container').remove();
                    var opt1='<option value="">-Select User-</option>';
                    $.each(arr , function(key, val) {
                        opt1='<option value="'+key+'">'+val+'</option>';
                        htmldata+=opt1;
                    });
                    $("#ticket_id").html(htmldata);
                    $('.select2').select2({
                        placeholder: "Select an option",
                        allowClear: true
                    });
                           
                },error: function(xhr,textStatus,error)
                {
                    document.getElementById("here").innerHTML = "Error";
                }
            });

        });            
    });
    function goback(){
        window.location.href=WEB_ADMIN_URL+'tickets ';
    }
</script>
<?php
    echo $this->Html->script('autocomplete/jquery-ui-1.10.3.custom.min');
    echo $this->Html->script('autocomplete/customer');
    echo $this->fetch('script');
?>