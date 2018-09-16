<?php 
	function fn_MergeXml(&$base, $add)
	{
		if ( count($add) != 0 )
		{
			$exists = false;
			if ( isset($base->{$add->getName()}) )
			{
				foreach ($base->{$add->getName()} as $child )
				{
					if ( (string) $child->attributes()->numero == (string) $add->attributes()->numero )
					{
						$new = $base->{$add->getName()};
						$exists = true;
						break;
					}
				}
			}
			if ( ! $exists )
			{
				$new = $base->addChild($add->getName());
			}
		}
		else
		{
			if ( !isset($base->{$add->getName()}) )
			{
				$new = $base->addChild($add->getName(), htmlspecialchars($add));
				//TG pour les URL des camera
				//$new = $base->addChild($add->getName(), $add);
			}
		}
		foreach ($add->attributes() as $a => $b)
		{
			if ( ! isset($new->attributes()->{$a}) )
			{
				//$new->addAttribute($a, htmlspecialchars($b));
		  $new->addAttribute($a, $b);
			}
		}
		if ( count($add) != 0 )
		{
			foreach ($add->children() as $child)
			{
				fn_MergeXml($new, $child);
				//fn_MergeXml($base, $child);
			}
		}
	}
?>