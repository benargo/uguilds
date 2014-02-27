<h1>My Account</h1>

<?php if($this->session->flashdata('message')): ?>

<section class="message">
	<?php echo $this->session->flashdata('message'); ?>
</section>

<?php endif; ?>

<section class="my-account" id="my-account">

	<h2>Email Address</h2>

	<p><?php echo $email; ?> [<a href="/account/email" title="Change your email address and manage your preferences">Edit <i class="fa fa-pencil-square-o"></i></a>]</p>

	<h2>Password</h2>
	<p><i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		<i class="fa fa-star"></i>
		[<a href="/account/password/change" title="Change your password to something different">Edit <i class="fa fa-pencil-square-o"></i></a>]</p>

	<h2>Officers Club</h2>
	<p>As an officer of <?php echo $guild_name; ?> you can manage the following additional sections:</p>
	<ul>
		<li><a href="/officers/ranks" title="Manage guild ranks and permissions">Ranks &amp; Permissions</a></li>
		<li><a href="/officers/accounts" title="Manage other user accounts">User Accounts</a></li>
	</ul>

</section>
<!-- #my-account -->

<section class="my-characters" id="my-characters">

	<h1>My Characters</h1>

	<p>Click on a character to set it as your active character. This character will represent you in all your activities on this site.</p>

	<?php foreach($account->get_all_characters() as $character): ?>

		<a id="<?php echo strtolower($character->name); ?>" 
			data-character-id="<?php echo $character->id; ?>" 
			href="/account/characters/switch/<?php echo strtolower($character->name); ?>" 
			class="set-primary<?php if($character->id === $active_character) echo ' primary'; ?>">
			<img src="<?php echo $character->getImageURL('thumbnail'); ?>" alt="Character Thumbnail" class="thumbnail" />
			<h2><?php echo $character->name; ?></h2>
			<p><?php echo $character->race->name; ?> <?php echo $character->level; ?> <?php echo $character->class->name; ?></p>
		</a>

	<?php endforeach; ?>

	<h2>Add a Character</h2>
	
	<form action="/account/characters/claim" method="post">
		<p><label for="new-character">Type your other character's name below:</label></p>
		<p><select name="new-character" id="new-character">
			<option value=""> </option>
			<?php foreach($members as $member): ?>
				<option value="<?php echo strtolower($member->name); ?>"><?php echo $member->name; ?></option>
			<?php endforeach; ?>
		</select></p>
		<p><input type="submit" value="Claim" /></p>
	</form>
</section>

