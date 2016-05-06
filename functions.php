<?php 
/*
Plugin Name: paypalESGI-plugin
Description: Plugin wordpress crée par les étudiants ESGI: Mathias-Rui Lopes, Alexandre Lau, Thomas Gauret, Benjamin Montbailly
Plugin URI: http://www.esgi.fr/ecole-informatique.html
Tags: PayPal payment, PayPal, button, payment, online payments, pay now, buy now, ecommerce, gateway, paypal button, paypal buy now button, paypal plugin
Author: Mathias-Rui Lopes
Author URI: http://mrlopes.fr
License: GPL2
Version: 0.1
*/
error_reporting(E_ALL);
ini_set('display_errors', 1);

global $pagenow, $typenow;
function wp_plugin_access_token($id_client, $id_secret) {
	/*$ch = curl_init();
	curl_setopt($ch, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_3);
	curl_setopt($ch, CURLOPT_URL, "https://api.sandbox.paypal.com/v1/oauth2/token");
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Accept-Language: en_US'));
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_USERPWD, $id_client.":".$id_secret);
	curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
	$result = curl_exec($ch);
	echo curl_error($ch);
	if(empty($result))die("Error: No response.");
	else
	{
	    $json = json_decode($result);
	    print_r($json->access_token);
	}
	curl_close($ch);*/

	//Le return présent ci-dessous est un debug. Il permet d'avancer sur le projet sans être bloqué a cause d'une version obselète d'OVH
	return file_get_contents("https://paypalesgi-esgipaypal.c9users.io/?idClient=".$id_client."&idSecret=".$id_secret);
}
/*
* PLUGIN FUNCTION
*
*/

//Appelle la fonction "pes_constructor" dès l'activation du plugin
register_activation_hook( __FILE__, "pes_constructor" );

function pes_constructor() {

$pes_settingsoptions = array(
'currency'    => '6',
'language'    => '3',
'clientID'    => '',
'SecretID'    => '',
'mode'    => '2',
'size'    => '2',
'opens'    => '2',
'cancel'    => '',
'return'    => '',
'note'    => '1',
'tax_rate'    => '',
'tax'    => '',
'weight_unit'    => '1',
'cbt'    => '',
'upload_image'    => '',
'showprice'    => '2',
'showname'    => '2',
'paymentaction' => '1'
);

add_option("pes_settingsoptions", $pes_settingsoptions);

}
	/* DEBUG PARAMETER*/
	/*$test = array(
	'currency'    => '2',
	'language'    => '3',
	'clientID'    => '',
	'SecretID'    => '',
	'mode'    => '2',
	'size'    => '2',
	'opens'    => '2',
	'cancel'    => '',
	'return'    => '',
	'note'    => '1',
	'tax_rate'    => '',
	'tax'    => '',
	'weight_unit'    => '1',
	'cbt'    => '',
	'upload_image'    => '',
	'showprice'    => '2',
	'showname'    => '2',
	'paymentaction' => '1'
	);
	update_option("pes_settingsoptions", $test);*/
/*
*
* AJOUT D'UN BOUTON DANS LE MENU
*
*/
add_action( "admin_menu", "pes_plugin_menu" );

function pes_plugin_menu() {
add_options_page( "Paypal ESGI", "Paypal ESGI", "manage_options", "paypal-esgi-settings", "pes_plugin_options" );
}
add_filter('plugin_action_links', 'pes_plugin_action_links', 10, 2);

function pes_plugin_action_links($links, $file) {
static $this_plugin;
if (!$this_plugin) {
$this_plugin = plugin_basename(__FILE__);
}
if ($file == $this_plugin) {
$settings_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=paypal-esgi-settings">Settings</a>';
array_unshift($links, $settings_link);
}
return $links;
}

