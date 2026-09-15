<?php

namespace TeamTeaTime\Forum\Frontend\Editors\Contracts;

interface Editor
{
    /**
     * The unique name of this editor.
     */
    public function getName(): string;

    /**
     * The canonical content format produced by this editor.
     */
    public function getFormat(): string;

    /**
     * The value used for the data-forum-editor attribute on the editor's
     * textarea, allowing the client-side adapter to find and initialise it.
     */
    public function getDataAttribute(): string;

    /**
     * The CDN assets required by this editor.
     *
     * @return array{css: array<int, string>, js: array<int, string>}
     */
    public function getCdnAssets(): array;
}
