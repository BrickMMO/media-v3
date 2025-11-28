<?php

security_check();
admin_check();

if (isset($_GET['delete'])) 
{

    $query = 'DELETE FROM tags 
        WHERE id = '.$_GET['delete'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM media_tag
        WHERE tag_id = '.$_GET['delete'];
    mysqli_query($connect, $query);

    message_set('Delete Success', 'Tag has been deleted.');
    header_redirect('/admin/tag/list');
    
}

define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Tags');
define('PAGE_SELECTED_SECTION', 'admin-dashboard');
define('PAGE_SELECTED_SUB_PAGE', '/admin/tag/list');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT tags.*,(
        SELECT COUNT(*)
        FROM media_tag
        INNER JOIN media 
        ON media.id = media_tag.media_id
        WHERE type = "image"
        AND media_tag.tag_id = tags.id
    ) AS image_count,(
        SELECT COUNT(*)
        FROM media_tag
        INNER JOIN media 
        ON media.id = media_tag.media_id
        WHERE type = "video"
        AND media_tag.tag_id = tags.id
    ) AS video_count,(
        SELECT COUNT(*)
        FROM media_tag
        INNER JOIN media 
        ON media.id = media_tag.media_id
        WHERE type = "audio"
        AND media_tag.tag_id = tags.id
    ) AS audio_count
    FROM tags
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
    Tags
</p>

<hr />

<h2>Media Tags</h2>

<table class="w3-table w3-bordered w3-striped w3-margin-bottom">
    <tr>
        <th>Name</th>
        <th class="bm-table-number">Images</th>
        <th class="bm-table-number">Video</th>
        <th class="bm-table-number">Audio</th>
        <th class="bm-table-icon"></th>
        <th class="bm-table-icon"></th>
    </tr>

    <?php while($record = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td>
                <?=$record['name']?>
            </td>
            <td class="bm-table-number">
                <?=$record['image_count']?>
            </td>
            <td class="bm-table-number">
                <?=$record['video_count']?>
            </td>
            <td class="bm-table-number">
                <?=$record['audio_count']?>
            </td>
            <td>
                <a href="/admin/tag/edit/<?=$record['id']?>">
                    <i class="fa-solid fa-pencil"></i>
                </a>
            </td>
            <td>
                <a href="#" onclick="return confirmModal('Are you sure you want to delete the tag <?=$record['name']?>?', '/admin/tag/list/delete/<?=$record['id']?>');">
                    <i class="fa-solid fa-trash-can"></i>
                </a>
            </td>
        </tr>
    <?php endwhile; ?>

</table>

<a
    href="/admin/tag/add"
    class="w3-button w3-white w3-border"
>
    <i class="fa-solid fa-tag fa-padding-right"></i> Add New Tag
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
