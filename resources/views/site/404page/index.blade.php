@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'About';
        $meta_description = @$metas->description ?? '';
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = ' ';
    @endphp
@endsection

@section('content')
    <section class="wrapper bg-light">
        <div class="container pt-12 pt-md-16 pb-14 pb-md-16">
            <div class="row">
                <div class="col-lg-9 col-xl-8 mx-auto">
                    <figure class="mb-10"><img class="img-fluid" src="{{ asset('site/assets/img/illustrations/404.png') }}"
                            srcset="{{ asset('site/assets/img/illustrations/404@2x.png 2x') }}" alt=""></figure>
                </div>
                <!-- /column -->
                <div class="col-lg-8 col-xl-7 col-xxl-6 mx-auto text-center">
                    <h1 class="mb-3">Oops! Page Not Found.</h1>
                    <p class="lead mb-7 px-md-12 px-lg-5 px-xl-7">The page you are looking for is not available or has been
                        moved. Try a different page or go to homepage with the button below.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary rounded-pill">Go to Homepage</a>
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <!-- /section -->
@endsection
