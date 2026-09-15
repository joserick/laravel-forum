<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Old thread threshold
    |--------------------------------------------------------------------------
    |
    | The minimum age of a thread before it should be considered old. This
    | determines whether or not a thread can be considered new or unread for
    | any user. Increasing this value to cover a longer period will increase
    | the ultimate size of your forum_threads_read table. Must be a valid
    | strtotime() string, or set to false to completely disable age-sensitive
    | thread features.
    |
    */

    'old_thread_threshold' => '7 days',

    /*
    |--------------------------------------------------------------------------
    | Soft deletes
    |--------------------------------------------------------------------------
    |
    | Disable this if you want threads and posts to be permanently removed from
    | your database when they're deleted. Note that by default, the option
    | to hard delete threads and posts exists regardless of this setting.
    |
    */

    'soft_deletes' => true,

    /*
    |--------------------------------------------------------------------------
    | Display trashed (soft-deleted) posts
    |--------------------------------------------------------------------------
    |
    | Enable this if you want to display placeholder messages for soft-deleted
    | posts instead of hiding them altogether. Enabling this will override the
    | viewTrashedPosts ability.
    |
    */

    'display_trashed_posts' => true,

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    |
    | "Per page" values for each model. These are applied for both the web and
    | API routes.
    |
    */

    'pagination' => [
        'threads' => 20,
        'posts' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation
    |--------------------------------------------------------------------------
    |
    | Values for some customisable validation rules.
    |
    */

    'validation' => [
        'title_min' => 3,
        'content_min' => 3,
    ],

    /*
    |--------------------------------------------------------------------------
    | Content
    |--------------------------------------------------------------------------
    |
    | Settings for how post content is stored and rendered.
    |
    | format: The canonical format of post content. Supported values are
    | 'plain', 'html' and 'markdown'. The default, 'plain', preserves the
    | original behaviour: all HTML is escaped and newlines are converted to
    | line breaks. You may register custom format drivers at runtime.
    |
    | store_rendered: When enabled, the rendered HTML of each post is stored
    | in the content_html column on save. This avoids re-rendering content on
    | every page view, which is especially useful for the 'markdown' format.
    | Requires the content_html migration to have been run.
    |
    | sanitizer: The allow-list used to sanitize HTML content on write. It
    | applies whenever rendered output can contain HTML ('html' and
    | 'markdown' formats). Only the elements, attributes and URL schemes
    | listed here will be retained in stored content.
    |
    */

    'content' => [
        'format' => 'plain',

        'store_rendered' => false,

        'sanitizer' => [
            'allowed_elements' => [
                'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike', 'sub', 'sup',
                'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
                'ul', 'ol', 'li',
                'blockquote', 'pre', 'code',
                'a', 'img', 'span', 'hr',
            ],

            'allowed_attributes' => [
                'a' => ['href', 'title', 'target', 'rel'],
                'img' => ['src', 'alt', 'title', 'width', 'height'],
                '*' => ['class'],
            ],

            'allowed_link_schemes' => ['http', 'https', 'mailto'],

            'allowed_media_schemes' => ['http', 'https'],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Approval
    |--------------------------------------------------------------------------
    |
    | Values for the content approval feature. Enabling either of the approval
    | types will override the equivalent per-category setting.
    |
    | Note that any content that is pending approval won't be automatically
    | approved when switching `enable_globally` from true to false.
    |
    | rejection_should_soft_delete is always ignored if soft deletes are
    | disabled.
    |
    */

    'content_approval' => [
        'threads' => [
            'enable_globally' => false,
            'rejection_should_soft_delete' => false
        ],
        'posts' => [
            'enable_globally' => false,
            'rejection_should_soft_delete' => false
        ]
    ]

];
