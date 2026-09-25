<?php

namespace App\Http\Controllers;

use App\Services\BlogService;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\TwitterCard;
use Artesaos\SEOTools\Facades\JsonLd;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    protected BlogService $blogService;

    public function __construct(BlogService $blogService)
    {
        $this->blogService = $blogService;
    }

    /**
     * Display a listing of blog posts.
     */
    public function index(Request $request)
    {
        $category = $request->query('categoria');
        $search = $request->query('q');

        $posts = $this->blogService->getAllPosts($category, $search);
        $categories = $this->blogService->getCategories();
        $featuredPost = !empty($posts) && !$search && !$category ? $posts[0] : null;
        $gridPosts = $featuredPost ? array_slice($posts, 1) : $posts;

        // Configure SEO
        SEOMeta::setTitle('Blog & Estrategias de Crecimiento | CitasYa Bolivia');
        SEOMeta::setDescription('Artículos prácticos, guías de automatización y consejos de gestión para salones, spas, consultorios y negocios de servicios en Santa Cruz y Bolivia.');
        SEOMeta::setCanonical(url('/blog'));
        SEOMeta::addKeyword(['blog citasya', 'automatizacion citas bolivia', 'gestion salones santa cruz', 'reservas online bolivia']);

        OpenGraph::setTitle('Blog & Estrategias de Crecimiento | CitasYa Bolivia');
        OpenGraph::setDescription('Artículos prácticos, guías de automatización y consejos de gestión para salones, spas y servicios en Bolivia.');
        OpenGraph::setUrl(url('/blog'));
        OpenGraph::addProperty('type', 'blog');
        OpenGraph::addImage(asset('favicon.svg'));

        TwitterCard::setTitle('Blog & Estrategias de Crecimiento | CitasYa Bolivia');
        TwitterCard::setSite('@CitasYa');

        return view('blog.index', compact('posts', 'gridPosts', 'featuredPost', 'categories', 'category', 'search'));
    }

    /**
     * Display a single blog post.
     */
    public function show(string $slug)
    {
        $post = $this->blogService->getPostBySlug($slug);

        if (!$post) {
            abort(404, 'El artículo solicitado no existe.');
        }

        $relatedPosts = $this->blogService->getRelatedPosts($post['slug'], 3);

        // Configure SEO metadata dynamically from frontmatter
        SEOMeta::setTitle($post['meta_title'] ?? ($post['title'] . ' | CitasYa'));
        SEOMeta::setDescription($post['meta_description']);
        SEOMeta::setCanonical(url('/blog/' . $post['slug']));
        
        $keywords = array_filter(array_merge(
            [$post['target_keyword']],
            $post['secondary_keywords'] ?? [],
            ['citasya', 'santa cruz bolivia', 'reservas online']
        ));
        SEOMeta::addKeyword($keywords);

        // OpenGraph
        OpenGraph::setTitle($post['title']);
        OpenGraph::setDescription($post['meta_description']);
        OpenGraph::setUrl(url('/blog/' . $post['slug']));
        OpenGraph::addProperty('type', 'article');
        OpenGraph::addProperty('article:published_time', $post['date'] . 'T09:00:00+02:00');
        OpenGraph::addProperty('article:author', $post['author']);
        OpenGraph::addProperty('article:section', $post['category']);
        
        if (!empty($post['image'])) {
            $imageUrl = str_starts_with($post['image'], 'http') ? $post['image'] : url($post['image']);
            OpenGraph::addImage($imageUrl);
            TwitterCard::setImage($imageUrl);
        } else {
            OpenGraph::addImage(asset('favicon.svg'));
        }

        // Twitter Card
        TwitterCard::setTitle($post['title']);
        TwitterCard::setDescription($post['meta_description']);
        TwitterCard::setSite('@CitasYa');

        // JSON-LD Article Schema
        JsonLd::setType('Article');
        JsonLd::setTitle($post['title']);
        JsonLd::setDescription($post['meta_description']);
        JsonLd::setUrl(url('/blog/' . $post['slug']));
        JsonLd::addValue('headline', $post['title']);
        JsonLd::addValue('datePublished', $post['date']);
        JsonLd::addValue('dateModified', $post['date']);
        JsonLd::addValue('author', [
            '@type' => 'Organization',
            'name' => $post['author'],
            'url' => url('/')
        ]);
        JsonLd::addValue('publisher', [
            '@type' => 'Organization',
            'name' => 'CitasYa',
            'logo' => [
                '@type' => 'ImageObject',
                'url' => asset('favicon.svg')
            ]
        ]);

        return view('blog.show', compact('post', 'relatedPosts'));
    }

    /**
     * Generate RSS feed for blog posts.
     */
    public function feed()
    {
        $posts = $this->blogService->getAllPosts();

        $rss = '<?xml version="1.0" encoding="UTF-8"?>';
        $rss .= '<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom">';
        $rss .= '<channel>';
        $rss .= '<title>CitasYa Blog - Automatización y Negocios en Bolivia</title>';
        $rss .= '<link>' . url('/blog') . '</link>';
        $rss .= '<description>Artículos prácticos sobre gestión de turnos, marketing y pagos con QR Simple para salones, spas y servicios en Bolivia.</description>';
        $rss .= '<language>es-BO</language>';
        $rss .= '<atom:link href="' . url('/blog/feed') . '" rel="self" type="application/rss+xml" />';

        foreach ($posts as $post) {
            $rss .= '<item>';
            $rss .= '<title>' . htmlspecialchars($post['title'], ENT_XML1, 'UTF-8') . '</title>';
            $rss .= '<link>' . url('/blog/' . $post['slug']) . '</link>';
            $rss .= '<guid>' . url('/blog/' . $post['slug']) . '</guid>';
            $rss .= '<pubDate>' . date(DATE_RSS, strtotime($post['date'])) . '</pubDate>';
            $rss .= '<description>' . htmlspecialchars($post['meta_description'], ENT_XML1, 'UTF-8') . '</description>';
            $rss .= '<category>' . htmlspecialchars($post['category'], ENT_XML1, 'UTF-8') . '</category>';
            $rss .= '</item>';
        }

        $rss .= '</channel>';
        $rss .= '</rss>';

        return response($rss, 200, ['Content-Type' => 'application/xml; charset=utf-8']);
    }
}
