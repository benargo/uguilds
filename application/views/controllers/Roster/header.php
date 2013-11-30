	<article>
		<h1>Guild Roster</h1>
		<!-- Filter -->
		<form action="/roster/filter" method="get" id="roster-filter">
			
			<!-- Character Name -->
			<span class="field">
				<label for="characterName">Character Name</label>
				<input type="text" name="characterName" placeholder="e.g. <?php echo $members[0]['character']['name']; ?>" maxlength="2" />
			</span>

			<!-- Race -->
			<span class="field">
				<label for="race">Race</label>
				<select name="race">
					<option value="" selected disabled>Select One...</option><?php	

					foreach($races->getAll($guild->getData()['side']) as $race)
					{
						echo "\n\t\t<option value=\"". strtolower($race['name']) ."\" data-image=\"/media/images/races/race_". $race['id'] ."_0.jpg\">". $race['name'] ."</option>";
					}
				?>
				</select>
			</span>

			<!-- Class -->
			<span class="field">
				<label for="class">Class</label>
				<select name="class">
					<option value="" selected disabled>Select One...</option><?php 

					foreach($classes->getAll() as $class)
					{
						echo "\n\t\t<option value=\"". strtolower($class['name']) ."\" data-image=\"/media/images/classes/class_". $class['id'] .".jpg\">". $class['name'] ."</option>";	
					}
				?>
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
					<option value="" selected disabled>Select One...</option><?php 

					foreach($guild->ranks as $position => $title)
					{
						echo "\n\t\t<option value=\"". $position ."\">". $title ."</option>";
					} 
				?>
				</select>
			</span>
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