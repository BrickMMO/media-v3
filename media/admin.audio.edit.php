<?php

security_check();
admin_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']) || 
    !media_fetch($_GET['key']))
{
    message_set('Audio Error', 'There was an error with the provided audio.');
    header_redirect('/admin/media/audio');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['name']))
    {
        message_set('Audio Error', 'There was an error with the provided audio.', 'red');
        header_redirect('/admin/media/audio');
    }
    
    $query = 'UPDATE media SET
        name = "'.addslashes($_POST['name']).'",
        approved = "'.addslashes($_POST['approved']).'",
        updated_at = NOW()
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    $query = 'DELETE FROM media_tag
        WHERE media_id = "'.$_GET['key'].'"';
    mysqli_query($connect, $query);

    foreach($_POST['tag_id'] as $value)
    {
        $query = 'INSERT INTO media_tag (
                tag_id,
                media_id
            ) VALUES (
                "'.$value.'",
                "'.$_GET['key'].'"
            )';
        mysqli_query($connect, $query);
    }

    message_set('Audio Success', 'Your audio has been edited.');
    header_redirect('/admin/media/audio');
    
}

define('APP_NAME', 'Media');

define('PAGE_TITLE','Edit Audio');
define('PAGE_SELECTED_SECTION', 'admin-content');
define('PAGE_SELECTED_SUB_PAGE', '/admin/media/audio');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_slideout.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$media = media_fetch($_GET['key']);

?>

<!-- CONTENT -->

<h1 class="w3-margin-top w3-margin-bottom">
    <img
        src="https://cdn.brickmmo.com/icons@1.0.0/bricksum.png"
        height="50"
        style="vertical-align: top"
    />
    Media
</h1>
<p>
    <a href="/city/dashboard">Dashboard</a> / 
    <a href="/admin/media/dashboard">Media</a> / 
    <a href="/admin/media/audio">Audio</a> / 
    Edit Audio
</p>

<hr />

<h2>Edit Media: <?=$media['name']?></h2>

<?php if($media['google_id']): ?>
    <iframe src="https://drive.google.com/file/d/<?=$media['google_id']?>/preview" 
        width="426" 
        height="100" 
        allow="autoplay"
        style="border: none"
        class="w3-padding w3-border w3-margin-bottom"></iframe>
<?php endif; ?>


<form
    method="post"
    novalidate
    id="main-form"
>

    <input  
        name="name" 
        class="w3-input w3-border" 
        type="text" 
        id="name" 
        autocomplete="off"
        value="<?=$media['name']?>"
    />
    <label for="name" class="w3-text-gray">
        Name <span id="name-error" class="w3-text-red"></span>
    </label>

    <?=form_select_table('tag_id', 'tags', 'id', 'name', array('multiple' => true, 'selected' => $media['tags'], 'size' => 10))?>
    <label for="building_id" class="w3-text-gray">
        Tags <span id="building-id-error" class="w3-text-red"></span>
    </label>

    <?=form_select_array('approved', array(1 => 'Approved', 0 => 'Unapproved'), array('selected' => $media['approved']))?>
    <label for="approved" class="w3-text-gray">
        Approved <span id="approved-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-headphones fa-padding-right"></i>
        Edit Audio
    </button>
</form>

<script>

    function validateMainForm() {
        let errors = 0;

        let name = document.getElementById("name");
        let name_error = document.getElementById("name-error");
        name_error.innerHTML = "";
        if (name.value == "") {
            name_error.innerHTML = "(name is required)";
            errors++;
        }

        if (errors) return false;
    }

</script>
    

<?php

include('../templates/modal_city.php');

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
