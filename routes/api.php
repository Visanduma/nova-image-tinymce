<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('get-images',[ config('nova-image-tinymce.media_handler'),'getImages']);
Route::get('search-images/{query}',[ config('nova-image-tinymce.media_handler'),'searchImages']);
Route::post('upload-image',[ config('nova-image-tinymce.media_handler'),'uploadImage']);
Route::post('delete-images',[ config('nova-image-tinymce.media_handler'),'deleteImages']);
