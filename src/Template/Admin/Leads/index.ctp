<?php if($AjaxRequest=='0'){?>
    <?php echo $this->Form->create('Userrole',array("id"=>"formmain","url"=>ADMIN_PATH."/projects/leadall","name"=>"searchAdminuserlist",'class'=>'form-horizontal form-bordered',"autocomplete"=>"off")); ?>
    <div class="row">
        <div class="col-md-12">
            <?php  echo $this->Flash->render('cutom_admin'); ?>
            <div class="portlet box blue-madison">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-list-ul"></i>All Leads
                    </div>
                    <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                    </div>
                    <div class="actions">
                        <?php
                        $blnAddAdminuserRights	= $Userright->checkadminrights($Userright->ADD_ADMIN_USER_ROLE);
                        if($blnAddAdminuserRights) {
                            echo $Userright->linkAddAdminuser(constant('WEB_ADMIN_URL').'leads/add','<i class="fa fa-plus"></i> Add Leads','','alt="addRecord" class="btn green btn-border"');
                        }
                        ?>
                    </div>
                </div>
                <div class="portlet-body form">
                    <?php //echo $this->Form->hidden('total_pages',array("value"=>$page_count,"id"=>"TotalPages")); ?>
                    <?php echo $this->Form->hidden('CurrentPage',array("value"=>"","id"=>"CurrentPage")); ?>
                    <?php echo $this->Form->hidden('Sort',array("value"=>$SortBy,"id"=>"Sort")); ?>
                    <?php echo $this->Form->hidden('Direction',array("value"=>$Direction,"id"=>"Direction")); ?>

                    <div class="form-body">
                        <div class="form-group">
                            <label class="control-label col-md-2">ID(s)</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->input('id', array('label' => false,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker')); ?>
                            </div>
                            <label class="control-label col-md-2">Project Name</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->input('project_name', array('label' => false ,'size'=>5,'div'=>false,'type'=>'text','class'=>'form-control form-control-inline input-medium date-picker')); ?>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Source Lead</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->select('source_lead',$source_lead, array('id'=>'source_lead','label' => false,'class'=>'form-control form-control-inline input-medium','empty'=>'-select Source of Lead-')); ?>
                            </div>

                            <label class="control-label col-md-2">Status</label>
                            <div class="col-md-4">
                                <?php echo $this->Form->select('status',$status_lead, array('id'=>'status','label' => false,'class'=>'form-control form-control-inline input-medium','empty'=>'- Status of Lead -')); ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-offset-5 col-md-6">
                                <button type="button" class="btn green" id="searchbtn"><i class="fa fa-check"></i> Submit</button>
                                <button type="button" onClick="resetsearch()" class="btn default"><i class="fa fa-refresh"></i> Reset</button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--DISPLAY LIST OF ADMIN USER ROLES-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box blue-madison">
                <div class="portlet-title">
                    <div class="caption">

                    </div>
                    <div class="tools">

                    </div>
                </div>
                <?php }?>
                <?php echo $this->element('leadlist'); ?>
                <?php if($AjaxRequest=='0'){?>
            </div>
        </div>
    </div>
    <!--DISPLAY LIST OF ADMIN USER ROLES-->
    <?php echo $this->Form->end(); ?>
    <script type="text/javascript">
        $(document).ready(function() {
            resetcustomdates(true);
            resetdates();
        });
        <?php
        echo $JqdTablescr;
        ?>

        function resetsearch()
        {
            $('#id').val("");
            $('#project-name').val("");
            $('#source_lead').val("");
            $('#status').val("");
            $('#searchbtn').click();
        }
        function resetcustomdates(onload)
        {
            var period		= $('#SearchPeriod').val();
            var Today		= '<?php echo date("d-m-Y");?>';
            var Yesterday	= '<?php echo date("d-m-Y",strtotime("yesterday"));?>';
            if(period==3)
            {
                $("#DateFrom").removeAttr("disabled");
                $("#DateTo").removeAttr("disabled");
                if(!onload) {
                    $("#DateFrom").val(Yesterday);
                    $("#DateTo").val(Today);
                }
            }
            else
            {
                $("#DateFrom").val("");
                $("#DateTo").val("");
                $("#DateFrom").attr("disabled",true);
                $("#DateTo").attr("disabled",true);
            }
            if(period=="")
            {
                $("#DateFrom").val("");
                $("#DateTo").val("");
            }
            if(period==1)
            {
                $("#DateFrom").val(Today);
                $("#DateTo").val(Today);
            }
            if(period==2)
            {
                $("#DateFrom").val(Yesterday);
                $("#DateTo").val(Today);
            }
            $("#DateFrom").datepicker({format:'dd-mm-yyyy',autoclose: true});
            $("#DateTo").datepicker({format:'dd-mm-yyyy',autoclose: true});
        }

        function validatesearchdates()
        {
            var err= '';
            if($('#SearchPeriod').val()=='' && $('#SearchDate').val() != '')
            {
                err +='Please select \"Period\" value.\r\n';
            }
            if($('#SearchPeriod').val()==3 && $('#SearchDate').val()!='')
            {
                if(err=='')
                {
                    date_1 = $("#DateFrom").val();
                    date_2 = $("#DateTo").val();
                    if (date_1 == '') {
                        err +='"From Date" should not empty.\r\n';
                        $("#DateFrom").focus();
                    } else if (date_2 == '') {
                        err +='"To Date" should not empty.\r\n';
                        $("#DateTo").focus();
                    } /*else if(!dateDiff(date_1,date_2)) {
                 err +='"To Date" should not be less than "From Date".\r\n';
                 $("#DateTo").focus();
                 }*/
                }
            }
            return err;
        }

        function validatesearchform()
        {
            var err = '';
            var setFocus = '';
            err = validatesearchdates();
            if(err!='')
            {
                alert(err);
                if(setFocus!='')
                {
                    var obj = eval(setFocus);
                    obj.focus();
                }
                return false;
            }
            return true;
        }

        function resetdates()
        {
            if($('#SearchDate').val()=='')
            {
                $('#SearchPeriod').val("");
                $('#SearchPeriod').attr("disabled",true).trigger("liszt:updated");
                resetcustomdates(false);
            }
            else
            {
                $('#SearchPeriod').removeAttr("disabled").trigger("liszt:updated");
            }
        }

    </script>
<?php }?>