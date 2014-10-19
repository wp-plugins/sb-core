<?php
class SB_Option {
    public static function update($sb_options) {
        self::update_option('sb_options', $sb_options);
    }
    public static function get_date_format() {
        return get_option('date_format');
    }

    public static function get_time_fortmat() {
        return get_option('time_format');
    }

    public static function get_date_time_format() {
        return self::get_date_format() . ' ' . self::get_time_fortmat();
    }

    public static function get_timezone_string() {
        return get_option('timezone_string');
    }

    public static function update_permalink($struct) {
        global $wp_rewrite;
        $wp_rewrite->set_permalink_structure( $struct );
    }

    public static function get_permalink_struct() {
        return get_option('permalink_structure');
    }

    public static function get() {
        global $sb_options;
        if(empty($sb_options)) {
            $sb_options = get_option('sb_options');
        }
        return $sb_options;
    }

    public static function get_favicon_url() {
        $options = self::get();
        return isset($options['theme']['favicon']) ? $options['theme']['favicon'] : '';
    }

    public static function get_logo_url() {
        $options = self::get();
        return isset($options['theme']['logo']) ? $options['theme']['logo'] : '';
    }

    public static function the_footer_text_html() {
        $options = self::get();
        echo isset($options['theme']['footer_text']) ? $options['theme']['footer_text'] : '';
    }

    public static function get_login_logo_url() {
        $options = self::get();
        $logo_url = isset($options['login_page']['logo']) ? $options['login_page']['logo'] : '';
        if(empty($logo_url) && defined('SB_THEME_VERSION')) {
            $logo_url = isset($options['theme']['logo']) ? $options['theme']['logo'] : '';
        }
        return $logo_url;
    }

    public static function get_theme_thumbnail_url() {
        $options = self::get();
        $url = isset($options['theme']['thumbnail']) ? $options['theme']['thumbnail'] : '';
        if(empty($url)) {
            $url = isset($options['post_widget']['no_thumbnail']) ? $options['post_widget']['no_thumbnail'] : '';
        }
        if(empty($url)) {
            $url = SB_Post::get_default_thumbnail_url();
        }
        return $url;
    }

    public static function get_widget_thumbnail_url() {
        $options = self::get();
        $url = isset($options['post_widget']['no_thumbnail']) ? $options['post_widget']['no_thumbnail'] : '';
        if(empty($url)) {
            $url = isset($options['theme']['thumbnail']) ? $options['theme']['thumbnail'] : '';
        }
        if(empty($url)) {
            $url = SB_Post::get_default_thumbnail_url();
        }
        return $url;
    }

    public static function get_by_key($args = array()) {
        $default = isset($args['default']) ? $args['default'] : '';
        $options = self::get();
        $keys = isset($args['keys']) ? $args['keys'] : array();
        $value = $default;
        $tmp = $options;
        if(!is_array($keys)) {
            return $value;
        }
        foreach($keys as $key) {
            $tmp = isset($tmp[$key]) ? $tmp[$key] : '';
            if(empty($tmp)) {
                break;
            }
        }
        if(!empty($tmp)) {
            $value = $tmp;
        }
        return $value;
    }

    public static function get_theme_social($social_key) {
        return self::get_by_key(array('keys' => array('theme', 'social', $social_key)));
    }

    public static function get_theme_option($args = array()) {
        if(isset($args['keys']) && is_array($args['keys'])) {
            array_unshift($args['keys'], 'theme');
        }
        return self::get_by_key($args);
    }

    public static function get_theme_footer_text() {
        $args = array(
            'keys' => array('footer_text')
        );
        return self::get_theme_option($args);
    }

    public static function get_scroll_top() {
        $result = self::get_theme_option(array('keys' => array('scroll_top')));
        return (bool)$result;
    }

    public static function get_bool_value_by_key($args = array()) {
        $value = self::get_by_key($args);
        return (bool)$value;
    }

    public static function get_int_value_by_key($args = array()) {
        $value = self::get_by_key($args);
        return intval($value);
    }

    public static function get_home_url() {
        return get_option('home');
    }

    public static function get_site_url() {
        return get_option('siteurl');
    }

    public static function change_option_array_url(&$options, $args = array()) {
        $site_url = '';
        $url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($site_url) || empty($url) || $url == $site_url) {
            return;
        }
        foreach($options as $key => &$value) {
            if(is_array($value)) {
                self::change_option_array_url($value, $args);
            } elseif(!empty($value) && !is_numeric($value)) {
                $value = str_replace($url, $site_url, $value);
            }
        }
    }

    public static function change_option_url($args = array()) {
        $site_url = '';
        $url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($site_url) || empty($url) || $url == $site_url) {
            return;
        }
        $options = self::get();
        self::change_option_array_url($options, $args);
        self::update($options);
    }

    public static function change_widget_text_url($args = array()) {
        $site_url = '';
        $url = '';
        extract($args, EXTR_OVERWRITE);
        if(empty($site_url) || empty($url) || $url == $site_url) {
            return;
        }
        $text_widgets = get_option('widget_text');
        foreach($text_widgets as $key => $widget) {
            if(isset($widget['text'])) {
                $text_widgets[$key]['text'] = str_replace($url, $site_url, $widget['text']);
            }
        }
        update_option('widget_text', $text_widgets);
    }

    public static function update_option($option_name, $option_value) {
        update_option($option_name, $option_value);
    }
}