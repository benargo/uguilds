<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $locale; ?>">
<head>
	<!--
	I apologise for the fact the HTML isn't as pretty as I'd like
	it to be. However, it does look very pretty in CodeIgniter.
	-->

	<!-- META INFORMATION -->
	<meta charset="UTF-8">
	<meta name="author" content="<?php echo $theme->data->page_author; ?>">
	<meta name="application-name" content="uGuilds" />
	<meta name="generator" content="CodeIgniter" />
	<meta name="keywords" content="<?php echo $guild->guildName; ?>, <?php echo $guild->realm; ?>, guild, website, world, warcraft, wow, mists, pandaria, mop">
	<meta name="description" content="World of Warcraft guild <?php echo $guild->guildName; ?> on <?php echo $guild->realm . ' ' . $guild->region; ?>">
	<meta name="apple-mobile-web-app-title" content="My Guild">
	<title><?php echo $theme->data->page_title; ?></title>

	<!-- CSS -->
	<?php echo $theme->css; ?>

	<!-- JAVASCRIPT -->
	<?php echo $theme->javascript; ?>

	<!-- SHORTCUT ICONS -->
	<!--<link type="image/x-icon" rel="shortcut icon" href="/media/images/favicon.ico" />-->
	<link rel="apple-touch-icon" href="<?php echo $guild->getEmblem(FALSE, 60); ?>" />
</head>
<body>
