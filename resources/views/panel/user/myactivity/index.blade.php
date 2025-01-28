@extends('layouts.user')

@section('meta_data')
    @php
        $meta_title = __('user/ui.user_session') . ' | ' . getSetting('app_name');
        $meta_description = '' ?? getSetting('seo_meta_description');
        $meta_keywords = '' ?? getSetting('seo_meta_keywords');
        $meta_motto = '' ?? getSetting('site_motto');
        $meta_abstract = '' ?? getSetting('site_motto');
        $meta_author_name = '' ?? 'Defenzelite';
        $meta_author_email = '' ?? 'support@defenzelite.com';
        $meta_reply_to = '' ?? getSetting('app_email');
        $meta_img = ' ';
        $customer = 1;
    @endphp
@endsection

@section('content')
<div class="row">
    <div class="main-card">
        @include('panel.user.include.message')
        @php
            $sectionHeader = [
                'headline' => __('user/ui.my_activity'),
                'sub_headline' => 'Check your activity',
                'filter_btn_visibility' => 1,
            ];
        @endphp
        @include('panel.user.partials.section_header')

        <div class="row">
            <div class="col-lg-12">
                <div class="table-responsive">
                    <table class="table table-striped w-630">
                        <thead>
                        <tr>
                            <th class="col-2">Sno.</th>
                            <th class="col-2">ID</th>
                            <th class="col-2">Activity</th>
                            <th class="col-2">Incident</th>
                            <th class="col-2">IP Address</th>
                            <th class="col-2">Occur At</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($userlogs as $userlog)

                        <tr>
                            <td> {{ @$loop->iteration }}</td>
                            <th scope="row" class="col-2">
                                <a href="{{ route('panel.user.my-activity.show',secureToken($userlog->id)) }}" class="table-link">
                                    {{ @$userlog->getPrefix() }}
                                </a>
                               </th>
                            <td class="col-2">{{ @App\Models\User::ACTIVITES[$userlog->activity]['label'] ?? '--' }}</td>

                            <td> {{ @$userlog->name }}</td>
                            <td class="">{{ $userlog->ip_address }}</td>

                             <td class="col-2">At {{ \Carbon\Carbon::parse($userlog->created_at)->format('d-m-Y H:i') }}</td>

                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td class="text-center" colspan="8">No Data Found...</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
@include('panel.user.myactivity.include.filter')
@endsection

