<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<ul>
	<li><a href="/" rel="home">Home</a></li>
	<li><a href="/roster">Guild Roster</a></li>
	<li class="align right"><a href="/account/login">Login</a></li>
</ul>
<form id="navigation" action="/navigate" method="post">
	<select name="url">
		<option value="/">Home</option>
		<option value="/roster">Guild Roster</option>
		<option value="/account/login">Login/Register</option>
	</select>
	<input type="submit" value="Go" />
</form>