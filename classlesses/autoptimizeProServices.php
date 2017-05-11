<?php
/*
Classlessly add a "more tools" tab to promote (future) AO addons and/ or affiliate services
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action('admin_init', 'ao_proserv_tabs_preinit');
function ao_proserv_tabs_preinit() {
    if (apply_filters('autoptimize_filter_show_partner_tabs',true)) {
        add_filter('autoptimize_filter_settingsscreen_tabs','ao_add_proserv_tabs');
    }
}

function ao_add_proserv_tabs($in) {
    $in=array_merge($in,array('ao_proserv' => __('Need Pro Help?','autoptimize')));
    return $in;
}

add_action('admin_menu','ao_proserv_init');
function ao_proserv_init() {
    if (apply_filters('autoptimize_filter_show_partner_tabs',true)) {
        $hook=add_submenu_page(NULL,'AO proserv','AO proserv','manage_options','ao_proserv','ao_proserv_displayPage');
        // register_settings here as well if needed
    }
}

function ao_proserv_displayPage() {
    ?>
    <style>
    </style>
    <div class="wrap">
        <h1><?php _e('Autoptimize Settings','autoptimize'); ?></h1>
        <?php echo autoptimizeConfig::ao_admin_tabs(); ?>
        <?php 
        echo ao_proserv_getTemplate();
        // $ao_proserv_form = "<div style='width:70%;'><form id='ao_proserv_form'>".$ao_proserv_form."</form></div>";
        echo ao_proserv_mailJS();
        ?>
    </div>
    <?php
}

function ao_proserv_getTemplate() {
    /*
        * get from remote (short timeout!)
        * store in transient for 1d
        * use hardcoded one as fallback
    */ 
    return "<h2>Titel</h2>
    <p>paragraaf 1 paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1</p>
    <legend></legend>
    <form id='ao_proserv_form'>
        <label><input id='proserv_name' name='name' type='text' value='' placeholder='Your name' required='true' /></label>
        <label><input id='proserv_mail' type='email' name='mail' value='' placeholder='Your email-address' required='true' /></label><br />
        <label><input id='proserv_url' type='text' name='url' value='" . site_url() . "' pattern='^(https?:)?\/\/([\da-z\.-]+)\.([\da-z\.]{2,6})([\/\w \.-]*)*(:\d{2,5})?\/?$' placeholder='Your website' required='true' /></label><br />
        <label><input type='radio' name='proserv_type' value='AO'>Autoptimize Pro Configuration (€99)</label><br />
        <label><input type='radio' name='proserv_type' value='OM'>Complete Speed Optimization (€499)</label><br />
        <label><textarea id='proserv_extra' name='extra' rows='5' style='width:100%;' placeholder='Anything you want to ask or tell us?'></textarea></label><br />
        <input id='proserv_submit' type='submit' value='Submit' class='button-primary'>
    </form>
    <p>paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2</p>
    ";
}

function ao_proserv_mailJS() {
    echo "<script>
    jQuery('#proserv_submit').click(
		function(){
			ao_proserv_sendmail();
			return false;
		}
	);
    
    var data = {
        'action': 'ao_proserv_sendmail',
        'ao_proserv_nonce': '".wp_create_nonce( "ao_proserv_nonce".md5(site_url()) )."',
        'ao_proserv_form_data': new Object
    };

    function ao_proserv_sendmail() {
        // collect data
        dontSend = new Array;
        jQuery('#ao_proserv_form').find(':input').each(
            function() {
				if (!this.checkValidity()) {
					dontSend.push(this.id);
				} else {
					if (this.name && (this.type !== 'radio' || this.checked)) {
						data['ao_proserv_form_data'][this.name] = this.value;
					}
				}
            }
        );
        
        // send to server
        if (typeof dontSend === 'undefined' || dontSend.length === 0) {
			jQuery.post(ajaxurl, data, function(response) {
				if (response != 'ok') {
					// displayNotice(response_array['string']);
					alert('nok: '+response);
				} else {
					alert('ok');
				}
			});
		} else {
			console.log(dontSend);
			for (error in dontSend) {
				console.log(dontSend[error]);
				jQuery('#'+dontSend[error]).css( "border", "red" );
			}
		}
    }
    </script>";  
}

function ao_proserv_mailAjax() {
    check_ajax_referer( "ao_proserv_nonce".md5(site_url()), 'ao_proserv_nonce' );
    if ( current_user_can('manage_options') ) {
        // mail stuff
        $mailbody="New lead from the Autoptimize plugin admin pages:\n";
        
        foreach ($_POST["ao_proserv_form_data"] as $name => $value) {
            $mailbody .= $name.": ".$value."\n";
        }
        
        $mailbody="Now go close that deal!\n";
        $to="futtta@gmail.com";
        $subject="New Autoptimize consultancy lead";
        
        if (wp_mail($to,$subject,$mailbody)) {
            echo "ok";
        } else {
            echo "nomail";
        }
    }
    die();
}
add_action( 'wp_ajax_ao_proserv_sendmail', 'ao_proserv_mailAjax' );

// add_action( 'phpmailer_init', 'mailer_config', 10, 1);
function mailer_config(PHPMailer $mailer){
  $mailer->IsSMTP();
  $mailer->Host = "out.gmail.com"; // your SMTP server
  $mailer->Port = 25;
  $mailer->SMTPDebug = 2; // write 0 if you don't want to see client/server communication in page
  $mailer->CharSet  = "utf-8";
}
