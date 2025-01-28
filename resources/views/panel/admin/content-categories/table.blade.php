<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th width="20%" class="no-export">
                    <input type="checkbox" class="mr-2 " id="selectall" value="">
                    @lang('admin/ui.actions')
                </th>
                <th width="20%" class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th width="20%"> Name <div class="table-div"><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div>
            </th>
            <th width="20%" class="col_3">
                Prompt
            </th>
            {{--  <th class="col_3"> Is Featured
                <div class="table-div"><i class="ik ik-arrow-up  asc " data-val="is_featured"></i><i
                        class="ik ik ik-arrow-down desc" data-val="is_featured"></i></div>
            </th> --}}
            <th width="20%"> Icon </th>
            {{-- <th class="col_5"> Description
            </th> --}}
            <th width="20%" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($contentCategories->count() > 0)
            @foreach ($contentCategories as $contentCategory)
                <tr id="{{ $contentCategory->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $contentCategory->id }}">
                                @if ($permissions->contains('edit_content_category') || $permissions->contains('delete_content_category'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        {{-- @if ($permissions->contains('view_content_categories'))
                                            <a href="{{ route('panel.admin.content-categories.show', secureToken($contentCategory->id)) }}"
                                                title="Edit Content Category" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif --}}
                                        @if ($permissions->contains('edit_content_category'))
                                            <a href="{{ route('panel.admin.content-categories.edit', secureToken($contentCategory->id)) }}"
                                                title="Edit Content Category" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('delete_content_category'))
                                            <a href="{{ route('panel.admin.content-categories.destroy', secureToken($contentCategory->id)) }}"
                                                title="Delete Content Category"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"><a class="table-link"
                                href="{{ route('panel.admin.content-categories.show', secureToken(@$contentCategory->id)) }}">
                                {{ @$contentCategory->getPrefix() }} </a></td>
                    @endif
                    <td class="col_1">
                        {{ $contentCategory->name }}</td>
                    <td class="col_3  is_published-{{ @$contentCategory->id }} "
                        data-status="{{ @$contentCategory->is_ai_enabled }}">
                        <span
                            class="badge badge-{{ @$contentCategory->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$contentCategory->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                    </td>
                    {{--  <td class="col_4"><input type="checkbox" class="switch-input js-switch isboolrec-update"
                            name="is_featured" @if ($contentCategory->is_featured) checked @endif
                            value='{{ $contentCategory->id }}'></td> --}}
                    <td class="col_4">
                        {{ $contentCategory->icon }}</td>
                    {{-- <td class="col_5">
                        {{ $contentCategory->description }}</td> --}}
                    <td class="col_5">{{ $contentCategory->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
