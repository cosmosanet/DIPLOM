@extends('layouts.app')

@section('content')
@if (session()->has('success'))
<div class="alert alert-success" role="alert">
  {{session()->get('success')}}
</div>
@endif
@if (session()->has('errors'))
<div class="alert alert-warning" role="alert">
  {{ session()->get('errors')}}
</div>

@endif
<div class="container-fluid full-height" style=" height: 80vh;">
     <div class="h-100 d-flex flex-column align-items-center justify-content-center">
        <a class="btn btn-primary m-3" href="https://oauth.yandex.ru/authorize?response_type=code&client_id={{ config('app.yandex_id_key')}}">Войти через Yandex ID</a>
        <a class="link-info" href="https://id.yandex.ru/">Выйти из yandex ID</a>
        <a class="link-info" href="{{ url('login?code=123')}}">Тестовая</a>
        <a class="link-info" href="{{ url('loginAdmin') }}">Для администраторов</a>
    </div>
</div>
@endsection
