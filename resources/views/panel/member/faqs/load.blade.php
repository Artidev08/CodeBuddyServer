<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            @if (getSetting('faq_activation_table_record_limit',@$setting))
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$faqs->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')

            </label>
            @endif
        </div>
        <div class="d-flex justify-content-between">
            @if (getSetting('faq_activation_table_search',@$setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                validation="permission_name" value="{{ request()->get('search') }}" />
            @endif
        @if (getSetting('faq_activation_table_filter',@$setting))
            <button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"><i class="ik ik-filter ik-lg"></i> </button>
        @endif
        </div>
    </div>
    <div class="table-responsive">
        <table id="faqTable" class="table">
            <thead>
                <tr>
                    <th width="8%">
                        @if (getSetting('faq_activation_checkbox',@$setting))
                        <input type="checkbox" class="mr-2 allChecked " name="id"
                            value="">@endif @lang('admin/ui.sNo') </th>

                    @if (getSetting('faq_activation_checkbox',@$setting))
                    <th width="10%"> @lang('admin/ui.actions') </th>
                            @endif
                    <th width="10%">@lang('admin/ui.#')
                        <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                                class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                    </th>
                    <th width="40%"> @lang('admin/ui.question') </th>
                    <th width="10%"> @lang('admin/ui.category') </th>
                    <th width="10%"> @lang('admin/ui.visibility') </th>
            
                        <th width="10%"><i class="icon-head" data-title="Created At"><i
                            class="fa-regular fa-clock"></i></i>
                    <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="created_at"></i><i
                            class="ik ik ik-arrow-down desc" data-val="created_at"></i></div>
                </th>
                </tr>
            </thead>
            <tbody class="no-data">
                @foreach (@$faqs as $faq)
                    <tr id="{{ @$faq->id }}">
                        <td>
                            @if (getSetting('faq_activation_bulk_status_update',@$setting) || getSetting ('faq_activation_bulk_upload',@$setting) || getSetting ('faq_activation_bulk_delete',@$setting))
                                <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                    value="{{ @$faq->id ?? '' }}">
                                    @endif
                                    {{ @$loop->iteration }}
                        </td>
                        <td>
                            <div class="dropdown">

                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                     @lang('admin/ui.actions')
                                </button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                    @if ($permissions->contains('edit_faq'))
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.faqs.edit', secureToken($faq->id ?? '')) }}"
                                                title="Edit Faq" class="btn btn-sm "><i
                                                    class="ik ik-edit mr-2"></i>Edit</a>
                                        </li>
                                    @endif
                                    <hr class="m-1 b-0">
                                    @if ($permissions->contains('delete_faq'))
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.faqs.destroy', secureToken($faq->id)) }}"
                                                title="Delete Faq" class="btn btn-sm delete-item text-danger fw-700"><i
                                                    class="ik ik-trash mr-2"></i> Delete</a></li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                        <td> {{ @$faq->getPrefix() }}</td>
                        <td>{{ @$faq->title ?? '--' }}</td>
                        <td>{{ @$faq->category->name ?? '--' }}</td>
                        <td class="is_published-{{ @$faq->id }}" data-status="{{ @$faq->is_published }}">
                            @if (@$faq->is_published == 1)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-danger">Unpublished</span>
                            @endif
                        </td>
                        <td>{{ @$faq->created_at ?? '--' }}</td>
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
                {{ @$faqs->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$faqs->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to') :
                    </div>
                       <div class="input-group w-50">
                            <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                                value="{{ $faqs->currentPage() ?? '' }}">
                            <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $faqs->lastPage() }}</div>
                        </div>
                </label>
            @endif
        </div>
    </div>
</div>
