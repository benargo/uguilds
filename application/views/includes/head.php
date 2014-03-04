<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!-- Meta Information -->
<meta charset="UTF-8">
<meta name="author" content="<?php echo $guild->name; ?>">
<meta name="application-name" content="uGuilds" />
<meta name="generator" content="CodeIgniter" />
<meta name="keywords" content="<?php echo $guild->guildName; ?>, <?php echo $this->guild->realm; ?>, guild, website, world, warcraft, wow, mists, pandaria, mop">
<meta name="description" content="World of Warcraft guild <?php echo $this->guild->guildName; ?> on <?php echo $this->guild->realm . ' ' . $this->guild->region; ?>">
<meta name="apple-mobile-web-app-title" content="My Guild">
<title><?php echo $page_title; ?></title>

<!-- CSS -->
<link rel="stylesheet" media="all" href="/media/css/uGuilds.css">
<?php foreach($theme->get_css_files() as $file): ?>
<link rel="stylesheet" media="all" href="<?php echo $file; ?>">
<?php endforeach; ?>

<!-- JavaScript -->
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script src="//static.wowhead.com/widgets/power.js"></script>
<script src="/media/js/uGuilds.min.js"></script>
<?php foreach($theme->get_javascript_files() as $file): ?>
<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<!-- Shortcut Icons -->
<link type="image/png" rel="icon" href="<?php echo $this->guild->getEmblem(FALSE, 64); ?>" />
<link rel="apple-touch-icon" href="<?php echo $this->guild->getEmblem(FALSE, 60); ?>" />
