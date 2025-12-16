<?php

security_check();
admin_check();

if(
    !isset($_GET['key']) || 
    !is_numeric($_GET['key']) || 
    !tag_fetch($_GET['key']))
{
    message_set('Tag Error', 'There was an error with the provided tag.');
    header_redirect('/admin/tag/list');
}
elseif ($_SERVER['REQUEST_METHOD'] == 'POST') 
{

    // Basic serverside validation
    if (!validate_blank($_POST['name']))
    {

        message_set('Tag Error', 'There was an error with the provided tag.', 'red');
        header_redirect('/admin/tag/list');
    }
    
    $query = 'UPDATE tags SET
        name = "'.addslashes($_POST['name']).'",
        updated_at = NOW()
        WHERE id = '.$_GET['key'].'
        LIMIT 1';
    mysqli_query($connect, $query);

    message_set('Tag Success', 'Your tag has been edited.');
    header_redirect('/admin/tag/list');
    
}

define('APP_NAME', 'Media');
define('PAGE_TITLE','Edit Tag');
define('PAGE_SELECTED_SECTION', 'admin-content');
define('PAGE_SELECTED_SUB_PAGE', '/admin/tag/list');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');
include('../templates/message.php');

$tag = tag_fetch($_GET['key']);

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
    <a href="<?=ENV_DOMAIN?>/admin/dashboard">Stock Media</a> / 
    <a href="<?=ENV_DOMAIN?>/admin/tag/list">Tags</a> / 
    Edit Tag
</p>

<hr />

<h2>Edit Tag: <?=$tag['name']?></h2>

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
        value="<?=$tag['name']?>"
    />
    <label for="name" class="w3-text-gray">
        Name <span id="name-error" class="w3-text-red"></span>
    </label>

    <button class="w3-block w3-btn w3-orange w3-text-white w3-margin-top" onclick="return validateMainForm();">
        <i class="fa-solid fa-tag fa-padding-right"></i>
        Edit Tag
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

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
