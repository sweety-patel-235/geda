<div class="grid_12">
<div class="box">
    <div class="content">
        <div class="portlet box blue-madison ">
            <div class="portlet-title">
                <div class="caption">
                    <i class="fa fa-user"></i> View Project Installer List
                </div>
                <div class="tools">
                    <a href="" class="collapse" data-original-title="" title=""></a>
                </div>
            </div>
            <div class="portlet-body form">
                <div class="form-body">
				    <div class="col-md-12">
                              <?php 
                                 if(!empty($projectInstallers)) {
                                    foreach ($projectInstallers as $key => $value) {
                                        ?>
                                       <div class="row">
                                            <hr/>
                                            <div class="col-md-4">
                                              <?php echo $value->installers['installer_name']; ?>
                                            </div>
                                            <div class="col-md-4">
                                                  <?php echo $value->installers['city']; ?>
                                            </div>
                                             <div class="col-md-4">
                                                  <?php echo $value->installers['email']; ?>
                                            </div>
                                        </div> 
                                    <?php      
                                    }
                                 }
                                 else {
                                     ?>
                                       <div class="row">
                                            <div class="col-md-12">
                                                User not selected any Installers!
                                            </div>
                                        </div> 
                                    <?php    
                                 }
                              ?>
                            </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">  
                    
                        <hr/>
                        <div class="col-md-offset-5 col-md-6">
                            <button type="button" onclick="javascript:goback();"  class="btn"><i class="fa fa-close"></i> Close</button>
                        </div>
                    </div>
                    </div>
            </div>
        </div> 
    </div>
    </div>
</div>
<script type="text/javascript">
function goback() {
    $('#myModal').modal('toggle');
}
</script>

