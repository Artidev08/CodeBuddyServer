
<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            @if (getSetting('category_management_table_record_limit',@$setting))
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$categoryTypes->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')
            </label>
        </div>
        @endif
        <div class="d-flex justify-content-between">
            @if (getSetting('category_management_table_search',@$setting))
            <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
            validation="permission_name" value="{{ request()->get('search') }}" />
        @endif
        </div>
    </div>
    <div class="row no-gutters custom-bulk-section">
        @foreach ($categoryTypes as $categoryType)
            <div class="col-md-3">
                <label for="card-{{ @$categoryType->id ?? '' }}" class="w-100 p-1">
                    <div class="card-body p-0">
                        <a href="{{ route('panel.admin.categories.index', @$categoryType->id) ?? ''}}">
                            <div class="form-check" style="visibility: hidden;">

                                <input type="checkbox" class="form-check-input toggle-selected" name="id"
                                    id="card-{{ @$categoryType->id ?? '' }}" value="{{ @$categoryType->id ?? ''}}"
                                    onclick="countSelected($(this))">

                            </div>
                        </a>
                        <div class="dropdown justify-content-end d-flex position-absolute " style="right: 5px;top: 10%">
                            <button class="btn btn-link dropdown-toggle  mt-2" type="button" id="dropdownMenu1"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ik ik-more-vertical "></i>
                            </button>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                @if ($permissions->contains('edit_category'))
                                    <a href="{{ route('panel.admin.category-types.edit', secureToken(@$categoryType->id ?? '')) }}"
                                        title="Edit Category Group" class="dropdown-item"><i
                                            class="ik ik-edit mr-2"></i>
                                        Edit</a>
                                @endif
                                <a href="{{ route('panel.admin.categories.index', @$categoryType->id ?? '') }}"
                                    title="Manage Category Group" class="dropdown-item"><i
                                        class="fa-solid fa-list-check mr-2"></i> Manage</a>
                                @if ($permissions->contains('delete_category'))
                                    @if ($categoryType->is_permanent != 1)
                                        <hr class="m-1 b-0">
                                        <a href="{{ route('panel.admin.category-types.destroy', secureToken($categoryType->id ?? '')) }}"
                                            title="Delete Category Group"
                                            class="dropdown-item text-danger fw-800 delete-item"><i
                                                class="ik ik-trash mr-2"> </i> Delete</a>
                                    @endif
                                @endif
                            </div>
                        </div>

                        <a href="{{ route('panel.admin.categories.index', secureToken($categoryType->id ?? '')) }}"
                            class="">
                            <div class="custom-card border mb-2 ">
                                <div class="mb-0 ">
                                    <h6 class="mb-0 ">
                                        <b>{{ ucwords(str_replace('_', ' ', @$categoryType->name ?? '')) ?? '-' }}</b>
                                    </h6>
                                    @if (@$categoryType->categories->count() == 0)
                                        <span class="fw-600 mt-2 text-muted">
                                             @lang('admin/ui.no_entry')
                                        </span>
                                    @else
                                        <span class="fw-600 mt-2 text-muted">

                                            {{ @$categoryType->categories->count() }} @if (@$categoryType->categories->count() == 1)
                                                Entry
                                            @else
                                                Entries
                                            @endif

                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>

                </label>
            </div>
        @endforeach
    </div>
</div>

<div class="card-footer">
    <div class="row">
        <div class="col-lg-6 mt-2">
            <div class="pagination mobile-justify-center">
                {{ @$categoryTypes->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-6 pt-0 mb-4 mobile-mt-20">
            @if (@$categoryTypes->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $categoryTypes->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $categoryTypes->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
