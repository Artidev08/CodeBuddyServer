<div class="card-body">
    <div class="table-controller mb-2">
        <div class="d-flex justify-content-between">
            <div class="mr-3">
                <label for="">  @lang('admin/ui.show')
                    <select name="length" class="length-input" id="length">
                        @foreach (tableLimits() as $limit)
                            <option value="{{ @$limit }}"{{ @$sliders->perPage() == @$limit ? 'selected' : '' }}>
                                {{ @$limit }}</option>
                        @endforeach
                    </select>
                      @lang('admin/ui.entry')
                </label>
            </div>
        </div>
        <div class="d-flex justify-content-between">
            <div>
                <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
                validation="permission_name" value="{{ request()->get('search') }}" />
            </div>
        </div>
    </div>
    <div class="table-responsive">
        <table id="sliderTable" class="table">
            <thead>
                <tr>
                    <th class="no-export "><input type="checkbox" class="mr-2 allChecked " name="id"
                            value="">  @lang('admin/ui.actions') </th>
                    <th class="text-center col_1">#
                        <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i
                                class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                    </th>
                    <th class="col_2">  @lang('admin/ui.title') <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="title"></i><i
                        class="ik ik ik-arrow-down desc" data-val="title"></i></div></th>
                    <th class="col_3">  @lang('admin/ui.type') </th>
                    <th class="col_4">  @lang('admin/ui.visibility') </th>
                </tr>
            </thead>
            <tbody class="no-data">
                @if (@$sliders->count() > 0)
                    @foreach (@$sliders as $slider)
                        <tr id="{{ @$slider->id }}">
                            <td class="no-export">
                                <div class="dropdown ">
                                    <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                        value="{{ @$slider->id }}">
                                    <button style="background: transparent;border:none;" class="dropdown-toggle p-0"
                                        type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true"
                                        aria-expanded="false"><i class="ik ik-more-vertical pl-1"></i></button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.sliders.edit', [secureToken(@$slider->id), 'sliderTypeId' => request()->get('sliderTypeId')]) }}"
                                                title="Edit Slider" class="btn btn-sm"><i class="ik ik-edit mr-2"></i>
                                                Edit</a>
                                        </li>
                                        <hr class=" m-1 b-0">
                                        <li class="dropdown-item p-0"><a
                                                href="{{ route('panel.admin.sliders.destroy', secureToken(@$slider->id)) }}"
                                                title="Delete Slider"
                                                class="btn btn-sm delete-item text-danger fw-700"><i
                                                    class="ik ik-trash mr-2"> </i>Delete</a></li>
                                    </ul>
                                </div>
                            </td>
                            <td class="text-center col_1"> {{ @$slider->getPrefix() }}</td>
                            <td class="col_2">{{ @$slider->title ?? '--' }}</td>
                            <td class="col_3">
                                <span
                                    class="badge badge-{{ @\App\Models\Slider::TYPES[@$slider->type]['color'] ?? '--' }}">
                                    {{ @\App\Models\Slider::TYPES[@$slider->type]['label'] ?? '--' }}
                                </span>
                            </td>
                            <td class="col_4 status-{{ @$slider->id }}" data-status="{{ @$slider->status }}"><span
                                    class="badge badge-{{ @$slider->status == 1 ? 'success' : 'danger' }}">{{ @$slider->status == 1 ? 'Published' : 'Unpublished' }}</span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-8">
            <div class="pagination mobile-justify-center">
                {{ @$sliders->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-4 mobile-mt-20">
            @if (@$sliders->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                          @lang('admin/ui.jump_to') :
                    </div>
                    <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                        value="{{ @$sliders->currentPage() ?? '' }}">
                </label>
            @endif
        </div>
    </div>
</div>
