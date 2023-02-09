<?php
require_once('../../../../wp-admin/includes/image.php');
require_once('../../../../wp-admin/includes/media.php');
require_once("../../../../wp-load.php");
require_once('../../../../wp-includes/wp-db.php');

$image = $_POST['image'];

list($type, $image) = explode(';', $image);
list(, $image) = explode(',', $image);

$image = base64_decode($image);
$image_name = time() . '.png';
//$image_url = 'adress img';

$upload_dir = wp_upload_dir();

//$image_data = file_get_contents($image_url);

$filename = $image_name;
$file = $upload_dir['basedir'] . '/' . $image_name;
file_put_contents($file, $image);
$wp_filetype = wp_check_filetype($filename, null);

$attachment = array(
    'post_mime_type' => $wp_filetype['type'],
    'post_title' => sanitize_file_name($filename),
    'post_content' => '',
    'post_status' => 'publish'
);

$attach_id = wp_insert_attachment($attachment, $file);
//require_once(ABSPATH . 'wp-admin/includes/image.php');
$attach_data = wp_generate_attachment_metadata($attach_id, $file);
wp_update_attachment_metadata($attach_id, $attach_data);

//file_put_contents('uploads/'.$image_name, $image);

echo 'successfully uploaded';
