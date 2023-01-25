<?php
/*
Plugin Name: Woo-delievery-manager
Description: This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong: Hello, Dolly. When activated you will randomly see a lyric from <cite>Hello, Dolly</cite> in the upper right of your admin screen on every page.
Author: ABC
Version: 1.0
*/
?>

<?php

//Create theme option menu on admin-panel
function add_theme_menu_item()
{
    add_menu_page("Theme Option", "Theme Option", "manage_options", "theme-option", "theme_settings_page", null, 99);
}
add_action("admin_menu", "add_theme_menu_item");

//Create section & submit button on setting page
function theme_settings_page()
{
?>
    <div class="wrap">
        <h1>Theme Option</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields("section");
            do_settings_sections("theme-options");
            submit_button();
            ?>
        </form>
    </div>
<?php
}

//Display chkbox
function display_checkbox()
{
?>
    <input type="checkbox" name="checkbox" id="checkbox" value="1 <?php echo get_option('checkbox'); ?>" <?php if (get_option('checkbox')) { ?>checked="checked" <?php } ?> />
<?php
}

//To display HTML code & automatic saving the value of fields
function display_theme_panel_fields()
{
    add_settings_section("section", "All Settings", null, "theme-options");

    add_settings_field("checkbox", "Woo-delivery-managment", "display_checkbox", "theme-options", "section");
    register_setting("section", "checkbox");
}
add_action("admin_init", "display_theme_panel_fields");

if (get_option('checkbox')) {
function reigel_woocommerce_checkout_fields($checkout_fields = array())
{
    
    $checkout_fields['order']['my_field_name'] = array(
        'id' => 'checkboxId',
        'type'      => 'checkbox',
        'class'     => array('input-checkbox'),
        'value' =>  'custom-chkbox',
        'label'     => __('My custom checkbox'),
        //'required'      => true,
    );

    $checkout_fields['order']['date_picker'] = array(
        'id'            => 'my_date_picker',
        'type'      => 'text',
        'class'     => 'input-date-picker',
        'value' =>  'date-picker',
        'label'     => __('Date'),
        'required'      => true,
    );
    return $checkout_fields;
    
}
add_filter('woocommerce_checkout_fields', 'reigel_woocommerce_checkout_fields');
}

add_action('woocommerce_checkout_update_order_meta', 'save_custom_checkout_hidden_field');
function save_custom_checkout_hidden_field($order_id)
{
    $value = isset($_POST['my_field_name']) ? '1' : '0';
    update_post_meta($order_id, 'my_field_name', sanitize_text_field($value));

    if (!empty($_POST['date_picker'])) {
        update_post_meta($order_id, 'date_picker', sanitize_text_field($_POST['date_picker']));
    }
}

add_action('woocommerce_thankyou', 'show_data_on_thankyou_page', 20);

//Display data on thankyou page
function show_data_on_thankyou_page($order_id)
{ 
    ?>
    <section class="custom-billing-details">
        <p class="woocommerce-customer-details--nif"><span>Delivery Date: </span><?php echo get_post_meta($order_id, 'date_picker', true); ?></p>
    </section>
<?PHP
}

// Register main datetimepicker jQuery plugin script
add_action('wp_enqueue_scripts', 'enabling_date_time_picker');
function enabling_date_time_picker()
{
    // Only on front-end and checkout page
    if (is_admin() || !is_checkout()) return;
    // Load the datetimepicker jQuery-ui plugin script
?>
    <link href='https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/ui-lightness/jquery-ui.css' rel='stylesheet'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <?php
}

// Call datetimepicker functionality in your custom text field
//add_action('woocommerce_before_order_notes', 'my_custom_checkout_field', 10, 1);

// The jQuery script
add_action('wp_footer', 'checkout_delivery_jquery_script');
function checkout_delivery_jquery_script()
{
    // Only on front-end and checkout page
    if (is_checkout() && !is_wc_endpoint_url()) :
    ?>
        <script>
            //$("#DeliveryDatePicker").hide();
            $(document).ready(function() {
                $(function() {
                    $("#my_date_picker").datepicker();
                });
            })
            jQuery(function($) {
                $('#checkboxId').val('');
                $("#checkboxId").removeAttr("checked");
                $("#my_date_picker").val('');
                $("#my_date_picker").hide();
                $('label[for="my_date_picker"]').hide();
                $('input[type=checkbox]').change(function() {
                    //alert('changed');
                    if ($('#checkboxId').is(':checked')) {
                        $('#checkboxId').val('1');
                        $('label[for="my_date_picker"]').show();
                        $("#my_date_picker").show();
                    } else {
                        $('#checkboxId').val('');
                        $('label[for="my_date_picker"]').hide();
                        $("#my_date_picker").hide();
                    }
                });
            });
        </script>
<?php
    endif;
}