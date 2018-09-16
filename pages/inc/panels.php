<?php require_once($GLOBALS["root_path"] . 'class/page.php'); 
if ( isset($_SESSION['Privilege']) && $_SESSION['Privilege'] >= (int)$GLOBALS["config"]->access->param )
{
	?>
	<!-- Debut Panel param1 -->
<div id="menu">
<nav>
		<ul>
			<li>
				<a href="#param2_panel" >
        <h3><?php echo fn_GetTranslation('configuration_general');?></h3>
        </a>
			</li>
			<?php
			foreach (array('user', 'carte', 'camera', 'scenario', 'cron', 'connexionauto', 'pushto', 'unite', 'graphique', 'page') as $class)
			{
				echo '<!-- '.fn_GetTranslation($class).'s -->' . PHP_EOL;
				?>
				<li >
					<a  href="List.php?add=true&amp;liens=1&amp;class=<?php echo $class; ?>">
						<img alt="<?php echo $class; ?>" src="images/'.$class;?>.png" />
						<h3><?php echo fn_GetTranslation($class);?>s</h3>
						<span><?php
							if ( isset($GLOBALS["config"]->{$class."s"}) )
								echo $GLOBALS["config"]->{$class."s"}->{$class}->Count();
							?></span>
					</a>
				</li>
				<?php
			}
			foreach (array('variable', 'vartxt', /*'btn', 'an', 'relai', 'cnt'*/) as $class)
			{
				?>
				<!-- <?php echo fn_GetTranslation($class);?>s -->
			<li>
					<a href="List.php?add=true&amp;liens=1&amp;class=<?php echo $class; ?>">
						<img alt="<?php echo $class; ?>" src="images/'.$class; ?>.png" />
						<h3><?php echo fn_GetTranslation($class);?>s</h3>
					</a>
				</li>
				<?php
			}
			?>
			<li>
				<a href="AddImage.php">
				  <img alt="image.png" src="images/image.png" />
				  <h3><?php echo fn_GetTranslation('manage_image'); ?></h3>
				  <span>&nbsp;</span>
				</a>
			</li>
		</ul>
	<!-- Fin Panel param1 -->
</nav>
</div>
<?php
}
?>
<!-- Popup Alert -->
<div id="popupAlert" data-role="popup" class="ui-content popupAlert" data-theme="e"></div>
<!-- Fin PopupAlert -->