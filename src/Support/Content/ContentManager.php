<?php

namespace TeamTeaTime\Forum\Support\Content;

use Illuminate\Support\Manager;
use TeamTeaTime\Forum\Support\Content\Contracts\ContentDriver;
use TeamTeaTime\Forum\Support\Content\Drivers\HtmlDriver;
use TeamTeaTime\Forum\Support\Content\Drivers\MarkdownDriver;
use TeamTeaTime\Forum\Support\Content\Drivers\PlainDriver;

class ContentManager extends Manager
{
    public function getDefaultDriver(): string
    {
        return $this->config->get('forum.general.content.format', 'plain');
    }

    public function render(string $content, ?string $driver = null): string
    {
        return $this->driver($driver)->render($content);
    }

    public function prepare(string $content, ?string $driver = null): string
    {
        return $this->driver($driver)->prepare($content);
    }

    public function toText(string $content, ?string $driver = null): string
    {
        return $this->driver($driver)->toText($content);
    }

    protected function createPlainDriver(): ContentDriver
    {
        return new PlainDriver;
    }

    protected function createHtmlDriver(): ContentDriver
    {
        return new HtmlDriver($this->sanitizer());
    }

    protected function createMarkdownDriver(): ContentDriver
    {
        return new MarkdownDriver($this->sanitizer());
    }

    private function sanitizer(): Sanitizer
    {
        return new Sanitizer($this->config->get('forum.general.content.sanitizer', []));
    }
}
