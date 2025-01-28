@extends('layouts.app')

@section('meta_data')
    @php
        // $meta_title = @$metas->title ?? 'Home';
        $meta_title = "Explore the Best Wishes, Greetings, Notes, and Quotes Across Languages and Emotions";
        $meta_description = "Find meaningful wishes, greetings, notes, and quotes designed for every emotion and relationship. Connect with others through beautifully crafted messages in various languages.";
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = ' ';

        $cta_visibility = false;
        $cta['title'] = 'Discover more about the power of ultimate project starter: zStarter';
        $cta['button_label'] = 'Discover Now';
        $cta['button_route'] = route('about');
    @endphp
@endsection
@section('content')
    <div class="gradient">
        @include('site.home.include.home_banner')
        @include('site.home.include.festive_lists')
    </div>
@endsection
