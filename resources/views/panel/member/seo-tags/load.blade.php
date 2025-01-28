<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                @if (getSetting('seo_tags_table_record_limit',@$setting))
                <label for=""> @lang('admin/ui.show')
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}" {{ @$seoTags->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                        @endforeach
                    </select>
                    @lang('admin/ui.entry')
                </label>
            </div>
            @endif
            @if (getSetting('seo_tags_table_excel_export',@$setting))
            <div class="d-flex justify-content-between">
                <button type="button" id="export_button" class="btn btn-light btn-sm">Excel</button>
            </div>
            @endif
        </div>
        <div class="d-flex justify-content-between">
            <div>
                @if (getSetting('seo_tags_table_search',@$setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                    validation="permission_name" value="{{ request()->get('search') }}" />
                @endif
            </div>
            @if (getSetting('seo_tags_table_filter',@$setting))
            <button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"><i
                    class="ik ik-filter ik-lg"></i> </button>
            @endif
        </div>

    </div>
    <div class="table-responsive">
        <table id="support-table" class="table">
            <thead>
                <tr>
                    <th width="10%" class="">
                        @if (getSetting('seo_tags_checkbox',@$setting))
                        <input type="checkbox" class="mr-2 allChecked " name="id" value="">@endif @lang('admin/ui.sNo')
                    </th>

                    @if (getSetting('seo_tags_checkbox',@$setting))
                    <th width="10%" class=" no-export">@lang('admin/ui.actions') </th>
                    @endif
                    <th width="10%" class="col_1">@lang('admin/ui.#')
                        <div class="table-div">
                            <i class="ik ik-arrow-up  asc" data-val="id"></i><i class="ik ik ik-arrow-down desc"
                            data-val="id"></i>
                        </div>
                    </th>
                    <th width="25%" class="col_3"> @lang('admin/ui.code') </th>
                    <th width="30%" class="col_4"> @lang('admin/ui.title') <div class="table-div"><i class="ik ik-arrow-up  asc"
                                data-val="title"></i><i class="ik ik ik-arrow-down desc" data-val="title"></i></div>
                    </th>
                    <th width="15%" class="col_5"> @lang('admin/ui.updated') <div class="table-div"><i class="ik ik-arrow-up  asc"
                                data-val="updated_at"></i><i class="ik ik ik-arrow-down desc" data-val="updated_at"></i>
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody class="no-data">
                @foreach (@$seoTags as $seoTag)
                <tr id="{{ @$seoTag->id ?? '' }}">
                    <td>
                        @if (getSetting('seo_tags_checkbox',@$setting))
                        <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                            value="{{ @$seoTag->id ?? '' }}">
                        @endif
                        {{ @$loop->iteration }}
                    </td>
                    <td class="no-export">

                        <div class="dropdown d-flex">

                            <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                @lang('admin/ui.actions')
                            </button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                @if ($permissions->contains('edit_seo_tag'))
                                <li class="dropdown-item p-0"><a
                                        href="{{ route('panel.admin.seo-tags.edit', secureToken(@$seoTag->id ?? '')) }}"
                                        title="Edit SEO Tag" class="btn btn-sm"><i class="ik ik-edit mr-2"> </i>Edit
                                </li></a></li>
                                @endif
                                @if ($permissions->contains('delete_seo_tag'))
                                @if (@$seoTag->is_permanent != 1)
                                <hr class="m-1 b-0 ">
                                <li class="dropdown-item p-0"><a
                                        href="{{ route('panel.admin.seo-tags.destroy', secureToken(@$seoTag->id ?? '')) }}"
                                        title="Delete SEO Tag" class="btn btn-sm delete-item text-danger"><i
                                            class="ik ik-trash"></i>Delete</a></li>
                                @endif
                                @endif
                            </ul>
                        </div>
                    </td>
                    <td class="col_1">{{ @$seoTag->getPrefix() }}</td>
                    <td class="col_3">{{ @$seoTag->code ?? '--' }}</td>
                    <td class="col_4">{{ @$seoTag->title ?? '--' }}</td>
                    <td class="col_5">{{ @$seoTag->formatted_updated_at ?? '--' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ @$seoTags->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$seoTags->lastPage() > 1)
            <label class="d-flex justify-content-end mobile-justify-center" for="">
                <div class="mr-2 pt-2 ">
                    @lang('admin/ui.jump_to') :
                </div>
                <div class="input-group w-50">
                    <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                        value="{{ $seoTags->currentPage() ?? '' }}">
                    <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $seoTags->lastPage() }}</div>
                </div>
            </label>
            @endif
        </div>
    </div>
</div>