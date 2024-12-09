// override jquery validate plugin defaults
$.validator.setDefaults({
    highlight: function(element) {
        $(element).closest('.form-group').addClass('has-error');
    },
    unhighlight: function(element) {
        $(element).closest('.form-group').removeClass('has-error');
    },
    errorElement: 'div',
    errorClass: 'help-block',
    errorPlacement: function(error, element) {
        if(element.parent('.input-group').length) {
            error.insertAfter(element.parent());
        } else {
            error.insertAfter(element);
        }
    }
});

$(".validate-form").validate();
//$(".select2").select2();
$("#forward_application button").click(function(){
    var formData = $("form#forward_application").serialize();
    jQuery.ajax({
        type: "POST",
        url: WEB_URL+"ApplyOnlines/forward",
        data: formData,
        success: function(result){            
            if( result === '1') { 
                $(".forward_application[data-id='"+$("#application_id").val()+"']").parents(".action-btn").remove();
                $('#forword_popup_discom').modal('hide');
            }
            $("#form-main").submit();
        }
    });

});
$(".applocation_status").click(function(){
    var application_status  = $(this).attr('data-status');
    var id                  = $(this).attr('data-id');
    var member_assign_id    = $(this).attr('data-member-type');
    var status_message      = $(this).attr('data-status-message');
    jQuery.ajax({
        type: "POST",
        url: WEB_URL+"ApplyOnlines/changeStatus",
        data: {'application_status': application_status,'id':id,'member_assign_id':member_assign_id},
        success: function(result){            
            if( result === '1') { 
                $(".forward_application[data-id='"+id+"']").parents(".row.p-row").find(".application-status").html(status_message);
                $(".forward_application[data-id='"+id+"']").parents(".action-btn").remove();
                $('#forword_popup_discom').modal('hide');
            }
            $("#form-main").submit();
        }
    });
    return false;
});
$("#sabsidy_availability button").click(function(){
    var formData        = $("form#sabsidy_availability").serialize();
    var status_message  = $(".application_status_message").html();
    var id              = $("#sabsidy_application_id").val();
    var appliation_status = $("#discom_status input[name='application_status']:checked").val();
    jQuery.ajax({
        type: "POST",
        url: WEB_URL+"ApplyOnlines/changeStatus",
        data:formData,
        success: function(result){            
            if( result === '1') { 
                $(".discom_status[data-id='"+id+"']").parents(".row.p-row").find(".application-status").html(status_message);
                if(appliation_status == 3) {//if select yes option in reio
                    $(".discom_status[data-id='"+id+"']").parents(".action-btn").remove();
                }
                $('#discom_status').modal('hide');
            }
            $("#form-main").submit();
        }
    });
    return false;
});
$("select[name='ApplyOnlines[apply_state]']").change(function(){
    var state        = $(this).val();
    var project_id   = $("#project_pass_id").val();
    jQuery.ajax({
        type: "POST",
        url: WEB_URL+"ApplyOnlines/instoler_list_by_state_id",
        data:{'state':state,'project_id':project_id},
        success: function(result){            
            result = JSON.parse(result);
            if( result['type'] === 'ok') {
                default_option = $('select[name="ApplyOnlines[installer_id]"]').find("option").eq(0);
                $('select[name="ApplyOnlines[installer_id]"]').html('');
                $('select[name="ApplyOnlines[installer_id]"]').append(default_option);
                $.each(result.data,function(k,v) {
                    $('select[name="ApplyOnlines[installer_id]"]').append('<option value="'+v['id']+'">'+v['installer_name']+'</option>');
                });
            }
        }
    });
    return false;
});
var mainadd_doc = $('.add_doc').eq(0).clone();
var counter_id_doc = 1;
$(mainadd_doc).find("input").val('');
$(mainadd_doc).find("img").remove();
$(mainadd_doc).find("a").remove();
$(".applay-online-from .add-more").click(function(){
    if($('.add_doc').length < 3){
        counter_id_doc++;
        var add_doc = $('.add_doc').eq(0).clone();
        
        $(add_doc).find("label").remove();
        $(add_doc).find("img").remove();
        $(add_doc).find("a").remove();
        $(add_doc).find("input").val('');
        if($(add_doc).find('.remove').hasClass('remove') == 'false'){
            $(add_doc).append('<a href="javascript:;" style="color: #fff !important;" class="btn btn-primary btn-lg mb-xlg cbtnsendmsg remove"><i class="fa fa-times-circle" style="font-size: 20px;padding-right:5px;"></i>Delete</a>')
        }
        $('.add_doc').eq($('.add_doc').length-1).after(add_doc);
        $('.add_doc').eq($('.add_doc').length-1).find(":input").attr("id", function()
        {
            var currId = $(this).attr("id");
            return 'applied_doc_'+counter_id_doc;
        });
        $('.applay-online-from .remove').click(function(){
            $(this).parents('span').remove();
            if($('.add_doc').length == 0){
                $(".add-more").closest(".col-md-6").after(mainadd_doc);
            }
        });
    }
});
$('.applay-online-from .remove').click(function(){
    $(this).parents('span').remove();
    if($('.add_doc').length == 0){
        $(".add-more").closest(".col-md-6").after(mainadd_doc);
    }
});