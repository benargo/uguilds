<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $locale; ?>" manifest="/manifest">
<head>
	<?php echo $head; ?>

	<!-- Theme Files -->
	<link rel="stylesheet" href="<?php echo $theme_path; ?>/css/screen.css">
	<script src="<?php echo $theme_path; ?>/js/theme.min.js"></script>
</head>
<body class="nojs">
	<div class="container">
		<!-- Header -->
		<nav class="menu-bar">
			<?php echo $nav; ?>
		</nav>
		<header>
			<a href="/" rel="home">
				<span><img src="<?php echo $guild->get_emblem(TRUE,150); ?>" alt="<?php echo $guild->name; ?> guild emblem" width="150" /></span>
				<span class="guild-name">
					<h1><?php echo $guild->name; ?></h1>
					<h2><?php echo $guild->realm .' '. $guild->region; ?></h2>
				</span>
			</a>
		</header>
		<article>
			<?php echo $content; ?>
		</article>
		<!-- Footer -->
		<footer>
			<?php echo $footer; ?>
		</footer>
		<!-- End Footer -->
	</div>
</body>
</html>
