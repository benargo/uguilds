<section class="sixteen columns row">
	<!-- Roster table -->
	<table class="guild-roster tablesorter">
		<thead>
			<tr>
				<th class="sortable character-name four columns alpha">Name</th>
				<th class="sortable race two columns">Race</th>
				<th class="sortable class two columns">Class</th>
				<th class="sortable level two columns">Level</th>
				<th class="sortable guild-rank three columns">Guild Rank</th>
				<th class="sortable achievements three columns omega">Achievement Points</th>
			</tr>
		</thead>
		<tbody>
		<?php foreach($members as $member): ?>
		<tr class="character <?php echo $member->name; ?>" <?php echo (isset($filtered) && !in_array($member, $filtered) ? 'style="display:none;"' : ''); ?>>
			<!-- Character Name -->
			<td class="character-name four columns alpha">
				<a href="/roster/<?php echo strtolower($member->name); ?>" 
					class="<?php echo strtolower(preg_replace('/\ /', '-', $classes->getClass($member->class, 'name'))); ?>"
					><?php echo $member->name; ?></a>
			</td>

			<!-- Race -->
			<td class="race two columns">
				<a href="<?php echo $uri; ?>/race=<?php echo strtolower(preg_replace('/\ /', '-', $races->getRace($member->race, 'name'))); ?>">
				<img src="<?php echo $races->getIcon($member->race, $member->gender); ?>"
					alt="<?php echo $races->getRace($member->race,'name'); ?>" width="18" /></a>
			</td>

			<!-- Class -->
			<td class="class two columns">
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
			<td class="level two columns"><?php echo $member->level; ?></td>

			<!-- Guild Rank -->
			<td class="guild-rank three columns" data-id="<?php echo $member->rank; ?>">
				<a href="/roster/rank=<?php echo (property_exists($member, 'rankname') ? strtolower(preg_replace('/\ /', '-', $member->rankname)) : $member->rank); ?>"><?php 
				echo (property_exists($member, 'rankname') ? $member->rankname : $member->rank); ?></a>
			</td>

			<!-- Achievement Points -->
			<td class="achievements three columns omega"><?php echo $member->achievementPoints; ?> <img src="/media/images/achievements.gif" alt="Achievement Points" width="8"	 /></td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
</section>
