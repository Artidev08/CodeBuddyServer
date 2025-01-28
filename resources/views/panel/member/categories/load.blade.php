<div class="card-body">
    <div class="d-flex justify-content-between mb-2">
        <div>
            <label for=""> @lang('admin/ui.show')
                <select name="length" class="length-input" id="length">
                    @foreach (tableLimits() as $limit)
                        <option
                            value="{{ @$limit }}"{{ @$categories->perPage() == @$limit ? 'selected' : '' }}>
                            {{ @$limit }}</option>
                    @endforeach
                </select>
                    @lang('admin/ui.entry')
            </label>
        </div>
        <div>
            <x-input name="search" placeholder="Search" type="text" tooltip="" regex="role_name"
            validation="permission_name" value="{{ request()->get('search') }}" />
        </div>
    </div>
    <div class="table-responsive">
        <div class="table-responsive">
            <table id="category_table" class="table">
                <thead>
                    <tr>
                        <th class="no-export" width="20%"><input type="checkbox" class="mr-2 allChecked " id="selectall"
                                name="id" value=""> @lang('admin/ui.actions') </th>
                        <th class="no-export" width="30%"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                                    data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                        </th>
                        <th width="30%"> @lang('admin/ui.name')<div class="table-div"><i class="ik ik-arrow-up asc" data-val="name"></i><i class="ik ik ik-arrow-down desc" data-val="name"></i></div> </th>
                        @if (@$categoryType && @$categoryType->allowed_level > @$level)
                            <th width="20%"> @lang('admin/ui.child_count') </th>
                        @endif
                    </tr>
                </thead>
                <tbody class="no-data">
                    @if (@$categories->count() > 0)
                        @foreach (@$categories as $category)
                            <tr id="{{ @$category->id }}">
                                <td>
                                    <div class="dropdown d-flex">
                                        <input type="checkbox" class="mr-2 text-center" name="id"
                                            onclick="countSelected()" value="{{ @$category->id }}">
                                        
                                        <button class="dropdown-toggle btn btn-secondary" type="button"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                @lang('admin/ui.actions')
                                        </button>
                                        <ul class="dropdown-menu multi-level" role="menu"
                                            aria-labelledby="dropdownMenu">
                                            <li class="dropdown-item p-0"><a
                                                    href="{{ route('panel.admin.categories.edit', [secureToken($category->id), 'parent_id' => request()->get('parent_id')]) }}"
                                                    title="Edit Lead Contact" class="btn btn-sm"> <i
                                                        class="ik ik-edit"> </i> Edit</a></li>

                                            <hr class="mt-0 mb-0">
                                            <li class="dropdown-item p-0"><a
                                                    href="{{ route('panel.admin.categories.destroy', secureToken($category->id)) }}"
                                                    title="Edit Lead Contact"
                                                    class="btn btn-sm delete-item text-danger fw-700">
                                                    <i class="ik ik-trash"></i> Delete</a></li>
                                        </ul>
                                    </div>
                                </td>
                                <td>{{ @$category->getPrefix() }}</td>
                                <td>{{ @$category->name ?? '--' }}</td>
                                @if (@$categoryType && @$categoryType->allowed_level > @$level)
                                    <td>
                                        @if (@$nextLevel <= 3)
                                            <a class="btn btn-link"
                                                href="{{ route('panel.admin.categories.index', [$category->category_type_id, 'level' => $nextLevel, 'parent_id' => $category->id]) }}">{{ App\Models\Category::where('parent_id', @$category->id)->where('parent_id', '!=', null)->count() ?? '--' }}</a>
                                        @else
                                            ---
                                        @endif
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center" colspan="15">@include('panel.admin.include.components.no-data-img')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="card-footer">
    <div class="row">
        <div class="col-lg-6 mt-2">
            <div class="pagination mobile-justify-center">
                {{ @$categories->appends(request()->except('page'))->links() }}
            </div>
        </div>
        <div class="col-lg-6 pt-0 mb-4 mobile-mt-20">
            @if (@$categories->lastPage() > 1)
                <label class="d-flex justify-content-end mobile-justify-center" for="">
                    <div class="mr-2 pt-2 ">
                            @lang('admin/ui.jump_to') :
                    </div>
                    <input type="number" class="w-25 form-control" id="jumpTo" name="page"
                        value="{{ @$categories->currentPage() ?? '--' }}">
                </label>
            @endif
        </div>
    </div>
</div>
