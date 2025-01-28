<style>
    .filter-container {
        display: flex;
        justify-content: space-between;
        gap: 10px;
    }

    @media (max-width: 600px) {
        .filter-container {
            display: flex;
            flex-direction: column;
        }
    }
</style>

<div class=" row p-0 py-3 border rounded-2">
    <div class="col-xl-12">
        <div class="row p-0 rounded-2">
            @if (@$event->getLatestImageUrl() != null && @$event->getLatestImageUrl() != 'null')
                <div class="col-lg-3">
                    <img style="height: 20vh" class="w-100 object-fit-cover rounded"
                        src="{{ $event->getLatestImageUrl() }}" alt="">
                </div>
                <div class="col-lg-9">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h1 class="text-dark fs-30 mb-0">Explore {{ $contents->count() }} {{ $event->name }} {{ str_replace('/', '', @$categoryBreadcrumb) }} </h1>
                        </div>
                        <div class="d-flex align-items-center">
                            @php $event->increment('view_count'); @endphp
                            <div>
                                <i class="uil uil-eye fs-20"></i>
                            </div>
                            <div>
                                {{ formatNumber($event->view_count) }} views
                            </div>
                        </div>
                    </div>
                    <p class="fs-18 lh-xs mb-0 mt-1">{{ $event->short_description }} </p>
                </div>
            @else
                <div class="col-lg-12">
                    <div class="d-flex justify-content-between flex-md-row flex-column">
                        <div>
                            <h1 class="text-dark fs-30 mb-0">Explore {{ $contents->count() }} {{ $event->name }} {{ str_replace('/', '', @$categoryBreadcrumb) }} </h1>

                        </div>
                        <div class="d-flex align-items-center">
                            @php $event->increment('view_count'); @endphp
                            <div>
                                <i class="uil uil-eye fs-20"></i>
                            </div>
                            <div>
                                {{ formatNumber($event->view_count) }} views
                            </div>
                        </div>
                    </div>

                    <p class="fs-18 lh-xs mb-0 mt-1">{{ $event->short_description }} </p>
                    {{-- share --}}
                    <a href="javascript:void(0);" class="" title="Share with others" data-toggle="modal" data-target="#CardShareModel">
                        <div class="fs-22 float-end">
                            <i class="uil uil-share-alt"></i>
                        </div>
                    </a>


                </div>
            @endif
            <div class="col-md-12 col-lg-12 col-xs-12 mt-3">

                <div class="filter-container p-2 rounded-3 mb-0 border">
                    {{-- Filters Section --}}
                    @include('site.festive.partials.filters')

                    {{-- Search --}}
                    @include('site.festive.partials.search')
                </div>
            </div>
        </div>
    </div>
</div>

{{-- modal --}}
@include('site.festive.modal.cardShare')
