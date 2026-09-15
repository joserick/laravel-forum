<?php

namespace TeamTeaTime\Forum\Frontend\Presets\BladeTailwind\Components;

use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\Component;
use Illuminate\View\View;
use TeamTeaTime\Forum\Frontend\Editors\EditorManager;

class Editor extends Component
{
    public function render(): View
    {
        return ViewFactory::make('forum::components.editor', [
            'editor' => app(EditorManager::class)->active(),
            'source' => config('forum.frontend.editor.source', 'cdn'),
        ]);
    }
}
