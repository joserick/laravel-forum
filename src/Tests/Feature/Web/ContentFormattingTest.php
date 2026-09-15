<?php

namespace TeamTeaTime\Forum\Tests\Feature\Web;

use Illuminate\Foundation\Auth\User;
use Orchestra\Testbench\Factories\UserFactory;
use PHPUnit\Framework\Attributes\Test;
use TeamTeaTime\Forum\Database\Factories\CategoryFactory;
use TeamTeaTime\Forum\Database\Factories\PostFactory;
use TeamTeaTime\Forum\Database\Factories\ThreadFactory;
use TeamTeaTime\Forum\Models\Category;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Support\Content\ContentManager;
use TeamTeaTime\Forum\Support\Frontend\Forum;
use TeamTeaTime\Forum\Tests\FeatureTestCase;

class ContentFormattingTest extends FeatureTestCase
{
    private Category $category;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->category = CategoryFactory::new()->createOne();
        $this->user = UserFactory::new()->createOne();
    }

    private function useFormat(string $format, bool $storeRendered = false): void
    {
        config([
            'forum.general.content.format' => $format,
            'forum.general.content.store_rendered' => $storeRendered,
        ]);

        $this->app->forgetInstance(ContentManager::class);
    }

    #[Test]
    public function should_sanitize_html_content_on_thread_creation()
    {
        $this->useFormat('html');

        $this->actingAs($this->user)->post(Forum::route('thread.store', $this->category), [
            'title' => 'Thread title',
            'content' => '<p>hello</p><script>alert(1)</script>',
        ]);

        $post = Post::firstOrFail();

        $this->assertStringContainsString('<p>hello</p>', $post->content);
        $this->assertStringNotContainsString('<script', $post->content);
    }

    #[Test]
    public function should_sanitize_html_content_on_post_update()
    {
        $this->useFormat('html');

        $thread = ThreadFactory::new()->createOne(['author_id' => $this->user->getKey()]);
        $post = PostFactory::new()->createOne([
            'thread_id' => $thread->getKey(),
            'author_id' => $this->user->getKey(),
        ]);

        $this->actingAs($this->user)->patch(Forum::route('post.update', $post), [
            'content' => '<p>edited</p><script>alert(1)</script>',
        ]);

        $post->refresh();

        $this->assertStringContainsString('<p>edited</p>', $post->content);
        $this->assertStringNotContainsString('<script', $post->content);
    }

    #[Test]
    public function should_store_rendered_content_when_enabled()
    {
        $this->useFormat('html', true);

        $this->actingAs($this->user)->post(Forum::route('thread.store', $this->category), [
            'title' => 'Thread title',
            'content' => '<p>hello</p>',
        ]);

        $post = Post::firstOrFail();

        $this->assertNotNull($post->content_html);
        $this->assertSame('<p>hello</p>', $post->content_html);
    }

    #[Test]
    public function rendered_content_falls_back_to_the_driver()
    {
        $this->useFormat('html');

        $thread = ThreadFactory::new()->createOne(['author_id' => $this->user->getKey()]);
        $post = PostFactory::new()->createOne([
            'thread_id' => $thread->getKey(),
            'author_id' => $this->user->getKey(),
            'content' => '<p>fallback</p>',
        ]);

        $this->assertNull($post->content_html);
        $this->assertSame('<p>fallback</p>', $post->renderedContent);
    }

    #[Test]
    public function should_fail_validation_for_markup_only_content()
    {
        $this->useFormat('html');

        $response = $this->actingAs($this->user)->post(Forum::route('thread.store', $this->category), [
            'title' => 'Thread title',
            'content' => '<p></p>',
        ]);

        $response->assertSessionHasErrors('content');
    }
}
