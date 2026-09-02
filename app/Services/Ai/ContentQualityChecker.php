<?php

namespace App\Services\Ai;

use App\Models\BlogPost;

class ContentQualityChecker
{
    /**
     * Audit an article for SEO quality, keyword safety, repetition, and readability.
     */
    public static function check(string $title, string $content, ?string $focusKeyword = null, int $excludePostId = 0): array
    {
        $warnings = [];
        $score = 100;

        $cleanContent = strip_tags($content);
        $words = str_word_count($cleanContent);

        // 1. Length & Depth Check
        if ($words < 250) {
            $warnings[] = "Thin Content Warning: Article has only {$words} words. Editorial recommendation is 400+ words.";
            $score -= 25;
        } elseif ($words < 400) {
            $warnings[] = "Short Article: {$words} words. Consider expanding sections with more specific venue or photography details.";
            $score -= 10;
        }

        // 2. Keyword Stuffing / Density Check
        if (!empty($focusKeyword)) {
            $kw = strtolower(trim($focusKeyword));
            $contentLower = strtolower($cleanContent);
            $count = substr_count($contentLower, $kw);
            $density = $words > 0 ? round(($count * str_word_count($kw)) / $words * 100, 2) : 0;

            if ($density > 3.5) {
                $warnings[] = "Keyword Stuffing Risk: Target keyword '{$focusKeyword}' appears {$count} times (Density {$density}%). Maximum safe density is 2.5%.";
                $score -= 30;
            } elseif ($density < 0.3 && $words > 300) {
                $warnings[] = "Low Keyword Presence: Focus keyword '{$focusKeyword}' is barely mentioned ({$count} times). Ensure it naturally appears in headings and intro.";
                $score -= 10;
            }
        }

        // 3. Repeated Sentence / Paragraph Check
        $paragraphs = array_filter(array_map('trim', explode("\n", $cleanContent)));
        $seen = [];
        $duplicateParagraphs = 0;
        foreach ($paragraphs as $p) {
            if (strlen($p) < 20) continue;
            if (isset($seen[$p])) {
                $duplicateParagraphs++;
            }
            $seen[$p] = true;
        }

        if ($duplicateParagraphs > 0) {
            $warnings[] = "Duplicate Paragraphs: Found {$duplicateParagraphs} repeated paragraph(s). Remove duplicate sections.";
            $score -= ($duplicateParagraphs * 15);
        }

        // 4. Duplicate Title Check against published blogs
        $duplicateTitle = BlogPost::where('title', 'like', $title)
            ->where('id', '!=', $excludePostId)
            ->exists();
        if ($duplicateTitle) {
            $warnings[] = "Duplicate Title: Another blog post already uses this exact title.";
            $score -= 20;
        }

        // 5. Internal Link Presence
        $hasInternalLink = (str_contains($content, 'href="/services"') || str_contains($content, 'href="/wedding-photographer') || str_contains($content, 'href="/galleries"') || str_contains($content, 'href="/#contact"'));
        if (!$hasInternalLink && $words > 300) {
            $warnings[] = "Internal Linking: No internal links to studio services or destinations detected.";
            $score -= 10;
        }

        $finalScore = max(10, min(100, $score));

        return [
            'score'       => $finalScore,
            'word_count'  => $words,
            'warnings'    => $warnings,
            'is_passing'  => empty($warnings) || $finalScore >= 70,
        ];
    }
}
