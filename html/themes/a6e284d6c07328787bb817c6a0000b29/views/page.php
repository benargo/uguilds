<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $guild->locale; ?>" manifest="/manifest">
<head>
	<?php get_include('head'); ?>
</head>
<body class="nojs">
	<div class="container">
		<!-- Header -->
		<nav class="menu-bar">
			<?php get_include('nav'); ?>
		</nav>
		<header>
			<a href="/" rel="home">
				<span><img src="<?php echo $guild->getEmblem(TRUE,150); ?>" alt="<?php echo $guild->name; ?> guild emblem" width="150" /></span>
				<span class="guild-name">
					<h1><?php echo $guild->name; ?></h1>
					<h2><?php echo $guild->realm .' '. $guild->region; ?></h2>
				</span>
			</a>
		</header>
		<article>
			<?php get_subview(); ?>
		</article>
		<!-- Footer -->
		<footer>
			<?php get_include('footer'); ?>
		</footer>
		<!-- End Footer -->
	</div>
</body>
</html>
