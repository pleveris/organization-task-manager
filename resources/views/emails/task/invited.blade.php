@component('mail::message')
# Task Invitation

You have been invited to task _{{ $title }}_.

@component('mail::button', ['url' => $url ])
View invitation
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
