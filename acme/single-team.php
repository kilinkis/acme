<?php get_header(); ?>

	<section class="intro">
		<div class="container">

			<h1 class="light-blue"><?php the_title(); ?></h1>

			<div class="row">

				<div class="col-sm-2"></div>

				<div class="col-sm-4">
					<figure class="rounded-avatar">
						<?php
						if ( has_post_thumbnail() ) {
					        the_post_thumbnail();
					    } else {
						    echo '<img src="' . get_bloginfo( 'stylesheet_directory' ) 
						        . '/img/gravatar.jpg" />';
						}
						?>
					</figure>
				</div>

				<div class="col-sm-4">
					<?php
					 if (have_posts()): while (have_posts()) : the_post(); ?>
							<?php the_content(); ?>
					<?php
					endwhile;
					endif;
					?>
				</div>
				
				<div class="col-sm-2"></div>

			</div> <!-- /row -->

		 </div>
	</section>

<?php get_footer(); ?>
