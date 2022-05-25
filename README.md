# Laravel Nova Image TinyMCE Editor field

Nova Image TinyMCE Editor is Laravel Nova field that integrates TinyMCE5 WYSIWYG editor wit inbuilt image gallery.

## Installation

Use the [composer](https://getcomposer.org/) to install this package.

```bash
composer require visanduma/nova-image-tinymce
```

## Usage

Publish config with the following command:

```bash
php artisan vendor:publish --provider="Visanduma\NovaImageTinymce\FieldServiceProvider"
```

Edit TinyMCE options and image related configrations. add your TinyMCE cloud API key here or to your .env file like this:

```bash
TINYMCE_API_KEY=your-key-here
```

Run migration to build image table

```bash
php artisan migrate
```

Add NovaImageTinymce class and field to your Nova Resource.

```php
use Visanduma\NovaImageTinymce\NovaImageTinymceEditor;

...

NovaImageTinymceEditor::make('Body', 'body')->useImageGallery()
```

Disable image upload feature

```php
NovaImageTinymceEditor::make('Body', 'body')->useImageGallery()->withoutImageUpload()
```

## Available options

You can pass arguments and TinyMCE options directly from a field to customize your toolbar and plugins, like this:

```php
NovaImageTinymceEditor::make('Body')->placeholder('Enter content here')
->options(['toolbar' => ['undo redo | align | link | code'], 'plugins' => ['link code']]),
```

For available options/plugins visit official TinyMCE 5 documentation.

## Customizing image handler

The default image handler is inbuilt one with it's own data table. you can chage image hnadler in config file. image handlers mustbe compatible with 
"MediaControllerInterface"

```php
 'media_handler' => \Visanduma\NovaImageTinymce\Controller\MediaController::class
```

### Screenshots

## Idea

This package was created on top of Kraftbit/nova-tinymce5-editor 
https://github.com/Kraftbit/nova-tinymce5-editor

## License

[MIT](https://choosealicense.com/licenses/mit/)
