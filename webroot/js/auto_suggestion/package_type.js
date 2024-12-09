/**
 * Site : environment_xchange
 * @author: dhingani yatin
 * script : Projects related script
 */
$('.package_type').live('focus',function(){
    $(this).autocomplete({
	   source: function( request, response ){
            $.ajax({
              url : WEB_ADMIN_URL+'packagetypes/packagetype_list/'+request.term,
              dataType: "json",
              /*data: {
                name_startsWith: request.term,
              },*/
              success: function( data ) {
                //alert(data);
                response( $.map( data, function( item ) {
                 var code = item.split("|");
                    return {
                        label: code[0],
                        value: code[0],
                        data : item
                    }
                }));
            }
        });
    },
    autoFocus: true,
    minLength: 0,
    select: function( event, ui ) {
            var names = ui.item.data.split("|");
            $(this).parents('div').find('#package_type_id').val(names[1]);
            /*var obj=$(this);
            var dt;
            $.ajax({
                  url : WEB_ADMIN_URL+'projects/project_details/'+names[1],
                  success: function( data ) {
                    customer_detail = JSON.parse(data);
                    dt=customer_detail.split('|');
                    $(".site_location").val(dt[0]);
                    $(".customer_name").val(dt[1]);
                    $(".customer_address").val(dt[2]);
                  }
            });*/
        }
    });
});