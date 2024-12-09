<?php
$this->Html->addCrumb($pageTitle);
?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="container">
    <div class="row" style="border:2px solid lightgreen; border-radius:5px; padding-top:10px;">
        <div class="col-md-12">
            <?= $this->Form->create('companylist' ,['name' => 'colist', 'id' => 'colist'], array('action' => 'installer_reg_verification'));?>
                <fieldset>
                    <legend>Installer Verification</legend>
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-6 ">
                                <label>Company Name</label>
                                <?php echo $this->Form->select('company_id','', array('label' => false,"class"=>"js-example-basic-single")); ?>
                            </div>
                            <div class="col-md-1">
                            </div>
                            <div class="col-md-3">
                                <input type="button" value="Send OTP!" class="btn btn-primary btn-lg mb-xlg sendotp" style="margin-top: 23px;" data-loading-text="Loading..." onclick="sendOtpInstaller()"><i class="fa fa-circle-o-notch fa-spin" id="spinnerloder" style="font-size:24px;display: none; margin-left: 14px;color:#71bf57"></i>
                            </div>
                        </div>
                    </div>
                </fieldset>

                <div class="row otpdiv" style="display: none;">
                    <div class="form-group">
                        <div class="col-md-6 ">
                            <label>Enter OTP</label>
                            <?php echo $this->Form->input('company_id',["type" => "hidden","label"=>false,"class" => "form-control",'id'=>"company_id"]);?>
                            <?php echo $this->Form->input('otp',["type" => "text","label"=>false,"class" => "form-control",'id'=>"otp"]);?>
                            <span class="error" style="display: none;color:red"></span>
                        </div>  
                        <div class="col-md-1">
                        </div>
                        <div class="col-md-3">
                            <input type="button" value="Verify OTP!" class="btn btn-primary btn-lg mb-xlg submitotp" style="margin-top: 23px;" data-loading-text="Loading..." onclick="sendcode()"><i class="fa fa-circle-o-notch fa-spin" id="spinner" style="font-size:24px;display: none; margin-left: 14px;color:#71bf57"></i>
                        </div>
                    </div>
                </div>
            <?php $this->Form->end(); ?>
        </div>
    </div>
</div>

<script>
    $('.js-example-basic-single').select2({
        ajax: {
            url: '<?php echo $this->Url->build(['controller' => 'InstallerRegistrations','action'=>'co_list']); ?>',
            dataType: 'json',
            type: "GET",
            data: function (params) {
                var queryParameters = {
                    term: params.term
                }
                return queryParameters;
            },
            processResults: function (data) {
                return {
                    results: data
                };
            },
            cache: true
        },
        minimumInputLength: 3,
        allowClear: true,
    });
    /**
     * sendOtpInstaller
     *
     * @defination : Method for generate otp.
     */
    function sendOtpInstaller(){
       $('#spinnerloder').show();
       $("#otp").val('');
        var company_id= $(".js-example-basic-single option:selected").val();
            $.ajax({
                url:'<?php echo $this->Url->build(['controller' => 'InstallerRegistrations','action'=>'sendOtpInstaller']); ?>',
                type: 'POST',
                data: jQuery.param({company_id: company_id}),

                success: function(data)
                {   
                    var data = JSON.parse(data);
                    if(data.success == 1){
                            $('.otpdiv').show();
                            $('#company_id').val(company_id);
                            $('.sendotp').val('Resend OTP!')
                            $('#spinnerloder').hide();
                    }
                }
            });
    }
    /**
     *
     * sendcode
     *
     * @defination : Method to verify otp.
     */
     function sendcode() {
        $('#spinner').show();
        var co_id= $("#company_id").val();
        var otp_code = $("#otp").val();
        $.ajax({
            url:'<?php echo $this->Url->build(['controller' => 'InstallerRegistrations','action'=>'submitOtp']); ?>',
            type: 'POST',
            data: jQuery.param({otp: otp_code, company_id: co_id}),

            success: function(data)
            {
                var data = JSON.parse(data);
                if(data.success == 1) {
                    $('#spinner').hide();
                    window.location = data.link;
                }
                else
                {
                    $('#spinner').hide();
                    if(data.msg){
                        $('.error').show().html(data.msg);

                    }
                }
            }
        });
    }
</script>

