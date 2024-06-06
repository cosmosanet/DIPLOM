@extends('layouts.app')


@section('content')
@if (session()->has('success'))
<div class="alert alert-success" role="alert">
  {{session()->get('success')}}
</div>
@endif
@if (session()->has('errors_action'))
<div class="alert alert-warning" role="alert">
  {{ session()->get('errors_action')}}
</div>
@endif
<div class="container">
    <div class="container h-100 d-flex justify-content-between p-3 my-3">
            <div>
                <table class="table w-100 me-2 border-end ">
                    <thead>
                    <tr>
                        <th scope="col">№</th>
                        <th scope="col">Роль</th>
                        <th scope="col">Описание</th>
                        <th scope="col"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($roles as $role) 
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $role->name_roles }}</td>
                                <td>{{ $role->deception }}</td>
                                <td>
                                    <div class="d-flex">
                                        <button class="btn btn-secondary" onclick='editForm(<?php echo json_encode($role->id); ?>)'>Изменить</button>
                                        <form action="{{ route('deleteRole') }}" method="post">
                                            @csrf
                                            @method('DELETE')
                                            <input type="text" value="{{ $role->id }}" name="id_role" hidden>
                                            @if ($role->name_roles != "admin")
                                                <button class="btn btn-danger mx-3">Удалить</button>
                                            @endif
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div id='editForm' class="modal-backdrop m-2">
                <div class="d-flex justify-content-center h-100 align-items-center"> 
                    
                    <div class="inner-editForm" id="innerForm">
                        <div class="d-flex w-100 justify-content-end ">
                            <img class="close-form-button" onclick="closeForm()" src="{{ asset('public\assets\image\svg\close-svgrepo-com.svg')}}" alt="">
                        </div>
                        <h4>Изменение ключей доступа</h4>
                        <div class="d-flex">
                          
                            <p id='roleName'></p>
                       </div>
                        <div class="d-flex flex-column">
                            {{-- форма изменения --}}
                            <form role="form" action="{{ route('editRole') }}" class="mx-3" autocomplete="off" enctype="multipart/form-data" method="POST" id="swapForm">
                                @csrf
                                
                                <input type="text" id='id_role' name="id_role" hidden>
                                <label class="my-2" for="">Статический ключ</label>
                                <input name='static_key' class="form-control" type="text" required>
                                <label class="my-2" for="">Секретный ключ</label>
                                <input name='secret_key'class="form-control" type="text" required>
                                <button class="btn btn-primary my-3" type="submit" onclick="document.getElementById('swapForm').submit()" class="btn btn-primary">Изменить</button>
                                
                            </form>
                        
                        </div>
                    </div>
                </div>
            </div>
          <div class="ms-2">
            <h2>Добавление Роли</h2>
            <form action="{{ route('addRole') }}" method="post">
                @csrf
                <label class="my-2" for="">Название роли</label>
                    <input name='role_name' class="form-control" type="text" required>
                    @error('role_name')
                     <div class="alert">{{ $message }}</div>
                    @enderror
                <label class="my-2" for="">Описание</label>
                    <textarea name='deception' class="form-control" id="exampleFormControlTextarea1" row="3" style="max-width: 300px; max-height: 80px;"></textarea>
                    @error('deception')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                <label class="my-2" for="">Статический ключ</label>
                    <input name='static_key' class="form-control" type="text" required>
                    @error('static_key')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                <label class="my-2" for="">Секретный ключ</label>
                    <input name='secret_key'class="form-control" type="text" required>
                    @error('secret_key')
                        <div class="alert">{{ $message }}</div>
                    @enderror
                <button class="btn btn-success my-2">Добавить</button>
            </form>


          </div>
    </div>
</div>
<script>
    //модельное окно формы
    var specifiedElement = document.getElementById('editForm');
    //обект с формой
    var innerSpecifiedElement = document.getElementById('innerForm');
    //счетчик
    var num = 1;
    function handleClickOutside(event) {
    // Проверяем, видим ли элемент
    var isDisplayed = window.getComputedStyle(specifiedElement).display !== 'none';
    
    // Проверяем, что клик был не по специфицированному элементу и его дочерним элементам и что элемент видим
    if (!innerSpecifiedElement.contains(event.target) && isDisplayed) {
        // Выполняем действия, если клик был вне видимого блока
        num =  num -1;
        if(num < 0)
        {
            closeForm();
            num = 1;
        }   
    }
    }
    //эвент нажатия
    document.addEventListener('click', handleClickOutside);
    //стиль для скрытия формы 
    document.getElementById('editForm').style.display = 'none';
    //функция для закрытия окна с формой
    function closeForm(){
        document.getElementById('editForm').style.display = 'none';
        num = 1;
    }
    //функция для открытия окна с формой
    function editForm(id) {
        console.log(id);
      
        document.getElementById('id_role').value = id;
        // document.getElementById('roleName').innerHTML = nameRole;
        document.getElementById('editForm').style.display = 'block';
    }
</script>
@endsection
