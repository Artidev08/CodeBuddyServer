<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$cities->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')
            </label>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                @if (getSetting('location_activation_table_search',@$setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                validation="permission_name" value="{{ request()->get('search') }}" />
                @endif
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="user_table" class="table p-0">
            <thead>
                <tr>
                    <th width="10%" class="col_2  no-export"> @lang('admin/ui.sNo')  </th>
                    <th width="10%" class="col_2  no-export"> @lang('admin/ui.actions') </th>
                    <th width="10%" class="col_1   no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                                data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                    </th>
                    <th width="50%" class="col_3"> @lang('admin/ui.name') <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="name"></i><i
                        class="ik ik ik-arrow-down desc" data-val="name"></i></div></th>
                    <th width="20%" class="col_3"> @lang('admin/ui.pincode') </th>
                </tr>
            </thead>
            <tbody>
                @if (@$cities->count() > 0)
                    @foreach (@$cities as $city)
                        <tr>
                            <td>
                                {{ @$loop->iteration }}
                            </td>
                            <td class="col_2 no-export">
                                <div class="dropdown">
                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                         @lang('admin/ui.actions')
                                    </button>

                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <li>
                                            <a href="javascript:void(0);" data-row="{{ @$city }}"
                                                title="Edit City" class="btn btn editCity dropdown-item">Edit</a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="col_1 no-export">{{ @$city->getPrefix() }}</td>
                            <td class="col_3">{{ @$city->name ?? '--' }}</td>
                            <td class="col_3">{{ @$city->pincode ?? '--' }}</td>
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
                {{ @$cities->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$cities->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $cities->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $cities->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
