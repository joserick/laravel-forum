<?php

namespace TeamTeaTime\Forum\Tests\Unit;

use InvalidArgumentException;
use PHPUnit\Framework\Attributes\Test;
use TeamTeaTime\Forum\Support\Content\ContentManager;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class ContentDriversTest extends FeatureTestCase
{
    private function manager(string $format): ContentManager
    {
        config(['forum.general.content.format' => $format]);

        $this->app->forgetInstance(ContentManager::class);

        return $this->app->make(ContentManager::class);
    }

    #[Test]
    public function plain_driver_escapes_html_and_converts_newlines()
    {
        $rendered = $this->manager('plain')->render("<b>hi</b>\nnew");

        $this->assertSame("&lt;b&gt;hi&lt;/b&gt;<br />\nnew", $rendered);
    }

    #[Test]
    public function plain_driver_prepare_is_passthrough()
    {
        $this->assertSame('<b>hi</b>', $this->manager('plain')->prepare('<b>hi</b>'));
    }

    #[Test]
    public function html_driver_strips_disallowed_elements_on_prepare()
    {
        $prepared = $this->manager('html')->prepare('<p>hello</p><script>alert(1)</script>');

        $this->assertStringContainsString('<p>hello</p>', $prepared);
        $this->assertStringNotContainsString('<script', $prepared);
    }

    #[Test]
    public function html_driver_retains_allowed_formatting()
    {
        $prepared = $this->manager('html')->prepare('<p><strong>bold</strong></p>');

        $this->assertSame('<p><strong>bold</strong></p>', $prepared);
    }

    #[Test]
    public function markdown_driver_retains_raw_source_and_renders_html()
    {
        $manager = $this->manager('markdown');

        $this->assertSame('**bold**', $manager->prepare('**bold**'));
        $this->assertStringContainsString('<strong>bold</strong>', $manager->render('**bold**'));
    }

    #[Test]
    public function html_driver_to_text_ignores_markup()
    {
        $this->assertSame('', $this->manager('html')->toText('<p></p>'));
        $this->assertSame('hello', $this->manager('html')->toText('<p>hello</p>'));
    }

    #[Test]
    public function unknown_driver_throws_exception()
    {
        $this->expectException(InvalidArgumentException::class);

        $this->manager('unknown')->render('content');
    }
}
