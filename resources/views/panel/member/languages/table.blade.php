<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'show_language')||isUserHasPermission($authUser->permissions['permissions'], 'edit_language') ||isUserHasPermission($authUser->permissions['permissions'], 'delete_language'))

                    <th class="no-export">
                        <input type="checkbox" class="mr-2 " id="selectall" value="">
                        @lang('admin/ui.actions')
                    </th>
                @endif
                <th class="text-center no-export"># <div class="table-div" ><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th class="col_1 m-0"> Name
                <div class="table-div" style="float: none"><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div>
            </th>
            <th width="20%" class="col_3">
                Prompt
            </th>
            <th class="" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($languages->count() > 0)
            @foreach ($languages as $language)
                <tr id="{{ $language->id }}">
                    @if (!isset($print_mode))
                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'show_language')||isUserHasPermission($authUser->permissions['permissions'], 'edit_language') ||isUserHasPermission($authUser->permissions['permissions'], 'delete_language'))

                            <td class="no-export">
                                <div class="dropdown d-flex">
                                    <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                        value="{{ $language->id }}">
                                    
                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                            Actions
                                        </button>
                                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                            {{-- @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'show_language'))
                                        
                                                <a href="{{ route('panel.member.languages.show', secureToken($language->id)) }}"
                                                    title="Edit Language" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                                </a>
                                            @endif --}}
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'edit_language'))
                                            
                                                <a href="{{ route('panel.member.languages.edit', secureToken($language->id)) }}"
                                                    title="Edit Language" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                                </a>
                                            @endif
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_language'))
                                            
                                                <a href="{{ route('panel.member.languages.destroy', secureToken($language->id)) }}"
                                                    title="Delete Language"
                                                    class="dropdown-item text-danger fw-700 delete-item">
                                                    <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                                </a>
                                            @endif
                                        </ul>
                                </div>
                            </td>
                        @endif
                        <td class="text-center no-export"> {{ $language->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $language->name }}</td>
                        <td class="col_3  is_published-{{ @$language->id }} p-0 m-0"
                            data-status="{{ @$language->is_ai_enabled }}">
                            <span
                                class="badge badge-{{ @$language->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$language->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                        </td>
                    <td class="col_5">{{ $language->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
