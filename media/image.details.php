<?php

if(!isset($_GET['key']))
{

    message_set('Image Error', 'There was an error loading the specified image.', 'red');
    header_redirect('/image/list');
 
}

$query = 'SELECT google_id,
    content_type
    FROM media
    WHERE id = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(!mysqli_num_rows($result))
{

    message_set('Image Error', 'There was an error loading the specified image.', 'red');
    header_redirect(ENV_DOMAIN.'/image/list');

}

$media = mysqli_fetch_assoc($result);

if(!$media['content_type'])
{

    $headers = google_file_headers($media['google_id']);
    
    $query = 'UPDATE media SET
        content_type = "'.$headers['content_type'].'",
        content_length = "'.$headers['content_length'].'",
        content_width = "'.$headers['content_width'].'",
        content_height = "'.$headers['content_height'].'"
        WHERE id = "'.$_GET['key'].'"
        LIMIT 1';
    mysqli_query($connect, $query);

    header_redirect(ENV_DOMAIN.'/image/details/'.$_GET['key']);

}

define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Image Details');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT media.*,
    COUNT(downloads.id) AS downloads_count,
    MAX(downloads.created_at) AS downloaded_at
    FROM media
    LEFT JOIN downloads
    ON downloads.media_id = media.id
    WHERE media.id = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);
$media = mysqli_fetch_assoc($result);

$query = 'SELECT *
    FROM tags
    JOIN media_tag ON tags.id = media_tag.tag_id
    WHERE media_tag.media_id = "'.$_GET['key'].'"
    ORDER BY name';
$result = mysqli_query($connect, $query);

$tags = array();
while($record = mysqli_fetch_assoc($result))
{
    $tags[] = $record;
}

?>

<img src="https://lh3.googleusercontent.com/d/<?=$media['google_id']?>=w800-h800" class="w3-image w3-margin-top" style="width: 100%;">

<div class="w3-green w3-container">
    <h2 class="w3-large">
        <i class="fa-solid fa-camera fa-padding-right"></i>
        <?=$media['name']?>
    </h2>
</div>

<p>
    Uploaded: <span class="w3-bold"><?=time_elapsed_string($media['created_at'])?></span>
    <br>
    Downloads: <span class="w3-bold"><?=$media['downloads_count']?></span>
    <br>
    Last Downloaded: 
    <?php if($media['downloads_count'] > 0): ?>
        <span class="w3-bold"><?=time_elapsed_string($media['downloaded_at'])?></span>
    <?php endif; ?>
</p>

<p>
    Dimensions: <span class="w3-bold"><?=$media['content_width']?> x <?=$media['content_height']?></span>
    <br>
    File Type: <span class="w3-bold"><?=$media['content_type']?></span>
    <br>
    Filesize: <span class="w3-bold"><?=string_filesize($media['content_length'], 2)?></span>
</p>

<?php if(count($tags) > 0): ?>
    <p>
        Tags: 
        <?php foreach($tags as $tag): ?>
            <span class="w3-tag w3-blue"><?=$tag['name']?></span>
        <?php endforeach; ?>
    </p>

<?php endif; ?>

<hr>

<a
    href="<?=$_SERVER['HTTP_REFERER'] ?? ENV_DOMAIN.'/image/list'?>"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-arrow-left fa-padding-right"></i> Back to Image List
</a>

<a
    href="<?=ENV_DOMAIN?>/action/download/<?=$media['id']?>"
    class="w3-button w3-white w3-border"
    target="_blank"
>
    <i class="fa-solid fa-download fa-padding-right"></i> Download Image
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');