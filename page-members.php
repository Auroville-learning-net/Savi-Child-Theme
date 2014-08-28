<?php
/*
Template Name: Members only page
*/
?>
<?php get_header(); ?>
<div id="main-content">


	<div class="container">

<?php if (is_user_logged_in()) { 
			$user_id = get_current_user_id( );
			$post_id = get_user_meta($user_id, 'profile_post_id', true);
			$confirm_receipt_sealed_envelop = get_post_meta($post_id, 'confirm_receipt_sealed_envelop', true);
			$template_dir = get_stylesheet_directory_uri();
			$post_type = get_post_type( $post_id );
			if($post_type != "view_6"):
			    
					echo "<h3>You donot have the permission to view this page</h3>";
			else:
			
			      if(empty($confirm_receipt_sealed_envelop)):
?>
					<div class="clearfix" id="content-area">

					<p class="confirmation_class">Please confirm that you have received Sealed VISA Request letter from register courier. </p>
					<p class="confirmation_class"><input type="button" name="received__visa_request_letter" id="received__visa_request_letter" 
							   value="Confirm Receipt of Sealed Envelop" >
							   <input type="hidden" id="hidden_postID" value="<?php echo $post_id?>" />
							   
				  </p>
				  <img id ="ajax_loader" style="display:none" src="<?php echo $template_dir.'/images/ajax-loader.gif' ?>" />			   
				   <h3 class="confirmationed_class" style="display:none">You have been confirmed that, Sealed VISA Request letter from register courier. </h3>			   
					</div>


<?php 

                else:
?>
                   <div class="clearfix" id="content-area">

					<p >You already confimed that received Sealed VISA Request letter from register courier. </p>
				</div>	
<?php        
    			endif;
		endif;

} else {
// they aren't logged in, so show them the login form
       ?>

		<div class="clearfix" id="content-area">
<p>I'm sorry, but you must be a logged in member to view this page. Please either login below.</p>
</div>

<form name='loginform' id='loginform' action='<?php bloginfo('wpurl'); ?>/wp-login.php' method='post'>
    <p>
      <label>Username<br />
      <input type='text' name='log' id='log' value='' size='20' tabindex='1' />
      </label>
    </p>
    <p>
      <label>Password<br />
      <input type='password' name='pwd' id='pwd' value='' size='20' tabindex='2' />
      </label>
    </p>
    
    <p class='submit'>
      <input type='submit' name='submit' id='submit' value='Login &raquo;' tabindex='4' />
      <?php //use a hidden field to return them to the page they came from ?>
      <input type="hidden" name="redirect_to" value="<?php echo $_SERVER["REQUEST_URI"]; ?>" />
    </p>
  </form>
 
  <?php	} ?>

 </div>
  </div>
  <script type="text/javascript" >
		jQuery('#received__visa_request_letter').click(function(e){
		var post_id = <?php echo $post_id ?>;
		 jQuery('.confirmation_class').hide();
		  jQuery('#ajax_loader').show();
		//alert(post_id);
		jQuery.ajax({ 
			 data: {action: 'savi_received__visa_request_letter', post_id:post_id,ajax_visa_action:'confirm_receipt_sealed_envelop'},
			 type: 'post',
			url: "<?php echo get_bloginfo('url')?>/wp-admin/admin-ajax.php",
			 success: function(data) {
				 jQuery('#ajax_loader').hide();
				if(data != 0) {
            
				  switch (data) {
						case ('confirm_receipt_sealed_envelop'):
						 
						   jQuery('.confirmation_class').hide();
						   jQuery('.confirmationed_class').show();
						  
							break;
						case ('already_receipt_sealed_enveloped_confirmed'):
						   
						   jQuery('.confirmation_class').hide();
						   jQuery('.confirmationed_class').show();
						  
						   
							break;    
						default:
							break;
					}  

			}
		  }	
		});
	});
</script>
<?php get_footer(); ?>
