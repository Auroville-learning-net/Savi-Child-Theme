
<div id="left-grid">
					<div id="left-image">
					<?php
					$thumb = '';

					$width = (int) apply_filters( 'et_pb_index_blog_image_width', 300 );

					$height = (int) apply_filters( 'et_pb_index_blog_image_height', 200 );
					$classtext = 'et_pb_post_main_image';
					$titletext = get_the_title();
					$thumbnail = get_thumbnail( $width, $height, $classtext, $titletext, $titletext, false, 'Blogimage' );
					$thumb = $thumbnail["thumb"];

					if ( 'on' == et_get_option( 'divi_thumbnails_index', 'on' ) && '' !== $thumb ) : ?>
						<a href="<?php the_permalink(); ?>">
							<?php print_thumbnail( $thumb, $thumbnail["use_timthumb"], $titletext, $width, $height ); ?>
						</a>
						<?php
							endif;
						?>
					

					</div>
					<div id="left-Content">
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'et_pb_post' ); ?>>
					<div class="table1">
					<ul>
					<li>
						<div class="left_li_arc">Unit Name :</div>
						<div class="right_li"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
					</li>
					<li>
						<div class="left_li_arc"> </div>
						<div class="right_li"><?php the_excerpt(); ?></div>
					</li>
					<li>
						<div class="left_li_arc"> Location : </div>
						<div class="right_li"><?php echo get_post_meta( get_the_ID(), "_et_listing_custom_address", true ); ?></div>
					</li>
					
									</ul>
								</div>
							</article> <!-- .et_pb_post -->
						</div> <!--left content-->
					</div> <!---left grid -->