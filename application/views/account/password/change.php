<h1>Change your password</h1>

<p class="validation_errors"><?php echo validation_errors(); ?></p>
<p><?php echo $authentication_error; ?></p>
		
<p>Want to change your password? Not a problem! Just fill out the form below and we'll have you sorted out in no time.</p>
		
<form action="/account/password/change" method="post">
		
	<input type="hidden" name="account_id" value="<?php echo $account->id; ?>">

	<p><label for="current">Type your current password:</label></p>
	<p><input type="password" name="current" id="current" required="true"></p>
		
	<p><label for="new_1">Type a new password:</label></p>
	<p><input type="password" name="new_1" id="new_1" required="true"></p>
			
	<p><label for="new_2">And just type it again for verification:</label></p>
	<p><input type="password" name="new_2" id="new_2" required="true"></p>
			
	<p><input type="submit" value="Change Password"></p>
		
</form>