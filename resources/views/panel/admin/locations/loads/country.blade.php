<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            @if (getSetting('location_activation_table_record_limit', @$setting))
                <label for=""> @lang('admin/ui.show')
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                            <option value="{{ @$limit }}"{{ @$countries->perPage() == @$limit ? 'selected' : '' }}>
                                {{ @$limit }}</option>
                        @endforeach
                    </select>
                    @lang('admin/ui.entry')
                </label>
        </div>
        @endif
        <div>
            @if (getSetting('location_activation_table_search', @$setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                    validation="permission_name" value="{{ request()->get('search') }}" />
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <table id="countryTable" class="table p-0">
            <thead>
                <tr>
                    <th width="10%" class=" no-export">
                        @if (getSetting('location_activation_checkbox', @$setting))
                            {{-- <input type="checkbox" class="allChecked mr-1" name="id[]" value=""> --}}
                        @endif
                        @lang('admin/ui.sNo')
                    </th>

                    @if (getSetting('location_activation_checkbox', @$setting))
                        <th width="10%" class=" no-export">@lang('admin/ui.actions')

                        </th>
                    @endif
                    <th width="10%" class="col_1  no-export">@lang('admin/ui.#') <div class="table-div"><i
                                class="ik ik-arrow-up  asc" data-val="id"></i><i class="ik ik ik-arrow-down desc"
                                data-val="id"></i></div>
                    </th>
                    <th width="30%" class="col_3"> @lang('admin/ui.name') <div class="table-div"><i
                                class="ik ik-arrow-up  asc" data-val="name"></i><i class="ik ik ik-arrow-down desc"
                                data-val="name"></i></div>
                    </th>
                    <th width="10%" class="col_4"> @lang('admin/ui.code') <div class="table-div"><i
                                class="ik ik-arrow-up  asc" data-val="iso3"></i><i class="ik ik ik-arrow-down desc"
                                data-val="iso3"></i></div>
                    </th>
                    <th width="10%" class="col_4">Ph @lang('admin/ui.code') <div class="table-div"><i
                                class="ik ik-arrow-up  asc" data-val="phonecode"></i><i class="ik ik ik-arrow-down desc"
                                data-val="phonecode"></i></div>
                    </th>
                    <th width="10%" class="col_5"> @lang('admin/ui.currency') </th>
                    <th width="10%" class="col_5"> @lang('admin/ui.flag') </th>
                </tr>
            </thead>
            <tbody>
                @if (@$countries->count() > 0)
                    @foreach (@$countries as $country)
                        <tr>
                            <td class="col_2 no-export">
                                {{-- <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                    value="{{ @$country->id }}"> --}}
                                {{ @$loop->iteration }}
                            </td>
                            <td class="col_2 no-export">
                                <div class="dropdown">

                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('admin/ui.actions')
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        {{-- @if ($permissions->contains('delete_location')) --}}
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.locations.state', ['country' => secureToken(@$country->id)]) }}"
                                                title="Manage States" class="btn btn-sm"><i
                                                    class="ik ik-git-merge mr-2"></i>States</a></li>
                                        {{-- @endif  --}}
                                        @if ($permissions->contains('edit_location'))
                                            <li class="dropdown-item p-0"><a
                                                    href="{{ route('panel.admin.locations.country.edit', secureToken(@$country->id)) }}"
                                                    title="Edit Country" class="btn btn-sm"><i
                                                        class="ik ik-edit mr-2"></i> @lang('admin/ui.edit')</a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                            <td class="col_1 no-export"><a
                                    href="{{ route('panel.admin.locations.state', ['country' => secureToken(@$country->id)]) }}"
                                    class="table-link m-0">{{ @$country->getPrefix() }}</a></td>
                            <td class="col_3"> {{ @$country->name . ' | ' . @$country->native }}</td>
                            <td class="col_4">{{ @$country->iso3 ?? '--' }}</td>
                            <td class="col_4">{{ @$country->phonecode ?? '--' }}</td>
                            <td class="col_5">{{ @$country->currency ?? '--' }}</td>
                            <td class="col_5">{{ @$country->emojiU ?? '--' }} </td>
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
                {{ @$countries->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$countries->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                        @lang('admin/ui.jump_to') :
                    </div>
                    <div class="input-group w-50">
                        <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                            value="{{ $countries->currentPage() ?? '' }}">
                        <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $countries->lastPage() }}</div>
                    </div>
                </label>
            @endif
        </div>
    </div>
</div>
