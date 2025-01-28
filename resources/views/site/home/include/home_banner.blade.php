<style>
    body {
        font-family: 'Source Sans Pro', sans-serif;
    }

    .image-banner {
        width: 100%;
        height: 17rem;
        background: url('{{ asset('site/assets/img/banner.jpg') }}') no-repeat center center;
        background-size: cover;
        position: relative;
        overflow: hidden;
    }

    .filters p {
        background: #abcdd7;
        padding: 5px 20px;
        border-radius: 1rem;
        font-weight: 600;
        cursor: pointer;
        margin: 0 5px;
    }

    .filter-options {
        margin: 4px;
        background: #ffffffd6;
        padding: 3px 15px;
        border-radius: 1rem;
        font-weight: 600;
        cursor: pointer;
        color: black;
        border: 1px solid transparent;
        transition: color 0.3s ease, border-color 0.3s ease;
        user-select: none;
    }

    .filter-options.active {
        color: white;
        background-color: #467383;
    }

    .banner-img {
        padding-top: 4rem;
    }
</style>
@php
    $evenbanners = \App\Models\Event::where('is_featured', 1)->where('date', '>=', now())->take(5)->get();
    // $contentbanner = \App\Models\Content::where('occasion_id', @$evenbanner->occasion_id)
    //     ->where('event_id', @$evenbanner->id)
    //     ->latest()
    //     ->take(1)
    //     ->get();
    // @$filters = getFilterItem(@$contentbanner);
    // @$filterOptions = getFilterOption(@$filters);
@endphp
{{-- <section class="wrapper image-wrapper bg-cover bg-image bg-xs-none" > --}}
<section class="wrapper bg-cover">
    <div class="container banner-img">
        <div class="banner">
            <a href="#">
                <img src="{{ asset('site/assets/img/Black_Orange_Pizza_Mobile_Banner_Ads.gif') }}" 
                     alt="Banner Image" 
                     style="    width: auto;
    height: 105px;
    text-align: center;
    margin: 0 auto;
    display: block;
">
            </a>
        </div>
    </div>
</section>



<script>
    document.querySelectorAll('.filter-options').forEach(function(span) {
        span.addEventListener('click', function() {
            document.querySelectorAll('.filter-options').forEach(function(s) {
                s.classList.remove('active');
            });
            this.classList.add('active');
        });
    });
</script>
