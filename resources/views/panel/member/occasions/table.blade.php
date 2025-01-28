<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'show_occasion')|| isUserHasPermission($authUser->permissions['permissions'], 'edit_occasion') || isUserHasPermission($authUser->permissions['permissions'], 'delete_occasion'))
                    <th width="15%" class="no-export">
                        <input type="checkbox" class="mr-2 " id="selectall" value="">&nbsp;&nbsp;&nbsp;&nbsp;
                        @lang('admin/ui.actions')
                    </th>
                @endif
                <th width="15%" class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th width="25%"> Name
            </th>
            <th width="10%"> Event
            </th>
            <th width="20%" class="col_3">
                Prompt
            </th>
            <th width="15%" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($occasions->count() > 0)
            @foreach ($occasions as $occasion)
                <tr id="{{ $occasion->id }}">
                    @if (!isset($print_mode))
                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'show_occasion')|| isUserHasPermission($authUser->permissions['permissions'], 'edit_occasion') || isUserHasPermission($authUser->permissions['permissions'], 'delete_occasion'))
                            <td class="no-export">
                                <div class="dropdown d-flex">
                                    <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                        value="{{ $occasion->id }}">&nbsp;&nbsp;&nbsp;&nbsp;

                                    <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                        aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        {{-- @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'show_occasion'))
                                    
                                            <a href="{{ route('panel.member.occasions.show', secureToken($occasion->id)) }}"
                                                title="Edit occasion" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif --}}
                                    
                                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'edit_occasion'))
                                        
                                        <a href="{{ route('panel.member.occasions.edit', secureToken($occasion->id)) }}"
                                            title="Edit occasion" class="dropdown-item ">
                                            <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                        </a>
                                        @endif
                                        
                                    
                                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_occasion'))
                                        <a href="{{ route('panel.member.occasions.destroy', secureToken($occasion->id)) }}"
                                            title="Delete occasion" class="dropdown-item text-danger fw-700 delete-item">
                                            <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                        </a>
                                        @endif
                                    
                                    </ul>
                                </div>
                            </td>
                        @endif
                        <td class="text-center no-export"> {{ $occasion->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $occasion->name }}</td>
                    <td class="col_1">
                        @php
                            $events = \App\Models\Event::where('occasion_id', $occasion->id)->get();
                        @endphp
                        <a href="@if(!is_null($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'view_events')) {{ route('panel.member.events.index', ['occasion_id' => encrypt($occasion->id)]) }} @else javascript:void(0); @endif"
                            class="btn-link">{{ $events->count() }}</a>
                    </td>
                    <td class="col_3  is_published-{{ @$occasion->id }} "
                        data-status="{{ @$occasion->is_ai_enabled }}">
                        <span
                            class="badge badge-{{ @$occasion->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$occasion->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                    </td>
                    <td class="col_5">{{ $occasion->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
