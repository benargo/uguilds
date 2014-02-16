<h1>New Password Required</h1>
		
<p>Awesome stuff <?php echo $character_name; ?>! 
Your account is activated but in order to continue we need you to set a new password for your account.</p>
		
<?php echo form_open('account/password/change'); ?>

	<?php echo form_hidden('account_id', $account_id); ?>

	<p><label for="password">Please type a new password in the box below:</label>
	<?php echo form_password(array(
		'name' 		=> 'password',
		'required' 	=> true
	)); ?></p>

	<p><label for="password_confirm">We now need you to retype that password for verification purposes:</label>
	<?php echo form_password(array(
		'name'		=> 'password_confirm',
		'required'	=> true
	)); ?></p>

	<p><?php echo form_submit('change_password', 'Change Password'); ?></p>
	
</form>