/*
*CONFIGURATION DE LA PAGE SETTINGS
*/
function pes_plugin_options() {
if ( !current_user_can( "manage_options" ) )  {
wp_die( __( "You do not have sufficient permissions to access this page." ) );
}

echo "<table width='100%'><tr><td width='95%'><br />";
echo "<label style='color: #000;font-size:18pt;'><center>ESGI Paypal</center></label>";
echo "<form method='post' action='".$_SERVER["REQUEST_URI"]."'>";



//Mets a jout les valeurs dans le formulaires avec les valeurs ci-dessous
if (isset($_POST['update'])) {
$options['currency'] = $_POST['currency'];
$options['language'] = $_POST['language'];
$options['clientID'] = $_POST['clientID'];
$options['SecretID'] = $_POST['SecretID'];
$options['mode'] = $_POST['mode'];
$options['size'] = $_POST['size'];
$options['opens'] = $_POST['opens'];
$options['cancel'] = $_POST['cancel'];
$options['return'] = $_POST['return'];
$options['paymentaction'] = $_POST['paymentaction'];

var_dump($options);
update_option("pes_settingsoptions", $options);

echo "<br /><div class='updated'><p><strong>"; _e("Settings Updated."); echo "</strong></p></div>";
}

// get options
$value = get_option('pes_settingsoptions');




echo "</td><td></td></tr><tr><td>";



// form
echo "<br />";
?>
<div style="background-color:#333333;padding:8px;color:#eee;font-size:12pt;font-weight:bold;">
&nbsp; DEBUG
<br><br>
<span style="color:grey;">URL C9.io: https://ide.c9.io/esgipaypal/paypalesgi#index.php</span>
<br><br>
<?php
$value = get_option('pes_settingsoptions');	
echo "Access_Token = ".wp_plugin_access_token($value['clientID'], $value['SecretID']);

?>
</div> <br><br>
<div style="background-color:#333333;padding:8px;color:#eee;font-size:12pt;font-weight:bold;">
&nbsp; Comment utiliser ce plugin?
</div><div style="background-color:#fff;border: 1px solid #E5E5E5;padding:5px;"><br />
Sur cette page de paramètre, vous pouvez fournir le client ID et client secret afin d'assurer les fonctionnalités de ce plugin.<br>
Il vous suffira par la suite à appeller le shortcode présent ci-dessous sur la page où vous voulez implémenter le bouton payement paypal.
</div>


<br /><br /><div style="background-color:#333333;padding:8px;color:#eee;font-size:12pt;font-weight:bold;">
&nbsp; PayPal Account </div><div style="background-color:#fff;border: 1px solid #E5E5E5;padding:5px;"><br />

<?php

echo "Entrer ci-dessous votre client ID:<br>";
echo "<b>Client ID: </b><input type='text' name='clientID' value='".$value['clientID']."'><span style='color:red;'>*</span><br><br><br>";

echo "Entrer ci-dessous votre secret ID:<br>";
echo "<b>Secret ID: </b><input type='text' name='SecretID' value='".$value['SecretID']."'><span style='color:red;'>*</span>";


echo "<br><br><br><b>Sandbox:</b>";
echo "&nbsp; &nbsp; <input "; if ($value['mode'] == "1") { echo "checked='checked'"; } echo " type='radio' name='mode' value='1'>Activer (Mode sandbox)";
echo "&nbsp; &nbsp; <input "; if ($value['mode'] == "2") { echo "checked='checked'"; } echo " type='radio' name='mode' value='2'>Desactiver (Mode live)";


echo "<br /><br /></div>";
?>
<br /><br /><div style="background-color:#333333;padding:8px;color:#eee;font-size:12pt;font-weight:bold;">
&nbsp; Paramètres </div><div style="background-color:#fff;border: 1px solid #E5E5E5;padding:5px;"><br />
	<b>Devise:</b> 
	<select name="currency">
		<option <?php if ($value['currency'] == "1") { echo "SELECTED"; } ?> value="1">Australian Dollar - AUD</option>
		<option <?php if ($value['currency'] == "2") { echo "SELECTED"; } ?> value="2">Brazilian Real - BRL</option> 
		<option <?php if ($value['currency'] == "3") { echo "SELECTED"; } ?> value="3">Canadian Dollar - CAD</option>
		<option <?php if ($value['currency'] == "4") { echo "SELECTED"; } ?> value="4">Czech Koruna - CZK</option>
		<option <?php if ($value['currency'] == "5") { echo "SELECTED"; } ?> value="5">Danish Krone - DKK</option>
		<option <?php if ($value['currency'] == "6") { echo "SELECTED"; } ?> value="6">Euro - EUR</option>
		<option <?php if ($value['currency'] == "7") { echo "SELECTED"; } ?> value="7">Hong Kong Dollar - HKD</option> 	 
		<option <?php if ($value['currency'] == "8") { echo "SELECTED"; } ?> value="8">Hungarian Forint - HUF</option>
		<option <?php if ($value['currency'] == "9") { echo "SELECTED"; } ?> value="9">Israeli New Sheqel - ILS</option>
		<option <?php if ($value['currency'] == "10") { echo "SELECTED"; } ?> value="10">Japanese Yen - JPY</option>
		<option <?php if ($value['currency'] == "11") { echo "SELECTED"; } ?> value="11">Malaysian Ringgit - MYR</option>
		<option <?php if ($value['currency'] == "12") { echo "SELECTED"; } ?> value="12">Mexican Peso - MXN</option>
		<option <?php if ($value['currency'] == "13") { echo "SELECTED"; } ?> value="13">Norwegian Krone - NOK</option>
		<option <?php if ($value['currency'] == "14") { echo "SELECTED"; } ?> value="14">New Zealand Dollar - NZD</option>
		<option <?php if ($value['currency'] == "15") { echo "SELECTED"; } ?> value="15">Philippine Peso - PHP</option>
		<option <?php if ($value['currency'] == "16") { echo "SELECTED"; } ?> value="16">Polish Zloty - PLN</option>
		<option <?php if ($value['currency'] == "17") { echo "SELECTED"; } ?> value="17">Pound Sterling - GBP</option>
		<option <?php if ($value['currency'] == "18") { echo "SELECTED"; } ?> value="18">Russian Ruble - RUB</option>
		<option <?php if ($value['currency'] == "19") { echo "SELECTED"; } ?> value="19">Singapore Dollar - SGD</option>
		<option <?php if ($value['currency'] == "20") { echo "SELECTED"; } ?> value="20">Swedish Krona - SEK</option>
		<option <?php if ($value['currency'] == "21") { echo "SELECTED"; } ?> value="21">Swiss Franc - CHF</option>
		<option <?php if ($value['currency'] == "22") { echo "SELECTED"; } ?> value="22">Taiwan New Dollar - TWD</option>
		<option <?php if ($value['currency'] == "23") { echo "SELECTED"; } ?> value="23">Thai Baht - THB</option>
		<option <?php if ($value['currency'] == "24") { echo "SELECTED"; } ?> value="24">Turkish Lira - TRY</option>
		<option <?php if ($value['currency'] == "25") { echo "SELECTED"; } ?> value="25">U.S. Dollar - USD</option>
	</select>
	<br>
	
	<?php 
	$siteurl = get_site_url();
	echo "<br /><b>Cancel URL: </b>";
	echo "<input type='text' name='cancel' value='".$value['cancel']."'> Optional <br />";
	echo "Ce lien d'annulation représente lorsque un utilisateur Paypal appuis sur le bouton 'Annuler' sur paypal lors de l'achat. <br />Voici un exemple: $siteurl/dommage.html <br /><br />";

	echo "<b>Return URL: </b>";
	echo "<input type='text' name='return' value='".$value['return']."'> Optional <br />";
	echo "Ce lien de retour représente la landing page où l'utilisateur attérie après avoir payes avec succès sur paypal.<br> Example: $siteurl/merci.html <br>";
	?>
</div>

<input type='hidden' name='update'><br />
<input type='submit' name='btn2' class='button-primary' style='font-size: 17px;line-height: 28px;height: 32px;' value='Sauvegarder'>


<?php
echo "</form></table>";
}

