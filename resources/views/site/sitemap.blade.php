@extends('layouts.app')

@section('meta_data')
    @php
        $meta_title = @$metas->title ?? 'Sitemap';
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
     body {
        font-family: 'Source Sans Pro', sans-serif !important;
    }
    .sitemap-list li a {
      font-size: 14px !important;
      color: rgb(99, 99, 99);
    }
    .header-shadow{
         box-shadow: 0px 10px 10px rgba(3, 4, 28, 0.1);
    }
    .header__sticky.header-sticky.header-sticky-blur {
         background-color: rgba(255, 255, 255, 0.8);
         backdrop-filter: blur(16px);
         box-shadow: 0px 10px 10px rgba(3, 4, 28, 0.1) !important;
    }
</style>
<section class="wrapper bg-light">
    <div class="container pt-16 pt-md-16 mb-5 text-center">
        <div class="row">
            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='%236c757d'/%3E%3C/svg%3E&#34;);" 
                aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a class="nav-link" href="{{ url('/') }}">Home</a></li>
                    <li class="breadcrumb-item active text-success" aria-current="page">Sitemap</li>
                </ol>
            </nav>
        </div>
        <!-- Sitemap Content -->
        <div class="row">
            <div class="col-lg-11 col-md-12 col-12 mx-auto">
                <div class="row">
                    <!-- Information Section -->
                    <div class="col-lg-4 col-md-4 col-12 mb-4">
                        <ul class="sitemap-list" style="list-style:none; padding: 0;">
                            <li><h4>Information</h4></li>
                            <li style="margin-bottom: 8px;"><a href="{{url('/')}}" style="padding: 5px 0;">Home</a></li>
                            <li style="margin-bottom: 8px;"><a href="{{route('about')}}" style="padding: 5px 0;">About</a></li>
                            <li style="margin-bottom: 8px;"><a href="{{route('contact')}}" style="padding: 5px 0;">Contact</a></li>
                            
                        </ul>
                    </div>
        
                    <!-- Other Information Section -->
                    <div class="col-lg-4 col-md-4 col-12 mb-4">
                        <ul class="sitemap-list" style="list-style:none; padding: 0;">
                            <li><h4>Other Information</h4></li>
                            <li style="margin-bottom: 8px;"><a href="{{url('page/privacy-and-policy')}}" style="padding: 5px 0;">Privacy Policy</a></li>
                            <li style="margin-bottom: 8px;"><a href="{{url('page/terms-and-condition')}}" style="padding: 5px 0;">Terms of Service</a></li>
                        </ul>
                    </div>
        
                    <!-- I am a Section -->
                    <div class="col-lg-4 col-md-4 col-12 mb-4">
                        <ul class="sitemap-list" style="list-style:none; padding: 0;">
                            <li>
                                <h4>Content</h4>
                            </li>
                            <!-- Content Category Dropdown -->
                            <li>
                                <a href="javascript:void(0);" onclick="toggleDropdown('contentDropdown')" style="text-decoration: none;">
                                    Content Categories
                                    <span style="float: right;">&#x25BC;</span> <!-- Down arrow icon -->
                                </a>
                                <ul id="contentDropdown" style="display: none; padding-left: 20px; list-style:none; padding: 0;">
                                    <!-- Occasion Dropdown -->
                                    <li>
                                        <a href="javascript:void(0);" onclick="toggleDropdown('occasionDropdown')" style="text-decoration: none;">
                                            Occasions
                                            <span style="float: right;">&#x25BC;</span> <!-- Down arrow icon -->
                                        </a>
                                        <ul id="occasionDropdown" style="display: none; padding-left: 20px; list-style:none; padding: 0;">
                                            <!-- Events under Occasion -->
                                            <li><a href="#">Events</a></li>
                                        </ul>
                                    </li>
                                    <!-- More categories can be added here -->
                                </ul>
                            </li>
                        </ul>
                    </div>
                    
        
                    <!-- Blogs Section (if applicable) -->
                    <div class="col-lg-4 col-md-4 col-12 mb-4">
                        {{-- Add blogs section here --}}
                    </div>
                </div>
            </div>
        </div>
        
        <!-- /.row -->
    </div>
    <!-- /.container -->
</section>
<script>
    function toggleDropdown(id) {
        var element = document.getElementById(id);
        if (element.style.display === "none") {
            element.style.display = "block";
        } else {
            element.style.display = "none";
        }
    }
</script>

@endsection
