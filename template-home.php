<?php 

/* Template Name: Home Page Template */

get_header(); 

// hero area
$hero_title = get_post_meta( get_the_ID(), 'meta-box-title', true );
$hero_subtitle = get_post_meta( get_the_ID(), 'meta-box-subtitle', true );
// Check if the custom field has a value (only the title is required)
if ( ! empty( $hero_title ) ) { ?>
    <main>
		<div class="container-fluid">
			<h1><?php echo $hero_title; ?><br>
			<small><?php echo $hero_subtitle; ?></small></h1>
		</div>
	</main>
<?php } ?>

<!-- section -->
<section class="intro">
	<div class="container">

	<?php 	$hero_title_after = get_post_meta( get_the_ID(), 'meta-box-title-after', true ); ?>
	<h2><?php echo $hero_title; ?></h2>

	<div class="row">

		<div class="col-sm-6">
			<figure>
				<?php
				if ( has_post_thumbnail() ) {
			        the_post_thumbnail();
			    } ?>
			</figure>
		</div>

		<div class="col-sm-6">


	
	<?php
	 if (have_posts()): while (have_posts()) : the_post(); ?>
			<?php the_content(); ?>
	<?php endwhile; ?>

	<?php else: 
		  _e( 'Sorry, nothing to display.', 'acme' );
		  endif; ?>
		</div>
	</div> <!-- /row -->
	</div> <!-- container -->
</section>
<!-- /section -->

<?php 

echo do_shortcode("[meet_the_team]" );

get_footer(); ?>
