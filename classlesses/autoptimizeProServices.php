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
        $ao_proserv_template = ao_proserv_getTemplate();
        $ao_proserv_form = ao_proserv_getForm();
        echo str_replace("<!--ao_proserv_form-->",$ao_proserv_form,$ao_proserv_template);
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
    <!--ao_proserv_form-->
    <p>paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2</p>
    ";
}

function ao_proserv_getForm() {
    return "<form method='post' action='options.php'>
    <input type='hidden' name='option_page' value='ao_proserv' />
    <input type='text' value='" . site_url() . "' />
    <input type='submit'>
    </form>";
}

function ao_proserv_mailForm() {
    
}