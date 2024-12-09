/**
 * Site : environment_xchange
 * @author: dhingani yatin
 * script : subscription type related script
 */
$('.user').live('focus',function(){
    $(this).autocomplete({
	   source: function( request, response ){
            $.ajax({
              url : WEB_ADMIN_URL+'users/userlist/'+request.term,
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
            $(this).parents('div').find('#user_id').val(names[1]);
        }
    });
});