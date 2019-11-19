<?php

//////////////////////////////////////////////////////////////
//===========================================================
// license.php
//===========================================================
// PAGELAYER
// Inspired by the DESIRE to be the BEST OF ALL
// ----------------------------------------------------------
// Started by: Pulkit Gupta
// Date:	   23rd Jan 2017
// Time:	   23:00 hrs
// Site:	   http://pagelayer.com/wordpress (PAGELAYER)
// ----------------------------------------------------------
// Please Read the Terms of use at http://pagelayer.com/tos
// ----------------------------------------------------------
//===========================================================
// (c)Pagelayer Team
//===========================================================
//////////////////////////////////////////////////////////////

// Are we being accessed directly ?
if(!defined('PAGELAYER_VERSION')) {
	exit('Hacking Attempt !');
}

// The Pagelayer Settings Header
function pagelayer_page_header($title = 'Pagelayer Editor'){
	
	wp_enqueue_script( 'pagelayer-admin', PAGELAYER_JS.'/pagelayer-admin.js', array('jquery'), PAGELAYER_VERSION);
	wp_enqueue_style( 'pagelayer-admin', PAGELAYER_CSS.'/pagelayer-admin.css', array(), PAGELAYER_VERSION);
		
	echo '<div style="margin: 10px 20px 0 2px;">	
<div class="metabox-holder columns-2">
<div class="postbox-container">	
<div id="top-sortables" class="meta-box-sortables ui-sortable">
	
	<table cellpadding="2" cellspacing="1" width="100%" class="fixed" border="0">
		<tr>
			<td valign="top"><h1>'.$title.'</h1></td>
			<td align="right"><a target="_blank" class="button button-primary" href="https://wordpress.org/support/view/plugin-reviews/pagelayer">Review Pagelayer</a></td>
			<td align="right" width="40"><a target="_blank" href="https://twitter.com/pagelayer"><img src="'.PAGELAYER_URL.'/images/twitter.png" /></a></td>
			<td align="right" width="40"><a target="_blank" href="https://www.facebook.com/pagelayer/"><img src="'.PAGELAYER_URL.'/images/facebook.png" /></a></td>
		</tr>
	</table>
	<hr />
	
	<!--Main Table-->
	<table cellpadding="8" cellspacing="1" width="100%" class="fixed">
	<tr>
		<td valign="top">';

}

// The Pagelayer Settings footer
function pagelayer_page_footer(){
	
	echo '
		</td>
		<td width="200" valign="top" id="pagelayer-right-bar">';
	
	if(!defined('PAGELAYER_PREMIUM')){
		
		echo '
			<div class="postbox" style="min-width:0px !important;">
				<h2 class="hndle ui-sortable-handle">
					<span><a target="_blank" href="'.PAGELAYER_PRO_URL.'"><img src="'.PAGELAYER_URL.'/images/pagelayer_product.png" width="100%" /></a></span>
				</h2>
				<div class="inside">
					<i>Upgrade to the premium version and get the following features </i>:<br>
					<ul class="pagelayer-right-ul">
						<li>60+ Premium Widgets</li>
						<li>400+ Premium Sections</li>
						<li>Theme Builder</li>
						<li>WooCommerce Builder</li>
						<li>Theme Creator and Exporter</li>
						<li>Form Builder</li>
						<li>And many more ...</li>
					</ul>
					<center><a class="button button-primary" target="_blank" href="'.PAGELAYER_PRO_URL.'">Upgrade</a></center>
				</div>
			</div>';
		
	}
	
	echo '
			<div class="postbox" style="min-width:0px !important;">
				<h2 class="hndle ui-sortable-handle">
					<span><a target="_blank" href="https://wpcentral.co/?from=pagelayer-plugin"><img src="'.PAGELAYER_URL.'/images/wpcentral_product.png" width="100%" /></a></span>
				</h2>
				<div class="inside">
					<i>Manage all your WordPress sites from <b>1 dashboard</b> </i>:<br>
					<ul class="pagelayer-right-ul">
						<li>1-click Admin Access</li>
						<li>Update WordPress</li>
						<li>Update Themes</li>
						<li>Update Plugins</li>
						<li>Backup your WordPress Site</li>
						<li>Plugins & Theme Management</li>
						<li>Post Management</li>
						<li>And many more ...</li>
					</ul>
					<center><a class="button button-primary" target="_blank" href="https://wpcentral.co/?from=pagelayer-plugin">Visit wpCentral</a></center>
				</div>
			</div>
		
		</td>
	</tr>
	</table>
	<br />
	<div style="width:45%;background:#FFF;padding:15px; margin:auto">
		<b>Let your followers know that you use Pagelayer to build your website :</b>
		<form method="get" action="https://twitter.com/intent/tweet" id="tweet" onsubmit="return dotweet(this);">
			<textarea name="text" cols="45" row="3" style="resize:none;">I easily built my #WordPress #site using @pagelayer</textarea>
			&nbsp; &nbsp; <input type="submit" value="Tweet!" class="button button-primary" onsubmit="return false;" id="twitter-btn" style="margin-top:20px;"/>
		</form>
		
	</div>
	<br />
	
	<script>
	function dotweet(ele){
		window.open(jQuery("#"+ele.id).attr("action")+"?"+jQuery("#"+ele.id).serialize(), "_blank", "scrollbars=no, menubar=no, height=400, width=500, resizable=yes, toolbar=no, status=no");
		return false;
	}
	</script>
	
	<hr />
	<a href="'.PAGELAYER_WWW_URL.'" target="_blank">Pagelayer</a> v'.PAGELAYER_VERSION.'. You can report any bugs <a href="http://wordpress.org/support/plugin/pagelayer" target="_blank">here</a>.

</div>	
</div>
</div>
</div>';

}

