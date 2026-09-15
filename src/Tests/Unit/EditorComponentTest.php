<?php

namespace TeamTeaTime\Forum\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use TeamTeaTime\Forum\Frontend\Editors\EditorManager;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class EditorComponentTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addNamespace('forum', dirname(__DIR__, 3) . '/ui-presets/blade-tailwind/views');
    }

    private function renderEditor(string $component): \Illuminate\Testing\TestView
    {
        return $this->blade($component);
    }

    #[Test]
    public function renders_a_plain_textarea_when_no_editor_is_configured()
    {
        config(['forum.frontend.editor.driver' => null]);
        $this->app->forgetInstance(EditorManager::class);

        $view = $this->renderEditor('<x-forum::editor name="content">hello</x-forum::editor>');

        $view->assertSee('name="content"', false);
        $view->assertSee('hello', false);
        $view->assertDontSee('data-forum-editor', false);
        $view->assertDontSee('quill.js', false);
    }

    #[Test]
    public function emits_cdn_assets_when_an_editor_is_configured()
    {
        config([
            'forum.frontend.editor.driver' => 'quill',
            'forum.frontend.editor.source' => 'cdn',
        ]);
        $this->app->forgetInstance(EditorManager::class);

        $view = $this->renderEditor('<x-forum::editor name="content"></x-forum::editor>');

        $view->assertSee('data-forum-editor="quill"', false);
        $view->assertSee('quill.snow.css', false);
        $view->assertSee('quill.js', false);
    }

    #[Test]
    public function does_not_emit_cdn_assets_when_source_is_bundled()
    {
        config([
            'forum.frontend.editor.driver' => 'quill',
            'forum.frontend.editor.source' => 'bundled',
        ]);
        $this->app->forgetInstance(EditorManager::class);

        $view = $this->renderEditor('<x-forum::editor name="content"></x-forum::editor>');

        $view->assertSee('data-forum-editor="quill"', false);
        $view->assertDontSee('quill.snow.css', false);
        $view->assertDontSee('quill.js', false);
    }

    #[Test]
    public function forwards_additional_attributes()
    {
        config(['forum.frontend.editor.driver' => null]);
        $this->app->forgetInstance(EditorManager::class);

        $view = $this->renderEditor('<x-forum::editor name="content" id="body" class="custom"></x-forum::editor>');

        $view->assertSee('id="body"', false);
        $view->assertSee('custom', false);
    }
}
