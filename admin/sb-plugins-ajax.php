<?php
$lists = new SB_List_Plugin();
$plugins = $lists->get();
?>
<?php foreach($plugins as $plugin) : ?>
    <?php $info = $plugin->get_information(); ?>
    <?php if(is_wp_error($info)) continue; ?>
    <div class="plugin-card">
        <div class="plugin-card-top">
            <div class="name column-name">
                <h4><a class="thickbox" href="<?php printf(admin_url(sprintf('plugin-install.php?tab=search&type=tag&s=%1$s', $info->slug))); ?>"><?php echo $info->name; ?></a></h4>
            </div>
            <div class="desc column-description">
                <p><?php echo $info->short_description; ?></p>
            </div>
            <div class="row-button">
                <?php $plugin->the_installation_button(); ?>
            </div>
        </div>
        <div class="plugin-card-bottom">
            <div class="row-downloaded">
                <strong><?php _e('Downloads:', 'sb-core'); ?></strong> <span title="<?php echo $info->downloaded; ?>"><?php echo number_format($info->downloaded, 0, '.', ','); ?></span>
            </div>
            <div class="row-updated">
                <strong><?php _e('Last Updated:', 'sb-core'); ?></strong> <span title="<?php echo $info->last_updated; ?>"><?php echo date_format(new DateTime($info->last_updated), 'Y-m-d'); ?></span>
            </div>
        </div>
    </div>
<?php endforeach; ?>