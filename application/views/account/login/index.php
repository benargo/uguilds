<h1>Login &amp; Register</h1>

<p>Please login to your <span class="uguilds">uGuilds.com</span> account using your email address and password.</p>
<p>Not registered yet? No problem, enter your email address and a password below and we'll get you going.</p>

<section class="validation_errors">
	<?php echo validation_errors(); ?>

	<?php if(isset($authentication_error)): ?>
		<?php echo $authentication_error; ?>
	<?php endif; ?>
</section>

<?php echo form_open('account/login/authenticate'); ?>

	<p><label for="email">Email Address:</label>
	<?php echo form_input(array(
		'name' 			=> 'email',
		'placeholder' 	=> 'john.smith@example.com',
		'required' 		=> true,
		'type' 			=> 'email',
		'value' 		=> $email
	)); ?>

	<p><label for="password">Password:</label>
	<?php echo form_password(array(
		'name' 		=> 'password',
		'required' 	=> true,
		'value' 	=> $password
	)); ?></p>

	<p><?php echo form_submit('login_submit', 'Log in'); ?></p>

	<p><a href="/account/password/recover">Forgot your password?</a></p>

</form>