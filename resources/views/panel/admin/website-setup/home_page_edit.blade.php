@extends('panel.admin.layouts.app')
@section('content')

<div class="row">
    <div class="col-xl-10 mx-auto">
        <h6 class="fw-600">{{ 'Home Page Settings' }}</h6>

        
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ 'Home Slider' }}</h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    {{ 'We have limited banner height to maintain UI. We had to crop from both left & right side in view for different devices to make it responsive. Before designing banner keep these points in mind.' }}
                </div>
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>{{ 'Photos & Links' }}</label>
                        <div class="home-slider-target">
                            <input type="hidden" name="types[]" value="home_slider_images">
                            <input type="hidden" name="types[]" value="home_slider_links">
                            @if ('home_slider_images' != null)
                                @foreach (json_decode('home_slider_images', true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div
                                                            class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ 'Browse' }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                                    <input type="hidden" name="types[]" value="home_slider_images">
                                                    <input type="hidden" name="home_slider_images[]"
                                                        class="selected-files"
                                                        value="{{ json_decode('home_slider_images', true)[@$key] }}">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="home_slider_links">
                                                <input type="text" pattern="[a-zA-Z]+.*"
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control"
                                                    placeholder="http://" name="home_slider_links[]"
                                                    value="{{ json_decode('home_slider_links', true)[@$key] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                            data-content= "" data-target=".home-slider-target">{{ 'Add New' }}
                        </button>

                        <div class="row gutters-5">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ 'Browse' }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                        <input type="hidden" name="types[]" value="home_slider_images">
                                        <input type="hidden" name="home_slider_images[]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="home_slider_links">
                                    <input type="text"  pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control" placeholder="http://" name="home_slider_links[]">
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <div class="form-group">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Home Banner 1 --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ 'Home Banner 1 (Max 3)' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>{{ 'Banner & Links' }}</label>
                        <div class="home-banner1-target">
                            <input type="hidden" name="types[]" value="home_banner1_images">
                            <input type="hidden" name="types[]" value="home_banner1_links">
                            @if ('home_banner1_images' != null)
                                @foreach (json_decode('home_banner1_images', true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div
                                                            class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ 'Browse' }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                                    <input type="hidden" name="types[]" value="home_banner1_images">
                                                    <input type="hidden" name="home_banner1_images[]"
                                                        class="selected-files"
                                                        value="{{ json_decode('home_banner1_images', true)[@$key] }}">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="home_banner1_links">
                                                <input type="text" pattern="[a-zA-Z]+.*"
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control"
                                                    placeholder="http://" name="home_banner1_links[]"
                                                    value="{{ json_decode('home_banner1_links', true)[@$key] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                            data-content="" data-target=".home-banner1-target">
                            {{ 'Add New' }}
                        </button>
                        <div class="row gutters-5">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ 'Browse' }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                        <input type="hidden" name="types[]" value="home_banner1_images">
                                        <input type="hidden" name="home_banner1_images[]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="home_banner1_links">
                                    <input type="text"  pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control" placeholder="http://" name="home_banner1_links[]">
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <div class="form-group">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                            
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Home Banner 2 --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ 'Home Banner 2 (Max 3)' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>{{ 'Banner & Links' }}</label>
                        <div class="home-banner2-target">
                            <input type="hidden" name="types[]" value="home_banner2_images">
                            <input type="hidden" name="types[]" value="home_banner2_links">
                            @if ('home_banner2_images' != null)
                                @foreach (json_decode('home_banner2_images', true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div
                                                            class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ 'Browse' }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                                    <input type="hidden" name="types[]" value="home_banner2_images">
                                                    <input type="hidden" name="home_banner2_images[]"
                                                        class="selected-files"
                                                        value="{{ json_decode('home_banner2_images', true)[@$key] }}">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="home_banner2_links">
                                                <input type="text" pattern="[a-zA-Z]+.*"
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control"
                                                    placeholder="http://" name="home_banner2_links[]"
                                                    value="{{ json_decode('home_banner2_links', true)[@$key] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                            data-content="" data-target=".home-banner2-target">
                            {{ 'Add New' }}
                        </button>
                        <div class="row gutters-5">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ 'Browse' }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                        <input type="hidden" name="types[]" value="home_banner2_images">
                                        <input type="hidden" name="home_banner2_images[]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="home_banner2_links">
                                    <input type="text"  pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control" placeholder="http://" name="home_banner2_links[]">
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <div class="form-group">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Home categories --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ 'Home Categories' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>{{ 'Categories' }}</label>
                        <div class="home-categories-target">
                            <input type="hidden" name="types[]" value="home_categories">
                            @if ('home_categories' != null)
                                @foreach (json_decode('home_categories', true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col">
                                            <div class="form-group">
                                                <select class="form-control aiz-selectpicker" name="home_categories[]"
                                                    data-live-search="true" data-selected={{ @$value }}
                                                    required>
                                                    @foreach (\App\Category::where('parent_id', 0)->with('childrenCategories')->get() as $category)
                                                        <option value="{{ @$category->id }}">
                                                            {{ @$category->getTranslation('name') }}</option>
                                                        @foreach (@$category->childrenCategories as $childCategory)
                                                            @include('categories.child_category', [
                                                                'child_category' => $childCategory,
                                                            ])
                                                        @endforeach
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <button type="button"
                                                class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                data-toggle="remove-parent" data-parent=".row">
                                                <i class="las la-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                            data-content="" data-target=".home-categories-target">
                            {{ 'Add New' }}
                        </button>
                            
                            <div class="row gutters-5">
                            <div class="col">
                                <div class="form-group">
                                    <select class="form-control aiz-selectpicker" name="home_categories[]" data-live-search="true" required>
                                        @foreach (\App\Category::all() as $key => $category)
                                            <option value="{{ @$category->id }}">{{ @$category->getTranslation('name') }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                    <i class="las la-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>


        {{-- Home Banner 3 --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ 'Home Banner 3 (Max 3)' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>{{ 'Banner & Links' }}</label>
                        <div class="home-banner3-target">
                            <input type="hidden" name="types[]" value="home_banner3_images">
                            <input type="hidden" name="types[]" value="home_banner3_links">
                            @if ('home_banner3_images' != null)
                                @foreach (json_decode('home_banner3_images', true) as $key => $value)
                                    <div class="row gutters-5">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <div class="input-group" data-toggle="aizuploader" data-type="image">
                                                    <div class="input-group-prepend">
                                                        <div
                                                            class="input-group-text bg-soft-secondary font-weight-medium">
                                                            {{ 'Browse' }}</div>
                                                    </div>
                                                    <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                                    <input type="hidden" name="types[]" value="home_banner3_images">
                                                    <input type="hidden" name="home_banner3_images[]"
                                                        class="selected-files"
                                                        value="{{ json_decode('home_banner3_images', true)[@$key] }}">
                                                </div>
                                                <div class="file-preview box sm">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md">
                                            <div class="form-group">
                                                <input type="hidden" name="types[]" value="home_banner3_links">
                                                <input type="text" pattern="[a-zA-Z]+.*"
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."
                                                    title="Please enter first letter alphabet and at least one alphabet character is required."class="form-control"
                                                    placeholder="http://" name="home_banner3_links[]"
                                                    value="{{ json_decode('home_banner3_links', true)[@$key] }}">
                                            </div>
                                        </div>
                                        <div class="col-md-auto">
                                            <div class="form-group">
                                                <button type="button"
                                                    class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger"
                                                    data-toggle="remove-parent" data-parent=".row">
                                                    <i class="las la-times"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <button type="button" class="btn btn-soft-secondary btn-sm" data-toggle="add-more"
                            data-content="" data-target=".home-banner3-target">
                            {{ 'Add New' }}
                        </button>
                        <div class="row gutters-5">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <div class="input-group" data-toggle="aizuploader" data-type="image">
                                        <div class="input-group-prepend">
                                            <div class="input-group-text bg-soft-secondary font-weight-medium">{{ 'Browse' }}</div>
                                        </div>
                                        <div class="form-control file-amount">{{ 'Choose File' }}</div>
                                        <input type="hidden" name="types[]" value="home_banner3_images">
                                        <input type="hidden" name="home_banner3_images[]" class="selected-files">
                                    </div>
                                    <div class="file-preview box sm">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <div class="form-group">
                                    <input type="hidden" name="types[]" value="home_banner3_links">
                                    <input type="text" pattern="[a-zA-Z]+.*" title="Please enter first letter alphabet and at least one alphabet character is required." title="Please enter first letter alphabet and at least one alphabet character is required." class="form-control" placeholder="http://" name="home_banner3_links[]">
                                </div>
                            </div>
                            <div class="col-md-auto">
                                <div class="form-group">
                                    <button type="button" class="mt-1 btn btn-icon btn-circle btn-sm btn-soft-danger" data-toggle="remove-parent" data-parent=".row">
                                        <i class="las la-times"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Top 10 --}}
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">{{ 'Top 10' }}</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('business_settings.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label">{{ 'Top Categories (Max 10)' }}</label>
                        <div class="col-md-10">
                            <input type="hidden" name="types[]" value="top10_categories">
                            <select name="top10_categories[]" class="form-control aiz-selectpicker" multiple
                                data-max-options="10" data-live-search="true" data-selected={{ 'top10_categories' }}
                                required>
                                @foreach (\App\Category::where('parent_id', 0)->with('childrenCategories')->get() as $category)
                                    <option value="{{ @$category->id }}">{{ @$category->getTranslation('name') }}
                                    </option>
                                    @foreach (@$category->childrenCategories as $childCategory)
                                        @include('categories.child_category', [
                                            'child_category' => $childCategory,
                                        ])
                                    @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2 col-from-label">{{ 'Top Brands (Max 10)' }}</label>
                        <div class="col-md-10">
                            <input type="hidden" name="types[]" value="top10_brands">
                            <select name="top10_brands[]" class="form-control aiz-selectpicker" multiple
                                data-max-options="10" data-live-search="true" required>
                                @foreach (\App\Brand::all() as $key => $brand)
                                    <option value="{{ @$brand->id }}"
                                        @if (in_array(@$brand->id, json_decode('top10_brands'))) selected @endif>
                                        {{ @$brand->getTranslation('name') ?? '--' }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="btn btn-primary">{{ 'Update' }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    {{-- START JS HELPERS INIT --}}
    <script type="text/javascript">
        $(document).ready(function() {
            AIZ.plugins.bootstrapSelect('refresh');
        });
    </script>
    {{-- END JS HELPERS INIT --}}
@endpush
