<?php get_header(); ?>
<div id="main-content">
	<div class="container">
		<div id="content-area" class="clearfix">
			<div id="left-area">
			<?php while ( have_posts() ) : the_post(); 
					$term_work_type=get_the_terms(get_the_ID() , 'savi_opp_cat_work_type' ); 
					$terms_work_area=get_the_terms(get_the_ID() , 'savi_opp_cat_work_area' ); 
					$terms_skills_gain=get_post_meta( get_the_ID(), "skills_gain", true );
					$terms_daily_tasks=get_post_meta( get_the_ID(), "daily_tasks", true );
					$terms_startdate=get_post_meta( get_the_ID(), "startdate", true );
					$terms_languages=get_the_terms(get_the_ID() , 'savi_opp_tag_languages' ); 
					$terms_soft=get_the_terms(get_the_ID() , 'savi_opp_tag_soft' ); 
					$terms_duration=get_post_meta( get_the_ID(), "duration", true );
					$terms_morning=get_post_meta( get_the_ID(), "morningtimings", true );
					$terms_afternoon=get_post_meta( get_the_ID(), "afternoontimings", true );
					$contactName =  get_post_meta( get_the_ID(), "contactPerson", true ); 
					$contactNumber = get_post_meta( get_the_ID(), "contactPhone", true );
					$contactEmail = get_post_meta( get_the_ID(), "contactEmail", true );
					$content= get_the_content();
					$oppID = get_the_ID();
					$showcontact = FALSE;	
						global $wpdb;
						$user_ID = get_current_user_id();
						//echo $user_ID;
						$profile = $wpdb->get_results("select post_type from wp_posts, wp_postmeta
where	wp_postmeta.meta_key = 'user_id'
and		wp_postmeta.meta_value = $user_ID
and		wp_posts.ID = wp_postmeta.post_id");
						$profilepostsarray = array();
						foreach ( $profile as $profileposts ) {
							$profilepostsarray[] = $profileposts->post_type;
						}
						$profilepost = join( ", ", $profilepostsarray );
						//echo $profilepost;
					switch($profilepost){
						case 'view_0':
							$showcontact=false;
						break;
						case 'view_1':
							$showcontact=false;
						break;
						case 'view_2':
							$showcontact=false;
						break;
						case 'view_3':
							$showcontact=true;
						break;
						case 'view_4':
							$showcontact=false;		
						break;
						case 'view_5':
							$showcontact=false;
						break;
						case 'view_6':
							$showcontact=false;
						break;
						case 'view_7':
							$showcontact=false;
						break;
					}
					
					if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom')); 
					$no_of_opportunities=get_post_meta( get_the_ID(), "no_of_opportunities", true );
					if($no_of_opportunities > 0 ){
					?>
					
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
						<h1 class="main_title"><?php the_title(); ?></h1>
							<div id="content_opp">
								<ul>
									<li>
										<div><span class="left_li_s">Unit</span>
										<span class="right_li_l">
										<?php 
											$id= get_post_meta(get_the_ID(), "av_unit", true );
											$query = new WP_Query(array(  'post_type' => 'av_unit',  'post__in' => array($id)));
											if ( $query->have_posts() ){
											echo '<ul>';
												while ( $query->have_posts() ) : 
													$query->the_post();	?>
									<li>
											<a href="<?php echo get_permalink(); ?>"><?php echo  the_title(); ?></a> 
									</li>
										<?php 	//echo get_post_meta( get_the_ID(), "landphone", true ); 
												endwhile;
										echo '</ul>';
									}
									else{
									?>
									<div class="entry">
										<!--If no results are found-->
											<p><?php esc_html_e('No Opportunity Specified','Divi'); ?></p>
									</div>
								<?php	} ?>	
										</span>
									</li>
									<li>
										<div><p><span class="left_li_s">Area</span>
											<?php if ( $terms_work_area && ! is_wp_error( $terms_work_area ) ) {
													$i=0;
													foreach ( $terms_work_area as $terms_work_area ){  
													if ($i >0) echo ',';?>
														<a href="<?php echo get_term_link( $terms_work_area->slug, 'savi_opp_cat_work_area' )?>"><?php echo $terms_work_area->name;?></a>			
													<?php $i++;
														} 							
													}
													else esc_html_e('No Languages Specified','Divi'); ?></p></div>
									</li>
									<li>
										<div><p class=""><span class="left_li_s">Type</span>
											<?php if ( $term_work_type && ! is_wp_error( $term_work_type ) ) {	
													$i=0;
													foreach ( $term_work_type as $term_work_type ){  
													if ($i >0) echo ',';?>
														<a href="<?php echo get_term_link( $term_work_type->slug, 'savi_opp_cat_work_type' )?>"><?php echo $term_work_type->name;?></a>
													<?php $i++;
														} 
													}
													else esc_html_e('No Languages Specified','Divi'); ?>
										</p></div>
									</li>
									<li>
										<div><span class="left_li_s">Skills Gain</span> 
											<?php echo $terms_skills_gain ?></div>
									</li>
									<li>
										<div><span class="left_li_s">Daily Tasks</span> <?php echo $terms_daily_tasks ?></div>
									</li>
									<li>
										<div class="left_li_s">Start Date</div>
										<div class="right_li_m"><?php 
											$new_format_date = date("M d, Y", strtotime($terms_startdate));
											echo $new_format_date;
										?></div>
									</li>
									<li>
										<div class="left_li_m">Duration (in month)</div>
										<div class="right_li_me"><?php echo $terms_duration ?></div>
									</li>
									<li>
										<div class="left_li_m">Morning Timings</div>
										<div class="right_li_me"><?php echo $terms_morning ?></div>
									</li>
									<li>
										<div class="left_li_m">Afternoon Timings</div>
										<div class="right_li_me"><?php echo $terms_afternoon ?></div>
									</li>
									<li>
										<div><p><span class="left_li_s">Languages</span>
										<?php if ( $terms_languages && ! is_wp_error( $terms_languages ) ) {
											$i=0;
											foreach ( $terms_languages as $terms_languages ){  
											if ($i >0) echo ',';?>
												<a href="<?php echo get_term_link( $terms_languages->slug, 'savi_opp_tag_languages' )?>"><?php echo $terms_languages->name;?></a>												
											<?php $i++;
												} 											
											}
											else esc_html_e('No Languages Specified','Divi'); ?></p></div>
									</li>
									<li>
										<div><p><span class="left_li_s">Software</span>
										<?php if ( $terms_soft && ! is_wp_error( $terms_soft ) ) {
											$i=0;
											foreach ( $terms_soft as $terms_soft ){  
											if ($i >0) echo ',';?>
												<a href="<?php echo get_term_link( $terms_soft->slug, 'savi_opp_tag_soft' )?>"><?php echo $terms_soft->name;?></a>											
											<?php $i++;
												} 										
											}
											else esc_html_e('No Software Specified','Divi'); ?></p></div>
									</li>
								<ul>
							</div>				
							<div id="content_side">
								<?php
									$thumb = '';
									$template_dir = get_stylesheet_directory_uri();
									$loading_image  = $template_dir."/images/ajax-loader.gif";
									$width = (int) apply_filters( 'et_pb_index_blog_image_width', 400 );
									$height = (int) apply_filters( 'et_pb_index_blog_image_height', 270 );
									$classtext = 'et_featured_image';
									$titletext = get_the_title();
									$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
									$thumb = $thumbnail["thumb"];
									$userId = wp_get_current_user()->ID;
									$profile_post_id = get_user_meta($userId, 'profile_post_id', true);
									$is_opportunity_exists ='no';
									$expressOpportunitiesMeta = get_post_meta( $profile_post_id, 'express_opportunities', false );
									$allexpressOpportunities = $expressOpportunitiesMeta[0];
									$allexpressOpportunities_id = array();
     							   if (sizeof($allexpressOpportunities) > 0 && is_array($allexpressOpportunities)) {
        								foreach($allexpressOpportunities as $key=>$expressOpportunity) {
                					        $expressOpportunitiesID = $expressOpportunity['express_opportunity'];
                      					  $allexpressOpportunities_id[] = $expressOpportunitiesID;                             
										}
        							} 
									if(in_array($oppID,$allexpressOpportunities_id)):
										$is_opportunity_exists ='yes';
									endif; 
									if ( 'on' === et_get_option( 'divi_thumbnails', 'on' ) && '' !== $thumb )
										print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height );
								?>
							</div>
							<div class="clear"></div>
								<h1 class="main_title2">Other Details</h1>
								<div id="content_opp">
								<?php
									echo $content;
									wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'Divi' ), 'after' => '</div>' ) );
								?>
								</div> <!-- .entry-content -->
								<?php  if ( $showcontact ) {	 ?>
								<div class="contact_info">
								<?php 
									
									echo '<h2>Contact</h2>';
									echo '<strong>Memtor Name</strong>: '.$contactName.'<br />';
									echo '<strong>Email</strong>: '.$contactEmail.'<br />';
									echo '<strong>Phone Number</strong>: '.$contactNumber.'<br />';
							
								?>
								</div>
						<?php 	} 
							if ( is_user_logged_in() ) { ?> 
								<div id="select-icon-container">
									<input type="checkbox" name="<?php echo wp_get_current_user()->ID ?>"
									 value="<?php echo $oppID; ?>" id="select-icon"
									 
									 <?php if($is_opportunity_exists == 'yes'): ?> checked='checked' <?php endif;?> />
									 <label for="select-icon"></label>
									 <div id="loading_image" style="display:none"><img src="<?php echo $loading_image ?>" alt="" ></div>
									<!--div id="selectResult"><p>Post ID :<?php #echo $oppID; ?>, User :<?php #echo wp_get_current_user()->ID ?></p></div-->
								</div>
						<?php }
									if ( et_get_option('divi_468_enable') == 'on' ){
										echo '<div class="et-single-post-ad">';
										if ( et_get_option('divi_468_adsense') <> '' ) echo( et_get_option('divi_468_adsense') );
										else { ?>
											<a href="<?php echo esc_url(et_get_option('divi_468_url')); ?>"><img src="<?php echo esc_attr(et_get_option('divi_468_image')); ?>" alt="468 ad" class="foursixeight" /></a>
								<?php 	}
										echo '</div> <!-- .et-single-post-ad -->';
									}
									if ( comments_open() && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) )
										comments_template( '', true );
								?>
							</article> <!-- .et_pb_post -->
						<?php 
						}else{
							echo "Sorry, This Opportunity is already taken";
						}
						if (et_get_option('divi_integration_single_bottom') <> '' && et_get_option('divi_integrate_singlebottom_enable') == 'on') echo(et_get_option('divi_integration_single_bottom'));  
					endwhile; ?>
				</div> <!-- #left-area -->
			<?php get_sidebar(); ?>
		</div> <!-- #content-area -->
	</div> <!-- .container -->
</div> <!-- #main-content -->
<?php get_footer(); ?>