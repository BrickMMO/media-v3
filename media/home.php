<?php

define('APP_NAME', 'Media');
define('PAGE_TITLE', 'Home');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

?>

<div class="w3-center">

    <h1>Stock Media</h1>

    <input 
        class="w3-input w3-border w3-margin-top w3-margin-bottom" 
        type="text" 
        placeholder="" 
        style="max-width: 300px; display: inline-block; box-sizing: border-box; vertical-align: middle;" 
        id="search-term">

    <select 
        class="w3-select w3-border w3-margin-top w3-margin-bottom" 
        id="search-type"
        style="max-width: 120px; display: inline-block; box-sizing: border-box; vertical-align: middle;">
        <option value="images" selected>Images</option>
        <option value="video">Video</option>
        <option value="audio">Audio</option>
    </select>

    <a
        href="#"
        class="w3-button w3-white w3-border w3-margin-top w3-margin-bottom" 
        style="display: inline-block; box-sizing: border-box; vertical-align: middle;"
        id="search-button"
    >
        <i class="fa-solid fa-magnifying-glass"></i> Search
    </a>
    
</div>

<hr>

<div class="w3-flex" style="flex-wrap: wrap; gap: 16px; align-items: stretch;">

    <div style="width: calc(33.3% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
        <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">

            <header class="w3-container w3-green">
                <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <i class="fa-solid fa-camera fa-padding-right"></i>
                    Images
                </h4>
            </header>
            <div class="w3-margin">
                <a href="/images/list" style="position: relative;">
                    <img src="images/images.png" alt="" style="max-width: 100%; height: auto;" />
                </a>
            </div>
            
        </div>
    </div>

    <div style="width: calc(33.3% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
        <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">

            <header class="w3-container w3-red">
                <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <i class="fa-solid fa-video fa-padding-right"></i>
                    Video
                </h4>
            </header>
            <div class="w3-margin">
                <a href="/video/list" style="position: relative;">
                    <img src="images/video.png" alt="" style="max-width: 100%; height: auto;" />
                </a>
            </div>

        </div>
    </div>

    <div style="width: calc(33.3% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
        <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">

            <header class="w3-container w3-purple">
                <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <i class="fa-solid fa-headphones fa-padding-right"></i>
                    Audio
                </h4>
            </header>
            <div class="w3-margin">
                <a href="/audio/list" style="position: relative;">
                    <img src="images/audio.png" alt="]" style="max-width: 100%; height: auto;" />
                </a>
            </div>
            
        </div>
    </div>

</div>

<script>

(function() {

    let searchButton = document.getElementById('search-button');
    let searchTerm = document.getElementById('search-term');
    let searchType = document.getElementById('search-type');

    function performSearch() 
    {

        let query = searchTerm.value.trim();

        if (query !== '') {
            // Remove anything that's not letters, numbers, or spaces
            query = query.replace(/[^a-zA-Z0-9\s]/g, '');
            // Replace spaces with hyphens
            query = query.replace(/\s+/g, '-');
            window.location.href = '/' + searchType.value + '/list/q/' + query;
        }
        else
        {
            window.location.href = '/' + searchType.value + '/list';
        }

    }

    searchButton.addEventListener('click', function(event) 
    {

        event.preventDefault();
        performSearch();

    });

    searchTerm.addEventListener('keypress', function(event) 
    {

        if (event.key === 'Enter') 
        {
            event.preventDefault();
            performSearch();
        }

    });

})();

</script>

<?php

include('../templates/main_footer.php');
include('../templates/debug.php');
include('../templates/html_footer.php');