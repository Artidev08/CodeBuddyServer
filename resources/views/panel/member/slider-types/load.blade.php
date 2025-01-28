<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            @if (getSetting('slider_table_record_limit',@$setting))
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">

                    @foreach (tableLimits() as $limit)
                        <option value="{{ @$limit }}"{{ @$sliderTypes->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                 @lang('admin/ui.entry')
            </label>
        </div>
             @endif

             <div class="d-flex justify-content-between">
                @if (getSetting('slider_table_search',@$setting))
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                validation="permission_name" value="{{ request()->get('search') }}" />
            @endif
        </div>
    </div>
    <div class="table-responsive">
        <table id="sliderType" class="table">
            <thead>
                <tr>
                    <th width="8%"  class="">
                        @if (getSetting('slider_table_checkbox',@$setting))
                        <input type="checkbox" class="mr-2 allChecked" name="id"
                            value=""> @endif @lang('admin/ui.sNo')
                         </th>

                    @if (getSetting('slider_table_checkbox',@$setting))
                    <th width="8%"  class="no-export"> @lang('admin/ui.actions') </th>
                            @endif
                    <div class="mr-3">
                        <th width="11%"  class="text-center col_5 mr-3">@lang('admin/ui.#')
                            <div class="table-div">
                                <i class="ik ik-arrow-up asc" data-val="id"></i><i class="ik ik ik-arrow-down desc"
                                data-val="id"></i>
                            </div>
                        </th>
                    </div>
                    <th width="55%"  class="col_5"> @lang('admin/ui.title') <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="title"></i><i
                        class="ik ik ik-arrow-down desc" data-val="title"></i></div></th>
                    <th width="10%"  class="col_4"> @lang('admin/ui.visibility') </th>
                    <th width="8%"  class="col_4"> @lang('admin/ui.record') </th>
                </tr>
            </thead>
            <tbody class="no-data">
                @foreach (@$sliderTypes as $sliderType)
                    <tr id="{{ @$sliderType->id }}">
                        <td class="">
                            @if (getSetting('slider_table_checkbox',@$setting))
                            <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                value="{{ @$sliderType->id }}">
                                 @endif
                                 {{ @$loop->iteration }}
                        </td>
                        <td class="no-export">
                            <div class="dropdown">

                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                     @lang('admin/ui.actions')
                                </button>
                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                    @if ($permissions->contains('edit_slider'))
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.slider-types.edit', secureToken($sliderType->id)) }}"
                                                title="Edit Slider Type" class="btn btn-sm"><i
                                                    class="ik ik-edit mr-2"></i>
                                                Edit</a></li>
                                    @endif
                                    <li class="dropdown-item p-1"><a style="padding-left:10px"
                                            href="{{ route('panel.admin.sliders.index', ['sliderTypeId' => $sliderType->id]) }}"
                                            title="Manage Slider Type" class="btn btn-sm">
                                            <i style="padding-right: 8px !important;"
                                                class="fa-solid fa-list-check mr-1"></i>Manage</a></li>
                                    <hr class="m-1 b-0">
                                    @if ($permissions->contains('delete_slider'))
                                        @if (@$sliderType->is_permanent != 1)
                                            <li class="dropdown-item p-0">
                                                <a href="{{ route('panel.admin.slider-types.destroy', secureToken($sliderType->id)) }}"
                                                    title="Delete Slider Type"
                                                    class="btn btn-sm delete-item  text-danger"><i
                                                        class="ik ik-trash mr-2"> </i>Delete
                                                </a>
                                            </li>
                                        @endif
                                    @endif

                                </ul>
                            </div>
                        </td>
                        <td class="text-center col_1"><a class="table-link"
                                href="@if (env('DEV_MODE') == 1) {{ route('panel.admin.slider-types.edit', secureToken($sliderType->id)) }} @endif">{{ @$sliderType->getPrefix() }}</a>
                        </td>
                        <td class="col_5">{{ @$sliderType->title ?? '--' }}</td>

                        <td class="col_4 status-{{ @$sliderType->id }}" data-status="{{ @$sliderType->status }}"><span
                                class="badge badge-{{ @$sliderType->is_published == 1 ? 'success' : 'danger' }}">{{ @$sliderType->is_published == 1 ? 'Published' : 'Unpublished' }}</span>
                        </td>
                        <td class="col_4"><a class="table-link"
                                href="@if (env('DEV_MODE') == 1) {{ route('panel.admin.sliders.index', ['sliderTypeId' => $sliderType->id]) }} @endif">{{ @$sliderType->sliders->count() }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ @$sliderTypes->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$sliderTypes->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                         @lang('admin/ui.jump_to') :
                    </div>
                        <div class="input-group w-50">
                            <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                                value="{{ $sliderTypes->currentPage() ?? '' }}">
                            <div class="w-25 bg-gray py-2 pl-2 fw-700">/ {{ $sliderTypes->lastPage() }}</div>
                        </div>
                </label>
            @endif
        </div>
    </div>
</div>
