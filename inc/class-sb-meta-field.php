<?php
class SB_Meta_Field {
    public static function text($args = array()) {
        $name = isset($args['name']) ? trim($args['name']) : '';
        if(empty($name)) {
            return;
        }
        $name = sb_build_meta_name($name);
        $value = isset($args['value']) ? trim($args['value']) : '';
        $field_class = isset($args['field_class']) ? trim($args['field_class']) : '';
        $label = isset($args['label']) ? $args['label'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr($name); ?>"><?php echo $label; ?>:</label>
            <input type="text" id="<?php echo esc_attr($name); ?>" name="<?php echo esc_attr($name); ?>" value="<?php echo $value; ?>" class="<?php echo $field_class; ?>">
        </p>
        <?php
    }

    public static function image_upload($args = array()) {
        $name = isset($args['name']) ? trim($args['name']) : '';
        if(empty($name)) {
            return;
        }
        $name = sb_build_meta_name($name);
        $container_class = isset($args['container_class']) ? trim($args['container_class']) : '';
        $container_class = SB_PHP::add_string_with_space_before($container_class, 'sb-media-upload');
        $label = isset($args['label']) ? $args['label'] : ''; ?>
        <p class="<?php echo $container_class; ?>">
            <label for="<?php echo esc_attr($name); ?>" class="display-block"><?php echo $label; ?>:</label>
            <?php SB_Field::media_upload_group($args); ?>
        </p>
        <?php
    }

    public static function editor($args = array()) {
        $name = isset($args['name']) ? $args['name'] : '';
        if(empty($name)) {
            return;
        }
        $value = isset($args['value']) ? $args['value'] : '';
        $id = isset($args['id']) ? $args['id'] : '';
        if(empty($id)) {
            $id = $name;
        }
        $label = isset($args['label']) ? $args['label'] : '';
        echo '<label for="' . $label . '">' . $label . ':</label>';
        wp_editor($value, $id, $args);
    }
}