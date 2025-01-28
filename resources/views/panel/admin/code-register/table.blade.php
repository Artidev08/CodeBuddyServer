
@if ($rows->count() > 0)
    <div class="card-grid">
        @foreach ($rows as $item)
            <div class="custom-cards" id="{{ $item->id }}">
                <div class="p-3 border-bottom d-flex justify-content-between">
                    <div class="">
                        <div class="fs-15">
                            <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"value="{{ $item->id }}"> 
                            {{ $item->getPrefix() }}-{{ Str::limit(@$item->title ?? '', 100) }}
                            
                        </div>
                    </div>
                    <div>
                        <div class="dropdown"style="float: right;">
                            <button class="dropdown-toggle p-0 custom-dopdown btn agent-ellipsis-btn"style="background:transparent" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-ellipsis pl-1"></i></button>
                            </button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                <a href="{{ route($route . '.sync', secureToken($item->id)) }}" title="Sync"
                                    class="dropdown-item ">
                                    <li class="p-0"><i class="ik ik-settings mr-2"></i> Sync</li>
                                </a>
                                @if($item->codeRegisterFile->count() > 0)
                                <a href="{{ route($route . '.edit', secureToken($item->id)) }}" title="Edit"
                                    class="dropdown-item ">
                                    <li class="p-0"><i class="ik ik-edit mr-2"></i> Edit</li>
                                </a>
                                <hr class="m-1 p-0">
                                <a href="{{ route($route . '.destroy', secureToken($item->id)) }}" title="Delete"
                                    class="dropdown-item  delete-item text-danger fw-700">
                                    <li class=" p-0">
                                        <i class="ik ik-trash mr-2"> </i> Delete
                                    </li>
                                </a>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body" style="height: 140px;">
                    <div class="mt-2" style="height: 80px;">
                        <h6>{{ Str::limit(@$item->base_path ?? '', 38) }}  | <span class="text-primary mr-2">{{ $item->workflow }}</span></h6>
                        <span class="text-muted">
                            Project: {{ getERPProjects($item->project_id)['name'] ?? '' }} |
                            Agent: {{ str::limit($item->agent->name,20) ?? '' }}
                        </span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-end">
                        <div class="">
                            <span class="text-{{ @App\Models\CodeRegister::STATUS[$item->status]['color'] }}">
                                {{ @App\Models\CodeRegister::STATUS[$item->status]['label'] }}
                            </span>
                        </div>
                        <div>
                            <a class="text-white"
                                href="{{ route($route.'-file.index')}}?code_register_id={{ $item->id }}">
                                Files: 
                                {{ @$item->codeRegisterFile->count() ?? '0' }}
                            </a>
                        </div>
                    </div> 
                </div>
                <div class="p-3 mt-4">
                    <div class="actions d-flex">
                        <a class="btn btn-secondary w-50" href="{{url('/api/local-code-optimization/'.$item->id.'/progress')}}" target="_blank"><i class="ik ik-terminal mr-0"></i> Controllers</a>
                        <a class="btn btn-secondary w-50 ml-2" href="{{url('/api/views-code-optimization/'.$item->id.'/progress')}}" target="_blank"><i class="ik ik-terminal mr-0"></i> Views</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="">
        <p class="text-center">No Data Found...</p>
    </div>
@endif

