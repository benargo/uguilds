	<article>
		<h1>Guild Roster</h1>
		<!-- Filter -->
		<form action="/roster/filter" method="get" id="roster-filter">
			
			<!-- Character Name -->
			<span class="field">
				<label for="characterName">Character Name
				<select name="characterName">
			<?php	foreach($members as $member)
					{
						?><option name="<?php echo strtolower($member['character']['name']); ?>"><?php echo $member['character']['name']; ?></option><?php
					}
			?>
				</select>
			</span>

			<!-- Race -->
			<span class="field">
				<label for="race">Race</label>
				<select name="race">
			<?php	foreach($races->getAll($guild->getData()['side']) as $race)
					{
						?>		<option name="<?php echo strtolower($race['name']); ?>" data-image="/media/images/races/race_<?php echo $race['id']; ?>_0.jpg"><?php echo $race['name']; ?></option>
			<?php
					}
			?>
				</select>
			</span>

			<!-- Class -->
			<span class="field">
				<label for="class">Class</label>
				<select name="class">
			<?php 	foreach($classes->getAll() as $class)
					{
						?>		<option name="<?php echo strtolower($class['name']); ?>" data-image="/media/images/classes/class_<?php echo $class['id']; ?>.jpg"><?php echo $class['name']; ?></option>
			<?php
					}
			?>
				</select>
			</span>

			<!-- Level Range -->
			<span class="field">
				<label for="min-level">Level</label>
				<select name="min-level">
			<?php 	for($i = $guild->getLowestLevelMember(); $i <= $guild->getHighestLevelMember(); $i++)
					{
						?>		<option name="<?php echo $i; ?>"><?php echo $i; ?></option>
				<?php
					}
				?>
				</select>
				&ndash;
				<select name="max-level">
			<?php 	for($i = $guild->getLowestLevelMember(); $i <= $guild->getHighestLevelMember(); $i++)
					{
						?>		<option name="<?php echo $i; ?>"<?php echo ($i == $guild->getHighestLevelMember() ? ' selected="true"' : ''); ?>><?php echo $i; ?></option>
				<?php
					}
				?>
				</select>
			</span>

			<!-- Guild Rank : TODO
			<span class="field">
				<label for="rank">Guild Rank</label>
				<select name="rank">

				</select>
			</span>-->
		</form>
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
