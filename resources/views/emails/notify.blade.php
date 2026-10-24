
@component('mail::message')
# Halo {{ $user->name }}

Terima kasih telah bergabung dengan aplikasi kami.

@component('mail::button', ['url' => $url])
Lihat Dashboard
@endcomponent

Terima kasih,<br>
{{ config('app.name') }}
@endcomponent
