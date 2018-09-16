<?php
/*-----------------------------------------------------------------------------*
* Titre : Donate.php                                                              *
*-----------------------------------------------------------------------------*/
?>
<div id="donate">
  <span class="Texte">
<?php
echo fn_GetTranslation('wy_gift');
?>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" class="paypal">
  <input type="hidden" name="cmd" value="_donations" />
  <input type="hidden" name="business" value="paypal@jublot.com" />
  <input type="hidden" name="item_name" value="Serveur Domotique Project" />
  <input type="hidden" name="item_number" value="Serveur Domotique IPX800 V<?php echo $GLOBALS["config"]->general->version; ?>" />
  <input type="hidden" name="no_shipping" value="0" />
  <input type="hidden" name="no_note" value="0" />
  <input type="hidden" name="tax" value="0" />
  <input type="hidden" name="lc" value="FR" />
  <input type="hidden" name="bn" value="PP-DonationsBF" />
  <input type="hidden" name="return" value="https://sourceforge.net/p/multicardipx800/" />
  <center>
  <label for="amount"><?php echo fn_GetTranslation('amount'); ?> :</label>
  <input type="text" name="amount" id="amount" size="3" value="" />
  <select name="currency_code" id="currency_code">
    <option value="EUR">Euro</option>
    <option value="GBP">Livre sterling</option>
    <option value="USD">Dollar américain</option>
    <option value="CAD">Dollar canadien</option>
    <option value="JPY">Yen japonais</option>
  </select>
  </center>
  <br/>
  <center>
  <input type="image" src="images/btn_donate.gif" border="0" name="submit" alt="PayPal - la solution de paiement en ligne la plus simple et la plus sécurisée !">
  </center>
  <br/>
  <br/>
</form>
</span>
</div>

<script type="text/javascript">
<?php
?>

jQuery(document).ready(function() 
{
	updateOrientation();
  UpdateMenu();
  UpdateTitle("'.fn_GetTranslation('do_gift').'");
});


</script>
