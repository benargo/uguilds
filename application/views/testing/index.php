<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Meta -->
	<meta charset="utf-8">
	<title>uGuilds Beta Testing</title>
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

		section.error p { border: solid 5px #ff0200; border-radius: 3px; -webkit-border-radius: 3px; padding: 5px; }
	</style>


	<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

	<!-- JavaScript -->
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
			<h2>Background Information</h2>
			
			<p>uGuilds is a World of Warcraft Guild hosting web application, utilising data from the World of Warcraft Community Platform API. The aim is to create a data-driven application that opens up the World of Warcraft community to an innovative and more immersive way of experiencing and managing guilds.</p>

			<p>The purpose of today’s session is to adequately test the authentication system used by uGuilds.</p>

			<h2>What Authentication System?</h2>

			<p>Blizzard does not provide an oAuth-style authentication system for community-developed applications for security reasons. Therefore custom solutions must be developed.</p>

			<p>One of the key unique selling points (USPs) of uGuilds aims to deliver over the existing solutions is that you are represented throughout the Service as the same character which represents you in-game.</p>

			<p>However, this somewhat complicates the authentication procedure. I have attached a flow diagram that sums up the whole authentication procedure. We will be simulating this procedure during this session.</p>

			<p><img src="//www.cems.uwe.ac.uk/~b2-argo/images/authentication_flow_diagram.svg" style="width: 100%;" alt="Authentication Flow Diagram"></p>

			<p>Due to the complexity of this procedure, I am expecting significant issues to arise. However, that’s why you’re here. I have provided a basic plan for you to follow. However, you are free to deviate from the plan and explore in a way that feels more natural to you. In fact, the more this happens, the better!</p>

			<h2>Ready to begin?</h2>
			

			<?php echo form_open('testing/begin'); ?>
				<p>Fantastic! I just need a little bit more information first though.</p>

				<section class="terms">
					<h5>Terms &amp; Conditions</h5>
					<p>Thank you for taking part in uGuilds’ (‘the Service’) user testing session (‘the Session’) today. This test will follow a supervised pre-planned methodology with the option for open questions and comments after the session.</p>
					<p>By taking part in this user test you agree to the following terms and conditions:</p>
					<ol>
						<li>You agree that the data gathered during the Session will be retained and used for the improvement of the Service as provided under applicable law.</li>
						<li>You consent to audio recordings taking place during the course of the Session.</li>
						<li>You acknowledge that, where possible, data gathered during the Session will be anonymised. Unfortunately, it may not be possible to anonymise all information gathered.</li>
						<li>You have the right to withdraw from the Session at any time no questions asked. However, any anonymous data gathered prior to withdrawal will be retained.</p>
						<li>You acknowledge that all data gathered is done so in accordance Data Protection Act 1998.</li>
						<li>You acknowledge that the registered data controller for the Session is University of the West of England, Frenchay Campus, Coldharbour Lane, Bristol, BS16 1QY.</li>
						<li>You acknowledge that whilst every effort is made to ensure that the content of the Service is accurate, the Service is provided "as is" and makes no representations or warranties in relation to the accuracy or completeness of the information found on it.</li>
						<li>We do not warrant that the Service will be error, virus or bug free and you accept that it is Your responsibility to make adequate provision for protection against such threats.
Please sign below indicating that you agree to take part in the Session.</li>
					</ol>
				</section>

				<?php if(validation_errors()): ?>
				<section class="error">
				<?php echo validation_errors(); ?>
				</section>
				<?php endif; ?>

				<p><label for="email">Email Address:</label>
				<?php echo form_input(array(
					'id'			=> 'email',
					'name' 			=> 'email',
					'placeholder' 	=> 'john2.smith@live.uwe.ac.uk',
					'required'		=> true,
					'type' 			=> 'email',
				)); ?>


				<p><label for="terms"><?php echo form_checkbox(array(
					'name' => 'terms',
					'id' => 'terms',
					'value' => 'accept'
				)); ?> I have read and accept the Terms &amp; Conditions and consent to taking part in this Session.</label></p>

				<p style="text-align: center"><input type="submit" value="Begin!"></p>
			</form>
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