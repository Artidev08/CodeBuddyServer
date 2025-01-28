@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = $page->title;
        $meta_description = $page->page_meta_description ? $page->page_meta_description : '';
        $meta_keywords = $page->page_keywords ? $page->page_keywords : getSetting('app_name');
        $meta_motto = false ? $page->page_keywords : getSetting('app_name');

    @endphp
@endsection

@section('content')
    <!--Shape End-->
    <!-- Start Terms & Conditions -->
    <section class="wrapper">
        <div class="container pt-13 pt-md-14 text-start">
            <div class="row">
                <div class="col-md-10 col-lg-8 col-xl-7 col-xxl-6">
                    <nav class="d-inline-block" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                        </ol>
                    </nav>
                    <!-- /nav -->
                </div>
                <!-- /column -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container -->
    </section>
    <section class="section bg-white">
        <div class="container">
            <div class="row gx-0">
                <!-- /column -->
                <div class="col-xl-12 mb-5">
                    <section id="terms-conditions" class="wrapper pt-3 pt-md-8">
                        <div class="card">
                            @if ($page->getMedia('page_meta_image')->isNotEmpty())
                                <img src="{{ $page->getFirstMediaUrl('page_meta_image') }}" class="card-img-top"
                                    alt="Banner Image">
                            @endif
                            <div class="card-body p-md-10 p-6">
                                <h2 class="mb-3">{{ $page->title }}</h2>
                                <p>{!! $page->content !!}</p>
                            </div>
                            <!--/.card-body -->
                        </div>
                        <!--/.card -->
                    </section>
                </div>
                <!-- /column -->
            </div>
        </div>
        <!-- /.container -->
    </section>
    <!--end section-->
    <!-- End Terms & Conditions -->
@endsection
