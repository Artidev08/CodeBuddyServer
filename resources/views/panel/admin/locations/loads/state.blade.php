<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            @if (getSetting('location_activation_table_record_limit', @$setting))
                <label for=""> @lang('admin/ui.show')
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                            <option value="{{ @$limit }}"{{ @$states->perPage() == @$limit ? 'selected' : '' }}>
                                {{ @$limit }}</option>
                        @endforeach
                    </select>
                    @lang('admin/ui.entry')
                </label>
        </div>
        @endif
        <div class="d-flex justify-content-between">
            <div>
                @if (getSetting('location_activation_table_search', @$setting))
                    <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                        validation="permission_name" value="{{ request()->get('search') }}" />
                @endif
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="stateTable" class="table p-0">
            <thead>
                <tr>
                    <th width="10%" class="col_2 "> @lang('admin/ui.sNo') </th>
                    <th width="10%" class="col_2  no-export"> @lang('admin/ui.actions') </th>
                    <th width="20%" class="col_1 no-export">@lang('admin/ui.#') <div class="table-div"><i
                                class="ik ik-arrow-up  asc" data-val="id"></i><i class="ik ik ik-arrow-down desc"
                                data-val="id"></i></div>
                    </th>
                    <th width="60%" class="col_3"> @lang('admin/ui.state') @lang('admin/ui.name') <div class="table-div"><i
                                class="ik ik-arrow-up  asc" data-val="name"></i><i class="ik ik ik-arrow-down desc"
                                data-val="name"></i></div>
                    </th>

                </tr>
            </thead>
            <tbody>
                @if (@$states->count() > 0)
                    @foreach ($states as $state)
                        <tr>
                            <td class="col_2">
                                {{ @$loop->iteration }}
                            </td>
                            <td class="col_2 no-export">
                                <div class="dropdown">
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('admin/ui.actions')
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <li class="dropdown-item p-0">
                                            <a href="javascript:void(0);" title="Edit State"
                                                class="btn btn-sm editState" data-row="{{ @$state }}"><i
                                                    class="ik ik-edit mr-2"></i>@lang('admin/ui.edit')</a>
                                        </li>
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.locations.city', ['state' => secureToken($state->id)]) }}"
                                                title="Manage City" class="btn btn-sm"><i
                                                    class="ik ik-git-merge mr-2"></i>@lang('admin/ui.cities')</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class=" col-1  no-export"><a class="table-link btn btn-link"
                                    href="{{ route('panel.admin.locations.city', ['state' => secureToken($state->id)]) }}">{{ @$state->getPrefix() }}</a>
                            </td>
                            <td class="col_3">{{ @$state->name ?? '--' }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td class="text-center" colspan="4">@include('panel.admin.include.components.no-data-img')</td>
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
                {{ @$states->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$states->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                        @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $states->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $states->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
