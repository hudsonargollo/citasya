<?php

namespace App\Services;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BlogService
{
    protected string $contentPath;

    public function __construct()
    {
        $this->contentPath = base_path('content/blog');
        if (!File::exists($this->contentPath)) {
            File::makeDirectory($this->contentPath, 0755, true);
        }
    }

    /**
     * Get all published blog posts sorted by date descending.
     */
    public function getAllPosts(string $category = null, string $search = null): array
    {
        $files = File::glob($this->contentPath . '/*.md');
        $posts = [];

        foreach ($files as $file) {
            $post = $this->parseFile($file);
            if ($post) {
                // Category filter
                if ($category && strtolower($post['category']) !== strtolower($category)) {
                    continue;
                }
                // Search filter
                if ($search) {
                    $q = strtolower($search);
                    $matches = str_contains(strtolower($post['title']), $q) ||
                               str_contains(strtolower($post['meta_description']), $q) ||
                               str_contains(strtolower($post['target_keyword']), $q) ||
                               str_contains(strtolower($post['excerpt']), $q);
                    if (!$matches) {
                        continue;
                    }
                }
                $posts[] = $post;
            }
        }

        // Sort newest first
        usort($posts, function ($a, $b) {
            return strtotime($b['date'] ?? 'now') - strtotime($a['date'] ?? 'now');
        });

        return $posts;
    }

    /**
     * Get single post by slug.
     */
    public function getPostBySlug(string $slug): ?array
    {
        $files = File::glob($this->contentPath . '/*.md');
        foreach ($files as $file) {
            $post = $this->parseFile($file, true);
            if ($post && ($post['slug'] === $slug || basename($file, '.md') === $slug)) {
                return $post;
            }
        }
        return null;
    }

    /**
     * Get related posts excluding the given slug.
     */
    public function getRelatedPosts(string $currentSlug, int $limit = 3): array
    {
        $all = $this->getAllPosts();
        $filtered = array_filter($all, fn($p) => $p['slug'] !== $currentSlug);
        return array_slice($filtered, 0, $limit);
    }

    /**
     * Get unique categories with counts.
     */
    public function getCategories(): array
    {
        $posts = $this->getAllPosts();
        $categories = [];
        foreach ($posts as $post) {
            $cat = $post['category'] ?? 'General';
            $categories[$cat] = ($categories[$cat] ?? 0) + 1;
        }
        return $categories;
    }

    /**
     * Parse a markdown file with frontmatter.
     */
    protected function parseFile(string $filePath, bool $fullContent = false): ?array
    {
        if (!File::exists($filePath)) {
            return null;
        }

        $raw = File::get($filePath);
        $frontmatter = [];
        $markdown = $raw;

        // Extract YAML Frontmatter
        if (preg_match('/^---\s*\n(.*?)\n---\s*\n(.*)$/s', $raw, $matches)) {
            $frontmatterStr = $matches[1];
            $markdown = $matches[2];
            $frontmatter = $this->parseYaml($frontmatterStr);
        }

        $filename = basename($filePath, '.md');
        $slug = $frontmatter['slug'] ?? $filename;
        $title = $frontmatter['title'] ?? Str::title(str_replace('-', ' ', $slug));
        $metaTitle = $frontmatter['meta_title'] ?? $title . ' | CitasYa Blog';
        $metaDescription = $frontmatter['meta_description'] ?? Str::limit(strip_tags($markdown), 155);
        $date = $frontmatter['date'] ?? date('Y-m-d', File::lastModified($filePath));
        $author = $frontmatter['author'] ?? 'Equipo Editorial CitasYa';
        $category = $frontmatter['category'] ?? 'Negocios & Gestión';
        $targetKeyword = $frontmatter['target_keyword'] ?? '';
        $secondaryKeywords = (array)($frontmatter['secondary_keywords'] ?? []);

        // Estimate reading time (average 200 words/min)
        $wordCount = $frontmatter['word_count'] ?? str_word_count(strip_tags($markdown));
        $readTime = max(1, (int)ceil($wordCount / 200));

        // Generate excerpt
        $cleanText = preg_replace('/^#+\s+.*$/m', '', $markdown);
        $cleanText = preg_replace('/!\[.*?\]\(.*?\)/', '', $cleanText);
        $cleanText = preg_replace('/>.*$/m', '', $cleanText);
        $excerpt = Str::limit(trim(strip_tags($cleanText)), 220);

        // Featured Image
        $image = $frontmatter['image'] ?? null;
        if (!$image) {
            // Check if there is an image referenced in markdown
            if (preg_match('/!\[.*?\]\((.*?)\)/', $markdown, $imgMatch)) {
                $imgSrc = $imgMatch[1];
                if (str_starts_with($imgSrc, '../images/')) {
                    $image = '/images/blog/' . basename($imgSrc);
                } else {
                    $image = $imgSrc;
                }
            } else {
                $image = '/images/blog/' . $slug . '.png';
            }
        }

        $data = [
            'slug' => $slug,
            'title' => $title,
            'meta_title' => $metaTitle,
            'meta_description' => $metaDescription,
            'date' => $date,
            'author' => $author,
            'category' => $category,
            'target_keyword' => $targetKeyword,
            'secondary_keywords' => $secondaryKeywords,
            'word_count' => $wordCount,
            'read_time' => $readTime,
            'excerpt' => $excerpt,
            'image' => $image,
            'file_path' => $filePath,
        ];

        if ($fullContent) {
            // Process markdown into HTML with TOC and enhanced styling
            [$html, $toc] = $this->renderMarkdownWithToc($markdown, $slug);
            $data['content_html'] = $html;
            $data['toc'] = $toc;
            $data['raw_markdown'] = $markdown;
        }

        return $data;
    }

