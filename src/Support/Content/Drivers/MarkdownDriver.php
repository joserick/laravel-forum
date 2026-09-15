<?php

namespace TeamTeaTime\Forum\Support\Content\Drivers;

use Illuminate\Support\Str;
use TeamTeaTime\Forum\Support\Content\Contracts\ContentDriver;
use TeamTeaTime\Forum\Support\Content\Sanitizer;

class MarkdownDriver implements ContentDriver
{
    private Sanitizer $sanitizer;

    public function __construct(Sanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function prepare(string $content): string
    {
        // The raw Markdown is retained so it can be loaded back into an editor.
        return $content;
    }

    public function render(string $content): string
    {
        return $this->sanitizer->sanitize(Str::markdown($content));
    }

    public function toText(string $content): string
    {
        return html_entity_decode(strip_tags($this->render($content)), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
