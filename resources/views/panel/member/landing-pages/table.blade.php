<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th class="no-export">
                    <input type="checkbox" class="mr-2 " id="selectall" value="">
                    @lang('admin/ui.actions')
                </th>
                <th class="text-center no-export"># <div class="table-div"><i class="ik ik-arrow-up  asc"
                            data-val="id"></i><i class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th class="col_2"> Title
            </th>
            <th class="col_6"> Content Categories
            </th>
            <th class="col_7"> Occasion
            </th>
            {{-- <th class="col_3"> Short Description
            </th>
            <th class="col_4"> Closing Description
            </th>
            <th class="col_5"> Ads Payload
            </th>
             --}}
            <th class="col_8"> Event
            </th>
            {{-- <th class="col_9"> Languages
            </th>
            <th class="col_10"> Sentiments
            </th>
            <th class="col_11"> Age Groups
            </th>
            <th class="col_12"> Relations
            </th>
            <th class="col_13"> Gender Specificitys
            </th>
            <th class="col_14"> Content Lengths
            </th>
            <th class="col_15"> Badges
            </th>
            <th class="col_16"> Predefined Date
            </th> --}}
            <th class="col_17"> Event Date
            </th>
            <th class="col_18"> Media Type
            </th>
            {{-- <th class="col_19"> Countries
            </th>
            <th class="col_20"> Meta Title
            </th>
            <th class="col_21"> Meta Description
            </th>
            <th class="col_22"> Meta Keywords
            </th> --}}
            <th class="" title="Created At">
                <i class="icon-head" data-title="Created At"><i class="fa fa-clock pl-30"></i></i>
            </th>
        </tr>
    </thead>
    <tbody>
        @if ($landingPages->count() > 0)
            @foreach ($landingPages as $landingPage)
                <tr id="{{ $landingPage->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            <div class="dropdown d-flex">
                                <input type="checkbox" class="mr-2 text-center" name="id" onclick="countSelected()"
                                    value="{{ $landingPage->id }}">
                                @if(!is_null($authUser->permissions) && isset($authUser->permissions['permissions']) && isUserHasPermission($authUser->permissions['permissions'], 'edit_landing_page') || isUserHasPermission($authUser->permissions['permissions'], 'delete_landing_page'))
                                    <button class="dropdown-toggle btn btn-secondary" type="button"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Actions
                                    </button>
                                    <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">
                                        @if(isUserHasPermission($authUser->permissions['permissions'], 'show_landing_page'))
                                            <a href="{{ route('panel.admin.landing-pages.show', secureToken($landingPage->id)) }}"
                                                title="Edit Landing Page" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-eye mr-2"></i>Show</li>
                                            </a>
                                        @endif
                                        @if(isUserHasPermission($authUser->permissions['permissions'], 'edit_landing_page'))
                                            <a href="{{ route('panel.admin.landing-pages.edit', secureToken($landingPage->id)) }}"
                                                title="Edit Landing Page" class="dropdown-item ">
                                                <li class="p-0"><i class="ik ik-edit mr-2"></i>Edit</li>
                                            </a>
                                        @endif
                                        @if(isUserHasPermission($authUser->permissions['permissions'], 'delete_landing_page'))
                                            <a href="{{ route('panel.admin.landing-pages.destroy', secureToken($landingPage->id)) }}"
                                                title="Delete Landing Page"
                                                class="dropdown-item text-danger fw-700 delete-item">
                                                <li class="p-0"><i class="ik ik-trash mr-2"></i>Delete</li>
                                            </a>
                                        @endif
                                    </ul>
                                @endif
                            </div>
                        </td>
                        <td class="text-center no-export"><a class="table-link"
                            href="{{ route('panel.admin.landing-pages.show', secureToken(@$landingPage->id)) }}">
                            {{ @$landingPage->getPrefix() }} </a></td>
                    @endif
                    <td class="col_2">
                        {{ $landingPage->title }}</td>
                        <td class="col_6">{{ @$landingPage->contentCategory->name ?? 'N/A' }}
                        </td>
                        <td class="col_7">{{ @$landingPage->occasion->name ?? 'N/A' }}
                        </td> 
                    {{-- <td class="col_3">
                        {{ $landingPage->short_description }}</td>
                    <td class="col_4">
                        {{ $landingPage->closing_description }}</td>
                    <td class="col_5">
                        {{ $landingPage->ads_payload }}</td>
                    --}}
                    <td class="col_8">{{ @$landingPage->event->name ?? 'N/A' }}
                    </td>
                    {{-- <td class="col_9">{{ @$landingPage->language->name ?? 'N/A' }}
                    </td>
                    <td class="col_10">{{ @$landingPage->sentiment->name ?? 'N/A' }}
                    </td>
                    <td class="col_11">{{ @$landingPage->ageGroup->name ?? 'N/A' }}
                    </td>
                    <td class="col_12">{{ @$landingPage->relation->name ?? 'N/A' }}
                    </td>
                    <td class="col_13">{{ @$landingPage->genderSpecificity->name ?? 'N/A' }}
                    </td>
                    <td class="col_14">{{ @$landingPage->contentLength->name ?? 'N/A' }}
                    </td>
                    <td class="col_15">{{ @$landingPage->badge->name ?? 'N/A' }}
                    </td>
                    <td class="col_16">
                        {{ $landingPage->is_predefined_date }}</td> --}}
                    <td class="col_17">
                        {{ @$landingPage->event_date  ?? 'N/A'}}</td>
                    <td class="col_18">{{ @$landingPage->mediaType->name ?? 'N/A' }}
                    {{-- </td>
                    <td class="col_19">{{ @$landingPage->countries->name ?? 'N/A' }}
                    </td>
                    <td class="col_20">
                        {{ $landingPage->meta_title }}</td>
                    <td class="col_21">
                        {{ $landingPage->meta_description }}</td>
                    <td class="col_22">
                        {{ $landingPage->meta_keywords }}</td> --}}
                    <td class="col_5">{{ $landingPage->formatted_created_at ?? '...' }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">No Data Found...</td>
            </tr>
        @endif
    </tbody>
</table>
