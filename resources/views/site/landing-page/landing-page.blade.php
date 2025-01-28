@extends('layouts.app')

@section('meta_data')
    @php
        // $meta_title = @$contentLandingPage->title ?? '';
        $meta_title =
            $event->name . ' ' . str_replace('/', '', @$categoryBreadcrumb) . ' | Celebrate Every Bond, Age & Emotion';
        $meta_description = "Browse our selection of inspiring wishes and greetings to brighten someone’s day or celebrate special events.";
        // $meta_description = @$metas->description ?? '';
        $meta_keywords = @$metas->keyword ?? '';
        $meta_motto = @$app_settings['site_motto'] ?? '';
        $meta_abstract = @$app_settings['site_motto'] ?? '';
        $meta_author_name = @$app_settings['app_name'] ?? 'Defenzelite';
        // $meta_author_email = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_reply_to = @$app_settings['frontend_footer_email'] ?? 'dev@defenzelite.com';
        $meta_img = ' ';
        $cta_visibility = false;
        $cta['title'] = 'Discover more about the power of ultimate project starter: zStarter';
        $cta['button_label'] = 'Discover Now';
        $cta['button_route'] = route('about');
    @endphp
@endsection
<style>
    body {
        font-family: 'Source Sans Pro', sans-serif !important;
    }

    .filter-label {
        font-weight: 600;
        font-size: 13px;
    }

    .gradient {
        background: #f2efeb;
        height: 100vh;
    }


    .side-filters span {
        padding: 2px 15px;
        border-radius: 1rem;
        cursor: pointer;
        background: #ddddddc7;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 14px;
    }


    /* search */
    .search-icon {
        position: absolute;
        top: 50%;
        left: 15px;
        transform: translateY(-50%);
        color: #666;
    }

    input[type="search"] {
        padding-left: 40px;
        text-indent: 25px;
        border: 1px solid rgb(179, 179, 179);
    }

    input[type="search"]:focus {
        border-color: #dcebf0;
        outline: none;
        box-shadow: 0 0 5px #b4d4de;
    }


    .card-content {
        color: #666666;
        font-weight: 600;
    }

    .filter-container {
        padding: 10px;
    }

    .custom-select {
        background: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22currentColor%22%3E%3Cpath%20d%3D%22M6%209l6%206%206-6%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22/%3E%3C/svg%3E') no-repeat right 10px center;
        background-size: 1em;
        padding-right: 2em;
        border: 1px solid #ccc;
        padding: 5px;
        font-size: 14px;
        border-radius: 5px;
        font-weight: 600
    }

    .filter-horizontal-scroll {
        display: flex;
        flex-wrap: wrap;
    }

    .filter-options {
        background-color: white;
        border-radius: 1rem;
        padding: 0 5px;
        position: relative;
        display: inline-block;
        cursor: pointer;
    }

    .tooltip-content {
        display: none;
        position: absolute;
        top: -40px;
        left: 0;
        background: #000000cc;
        padding: 5px 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        z-index: 1000;
        border-radius: 10px
    }

    .tooltip-content p {
        color: white !important;
    }

    .filter-options:hover .tooltip-content {
        display: block;
    }

    .custom-select:focus {
        outline: none;
    }

    blockquote {
        cursor: pointer;
    }

    .copyCard {
        position: relative;
    }

    .copyMessage {
        display: none;
        position: absolute;
        bottom: 10px;
        left: 0;
        right: 0;
        background: #333;
    }
</style>
@section('content')
    <section class="gradient">
        <div class="pt-13 container px-md-0 px-6">
            <div class="my-1"> <a href="{{ route('index') }}">Home / Landing Page</a> </div>
            {{-- card --}}  
            <div class=" row p-0 py-3 border rounded-2">
                <div class="col-xl-12">
                    <div class="row p-0 rounded-2">
                        @if ($event->getFirstMediaUrl('image'))
                            <div class="col-lg-3">
                                <img style="height: 15vh" class="w-100 object-fit-cover rounded"
                                    src="{{ $event->getFirstMediaUrl('image') }}" alt="">
                            </div>
                        @endif
                        <div class="{{ $event->getFirstMediaUrl('image') ? 'col-lg-9' : 'col-lg-12' }}">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h1 class="text-dark fs-30">Happy {{ $event->name }}! 🎇</h1>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    @php $event->increment('view_count'); @endphp
                                    <div>
                                        <i class="uil uil-eye fs-20"></i>
                                    </div>
                                    <div>
                                        {{ formatNumber($event->view_count) }} views
                                    </div>
                                </div>
                            </div>
                            <p class="fs-18 lh-xs mb-0"> {!!  $event->description !!} ✨</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-1">
                <div class="col-md-12 col-lg-12 col-xs-12 p-0">
                    <div class="card-container mb-10">
                        @include('site.landing-page.include.content')
                    </div>
                </div>
            </div>
    </section>
    {{-- modal --}}
    @include('site.festive.modal.share')
    @push('script')
        <script>
            function openShareModal(content) {
                document.getElementById('linkInput').innerText = content;
                var myModal = new bootstrap.Modal(document.getElementById('ShareModel'), {});
                myModal.show();
            }
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('[data-bs-toggle="modal"]').forEach(function(trigger) {
                    trigger.addEventListener('click', function() {
                        var link = this.getAttribute('data-link');
                        var modal = new bootstrap.Modal(document.getElementById('ShareModel'), {});
                        document.getElementById('linkInput').value = link;
                        modal.show();
                    });
                });
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const blockquotes = document.querySelectorAll('.copyCard');

                blockquotes.forEach(blockquote => {
                    blockquote.addEventListener('click', function() {
                        const text = this.querySelector('p').innerText;
                        const message = this.querySelector('.copyMessage');

                        const textarea = document.createElement('textarea');
                        textarea.value = text;
                        document.body.appendChild(textarea);
                        textarea.select();
                        document.execCommand('copy');
                        document.body.removeChild(textarea);

                        message.style.display = 'block';

                        setTimeout(function() {
                            message.style.display = 'none';
                        }, 1000);
                    });
                });
            });
        </script>
        <script>
            $(document).ready(function() {
                $('.cards1').removeClass('d-none');
                $('.shimmer-content').addClass('d-none');
                $('.content-found').removeClass('d-none');
            });
        </script>
    @endpush
@endsection
