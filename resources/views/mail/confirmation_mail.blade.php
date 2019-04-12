@component('mail::message')
# Introduction

The body of your message.

@component('mail::button', ['url' => 'http://' . request()->getHttpHost() .'/registration-confirm/' . $user->confirmation_token])
Press to confirm registration.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
