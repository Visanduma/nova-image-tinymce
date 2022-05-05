<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('get-images',[ config('nova-tinymce5-editor.options.media_handler'),'getImages']);
Route::get('search-images/{query}',[ config('nova-tinymce5-editor.options.media_handler'),'searchImages']);
Route::post('upload-image',[ config('nova-tinymce5-editor.options.media_handler'),'uploadImage']);