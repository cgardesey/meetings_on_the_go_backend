@component('mail::message')
# Introduction

Hello {{ $user->info->firstname }},

Welcome to Univirtual Schools. Please confirm your email address by clicking the button below:

@component('mail::button', ['url' =>  url("email/$user->email/$user->confirmation_token")])

Confirm
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
