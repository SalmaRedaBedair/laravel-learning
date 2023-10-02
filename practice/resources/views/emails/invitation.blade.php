@component('mail::message')
# Invitation to Join Our Mailing List

Click the button below to confirm your subscription:

@component('mail::button', ['url' => $url])
Confirm Subscription
@endcomponent

Thanks,
The Example Team
@endcomponent
