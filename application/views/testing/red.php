<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<title>Red Route</title>
	<meta name="description" content="UFCEWT-20-3: Advanced Topics in Web Development">
	<meta name="author" content="10008548">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

	<!-- CSS -->
	<!-- http://www.getskeleton.com/ -->
	<link rel="stylesheet" href="//www.cems.uwe.ac.uk/~b2-argo/css/base.css">
	<link rel="stylesheet" href="//www.cems.uwe.ac.uk/~b2-argo/css/skeleton.css">
	<link rel="stylesheet" href="//www.cems.uwe.ac.uk/~b2-argo/css/layout.css">
	<link rel="stylesheet" href="/media/css/uGuilds.css">
	<style type="text/css">
		.terms {
			height: 300px;
			overflow-y: scroll;
			margin-bottom: 20px;
		}
		input[type="email"] {
			width: 300px;
		}

		table { margin-bottom: 20px; }
		table td { border: solid 1px #ccc; padding: 5px; }

		.red-route { display: block; border: solid 5px #ff0200; border-radius: 3px; -webkit-border-radius: 3px; text-align: center; }
		.green-route { display: block; border: solid 5px #00b224; border-radius: 3px; -webkit-border-radius: 3px; text-align: center; }
		.purple-route { display: block; border: solid 5px #551A8B; border-radius: 3px; -webkit-border-radius: 3px; text-align: center; }

		#instructions2, #feedback2 { display: none; }
	</style>


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript">
	<!--
		$(function(){
			$('#account_exists_test').change(function() {
				$('#instructions2, #feedback2').toggle();
			});
		});
	-->
	</script>
	<script type="text/javascript">
	<!--// Google Analytics
	
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', 'UA-23790873-4']);
		_gaq.push(['_setDomainName', 'cems.uwe.ac.uk']);
		_gaq.push(['_trackPageview']);

	 	(function() {
	    	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	    	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	    	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  	})();
	-->
	</script>

</head>
<body>

	<div class="container">
		<header class="sixteen columns">
			<h1 class="remove-bottom uguilds" style="margin-top: 40px">uGuilds Beta Testing</h1>
			<h5>UFCEWS-30-3 Digital Media Project</h5>
			<hr />
		</header>

		<article>
			<h2>Red Route</h2>

			<p>&#8592; <a href="routes">Other Routes</a></p>
			
			<img src="//www.cems.uwe.ac.uk/~b2-argo/images/red_route.svg" alt="Red Route" style="width: 100%;" class="red-route">

			<?php echo form_open('testing/feedback'); ?>

				<?php echo form_hidden('route', 'red'); ?>

				<section id="instructions1" class="two-thirds column alpha">
					<h3>Instructions</h3>

					<p>Starting Page: <a href="http://mercenariesinc.beta.uguilds.net/roster/animorphus" target="_blank">http://mercenariesinc.beta.uguilds.net/roster/animorphus</a></p>

					<p>1. Click the 'Login/Register' button in the main navigation.</p>

					<p>2. Log in first of all leaving the Email Address and Password field blank.</p>

					<p>3. Log in a second time using:</p>

					<table>
						<tr>
							<td>Email Address:</td>
							<td><?php echo $test_email; ?></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><?php echo $password; ?></td>
						</tr>
					</table>

					<h4>Important Question!</h4>
					<p><label for="account_exists_test"><?php echo form_checkbox(array(
						'name' => 'account_exists_test',
						'id' => 'account_exists_test',
						'value' => 'Yes'
					)); ?> Did the Registration Form appear when you logged in using the provided details?</label></p>
				</section>

				<section id="feedback1" class="one-third column omega">
					<h3>Feedback</h3>

					<p><label for="loading_time">How long did the Starting Page take to load?</label>
					<?php echo form_dropdown('loading_time', array(
						'Very quickly',
						'Fairly quickly',
						'Average',
						'Fairly slow',
						'Very slow'
					)); ?>

					<p><label for="login_validation_test"><?php echo form_checkbox(array(
						'name' => 'login_validation_test',
						'id' => 'login_validation_test',
						'value' => 'Yes'
					)); ?> Did the Login Form throw an error when you tried to log in with a blank email address and password?</label></p>
				</section>

				<section id="instructions2" class="two-thirds column alpha row clearfix">
					<p>4. Fill in and submit the form as follows:</p>

					<table>
						<tr>
							<td>Character Name:</td>
							<td>Animorphus</td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><em>LEAVE BLANK</em></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><em>LEAVE BLANK</em></td>
						</tr>
						<tr>
							<td>Confirm Password:</td>
							<td><em>LEAVE BLANK</em></td>
						</tr>
					</table>

					<p>5. Fill in and submit the form a second time as follows:</p>

					<table>
						<tr>
							<td>Character Name:</td>
							<td>Animorphus</td>
						</tr>
						<tr>
							<td>Email Address:</td>
							<td><?php echo $test_email; ?></td>
						</tr>
						<tr>
							<td>Password:</td>
							<td><?php echo $password; ?></td>
						</tr>
						<tr>
							<td>Confirm Password:</td>
							<td><?php echo $password; ?></td>
						</tr>
					</table>

					<p>6. Try to submit the form, which should now include the header 'Verify Your Character' straight away.</p>

					<p><em>At this point, call Ben over who will need to do the next step for you. The next step requires logging in and out of my World of Warcraft account.</em></p>

					<p>7. While you're waiting, give the following URL a try: <a href="http://mercenariesinc.beta.uguilds.net/account/activate/132423/51e03b518158f1c6945e81bf14d8ea93">http://mercenariesinc.beta.uguilds.net/account/activate/132423/51e03b518158f1c6945e81bf14d8ea93</a></p>

					<p>8. Head over to <a href="https://wcc.secureserver.net/email">https://wcc.secureserver.net/email</a>. If it doesn't log you in automatically let Ben know.</p>

					<p>9. Copy and paste the activation link into a new tab and go!</p>

					<h4>Final Question</h4>
					<p><label for="final_question_test"><?php echo form_checkbox(array(
						'name' => 'final_question_test',
						'id' => 'final_question_test',
						'value' => 'Yes'
					)); ?> Did we arrive back at <a href="http://mercenariesinc.beta.uguilds.net/roster/animorphus">http://mercenariesinc.beta.uguilds.net/roster/animorphus</a>?</label></p>

				</section>

				<section id="feedback2" class="one-third column omega">
					<p><label for="registration_character_validation_test"><?php echo form_checkbox(array(
						'name' => 'registration_character_validation_test',
						'id' => 'registration_character_validation_test',
						'value' => 'Yes'
					)); ?> Did the Registration Form automatically repopulate 'Animorphus' as the character when the validation failed?</label></p>

					<p><label for="registration_email_validation_test"><?php echo form_checkbox(array(
						'name' => 'registration_email_validation_test',
						'id' => 'registration_email_validation_test',
						'value' => 'Yes'
					)); ?> Did the Registration Form throw an error when you removed the email address?</label></p>

					<p><label for="registration_password_validation_test"><?php echo form_checkbox(array(
						'name' => 'registration_password_validation_test',
						'id' => 'registration_password_validation_test',
						'value' => 'Yes'
					)); ?> Did the Registration Form throw an error when you removed the password?</label></p>

					<p><label for="registration_password_confirm_validation_test"><?php echo form_checkbox(array(
						'name' => 'registration_password_confirm_validation_test',
						'id' => 'registration_password_confirm_validation_test',
						'value' => 'Yes'
					)); ?> Did the Registration Form throw an error when you tried to submit without confirming your password?</label></p>

					<p><label for="registration_character_verification_test"><?php echo form_checkbox(array(
						'name' => 'registration_character_verification_test',
						'id' => 'registration_character_verification_test',
						'value' => 'Yes'
					)); ?> Did the Registration Form throw an error when you tried to submit without completing the Character verification task?</label></p>

					<p><label for="arbitrary_activation_link_test"><?php echo form_checkbox(array(
						'name' => 'arbitrary_activation_link_test',
						'id' => 'arbitrary_activation_link_test',
						'value' => 'Yes'
					)); ?> Did the arbitrary activation link provided in part 7 throw an error?</label></p>
				</section>

				<hr class="clearfix">
				<p><label for="other_comments">Any other comments, questions or thoughts?</label>
				<?php echo form_textarea(array(
					'name' => 'other_comments',
					'id' => 'other_comments',
					'rows' => '10',
					'style' => 'width: 100%'
				)); ?>
				<p style="text-align: center;"><label for="submit">All done? Fantastic!</label>
				<?php echo form_submit('submit', 'Send Feedback'); ?></p>

			</form>

			<p><a href="routes">&#8592; Other Routes</a></p>
			
		</article>

		<!-- Footer -->
		<div class="sixteen columns clearfix">
			<p>Copyright &copy; 2013-14 University of the West of England, Bristol, <a href="http://www.benargo.com/">Ben Argo</a> &amp; <span class="uguilds">uGuilds.com</span>.</p>
		</div>
		<div class="two-thirds column clearfix row">
			<a href="http://www.uwe.ac.uk/" target="_blank" rel="nofollow"><img src="//www.cems.uwe.ac.uk/~b2-argo/images/uwe_logo.gif" alt="UWE Logo"></a>
			<a href="http://www.uwesu.org/" target="_blank" rel="nofollow"><img src="//www.cems.uwe.ac.uk/~b2-argo/images/uwesu_logo.png" alt="UWESU Logo"></a>
		</div>
		<div class="one-third column text-align right" >
			<h1 class="uguilds"><a href="http://www.uguilds.com/">uGuilds.com</a></h1>
		</div> 
	</div>
</body>
</html>