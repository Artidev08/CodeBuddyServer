
@php
    $authRole = AuthRole();
@endphp

@if ($authRole == 'User')
    @include('layouts.user')
@elseif($authRole == 'Member')
    @include('layouts.member')
@else
    @include('layouts.admin')
@endif
