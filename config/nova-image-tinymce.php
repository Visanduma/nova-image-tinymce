<?php

return [
    /*
    |--------------------------------------------------------------------------------
    | TinyMCE Editor config options
    |--------------------------------------------------------------------------------
    |
    */

    'options' => [
        'apiKey' => env('TINYMCE_API_KEY', ''),

        'init' => [
            'menubar' => false,
            'autoresize_bottom_margin' => 40,
            'branding' => false,
            'image_caption' => true,
            'paste_as_text' => true,
            'paste_word_valid_elements' => 'b,strong,i,em,h1,h2',
        ],
        'plugins' => [
            'advlist autolink lists link image imagetools media',
            'paste code wordcount autoresize table',
        ],

        // "image-gallery" keyword in toolbar is used for showing image picker/uploader

        'toolbar' => [
            'image-gallery | undo redo | formatselect forecolor backcolor |
                 bold italic underline strikethrough blockquote removeformat |
                 align bullist numlist outdent indent | link table media insertmedialibrary | code',
        ],

    ],

    /*
     |--------------------------------------------------------------------------
     | Images related setup
     |--------------------------------------------------------------------------
     |
     */

    'storage_path' => 'tinymce/',

    'disk' => env('FILESYSTEM_DRIVER', 'local'),


    // You can create your own handler. it must compatible with  MediaControllerInterface
    'media_handler' => \Visanduma\NovaImageTinymce\Controller\MediaController::class


];

