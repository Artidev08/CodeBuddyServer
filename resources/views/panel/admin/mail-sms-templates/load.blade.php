<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            @if (getSetting('templates_table_record_limit',@$setting))
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                    <option value="{{ @$limit }}" {{ @$mailSmsTemplates->perPage() == @$limit ? 'selected' : '' }}>
                        {{ @$limit }}</option>
                    @endforeach
                </select>
                @lang('admin/ui.entry')
            </label>
        </div>
        @endif

        <div class="d-flex justify-content-between">
            @if (getSetting('templates_table_search',@$setting))
            <x-input name="search"  placeholder="{{ __('admin/ui.left_sidebar_search')}}" type="text" tooltip="" regex="role_name"
                validation="permission_name" value="{{ request()->get('search') }}" />
            @endif
            @if (getSetting('templates_table_filter',@$setting))
            <button type="button" class="off-canvas btn btn-light rounded-0 text-muted btn-icon"><i
                    class="ik ik-filter ik-lg"></i> </button>
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <table id="mailSmsTable" class="table">
            <thead>
                <tr>

                    <th width="8%" class="">
                        @if (getSetting('templates_table_checkbox',@$setting))
                        <input type="checkbox" class="mr-2 allChecked " name="id" value="">
                        @endif
                        @lang('admin/ui.sNo')
                    </th>

                    @if (getSetting('templates_table_checkbox',@$setting))
                    <th width="8%" class="no-export"> @lang('admin/ui.actions') </th>
                    @endif
                    <th width="8%" class="no-export">@lang('admin/ui.#') <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                        class="ik ik ik-arrow-down desc" data-val="id"></i></div></th>
                    <th width="30%" class="col_1"> @lang('admin/ui.subject') </th>
                    <th width="25%" class="col_2"> @lang('admin/ui.code') </th>
                    <th width="10%" class="col_3"> @lang('admin/ui.type') </th>
                    <th width="12%" class="col_3"><i class="icon-head" title="Created At"><i class="fa-regular fa-clock"></i></i>
                        <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="created_at"></i><i
                            class="ik ik ik-arrow-down desc" data-val="created_at"></i></div>
                    </th>
                </tr>
            </thead>
            <tbody class="">
                @if (@$mailSmsTemplates->count() > 0)
                @foreach (@$mailSmsTemplates as $mailSmsTemplate)
                <tr id="{{ @$mailSmsTemplate->id }}">
                    <td>
                        @if (getSetting('templates_table_checkbox',@$setting))
                        <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                            value="{{ @$mailSmsTemplate->id }}">
                        @endif
                        {{ @$loop->iteration }}
                    </td>
                    <td>
                        <div class="dropdown d-flex">

                            <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                @lang('admin/ui.actions') 
                            </button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                <li class="dropdown-item p-0"><a
                                        href="{{ route('panel.admin.templates.show', secureToken($mailSmsTemplate->id)) }}"
                                        title="View Template" class="btn btn-sm"><i class="ik ik-eye">
                                        </i>@lang('admin/ui.show') </a></li>

                                @if ($permissions->contains('edit_mail_template'))
                                <li class="dropdown-item p-0"><a
                                        href="{{ route('panel.admin.templates.edit', secureToken($mailSmsTemplate->id)) }}"
                                        title="Edit Template" class="btn btn-sm"><i class="ik ik-edit">
                                        </i> @lang('admin/ui.edit') </a></li>
                                @endif
                                @if (@$mailSmsTemplate->is_permanent != 1)
                                @if ($permissions->contains('delete_mail_template'))
                                <li class="dropdown-item p-0"><a
                                        href="{{ route('panel.admin.templates.destroy', secureToken($mailSmsTemplate->id)) }}"
                                        title="Edit Template" class="btn btn-sm delete-item text-danger"><i
                                            class="ik ik-trash"></i> Delete</a></li>
                                @endif
                                @endif
                            </ul>

                        </div>
                    </td>
                    <td><a class="table-link p-0 m-0 text-color-white"
                            href="{{ route('panel.admin.templates.edit', secureToken($mailSmsTemplate->id)) }}">{{
                            @$mailSmsTemplate->getPrefix() }}</a>
                    </td>
                    <td class="col_1">{{ @$mailSmsTemplate->subject ?? '--' }}</td>
                    <td class="col_2">{{ @$mailSmsTemplate->code ?? '--' }}</td>
                    <td class="col_3">
                        @if (@$mailSmsTemplate->type == 1)
                        <span>Mail</span>
                        @elseif(@$mailSmsTemplate->type == 2)
                        <span>SMS</span>
                        @elseif(@$mailSmsTemplate->type == 3)
                        <span>Whatsapp</span>
                        @else
                        <span>Prompt</span>
                        @endif
                    </td>
                    <td>{{ @$mailSmsTemplate->formatted_created_at ?? '--' }}</td>
                </tr>
                @endforeach
                @else
                <tr>
                    <td class="text-center" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer d-flex justify-content-between">
    <div class="pagination">
        {{ @$mailSmsTemplates->appends(request()->except('page'))->links() }}
    </div>
    <div>
        @if (@$mailSmsTemplates->lastPage() > 1)
        <label class="d-flex justify-content-end" for="">
            <div class="mr-2 pt-2">
                @lang('admin/ui.jump_to') :
            </div>
            <div class="input-group w-50">
                <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                    value="{{ $mailSmsTemplates->currentPage() ?? '' }}">
                <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $mailSmsTemplates->lastPage() }}</div>
            </div>
        </label>
        @endif
    </div>
</div>