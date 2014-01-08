<section>
	<h1>Guild Roster</h1>
	<!-- Filter -->
	<form action="/roster/filter" method="get" id="roster-filter">
		
		<!-- Character Name -->
		<span class="field">
			<label for="characterName">Character Name</label>
			<input type="text" name="characterName" placeholder="e.g. <?php echo $members[0]->name; ?>"/>
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


