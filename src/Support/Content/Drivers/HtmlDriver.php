<?php

namespace TeamTeaTime\Forum\Support\Content\Drivers;

use TeamTeaTime\Forum\Support\Content\Contracts\ContentDriver;
use TeamTeaTime\Forum\Support\Content\Sanitizer;

class HtmlDriver implements ContentDriver
{
    private Sanitizer $sanitizer;

    public function __construct(Sanitizer $sanitizer)
    {
        $this->sanitizer = $sanitizer;
    }

    public function prepare(string $content): string
    {
        return $this->sanitizer->sanitize($content);
    }

    public function render(string $content): string
    {
        // Sanitize defensively on render so content stored before sanitization
        // was enabled is still safe to display.
        return $this->sanitizer->sanitize($content);
    }

    public function toText(string $content): string
    {
        return html_entity_decode(strip_tags($content), ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}
