<?php

/**
 * Template Name: edit-img
 * Template Post Type:post,page,my-post-type;
 */
?>
<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri(); ?>/template/assets/jquery.Jcrop.min.css" type="text/css" />
<div class="bgColor">
    <form id="uploadForm" action="" method="post" enctype="multipart/form-data">

        <div id="uploadFormLayer">
            <input name="userImage" id="userImage" type="file" class="inputFile"><br> <input type="submit" name="upload" value="Submit" class="btnSubmit">
            <a href="#" class="select-image">Upload</a>
        </div>
    </form>
</div>
<div>
    <img src="<?php echo $imagePath; ?>" id="cropbox" class="img" /><br />
</div>
<div id="btn">
    <input type='button' id="crop" value='CROP'>
</div>
<div>
    <img src="#" id="cropped_img" style="display: none;">
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/template/assets/jquery.min.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/template/assets/jquery.Jcrop.min.js"></script>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".select-image").click(function() {
            var custom_uploader = wp.media({
                title: 'Selecteer een afbeelding',
                button: {
                    text: 'Selecteer'
                },
                multiple: false
            });
            custom_uploader.on('select', function() {
                custom_uploader.Jcrop();
            });
            custom_uploader.open();
        });
    });

    $(document).ready(function() {
        var size;
        $('#cropbox').Jcrop({
            aspectRatio: 1,
            onSelect: function(c) {
                size = {
                    x: c.x,
                    y: c.y,
                    w: c.w,
                    h: c.h
                };
                $("#crop").css("visibility", "visible");
            }
        });

        $("#crop").click(function() {
            var img = $("#cropbox").attr('src');
            $("#cropped_img").show();
            $("#cropped_img").attr('src', 'image-crop.php?x=' + size.x + '&y=' + size.y + '&w=' + size.w + '&h=' + size.h + '&img=' + img);
        });
    });
</script>
<?php
if (!empty($_POST["upload"])) {
    if (is_uploaded_file($_FILES['userImage']['tmp_name'])) {
        //echo "<pre>";
        //print_r($_FILES);
        //die;
        $targetPath = "../uploads/" . $_FILES['userImage']['name'];
        if (move_uploaded_file($_FILES['userImage']['tmp_name'], $targetPath)) {
            $uploadedImagePath = $targetPath;
        }
    }
}
?>