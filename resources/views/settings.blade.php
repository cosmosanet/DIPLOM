@extends('layouts.app')

@section('content')
@if (session()->has('success_action'))
<div class="alert alert-success" role="alert">
  {{session()->get('success_action')}}
</div>
@endif
@if (session()->has('errors_action'))
<div class="alert alert-warning" role="alert">
  {{ session()->get('errors_action')}}
</div>
@endif
<div class="container d-flex justify-content-between flex-column  align-items-center">
    <div>

        <h3 class="my-3">Изменение пароля</h3>
        <form action="{{ route('changePassword')}}" method="post" style="width: 400px">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Старый пароль</label>
                <input class="form-control" type="password" name="oldPassword" required>

                @error('oldPassword')
                    <div class="alert">{{ $message }}</div>
                @enderror

            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Новый пароль</label>
                <input class="form-control" type="password" name="password" required>

                @error('password')
                    <div class="alert">{{ $message }}</div>
                @enderror

            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary" type="submit">Готово</button>
            </div>
        </form>
    </div>

    {{-- <div>
        <h3 class="my-3">Изменение ключей</h3>
        <form action="{{ route('changeYandexIDKey')}}" method="post">
            @csrf
            <div class="mb-3">
                <label for="password" class="form-label">Ключ yandex ID</label>
                <input class="form-control" type="text" name="key" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Секретный ключ yandex ID</label>
                <input class="form-control" type="text" name="secretKEy" required>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary"  type="submit">Готово</button>
            </div>
        </form>
    </div> --}}
</div>
@endsection
