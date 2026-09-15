<?php

namespace TeamTeaTime\Forum\Support\Validation;

use TeamTeaTime\Forum\Support\Validation\Rules\MinimumContentLength;

class PostRules
{
    public static function create(): array
    {
        return [
            'content' => ['required', 'string', new MinimumContentLength(config('forum.general.validation.content_min'))],
        ];
    }

    public static function search(): array
    {
        return [
            'term' => ['required', 'string'],
        ];
    }

    public static function delete(): array
    {
        return [
            'permadelete' => ['boolean'],
        ];
    }

    public static function bulk(): array
    {
        return [
            'posts' => ['required', 'array'],
        ];
    }

    public static function bulkDelete(): array
    {
        return static::bulk() + [
            'permadelete' => ['boolean'],
        ];
    }
}
