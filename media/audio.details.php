<?php

if(!isset($_GET['key']))
{

    message_set('Video Error', 'There was an error loading the specified video.', 'red');
    header_redirect('/video/list');
 
}

$query = 'SELECT google_id,
    content_type
    FROM media
    WHERE id = "'.$_GET['key'].'"
    LIMIT 1';
$result = mysqli_query($connect, $query);

if(!mysqli_num_rows($result))
{

    message_set('Video Error', 'There was an error loading the specified video.', 'red');
    header_redirect(ENV_DOMAIN.'/video/list');

}

$media = mysqli_fetch_assoc($result);

if(!$media['content_type'])
{

    $headers = google_file_headers($media['google_id']);

    $query = 'UPDATE media SET
        content_type = "'.$headers['content_type'].'",
        content_length = "'.$headers['content_length'].'"
        WHERE id = "'.$_GET['key'].'"
        LIMIT 1';
    mysqli_query($connect, $query);

    header_redirect(ENV_DOMAIN.'/audio/details/'.$_GET['key']);

}

define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Audio Details');
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

<div style="position: relative;
        width: 100%;
        padding-bottom: 70px;
        height: 0;
        overflow: hidden;"
    class="w3-margin-top">
    <iframe 
        width="100%" 
        height="70" 
        src="https://drive.google.com/file/d/<?=$media['google_id']?>/preview"
        frameborder="0"
        style="position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;"
        allowfullscreen></iframe>
</div>

<div class="w3-purple w3-container">
    <h2 class="w3-large">
        <i class="fa-solid fa-headphones fa-padding-right"></i>
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
    href="<?=$_SERVER['HTTP_REFERER'] ?? ENV_DOMAIN.'/audio/list'?>"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-arrow-left fa-padding-right"></i> Back to Audio List
</a>

<a
    href="<?=ENV_DOMAIN?>/action/download/<?=$media['id']?>"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-download fa-padding-right"></i> Download Audio
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');