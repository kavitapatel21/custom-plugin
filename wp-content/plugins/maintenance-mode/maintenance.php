<?php

/**
 * Maintenance Mode
 *
 * Plugin Name: Maintenance Mode
 * Description: Easy to set Maintenance site mode. 
 * Text Domain: maintenance-mode
 */

defined('ABSPATH') || exit;

add_action('admin_menu', 'maintenance_admin_page');
function maintenance_admin_page()
{
    add_menu_page('maintenance Settings', 'Maintenance Settings', 'administrator', 'maintenance-settings', 'maintenance_admin_page_callback');
}

/*
 * Register the settings
 */
add_action('admin_init', 'maintenance_register_settings');
function maintenance_register_settings()
{
    //this will save the option in the wp_options table as 'maintenance_settings'
    //the third parameter is a function that will validate your input values
    register_setting('maintenance_settings', 'maintenance_settings', 'maintenance_settings_validate');
}

//Display the validation errors and update messages
/*
 * Admin notices
 */
/**add_action('admin_notices', 'maintenance_admin_notices');
function maintenance_admin_notices()
{
    //settings_errors();
}*/

//The markup for your plugin settings page
function maintenance_admin_page_callback()
{ ?>
    <div class="wrap">
        <?php settings_errors(); ?>
        <h2>maintenance Settings</h2>
        <form action="options.php" method="post"><?php
                                                    settings_fields('maintenance_settings');
                                                    do_settings_sections(__FILE__);

                                                    //get the older values, wont work the first time
                                                    $options = get_option('maintenance_settings');

                                                    wp_enqueue_style('wp-color-picker');
                                                    wp_enqueue_script('wp-color-picker');

                                                    ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Enable/Disable</th>
                    <td>
                        <fieldset>
                            <label>
                                <input name="maintenance_enable" type="checkbox" id="maintenance_enable" value="1 <?php echo get_option('maintenance_enable'); ?>" <?php if (get_option('maintenance_enable')) { ?>checked="checked" <?php } ?> />
                                <span class="description">Enable Maintenance Site .</span>
                            </label>
                        </fieldset>
                    </td>
                </tr>
            </table>
            <input type="submit" value="Save" />
        </form>
    </div>
<?php }
?>