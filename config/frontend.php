<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Enable/disable feature
    |--------------------------------------------------------------------------
    |
    | Whether or not to enable the frontend feature.
    |
    */

    'enable' => true,

    /*
    |--------------------------------------------------------------------------
    | Preset
    |--------------------------------------------------------------------------
    |
    | The frontend preset to use. Must be installed with the
    | forum:preset-install command.
    |
    */

    'preset' => 'blade-tailwind',

    /*
    |--------------------------------------------------------------------------
    | Editor
    |--------------------------------------------------------------------------
    |
    | The rich text editor to use for post and thread content. Available
    | drivers are registered with the editor manager; the package ships with
    | a 'quill' driver. Set 'driver' to null to disable rich text editing and
    | use the default textarea.
    |
    | source: Where the editor's assets are loaded from. 'cdn' emits the
    | driver's CDN stylesheet and script tags automatically. 'bundled' emits
    | no tags and expects you to import the editor yourself in your
    | application's assets. Note that the server always sanitizes content on
    | write regardless of this setting.
    |
    | cdn: Optional per-driver overrides for the CDN assets used when source
    | is 'cdn'. Each entry accepts 'css' and 'js' arrays of URLs.
    |
    */

    'editor' => [
        'driver' => null,

        'source' => 'cdn',

        'cdn' => [],

        'options' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Router
    |--------------------------------------------------------------------------
    |
    | Router config for the frontend routes.
    |
    */

    'router' => [
        'prefix' => '/forum',
        'as' => 'forum.',
        'middleware' => ['web'],
        'auth_middleware' => ['auth'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Route Prefixes
    |--------------------------------------------------------------------------
    |
    | Prefixes to use for each model in frontend routes.
    |
    */

    'route_prefixes' => [
        'category' => 'c',
        'thread' => 't',
        'post' => 'p',
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Category Color
    |--------------------------------------------------------------------------
    |
    | The default color to use when creating new categories.
    |
    */

    'default_category_color' => '#007bff',

    /*
    |--------------------------------------------------------------------------
    | Utility Class
    |--------------------------------------------------------------------------
    |
    | Here we specify the class to use for various frontend utility methods.
    | This is automatically aliased to 'Forum' for ease of use in views.
    |
    */

    'utility_class' => TeamTeaTime\Forum\Support\Frontend\Forum::class,

];
