<?php

namespace TeamTeaTime\Forum\Frontend\Editors\Drivers;

use TeamTeaTime\Forum\Frontend\Editors\Contracts\Editor;

class QuillEditor implements Editor
{
    public function __construct(
        private array $cdn = [],
        private array $options = [],
    )
    {
    }

    public function getName(): string
    {
        return 'quill';
    }

    public function getFormat(): string
    {
        return 'html';
    }

    public function getDataAttribute(): string
    {
        return 'quill';
    }

    public function getCdnAssets(): array
    {
        return [
            'css' => $this->cdn['css'] ?? [
                'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css',
            ],
            'js' => $this->cdn['js'] ?? [
                'https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js',
            ],
        ];
    }

    public function getOptions(): array
    {
        return $this->options;
    }
}
