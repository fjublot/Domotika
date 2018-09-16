<?php
	class menu {
		public function __construct() {
			/* Advance Configuration - no need to edit this section */
			define('SIDE_BAR_ID',        'sidebar-menu');
		}
		
		public function jsOnClick() {
?>
			$('[data-toggle="tab"]').tooltip({
				trigger: 'hover',
				placement: 'top',
				animate: true,
				delay: 500,
				container: 'body'
			});

<?php	
		}
		
		public function jsDisplay($page) {
			switch ($page) {
				case "Add":
				case "List";
					echo 'jQuery("#linkmenuconfig").trigger( "click" );';
					break;
				case "Donate":
				case "SaveConfig":
				case "RestoreConfig":
				case "RecupConfig":
				case "Trace":
				case "Help":
				case "AddImage":
				case "TraceArbo":
				case "TraceLog";
					echo 'jQuery("#linkmenuutilitaires").trigger( "click" );';
					break;				
				case "Main":
				case "ViewCam":
				case "ViewGraphique";
					echo 'jQuery("#linkmenumain").trigger( "click" );';
					break;
				default;
					echo 'jQuery("#linkmenumain").trigger( "click" );';
			}
		}	
		public function menu() {
?>
			<!-- sidebar menuconfig -->
			<div id="<?php echo SIDE_BAR_ID?>" class="main_menu_side hidden-print main_menu">
				<!-- sidebar-header -->
				<ul class="sidebar-header nav nav-tab" >
					<li class="vn active"><a id="linkmenumain" href="#menumain" data-toggle="tab" data-original-title="<?php echo ucfirst(fn_GetTranslation('main'));?>">
						<span aria-hidden="true" class="glyphicon glyphicon-eye-close"></span>
					</a></li>
					<li><a id="linkmenuconfig" href="#menuconfig" data-toggle="tab" data-original-title="<?php echo ucfirst(fn_GetTranslation('settings'));?>">
						<span aria-hidden="true" class="glyphicon glyphicon-cog"></span>
					</a>
					</li>
					<li><a id="linkmenuutilitaires" href="#menuutilitaires"data-toggle="tab" data-original-title="<?php echo ucfirst(fn_GetTranslation('tools'));?>">
						<i class="fa <?php echo fn_GetTranslation("fa-tool");?>"></i>
					</a>
					</li>
				</ul>
				<!-- /sidebar-header -->
				<div class="tab-content">
					<div id="menumain" class="tab-pane active">
						<!--<h3>General</h3>-->
						<ul class="nav side-menu">
							<!-- Dashboard -->
							<li>
								<a  href="?app=Mn">
									<i class="fa <?php echo fn_GetTranslation("fa-dashboard");?>"></i>
									<?php echo ucfirst(fn_GetTranslation("dashboard"));?>
								</a>
							</li>
							<!-- Cameras -->
							<?php $class='camera';  ?>
							<li>
								<a href="#"><i class="fa <?php echo fn_GetTranslation("fa-".$class);?>"></i> <?php echo fn_GetTranslation($class);?>s <span class="fa fa-chevron-down"></span></a>                                
								<ul class="nav child_menu" style="display: none">
									<?php
									if (isset($GLOBALS["config"]->{$class. "s"}->{$class})) {
										foreach($GLOBALS["config"]->{$class. "s"}->{$class} as $info) {
											$current = new $class($info->attributes()->numero, $info);
											$href = 'href="?app=Mn&amp;page=ViewCam&amp;numero=' . $current->numero . '"';
											echo '<li><a '.$href. '><span class="Pastille">' . $current->numero . '</span>' . $current->label . '</a></li>';
										}
									}
									?>          
								</ul>
							</li>
							<!-- /Cameras -->
							<!-- Graphiques -->
							<?php $class='graphique';  ?>
							<li>
								<a href="#"><i class="fa <?php echo fn_GetTranslation("fa-".$class);?>"></i><?php echo fn_GetTranslation($class);?>s <span class="fa fa-chevron-down"></span></a>
								<ul class="nav child_menu" style="display: none">
									<?php
									if (isset($GLOBALS["config"]->{$class. "s"}->{$class})) {
										foreach($GLOBALS["config"]->{$class. "s"}->{$class} as $info) { 
										$current = new $class($info->attributes()->numero, $info);
										$href = 'href="?app=Mn&amp;page=ViewGraphique&amp;numero=' . $current->numero . '"';
										echo '<li><a '.$href. '><span class="Pastille">' . $current->numero . '</span>' . $current->label . '</a></li>';
										}
									}
									?>          
								</ul>
							</li>
							<!-- /Graphiques -->
							<!-- Historiques -->
							<li>
								<a href="?app=Mn&amp;page=Histo">
									<i class="fa <?php echo fn_GetTranslation("fa-help"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('history'));?>
								</a>
							</li>
						</ul>
					</div>
					<div id="menuconfig" class="tab-pane">
						<!--<h3>General</h3>-->
						<ul class="nav side-menu">
							<!-- Config -->
							<?php
								foreach (array('user', 'carte', 'relai', 'btn', 'cnt', 'an', 'razdevice', 'espdevice', 'camera', 'scenario', 'cron', 'connexionauto', 'pushto', 'unite', 'graphique', 'variable', 'vartxt', 'page' ) as $classactive) {		
									echo '<!-- '.fn_GetTranslation($classactive).'s -->' . PHP_EOL;
								?>				
									<li>					
										<a  href="?app=Mn&amp;page=List&amp;class=<?php echo $classactive;?>&amp;<?php if ($_SESSION['Privilege']>=90) echo "addnew=true"; ?>">
											<i class="fa <?php echo fn_GetTranslation("fa-".$classactive);?>"></i>
											<?php echo ucfirst(fn_GetTranslation($classactive));?>s <!--<span class="fa fa-chevron-down">-->
											<?php 
												if (isset($GLOBALS["config"]->{$classactive. "s"}->{$classactive}))
													echo '<span class="badge pull-right">'.$GLOBALS["config"]->{$classactive."s"}->{$classactive}->Count().'</span>';
												else
												echo '<span class="badge pull-right">0</span>';
											?>
										</a>
									</li>
								<?php
								echo '<!-- /'.fn_GetTranslation($classactive).'s -->' . PHP_EOL;
								}
								?>
							<!-- /config -->
						</ul>
					</div>
					<div id="menuutilitaires" class="tab-pane">
						<!--<h3>General</h3>-->
						<ul class="nav side-menu">
							<!-- Paramétrage général -->
									<li>
										<a href="?app=Mn&amp;page=Setup">
											<i class="fa <?php echo fn_GetTranslation("fa-config"); ?>"></i>
											<?php echo ucfirst(fn_GetTranslation('configuration_general'));?>
										</a>
									</li>
							<!-- Aide -->
							<li>                                
								<a href="?app=Mn&amp;page=Help">
									<i class="fa <?php echo fn_GetTranslation("fa-help"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('in_line_help'));?>
								</a>
							</li>
							<!-- Trace -->
							<li> 
								<a href="?app=Mn&amp;page=Trace">
									<i class="fa <?php echo fn_GetTranslation("fa-search"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('log'));?>
								</a>
							</li>
							<!-- AddImage -->         
							<li> 
								<a href="?app=Mn&amp;page=AddImage">
									<i class="fa <?php echo fn_GetTranslation("fa-mypicture"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('mypictures'));?>
								</a>
							</li>
							<!-- Don -->         
							<li> 
								<a href="?app=Mn&amp;page=Donate">
									<i class="fa <?php echo fn_GetTranslation("fa-paypal"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('do_gift'));?>
								</a>
							</li>
							<!-- Aide d'information au support -->         
							<li> 
								<a href="?app=Ws&amp;page=getinfo">
									<i class="fa <?php echo fn_GetTranslation("fa-info"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('info_support'));?>
								</a>
							</li>
							<!-- Sauvegarder la configuration -->         
							<li> 
								<a href="?app=Ws&amp;page=saveconfig">
									<i class="fa <?php echo fn_GetTranslation("fa-save"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('save_config'));?>
								</a>
							</li>
							<!-- Restaurer la configuration -->         
							<li> 
								<a href="?app=Mn&amp;page=RestoreConfig">
									<i class="fa <?php echo fn_GetTranslation("fa-restore"); ?>"></i>
									<?php echo ucfirst(fn_GetTranslation('restore_config'));?>
								</a>
							</li>
							<!-- /Utilitaires -->							
						</ul>
					</div>
				</div><!-- /tab-content -->
			</div><!-- /sidebar-menuconfig -->
<?php
		}
	}
?>