    /**
     * Basic YAML parser for key-values and simple lists.
     */
    protected function parseYaml(string $yaml): array
    {
        $data = [];
        $lines = explode("\n", $yaml);
        $currentKey = null;

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || str_starts_with($line, '#')) {
                continue;
            }

            // List item
            if (str_starts_with($line, '-') && $currentKey) {
                $item = trim(substr($line, 1));
                $item = trim($item, '"\'');
                if (!isset($data[$currentKey]) || !is_array($data[$currentKey])) {
                    $data[$currentKey] = [];
                }
                $data[$currentKey][] = $item;
                continue;
            }

            // Key-Value
            if (str_contains($line, ':')) {
                [$key, $val] = explode(':', $line, 2);
                $key = trim($key);
                $val = trim($val);
                $val = trim($val, '"\'');

                if ($val === '' || $val === '[]') {
                    $currentKey = $key;
                    $data[$key] = [];
                } else {
                    $currentKey = $key;
                    if (is_numeric($val)) {
                        $data[$key] = str_contains($val, '.') ? (float)$val : (int)$val;
                    } elseif (strtolower($val) === 'true') {
                        $data[$key] = true;
                    } elseif (strtolower($val) === 'false') {
                        $data[$key] = false;
                    } else {
                        $data[$key] = $val;
                    }
                }
            }
        }

        return $data;
    }

    /**
     * Render markdown with heading IDs, TOC extraction, and custom enhancements.
     */
    protected function renderMarkdownWithToc(string $markdown, string $slug): array
    {
        // Replace relative image paths
        $markdown = preg_replace('/!\[(.*?)\]\(\.\.\/images\/(.*?)\)/', '![$1](/images/blog/$2)', $markdown);

        // Convert markdown to HTML via Laravel standard Str::markdown
        $html = Str::markdown($markdown);

        // Extract headings and inject IDs
        $toc = [];
        $html = preg_replace_callback('/<h([2-3])>(.*?)<\/h\1>/i', function ($matches) use (&$toc) {
            $level = (int)$matches[1];
            $headingText = strip_tags($matches[2]);
            $headingId = Str::slug($headingText);

            $toc[] = [
                'level' => $level,
                'title' => $headingText,
                'id' => $headingId,
            ];

            return sprintf('<h%d id="%s" class="scroll-mt-24 group flex items-center justify-between">%s <a href="#%s" class="opacity-0 group-hover:opacity-100 text-brand-500 hover:text-brand-600 transition-opacity ml-2 text-sm font-normal" aria-label="Enlace a esta sección">#</a></h%d>', $level, $headingId, $matches[2], $headingId, $level);
        }, $html);

        // Wrap tables in responsive overflow container with styled design
        $html = preg_replace('/<table>/i', '<div class="overflow-x-auto my-8 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-sm"><table class="w-full text-left border-collapse text-sm">', $html);
        $html = preg_replace('/<\/table>/i', '</table></div>', $html);

        // Enhance blockquotes for key takeaways or highlights
        $html = preg_replace('/<blockquote>\s*<p><strong>Puntos Clave(.*?)<\/strong>/is', '<blockquote class="bg-gradient-to-br from-emerald-500/10 via-lime-500/5 to-transparent border-l-4 border-lime-500 rounded-r-2xl p-6 my-8 text-slate-800 dark:text-slate-200"><div class="flex items-center gap-2 text-emerald-700 dark:text-lime-400 font-bold text-base mb-3"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Puntos Clave', $html);

        return [$html, $toc];
    }
}
