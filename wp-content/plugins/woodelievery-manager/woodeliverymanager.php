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

//Display chkbox
function display_chkbox()
{
?>
    <input type="checkbox" name="weekend-chk" id="chkbox" value="1 <?php echo get_option('weekend-chk'); ?>" <?php if (get_option('weekend-chk')) { ?>checked="checked" <?php } ?> />
<?php
}

//To display HTML code & automatic saving the value of fields
function display_theme_panel_fields()
{
    add_settings_section("section", "All Settings", null, "theme-options");

    add_settings_field("checkbox", "Woo-delivery-managment", "display_checkbox", "theme-options", "section");
    register_setting("section", "checkbox");

    add_settings_field("weekend-chk", "For Weekends", "display_chkbox", "theme-options", "section");
    register_setting("section", "weekend-chk");
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
            //'value' =>  '',
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
        WC()->session->__unset('enable_fee');
    ?>
        <script>
            //$("#DeliveryDatePicker").hide();
            $(document).ready(function() {
                var week = '<?php echo get_option('weekend-chk') ?>';
                if (week) {
                    $("#my_date_picker").datepicker({
                            minDate: new Date(),
                            dateFormat: 'dd/mm/yy',
                            beforeShowDay: $.datepicker.noWeekends
                        })
                        .on('change', function() { // This check is for dd/mm/yyyy format but can be easily adapted to any other
                            if (this.value.match(/\d{1,2}[^\d]\d{1,2}[^\d]\d{4,4}/gi) == null)
                                alert('Invalid date format');
                            else {
                                var t = this.value.split(/[^\d]/);
                                var dd = parseInt(t[0], 10);
                                var m0 = parseInt(t[1], 10) - 1; // Month in JavaScript Date object is 0-based
                                var yyyy = parseInt(t[2], 10);
                                var d = new Date(yyyy, m0, dd); // new Date(2017, 13, 32) is still valid
                                if (d.getDate() != dd || d.getMonth() != m0 || d.getFullYear() != yyyy)
                                    alert('Invalid date value');
                            }
                        });
                } else {
                    $("#my_date_picker").datepicker({
                            minDate: new Date(),
                            dateFormat: 'dd/mm/yy'
                        })
                        .on('change', function() { // This check is for dd/mm/yyyy format but can be easily adapted to any other
                            if (this.value.match(/\d{1,2}[^\d]\d{1,2}[^\d]\d{4,4}/gi) == null)
                                alert('Invalid date format');
                            else {
                                var t = this.value.split(/[^\d]/);
                                var dd = parseInt(t[0], 10);
                                var m0 = parseInt(t[1], 10) - 1; // Month in JavaScript Date object is 0-based
                                var yyyy = parseInt(t[2], 10);
                                var d = new Date(yyyy, m0, dd); // new Date(2017, 13, 32) is still valid
                                if (d.getDate() != dd || d.getMonth() != m0 || d.getFullYear() != yyyy)
                                    alert('Invalid date value');
                            }
                        });
                }
            });

            jQuery(function($) {
                //$('#checkboxId').val('');
                $("#checkboxId").removeAttr("checked");
                $("#my_date_picker").val('');
                $("#my_date_picker").hide();
                $('label[for="my_date_picker"]').hide();
                $('input[type=checkbox]').change(function() {
                    var fee = $(this).prop('checked') === true ? '1' : '';
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

                    $.ajax({
                        type: 'POST',
                        url: wc_checkout_params.ajax_url,
                        data: {
                            'action': 'enable_fee',
                            'enable_fee': fee,
                        },
                        success: function(result) {
                            $('body').trigger('update_checkout');
                        },
                    });
                });
            });
        </script>
<?php
    else :
        WC()->session->__unset('enable_fee');
    endif;
}

// Get Ajax request and saving to WC session
add_action('wp_ajax_enable_fee', 'get_enable_fee');
add_action('wp_ajax_nopriv_enable_fee', 'get_enable_fee');
function get_enable_fee()
{
    if (isset($_POST['enable_fee'])) {
        WC()->session->set('enable_fee', ($_POST['enable_fee'] ? true : false));
    }
    die();
}

// Add a custom dynamic $10 fee on checkout page


add_action('woocommerce_cart_calculate_fees', 'custom_percetage_fee', 20, 1);
function custom_percetage_fee($cart)
{
    // Only on checkout
    if ((is_admin() && !defined('DOING_AJAX')) || !is_checkout())
        return;

    $percent = 10;

    if (WC()->session->get('enable_fee'))
        $cart->add_fee(__('Extra fee', 'woocommerce') . " ($percent)", ($percent));
}
