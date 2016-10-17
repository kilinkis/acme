<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>

		<link href="//www.google-analytics.com" rel="dns-prefetch">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">

		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

        <?php // here is where the analytics code should be placed ?>

	</head>
	<body <?php body_class(); ?>>


			<!-- header -->
			<header>
				<div class="container">
					<nav role="navigation" class="navbar">

						<div class="navbar-header">
							<button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>
							<a href="#" class="navbar-brand"></a>
						</div>
						
						<div id="navbarCollapse" class="collapse navbar-collapse">
						<?php /* Primary navigation */
							wp_nav_menu( array(
							  'menu' => 'top_menu',
							  'depth' => 2,
							  'menu_class' => 'nav navbar-nav',
							  'walker' => new wp_bootstrap_navwalker())
							);
							?>
						</div>

					</nav>
				</div>
			</header>
			<!-- /header -->
