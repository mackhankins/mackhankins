<?php

use App\Models\Post;

return [
    'feeds' => [
        'main' => [
            'items' => [Post::class, 'getFeedItems'],
            'url' => '/feed',
            'title' => 'Stuff & Things — Mack Hankins',
            'description' => 'Thoughts on development, tools, and building stuff.',
            'language' => 'en-US',
            'image' => '',
            'format' => 'atom',
            'view' => 'feed::atom',
            'type' => '',
            'contentType' => '',
        ],
    ],
];
