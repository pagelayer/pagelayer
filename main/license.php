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

include_once(PAGELAYER_DIR.'/main/settings.php');

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