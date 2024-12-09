$(document,window).ready(function() {
	console.log("You are in");

	$('#company_name').autocomplete({
	   source: function( request, response ){
		   console.log(request);
            $.ajax({
              url : '/installerregistrations/companylist/'+request.term,
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
            $(this).parents('div').find('#company_id').val(names[1]);
        }
    });
});