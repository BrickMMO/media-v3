<?php

security_check();
admin_check();

define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Dashboard');
define('PAGE_SELECTED_SECTION', 'admin-dashboard');
define('PAGE_SELECTED_SUB_PAGE', '/admin/dashboard');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');
include('../templates/message.php');    

$query = 'SELECT *
    FROM media
    WHERE type = "image"
    AND deleted_at IS NULL';
$image_count = mysqli_num_rows(mysqli_query($connect, $query));

$query = 'SELECT *
    FROM media
    WHERE type = "video"
    AND deleted_at IS NULL';
$video_count = mysqli_num_rows(mysqli_query($connect, $query));

$query = 'SELECT *
    FROM media
    WHERE type = "audio"
    AND deleted_at IS NULL';
$audio_count = mysqli_num_rows(mysqli_query($connect, $query));

$query = 'SELECT *
    FROM downloads';
$download_count = mysqli_num_rows(mysqli_query($connect, $query));

$query = 'SELECT media.*,(
        SELECT COUNT(*)
        FROM downloads
        WHERE downloads.media_id = media.id
    ) AS downloads_count,(
        SELECT MAX(downloads.created_at)
        FROM downloads
        WHERE downloads.media_id = media.id
    ) AS last_download
    FROM media
    HAVING downloads_count > 0
    ORDER BY downloads_count DESC
    LIMIT 10';
$result = mysqli_query($connect, $query);

?>

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/media.png"
        height="50"
        style="vertical-align: top"
    />
    Stock Media
</h1>

<p>
    Total Images: <span class="w3-tag w3-blue"><?=$image_count?></span> 
    Total Video: <span class="w3-tag w3-blue"><?=$video_count?></span> 
    Total Audio: <span class="w3-tag w3-blue"><?=$audio_count?></span> 
    Total Downloads: <span class="w3-tag w3-blue"><?=$download_count?></span>
</p>

<hr />

<h2>Popular Media</h2>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th class="bm-table-icon"></th>
        <th>Name</th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
    </tr>

    <?php while ($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <?php if($record['type'] != 'audio'): ?>
                    <a href="<?=ENV_DOMAIN?>/admin/thumbnail/<?=$record['id'] ?>">
                        <img src="https://lh3.googleusercontent.com/d/<?=$record['google_id']?>=w800-h800-c" width="70">
                    </a>
                <?php endif; ?>
            </td>
            <td>
                <?=$record['name'] ?>
                <br>
                <small>
                    Uploaded: <span class="w3-bold"><?=time_elapsed_string($record['created_at'])?></span>
                    <br>
                    Downloads: <span class="w3-bold"><?=$record['downloads_count']?></span>
                    <br>
                    Last Downloaded: 
                    <span class="w3-bold"><?=time_elapsed_string($record['last_download'])?></span>
                    <br>
                    Google ID: 
                    <a href="https://drive.google.com/file/d/<?=$record['google_id']?>/preview">
                        <?=$record['google_id']?>
                    </a>
                    <br>
                    Tags:
                    <?php foreach(media_tags($record['id']) as $tag):?>
                        <span class="w3-tag w3-blue"><?=$tag['name']?></span>
                    <?php endforeach; ?>
                </small>
            </td>
            <td>
                <a href="<?=ENV_DOMAIN?>/<?=$record['type']?>/details/<?=$record['id'] ?>">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
            </td>
            <td>
                <a href="<?=ENV_DOMAIN?>/admin/<?=$record['type']?>/edit/<?=$record['id'] ?>">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>


<!--
<a
    href="<?=ENV_DOMAIN?>/admin/import"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-download"></i> Import Colours
</a>

<hr />

<div
    class="w3-row-padding"
    style="margin-left: -16px; margin-right: -16px"
>
    <div class="w3-half">
        <div class="w3-card">
            <header class="w3-container w3-grey w3-padding w3-text-white">
                <i class="bm-colours"></i> Uptime Status
            </header>
            <div class="w3-container w3-padding">Uptime Status Summary</div>
            <footer class="w3-container w3-border-top w3-padding">
                <a
                    href="<?=ENV_DOMAIN?>/admin/uptime/colours"
                    class="w3-button w3-border w3-white"
                >
                    <i class="fa-regular fa-file-lines fa-padding-right"></i>
                    Full Report
                </a>
            </footer>
        </div>
    </div>
    <div class="w3-half">
        <div class="w3-card">
            <header class="w3-container w3-grey w3-padding w3-text-white">
                <i class="bm-colours"></i> Stat Summary
            </header>
            <div class="w3-container w3-padding">App Statistics Summary</div>
            <footer class="w3-container w3-border-top w3-padding">
                <a
                    href="<?=ENV_DOMAIN?>/stats/colours"
                    class="w3-button w3-border w3-white"
                >
                    <i class="fa-regular fa-chart-bar fa-padding-right"></i> Full Report
                </a>
            </footer>
        </div>
    </div>
</div>
-->

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
