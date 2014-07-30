<?php /* Template Name: Suggestions */
if(isset($_POST['submitted']) && isset($_POST['post_nonce_field']) && wp_verify_nonce($_POST['post_nonce_field'], 'post_nonce')) {

	if(trim($_POST['postTitle']) === '') {
		$postTitleError = 'Please enter a Name.';
		$hasError = true;
	} else {
		$postTitle = trim($_POST['postTitle']);
	}
   if(trim($_POST['email']) === '') {
		$postEmailError = 'Please enter a Email.';
		$hasError = true;
	}
   if(trim($_POST['country']) === '') {
		$postCountryError = 'Please enter a Country.';
		$hasError = true;
	}
    if(trim($_POST['phone']) === '') {
		$postPhoneError = 'Please enter a Phone.';
		$hasError = true;
	}
    if(trim($_POST['time_of_stay']) === '') {
		$postTimeError = 'Please enter a Time of Stay.';
		$hasError = true;
	}
    if(trim($_POST['visa_required']) === '') {
		$postVisaRequiredError = 'Please select the Visa Reqiured.';
		$hasError = true;
	}
    if(trim($_POST['motivation']) === '') {
		$postMotivationError = 'Please enter a Motivation.';
		$hasError = true;
	}
     if(trim($_POST['skills']) === '') {
		$postSkillsError = 'Please enter a Skills.';
		$hasError = true;
	}
	$post_information = array(
		'post_title' => esc_attr(strip_tags($_POST['postTitle'])),
		'post_type' => 'View_0',
		'post_status' => 'publish'
	);

 if(!$hasError):
	$post_id = wp_insert_post($post_information);
    update_post_meta($post_id,'name',$_POST['postTitle']);
    update_post_meta($post_id,'email',$_POST['email']);
    update_post_meta($post_id,'country',$_POST['country']);
    update_post_meta($post_id,'phone',$_POST['phone']);
    update_post_meta($post_id,'time_of_stay',$_POST['time_of_stay']);
    update_post_meta($post_id,'visa_required',$_POST['visa_required']);
    update_post_meta($post_id,'motivation',$_POST['motivation']);
    update_post_meta($post_id,'skills',$_POST['skills']);
	if($post_id)
	{
      header("location:http://auroville-learning.net/beta2/registration_from//?msg=New View_0 Post created&post_id=".$post_id);
      // echo "<span style='color:green;font-weight:bold'>New View_0 Post created".$post_id."</span>";
		//wp_redirect(home_url());
		//exit;
	}
endif;
}
?>
<?php get_header(); ?>
  <?php
    if(isset($_GET['msg'])){
      echo"<center style='margin-top:30px'><span style='color:green;font-weight:bold'>New View_0 Post created".$_GET['post_id']."</span></center>";
    }
  ?>
	<!-- #primary BEGIN -->
	<div id="primary">

		<form action="" id="primaryPostForm" method="POST">

			<div style="display:block;width:100%;margin:40px;">

				<label for="postTitle" style="display:inline;width:20%;float:left"><?php _e('Name:', 'framework') ?></label>

				<input type="text" name="postTitle" id="postTitle" value="<?php if(isset($_POST['postTitle'])) echo $_POST['postTitle'];?>" class="required" />
                  
              	<?php if($postTitleError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postTitleError; ?></span>
				<div class="clearfix"></div>
			<?php } ?>
               

		     </div>

		
			<div style="display:block;width:100%;margin:40px">
						
				<label for="email" style="display:inline;width:20%;float:left"><?php _e('Email:', 'framework') ?></label>

            <input type="text" name="email" id="email" 
             value="<?php if(isset($_POST['email'])) echo $_POST['email'];?>" class="required" />
           	<?php if($postEmailError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postEmailError; ?></span>
				<div class="clearfix"></div>
			<?php } ?>
			</div>

           <div style="display:block;width:100%;margin:40px">
						
				<label for="country" style="display:inline;width:20%;float:left"><?php _e('Country:', 'framework') ?></label>

            <input type="text" name="country" id="country" 
             value="<?php if(isset($_POST['country'])) echo $_POST['country'];?>" class="required" />
            	<?php if($postCountryError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postCountryError; ?></span>
				<div class="clearfix"></div>
			   <?php } ?>
			</div>
            <div style="display:block;width:100%;margin:40px">
						
				<label for="phone" style="display:inline;width:20%;float:left"><?php _e('Phone:', 'framework') ?></label>

            <input type="text" name="phone" id="phone" 
             value="<?php if(isset($_POST['phone'])) echo $_POST['phone'];?>" class="required" />
             	<?php if($postPhoneError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postPhoneError; ?></span>
				<div class="clearfix"></div>
			<?php } ?>
			</div>
           <div style="display:block;width:100%;margin:40px">
						
				<label for="time_of_stay" style="display:inline;width:20%;float:left"><?php _e('Time of Stay:', 'framework') ?></label>
 
            <input type="text" name="time_of_stay" id="time_of_stay" 
             value="<?php if(isset($_POST['time_of_stay'])) echo $_POST['time_of_stay'];?>" class="required" />
            	<?php if($postTimeError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postTimeError; ?></span>
				<div class="clearfix"></div>
			    <?php } ?> 
			</div>
             <div style="display:block;width:100%;margin:40px">
						
				<label for="visa_required" style="display:inline;width:20%;float:left"><?php _e('Visa Required:', 'framework') ?></label>
 
            <input type="radio" name="visa_required" 
             value="yes" <?php if($_POST['visa_required'] =="yes") :?> checked="checked" <?php endif ?>/>yes
             <input type="radio" name="visa_required" 
             value="no"<?php if($_POST['visa_required'] =="no") :?> checked="checked" <?php endif ?>/>no
            	<?php if($postVisaRequiredError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postVisaRequiredError; ?></span>
				<div class="clearfix"></div>
			    <?php } ?> 
			</div>
            <div style="display:block;width:100%;margin:40px">
						
				<label for="motivation" style="display:inline;width:20%;float:left"><?php _e('Motivation:', 'framework') ?></label>

            <textarea name="motivation" id="motivation" rows="8" cols="30">
                <?php if(isset($_POST['motivation'])) { if(function_exists('stripslashes')) 
                  { echo stripslashes($_POST['motivation']); } else { echo $_POST['motivation']; } } ?>
           </textarea>
           	<?php if($postMotivationError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postMotivationError; ?></span>
				<div class="clearfix"></div>
			<?php } ?>
  
			</div>
            <div style="display:block;width:100%;margin:40px">
						
				<label for="skills" style="display:inline;width:20%;float:left"><?php _e('Skills:', 'framework') ?></label>

            <textarea name="skills" id="skills" rows="8" cols="30">
                <?php if(isset($_POST['skills'])) { if(function_exists('stripslashes')) 
                  { echo stripslashes($_POST['skills']); } else { echo $_POST['skills']; } } ?>
           </textarea>
             	<?php if($postSkillsError != '') { ?>
				<span class="error" style="color:red;font-weight:bold"><?php echo $postSkillsError; ?></span>
				<div class="clearfix"></div>
			<?php } ?> 
			</div>
			<div style="display:block;width:100%;margin:40px;">
				
				<?php wp_nonce_field('post_nonce', 'post_nonce_field'); ?>

				<input type="hidden" name="submitted" id="submitted" value="true" />
				<button type="submit"><?php _e('Add Post', 'framework') ?></button>

			</div>

		</form>

	</div>
 
<?php get_footer(); ?>