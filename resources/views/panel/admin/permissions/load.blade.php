<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                <label for=""> @lang('admin/ui.show')
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                        <option
                        value="{{ @$limit }}"{{ @$allPermissions->perPage() == @$limit ? 'selected' : '' }}>
                        {{ @$limit }}</option>

                        @endforeach
                    </select>
                     @lang('admin/ui.entry')
                </label>
            </div>
            <div>
                <button type="button" id="export_button"
                    class="btn btn-light btn-sm"> @lang('admin/ui.btn_excel') </button>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                @if (getSetting('permission_table_search',@$setting))
                <x-input name="search" placeholder="{{ __('admin/ui.left_sidebar_search') }}"  type="text" tooltip="" regex="role_name"
                validation="permission_name" value="{{ request()->get('search') }}" />
            @endif

            </div>
        </div>

    </div>
    <div class="table-responsive">
        <table id="permissions_table" class="table">
            <thead>
                <tr>
                    <th class="no-export">{{ 'Action' }} <div class="table-div"><i class="ik ik-arrow-up asc"
                                data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                    </th>
                    <th> @lang('admin/ui.permission') </th>
                    <th> @lang('admin/ui.assign_roles') </th>
                    <th> @lang('admin/ui.group_name') </th>
                </tr>
            </thead>
            <tbody>

                @if ($allPermissions->count() > 0)
                    @foreach ($allPermissions as $item)
                        <tr>
                            <td class="no-export">
                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                aria-haspopup="true" aria-expanded="false">
                                 @lang('admin/ui.actions')
                            </button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                @if (env('DEV_MODE') == 1)
                                    <a href="{{ route('panel.admin.permissions.destroy', $item->id) }}"
                                        class="btn btn-sm delete-item text-danger fw-700">
                                        <i class="ik ik-trash f-16 text-red"></i> Delete
                                    </a>
                                @endif
                            </ul>
                            </td>
                            <td>{{ $item->name }}</td>
                            <td>
                                @foreach ($item->roles()->get() as $role)
                                    <span class="badge badge-dark mr-1 mt-1">{{ $role->display_name }}</span>
                                @endforeach
                            </td>
                            <td>{{ $item->group ?? '--' }}</td>

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
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ $allPermissions->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if ($allPermissions->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $allPermissions->currentPage() ?? '' }}">
                            {{-- <x-input name="page" placeholder="" type="number" tooltip=""
                            regex="" validation="" value="{{ $allPermissions->currentPage() ?? ''  }}"/> --}}
                        <div class="w-25 bg-gray py-2 fw-700">/ {{ $allPermissions->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
