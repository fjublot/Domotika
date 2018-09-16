<?php
	$privilege="";
	if (isset($_SESSION['Privilege'])) {
		$xpath = "//modelprivileges/modelprivilege[@numero='".$_SESSION['Privilege']."']";
		$privilege = fn_GetByXpath($xpath, 'bal', 'label');
	}
	$TypeConnexion = isset($_SESSION["TypeConnexion"])?$_SESSION["TypeConnexion"]:"";
	$ClientIP = isset($_SESSION["ClientIP"])?$_SESSION["ClientIP"]:"";
	$Timezone = isset($_SESSION["Timezone"])?$_SESSION["Timezone"]:"";
	$LoginConn = isset($_SESSION["LoginConn"])?$_SESSION["LoginConn"]:"";
	$AuthId = isset($_SESSION["AuthId"])?$_SESSION["AuthId"]:"";
	$ApiKey = isset($_SESSION["ApiKey"])?$_SESSION["ApiKey"]:"";
?>

	<!-- top navigation -->
	<header class="nav_menu">
		<div id="menuToggle" >
			<input type="checkbox" />
			<span class="burger"></span>
			<span class="burger"></span>
			<span class="burger"></span>
			<!-- Menu de gauche -->
			<div id="menu">
				<?php  
					$menu = new menu;
					echo $menu->menu();
				?>
			</div> 
			<!-- /Menu de gauche -->
		</div>
		<div class="navbar-header">
			<div class="navbar-title"><a class="TopbarTitle" href="?app=Mn"><?php echo $GLOBALS["config"]->general->nameappli; ?></a></div>
			<div class="navbar-time"><span class="time"></span></div>  
		</div>
		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav navbar-right ">
				<li role="presentation" class="dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="user-profile">
							<img src="images/profil.png" alt="" />
							<span><?php echo $LoginConn; ?>&nbsp;
								<span class=" fa fa-angle-down"></span>
								<span class="privilege"><?php echo $privilege; ?></span>
							</span>
						</span>
					</a>
					<ul id="menu2" class="dropdown-menu list-unstyled msg_list user_profil animated fadeInDown" role="menu">
						<li>
							<a>
								<span class="user-profile">
									<img src="images/profil.png" alt="Profile Image" />
								</span>
								<span>
									<span><?php echo $LoginConn; ?></span>
									<span class="badge bg-red pull-right"><?php echo $AuthId; ?></span>
								</span>
								<span class="message"><?php echo $privilege; ?></span>
								<span class="message"><?php echo $Timezone; ?></span>
								<span class="message"><?php echo $ClientIP; ?></span>
								<span class="message"><?php echo $TypeConnexion; ?></span>
							</a>
						</li>
						<li>
								<button class="btn btn-primary col-sm-12" onClick="javascript:session_info();">
									<span aria-hidden="true" class="glyphicon glyphicon-off"></span>&nbsp;<?php echo ucfirst(fn_GetTranslation('session'));?>
								</button>
						</li>
					</ul>
					
				</li>
				<li role="presentation" class="dropdown separator-left navbar-50">
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						<span class="info-number">
						<i class="fa fa-envelope-o"></i>
						<span class="badge bg-green">6</span>
						</span>
					</a>
					<ul id="menu1" class="dropdown-menu list-unstyled msg_list user_profil animated fadeInDown" role="menu">
						<!-- Ajax pour charger la liste des alertes -->
					</ul>
				</li>
				<li role="presentation" class="separator-left navbar-50">
					<a href="javascript:logout();" aria-expanded="false">
						<i class="fa fa-power-off"></i>
					</a>
				</li>
			</ul>
		</div>
		
	</header><!-- /top navigation -->
    <?php echo fn_SessionInfo();?>
	<!-- page content -->
    <div class="main" role="main">
	<div id="errorPanel" class="col-sm-12"></div>