<?php
/*
Classlessly add a "more tools" tab to promote (future) AO addons and/ or affiliate services
*/

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
        echo str_replace("<!--ao_proserv_form-->","<input type=\"text\">",$ao_proserv_template);
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
    return "
    <h2>Titel</h2>
    <p>paragraaf 1 paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1paragraaf 1</p>
    <!--ao_proserv_form-->
    <p>paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2 paragraaf 2</p>
    ";
}
/* function getAOPartnerFeed() {
    $noFeedText=__( 'Have a look at <a href="http://optimizingmatters.com/">optimizingmatters.com</a> for Autoptimize power-ups!', 'autoptimize' );

    if (apply_filters('autoptimize_settingsscreen_remotehttp',true)) {
        $rss = fetch_feed( "http://feeds.feedburner.com/OptimizingMattersDownloads" );
        $maxitems = 0;

        if ( ! is_wp_error( $rss ) ) {
            $maxitems = $rss->get_item_quantity( 20 ); 
            $rss_items = $rss->get_items( 0, $maxitems );
        } ?>
        <ul>
            <?php
            if ( $maxitems == 0 ) {
                echo $noFeedText;
            } else {
                foreach ( $rss_items as $item ) : 
                    $itemURL = esc_url( $item->get_permalink() ); ?>
                    <li class="itemDetail">
                        <h3 class="itemTitle"><a href="<?php echo $itemURL; ?>" target="_blank"><?php echo esc_html( $item->get_title() ); ?></a></h3>
                        <?php
                        if (($enclosure = $item->get_enclosure()) && (strpos($enclosure->get_type(),"image")!==false) ) {
                            $itemImgURL=esc_url($enclosure->get_link());
                            echo "<div class=\"itemImage\"><a href=\"".$itemURL."\" target=\"_blank\"><img src=\"".$itemImgURL."\"/></a></div>";
                        }
                        ?>
                        <div class="itemDescription"><?php echo wp_kses_post($item -> get_description() ); ?></div>
                        <div class="itemButtonRow"><div class="itemButton button-secondary"><a href="<?php echo $itemURL; ?>" target="_blank">More info</a></div></div>
                    </li>
                <?php endforeach; ?>
            <?php } ?>
        </ul>
        <?php
    } else {
        echo $noFeedText;
    }
}
*/