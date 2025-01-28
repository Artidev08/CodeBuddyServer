<table id="table" class="table p-0">
    <thead>
        <tr>
            <th class="col_1" width="8%">
                <input type="checkbox" class="allChecked mr-1" name="id" value="">
                @lang('admin/ui.sNo')
            </th>
            <th class="col_1 no-export" width="8%">
                @lang('admin/ui.actions')
            </th>
            <th class="col_2 no-export" width="8%"> @lang('admin/ui.#')
                <div class="table-div"><i class="ik ik-arrow-up  asc" data-val="id"></i><i
                        class="ik ik ik-arrow-down desc" data-val="id"></i></div>
            </th>
            <th class="col_3" width="15%"> @lang('admin/ui.customer')</th>
            <th scope="col">{{ __('user/ui.email') }}</th>
            <th>{{ __('user/ui.phone') }}</th>
            <th>Model</th>
            <th>Registers</th>
            <th width="" class="col_3">
                Prompt
            </th>
            <th class="col_8" width="10%"><i class="icon-head" title="Join At"><i
                        class="fa-regular fa-clock"></i></i>
                <div class="table-div"><i class="ik ik-arrow-up asc" data-val="created_at"></i><i
                        class="ik ik ik-arrow-down desc" data-val="created_at"></i></div>
            </th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if (@$users->count() > 0)
            @foreach (@$users as $user)
                <tr id="{{ @$user->id }}">
                    @if (!isset($print_mode))
                        <td class="col_1">
                            <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                            value="{{ @$user->id }}">
                            {{ @$loop->iteration }}
                        </td>
                        <td class="col_1 no-export">
                            <div class="d-flex mb-1">
                                <div class="dropdown">
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @lang('admin/ui.actions')
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        <li class="dropdown-item">
                                            <a href="{{ route('panel.admin.users.edit', secureToken($user->id)) }}"  title="Edit"><i
                                                class="ik ik-edit mr-2"> </i>Edit</a>
                                        </li>
                                        @if (auth()->user()->hasRole('admin'))
                                            @if (getSetting('dac_activation') == 1)
                                                <a href="{{ route('panel.admin.users.login-as', secureToken($user->id)) }}"
                                                    title="Login As ">
                                                    <li class="dropdown-item loginAsBtn"
                                                        data-user_id="{{ $user->id }}"
                                                        data-first_name="{{ $user->first_name }}"><i
                                                            class="ik ik-log-in mr-2"> </i> Login
                                                        As
                                                    </li>
                                                </a>
                                            @else
                                                <a href="{{ route('panel.admin.users.login-as', $user->id) }}"
                                                    title="Login As">
                                                    <li class="dropdown-item"><i class="ik ik-log-in mr-2"> </i> Login
                                                        As
                                                    </li>
                                                </a>
                                            @endif
                                        @endif
                                  
                                        <hr class="m-1 b-0">
                                        <a class="delete-item"
                                            href="{{ route('panel.admin.users.destroy', secureToken($user->id)) }}"
                                            title="Delete">
                                            <li class="dropdown-item text-danger fw-700"><i
                                                    class="ik ik-trash mr-2"> </i> Delete
                                            </li>
                                        </a>
                                    </ul>
                                </div>
                            </div>
                        </td>
                        <td class="col_2 no-export"><a class="table-link p-1"
                                href="{{ route('panel.admin.users.edit', [secureToken($user->id)]) }}">
                                {{ @$user->getPrefix() }}
                            </a>
                        </td>
                    @endif

                    <td class="col_3 max-w-150">{{ Str::limit(@$user->full_name, 15) }}</td>
                    <td class="col_5">{{ @$user->email ?? '--' }}</td>
                    <td>
                        @if($user->country_code) 
                            +{{ @$user->country_code ?? '' }} 
                        @endif {{ @$user->phone ?? '---' }}</td>
                    <td>{{ @$user->ai_payload['model'] ?? '--' }}</td>
                    <td>
                        <a href="{{ route('panel.admin.agent-content-registers.index', ['agent_id' => $user->id]) }}" class="fw-800">{{ $user->registers->count() }}</a>
                    </td>
                    <td class="col_3  is_published-{{ @$user->id }} "
                        data-status="{{ @$user->is_ai_enabled }}">
                        <span
                            class="badge badge-{{ @$user->is_ai_enabled == 1 ? 'success' : 'danger' }} ">{{ @$user->is_ai_enabled == 1 ? 'Enable' : 'Disable' }}</span>
                    </td>
                    <td class="col_8">{{ @$user->formatted_created_at ?? '--' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
            </tr>
        @endif
    </tbody>
</table>
