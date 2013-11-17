	<article>
		<h1>Guild Roster</h1>
		<!-- Filter -->
		<form action="/roster/filter" method="get" id="roster-filter">
			
			<!-- Character Name -->
			<span class="field">
				<label for="characterName">Character Name</label>
				<select name="characterName">
					<option value="">Select One...</option><?php	

					foreach($members as $member)
					{
						echo "\n\t\t<option value=\"". strtolower($member['character']['name']) ."\">". $member['character']['name'] ."</option>";
					}
				?>
				</select>
			</span>

			<!-- Race -->
			<span class="field">
				<label for="race">Race</label>
				<select name="race">
					<option value="">Select One...</option><?php	

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
					<option value="">Select One...</option><?php 

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
				<select name="minLevel">
					<option value="">Select One...</option><?php 

					for($i = $guild->getLowestLevelMember(); $i <= $guild->getHighestLevelMember(); $i++)
					{
						echo "\n\t\t<option value=\"". $i ."\">". $i ."</option>";
					}
				?>
				</select>
				&ndash;
				<select name="maxLevel">
					<option value="">Select One...</option><?php 

					for($i = $guild->getLowestLevelMember(); $i <= $guild->getHighestLevelMember(); $i++)
					{
						echo "\n\t\t<option value=\"". $i ."\"". ($i == $guild->getHighestLevelMember() ? ' selected="true"' : '') .">". $i ."</option>";
					}
				?>
				</select>
			</span>

			<!-- Guild Rank -->
			<span class="field">
				<label for="rank">Guild Rank</label>
				<select name="rank">
					<option value="">Select One...</option><?php 

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
