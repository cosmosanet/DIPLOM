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
<div class="container">
    <div class="container h-100 d-flex justify-content-between p-3">
            <div>
                <table class="table w-100 me-2 border-end ">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Пользователь</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Почта</th>
                        <th scope="col">Время созддания</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $item) 
                     
                            <tr>
                                <th scope="row">{{ $loop->index+1}}</th>
                                <td>{{$item->name}}</td>
                                <td>
                                    <form action="{{ route('editUser') }}" method="post" id="editUserForm{{ $loop->index+1 }}">
                                        @csrf
                                        <input type="text" value="{{ $item->id }}" name="id_user" hidden>

                                        <select name="role"  class="form-select">
                                         
                                            @if (isset($item->role->id))
                                                <option selected value="{{ $item->role->id }}">{{ $item->role->name_roles }}</option>
                                                @elseif ($item->status == 'Удалён')
                                                <option selected>NULL</option>
                                                @else
                                                <option selected>NULL</option>
                                            @endif
                                            @foreach ($roles as $role)
                                            <option value="{{ $role->id }}">{{ $role->name_roles }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                                <td>{{$item->email}}</td>
                                <td>{{$item->create_time}}</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-primary" type="submit" onclick="document.getElementById('editUserForm{{ $loop->index+1 }}').submit()">{{'Изменить'}}</button>
                                        @if ($item->status == null)
                                        <form action="{{ route('deleteUser') }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="text" value="{{ $item->id }}" name="id_user" hidden>
                                            <button class="btn btn-danger mx-3">{{'Удалить'}}</button>
                                        </form>
                                        @else
                                        <div class="d-flex justify-content-center w-100 h-100 align-items-center">
                                            <p>Удалён</p>
                                        </div>
                                    @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          <div class="m-2">
            <h2>Добавление пользователя</h2>
            <form action="{{ route('addUser') }}" method="post">
                @csrf
                <label class=" form-label ">Почта</label>
                <input type="email" class=" form-control " name="email" id="" required>
                
                <select name="role" class="form-select my-3" id="">
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name_roles }}</option>
                    @endforeach
                </select>

                <button class="btn btn-success ">Добавить</button>
            </form>


          </div>
    </div>
</div>
@endsection
