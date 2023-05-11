@component('mail::message')
## Hello {{$user->first_name?:''}} ,

Welcome to the CRG Platform, an interactive reporting portal. We can't wait for you to get started!
<br>
<br>
You'll need a temporary pin to verify your email address. Your temporary pin is:
<br>
<br>
**{{$user->verification_code}}**
<br>
<br>
Please click on the Get Started button below and enter the temporary pin to complete your account setup.
@component('mail::button', ['url' => config('app.front_end_url')."/set-password?email=$user->email" , 'color' => 'primary'])
    Get Started
@endcomponent

<br>
<br>
{{ config('app.name') }}
