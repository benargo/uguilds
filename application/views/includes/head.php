<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $locale; ?>">
<head>

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
	<link rel="stylesheet" media="all" href="/media/css/uGuilds.css">
	<link rel="stylesheet" media="all" href="//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<?php echo $theme->css; ?>

	<!-- JAVASCRIPT -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/<?php echo $theme->jquery_version; ?>/jquery.min.js"></script>
	<script src="//code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
	<?php echo $theme->javascript; ?>

	<!-- SHORTCUT ICONS -->
	<link type="image/png" rel="icon" href="<?php echo $guild->getEmblem(FALSE, 64); ?>" />
	<link rel="apple-touch-icon" href="<?php echo $guild->getEmblem(FALSE, 60); ?>" />
</head>
<body>
