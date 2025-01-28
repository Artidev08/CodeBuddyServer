@extends('layouts.empty')
@section('title', 'Notification Print')
@section('content')

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="table-responsive">
                    <table id="table" class="table">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th>Notification</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($notifications->count() > 0)
                                @foreach ($notifications as $notification)
                                    <tr>
                                        <td class="text-center">{{ $loop->iteration }}</td>
                                        <td>
                                            @if ($notification->is_read == 0)
                                                <span class="new-update"></span>
                                            @endif
                                            {{ $notification->title }} {{ $notification->notification }}
                                        </td>
                                        <td><a href="{{ route('panel.admin.notifications.update', $notification->id) }}"
                                                class="btn btn-icon btn-sm btn-outline-info"><i
                                                    class="ik ik-eye"></i></a></td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="8">@include('panel.admin.include.components.no-data-img')</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
