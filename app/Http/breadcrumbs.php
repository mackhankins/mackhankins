<?php

// Home
Breadcrumbs::register('home', function ($breadcrumbs)
{
    $breadcrumbs->push('Home', route('/'));
});

// Blog
Breadcrumbs::register('blog', function ($breadcrumbs)
{
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Blog', route('blog'));
});

// Post
Breadcrumbs::register('post', function ($breadcrumbs, $post)
{
    $breadcrumbs->parent('blog');
    $breadcrumbs->push($post->title, action('Pub\BlogController@single', $post->slug));
});