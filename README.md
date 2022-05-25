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

Now you have nova-image-tinymce.php file in your config folder. Edit TinyMCE options and image related configrations
here.

Add your TinyMCE cloud API key here or to your .env file like this:

```bash
TINYMCE_API_KEY=your-key-here
```

Run migration to build image table

```bash
php artisan migrate
```

After that you are good to go! Add NovaImageTinymce class and field to your Nova Resource.

```php
use Visanduma\NovaImageTinymce\NovaImageTinymceEditor;

...

NovaImageTinymceEditor::make('Body', 'body'),
```

## Available options

You can pass arguments and TinyMCE options directly from a field to customize your toolbar and plugins, like this:

```php
NovaImageTinymceEditor::make('Body')->placeholder('Enter content here')
->options(['toolbar' => ['undo redo | align | link | code'], 'plugins' => ['link code']]),
```

For available options/plugins visit official TinyMCE 5 documentation.

### Screenshots

## Contributing

This package was created using Kraftbit/nova-tinymce5-editor core
https://github.com/Kraftbit/nova-tinymce5-editor

## License

[MIT](https://choosealicense.com/licenses/mit/)
