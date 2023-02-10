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
                                                    //wp_enqueue_style('wp-color-picker');
                                                    //wp_enqueue_script('wp-color-picker');
                                                    ?>
            <table class="form-table">
                <tr>
                    <th scope="row">Enable/Disable</th>
                    <td>
                        <fieldset>
                            <label>
                                <input name="maintenance_settings[maintenance_enable]" type="checkbox" id="maintenance_enable" value="1" <?php echo (isset($options['maintenance_enable']) && $options['maintenance_enable'] == '1') ? 'checked' : ''; ?> />
                                <span class="description">Enable Maintenance Site .</span>
                            </label>
                        </fieldset>
                    </td>
                </tr>
                <tr>
                    <th scope="row">Title</th>
                    <td>
                        <fieldset>
                            <label>
                                <input name="maintenance_settings[custom_title]" type="text" id="custom_title" value="<?php echo (isset($options['custom_title']) && $options['custom_title'] != '') ? $options['custom_title'] : ''; ?>" />
                            </label>

                        </fieldset>
                    </td>
                </tr>

                <tr>
                    <th>Description</th>
                    <td>
                        <?php
                        $options = get_option('maintenance_settings', array());
                        //echo "<pre>";
                        //print_r($options);
                        $content = isset($options['cutom_txt_editor']) ?  $options['cutom_txt_editor'] : false;
                        wp_editor($content, 'cutom_txt_editor', array(
                            'textarea_name' => 'maintenance_settings[cutom_txt_editor]',
                            'media_buttons' => true,
                            'textarea_rows' => 5,
                        ));
                        ?>
                    </td>
                </tr>

                <tr>
                    <th scope="row">Background Image</th>
                    <?php
                    if (function_exists('wp_enqueue_media')) {
                        wp_enqueue_media();
                    } else {
                        wp_enqueue_style('thickbox');
                        wp_enqueue_script('media-upload');
                        wp_enqueue_script('thickbox');
                    }
                    ?>
                    <td>
                        <fieldset>
                            <label>
                                <input type="text" name="maintenance_settings[image_url]" id="image_url" class="regular-text" value="<?php echo (isset($options['image_url']) && $options['image_url'] != '') ? $options['image_url'] : ''; ?>">
                                <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
                                <span class="description"></span>
                            </label>
                        </fieldset>
                        <script>
                            jQuery(document).ready(function($) {
                                $('#upload-btn').click(function(e) {
                                    e.preventDefault();
                                    var custom_uploader = wp.media({
                                            title: 'Custom Image',
                                            button: {
                                                text: 'Upload Image'
                                            },
                                            multiple: false // Set this to true to allow multiple files to be selected
                                        })
                                        .on('select', function() {
                                            var attachment = custom_uploader.state().get('selection').first().toJSON();
                                            //$('.favicon').attr('src', attachment.url);
                                            $('#image_url').val(attachment.url);
                                        })
                                        .open();
                                });
                            });
                        </script>
                    </td>
                </tr>
            </table>
            <input type="submit" value="Save" />
        </form>
    </div>
    <?php }

//Data display on frontend
function data_display()
{
    $options = get_option('maintenance_settings');
    $title = $options['custom_title'];
    $desc = $options['cutom_txt_editor'];
    $bg_img = $options['image_url'];
    if (isset($options['maintenance_enable']) && $options['maintenance_enable'] == '1') {
    ?>
        <!DOCTYPE html>
        <html>

        <head>
            <meta charset="UTF-8">
            <title>Maintenance Mode</title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
            <?php
            if (!empty($bg_img)) {
                $background =  (!empty($bg_img) ? $bg_img : '');
            } else {
                $background = '#ffffff';
            }
            ?>
        </head>

        <body class="background">
            <div class="wrap">
                <?php
                if (!empty($title)) {
                ?>
                    <h1 class="heading"><?php echo $title; ?></h1>
                <?php
                }
                ?>
                <?php
                if (!empty($desc)) {
                ?>
                    <p><?php echo $desc; ?></p>
                <?php
                }
                ?>
            </div>
            <style type="text/css">
                .wrap {
                    position: absolute;
                    top: 30%;
                    width: 90%;
                    text-align: center;
                    font-size: 30px;
                    color: black;
                    font-weight: 700;
                }

                .background {
                    background-image: url("<?php echo $background ?>");
                    background-position: center center;
                    background-size: cover;
                    background-repeat: no-repeat;
                    background-attachment: fixed;
                }
            </style>
        </body>

        </html>
<?php
        exit();
    }
}
add_action('get_header', 'data_display');
?>