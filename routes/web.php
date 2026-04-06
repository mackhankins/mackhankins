<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LlmsController;
use App\Http\Controllers\OgImageController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
Route::get('/projects/{project:slug}', [ProjectController::class, 'show'])->name('projects.show');

Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/preview/{post:slug}', [PostController::class, 'preview'])->name('blog.preview')->middleware('signed');
Route::get('/blog/{post:slug}', [PostController::class, 'show'])->name('blog.show');
Route::get('/blog/{post:slug}/og-image.png', OgImageController::class)->name('blog.og-image');

Route::get('/about', AboutController::class)->name('about');

Route::feeds();

Route::get('/llms.txt', [LlmsController::class, 'index'])->name('llms.txt');
Route::get('/llms-full.txt', [LlmsController::class, 'full'])->name('llms-full.txt');

Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
