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

			<hr class="row clearfix">

			<?php echo form_open('testing/feedback'); ?>

				<?php echo form_hidden('route', 'red'); ?>

				
				<p><label for="comments">Comments, questions or thoughts?</label>
				<?php echo form_textarea(array(
					'name' => 'comments',
					'id' => 'comments',
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