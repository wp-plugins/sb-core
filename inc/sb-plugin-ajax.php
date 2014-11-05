<?php
function sb_core_deactivate_ajax_callback() {
    echo SB_Message::get_deactivate_sb_core_confirm_text();
    die();
}
add_action('wp_ajax_sb_core_deactivate_message', 'sb_core_deactivate_ajax_callback');

function sb_plugins_ajax_callback() {
    include SB_CORE_INC_PATH . '/sb-plugins-ajax.php';
    die();
}
add_action('wp_ajax_sb_plugins', 'sb_plugins_ajax_callback');

function sb_option_reset_ajax_callback() {
    $sb_page = isset($_POST['sb_option_page']) ? $_POST['sb_option_page'] : '';
    switch($sb_page) {
        case 'sb_paginate':
            echo json_encode(SB_Default_Setting::sb_paginate());
            break;
        default:
            break;
    }
    die();
}
add_action('wp_ajax_sb_option_reset', 'sb_option_reset_ajax_callback');

function sb_add_ui_item_ajax_callback() {
    $type = isset($_POST['data_type']) ? $_POST['data_type'] : '';
    switch($type) {
        case 'rss_feed':
            include SB_CORE_INC_PATH . '/ajax-add-rss-feed.php';
            break;
    }
    die();
}
add_action('wp_ajax_sb_add_ui_item', 'sb_add_ui_item_ajax_callback');

function sb_ui_reset_ajax_callback() {
    $type = isset($_POST['data_type']) ? $_POST['data_type'] : '';
    switch($type) {
        case 'rss_feed':
            $options = SB_Option::get();
            unset($options['theme']['rss_feed']);
            SB_Option::update($options);
            break;
    }
    die();
}
add_action('wp_ajax_sb_ui_reset', 'sb_ui_reset_ajax_callback');

function sb_deactivate_all_sb_product_ajax_callback() {
    sb_deactivate_all_sb_plugin();
    sb_switch_to_default_theme();
    update_option('sb_core_activated', 0);
    update_option('sb_core_deactivated_caller', 'user');
    die();
}
add_action('wp_ajax_sb_deactivate_all_sb_product', 'sb_deactivate_all_sb_product_ajax_callback');

function sb_deactivate_all_sb_plugin() {
    $activated_plugins = get_option('active_plugins');
    $sb_plugins = array(
        'sb-core/sb-core.php',
        'sb-tbfa/sb-tbfa.php'
    );
    $new_plugins = $activated_plugins;
    foreach($activated_plugins as $plugin) {
        if(in_array($plugin, $sb_plugins)) {
            $item = array($plugin);
            $new_plugins = array_diff($new_plugins, $item);
        }
    }
    update_option('active_plugins', $new_plugins);
}

function sb_switch_to_default_theme() {
    $theme = wp_get_theme();
    $text_domain = $theme->get('TextDomain');
    if('sb-theme' == $text_domain) {
        $theme = sb_core_get_default_theme();
        if(!empty($theme)) {
            switch_theme($theme->get('TextDomain'));
        }
    }
}