// The License Page
function pagelayer_license(){
	
	global $pl_error;
	
	if(!empty($_REQUEST['install_pro'])){
		pagelayer_install_pro();
		return;		
	}

	// Is there a license key ?
	if(isset($_POST['save_pl_license'])){
	
		$license = pagelayer_optpost('pagelayer_license');
		
		// Check if its a valid license
		if(empty($license)){
			$pl_error['lic_invalid'] = __('The license key was not submitted', 'pagelayer');
			return pagelayer_license_T();
		}
		
		$resp = wp_remote_get(PAGELAYER_API.'license.php?license='.$license, array('timeout' => 30));
		
		if(is_array($resp)){
			$json = json_decode($resp['body'], true);
			//print_r($json);
		}else{
		
			$pl_error['resp_invalid'] = __('The response was malformed<br>'.var_export($resp, true), 'pagelayer');
			return pagelayer_license_T();
			
		}
		
		// Save the License
		if(empty($json['license'])){
		
			$pl_error['lic_invalid'] = __('The license key is invalid', 'pagelayer');
			return pagelayer_license_T();
			
		}else{
			
			update_option('pagelayer_license', $json);
	
			// Load license
			pagelayer_load_license();
			
			// Mark as saved
			$GLOBALS['pl_saved'] = true;
		}
		
	}
	
	pagelayer_license_T();
	
}

