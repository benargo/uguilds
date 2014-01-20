<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- Meta Information -->
<meta charset="UTF-8">
<meta name="author" content="<?php echo $guild->name; ?>">
<meta name="application-name" content="uGuilds" />
<meta name="generator" content="CodeIgniter" />
<meta name="keywords" content="<?php echo $guild->guildName; ?>, <?php echo $guild->realm; ?>, guild, website, world, warcraft, wow, mists, pandaria, mop">
<meta name="description" content="World of Warcraft guild <?php echo $guild->guildName; ?> on <?php echo $guild->realm . ' ' . $guild->region; ?>">
<meta name="apple-mobile-web-app-title" content="My Guild">
<title><?php echo $page_title; ?></title>

<!-- CSS -->
<link rel="stylesheet" media="all" href="/media/css/uGuilds.css">
<?php foreach($controller_css as $file): ?>
<link rel="stylesheet" media="all" href="<?php echo $file; ?>">
<?php endforeach; ?>

<!-- JavaScript -->
<script src="//code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="/media/js/uGuilds.min.js"></script>
<?php foreach($controller_js as $file): ?>
<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<!-- Shortcut Icons -->
<link type="image/png" rel="icon" href="<?php echo $guild->getEmblem(FALSE, 64); ?>" />
<link rel="apple-touch-icon" href="<?php echo $guild->getEmblem(FALSE, 60); ?>" />
