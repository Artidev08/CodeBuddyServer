@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'Thank You';
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
    <style>
        .btn.btn-circle.btn-lg,
        .btn-group-lg>.btn.btn-circle {
            width: 5rem;
            height: 5rem;
            
        }
        #thankyou{
            height: 500px;
        }
    </style>
    <section class="pt-17 pb-10">
        <div class="container">
            <div class="row justify-content-center align-items-center text-center" id="thankyou">
                <div class="col-md-8">
                    <div class="mb-5">
                        <a href="#" class="btn btn-circle btn-gradient gradient-1 btn-lg"><i class="uil uil-check fs-48 fw-semibold"></i></a>
                    </div>
                    <div>
                        <h2 class="fw-bold">Thank you for reaching out to us!</h2>
                        <p class="mb-4">We'll get back to you as soon as possible.</p>
                        <div>
                            <a href="{{ url('/') }}" class="btn btn-primary">Back to Home</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