//Ajout de notice

add_action('admin_notices', 'pes_admin_notices');

function pes_admin_notices() {
if (!get_option('pes_plugin_notice_shown')) {
echo "<div class='updated'><p><a href='admin.php?page=paypal-esgi-settings'>Paypal ESGI: Cliquez ici pour voir/modifier les paramètres de l'application</a>.</p></div>";
update_option("pes_plugin_notice_shown", "true");
}
}

/*
* Creation d'un shortcode
*/

add_shortcode('pes', 'pes_options');

function pes_options($atts) {

// get shortcode user fields
$atts = shortcode_atts(array('name' => 'Example Name','price' => '0.00','size' => '','align' => ''), $atts);

// get settings page values
$value = get_option('pes_settingsoptions');	


// live of test mode
if ($value['mode'] == "1") {
$account = $value['SecretID'];
$path = "sandbox.paypal";
} elseif ($value['mode'] == "2")  {
$account = $value['clientID'];
$path = "paypal";
}

// payment action
if ($value['paymentaction'] == "1") {
$paymentaction = "sale";
} elseif ($value['paymentaction'] == "2")  {
$paymentaction = "authorization";
} else {
$paymentaction = "sale";
}

// currency
if ($value['currency'] == "1") { $currency = "AUD"; }
if ($value['currency'] == "2") { $currency = "BRL"; }
if ($value['currency'] == "3") { $currency = "CAD"; }
if ($value['currency'] == "4") { $currency = "CZK"; }
if ($value['currency'] == "5") { $currency = "DKK"; }
if ($value['currency'] == "6") { $currency = "EUR"; }
if ($value['currency'] == "7") { $currency = "HKD"; }
if ($value['currency'] == "8") { $currency = "HUF"; }
if ($value['currency'] == "9") { $currency = "ILS"; }
if ($value['currency'] == "10") { $currency = "JPY"; }
if ($value['currency'] == "11") { $currency = "MYR"; }
if ($value['currency'] == "12") { $currency = "MXN"; }
if ($value['currency'] == "13") { $currency = "NOK"; }
if ($value['currency'] == "14") { $currency = "NZD"; }
if ($value['currency'] == "15") { $currency = "PHP"; }
if ($value['currency'] == "16") { $currency = "PLN"; }
if ($value['currency'] == "17") { $currency = "GBP"; }
if ($value['currency'] == "18") { $currency = "RUB"; }
if ($value['currency'] == "19") { $currency = "SGD"; }
if ($value['currency'] == "20") { $currency = "SEK"; }
if ($value['currency'] == "21") { $currency = "CHF"; }
if ($value['currency'] == "22") { $currency = "TWD"; }
if ($value['currency'] == "23") { $currency = "THB"; }
if ($value['currency'] == "24") { $currency = "TRY"; }
if ($value['currency'] == "25") { $currency = "USD"; }

// language
if ($value['language'] == "1") {
$language = "da_DK";
$image = "https://www.paypalobjects.com/da_DK/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/da_DK/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/da_DK/DK/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Danish

if ($value['language'] == "2") {
$language = "nl_BE";
$image = "https://www.paypalobjects.com/nl_NL/NL/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/nl_NL/NL/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/nl_NL/NL/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Dutch

if ($value['language'] == "3") {
$language = "EN_US";
$image = "https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //English

if ($value['language'] == "20") {
$language = "en_GB";
$image = "https://www.paypalobjects.com/en_US/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/en_US/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //English - UK

if ($value['language'] == "4") {
$language = "fr_CA";
$image = "https://www.paypalobjects.com/fr_CA/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/fr_CA/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/fr_CA/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //French

if ($value['language'] == "5") {
$language = "de_DE";
$image = "https://www.paypalobjects.com/de_DE/DE/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/de_DE/DE/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/de_DE/DE/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //German

if ($value['language'] == "6") {
$language = "he_IL";
$image = "https://www.paypalobjects.com/he_IL/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/he_IL/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/he_IL/IL/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Hebrew

if ($value['language'] == "7") {
$language = "it_IT";
$image = "https://www.paypalobjects.com/it_IT/IT/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/it_IT/IT/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/it_IT/IT/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Italian

if ($value['language'] == "8") {
$language = "ja_JP";
$image = "https://www.paypalobjects.com/ja_JP/JP/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/ja_JP/JP/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/ja_JP/JP/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Japanese

if ($value['language'] == "9") {
$language = "no_NO";
$image = "https://www.paypalobjects.com/no_NO/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/no_NO/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/no_NO/NO/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Norwgian

if ($value['language'] == "10") {
$language = "pl_PL";
$image = "https://www.paypalobjects.com/pl_PL/PL/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/pl_PL/PL/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/pl_PL/PL/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Polish

if ($value['language'] == "11") {
$language = "pt_BR";
$image = "https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/pt_PT/PT/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Portuguese

if ($value['language'] == "12") {
$language = "ru_RU";
$image = "https://www.paypalobjects.com/ru_RU/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/ru_RU/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/ru_RU/RU/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Russian

if ($value['language'] == "13") {
$language = "es_ES";
$image = "https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/es_ES/ES/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Spanish

if ($value['language'] == "14") {
$language = "sv_SE";
$image = "https://www.paypalobjects.com/sv_SE/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/sv_SE/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/sv_SE/SE/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Swedish

if ($value['language'] == "15") {
$language = "zh_CN";
$image = "https://www.paypalobjects.com/zh_XC/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/zh_XC/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/zh_XC/C2/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Simplified Chinese - China

if ($value['language'] == "16") {
$language = "zh_HK";
$image = "https://www.paypalobjects.com/zh_HK/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/zh_HK/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/zh_HK/HK/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Traditional Chinese - Hong Kong

if ($value['language'] == "17") {
$language = "zh_TW";
$image = "https://www.paypalobjects.com/zh_TW/TW/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/zh_TW/TW/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/zh_TW/TW/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Traditional Chinese - Taiwan

if ($value['language'] == "18") {
$language = "tr_TR";
$image = "https://www.paypalobjects.com/tr_TR/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/tr_TR/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/tr_TR/TR/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Turkish

if ($value['language'] == "19") {
$language = "th_TH";
$image = "https://www.paypalobjects.com/th_TH/i/btn/btn_buynow_SM.gif";
$imageb = "https://www.paypalobjects.com/th_TH/i/btn/btn_buynow_LG.gif";
$imagecc = "https://www.paypalobjects.com/th_TH/TH/i/btn/btn_buynowCC_LG.gif";
$imagenew = "https://www.paypalobjects.com/webstatic/en_US/i/buttons/buy-logo-medium.png";
} //Thai

if (!empty($atts['size'])) {
if ($atts['size'] == "1") { $img = $image; }
if ($atts['size'] == "2") { $img = $imageb; }
if ($atts['size'] == "3") { $img = $imagecc; }
if ($atts['size'] == "5") { $img = $imagenew; }
} else {
if ($value['size'] == "1") { $img = $image; }
if ($value['size'] == "2") { $img = $imageb; }
if ($value['size'] == "3") { $img = $imagecc; }
if ($value['size'] == "4") { $img = $value['upload_image']; }
if ($value['size'] == "5") { $img = $imagenew; }
}

// window action
if ($value['opens'] == "1") { $target = ""; }
if ($value['opens'] == "2") { $target = "_blank"; }

// alignment
if ($atts['align'] == "left") { $alignment = "style='float: left;'"; }
if ($atts['align'] == "right") { $alignment = "style='float: right;'"; }
if ($atts['align'] == "center") { $alignment = "style='margin-left: auto;margin-right: auto;width:170px'"; }

if (!isset($alignment)) { $alignment = ""; }

if (!isset($note)) { $note = ""; }

$output = "";
$output .= "<div $alignment>";
$output .= "<form target='$target' action='https://www.$path.com/cgi-bin/webscr' method='post'>";
$output .= "<input type='hidden' name='cmd' value='_xclick' />";
$output .= "<input type='hidden' name='business' value='$account' />";
$output .= "<input type='hidden' name='item_name' value='". $atts['name'] ."' />";
$output .= "<input type='hidden' name='currency_code' value='$currency' />";
$output .= "<input type='hidden' name='amount' value='". $atts['price'] ."' />";
$output .= "<input type='hidden' name='lc' value='$language'>";
$output .= "<input type='hidden' name='no_note' value='$note'>";
$output .= "<input type='hidden' name='paymentaction' value='$paymentaction'>";
$output .= "<input type='hidden' name='return' value='". $value['return'] ."' />";
$output .= "<input type='hidden' name='bn' value='WPPlugin_SP'>";
$output .= "<input type='hidden' name='cancel_return' value='". $value['cancel'] ."' />";
$output .= "<input style='border: none;' class='paypalbuttonimage' type='image' src='$img' border='0' name='submit' alt='Make your payments with PayPal. It is free, secure, effective.'>";
$output .= "<img alt='' border='0' style='border:none;display:none;' src='https://www.paypal.com/$language/i/scr/pixel.gif' width='1' height='1'>";
$output .= "</form></div>";

return $output;

}
?>