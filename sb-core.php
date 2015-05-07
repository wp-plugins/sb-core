<?php
/*
Plugin Name: SB Core
Plugin URI: http://hocwp.net/
Description: SB Core is not only a plugin, it contains core function for all plugins and themes that are created by SB Team.
Author: SB Team
Version: 1.6.8
Author URI: http://hocwp.net/
Text Domain: sb-core
Domain Path: /languages/
*/

function sb_core_deactivate_all_sb_plugins() {
    $activated_plugins = get_option( 'active_plugins' );
    $sb_plugins = array(
        'sb-banner-widget/sb-banner-widget.php',
        'sb-clean/sb-clean.php',
        'sb-comment/sb-comment.php',
        'sb-core/sb-core.php',
        'sb-login-page/sb-login-page.php',
        'sb-paginate/sb-paginate.php',
        'sb-post-widget/sb-post-widget.php',
        'sb-tab-widget/sb-tab-widget.php',
        'sb-tbfa/sb-tbfa.php'
    );
    $new_plugins = $activated_plugins;
    foreach ( $activated_plugins as $plugin ) {
        if ( in_array( $plugin, $sb_plugins ) ) {
            $item = array( $plugin );
            $new_plugins = array_diff( $new_plugins, $item );
        }
    }
    update_option( 'active_plugins', $new_plugins );
}

function sb_core_theme_support_message() {
    unset($_GET['activate']);
    unset($_GET['error']);
    ?>
    <div class="updated" id="message"><p><strong>Note:</strong> Plugin <strong>SB Core</strong> has been deactivated because current theme doesn't need it any more.</p></div>
    <?php
    sb_core_deactivate_all_sb_plugins();
    delete_transient('sb_core_error');
}

$sb_theme_version = get_option('sb_theme_version');

if(!(bool)get_option('sb_theme_use_old_version') && (defined('SB_CORE_VERSION') || (defined('SB_THEME_VERSION') && version_compare(SB_THEME_VERSION, '1.7.0', '>=')) || (!empty($sb_theme_version) && version_compare($sb_theme_version, '1.7.0', '>=')))) {
    set_transient('sb_core_error', 1, MINUTE_IN_SECONDS);
    add_action('admin_notices', 'sb_core_theme_support_message');
    return;
}

define( 'SB_CORE_VERSION', '1.6.8' );

define( 'SB_CORE_FILE', __FILE__ );

define( 'SB_CORE_PATH', untrailingslashit( plugin_dir_path( SB_CORE_FILE ) ) );

define( 'SB_CORE_URL', plugins_url( '', SB_CORE_FILE ) );

define( 'SB_CORE_INC_PATH', SB_CORE_PATH . '/inc' );

define( 'SB_CORE_BASENAME', plugin_basename( SB_CORE_FILE ) );

define( 'SB_CORE_DIRNAME', dirname( SB_CORE_BASENAME ) );

require SB_CORE_INC_PATH . '/sb-plugin-load.php';