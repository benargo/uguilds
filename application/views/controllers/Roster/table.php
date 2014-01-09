<section>
	<h1>Guild Roster</h1>
	<!-- Filter -->
	<form action="/roster/filter" method="get" id="roster-filter">
		
		<!-- Character Name -->
		<span class="field">
			<label for="characterName">Character Name</label>
			<input type="text" name="characterName" placeholder="e.g. <?php echo $members[0]->name; ?>" autofocus="true" />
		</span>

		<!-- Race -->
		<span class="field">
			<label for="race">Race</label>
			<select name="race">
				<option value="all" selected>All</option>	
				<?php foreach($races->getAll($guild->getData()['side']) as $race): ?>
				<option value="<?php echo $race->id; ?>" data-image="<?php echo $races->getIcon($race->id); ?>"><?php echo $race->name; ?></option>
				<?php endforeach; ?>
			</select>
		</span>

		<!-- Class -->
		<span class="field">
			<label for="class">Class</label>
			<select name="class">
				<option value="all" selected>All</option>
				<?php foreach($classes->getAll() as $class): ?>
				<option value="<?php echo $class->id; ?>" data-image="<?php echo $classes->getIcon($class->id); ?>.jpg"><?php echo $class->name; ?></option>	
				<?php endforeach; ?>
			</select>
		</span>

		<!-- Level Range -->
		<span class="field">
			<label for="minLevel">Level</label>
			<input type="number" name="minLevel" min="<?php echo $guild->getLowestLevelMember(); ?>" max="<?php echo $guild->getHighestLevelMember(); ?>" placeholder="<?php echo $guild->getLowestLevelMember(); ?>" />
			&ndash;
			<input type="number" name="maxLevel" min="<?php echo $guild->getLowestLevelMember(); ?>" max="<?php echo $guild->getHighestLevelMember(); ?>" placeholder="<?php echo $guild->getHighestLevelMember(); ?>" />
		</span>

		<!-- Guild Rank -->
		<span class="field">
			<label for="rank">Guild Rank</label>
			<select name="rank">
				<option value="all" selected>Select One...</option><?php 

				foreach($ranks as $position => $title)
				{
					echo "\n\t\t<option value=\"". $position ."\">". $title ."</option>";
				} 
			?>
			</select>
		</span>

		<span>
			<input type="submit" value="Filter" class="nojs" />
			<input type="reset" value="Clear" />
		</span>
	</form>
</section>


<section>
	<!-- Roster table -->
	<table class="guild-roster tablesorter">
		<thead>
			<tr>
				<th class="sortable character-name">Name</th>
				<th class="sortable race">Race</th>
				<th class="sortable class">Class</th>
				<th class="sortable level">Level</th>
				<th class="sortable guild-rank">Guild Rank</th>
				<th class="sortable achievements">Achievement Points</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($members as $member): ?>
		<tr class="character <?php echo $member->name; ?>" <?php echo (isset($filtered) && !in_array($member, $filtered) ? 'style="display:none;"' : ''); ?>>
			<!-- Character Name -->
			<td class="character-name">
				<a href="/roster/<?php echo strtolower($member->name); ?>" 
					class="class <?php echo strtolower(preg_replace('/\ /', '-', $classes->getClass($member->class, 'name'))); ?>"
					><?php echo $member->name; ?></a>
			</td>

			<!-- Race -->
			<td class="race">
				<a href="<?php echo $uri; ?>/race=<?php echo strtolower(preg_replace('/\ /', '-', $races->getRace($member->race, 'name'))); ?>">
				<img src="<?php echo $races->getIcon($member->race, $member->gender); ?>"
					alt="<?php echo $races->getRace($member->race,'name'); ?>" width="18" /></a>
			</td>

			<!-- Class -->
			<td class="class">
				<a href="<?php echo $uri; ?>/class=<?php echo strtolower(preg_replace('/\ /', '-', $classes->getClass($member->class, 'name'))); ?>">
					<img src="<?php echo $classes->getIcon($member->class, 18); ?>"
						alt="<?php echo $classes->getClass($member->class, 'name'); ?>" width="18" />
					<?php if(property_exists($member, 'spec'))
						{
							?><img src="<?php echo $guild->getIcon($member->spec->icon, 18); ?>" 
						alt="<?php echo $member->spec->name; ?>" width="18" class="spec" /><?php
					} ?>
				</a>
			</td>

			<!-- Level -->
			<td class="level"><?php echo $member->level; ?></td>

			<!-- Guild Rank -->
			<td class="guild-rank" data-id="<?php echo $member->rank; ?>">
				<a href="/roster/rank=<?php echo (property_exists($member, 'rankname') ? strtolower(preg_replace('/\ /', '-', $member->rankname)) : $member->rank); ?>"><?php 
				echo (property_exists($member, 'rankname') ? $member->rankname : $member->rank); ?></a>
			</td>

			<!-- Achievement Points -->
			<td class="achievements"><?php echo $member->achievementPoints; ?> <img src="/media/images/achievements.gif" alt="Achievement Points" width="8"	 /></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</section>
