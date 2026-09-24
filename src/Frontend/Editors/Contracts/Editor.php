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

    /**
     * Driver-specific options passed to the client-side editor instance.
     *
     * Values MUST be JSON-serializable. As JSON cannot carry functions,
     * client-side callbacks (such as toolbar handlers) may be referenced by
     * the name of a global function, which the adapter resolves at runtime.
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array;
}
