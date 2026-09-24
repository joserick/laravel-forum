<?php

namespace TeamTeaTime\Forum\Tests\Unit;

use Illuminate\Support\ViewErrorBag;
use Illuminate\View\ComponentAttributeBag;
use PHPUnit\Framework\Attributes\Test;
use TeamTeaTime\Forum\Frontend\Editors\Drivers\QuillEditor;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class LivewireEditorViewTest extends FeatureTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app['view']->addNamespace('forum', dirname(__DIR__, 3) . '/ui-presets/livewire-tailwind/views');
    }

    private function render(?QuillEditor $editor, array $attributes = []): string
    {
        return view('forum::components.form.input-textarea', [
            'editor' => $editor,
            'source' => 'cdn',
            'id' => 'content',
            'label' => '',
            'xShow' => '',
            'attributes' => new ComponentAttributeBag($attributes),
            'errors' => new ViewErrorBag,
        ])->render();
    }

    #[Test]
    public function renders_a_textarea_when_no_editor_is_configured()
    {
        $html = $this->render(null, ['wire:model' => 'content']);

        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringNotContainsString('data-forum-editor', $html);
        $this->assertStringNotContainsString('quill.js', $html);
    }

    #[Test]
    public function renders_an_entangled_editor_when_configured()
    {
        $html = $this->render(new QuillEditor, ['wire:model' => 'content']);

        $this->assertStringContainsString("\$wire.entangle('content')", $html);
        $this->assertStringContainsString("'quill'", $html);
        $this->assertStringContainsString('quill.snow.css', $html);
        $this->assertStringContainsString('quill.js', $html);
        $this->assertStringContainsString('x-model="model"', $html);
        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringNotContainsString('wire:model="content"', $html);
    }

    #[Test]
    public function falls_back_to_a_textarea_without_a_wire_model_binding()
    {
        $html = $this->render(new QuillEditor, ['id' => 'content']);

        $this->assertStringContainsString('<textarea', $html);
        $this->assertStringNotContainsString('$wire.entangle', $html);
        $this->assertStringNotContainsString('quill.js', $html);
    }

    #[Test]
    public function forwards_editor_options_to_the_editor_wrapper()
    {
        $options = [
            'modules' => [
                'toolbar' => [
                    'container' => [['bold', 'italic']],
                    'handlers' => ['image' => 'AppQuillImageHandler'],
                ],
            ],
        ];

        $html = $this->render(new QuillEditor([], $options), ['wire:model' => 'content']);

        $this->assertStringContainsString('data-forum-editor-options', $html);
        $this->assertStringContainsString(htmlspecialchars(json_encode($options), ENT_QUOTES, 'UTF-8'), $html);
    }

    #[Test]
    public function does_not_emit_an_options_attribute_without_configured_options()
    {
        $html = $this->render(new QuillEditor, ['wire:model' => 'content']);

        $this->assertStringNotContainsString('data-forum-editor-options', $html);
    }
}
