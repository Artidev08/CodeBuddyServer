<table id="table" class="table">
    <thead>
        <tr>
            <th class="no-export">
                <!-- <input type="checkbox" class="allChecked mr-1" name="id[]" value=""> -->
                Actions</th>
            <th class="text-center no-export" title="Id/GPT Code ">{{ __('#') }}</th>
            <th class="col_1"> Avatar </th>
            <th class="col_1"> Name/Bio </th>
            <th>Model</th>
            <th>Registers</th>
            <!-- <th class="col_3" title="Status/Scenarios "> Status </th> -->
            <th class="col_6" title="Playground"> <i class="ik ik-clock"></i> </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if ($agents->count() > 0)
            @foreach ($agents as $item)
                <tr id="{{ $item->id }}">
                    <td class="no-export">
                        <div class="dropdown">
                            <!-- <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                                value="{{ $item->id }}"> -->

                            <button class="dropdown-toggle p-0 custom-dopdown btn btn-light " type="button"
                                id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i
                                    class="ik ik-more-vertical pl-1"></i></button>
                            </button>
                            <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                <a href="{{ route('panel.admin.agent-content-registers.index', ['agent_id' => $item->id]) }}"
                                    title="Edit Agent" class="dropdown-item ">
                                    <li class="p-0"><i class="ik ik-book mr-2"></i> Registers</li>
                                </a>
                                <a href="{{ route('panel.admin.agents.edit', secureToken($item->id)) }}"
                                    title="Edit Agent" class="dropdown-item ">
                                    <li class="p-0"><i class="ik ik-edit mr-2"></i> Edit</li>
                                </a>
                                <hr class="m-1 p-0">
                                <a href="{{ route('panel.admin.agents.destroy', secureToken($item->id)) }}"
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
                            href="{{ route('panel.admin.agents.edit', secureToken($item->id)) }}">
                           {{ $item->getPrefix() }}
                        </a>
                    </td>
                    <td>
                        <img title="{{ $item->formatted_created_at }} - {{ $item->formatted_updated_at }}"
                            class="avatar"
                            src="{{ $item && $item->avatar ? $item->avatar : asset('backend/default/default-avatar.png') }}"
                            style="object-fit: fill; width: 55px; height: 55px; border-radius: 50%;" alt="">
                    </td>
                    <td class="max-w-450">
                        <a class="btn-link" href="{{ route('panel.admin.agents.show', $item->id) }}">
                            <span class="fw-800">{{ @$item->name }}</span>
                        </a>
                        <hr class="my-0">
                        {{ Str::limit(@$item->bio ?? '', 100) }}
                    </td>
                    <td>
                        {{ $item->model ? $item->model->name : '' }}
                    </td>
                    <td>
                        <a href="{{ route('panel.admin.agent-content-registers.index', ['agent_id' => $item->id]) }}" class="fw-800">{{ $item->registers->count() }}</a>
                    </td>
                    <!-- <td>
                        <span title="Status"
                            class="fw-700 text-{{ @App\Models\Agent::STATUSES[$item->status]['color'] }}">
                            {{ @App\Models\Agent::STATUSES[$item->status]['label'] }}
                        </span>
                    </td> -->
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
