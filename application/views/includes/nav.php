<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul>
	<li><a href="/" rel="home">Home</a></li>
	<li><a href="/roster">Guild Roster</a></li>
	<span class="align right">
	<?php if(isset($account)): ?>
		<li><a href="/account">My Account</a></li>
		<li><a href="/account/logout">Log out</a></li>
	<?php else: ?>
		<li><a href="/account/login">Login/Register</a></li>
	<?php endif; ?>
</ul>
<form id="navigation" action="/navigate" method="post">
	<select name="url">
		<option value="/">Home</option>
		<option value="/roster">Guild Roster</option>
		<?php if(isset($account)): ?>
			<option value="/account/">My Account</option>
			<option value="/account/characters/">My Characters</option>
			<option value="/account/logout">Log out</option>
		<?php else: ?>
			<option value="/account/login">Login/Register</option>
		<?php endif; ?>
	</select>
	<input type="submit" value="Go" />
</form>