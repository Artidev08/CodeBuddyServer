<style>
    .copyMessage {
        position: absolute;
        bottom: 10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: rgba(0, 0, 0, 0.7);
        color: white;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
    }

    blockquote {
        height: 15rem;
    }

    /* .container {
        padding: 1rem 0 !important;
    } */

    @media (max-width: 600px) {
        blockquote {
            height: 15rem;
        }
    }

    @media (min-width: 600px) and (max-width: 1024px) {
        blockquote {
            height: 12.5rem;
        }
    }
</style>

{{-- banner --}}
@include('site.festive.include.banner')
@if ($contents->count() > 0)
    <div class="shimmer-content">
        @include('site.festive.include.shimmer')
    </div>
@endif
<div class="fs-15 mb-1 text-muted content-found d-none">
    <strong>{{ $contents->count() }}</strong> content found
</div>
{{-- Content Cards Section --}}
{{-- <div class="row">
    @foreach ($contents as $content)
        <div class="col-md-4 mb-3" data-content="{{ addslashes($content->description) }}">
            <div class="cards1 border rounded-2 d-none" >
                <div class="d-flex gap-3 px-6 py-4">
                    <figure class="mb-0">
                        <blockquote class="icon fs-lg copyCard">
                            <h2 class="card-content fs-25 text-dark m-1">{{ $content->description }}</h2>
                            <div class="text-white fs-15 w-15 text-center rounded copyMessage">Text copied</div>
                        </blockquote>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <figcaption class="blockquote-footer mb-0 text-muted">Admin</figcaption>
                            <div class="d-flex justify-content-end">
                                <a href="javascript:void(0);" class="text-dark d-flex align-items-center" title="Share with others" onclick="openShareModal('{{ addslashes($content->description) }}')">
                                    <span class="fs-20"><i class="uil uil-share-alt"></i></span>
                                </a>
                            </div>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
    @endforeach
</div> --}}
@if ($contents->count() > 0)
    <div class="row" id="content-container">
        @foreach ($contents as $index => $content)
            <div class="col-lg-4 col-md-6 col-12 mb-3 content-item px-2"
                data-content="{{ addslashes($content->description) }}"
                style="display: {{ $index < 6 ? 'block' : 'none' }}">
                <div class="cards1 border rounded-2">
                    <div class="d-flex gap-3 px-6 py-4">
                        <figure class="mb-0 copyCard">
                            <blockquote class="icon fs-lg"
                                onclick="copyContent('{{ addslashes($content->description) }}', this)">
                                <h2 class="card-content fs-25 text-dark m-1">{{ $content->description }}</h2>
                                <div class="text-white fs-15 w-15 text-center rounded copyMessage"
                                    style="display: none;">Text copied</div>
                            </blockquote>
                            <hr class="my-1">
                            <div class="d-flex justify-content-between align-items-center">
                                <figcaption class="blockquote-footer mb-0 text-muted">
                                    {{ $content->agent->full_name ?? '' }}
                                </figcaption>
                                <div class="d-flex justify-content-end">
                                    <a href="javascript:void(0);" class="text-dark d-flex align-items-center"
                                        title="Share with others"
                                        onclick="openShareModal('{{ $content->id }}','{{ addslashes($content->description) }}')">
                                        <div class="d-flex align-items-center">
                                            <span class="fs-20 me-1"><i class="uil uil-share-alt"></i></span>
                                            <span>{{ formatNumber($content->share_count) }}</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </figure>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div id="loading-indicator" class="text-center" style="display: none;">
        <div class="row g-4">
            <div class="col-md-4">
                <div class="shimmer-box shimmer">
                    <div class="sh-img"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="shimmer-box shimmer">
                    <div class="sh-img"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="shimmer-box shimmer">
                    <div class="sh-img"></div>
                </div>
            </div>
        </div>
    </div>
    <div id="load-marker" style="height: 20px; width: 100%;"></div>
@else
    <div class="row  cards1" style="min-height: 50vh;">
        <div class="col-md-12 d-flex flex-column justify-content-center align-items-center text-center">
            <!-- Main message -->
            <h1 style="font-size: 2.5rem; color: #333; margin-bottom: 10px;">Uh-oh, no greetings found!</h1>
            <!-- Subtext message -->
            <p style="font-size: 1.2rem; color: #777;">Our dedicated team of professional editors is working diligently
                to include a wide range of greetings from around the world.

                Check Out Other Choices.
            </p>
            <!-- Optional: A call to action button -->
            <a href="/" class="btn  btn-primary">Go Home</a>
        </div>
    </div>

@endif


<script>
    function copyContent(content, element) {
        navigator.clipboard.writeText(content).then(function() {
            const copyMessage = element.querySelector('.copyMessage');
            copyMessage.style.display = 'block';

            setTimeout(() => {
                copyMessage.style.display = 'none';
            }, 1000);
        }).catch(function(error) {
            console.error('Failed to copy text: ', error);
        });
    }
</script>
