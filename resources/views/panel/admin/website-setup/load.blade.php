<div class="card-body">
    <div class="table-controller mb-2">
        <div>
            @if (getSetting('pages_activation_table_record_limit',@$setting))
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$websitePages->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit ?? '--' }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')
            </label>
        </div>
        @endif
        <div class="d-flex justify-content-between">
            @if (getSetting('pages_activation_table_search',@$setting))
            <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
            validation="permission_name" value="{{ request()->get('search') }}" />
        @endif

        @if (getSetting('pages_activation_table_filter',@$setting))
            <button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"> <i class="ik ik-filter ik-lg"></i></button>
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <div class="table-responsive">
            <table id="page_table" class="table aiz-table mb-0">
                <thead>
                    <tr>
                        <th width="10%" class="">
                            @if (getSetting('pages_activation_checkbox',@$setting))
                            <input type="checkbox" class="mr-2 allChecked " name="id"
                                value="">
                                @endif
                                @lang('admin/ui.sNo')
                        </th>
                        <th width="10%" class="no-export"> @lang('admin/ui.actions')
                            <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                                    class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                        </th>
                        <th width="15%" class="no-export">@lang('admin/ui.#')</th>
                        <th width="30%" class="col_1"> @lang('admin/ui.name') </th>
                        <th width="20%" class="col_2"> @lang('admin/ui.visibility') </th>
                        <th width="15%" class="no-export"><i class="icon-head" data-title="Created At"><i
                            class="fa-regular fa-clock pl-30"></i><div class="table-div"><i class="ik ik-arrow-up  asc" data-val="created_at"></i><i
                                class="ik ik ik-arrow-down desc" data-val="created_at"></i></div></i>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if (@$websitePages->count() > 0)
                        @foreach (@$websitePages as $websitePage)
                            <tr id="{{ @$websitePage->id }}">
                                <td class="">
                                    @if (getSetting('pages_activation_checkbox',@$setting))
                                        <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                            value="{{ @$websitePage->id }}">
                                             @endif
                                             {{ @$loop->iteration }}
                                </td>
                                <td class="no-export">
                                    <div class="dropdown d-flex">

                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                             @lang('admin/ui.actions')
                                        </button>
                                        <ul class="dropdown-menu multi-level" role="menu"
                                            aria-labelledby="dropdownMenu">
                                            @if ($permissions->contains('show_page'))
                                                <li class="dropdown-item p-0"><a
                                                        href="{{ route('page.slug', $websitePage->slug) }}"
                                                        title="" class="btn btn-sm">Show</a></li>
                                            @endif
                                            @if ($permissions->contains('edit_page'))
                                                <li class="dropdown-item p-0"><a
                                                        href="{{ route('panel.admin.website-pages.edit', secureToken($websitePage->id)) }}"
                                                        title="Edit Website Page" class="btn btn-sm"><i
                                                            class="ik ik-edit mr-2"></i>Edit</a></li>
                                            @endif
                                            @if ($permissions->contains('delete_page'))
                                                @if (@$websitePage->is_permanent != 1)
                                                    <hr class="m-1 b-0">
                                                    <li class="dropdown-item p-0"><a
                                                            href="{{ route('panel.admin.website-pages.destroy', $websitePage->id) }}"
                                                            title="Delete Website Page"
                                                            class="btn btn-sm delete-item text-danger fw-700"><i
                                                                class="ik ik-trash"></i> Delete</a></li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                                <td class="col_1">{{ @$websitePage->getPrefix()}}</td>
                                <td class="col_1">{{ @$websitePage->title ?? '--' }}</td>
                                <td class="col_2"><span
                                        class="badge badge-{{ getPublishStatus(@$websitePage->status)['color'] }}">{{ getPublishStatus(@$websitePage->status)['name'] ?? '--' }}</span>
                                </td>
                                <td>{{ @$websitePage->formatted_created_at ?? '--' }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ @$websitePages->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$websitePages->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $websitePages->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $websitePages->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
