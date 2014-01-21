<div class="<?php echo strtolower($faction); ?> roster character">
	<a id="character-inset" href="/roster/<?php echo strtolower($character->name); ?>/profile-picture"><img src="<?php echo $inset_image; ?>" alt="<?php echo $character->name; ?>" /></a>

	<h1><?php echo $character->getCurrentTitle(true); ?></h1>
	<h2>Level <?php echo $character->level; ?> 
		<?php echo $character->race->name; ?> 
		<?php echo $character->getSpec('active')->name; ?>
		<span class="class <?php echo strtolower($character->class->name); ?>"><?php echo $character->class->name; ?></span>
	</h2>
	<nav class="breadcrumbs">
		<ul>
			<?php foreach($breadcrumbs as $url => $text): ?>
			<li><a href="<?php echo $url; ?>"<?php echo ($text === $character->name ? ' class="class '. strtolower($character->class->name) .'"' : ''); ?>><?php echo $text; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</nav>
</div>