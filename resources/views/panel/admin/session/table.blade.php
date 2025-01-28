<table id="table" class="table">
    <thead>
        <tr>

            @if (!isset($print_mode))
                <th class="col_1 no-export">
                    <input type="checkbox" class="allChecked mr-1" name="id" value="">
                </th>
            @endif
            <th class="col_2">@lang('admin/ui.ip_address') </th>
            <th class="col_4">@lang('admin/ui.last_activity')</th>
            <th class="col_4">@lang('admin/ui.logout')</th>

        </tr>
    </thead>
    <tbody>
        @if (@$sessions->count() > 0)
            @foreach (@$sessions as $session)
                <tr id="{{ @$session->id }}">
                    <td>
                        <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                            value="{{ @$session->id }}">
                    </td>
                    <td class="col_3">{{ @$session->ip_address ?? 'N/A' }}</td>

                    <td class="col_4 ml-2">
                        {{ isset($session->last_activity)? \Carbon\Carbon::createFromTimestamp($session->last_activity)->setTimezone('Asia/Kolkata')->format('Y-m-d H:i:s'): 'N/A' }}
                    </td>
                    <td class="col_3">
                        <div class="d-flex justify-content-right">
                            <a href="{{ route('panel.admin.users.sessionDelete', $session->id) }}"
                                class="btn btn-outline-danger mr-2" title="Add New User Subscription">@lang('admin/ui.logout')</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center no-export" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
            </tr>
        @endif
    </tbody>
</table>
