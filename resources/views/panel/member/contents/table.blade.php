<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                @if (!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'edit_content') || isUserHasPermission($authUser->permissions['permissions'], 'delete_content'))
                    <th class="no-export">
                        <input type="checkbox" class="mr-2 " id="selectall" value="">
                        @lang('admin/ui.actions')
                    </th>
                @endif
            <th class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                        data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            @endif
            <th class="col_1"> Description
            </th>
            <th class="col_2"> Category
            </th>
            <th class="col_2"> Shares
            </th>
            <th class="col_3"> Occasion
            </th>
            <th class="col_4"> Event
            </th>
            <th class="col_13"> Date
            </th>
            <th class="col_14"> Media
            </th>
            <th class="" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($contents->count() > 0)
            @foreach ($contents as $content)
                <tr id="{{ $content->id }}">
                    @if (!isset($print_mode))
                            @if (!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'edit_content') || isUserHasPermission($authUser->permissions['permissions'], 'delete_content')  )
                                <td class="no-export">
                                    <div class="dropdown d-flex">
                                        <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                            value="{{ $content->id }}">
                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Actions
                                        </button>
                                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                            
                                            @if(isUserHasPermission($authUser->permissions['permissions'], 'edit_content'))
                                            
                                                <a href="{{ route('panel.member.contents.edit', secureToken($content->id)) }}"
                                                    title="Edit Content" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                                </a>
                                            @endif

                                            @if(isUserHasPermission($authUser->permissions['permissions'], 'delete_content'))
                                        
                                                <a href="{{ route('panel.member.contents.destroy', secureToken($content->id)) }}"
                                                    title="Delete Content"
                                                    class="dropdown-item text-danger fw-700 delete-item">
                                                    <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                                </a>
                            
                                            @endif
                                        </ul>
                                    </div>
                                </td>
                            @endif
                        <td class="text-center no-export">
                                {{ @$content->getPrefix() }} </td>
                    @endif
                    <td class="col_1">
                        {!! nl2br($content->description) !!}</td>
                    <td class="col_2">{{ @$content->contentCategory->name ?? 'N/A' }}
                    </td>
                    <td class="col_3">{{ @$content->share_count ?? 'N/A' }}
                    </td>
                    <td class="col_3">{{ @$content->occasion->name ?? 'N/A' }}
                    </td>
                    <td class="col_4">{{ @$content->event->name ?? 'N/A' }}
                    </td>
                    <td class="col_13">
                        {{ $content->event_date  ?? 'N/A' }}</td>
                    <td class="col_14">{{ @$content->mediaType->name ?? 'N/A' }}
                    </td>
                    <td class="col_5">{{ $content->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
