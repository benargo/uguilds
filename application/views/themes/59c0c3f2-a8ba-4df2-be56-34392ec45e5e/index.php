<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $locale; ?>" manifest="<?php echo $manifest; ?>">
<head>
	<!-- Meta Information -->
	<?php echo $app->meta; ?>

	<!-- Page Title -->
	<title><?php echo $app->page_title; ?></title>

	<!-- CSS -->
	<?php echo $app->system_css; ?>
	<link rel="stylesheet" media="screen" href="<?php echo $theme_url; ?>/css/screen.css">

	<!-- JavaScript -->
	<?php echo $app->system_js; ?>
	<script src="<?php echo $theme_url; ?>/js/jquery.sticky.js"></script>
	<script src="<?php echo $theme_url; ?>/js/theme.js"></script>
</head>
<body>
	<!-- Header -->
	<nav class="menu-bar">
		<?php echo $app->nav; ?>
	</nav>
	<header>
		<a href="/" rel="home">
			<span>
				<?php echo $guild->emblem(TRUE,150); ?>
			</span>
			<span class="guild-name">
				<h1><?php echo $guild->name; ?></h1>
				<h2><?php echo $guild->realm; ?> <?php echo $guild->region; ?></h2>
			</span>
		</a>
	</header>
	<!-- End Header -->
	<?php echo $app->controller; ?>
	<!-- Footer -->
	<?php echo $app->footer; ?>