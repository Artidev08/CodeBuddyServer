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
            <th width="15%"> Name
                <div class="table-div" style=""><i class="ik ik-arrow-up  asc " data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div>
            </th>
            <th class="col_2" width="20%"> Prompt
            </th>
            <th width="20%" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($ageGroups->count() > 0)
            @foreach ($ageGroups as $ageGroup)
                <tr id="{{ $ageGroup->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $ageGroup->id }}">
                                @if ($permissions->contains('edit_age_group') || $permissions->contains('delete_age_group'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if ($permissions->contains('view_age_groups'))
                                            <a href="{{ route('panel.admin.age-groups.show', secureToken($ageGroup->id)) }}"
                                                title="Edit Age Group" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('edit_age_group'))
                                            <a href="{{ route('panel.admin.age-groups.edit', secureToken($ageGroup->id)) }}"
                                                title="Edit Age Group" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('delete_age_group'))
                                            <a href="{{ route('panel.admin.age-groups.destroy', secureToken($ageGroup->id)) }}"
                                                title="Delete Age Group"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"> {{ $ageGroup->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $ageGroup->name }}</td>
                        <td class="col_3  is_published-{{ @$ageGroup->id }} "
                            data-status="{{ @$ageGroup->is_ai_enabled }}">
                            <span
                                class="badge badge-{{ @$ageGroup->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$ageGroup->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                        </td>
                    <td class="col_5">{{ $ageGroup->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
