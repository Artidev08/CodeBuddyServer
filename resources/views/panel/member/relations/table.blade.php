<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'show_relation') ||  isUserHasPermission($authUser->permissions['permissions'], 'edit_relation') || isUserHasPermission($authUser->permissions['permissions'],'delete_relation'))
                    <th class="no-export">
                        <input type="checkbox" class="mr-2 " id="selectall" value="">
                        @lang('admin/ui.actions')
                    </th>
                @endif
                <th class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th class="col_1"> Name
                <div class="table-div"><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div>
            </th>
            <th class="col_2">Emoji
            </th>
            <th width="" class="col_3">
                Prompt
            </th>
            <th class="" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($relations->count() > 0)
            @foreach ($relations as $relation)
                <tr id="{{ $relation->id }}">
                    @if (!isset($print_mode))
                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'show_relation') ||  isUserHasPermission($authUser->permissions['permissions'], 'edit_relation') || isUserHasPermission($authUser->permissions['permissions'],'delete_relation'))
                            <td class="no-export">
                                <div class="dropdown d-flex">
                                    <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                        value="{{ $relation->id }}">
                                    
                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                            {{-- @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'show_relation'))
                                                <a href="{{ route('panel.member.relations.show', secureToken($relation->id)) }}"
                                                    title="Edit Relation" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                                </a>
                                            @endif --}}
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'edit_relation'))

                                                <a href="{{ route('panel.member.relations.edit', secureToken($relation->id)) }}"
                                                    title="Edit Relation" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                                </a>
                                            @endif
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_relation'))

                                                <a href="{{ route('panel.member.relations.destroy', secureToken($relation->id)) }}"
                                                    title="Delete Relation"
                                                    class="dropdown-item text-danger fw-700 delete-item">
                                                    <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                                </a>
                                            @endif
                                        </ul>
                                </div>
                            </td>
                        @endif
                        <td class="text-center no-export"> {{ $relation->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $relation->name }}</td>
                    <td class="col_2">
                        {{ $relation->description }}</td>
                        <td class="col_3  is_published-{{ @$relation->id }} " data-status="{{ @$relation->is_ai_enabled }}">
                            <span
                                class="badge badge-{{ @$relation->is_ai_enabled == 1 ? 'success' : 'danger' }}">{{ @$relation->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                        </td>
                    <td class="col_5">{{ $relation->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
