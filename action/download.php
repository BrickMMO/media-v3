<?php

if(isset($_GET['key']))
{
    
    $query = 'SELECT id,
        google_id,
        name
        FROM media
        WHERE id = "'.addslashes($_GET['key']).'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(mysqli_num_rows($result))
    {

        $media = mysqli_fetch_assoc($result);

        $query = 'INSERT INTO downloads (
                media_id, 
                created_at
            ) VALUES (
                "'.$media['id'].'",
                NOW()
            )';
        mysqli_query($connect, $query);
            
        header_redirect('https://drive.google.com/uc?export=download&id='.$media['google_id']);

    }

}

message_set('Download Error', 'There was an error downloading the specified media.', 'red');
header_redirect('/');
