<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if (Route::has('login'))
    @auth
    <meta http-equiv="refresh" content="0; url={{ url('/admin') }}" />
    @else
    <meta http-equiv="refresh" content="0; url={{ route('login') }}" />
    @endauth
    @endif
</head>

</html>