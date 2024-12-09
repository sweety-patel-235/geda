<style>
.rowcat .col-md-6 {
    border: 1px solid #c1c1c1;
}
.rowcat .control-label {
    text-align: right;
}
.rowcat1 .row {
    border: 1px solid #c1c1c1;
    padding: 7px;
}
</style>
<div class="container-fluid">
    <div class="">
        <div class="col-md-12">
        <?php echo $this->Form->create('termscondition',['name'=>'termscondition','id'=>'termscondition','type' => 'file','enctype'=>"multipart/form-data", 'url' => '/projects/uploadterms']);
        
        echo $this->Form->input('project_id',["type" => "hidden","value"=>(!empty($this->request->params['pass'][0])?decode($this->request->params['pass'][0]):'')]); ?>
        
        <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Terms And condition 1</label>
            <div class="col-sm-4">
               <?php echo $this->Form->input("termspath_1",["type" => "file","class" => "form-control",'label' =>false]);?>
            </div>
            <div class="col-sm-2">
            <?php
            $val1           = '';
            $default_text   = '';
            if(!empty($termsdata)) 
            {
                if(in_array('1', $terms_key_data))
                {
                    $arr_found  = (array_keys($terms_key_data,'1'));
                    $path       = $termsdata[$arr_found[0]]['termspath'];
                    $val1       = $arr_found[0];
                    if($termsdata[$arr_found[0]]['is_default'] == '1')
                    {
                        $default_text = '1';
                    }
                    echo $this->Form->button(__('View Document'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_document(\''.$path.'\');']);
                } 
            }
            ?>
            <?php echo $this->Form->input("term_id_1",["type" => "hidden","class" => "form-control",'label' =>false,"value" => $val1]);?>
            </div>
            <?php
            if($val1 != '')
            {

                ?>
                <div class="col-sm-2">
                <?php
                if($default_text == '1')
                {
                    echo '<span style="margin:31px;"> Default </span>';
                }
                else
                {
                    echo $this->Form->button(__('Make Default'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_default(\''.$val1.'\');']);
                }
                ?>
                </div>
                <?php
            }
            ?> 
        </div>
        <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Terms And condition 2</label>
            <div class="col-sm-4">
               <?php echo $this->Form->input("termspath_2",["type" => "file","class" => "form-control",'label' =>false]);?>
            </div>
            <div class="col-sm-2">
                <?php
                $val2           = '';
                $default_text   = '';
                if(!empty($termsdata)) 
                {
                    if(in_array('2', $terms_key_data))
                    {
                        $arr_found   = (array_keys($terms_key_data,'2'));
                        $path        = $termsdata[$arr_found[0]]['termspath'];
                        $val2        = $arr_found[0];
                        if($termsdata[$arr_found[0]]['is_default'] == '1')
                        {
                            $default_text = '1';
                        }
                        echo $this->Form->button(__('View Document'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_document(\''.$path.'\');']);
                    }
                }
                ?>
                <?php echo $this->Form->input("term_id_2",["type" => "hidden","class" => "form-control",'label' =>false,"value" => $val2]);?>
            </div>
            <?php
            if($val2 != '')
            {
                ?>
                <div class="col-sm-2">
                <?php
                if($default_text == '1')
                {
                    echo '<span style="margin:31px;"> Default </span>';
                }
                else
                {
                    echo $this->Form->button(__('Make Default'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_default(\''.$val2.'\');']);
                }
                ?>
                </div>
                <?php
            }
            ?> 
        </div>

        <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Terms And condition 3</label>
            <div class="col-sm-4">
               <?php echo $this->Form->input("termspath_3",["type" => "file","class" => "form-control",'label' =>false]);?>
            </div>
            <div class="col-sm-2">
                <?php
                $val3           = '';
                $default_text   = '';
                if(!empty($termsdata)) 
                {
                    if(in_array('3', $terms_key_data))
                    {
                        $arr_found   = (array_keys($terms_key_data,'3'));
                        $path        = $termsdata[$arr_found[0]]['termspath'];
                        $val3        = $arr_found[0];
                        if($termsdata[$arr_found[0]]['is_default'] == '1')
                        {
                            $default_text = '1';
                        }
                       echo $this->Form->button(__('View Document'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_document(\''.$path.'\');']);
                    }   
                }
                ?>
                <?php echo $this->Form->input("term_id_3",["type" => "hidden","class" => "form-control",'label' =>false,"value" => $val3]);?>
            </div>
            <?php
            if($val3 != '')
            {
                ?>
                <div class="col-sm-2">
                <?php
                if($default_text == '1')
                {
                    echo '<span style="margin:31px;"> Default </span>';
                }
                else
                {
                    echo $this->Form->button(__('Make Default'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_default(\''.$val3.'\');']);

                }
                ?>
                </div>
                <?php
            }
            ?>
        </div>
        <div class="form-group">
            <label for="pv_wp" class="control-label col-sm-2">Terms And condition 4</label>
            <div class="col-sm-4">
               <?php echo $this->Form->input("termspath_4",["type" => "file","class" => "form-control",'label' =>false]);?>
            </div>
            <div class="col-sm-2">
                <?php
                $val4           = '';
                $default_text   = '';
                if(!empty($termsdata)) 
                {
                    if(in_array('4', $terms_key_data))
                    {
                        $arr_found   = (array_keys($terms_key_data,'4'));
                        $path        = $termsdata[$arr_found[0]]['termspath'];
                        $val4        = $arr_found[0];
                        if($termsdata[$arr_found[0]]['is_default'] == '1')
                        {
                            $default_text = '1';
                        }
                       echo $this->Form->button(__('View Document'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_document(\''.$path.'\');']);
                    }
                }
                ?>
                <?php echo $this->Form->input("term_id_4",["type" => "hidden","class" => "form-control",'label' =>false,"value" => $val4]);?>    
            </div>
            <?php
            if($val4 != '')
            {
                ?>
                <div class="col-sm-2">
                <?php
                if($default_text == '1')
                {
                    echo '<span style="margin:31px;"> Default </span>';
                }
                else
                {
                    echo $this->Form->button(__('Make Default'),['type'=>'button','class'=>'btn-primary btn text-center center-block','onclick'=>'javascript:click_default(\''.$val4.'\');']);
                }
                ?>
                </div>
                <?php
            }
            ?>
        </div>
        <?php echo $this->Form->input("is_default",["type" => "hidden","id" => "is_default"]);
        echo $this->Form->input("action",["type" => "hidden","id" => "action"]);?>
        <?= $this->Form->button(__('Submit'),['type'=>'submit','id'=>'save_note','class'=>'btn-primary btn pull-left']);?>
        <?= $this->Form->end(); ?>
        
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript">
$(document).on('click', '#addNote', function() {
    
    $('#add_projects_note_model').modal('show');
    });

    $("#add_project_note_form").submit(function(e) {
        var form_data = new FormData(this);
        jQuery.ajax({
        url: '<?php echo URL_HTTP."projects/saveProjectNote"; ?>',
        type: 'POST',
        data:  form_data,
        dataType:  'json',
        mimeType:"multipart/form-data",
        processData: false,
        contentType: false,
        success: function(results)
        {
            if(results.status=='1') {
            location.reload();
            } else {

            }
        }
    });
    e.preventDefault();
});
</script>
<script type="text/javascript">
function click_document(path)
{
    window.open('<?php echo INSTALLER_TERMS_URL.$cus_id.'/';?>'+path, '_blank');
}
function click_default(term_id)
{
    $("#is_default").val(term_id);
    $("#action").val('update_default_status');
    $("#termscondition").submit();
}
</script>