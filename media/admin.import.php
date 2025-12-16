<?php

security_check();
admin_check();

define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Tags');
define('PAGE_SELECTED_SECTION', 'admin-dashboard');
define('PAGE_SELECTED_SUB_PAGE', '/admin/import');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');
include('../templates/message.php');

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
    Import Media
</p>

<hr />

<h2>Import Media</h2>

<a
    href="<?=ENV_DOMAIN?>/action/google/import/image"
    class="w3-button w3-white w3-border"
    onclick="loading();"
>
    <i class="fa-solid fa-camera fa-padding-right"></i> Import Images
</a>

<a
    href="<?=ENV_DOMAIN?>/action/google/import/video"
    class="w3-button w3-white w3-border"
    onclick="loading();"
>
    <i class="fa-solid fa-video fa-padding-right"></i> Import Videos
</a>

<a
    href="<?=ENV_DOMAIN?>/action/google/import/audio"
    class="w3-button w3-white w3-border"
    onclick="loading();"
>
    <i class="fa-solid fa-headphones fa-padding-right"></i> Import Audio
</a>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');
