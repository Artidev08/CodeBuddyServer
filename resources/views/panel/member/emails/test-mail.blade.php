@component('mail::message')
    # Test mail

    This is a testing mail.

    ## Congrats it's working....!
    Regards,<br>
    {{ getSetting('app_name') }}
@endcomponent
