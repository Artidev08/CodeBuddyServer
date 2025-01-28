<div class="card-body">
    <div class="table-controller mb-2">
        <div>
            @if (getSetting('paragraph_content_table_record_limit', @$setting))
                <label for=""> @lang('admin/ui.show')
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                            <option
                                value="{{ @$limit }}"{{ @$paragraphContents->perPage() == @$limit ? 'selected' : '' }}>
                                {{ @$limit }}</option>
                        @endforeach
                    </select>
                    @lang('admin/ui.entry')
                </label>
        </div>
        @endif
        <div class="d-flex justify-content-between">
            @if (getSetting('paragraph_content_table_search', @$setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                    validation="permission_name" value="{{ request()->get('search') }}" />
            @endif
            @if (getSetting('paragraph_content_table_filter', @$setting))
                <button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"><i
                        class="ik ik-filter ik-lg"></i></button>
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <table id="paragraphTable" class="table">
            <thead>
                <tr>
                    <th width="8%" class="">
                        @if (getSetting('paragraph_content_checkbox', @$setting))
                            <input type="checkbox" class="mr-2 allChecked  text-center" name="id" value="">
                        @endif
                        @lang('admin/ui.sNo')
                    </th>

                    <th width="8%" class="">
                        @if (getSetting('paragraph_content_checkbox', @$setting))
                            @lang('admin/ui.actions')
                    </th>
                    @endif
                    <th width="10%" class="">@lang('admin/ui.#') <div class="table-div"><i
                                class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik ik-arrow-down desc"
                                data-val="id"></i></div>
                    </th>
                    <th width="40%"> @lang('admin/ui.code') </th>
                    <th width="10%"> @lang('admin/ui.type') </th>
                    <th width="10%"> @lang('admin/ui.group') </th>
                    <th width="10%"><i class="icon-head" data-title="Created At"><i
                                class="fa-regular fa-clock"></i></i>
                        <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                                class="ik ik ik-arrow-down desc" data-val="created_at"></i></div>
                    </th>
                </tr>
            </thead>
            <tbody class="">
                @foreach (@$paragraphContents as $paragraphContent)
                    <tr id="{{ @$paragraphContent->id }}">
                        <td>
                            @if (getSetting('paragraph_content_bulk_upload', @$setting) || getSetting('paragraph_content_bulk_delete', @$setting))
                                <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                    value="{{ @$paragraphContent->id }}">
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
                                    @if ($permissions->contains('edit_paragraph_content'))
                                        <li class="dropdown-item p-0">
                                            <a href="{{ route('panel.admin.paragraph-contents.edit', secureToken($paragraphContent->id)) }}"
                                                title="Edit Paragraph Content" class="btn btn-sm"><i
                                                    class="ik ik-edit mr-2"> </i> Edit</a>
                                        </li>
                                    @endif

                                    <li class="dropdown-item p-0">
                                        @if ($paragraphContent->is_permanent != 1)
                                            <hr class="m-1 b-0">
                                            <a href="{{ route('panel.admin.paragraph-contents.destroy', secureToken($paragraphContent->id)) }}"
                                                title="Delete Paragraph Content"
                                                class="btn btn-sm delete-item text-danger"><i class="ik ik-trash mr-2">
                                                </i> Delete</a>
                                        @endif
                                    </li>

                                </ul>
                            </div>
                        </td>
                        <td class="">
                            {{ @$paragraphContent->getPrefix() }}</td>
                        <td>{{ @$paragraphContent->code ?? '--' }}</td>
                        <td><span
                                class="badge badge-{{ @\App\Models\ParagraphContent::TYPES[@$paragraphContent->type]['color'] }}">{{ @\App\Models\ParagraphContent::TYPES[@$paragraphContent->type]['label'] ?? '--' }}</span>
                        </td>
                        <td>{{ @$paragraphContent->group ?? '--' }}</td>
                        <td>{{ @$paragraphContent->formatted_created_at ?? '--' }}</td>
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
                {{ @$paragraphContents->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$paragraphContents->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                        @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $paragraphContents->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $paragraphContents->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
