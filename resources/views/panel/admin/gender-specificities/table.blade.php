<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th width="16%" class="no-export">
                    <input type="checkbox" class="mr-2 " id="selectall" value="">
                    @lang('admin/ui.actions')
                </th>
                <th width="16%" class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th width="16%"> Name </th>
            <th width="20%"> Description</th>
            <th width="10%"> Emoji </th>
            <th width="10%"> Prompt </th>

            <th width="16%" class="" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($genderSpecificities->count() > 0)
            @foreach ($genderSpecificities as $genderSpecificity)
                <tr id="{{ $genderSpecificity->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $genderSpecificity->id }}">
                                @if ($permissions->contains('edit_gender_specificity') || $permissions->contains('delete_gender_specificity'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">

                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if ($permissions->contains('view_gender_specificities'))
                                            <a href="{{ route('panel.admin.gender-specificities.show', secureToken($genderSpecificity->id)) }}"
                                                title="Edit Gender Specificity" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('edit_gender_specificity'))
                                            <a href="{{ route('panel.admin.gender-specificities.edit', secureToken($genderSpecificity->id)) }}"
                                                title="Edit Gender Specificity" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if ($permissions->contains('delete_gender_specificity'))
                                            <a href="{{ route('panel.admin.gender-specificities.destroy', secureToken($genderSpecificity->id)) }}"
                                                title="Delete Gender Specificity"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"> {{ $genderSpecificity->getPrefix() }}</td>
                    @endif
                    <td class="col_1">
                        {{ $genderSpecificity->name }}</td>
                    <td class="col_2">
                        {{ $genderSpecificity->description }}</td>
                    <td class="col_3">
                        {{ $genderSpecificity->emoji }}</td>
                    <td class="col_3  is_published-{{ @$genderSpecificity->id }} " data-status="{{ @$genderSpecificity->is_ai_enabled }}">
                        <span
                            class="badge badge-{{ @$genderSpecificity->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$genderSpecificity->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                    </td>
                    <td class="col_5">{{ $genderSpecificity->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
