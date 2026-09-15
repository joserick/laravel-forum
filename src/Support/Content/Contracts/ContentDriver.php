<?php

namespace TeamTeaTime\Forum\Support\Content\Contracts;

interface ContentDriver
{
    /**
     * Prepare the given raw content for storage.
     */
    public function prepare(string $content): string;

    /**
     * Render the given stored content for display.
     */
    public function render(string $content): string;

    /**
     * Produce a plain-text projection of the given content suitable for
     * validation and excerpting.
     */
    public function toText(string $content): string;
}