// The License Page - THEME
function pagelayer_license_T(){
	
	global $pagelayer, $pl_error;

	pagelayer_page_header('Pagelayer License');

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div id="message" class="updated"><p>'. __('The settings were saved successfully', 'pagelayer'). '</p></div><br />';
	}

	// If the license is active and you are the free version, then suggest to install the pro
	if(!empty($pagelayer->license['status']) && !defined('PAGELAYER_PREMIUM') && empty($_REQUEST['install_pro'])){
		echo '<div class="updated"><p>'. __('You have activated the license, but are using the Free version ! <a href="'.admin_url('admin.php?page=pagelayer_license&install_pro=1').'" class="button button-primary">Install Pro Now</a>', 'pagelayer'). '</p></div><br />';
	}
	
	if(date('Ymd') <= 20200331 && !defined('PAGELAYER_PREMIUM')){
		echo '<div class="updated"><p><span style="font-size: 14px"><b>Promotional Offer</b></span> : If you buy <a href="'.PAGELAYER_PRO_URL.'"><b>Pagelayer Pro</b></a> before <b>31st March, 2020</b> then you will get an additional year free and your license will expire on <b>31st March, 2022</b></p></div><br />.';
	}
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}
	
	?>
	
	<div class="postbox">
	
		<button class="handlediv button-link" aria-expanded="true" type="button">
			<span class="screen-reader-text">Toggle panel: System Information</span>
			<span class="toggle-indicator" aria-hidden="true"></span>
		</button>
		
		<h2 class="hndle ui-sortable-handle">
			<span><?php echo __('System Information', 'pagelayer'); ?></span>
		</h2>
		
		<div class="inside">
		
		<form action="" method="post" enctype="multipart/form-data">
		<?php wp_nonce_field('pagelayer-options'); ?>
		<table class="wp-list-table fixed striped users" cellspacing="1" border="0" width="95%" cellpadding="10" align="center">
		<?php
			echo '
			<tr>				
				<th align="left" width="25%">'.__('Pagelayer Version', 'pagelayer').'</th>
				<td>'.PAGELAYER_VERSION.(defined('PAGELAYER_PREMIUM') ? ' (PRO Version)' : '').'</td>
			</tr>';
			
			echo '
			<tr>			
				<th align="left" valign="top">'.__('Pagelayer License', 'pagelayer').'</th>
				<td align="left">
					'.(defined('PAGELAYER_PREMIUM') && empty($pagelayer->license) ? '<span style="color:red">Unlicensed</span> &nbsp; &nbsp;' : '').' 
					<input type="text" name="pagelayer_license" value="'.(empty($pagelayer->license) ? '' : $pagelayer->license['license']).'" size="30" placeholder="e.g. PAGEL-11111-22222-33333-44444" style="width:300px;" /> &nbsp; 
					<input name="save_pl_license" class="button button-primary" value="Update License" type="submit" />';
					
					if(!empty($pagelayer->license)){
						
						$expires = $pagelayer->license['expires'];
						$expires = substr($expires, 0, 4).'/'.substr($expires, 4, 2).'/'.substr($expires, 6);
						
						echo '<div style="margin-top:10px;">License Status : '.(empty($pagelayer->license['status_txt']) ? 'N.A.' : $pagelayer->license['status_txt']).' &nbsp; &nbsp; &nbsp; 
						License Expires : '.($pagelayer->license['expires'] <= date('Ymd') ? '<span style="color:red">'.$expires.'</span>' : $expires).'
						</div>';
					}
					
					
				echo 
				'</td>
			</tr>';
			
			echo '<tr>
				<th align="left">'.__('URL', 'pagelayer').'</th>
				<td>'.get_site_url().'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Path', 'pagelayer').'</th>
				<td>'.ABSPATH.'</td>
			</tr>
			<tr>				
				<th align="left">'.__('Server\'s IP Address', 'pagelayer').'</th>
				<td>'.$_SERVER['SERVER_ADDR'].'</td>
			</tr>
			<tr>				
				<th align="left">'.__('wp-config.php is writable', 'pagelayer').'</th>
				<td>'.(is_writable(ABSPATH.'/wp-config.php') ? '<span style="color:red">Yes</span>' : '<span style="color:green">No</span>').'</td>
			</tr>';
			
			if(file_exists(ABSPATH.'/.htaccess')){
				echo '
			<tr>				
				<th align="left">'.__('.htaccess is writable', 'pagelayer').'</th>
				<td>'.(is_writable(ABSPATH.'/.htaccess') ? '<span style="color:red">Yes</span>' : '<span style="color:green">No</span>').'</td>
			</tr>';
			
			}
			
		?>
		</table>
		</form>
		
		</div>
	</div>

<?php
	
	pagelayer_page_footer();

}

// The Pagelayer Admin Dashbaoard
function pagelayer_dashboard_T(){
	
	global $pl_error;

	pagelayer_page_header('Pagelayer License');
	
	echo '<script src="'.PAGELAYER_API.(defined('PAGELAYER_PREMIUM') ? 'news_pro.js' : 'news.js').'"></script><br>';

	// Saved ?
	if(!empty($GLOBALS['pl_saved'])){
		echo '<div id="message" class="updated"><p>'. __('The settings were saved successfully', 'pagelayer'). '</p></div><br />';
	}
	
	// Any errors ?
	if(!empty($pl_error)){
		pagelayer_report_error($pl_error);echo '<br />';
	}

	pagelayer_page_footer();
	
}