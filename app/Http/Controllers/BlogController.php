<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Location;
use App\Models\Service;
use App\Services\Seo\SeoService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $query = BlogPost::where('is_published', true)->orderBy('published_at', 'desc')->latest();

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category', $request->category);
        }

        $posts = $query->paginate(9);
        $categories = BlogPost::where('is_published', true)->distinct()->pluck('category');
        $locations = Location::where('is_published', true)->take(6)->get();

        $seo = SeoService::resolve(null, [
            'title'            => 'Wedding Journal & Photography Insights — Paneventz',
            'meta_description' => 'Explore destination wedding guides, candid photography tips, bridal styling inspiration, and cinematic heirloom advice by Paneventz.',
            'canonical_url'    => url('/blog'),
        ]);

        return view('blog.index', [
            'posts'      => $posts,
            'categories' => $categories,
            'locations'  => $locations,
            'selectedCategory' => $request->category,
            'seo'        => $seo,
        ]);
    }

    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();

        $relatedPosts = BlogPost::where('is_published', true)
            ->where('id', '!=', $post->id)
            ->where('category', $post->category)
            ->take(3)
            ->get();

        if ($relatedPosts->isEmpty()) {
            $relatedPosts = BlogPost::where('is_published', true)
                ->where('id', '!=', $post->id)
                ->take(3)
                ->get();
        }

        $relatedLocations = Location::where('is_published', true)->take(4)->get();
        $relatedServices = Service::where('is_published', true)->take(3)->get();

        $seo = SeoService::resolve($post);

        return view('blog.show', [
            'post'             => $post,
            'relatedPosts'     => $relatedPosts,
            'relatedLocations' => $relatedLocations,
            'relatedServices'  => $relatedServices,
            'seo'              => $seo,
        ]);
    }
}
