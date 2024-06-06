@extends('layouts.app')

@section('content')

<div class="container-fluid full-height" style=" height: 80vh;">
    <div class="d-flex flex-column align-items-center justify-content-center h-100">
        <form method="post" action="{{ route('adminLogin') }}">
            @csrf
            <div class="mb-3" style="width: 300px;">
              <label for="login" class="form-label">Логин</label>
              <input type="text" class="form-control" name="login" id="login" aria-describedby="login" value="{{ old('login') }}">
                @error('login')
                    <div class="form-text ">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
              <label for="password" class="form-label">Пароль</label>
              <input type="password" name="password" class="form-control" id="password">
            </div>
            @error('password')
                <div class="form-text ">{{ $message }}</div>
            @enderror
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-primary my-3">Войти</button>
            </div>
          </form>
   
    </div>
</div>
@endsection
