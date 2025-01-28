@component('mail::message')
    {!! $body !!}

    Regards,<br>
    **{{ getSetting('app_name') }}**
@endcomponent
