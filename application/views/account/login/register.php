<h1>Login &amp; Register</h1>
<p>It appears you don't have an account yet. No worries, we can fix that in a jiffy!</p>

<section class="validation_errors">
	<?php echo validation_errors(); ?>

	<?php if(isset($authentication_error)): ?>
		<?php echo $authentication_error; ?>
	<?php endif; ?>
</section>

<?php echo form_open('account/register/verify'); ?>

	<p><label for="character">Character Name:</label>
	<select name="character">
	<?php foreach($members as $member): ?>
		<option value="<?php echo $member; ?>" <?php if($member === $character_name) echo 'selected'; ?>><?php echo $member; ?></option>
	<?php endforeach; ?>
	</select>

	<p><label for="email">Email Address:</label>
	<?php echo form_input(array('name' => 'email',
								'type' => 'email',
								'maxlength' => 255,
								'value' => $email)); ?></p>

	<p><label for="password">Password:</label>
	<?php echo form_password('password', $password); ?></p>

	<p><label for="password_confirm">Confirm Password:</label>
	<?php echo form_password('password_confirm', $password_confirm); ?></p>

	<?php if($remainder): ?>

		<h2>Verify Your Character</h2>

		<p>We need to verify that you really are <?php echo $character_name; ?>. 
		To do this you will need to remove two pieces of armour from your character. 
		We will then check if those two pieces have been removed.</p>

		<p>To do this you will need to be able to log in and out of World of Warcraft.</p>

		<p>When you're ready, please log into World of Warcraft, and remove the following items:</p>

		<?php $count = 1; ?>
		<?php foreach($items as $slot => $item): ?>

			<?php echo form_hidden('slot'. $count, $slot); ?>
			<?php $count++; ?>

			<p><strong><?php echo ucfirst($slot); ?>:</strong>
				<img src="<?php echo $item['icon']; ?>" alt="<?php echo $slot; ?>">
				<a class="wh" rel="item=<?php echo $item['id']; ?>"><?php echo $item['name']; ?></a></p>
		<?php endforeach; ?>

	<?php else: ?>

		<?php echo form_hidden('password', $password); ?>

	<?php endif; ?>

	<p><?php echo form_submit('verify', 'Continue'); ?></p>

<?php echo form_close(); ?>


