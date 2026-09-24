<?php

namespace TeamTeaTime\Forum\Frontend\Editors;

use Illuminate\Support\Manager;
use TeamTeaTime\Forum\Frontend\Editors\Contracts\Editor;
use TeamTeaTime\Forum\Frontend\Editors\Drivers\QuillEditor;

class EditorManager extends Manager
{
    public function getDefaultDriver(): ?string
    {
        return $this->config->get('forum.frontend.editor.driver') ?: null;
    }

    public function isEnabled(): bool
    {
        return $this->getDefaultDriver() !== null;
    }

    /**
     * Resolve the active editor, or null when rich text editing is disabled.
     */
    public function active(): ?Editor
    {
        return $this->isEnabled() ? $this->driver() : null;
    }

    protected function createQuillDriver(): Editor
    {
        return new QuillEditor(
            $this->config->get('forum.frontend.editor.cdn.quill', []),
            $this->config->get('forum.frontend.editor.options', []),
        );
    }
}
