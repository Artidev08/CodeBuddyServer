<div class="shimmer-content">
    @include('site.landing-page.include.shimmer')
</div>
{{-- <div class="fs-15 mb-1 text-muted content-found d-none">
    <strong>1</strong> content found
</div> --}}
<div class="row" id="content-container">
        <div class="col-md-4 mb-3 content-item" data-content="{{ addslashes($contentLandingPage->description) }}">
            <div class="cards1 mt-2 border rounded-2 d-none">
                <div class="d-flex gap-3 px-6 py-4">
                    <figure class="mb-0">
                        <h6 class="m-0 p-0">{{ $contentLandingPage->title}}</h6>
                        <span class="m-0 p-0">{{$contentLandingPage->short_description}}</span>
                        <blockquote class="icon fs-lg copyCard">
                            <h2 class="card-content fs-25 text-dark ">{{ $contentLandingPage->description }}</h2>
                            <div class="text-white fs-15 w-15 text-center rounded copyMessage">Text copied</div>
                        </blockquote>
                        <hr class="my-1">
                        <div class="d-flex justify-content-between align-items-center">
                            <figcaption class="blockquote-footer mb-0 text-muted">Admin</figcaption>
                            <div class="d-flex justify-content-end">
                                <a href="javascript:void(0);" class="text-dark d-flex align-items-center"
                                    title="Share with others"
                                    onclick="openShareModal('{{ addslashes($contentLandingPage->description) }}')">
                                    <div class="d-flex align-items-center">
                                        <span class="fs-20 me-1"><i class="uil uil-share-alt"></i></span>
                                        <span>{{ formatNumber($contentLandingPage->share_count) }}</span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </figure>
                </div>
            </div>
        </div>
</div>

<div id="loading-indicator" class="text-center" style="display: none;">
    <p>Loading more content...</p>
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<div id="load-marker" style="height: 20px; width: 100%;"></div>
