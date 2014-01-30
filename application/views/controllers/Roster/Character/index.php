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

	<!-- Achievement Points -->
	<p class="achievements"><?php echo $character->achievementPoints; ?> <img src="/media/images/achievements.gif" alt="achievement points"> (<?php echo $character->get_achievements_position(); ?>)</p>

	<!-- Talents & Specs -->
	<section id="specs">
		<div id="talents">
			<h3>Talents</h3>
			
			<!-- Specs -->
			<div class="specs">

				<?php if($character->get_spec('primary')): ?>
				<!-- Primary Spec -->
				<a href="javascript:;" class="primary spec<?php echo ($character->get_spec('primary')->selected ? ' active' : ' passive'); ?>">
					<p>
						<img src="<?php echo $character->get_spec('primary')->getIcon(56); ?>" alt="<?php echo $character->get_spec('primary')->name; ?>" height="32">
						<?php echo $character->get_spec('primary')->name; ?>
					</p>
				</a>
				<?php endif; ?>
				
				<?php if($character->get_spec('secondary')): ?>
				<!-- Secondary Spec -->
				<a href="javascript:;" class="secondary spec<?php echo ($character->get_spec('secondary')->selected ? ' active' : ' passive'); ?>">
					<p>
						<img src="<?php echo $character->get_spec('secondary')->getIcon(56); ?>" alt="<?php echo $character->get_spec('secondary')->name; ?>" height="32">
						<?php echo $character->get_spec('secondary')->name; ?>
					</p>
				</a>
				<?php endif; ?>

			</div>
			<!-- Specs -->

			<!-- Talents -->
			<div class="talents">
				<?php if($character->get_spec('primary')): ?>
					<!-- Primary Spec -->
					<ol class="talents primary<?php echo ($character->get_spec('primary')->selected ? ' active' : ' passive'); ?>">
					
					<?php foreach($character->get_spec('primary')->talents as $talent): ?>
						<li data-level="<?php echo $talent->level; ?>">
							<a class="wh" rel="spell=<?php echo $talent->spell->id; ?>">
								<img src="<?php echo $talent->spell->getIcon(18); ?>" alt="<?php echo $talent->spell->name; ?>">
								<?php echo $talent->spell->name; ?>
							</a>
						</li>
					<?php endforeach; ?>

					</ol>
				<?php endif; ?>
			
				<?php if($character->get_spec('secondary')): ?>
					<!-- Secondary Spec -->
					<ol class="talents secondary<?php echo ($character->get_spec('secondary')->selected ? ' active' : ' passive'); ?>">
					
					<?php foreach($character->get_spec('secondary')->talents as $talent): ?>
						<li data-level="<?php echo $talent->level; ?>">
							<a class="wh" rel="spell=<?php echo $talent->spell->id; ?>">
								<img src="<?php echo $talent->spell->getIcon(18); ?>" alt="<?php echo $talent->spell->name; ?>">
								<?php echo $talent->spell->name; ?>
							</a>
						</li>
					<?php endforeach; ?>

					</ol>
				<?php endif; ?>
			</div>
			<!-- Talents -->

			<p class="talent-calc"><a href="http://<?php echo strtolower($guild->region); ?>.battle.net/wow/en/tool/talent-calculator#<?php echo $character->get_talent_calculator_url('active'); ?>" target="_blank">View in talent calculator</a></p>

		</div>
		<!-- Talents & Specs -->

		<!-- Glyphs -->
		<div id="glyphs">

			<?php if($character->get_spec('primary')): ?>

				<!-- Primary Spec -->
				<span class="glyphs primary<?php echo ($character->get_spec('primary')->selected ? ' active' : ' passive'); ?>">
					
					<h3>Glyphs</h3>

					<?php if($character->get_spec('primary')->glyphs['major']): ?>
						<p>Major Glyphs</p>
						<ul>
							<?php $glyph_level = 0; ?>
							
							<?php foreach($character->get_spec('primary')->glyphs['major'] as $glyph): ?>

								<?php $glyph_level += 25; ?>

								<li data-level="<?php echo $glyph_level; ?>">
									<a class="wh" rel="item=<?php echo $glyph->item->id; ?>">
										<img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>">
										<?php echo $glyph->name; ?>
									</a>
								</li>
							
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if($character->get_spec('primary')->glyphs['minor']): ?>
						<p>Minor Glyphs</p>
						<ul>
							<?php $glyph_level = 0; ?>
							
							<?php foreach($character->get_spec('primary')->glyphs['minor'] as $glyph): ?>

								<?php $glyph_level += 25; ?>

								<li data-level="<?php echo $glyph_level; ?>">
									<a class="wh" rel="item=<?php echo $glyph->item->id; ?>">
										<img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>">
										<?php echo $glyph->name; ?>
									</a>
								</li>

							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</span>
				<!-- Primary Spec -->

			<?php endif; ?>

			<?php if($character->get_spec('secondary')): ?>

				<!-- Secondary Spec -->
				<span class="glyphs secondary<?php echo ($character->get_spec('secondary')->selected ? ' active' : ' passive'); ?>">
					
					<h3>Glyphs</h3>

					<?php if($character->get_spec('secondary')->glyphs['major']): ?>
						<p>Major Glyphs</p>
						<ul>
							<?php $glyph_level = 0; ?>
							
							<?php foreach($character->get_spec('secondary')->glyphs['major'] as $glyph): ?>

								<?php $glyph_level += 25; ?>

								<li data-level="<?php echo $glyph_level; ?>">
									<a class="wh" rel="item=<?php echo $glyph->item->id; ?>">
										<img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>">
										<?php echo $glyph->name; ?>
									</a>
								</li>
							
							<?php endforeach; ?>
						</ul>
					<?php endif; ?>

					<?php if($character->get_spec('secondary')->glyphs['minor']): ?>
						<p>Minor Glyphs</p>
						<ul>
							<?php $glyph_level = 0; ?>
							
							<?php foreach($character->get_spec('secondary')->glyphs['minor'] as $glyph): ?>

								<?php $glyph_level += 25; ?>
								
								<li data-level="<?php echo $glyph_level; ?>">
									<a class="wh" rel="item=<?php echo $glyph->item->id; ?>">
										<img src="<?php echo $glyph->item->getIcon(18); ?>" alt="<?php echo $glyph->item->name; ?>">
										<?php echo $glyph->name; ?>
									</a>
								</li>

							<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</span>
				<!-- Secondary Spec -->

			<?php endif; ?>
		</div>
		<!-- Glyphs -->

	</section>
	<!-- Specs -->

	<!-- Professions -->
	<section id="professions">
		<h3>Professions</h3>
		
		<ul>
			<?php foreach($character->professions as $profession): ?>
				<li>
					<div class="ui-progress-bar ui-container" id="<?php echo strformat($profession->name); ?>_bar">
						<span class="ui-label">
							
							<?php if($profession->has_recipes()): ?>
								<a href="/roster/<?php echo strtolower($character->name); ?>/<?php echo strformat($profession->name, '-'); ?>">
							<?php endif; ?>
							
							<span class="icon">
								<img src="<?php echo $profession->getIcon(18); ?>" alt="<?php echo $profession->name; ?>">
							</span>
							
							<span class="name"><?php echo $profession->name; ?></span>
							
							<span class="level"><?php echo $profession->rank; ?></span>
						
							<?php if($profession->has_recipes()): ?>
								</a>
							<?php endif; ?>

						</span>
					
						<div class="ui-progress" style="width: <?php echo $profession->get_percentage(); ?>%;"></div>

					</div>
				</li>
			<?php endforeach; ?>
		</ul>
	</section>
	<!-- Professions -->


</div>