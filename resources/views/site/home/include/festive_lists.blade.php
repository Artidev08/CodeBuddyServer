<style>
    .position-relative {
        position: relative;
    }

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
        border-color: #a0d3f5;
        outline: none;
        box-shadow: 0 0 5px rgba(140, 197, 234, 0.5);
    }

    .tabs {
        cursor: pointer;
        gap: 1rem;
    }

    .tabs h6 {
        border: 1px solid black;
        padding: 0 15px;
        font-size: 0.8rem;
    }

    .active-tab {
        background-color: #323130;
        color: white !important;
        border: none;
    }
</style>

<section class="container gradient mb-9">
    <div class="row mt-md-5 mt-0">
        <div class="col-lg-9 col-md-12 col-sm-12">
            <div class="row">
                <div class="col-lg-8 col-md-12">
                        <h6 class="text-black fs-22">Top Picked {{ @$contentCategory->name }} by Occasion</h6>
                    <div class="row">
                        @foreach (@$occasions as $occasion)
                            @if (@$occasion->event->count() > 0 && @$occasion->getContent->count() > 0)
                                <div class="col-6">
                                        <h6 class="text-black">{{ @$occasion->name }}</h6>
                                    <div>
                                        @foreach (\App\Models\Event::where('occasion_id', @$occasion->id)->where('is_published', 1)->get() as $event)
                                            <a href="{{ route('event.index', [
                                                    'category' =>  @$contentCategory->slug,
                                                    'occasion' => @$occasion->slug,
                                                    'event' =>  @$event->slug ? $event->slug : '-'
                                                ]) }}">
                                                <p class="mb-1 w-24"> {{ @$event->icon .' '. @$event->name }}</p>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 gradient" >
                    <div class="d-flex tabs">
                        <h6 class="text-black active-tab" id="mostPopularTab" onclick="showContent('mostPopular')">Most
                            Popular</h6>
                        <h6 class="text-black" id="recentlyAddedTab" onclick="showContent('recentlyAdded')">
                            Recently Added</h6>
                    </div>
                    {{-- most popular --}}
                    <div id="mostPopularContent" class="short-topics">
                        @foreach (@$landingPagePopulars as $landingPagePopular)
                        <a href="{{route('landing.page',['slug' => $landingPagePopular->slug])}}" class="link"><p class="mb-1 text-black">✨  {{@$landingPagePopular->title}}</p></a>
                        @endforeach
                    </div>
                    {{-- recent add --}}
                    <div id="recentlyAddedContent" class="short-topics" style="display: none;">
                        @foreach (@$landingPageRecents as $landingPageRecent)
                        <a href="{{route('landing.page',['slug' => $landingPageRecent->slug])}}" class="link"><p class="mb-1 text-black"> 📅 {{@$landingPageRecent->title}}</p></a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12">
            <img class="w-100" src="{{ asset('site/assets/img/Black_and_White_Modern_Elegant_Mobile_Video.png') }}" alt="">
        </div>
    </div>
</section>

{{-- search animation placeholder --}}
<script>
    const placeholders = ["Ideas, topics & more..", "Thoughts & Festivals"];
    let index = 0;

    function changePlaceholder() {
        const searchInput = document.getElementById('animated-placeholder');
        searchInput.placeholder = placeholders[index];
        index = (index + 1) % placeholders.length;
    }

    changePlaceholder();
    setInterval(changePlaceholder, 2000);
</script>

{{-- tabs content --}}
<script>
    function showContent(tab) {
        document.getElementById('mostPopularContent').style.display = 'none';
        document.getElementById('recentlyAddedContent').style.display = 'none';

        document.getElementById('mostPopularTab').classList.remove('active-tab');
        document.getElementById('recentlyAddedTab').classList.remove('active-tab');

        if (tab === 'mostPopular') {
            document.getElementById('mostPopularContent').style.display = 'block';
            document.getElementById('mostPopularTab').classList.add('active-tab');
        } else if (tab === 'recentlyAdded') {
            document.getElementById('recentlyAddedContent').style.display = 'block';
            document.getElementById('recentlyAddedTab').classList.add('active-tab');
        }
    }
</script>
