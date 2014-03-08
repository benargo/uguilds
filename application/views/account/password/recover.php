<h1>Password Recovery</h1>

<p class="validation_errors"><?php echo validation_errors(); ?></p>
	
<p>All passwords are irreversibly encrypted, so there's no way of getting them back. 
	We can, however, help you reset your password. 
	This time, please try to remember it though.</p>
	
<form action="/account/password/recover" method="post">

	<p><label for="email">Type your email address:</label>
	<p><input type="email" id="email" name="email" required="true" placeholder="e.g. john.smith@example.com" /></p>

	<p><label for="new_1">Type a new password:</label></p>
	<p><input type="password" name="new_1" id="new_1" required="true"></p>
			
	<p><label for="new_2">And type it again for verification:</label></p>
	<p><input type="password" name="new_2" id="new_2" required="true"></p>
			
	<p><input type="submit" value="Change Password"></p>

</form>