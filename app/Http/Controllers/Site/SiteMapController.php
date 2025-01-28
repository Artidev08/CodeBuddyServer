<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsitePage;
use App\Models\Blog;

class SiteMapController extends Controller
{
    public function index()
    {
        $website_pages = WebsitePage::whereStatus(1)->get();
        $articles = Blog::whereIsPublish(1)->get();
        $xml = '<?xml version="1.0" encoding="UTF-8"?>';
        return response()->view(
            'site.sitemap',
            [
            'website_pages' => $website_pages,
            'articles' => $articles,
            'xml' => $xml
            ]
        )->header('Content-Type', 'text/xml');
    }
}
