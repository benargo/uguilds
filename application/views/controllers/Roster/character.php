<h1><?php echo $character_title; ?></h1>
<nav class="breadcrumbs">
	<ul>
		<?php foreach($breadcrumbs as $url => $text): ?>
		<li><a href="<?php echo $url; ?>"<?php echo ($text === $character_name ? ' class="class '. strtolower($character_class->name) .'"' : ''); ?>><?php echo $text; ?></a></li>
		<?php endforeach; ?>
	</ul>
</nav>

<a id="character-inset" href="/roster/<?php echo strtolower($character_name); ?>/profile-picture"><img src="<?php echo $inset_image; ?>" alt="<?php echo $character_name; ?>" /></a>