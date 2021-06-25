@component('mail::message')
Hello ,
<br>

 {{-- click here to change your  <a href="http://localhost:3000/reset/{{$token}}">password</a>  --}}
 This is your Token  {{$token}}
Do not share it with any one..!
{{$email}}
<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
