<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $locale; ?>" manifest="/manifest">
<head>
	<?php echo $head; ?>

	<!-- Theme Files -->
	<link rel="stylesheet" href="<?php echo $theme_path; ?>/css/skeleton.css">
	<link rel="stylesheet" href="<?php echo $theme_path; ?>/css/screen.css">
	<script src="<?php echo $theme_path; ?>/js/theme.min.js"></script>
</head>
<body>
	<div class="container">
		<!-- Header -->
		<nav class="menu-bar sixteen columns row">
			<?php echo $nav; ?>
		</nav>
		<header class="sixteen columns row">
			<a href="/" rel="home">
				<span><img src="<?php echo $guild->getEmblem(TRUE,150); ?>" alt="<?php echo $guild->name; ?> guild emblem" width="150" /></span>
				<span class="guild-name">
					<h1><?php echo $guild->name; ?></h1>
					<h2><?php echo $guild->realm .' '. $guild->region; ?></h2>
				</span>
			</a>
		</header>
		<article class="sixteen columns row">
			<?php echo $content; ?>
		</article>
		<!-- Footer -->
		<footer class="sixteen columns row">
			<?php echo $footer; ?>
		</footer>
		<!-- End Footer -->
	</div>
</body>
</html>
