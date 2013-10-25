<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="<?php echo $this->uguilds->locale; ?>">
<head>
	<!-- META INFORMATION -->
	<meta charset="UTF-8">
	<meta name="author" content="Ben Argo">
	<meta name="keywords" content="<?php echo $this->uguilds->guild->guildName; ?>, <?php echo $this->uguilds->guild->realm; ?>, guild, website, world, warcraft, wow, mists, pandaria, mop">
	<meta name="description" content="World of Warcraft guild <?php echo $this->uguilds->guild->guildName; ?> on <?php echo $this->uguilds->guild->realm . ' ' . $this->uguilds->guild->region; ?>">
	<title><?php echo $page_title; ?></title>

	<!-- CSS -->
	<?php foreach($this->uguilds->theme->getCssFiles() as $css_file)
	{
		echo $css_file;
	} ?>

	<!-- JAVASCRIPT -->
	<?php foreach($this->uguilds->theme->getJavaScriptFiles() as $js_file)
	{
		echo $js_file;
	} ?>

	<!-- SHORTCUT ICONS -->
	<!--<link type="image/x-icon" rel="shortcut icon" href="/media/images/favicon.ico" />
	<link rel="apple-touch-icon" href="/media/images/iphone-icon.png" />-->
</head>
<body>
