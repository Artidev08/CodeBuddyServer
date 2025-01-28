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
            <th class="col_1">
                
                Name
                <span class="table-div m-0"><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></span>
            </th>
            <th class="col_2">Event Icon </th>
            <th class="col_2">Event Date </th>
            <th class="col_3">Predefined Date
            </th>
            {{-- <th class="col_4"> Description </th> --}}
            <th width="" class="col_3">
                Prompt
            </th>
            <th class="col_5">Visibility</th>
            <th class="col_6">Views</th>
            <th class="col_6"> Occasion</th>
            <th class="" title="Created At"><i class="icon-head" data-title="Created At"><i
                        class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($events->count() > 0)
            @foreach ($events as $event)
                <tr id="{{ $event->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $event->id }}">
                                @if ($permissions->contains('edit_event') || $permissions->contains('delete_event'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        {{-- @if ($permissions->contains('view_events'))
                                            <a href="{{ route('panel.admin.events.show', secureToken($event->id)) }}"
                                                title="Edit Event" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif --}}
                                        @if ($permissions->contains('edit_event'))
                                         @if (isset($occasion_id) && $occasion_id != null)
                                         <a href="{{ route('panel.admin.events.edit', [secureToken($event->id),'occasion_id' => $occasion_id]) }}"
                                            title="Edit Event" class="dropdown-item ">
                                            <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                        </a>
                                         @else
                                        <a href="{{ route('panel.admin.events.edit', secureToken($event->id)) }}"
                                            title="Edit Event" class="dropdown-item ">
                                            <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                        </a>
                                         @endif
                                           
                                        @endif
                                        @if ($permissions->contains('delete_event'))
                                            <a href="{{ route('panel.admin.events.destroy', secureToken($event->id)) }}"
                                                title="Delete Event"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"><a class="table-link"
                                href="{{ route('panel.admin.events.show', secureToken(@$event->id)) }}">
                                {{ @$event->getPrefix() }}
                                {{-- @if (@$event->is_featured == 1)
                                    <span title="Featured">
                                        <i class="fa fa-star text-warning" aria-hidden="true"></i>
                                    </span>
                                @endif --}}
                            </a></td>
                    @endif
                    <td class="text-start col_1">
                        {{ $event->name }}</td>
                        <td class="text-start col_1">
                            {{ $event->icon }}</td>
                    <td class="text-center col_2">
                        {{ @$event->date ?? 'N/A' }}</td>
                    <td class="text-center col_4">
                        {{ $event->is_predefined_date ? 'Yes' : 'No' }}
                    </td>

                    <td class="col_3  is_published-{{ @$event->id }} "
                        data-status="{{ @$event->is_ai_enabled }}">
                        <span
                            class="badge badge-{{ @$event->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$event->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                    </td>
                    <td class="col_3 is_published-{{ @$event->id }}" data-status="{{ @$event->is_published }}">
                        <span
                            class="badge badge-{{ @$event->is_published == 1 ? 'success' : 'danger' }}">{{ @$event->is_published == 1 ? 'Publish' : 'Unpublish' }}</span>
                    </td>
                    <td class="text-center col_4">{{ @$event->view_count ?? '0' }}</td>
                    <td class="text-center col_7">{{ @$event->occasion->name ?? 'N/A' }}
                    </td>
                    <td class="text-center col_5">{{ $event->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
