<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use App\Models\ContentCalendar;
use Illuminate\Console\Command;

class PublishScheduledBlogsCommand extends Command
{
    protected $signature = 'blog:publish-scheduled';
    protected $description = 'Publish scheduled blog posts and update content calendar status';

    public function handle(): int
    {
        $this->info('Checking for scheduled blogs ready to publish...');

        // 1. Posts scheduled via published_at timestamp
        $postsToPublish = BlogPost::where('is_published', false)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->get();

        $publishedCount = 0;
        foreach ($postsToPublish as $post) {
            $post->is_published = true;
            $post->status = 'published';
            $post->save();

            // Update calendar if linked
            if ($post->calendarSchedule) {
                $post->calendarSchedule->status = 'published';
                $post->calendarSchedule->save();
            }

            $this->line("Published: {$post->title} ({$post->url})");
            $publishedCount++;
        }

        // 2. Calendar entries due today that are linked to a post
        $dueCalendar = ContentCalendar::where('scheduled_for', '<=', now()->toDateString())
            ->where('status', 'generated')
            ->whereNotNull('blog_post_id')
            ->with('blogPost')
            ->get();

        foreach ($dueCalendar as $entry) {
            if ($entry->blogPost && !$entry->blogPost->is_published) {
                $entry->blogPost->is_published = true;
                $entry->blogPost->status = 'published';
                $entry->blogPost->published_at = now();
                $entry->blogPost->save();

                $entry->status = 'published';
                $entry->save();
                $publishedCount++;
            }
        }

        $this->info("Completed. Published {$publishedCount} blog post(s).");
        return self::SUCCESS;
    }
}
