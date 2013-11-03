<?php foreach($members as $member)
{
	?><tr>
		<!-- Character Name -->
		<td class="character-name">
			<a href="/roster/character/<?php echo strtolower($member['character']['name']); ?>" 
				class="<?php echo strtolower(preg_replace('/\ /', '-', $classes->getClass($member['character']['class'], 'name'))); ?>"
				><?php echo $member['character']['name']; ?></a>
		</td>

		<!-- Race -->
		<td class="race">
			<a href="/roster/filter?race=<?php echo strtolower(preg_replace('/\ /', '-', $races->getRace($member['character']['race'], 'name'))); ?>">
			<img src="<?php echo $races->getIcon($member['character']['race'], $member['character']['gender']); ?>"
				alt="<?php echo $races->getRace($member['character']['race'],'name'); ?>" width="18" /></a>
		</td>

		<!-- Class -->
		<td class="class">
			<a href="/roster/filter?class=<?php echo strtolower(preg_replace('/\ /', '-', $classes->getClass($member['character']['class'], 'name'))); ?>">
				<img src="<?php echo $classes->getIcon($member['character']['class']); ?>"
					alt="<?php echo $classes->getClass($member['character']['class'], 'name'); ?>" width="18" />
				<?php if(array_key_exists('spec', $member['character']))
					{
						?><img src="//media.blizzard.com/wow/icons/18/<?php echo $member['character']['spec']['icon']; ?>.jpg" 
					alt="<?php echo $member['character']['spec']['name']; ?>" width="18" class="spec" /><?php
				} ?>
			</a>
		</td>

		<!-- Level -->
		<td class="level"><?php echo $member['character']['level']; ?></td>

		<!-- Guild Rank -->
		<td class="guild-rank">
			<a href="/roster/filter?rank=<?php echo $member['rank']; ?>"><?php echo $member['rank']; ?></a>
		</td>

		<!-- Achievement Points -->
		<td class="achievements"><?php echo $member['character']['achievementPoints']; ?>
			<img src="/media/images/achievements.gif" alt="Achievement Points" width="8" height="10" /></td>
	</tr><?php 
} 
?>
</table>
</article>