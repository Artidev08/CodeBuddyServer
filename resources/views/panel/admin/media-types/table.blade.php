<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th width="25%" class="no-export">
                    <input type="checkbox" class="mr-2 " id="selectall" value="">
                    @lang('admin/ui.actions')
                </th>
                <th width="25%" class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th width="25%"> Name
            </th>
            <th width="25%" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($mediaTypes->count() > 0)
            @foreach ($mediaTypes as $mediaType)
                <tr id="{{ $mediaType->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $mediaType->id }}">
                                @if ($permissions->contains('edit_media_type') || $permissions->contains('delete_media_type'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if ($permissions->contains('view_media_types'))
                                            <a href="{{ route('panel.admin.media-types.show', secureToken($mediaType->id)) }}"
                                                title="Edit Media Type" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('edit_media_type'))
                                            <a href="{{ route('panel.admin.media-types.edit', secureToken($mediaType->id)) }}"
                                                title="Edit Media Type" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('delete_media_type'))
                                            <a href="{{ route('panel.admin.media-types.destroy', secureToken($mediaType->id)) }}"
                                                title="Delete Media Type"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"> {{ $mediaType->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $mediaType->name }}</td>
                    <td class="col_5">{{ $mediaType->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
