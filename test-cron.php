<?php 
//require_once('wp-admin/includes/image.php');
//require_once('wp-admin/includes/file.php');
require_once('wp-admin/includes/media.php');
require_once("wp-load.php");
require_once('wp-includes/wp-db.php');
//echo "test cron run";
$post_data = array(
    'post_type'         => 'post',
    'post_title'        => 'one',
    'post_status'       => 'publish',
    //'category_name' => 'first',
);
$post_id = wp_insert_post($post_data);
