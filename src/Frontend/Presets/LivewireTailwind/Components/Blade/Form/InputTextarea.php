<?php

namespace TeamTeaTime\Forum\Frontend\Presets\LivewireTailwind\Components\Blade\Form;

use Illuminate\Support\Facades\View as ViewFactory;
use Illuminate\View\View;
use TeamTeaTime\Forum\Frontend\Editors\EditorManager;

class InputTextarea extends InputComponent
{
    public function render(): View
    {
        return ViewFactory::make('forum::components.form.input-textarea', [
            'editor' => app(EditorManager::class)->active(),
            'source' => config('forum.frontend.editor.source', 'cdn'),
        ]);
    }
}
