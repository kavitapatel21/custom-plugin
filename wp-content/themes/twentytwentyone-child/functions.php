<?php
/* Child theme generated with WPS Child Theme Generator */

if (!function_exists('b7ectg_theme_enqueue_styles')) {
    add_action('wp_enqueue_scripts', 'b7ectg_theme_enqueue_styles');

    function b7ectg_theme_enqueue_styles()
    {
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style'));
    }
}

//cron file
/**add_filter('cron_schedules', 'example_add_cron_interval');

function example_add_cron_interval($schedules)
{
    $schedules['every_one_minute'] = array(
        'interval' => 60, //[interval] => 86400 once a daily
        'display' => esc_html__('Every one minute'),
    );

    return $schedules;
}


//Schedule the event
add_action('wp', 'launch_the_action');
function launch_the_action()
{
    if (!wp_next_scheduled("custom__cron")) {
        wp_schedule_event(time(), "every_one_minute", "custom__cron");
    }
}**/

function register_shipment_arrival_order_status()
{
    register_post_status('wc-arrival-shipment', array(
        'label'                     => 'Shipment Arrival',
        'public'                    => true,
        'show_in_admin_status_list' => true,
        'show_in_admin_all_list'    => true,
        'exclude_from_search'       => false,
        'label_count'               => _n_noop('Shipment Arrival <span class="count">(%s)</span>', 'Shipment Arrival <span class="count">(%s)</span>')
    ));
}
add_action('init', 'register_shipment_arrival_order_status');
function add_awaiting_shipment_to_order_statuses($order_statuses)
{
    $new_order_statuses = array();
    foreach ($order_statuses as $key => $status) {
        $new_order_statuses[$key] = $status;
        if ('wc-processing' === $key) {
            $new_order_statuses['wc-arrival-shipment'] = 'Shipment Arrival';
        }
    }
    return $new_order_statuses;
}
add_filter('wc_order_statuses', 'add_awaiting_shipment_to_order_statuses');

//action for cron event
add_action('custom__cron', 'create_post');
function create_post()
{
    require_once(ABSPATH . 'test-cron.php');
}

/**Woocommerce order tracking code start */

function bbloomer_add_order_tracking_endpoint()
{
    add_rewrite_endpoint('order-tracking', EP_ROOT | EP_PAGES);
}

add_action('init', 'bbloomer_add_order_tracking_endpoint');

// ------------------
// 2. Add new query var

function bbloomer_order_tracking_query_vars($vars)
{
    $vars[] = 'order-tracking';
    return $vars;
}

// ------------------
// 3. Insert the new endpoint into the My Account menu

function bbloomer_add_order_tracking_link_my_account($items)
{
    $items['order-tracking'] = 'Order tracking';
    return $items;
}

add_filter('woocommerce_account_menu_items', 'bbloomer_add_order_tracking_link_my_account');

// ------------------
// 4. Add content to the new tab

function bbloomer_order_tracking_content()
{


    defined('ABSPATH') || exit;

    global $woocommerce, $user_id;

    if (!class_exists('WooCommerce') || !get_current_user_id()) {
        return;
    };

    $user_id = get_current_user_id();

    //$customer = wp_get_current_user();
    $posts_per_page = 20;
    // Get all customer orders
    $customer__all_orders = get_posts(apply_filters('woocommerce_my_account_my_orders_query', array(
        'numberposts' => -1,
        'meta_key' => '_customer_user',
        'orderby' => 'date',
        'order' => 'DESC',
        'meta_value' => $user_id,
        'post_type' => wc_get_order_types(),
        'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing', 'wc-completed', 'wc-on-hold', 'wc-arrival-shipment'),
    )));
    $paged = isset($_REQUEST['order_page']) ? $_REQUEST['order_page'] : 1;
    $total_records = count($customer__all_orders);
    $total_pages = ceil($total_records / $posts_per_page);


    $customer_orders = get_posts(array(
        'meta_key' => '_customer_user',
        'order' => 'DESC',
        'meta_value' => $user_id,
        'post_type' => wc_get_order_types(),
        'posts_per_page' => $posts_per_page,
        'paged' => $paged,
        'post_status' => array_keys(wc_get_order_statuses()), 'post_status' => array('wc-processing', 'wc-completed', 'wc-on-hold', 'wc-arrival-shipment'),
    ));
