	<!-- Header -->
	<nav class="menu-bar ">
		<ul>
			<li><a href="/" rel="home">Home</a></li>
			<li><a href="/<?php echo $this->uguilds->getController('roster'); ?>">Guild Roster</a></li>
			<li class="right">
				<a href="/account/login">Login</a>
			</li>
		</ul>
	</nav>
	<header>
		<a href="/" rel="home">
			<span><img src="<?php echo $this->uguilds->guild->getEmblem(TRUE,150); ?>" alt="<?php echo $this->uguilds->guild->guildName; ?> guild emblem" width="150" /></span>
			<span class="guild-name"><h1><?php echo $this->uguilds->guild->name; ?></h1>
				<h2><?php echo $this->uguilds->guild->realm; ?> <?php 
						  echo $this->uguilds->guild->region; ?></h2></span>
		</a>
	</header>
	<!-- End Header -->