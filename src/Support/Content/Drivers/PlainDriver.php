<?php

namespace TeamTeaTime\Forum\Support\Content\Drivers;

use TeamTeaTime\Forum\Support\Content\Contracts\ContentDriver;

class PlainDriver implements ContentDriver
{
    public function prepare(string $content): string
    {
        return $content;
    }

    public function render(string $content): string
    {
        return nl2br(e($content));
    }

    public function toText(string $content): string
    {
        return $content;
    }
}
