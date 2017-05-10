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
        <label><input name='name' type='text' value='' placeholder='Your name' required='true' /></label>
        <label><input type='email' name='mail' value='' placeholder='Your email-address' required='true' /></label><br />
        <label><input type='text' name='url' value='" . site_url() . "' pattern='^(https?:)?\/\/([\da-z\.-]+)\.([\da-z\.]{2,6})([\/\w \.-]*)*(:\d{2,5})?\/?$' placeholder='Your website' required='true' /></label><br />
        <label><input type='radio' name='proserv_type' value='AO'>Autoptimize Pro Configuration (€99)</label><br />
        <label><input type='radio' name='proserv_type' value='OM'>Complete Speed Optimization (€499)</label><br />
        <label><textarea name='extra' rows='5' style='width:100%;' placeholder='Anything you want to ask or tell us?'></textarea></label><br />
        <input type='submit' value='Submit' class='button-primary'>
    </form>
    <p>paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2</p>
    ";
}

function ao_proserv_mailJS() {
    echo "<script>
    var data = {
        'action': 'ao_proserv_sendmail',
        'ao_proserv_nonce': '".wp_create_nonce( "ao_proserv_nonce".md5(site_url()) )."',
        'ao_proserv_form_data': new Object
    };

    function ao_proserv_sendmail() {
        // collect data
        jQuery('#ao_proserv_form').find(':input').each(
            function() {
                if (this.name && (this.type !== 'radio' || this.checked)) {
                    data['ao_proserv_form_data'][this.name] = this.value;
                }
            }
        );
        
        // send to server
        jQuery.post(ajaxurl, data, function(response) {
            if (response != 'ok') {
                // displayNotice(response_array['string']);
                alert('nok');
            } else {
                alert('ok');
            }
        });
    }
    </script>";  
}

function ao_proserv_mailAjax() {
    check_ajax_referer( "ao_proserv_nonce".md5(site_url()), 'ao_proserv_nonce' );
    if ( current_user_can('manage_options') ) {
        // mail stuff
        $mailbody="";
        foreach ($_POST["ao_proserv_form_data"] as $name => $value) {
            $mailbody .= $name.": ".$value."\n";
        }
        error_log($mailbody);
        
        // and echo OK leading to browser reload
        echo "ok";
    }
    die();
}
add_action( 'wp_ajax_ao_proserv_sendmail', 'ao_proserv_mailAjax' );