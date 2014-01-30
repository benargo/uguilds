<div class="<?php echo strtolower($faction); ?> roster character">
	<a id="character-inset" href="/roster/<?php echo strtolower($character->name); ?>/profile-picture"><img src="<?php echo $inset_image; ?>" alt="<?php echo $character->name; ?>" /></a>

	<h1><?php echo $character->getCurrentTitle(true); ?></h1>
	<h2 class="class <?php echo strtolower($character->class->name); ?>"><strong><?php echo $character->level; ?></strong>
		<?php echo $character->race->name; ?> 
		<?php echo $character->get_spec('active')->name; ?>
		<?php echo $character->class->name; ?>
	</h2>

	<!-- Achievement Points -->
	<p class="achievements"><?php echo $character->achievementPoints; ?> <img src="/media/images/achievements.gif" alt="achievement points"> (<?php echo $character->get_achievements_position(); ?>)</p>

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
			<?php if($character->get_spec('primary')): ?>
			<a href="javascript:;" class="primary spec"><img src="<?php echo $character->get_spec('primary')->getIcon(56); ?>" alt="<?php echo $character->get_spec('primary')->name; ?>" height="32"><?php echo $character->get_spec('primary')->name; ?></a>
			<?php endif; ?>
			<?php if($character->get_spec('secondary')): ?>
				<a href="javascript:;" class="secondary spec"><img src="<?php echo $character->get_spec('secondary')->getIcon(56); ?>" alt="<?php echo $character->get_spec('secondary')->name; ?>" height="32"><?php echo $character->get_spec('secondary')->name; ?></a>
			<?php endif; ?>
		</p>
		<?php if($character->get_spec('primary')): ?>
			<ol class="talents primary">
			<?php foreach($character->get_spec('primary')->talents as $talent): ?>
				<li data-level="<?php echo $talent->level; ?>"><a class="wh" rel="spell=<?php echo $talent->spell->id; ?>"><img src="<?php echo $talent->spell->getIcon(18); ?>" alt="<?php echo $talent->spell->name; ?>"><?php echo $talent->spell->name; ?></a></li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>
		<?php if($character->get_spec('secondary')): ?>
			<ol class="talents secondary">
			<?php foreach($character->get_spec('secondary')->talents as $talent): ?>
				<li data-level="<?php echo $talent->level; ?>"><a class="wh" rel="spell=<?php echo $talent->spell->id; ?>"><img src="<?php echo $talent->spell->getIcon(18); ?>" alt="<?php echo $talent->spell->name; ?>"><?php echo $talent->spell->name; ?></a></li>
			<?php endforeach; ?>
			</ol>
		<?php endif; ?>

		<?php if($character->get_spec('primary')): ?>
			<span class="glyphs primary">
				<p>Major Glyphs</p>
				<ul>
					<?php $glyph_level = 25; ?>
					<?php foreach($character->get_spec('primary')->glyphs['major'] as $glyph): ?>
					<li><a class="wh" rel="item=<?php echo $glyph->item->id; ?>"><img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>"> <?php echo $glyph->name; ?></a></li>
					<?php $glyph_level += 25; ?>
					<?php endforeach; ?>
				</ul>

				<p>Minor Glyphs</p>
				<ul>
				<?php foreach($character->get_spec('primary')->glyphs['minor'] as $glyph): ?>
					<li><a class="wh" rel="item=<?php echo $glyph->item->id; ?>"><img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>"> <?php echo $glyph->name; ?></a></li>
				<?php endforeach; ?>
				</ul>
			</span>
		<?php endif; ?>

		<?php if($character->get_spec('secondary')): ?>
			<span class="glyphs secondary">
				<p>Major Glyphs</p>
				<ul>
					<?php $glyph_level = 25; ?>
					<?php foreach($character->get_spec('secondary')->glyphs['major'] as $glyph): ?>
					<li><a class="wh" rel="item=<?php echo $glyph->item->id; ?>"><img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>"> <?php echo $glyph->name; ?></a></li>
					<?php $glyph_level += 25; ?>
					<?php endforeach; ?>
				</ul>

				<p>Minor Glyphs</p>
				<ul>
				<?php foreach($character->get_spec('secondary')->glyphs['minor'] as $glyph): ?>
					<li><a class="wh" rel="item=<?php echo $glyph->item->id; ?>"><img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>"> <?php echo $glyph->name; ?></a></li>
				<?php endforeach; ?>
				</ul>
			</span>
		<?php endif; ?>
	</section>
	<!-- Talents -->

	<!-- Professions -->
	<section id="professions">
		<h3>Professions</h3>
		<ul>
		<?php foreach($character->professions as $profession): ?>
			<li><div class="ui-progress-bar ui-container" id="<?php echo strformat($profession->name); ?>_bar">
					<span class="ui-label">
						<a href="/roster/<?php echo strtolower($character->name); ?>/<?php echo strformat($profession->name, '-'); ?>">
							<span class="icon"><img src="<?php echo $profession->getIcon(18); ?>" alt="<?php echo $profession->name; ?>"></span>
							<span class="name"><?php echo $profession->name; ?></span>
							<span class="level"><?php echo $profession->rank; ?></span>
						</a>
					</span>
					<div class="ui-progress" style="width: <?php echo $profession->get_percentage(); ?>%;"></div>

				</div>
			</li>
		<?php endforeach; ?>
		</ul>
	</section>
	<!-- Professions -->


</div>