?>


    <?php if (!empty($customer_orders)) : ?>

        <table class="woocommerce-orders-table woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
            <thead>
                <tr>
                    <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                        <th class="woocommerce-orders-table__header woocommerce-orders-table__header-<?php echo esc_attr($column_id); ?>"><span class="nobr"><?php echo esc_html($column_name); ?></span></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ($customer_orders as $customer_order) {
                    $order      = wc_get_order($customer_order);
                    $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                ?>
                    <tr class="woocommerce-orders-table__row woocommerce-orders-table__row--status-<?php echo esc_attr($order->get_status()); ?> order">
                        <?php foreach (wc_get_account_orders_columns() as $column_id => $column_name) : ?>
                            <td class="woocommerce-orders-table__cell woocommerce-orders-table__cell-<?php echo esc_attr($column_id); ?>" data-title="<?php echo esc_attr($column_name); ?>">
                                <?php if (has_action('woocommerce_my_account_my_orders_column_' . $column_id)) : ?>
                                    <?php do_action('woocommerce_my_account_my_orders_column_' . $column_id, $order); ?>

                                <?php elseif ('order-number' === $column_id) : ?>
                                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>">
                                        <?php echo esc_html(_x('#', 'hash before order number', 'woocommerce') . $order->get_order_number()); ?>
                                    </a>

                                <?php elseif ('order-date' === $column_id) : ?>
                                    <time datetime="<?php echo esc_attr($order->get_date_created()->date('c')); ?>"><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></time>

                                <?php elseif ('order-status' === $column_id) : ?>
                                    <?php echo esc_html(wc_get_order_status_name($order->get_status())); ?>

                                <?php elseif ('order-total' === $column_id) : ?>
                                    <?php
                                    /* translators: 1: formatted order total 2: total order items */
                                    echo wp_kses_post(sprintf(_n('%1$s for %2$s item', '%1$s for %2$s items', $item_count, 'woocommerce'), $order->get_formatted_order_total(), $item_count));
                                    ?>

                                <?php elseif ('order-actions' === $column_id) : ?>
                                    <?php
                                    $actions = wc_get_account_orders_actions($order);

                                    if (!empty($actions)) {
                                        foreach ($actions as $key => $action) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
                                            echo '<a href="' . esc_url($action['url']) . '" class="woocommerce-button button ' . sanitize_html_class($key) . '">' . esc_html($action['name']) . '</a>';
                                        }
                                    }
                                    ?>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
        <div class="pagination">
            <?php
            $args = array(
                'base' => '%_%',
                'format' => '?order_page=%#%',
                'total' => $total_pages,
                'current' => $paged,
                'show_all' => False,
                'end_size' => 5,
                'mid_size' => 5,
                'prev_next' => True,
                'prev_text' => __('&laquo; Previous'),
                'next_text' => __('Next &raquo;'),
                'type' => 'plain',
                'add_args' => False,
                'add_fragment' => ''
            );
            echo paginate_links($args);
            ?>
        </div>
    <?php else : ?>
        <div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info">
            <a class="woocommerce-Button button" href="<?php echo esc_url(apply_filters('woocommerce_return_to_shop_redirect', wc_get_page_permalink('shop'))); ?>"><?php esc_html_e('Browse products', 'woocommerce'); ?></a>
            <?php esc_html_e('No order has been made yet.', 'woocommerce'); ?>
        </div>
    <?php endif; ?>
<?php
}

add_action('woocommerce_account_order-tracking_endpoint', 'bbloomer_order_tracking_content');


add_action('woocommerce_order_details_before_order_table_items', 'misha_after_customer');

function misha_after_customer($order)
{
    //echo "<pre>";
    //print_r($order->status);
?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <style>
        .hh-grayBox {
            background-color: #F8F8F8;
            margin-bottom: 20px;
            padding: 35px;
            margin-top: 20px;
        }

        .pt45 {
            padding-top: 45px;
        }

        .order-tracking {
            text-align: center;
            width: 33.33%;
            position: relative;
            display: block;
        }

        .order-tracking .is-complete {
            display: block;
            position: relative;
            border-radius: 50%;
            height: 30px;
            width: 30px;
            border: 0px solid #AFAFAF;
            background-color: #f7be16;
            margin: 0 auto;
            transition: background 0.25s linear;
            -webkit-transition: background 0.25s linear;
            z-index: 2;
        }

        .order-tracking .is-complete:after {
            display: block;
            position: absolute;
            content: '';
            height: 14px;
            width: 7px;
            top: -2px;
            bottom: 0;
            left: 5px;
            margin: auto 0;
            border: 0px solid #AFAFAF;
            border-width: 0px 2px 2px 0;
            transform: rotate(45deg);
            opacity: 0;
        }

        .order-tracking.completed .is-complete {
            border-color: #27aa80;
            border-width: 0px;
            background-color: #27aa80;
        }

        .order-tracking.completed .is-complete:after {
            border-color: #fff;
            border-width: 0px 3px 3px 0;
            width: 7px;
            left: 11px;
            opacity: 1;
        }

        .order-tracking p {
            color: #A4A4A4;
            font-size: 16px;
            margin-top: 8px;
            margin-bottom: 0;
            line-height: 20px;
        }

        .order-tracking p span {
            font-size: 14px;
        }

        .order-tracking.completed p {
            color: #000;
        }

        .order-tracking::before {
            content: '';
            display: block;
            height: 3px;
            width: calc(100% - 40px);
            background-color: #f7be16;
            top: 13px;
            position: absolute;
            left: calc(-50% + 20px);
            z-index: 0;
        }

        .order-tracking:first-child:before {
            display: none;
        }

        /** .order-tracking.completed:before {
            background-color: #27aa80;
        }*/
    </style>
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-10 hh-grayBox pt45 pb20">
                <div class="row justify-content-between">
                    <div class="order-tracking completed ordered">
                        <span class="ordered is-complete"></span>
                        <p>Ordered</p>
                    </div>
                    <div class="order-tracking completed shipped">
                        <span class="shipped is-complete"></span>
                        <p>Shipped</p>
                    </div>
                    <div class="order-tracking completed delivered">
                        <span class="delivered is-complete"></span>
                        <p>Delivered</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <script>
        //alert("hello");
        $(document).ready(function() {
            var order_status = '<?php echo $order->status ?>';
            if (order_status == 'processing' || order_status == 'on-hold') {
                $(".order-tracking.completed.ordered .is-complete").css("background-color", "#f7be16");
            }
            if (order_status == 'arrival-shipment') {
                $(".order-tracking.completed.ordered .is-complete").css("background-color", "#f7be16");
                $(".order-tracking.completed.shipped .is-complete").css("background-color", "#f7be16");
                $(".order-tracking.completed.shipped:before .is-complete").css("background-color", "#f7be16");
            }
            if (order_status == 'completed') {
                $(".order-tracking.completed.ordered .is-complete").css("background-color", "#f7be16");
                $(".order-tracking.completed.shipped .is-complete").css("background-color", "#f7be16");
                $(".order-tracking.completed.completed .is-complete").css("background-color", "#f7be16");
            }
        });
    </script>
<?php
}
