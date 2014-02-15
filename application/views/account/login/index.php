<h1>Login &amp; Register</h1>
<?php echo validation_errors(); ?>
<?php if($authentication_error) echo $authentication_error; ?>
<?php echo form_open('account/login/authenticate'); ?>

<p><label for="character">Character Name:</label>
<?php echo form_input(array('name' => 'character', 
							'maxlength' => 12, 
							'placeholder' => 'e.g. '. $this->guild->getMembers('rank')[0]->name)); ?></p>

<p><label for="password">Password:</label>
<?php echo form_password('password'); ?></p>

<p><?php echo form_submit('login_submit', 'Log in'); ?></p>

<?php echo form_close(); ?>