<style type="text/css">
.project-leads .p-row{
    background: #f9f9f9;
    padding: 15px;
    margin: 10px 0;
    border: 1px solid #e4e4e4;
    border-radius: 5px;
}
.project-leads .p-title{
    font-size: 18px;
    font-weight: 600;
    margin-bottom: 5px;
    
}
.project-leads .p-title a{
    color: #71BF57;
}
.project-leads .p-date{
    font-size: 14px;
    text-align: right;
    display: inline;
    margin-left: 10px;
}
.project-leads .action-btn{
    margin-top: 7px;
}
.form-control{
	box-shadow:none;
	border:1px solid #e6e6e6;
}
.btn-primary{
	border-color:#71BF57;
}
@media screen and (max-width: 991px){
.form-control{
	margin:10px 0;
}
}
</style>
<?php
    $this->Html->addCrumb($pageTitle); 
?>
<br/>
<div class="container project-leads">
<form name="form-main" action="/project" method="post">
    <div class="row">
        <div class="col-md-12">
           <div class="col-md-3">
                <select name="customer_type" id="customer_type" class="form-control">
                    <option value="">Select Category</option>
                    <?php
                    if(!empty($projectTypeList)) {
                        foreach ($projectTypeList as $key => $value) { 
                            $selected = ((!empty($this->request->data['customer_type']) && $this->request->data['customer_type'] == $key)? "selected":"");
                            echo "<option value='".$key."' $selected>".$value."</option>";
                        }
                    } ?>
                </select>
            </div>

            <div class="col-md-3">
                <select name="project_source" id="project_source" class="form-control">
                    <option value="">Select Project Source</option>
                    <?php
                    if(!empty($projectSourceList)) {
                        foreach ($projectSourceList as $key => $value) {
                            $selected = ((!empty($this->request->data['project_source']) && $this->request->data['project_source'] == $key)? "selected":"");
                            echo "<option value='".$key."' $selected>".$value."</option>";
                        }
                    } ?>
                </select>
            </div>

            <div class="col-md-3">
                <input type="text" name="project_name" id="project_name" value="<?php echo (!empty($this->request->data['project_name'])?$this->request->data['project_name']:''); ?>" class="form-control" placeholder="Enter Project Name">
            </div>

            <div class="col-md-3">
                <input type="text" name="location" id="location" value="<?php echo (!empty($this->request->data['location'])?$this->request->data['location']:''); ?>" class="form-control" placeholder="Enter City or State Name">
            </div>
        </div>   
    </div>
    <br/>

    <div class="row">
        <div class="col-md-12">
            <div class="col-md-4">
                <input type="submit" name="search_project" id="search" value="Search" class="btn btn-primary" >
            </div>
        </div>   
    </div>
    <br/>

    

    <div class="row">
        <div class="col-md-6">
            <h5 style="margin:10px;"><?php echo $this->Paginator->counter(['format' => 'Total Projects found: {{count}}']) ?></h5>
        </div>
        <div class="col-md-6">
            <div class="text-right">        
                <ul class="pagination text-right" style="margin: 0px;">
                    <?php
                        $this->Paginator->options(array('url'=> array('controller' => 'Projects','action' => 'index')));
                        echo $this->Paginator->numbers([
                                                        'before' => $this->Paginator->prev('Prev'),
                                                        'after' => $this->Paginator->next('Next')]);
                    ?>
                </ul>
            </div>
        </div>
        <div class="col-md-12">
            <?php foreach($projectLeads as $projectLead): ?>
                <div class="row p-row">
                    <div class="p-title">
                    <?php echo (!empty($projectLead['projects']['name'])?$this->Html->link($projectLead['projects']['name'],"javascript:;"):'<a href="javascript:;">PROJECT-'.$projectLead['projects']['id'].'</a>'); ?>
                    <?php if($projectLead['projects']['is_apply_disp']=='1') { ?>
                        <a href="<?php echo URL_HTTP; ?>apply-onlines/0/<?php echo encode($projectLead['projects']['id']);?>">
                            <span class="next btn btn-primary cbtnsendmsg">
                                <i class="fa fa-file-text"></i> Apply Online
                            </span>
                        </a>
                    <?php } ?>
                    <span class="p-date pull-right">
                        Date: <?php echo (!empty($projectLead['projects']['created']) ? date(LIST_DATE_FORMAT,strtotime($projectLead['projects']['created'])) : ''); ?>
                    </span>
                </div>
                <div>
                    Capacity: <?php echo (isset($projectLead['projects']['capacity_kw'])?$projectLead['projects']['capacity_kw']:0)." kW"; ?>
                </div>
                <div>Location: <?php echo (isset($projectLead['projects']['address']) ? $projectLead['projects']['address']:'');?></div>
                <div>City: <?php echo (!empty($projectLead['projects']['city'])?$projectLead['projects']['city']:'') ?></div>
            </div>
            <?php endforeach; ?>
            <!-- Paging Starts Here -->
            <div class="text-right">        
                <ul class="pagination text-right">
                <?php echo $this->Paginator->numbers([
                            'before' => $this->Paginator->prev('Prev'),
                            'after' => $this->Paginator->next('Next')]); ?>
                </ul>
            </div>
        </div>
    </div>
</form>
</div>
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog2">
        <div class="modal-content">
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

<script type="text/javascript">
$('a[rel="viewView"]').click(function(){
    $.fancybox({
        'autoDimensions' : true,
        'href'    : this.href,
        'width'   : 700, 
        'type'    : 'iframe',
        'arrows'  : false,
        'scrolling':false,
        'autoSize':true,
        'mouseWheel':false
    });
    return false;
});

$(".showModel").click(function(){

    var modelheader = $(this).data("title");
    var modelUrl = $(this).data("url");

    document_window = 500;
    document_height = 500;

    modal_body = '<div class="modal-header" style="min-height: 45px;">'+
        '<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button><h4 class="modal-title">'+modelheader+'</h4>'+
        '</div>'+
        '<div class="modal-body">'+
        '<iframe id="TaskIFrame" width="100%;" src="'+modelUrl+'" height="100%;" frameborder="0" allowtransparency="true"></iframe>'+
        '</div>';

    $('#myModal').find(".modal-content").html(modal_body);
    $('#myModal').modal('show');
    $('#myModal').find(".modal-dialog").attr('style',"min-width:"+document_window+"px !important;");
    $('#myModal').find(".modal-body").attr('style',"height:"+document_height+"px !important;");
    return false;

});

window.closeModal = function(){ $('#myModal').modal('hide'); };
</script>