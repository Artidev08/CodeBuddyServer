<table id="table" class="table">
    <thead>
        <tr>
            @if (!isset($print_mode))
                <th class="no-export" width="8%">
                    @if (getSetting('website_enquiry_bulk_status_update', @$setting) ||
                            getSetting('website_enquiry_bulk_delete', @$setting) ||
                            getSetting('website_enquiry_bulk_upload', @$setting))
                        <input type="checkbox" class="mr-2 allChecked " name="id" value="">
                    @endif
                     @lang('admin/ui.sNo')
                </th>
                <th class="no-export" width="10%">
                     @lang('admin/ui.actions')
                </th>
                <th class="no-export"  width="10%">#
                    <div class="table-div"><i class="ik ik-arrow-up asc" data-val="id"></i><i
                            class="ik ik ik-arrow-down desc" data-val="id"></i></div>
                </th>
            @endif
            <th class="col_1"  width="24%"> @lang('admin/ui.name') </th>
            <th class="col_4"  width="15%"> @lang('admin/ui.subject') </th>
            <th class="col_3" width="15%"> @lang('admin/ui.phone')  Number<div class="table-div"><i class="ik ik-arrow-up  asc" data-val="phone"></i><i
                class="ik ik ik-arrow-down desc" data-val="phone"></i></div></th>
            <th class="col_4" width="8%"> @lang('admin/ui.status') </th>
            <th class="col_5" width="10%"><i class="icon-head" title="Created At"><i class="fa-regular fa-clock"></i></i><div class="table-div"><i class="ik ik-arrow-up  asc" data-val="created_at"></i><i
                class="ik ik ik-arrow-down desc" data-val="created_at"></i></div></th>
        </tr>
    </thead>
    <tbody class="no-data">
        @if ($websiteEnquiries->count() > 0)
            @foreach ($websiteEnquiries as $websiteEnquiry)
                <tr id="{{ $websiteEnquiry->id }}">
                    @if (!isset($print_mode))
                        <td class="no-export">
                            @if (getSetting('website_enquiry_bulk_status_update', @$setting) ||
                            getSetting('website_enquiry_bulk_delete', @$setting) ||
                            getSetting('website_enquiry_bulk_upload', @$setting))
                        <input type="checkbox" class="mr-2 delete_Checkbox text-center" name="id"
                            value="{{ $websiteEnquiry->id }}">
                    @endif
                    {{ @$loop->iteration }}
                        </td>
                        <td class="no-export">
                            <div class="dropdown d-flex">

                                <button class="dropdown-toggle btn btn-secondary" type="button" data-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    Action
                                </button>

                                <ul class="dropdown-menu multi-level" role="menu" aria-labelledby="dropdownMenu">

                                    @if ($permissions->contains('edit_enquiry'))
                                        <li class="dropdown-item">
                                            <a
                                                href="{{ route('panel.admin.website-enquiries.edit', secureToken($websiteEnquiry->id)) }}"><i
                                                    class="ik ik-edit mr-2"> </i>Edit</a>
                                        </li>
                                    @endif

                                    @if ($permissions->contains('blacklist_enquiry'))
                                        <!-- Assuming 'blacklist_enquiry' is the permission name -->
                                        @php
                                            $ipAddress = $websiteEnquiry->ip_address;
                                            $isBlacklisted = \App\Models\Blacklist::where(
                                                'ip_address',
                                                $ipAddress,
                                            )->exists();

                                        @endphp

                                        @if ($isBlacklisted)
                                            <a class="blacklist-item"
                                                href="{{ route('panel.admin.website-enquiries.unblock', $ipAddress) }}">
                                                <li class="dropdown-item">
                                                    <i class="ik ik-check-square mr-2"></i> Unblock
                                                </li>
                                            </a>
                                        @else
                                            <a class="blacklist-item"
                                                href="{{ route('panel.admin.website-enquiries.blacklist', ['id' => secureToken($websiteEnquiry->id)]) }}">
                                                <li class="dropdown-item">
                                                    <i class="ik ik-slash mr-2"></i> Blacklist
                                                </li>
                                            </a>
                                        @endif
                                    @endif
                                    <hr class="m-1 b-0">
                                    @if ($permissions->contains('delete_enquiry'))
                                        <a class="delete-item"
                                            href="{{ route('panel.admin.website-enquiries.destroy', secureToken($websiteEnquiry->id)) }}">
                                            <li class="dropdown-item text-danger fw-700"><i class="ik ik-trash mr-2">
                                                </i> Delete
                                            </li>
                                        </a>
                                    @endif
                                </ul>
                            </div>
                        </td>
                        <td class="no-export"><a class="table-link pl-0"
                                href="@if ($permissions->contains('show_enquiry')) {{ route('panel.admin.website-enquiries.show', secureToken($websiteEnquiry->id)) }} @endif">{{ $websiteEnquiry->getPrefix() }}</a>
                        </td>
                    @endif
                    <td class="col_1">{{ Str::limit(@$websiteEnquiry->name, 25) }}</td>
                    <td class="col_4">{{ Str::limit(@$websiteEnquiry->subject, 20) }}</td>
                    <td class="col_3">+{{ @$websiteEnquiry->country_code }} {{ @$websiteEnquiry->phone }}</td>

                    <td class="col_4">
                        <span
                            class="badge badge-{{ @\App\Models\WebsiteEnquiry::STATUSES[$websiteEnquiry->status]['color'] }}">{{ @\App\Models\WebsiteEnquiry::STATUSES[@$websiteEnquiry->status]['label'] }}</span>
                    </td>
                    <td class="col_5">{{ @$websiteEnquiry->formatted_created_at }}</td>
                </tr>
            @endforeach
        @else
            <tr>
                <td class="text-center" colspan="8">
                    @include('panel.admin.include.components.no-data-img')
                </td>
            </tr>
        @endif
    </tbody>
</table>
