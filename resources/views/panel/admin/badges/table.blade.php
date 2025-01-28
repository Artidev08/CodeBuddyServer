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
            <th width="20%"> Name
                {{-- <div class="table-div"><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div>
            </th> --}}
            <th width="20%"> Description
            </th>
            <th width="20%" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($badges->count() > 0)
            @foreach ($badges as $badge)
                <tr id="{{ $badge->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $badge->id }}">
                                @if ($permissions->contains('edit_badge') || $permissions->contains('delete_badge'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if ($permissions->contains('view_badges'))
                                            <a href="{{ route('panel.admin.badges.show', secureToken($badge->id)) }}"
                                                title="Edit Badge" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('edit_badge'))
                                            <a href="{{ route('panel.admin.badges.edit', secureToken($badge->id)) }}"
                                                title="Edit Badge" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('delete_badge'))
                                            <a href="{{ route('panel.admin.badges.destroy', secureToken($badge->id)) }}"
                                                title="Delete Badge"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"> {{ $badge->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $badge->name }}</td>
                    <td class="col_2">
                        {{ $badge->description }}</td>
                    <td class="col_5">{{ $badge->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
