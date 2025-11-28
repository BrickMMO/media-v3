<?php

if(isset($_GET['q']))
{

    $q = string_url($_GET['q']);
    if($q != $_GET['q'])
    {
        header_redirect('/audio/list/q/'.$q);
    }
 
}

// Get page number from URL if set
if(isset($_GET['page']) && is_numeric($_GET['page']))
{
    $current_page = (int)$_GET['page'];
}
else
{
    $current_page = 1;
}

define('APP_NAME', 'Stock Media');
define('PAGE_TITLE', 'Audio');
define('PAGE_SELECTED_SECTION', '');
define('PAGE_SELECTED_SUB_PAGE', '');

define('THEME_WIDTH', '100%');

include('../templates/html_header.php');
include('../templates/nav_header.php');
include('../templates/nav_sidebar.php');
include('../templates/main_header.php');

include('../templates/message.php');

$query = 'SELECT *
    FROM media
    WHERE type = "audio"
    AND deleted_at IS NULL
    LIMIT 20';
$result = mysqli_query($connect, $query);

// Pagination setup
$results_per_page = 64;
$offset = ($current_page - 1) * $results_per_page;

// Split search term by dashes
if(isset($q))
{

    $search_terms = explode('-', $q);

    // Build WHERE clause for multiple terms
    $where_conditions = [];
    foreach($search_terms as $term) 
    {

        $term = trim($term);

        if(!empty($term)) 
        {
            $where_conditions[] = 'media.name LIKE "%'.mysqli_real_escape_string($connect, $term).'%"';
        }

    }

    $where_clause = implode(' OR ', $where_conditions);
    // $where_clause = '';

}

// Count total results
$count_query = 'SELECT COUNT(DISTINCT media.id) AS total
    FROM media
    WHERE type = "audio" 
    AND deleted_at IS NULL 
    '.(isset($q) ? 'AND ('.$where_clause.')' : '').'
    ';
$count_result = mysqli_query($connect, $count_query);
$count_row = mysqli_fetch_assoc($count_result);
$total_results = $count_row['total'];
$total_pages = ceil($total_results / $results_per_page);

// Get paginated results
$query = 'SELECT DISTINCT id, 
    name,
    google_id
    FROM media
    WHERE type = "audio" 
    AND deleted_at IS NULL 
    '.(isset($q) ? 'AND ('.$where_clause.')' : '').'
    GROUP BY media.id
    ORDER BY name DESC
    LIMIT '.$offset.', '.$results_per_page;
$result = mysqli_query($connect, $query);

?>

<div class="w3-center">

    <h1>Stock Audio</h1>

    <input 
        class="w3-input w3-border w3-margin-top w3-margin-bottom" 
        type="text" 
        value="<?=isset($_GET['q']) ? htmlspecialchars(str_replace('-', ' ', $_GET['q'])) : ''?>"
        placeholder="" 
        style="max-width: 300px; display: inline-block; box-sizing: border-box; vertical-align: middle;" 
        id="search-term">

    <select 
        class="w3-select w3-border w3-margin-top w3-margin-bottom" 
        id="search-type"
        style="max-width: 120px; display: inline-block; box-sizing: border-box; vertical-align: middle;">
        <option value="image">Images</option>
        <option value="video">Video</option>
        <option value="audio" selected>Audio</option>
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

    <?php while($record = mysqli_fetch_assoc($result)): ?>

        <div style="width: calc(25% - 16px); box-sizing: border-box; display: flex; flex-direction: column;">
            <div class="w3-card-4 w3-margin-top" style="max-width:100%; height: 100%; display: flex; flex-direction: column;">
                    <header class="w3-container w3-purple">
                        <h4 style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                            <i class="fa-solid fa-headphones fa-padding-right"></i>
                            <?=$record['name']?>
                        </h4>
                    </header>
                <a href="/audio/details/<?=$record['id']?>" class="w3-margin" style="position: relative; display: block;">
                    <img src="https://cdn.brickmmo.com/images@1.0.0/no-screenshot.png" alt="" style="max-width: 100%; height: auto;" />
                </a>
            </div>
        </div>

    <?php endwhile; ?>

</div>

<hr>

<nav class="w3-text-center w3-section">

    <div class="w3-bar">            

        <?php
        
        // Display pagination links
        for ($i = 1; $i <= $total_pages; $i++) 
        {
            echo '<a href="'.ENV_DOMAIN.'/image/list';
            if($i > 1) echo '/page/'.$i;
            if(isset($q)) echo '/q/'.$q;
            echo '" class="w3-button';
            if($i == $current_page) echo ' w3-border';
            echo '">'.$i.'</a>';
        }

        ?>

    </div>

</nav>

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