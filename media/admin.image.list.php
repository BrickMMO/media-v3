<?php

security_check();
admin_check();

if (isset($_GET['approve'])) 
{

    $query = 'UPDATE media SET
        approved = 1
        WHERE id = '.$_GET['approve'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Approval Success', 'Image has been deleted.');
    header_redirect('/admin/image/list');
    
}


define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Images');
define('PAGE_SELECTED_SECTION', 'admin-content');
define('PAGE_SELECTED_SUB_PAGE', '/admin/image/list');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');
include('../templates/message.php');

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
    WHERE type = "image"
    ORDER BY name';
$result = mysqli_query($connect, $query);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/media.png"
        height="50"
        style="vertical-align: top"
    />
    Stock Media
</h1>
<p>
    <a href="/admin/dashboard">Stock Media</a> / 
    Images
</p>

<hr />

<h2>Images</h2>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th class="bm-table-icon"></th>
        <th>Name</th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
    </tr>

    <?php while($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <img src="https://lh3.googleusercontent.com/d/<?=$record['google_id']?>=w70-h70-c" width="70">
            </td>
            <td>
                <?=$record['name']?>
                <br>
                <small>
                    Uploaded: <span class="w3-bold"><?=time_elapsed_string($record['created_at'])?></span>
                    <br>
                    Downloads: <span class="w3-bold"><?=$record['downloads_count']?></span>
                    <br>
                    Last Downloaded: 
                    <?php if($record['last_download']): ?>
                        <span class="w3-bold"><?=time_elapsed_string($record['last_download'])?></span>
                    <?php endif; ?>
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
            <td class="bm-table-icon">
                <?php if($record['approved'] == 1): ?>
                    <i class="fa-solid fa-thumbs-up w3-text-green"></i>
                <?php else: ?>
                    <a href="#" onclick="return confirmModal('Are you sure you want to approve the image <?=$record['name']?>?', '/admin/image/list/approve/<?=$record['id']?>');">
                        <i class="fa-solid fa-thumbs-down w3-text-red"></i>
                    </a>
                <?php endif; ?>
            </td>
            <td>
                <a href="<?=ENV_DOMAIN?>/<?=$record['type']?>/details/<?=$record['id'] ?>">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </a>
            </td>
            <td class="bm-table-icon">
                <a href="/admin/image/edit/<?=$record['id']?>">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<a
  href="/action/google/import/image"
  class="w3-button w3-white w3-border" 
  onclick="loading();"
>
    <i class="fa-solid fa-file-import fa-padding-right"></i> Import Images from Google Drive
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
