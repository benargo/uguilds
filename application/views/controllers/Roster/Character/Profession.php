<div class="roster character profession">
	<a id="character-inset" href="/roster/<?php echo strtolower($character->name); ?>/profile-picture"><img src="<?php echo $inset_image; ?>" alt="<?php echo $character->name; ?>" /></a>

	<h1><?php echo $character->getCurrentTitle(true); ?></h1>
	<h2 class="class <?php echo strtolower($character->class->name); ?>"><strong><?php echo $character->level; ?></strong>
		<?php echo $character->race->name; ?> 
		<?php echo $character->get_spec('active')->name; ?>
		<?php echo $character->class->name; ?>
	</h2>

	<!-- Breadcrumbs -->
	<nav class="breadcrumbs">
		<ul>
			<?php foreach($breadcrumbs as $url => $text): ?>
			<li><a href="<?php echo $url; ?>"<?php echo ($text === $character->name ? ' class="class '. strtolower($character->class->name) .'"' : ''); ?>><?php echo $text; ?></a></li>
			<?php endforeach; ?>
		</ul>
	</nav>

	<!-- Profession Bar -->
	<div class="ui-progress-bar ui-container" id="<?php echo strformat($profession->name); ?>_bar">
		<span class="ui-label">
			<span class="icon"><img src="<?php echo $profession->getIcon(18); ?>" alt="<?php echo $profession->name; ?>"></span>
			<span class="name"><?php echo $profession->name; ?></span>
			<span class="level"><?php echo $profession->rank; ?></span>
		</span>
		<div class="ui-progress" style="width: <?php echo $profession->get_percentage(); ?>%;"></div>
	</div>

	<!-- Profession -->
	<section id="profession">
		<!-- Filter -->
		<form id="filter" action="/roster/<?php echo strtolower($character->name); ?>/<?php echo strformat($profession->name); ?>/filter" method="post">
			<fieldset>
				<field for="recipe-name">Name
					<input type="text" name="recipe-name" placeholder="e.g. <?php echo array_shift(array_slice($profession->get_recipes(), 0, 1))->name; ?>" />
				</field>
			</fieldset>
		</form>
	</section>
</div>