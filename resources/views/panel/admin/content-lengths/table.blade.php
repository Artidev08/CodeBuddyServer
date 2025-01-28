<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th class="no-export">
                    <input type="checkbox" class="mr-2 " id="selectall" value="">
                    @lang('admin/ui.actions')
                </th>
                <th class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th class="col_1"> Name
                <div class="table-div"><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div>
            </th>
            <th>Length</th>
            <th class="col_2"> Description
            </th>
            <th width="10%" class="col_3">
                Prompt
            </th>
            <th class="" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($contentLengths->count() > 0)
            @foreach ($contentLengths as $contentLength)
                <tr id="{{ $contentLength->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $contentLength->id }}">
                                @if ($permissions->contains('edit_content_length') || $permissions->contains('delete_content_length'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if ($permissions->contains('view_content_lengths'))
                                            <a href="{{ route('panel.admin.content-lengths.show', secureToken($contentLength->id)) }}"
                                                title="Edit Content Length" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('edit_content_length'))
                                            <a href="{{ route('panel.admin.content-lengths.edit', secureToken($contentLength->id)) }}"
                                                title="Edit Content Length" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('delete_content_length'))
                                            <a href="{{ route('panel.admin.content-lengths.destroy', secureToken($contentLength->id)) }}"
                                                title="Delete Content Length"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"> {{ $contentLength->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $contentLength->name }}</td>
                    <td> {{ $contentLength->length }}Chars</td>
                    <td class="col_2">
                        {{ $contentLength->description }}</td>
                        <td class="col_3  is_published-{{ @$contentLength->id }} "
                            data-status="{{ @$contentLength->is_ai_enabled }}">
                            <span
                                class="badge badge-{{ @$contentLength->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$contentLength->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                        </td>
                    <td class="col_5">{{ $contentLength->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
