<div class="<?php echo strtolower($faction); ?> roster character">
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

	<!-- Talents -->
	<section id="talents">
		<h3>Talents</h3>
		<p class="talent-calc"><a href="http://<?php echo $guild->region; ?>.battle.net/wow/en/tool/talent-calculator#<?php echo $character->get_talent_calculator_url('active'); ?>" target="_blank">View in talent calculator</a></p>
		<p class="specs">
			<a href="javascript:;" class="primary spec"><img src="<?php echo $character->get_spec('primary')->getIcon(56); ?>" alt="<?php echo $character->get_spec('primary')->name; ?>" height="32"><?php echo $character->get_spec('primary')->name; ?></a>
			<a href="javascript:;" class="secondary spec"><img src="<?php echo $character->get_spec('secondary')->getIcon(56); ?>" alt="<?php echo $character->get_spec('secondary')->name; ?>" height="32"><?php echo $character->get_spec('secondary')->name; ?></a>
		</p>
		<ol class="talents active">
		<?php foreach($character->get_spec('active')->talents as $talent): ?>
			<li data-level="<?php echo $talent->level; ?>"><a class="wh" rel="spell=<?php echo $talent->spell->id; ?>"><img src="<?php echo $talent->spell->getIcon(18); ?>" alt="<?php echo $talent->spell->name; ?>"><?php echo $talent->spell->name; ?></a></li>
		<?php endforeach; ?>
		</ol>
		<ol class="talents passive">
		<?php foreach($character->get_spec('passive')->talents as $talent): ?>
			<li data-level="<?php echo $talent->level; ?>"><a class="wh" rel="spell=<?php echo $talent->spell->id; ?>"><img src="<?php echo $talent->spell->getIcon(18); ?>" alt="<?php echo $talent->spell->name; ?>"><?php echo $talent->spell->name; ?></a></li>
		<?php endforeach; ?>
		</ol>
		<span class="glyphs active">
			<p>Major Glyphs</p>
			<ul>
				<?php $glyph_level = 25; ?>
				<?php foreach($character->get_spec('active')->glyphs['major'] as $glyph): ?>
				<li><a class="wh" rel=""></a></li>
				<?php $glyph_level += 25; ?>
				<?php endforeach; ?>
			</ul>
		</span>
	</section>
</div>