jQuery(document).ready(function($){
  	$('input#select-icon').change(function() {	
   		var div = $(this).parent().find('div#selectResult');
		var site_url = $('#hidden_blog_url').val();
   		$('label[for="select-icon"]').hide();
   		$('#loading_image').css( 'display', 'inline');
   		$.post(site_url+'/wp-admin/admin-ajax.php', {
   			action: 'sy_register_opportunity',
   			post_id: $(this).attr('value'),
   			user_id: $(this).attr('name'),
   			selection: $(this).attr('checked')
   			}, function(data){
   				div.html($(data));
   				$('#loading_image').hide();
   				$('label[for="select-icon"]').show();
   			});
   		//return false;
	});

});
