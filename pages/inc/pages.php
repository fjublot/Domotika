
			<div class="" role="tabpanel">
				<ul id="pages" class="nav nav-tabs bar_tabs" role="tablist">
					<?php
						$first = true;
						$html1="";
						$html2='<div id="pagesContent" class="tab-content">';
						
					if (isset($GLOBALS["config"]->pages)) {	
						foreach($GLOBALS["config"]->pages->page as $info)
						{
							$currentpage = new page($info->attributes()->numero, $info);
							
							if (fn_GetAuth($_SESSION["AuthId"], "page", $currentpage->numero))
							{
								$IdPage='page' . $currentpage->numero;
								if ($first == true) {
									$Active1=" active";
									$Active2=" active in";
								}
								else {
									$Active1="";
									$Active2="";
								}
								
								$html1 .='<li role="presentation" class="'.$Active1.'"><a href="#'.$IdPage.'" id="tab-'.$IdPage.'" role="tab" data-toggle="tab" aria-expanded="true">'.$currentpage->label.'</a></li>';
								$html2 .='<div id="' . $IdPage . '"  class="tab-pane fade '. $Active2 . '" aria-labelledby="'. $currentpage->label . '" role="tabpanel">';
								$i=0;
								foreach($currentpage->data as $item)
								{
									list($class, $numero) = explode("_", $item);
									$html2 .= '<!--' . $class . '(' . $numero . ')-->' . PHP_EOL; 
									if (fn_GetAuth($_SESSION["AuthId"], "$class", $numero))
									{
									$currentpage_data = new $class($numero);
									$html2 .= $currentpage_data->disp();
									}
									else
										$html2 .= '<!-- not autorized -->' ;
									$i++;
								}
								$html2 .='</div>';
								$first = false;
							}
							else
								$html1 .= '<!-- page (' .  $currentpage->numero . ') not autorized -->' . PHP_EOL; 
							
						}
					}
						echo $html1;
					$html2.='</div>';

					?>
				</ul>
					<?php echo  $html2;?>
			</div>


