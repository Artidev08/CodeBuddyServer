<table id="table" class="table">
    <thead>
        <tr>
            <th class="no-export d-flex justify-content-between">
                <input type="checkbox" class="allChecked mr-1" name="id[]" value="">
                @lang('admin/ui.sNo')
            </th>
            <th class="col_1">   Actions</th>
            <th class="text-center no-export" title="Id/GPT Code ">{{ __('#') }}</th>
            <th class="col_1"> Criteria </th>
            <th>Contents</th>
            <th>Status</th>
            <th class="col_6" title="Playground"> <i class="ik ik-clock"></i> </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if ($agents->count() > 0)
            @foreach ($agents as $item)
                @php 
                    $contents = App\Models\Content::where('agent_content_register_id',$item->id)->count();
                @endphp
                <tr id="{{ $item->id }}">
                    <td class="">
                     <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                          value="{{ $item->id }}"> 
                          {{ @$loop->iteration }}
                     </td> 
                    <td class="no-export">
                        <div class="dropdown">
                           
                            
                                <button class="dropdown-toggle btn btn-secondary" type="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                @lang('admin/ui.actions')
                            </button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                               <a href="{{ route('panel.admin.agent-content-registers.edit', secureToken($item->id)) }}"
                                    title="Edit Agent" class="dropdown-item ">
                                    <li class="p-0"><i class="ik ik-edit mr-2"></i> Edit</li>
                                </a> 
                                <hr class="m-1 p-0">
                                <a href="{{ route('panel.admin.agent-content-registers.destroy', secureToken($item->id)) }}"
                                    title="Delete Agent" class="dropdown-item  delete-item text-danger fw-700">
                                    <li class=" p-0">
                                        <i class="ik ik-trash mr-2"> </i> Delete
                                    </li>
                                </a>
                            </ul>
                        </div>
                    </td>
                    <td class="text-center no-export">
                        <a class="btn btn-link text-dark"
                            href="{{ route('panel.admin.agent-content-registers.edit', secureToken($item->id)) }}">
                           {{ $item->getPrefix() }}
                        </a>
                    </td>
                    <td>
                        @if($item->criteria_payload)
                            @foreach (@$item->criteria_payload as $key => $value)
                                @php
                                    $modelClass = '\\App\\Models\\' . ucfirst($key);
                                    $category = $modelClass::where('id', $value)->first('name');
                                @endphp
                                <span class="badge badge-secondary m-1"> {{ @$category->name }}</span>
                            @endforeach
                        @else
                        --
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('panel.admin.contents.index', ['agent_content_register_id' => $item->id]) }}" class="fw-800">{{ $contents }}</a>
                    </td>
                    <td class="col_6">
                        <select name="status" id="" class="form-control select-w-100" onchange="changeStatus('status','{{$item->id}}',this)">
                            @foreach (App\Models\AgentContentRegister::STATUSES as $status_key => $status)
                                <option value="{{ $status_key}}" @if($item->status == $status_key) selected @endif>{{ $status['label'] }}</option>
                            @endforeach
                        </select>   
                    </td>
                    <td class="col_6">
                        {{ $item->formatted_created_at}}
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center no-export" colspan="12">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
