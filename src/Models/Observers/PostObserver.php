<?php

namespace TeamTeaTime\Forum\Models\Observers;

use Illuminate\Support\Facades\Schema;
use TeamTeaTime\Forum\Models\Post;
use TeamTeaTime\Forum\Support\Content\ContentManager;

class PostObserver
{
    private ContentManager $content;

    public function __construct(ContentManager $content)
    {
        $this->content = $content;
    }

    public function saving(Post $post): void
    {
        if (!$post->isDirty('content')) {
            return;
        }

        $post->content = $this->content->prepare($post->content ?? '');

        if (config('forum.general.content.store_rendered') && Schema::hasColumn($post->getTable(), 'content_html')) {
            $post->content_html = $this->content->render($post->content);
        }
    }
}
