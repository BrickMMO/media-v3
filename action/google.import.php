<?php

if(!isset($_GET['key']) || !$_GET['key'])
{
    message_set('Google API Error', 'There was an error importing Google Drive media.', 'red');
    header_redirect('/admin/media/dashboard');
}

/*
$query = 'DELETE FROM media';
mysqli_query($connect, $query);

$query = 'DELETE FROM media_tag';
mysqli_query($connect, $query);

$query = 'DELETE FROM tags';
mysqli_query($connect, $query);
*/

$google = setting_fetch('GOOGLE_ACCESS_TOKEN');
$google = json_decode($google, true);

if($_GET['key'] == 'audio') $setting = 'GOOGLE_DRIVE_AUDIO';
elseif($_GET['key'] == 'image') $setting = 'GOOGLE_DRIVE_IMAGE';
elseif($_GET['key'] == 'video') $setting = 'GOOGLE_DRIVE_VIDEO';

$folder = setting_fetch($setting, 'comma_2_array');
$type = $_GET['key'];

if($_GET['key'] == 'audio') $redirect = 'audio';
elseif($_GET['key'] == 'image') $redirect = 'images';
elseif($_GET['key'] == 'video') $redirect = 'video';

try
{

    $files = google_list_files($google, $folder[0], $folder[1]);

}
catch(Exception $e)
{

    message_set('Google API Error', 'Google Access Token has expired.', 'red');
    header_redirect('/admin/authentication/dashboard');

}

$media = 0;

foreach($files as $key => $file)
{
    $query = 'SELECT *
        FROM media 
        WHERE google_id = "'.$file['google_id'].'"
        LIMIT 1';
    $result = mysqli_query($connect, $query);

    if(!mysqli_num_rows($result))
    {

        $query = 'INSERT INTO media (
                name,
                type,
                google_id,
                city_id,
                user_id,
                created_at,
                updated_at
            ) VALUES (
                "'.$file['name'].'",
                "'.$file['type'].'",
                "'.$file['google_id'].'",
                "'.$_city['id'].'",
                "'.$_user['id'].'",
                NOW(),
                NOW()
            )';
        mysqli_query($connect, $query); 

        $media_id = mysqli_insert_id($connect);

        $tags = substr($file['name'], 0, strpos($file['name'], '.')).'-'.$file['folder'];
        $tags = explode('-', $tags);
        $tags = array_unique($tags);
        $tags = array_filter($tags);

        foreach($tags as $key => $value) 
            if(is_numeric($value)) 
                unset($tags[$key]);

        foreach($tags as $tag)
        {
            $query = 'SELECT *
                FROM tags 
                WHERE name = "'.$tag.'"
                LIMIT 1';
            $result = mysqli_query($connect, $query);

            if(!mysqli_num_rows($result))
            {
                $query = 'INSERT INTO tags (
                        name,
                        created_at,
                        updated_at
                    ) VALUES (
                        "'.$tag.'",
                        NOW(),
                        NOW()
                    )';
                mysqli_query($connect, $query); 

                $tag_id = mysqli_insert_id($connect);
            }
            else
            {
                $record = mysqli_fetch_assoc($result);
                $tag_id = $record['id'];
            }

            $query = 'INSERT INTO media_tag (
                    medium_id,
                    tag_id
                ) VALUES (
                    "'.$media_id.'",
                    "'.$tag_id.'"
                )';
            mysqli_query($connect, $query); 

        }

        $media ++;

    }

}

message_set(
    'Import Success', 
    'Media from the BrickMMO Google Drive have been imported. '.
    'Imported '.$media.' new media.'
);

header_redirect(ENV_DOMAIN.'/admin/import');

