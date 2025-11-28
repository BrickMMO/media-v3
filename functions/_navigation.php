<?php

function navigation_array($selected = false)
{

    $navigation = [
        [
            'title' => 'Stock Media',
            'sections' => [
                [
                    'title' => 'Stock Media',
                    'id' => 'admin-content',
                    'pages' => [
                        [
                            'icon' => 'media',
                            'url' => '/admin/dashboard',
                            'title' => 'Stock Media',
                            'sub-pages' => [
                                [
                                    'title' => 'Dashboard',
                                    'url' => '/admin/dashboard',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Images',
                                    'url' => '/admin/image/list',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Video',
                                    'url' => '/admin/video/list',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Audio',
                                    'url' => '/admin/audio/list',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Tags',
                                    'url' => '/admin/tag/list',
                                    'colour' => 'red',
                                ],[
                                    'title' => 'Import Media',
                                    'url' => '/admin/import',
                                    'colour' => 'red',
                                ],[
                                    
                                    'br' => '---',
                                ],[
                                    'title' => 'Visit Media App',
                                    'url' => 'https://media.brickmmo.com',
                                    'colour' => 'orange',
                                    'icon' => 'fa-solid fa-arrow-up-right-from-square',
                                ],[
                                    'br' => '---',
                                ],[
                                    'title' => 'Uptime Report',
                                    'url' => 'https://uptime.brickmmo.com/details/16',
                                    'colour' => 'orange',
                                    'icons' => 'bm-uptime',
                                ],[
                                    'title' => 'Stats Report',
                                    'url' => '/stas/events',
                                    'colour' => 'orange',
                                    'icons' => 'bm-stats',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ];

    if($selected)
    {
        
        $selected = '/'.$selected;
        $selected = str_replace('//', '/', $selected);
        $selected = str_replace('.php', '', $selected);
        $selected = str_replace('.', '/', $selected);
        $selected = substr($selected, 0, strpos($selected, '/'));

        foreach($navigation as $levels)
        {

            foreach($levels['sections'] as $section)
            {

                foreach($section['pages'] as $page)
                {

                    if(strpos($page['url'], $selected) === 0)
                    {
                        return $page;
                    }

                }

            }

        }

    }

    return $navigation;

}