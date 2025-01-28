<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'show_sentiment') || isUserHasPermission($authUser->permissions['permissions'], 'edit_sentiment') || isUserHasPermission($authUser->permissions['permissions'], 'delete_sentiment'))    
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
            </th>
            {{-- <th class="col_2"> Description
            </th> --}}
            <th class="col_3"> Emoji
            </th>
            <th class="col_4"> Sequence
            </th>
            <th class="col_5">Visibility
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
        @if ($sentiments->count() > 0)
            @foreach ($sentiments as $sentiment)
                <tr id="{{ $sentiment->id }}">
                    @if (!isset($print_mode))
                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'show_sentiment') || isUserHasPermission($authUser->permissions['permissions'], 'edit_sentiment') || isUserHasPermission($authUser->permissions['permissions'], 'delete_sentiment'))    
                            <td class="no-export">
                                <div class="dropdown d-flex">
                                    <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                        value="{{ $sentiment->id }}">
                                    @if ($permissions->contains('edit_sentiment') || $permissions->contains('delete_sentiment'))
                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                            Actions
                                        </button>
                                        <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'show_sentiment'))
                                            @if ($permissions->contains('view_sentiments'))
                                                <a href="{{ route('panel.member.sentiments.show', secureToken($sentiment->id)) }}"
                                                    title="Edit Sentiment" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                                </a>
                                            @endif
                                        @endif
                                        @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'edit_sentiment'))
                                            @if ($permissions->contains('edit_sentiment'))
                                                <a href="{{ route('panel.member.sentiments.edit', secureToken($sentiment->id)) }}"
                                                    title="Edit Sentiment" class="dropdown-item ">
                                                    <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                                </a>
                                            @endif
                                            @endif
                                            @if(!is_null($authUser->permissions) && is_array($authUser->permissions) && isset($authUser->permissions['permissions']) &&  isUserHasPermission($authUser->permissions['permissions'], 'delete_sentiment'))
                                            @if ($permissions->contains('delete_sentiment'))
                                                <a href="{{ route('panel.member.sentiments.destroy', secureToken($sentiment->id)) }}"
                                                    title="Delete Sentiment"
                                                    class="dropdown-item text-danger fw-700 delete-item">
                                                    <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                                </a>
                                            @endif
                                            @endif
                                        </ul>
                                    @endif
                                </div>
                            </td>
                        @endif
                        <td class="text-center no-export"><a class="table-link"
                                href="{{ route('panel.admin.sentiments.show', secureToken(@$sentiment->id)) }}">
                                {{ @$sentiment->getPrefix() }} </a></td>
                    @endif
                    <td class="col_1">
                        {{ $sentiment->name }}</td>
                    {{-- <td class="col_2">
                        {{ $sentiment->description }}</td> --}}
                    <td class="col_3">
                        {{ $sentiment->emoji }}</td>
                    <td class="col_4">
                        {{ $sentiment->sequence }}</td>
                    <td class="col_3 is_published-{{ @$sentiment->id }}"
                        data-status="{{ @$sentiment->is_published }}">
                        <span
                            class="badge badge-{{ @$sentiment->is_published == 1 ? 'success' : 'danger' }}">{{ @$sentiment->is_published == 1 ? 'Publish' : 'Unpublish' }}</span>
                    </td>
                    <td class="col_3  is_published-{{ @$sentiment->id }} "
                        data-status="{{ @$sentiment->is_ai_enabled }}">
                        <span
                            class="badge badge-{{ @$sentiment->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$sentiment->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                    </td>
                    <td class="col_5">{{ $sentiment->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
