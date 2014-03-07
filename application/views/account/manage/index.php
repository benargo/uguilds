<h1>My Account</h1>

<?php if($this->session->flashdata('message')): ?>

<section class="message">
	<?php echo $this->session->flashdata('message'); ?>
</section>

<?php endif; ?>

<section class="my-account" id="my-account">

	<a 	class="button manage"
		href="/account/password/change" 
		title="Change your password to something different">Change Password</a>


	<a 	class="button manage officer"
			href="/officers/ranks" 
			title="Manage guild ranks and permissions">Ranks &amp; Permissions</a>

</section>
<!-- #my-account -->

<section class="my-characters" id="my-characters">

	<h1>My Characters</h1>

	<p>Click on a character to set it as your active character. This character will represent you in all your activities on this site.</p>

	<?php foreach($account->get_all_characters() as $character): ?>

		<a 	id="<?php echo strtolower($character->name); ?>" 
			data-character-id="<?php echo $character->id; ?>" 
			href="/account/characters/switch/<?php echo strtolower($character->name); ?>" 
			class="character set-primary<?php if($character->id === $active_character) echo ' primary'; ?>">
			<img src="<?php echo $character->getImageURL('thumbnail'); ?>" alt="Character Thumbnail" class="thumbnail" />
			<h2><?php echo $character->name; ?></h2>
			<p><?php echo $character->race->name; ?> <?php echo $character->level; ?> <?php echo $character->class->name; ?></p>
		</a>

	<?php endforeach; ?>
	
	<a 	id="add-new" 
		href="/account/characters/add"
		class="character">
		<span class="fa fa-plus"></span>
		<h2>Add new</h2>
	</a>
</section>
<!-- #my-characters -->

<section class="my-guilds" id="my-guilds">

	

</section>
