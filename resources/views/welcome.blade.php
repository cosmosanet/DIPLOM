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
@if (session()->has('apiKey'))

    <div class="container h-100 d-flex justify-content-between p-3">
       
        <div class="w-100 me-2 border-end ">
            <div class="d-flex">
                <form action="{{ route('index') }}" method="get" class="d-flex">
                    @csrf
                    <input class="form-control " type="text" name="name" id="" {{ isset($user)? "value=" . $user : ''}} placeholder="Название файла">
                    <button class="btn btn-primary mx-3" type="submit" name="filter">Поиск</button>
                </form>
            </div>
            <table class="table">
                <thead>
                  <tr>
                    <th scope="col">№</th>
                    <th scope="col">Название файла</th>
                    <th scope="col">Отправитель</th>
                    <th scope="col">Дата</th>
                    <th scope="col">Статус</th>
                    <th scope="col"></th>
                  </tr>
                </thead>
                <tbody>
                    @foreach ($list as $item)    
                        <tr>
                            <th scope="row">{{ $loop->index+1}}</th>
                            <td class="dowload-link"><a class="link-dark" href="{{ $item->status == 'отправлен' ? url('fileDowload','HASHSUM' . 'MD5' . $item->md5 . 'SHA1' . $item->sha1 . 'SHA512' .  $item->sha512 .  'FILE_NAME' . $item->file_name) : '' }}">{{$item->file_name}}</a></td>
                            <td>{{$item->user->name}}</td>
                            <td>{{$item->create_time}}</td>
                            <td>{{$item->status}}</td>
                            @if ($item->status == 'отправлен')
                                <td><button class="btn btn-secondary" onclick='editForm(<?php echo json_encode($item->md5); ?>, <?php echo json_encode($item->sha1); ?>, <?php echo json_encode($item->sha512); ?>, <?php echo json_encode($item->file_name); ?>)'>Изменить</button></td>
                            @endif  
                        </tr>
                    @endforeach
                </tbody>
              </table>
        </div>
        <div id='editForm' class="modal-backdrop">
            <div class="d-flex justify-content-center h-100 align-items-center"> 
                
                <div class="inner-editForm" id="innerForm">
                    <div class="d-flex w-100 justify-content-end ">
                        <img class="close-form-button" onclick="closeForm()" src="{{ asset('public\assets\image\svg\close-svgrepo-com.svg')}}" alt="">
                    </div>
                    <h4>Фаил:</h4>
                    <p id='fileName' class="dowload-link" style="max-width: 400px;"></p>
                    <div class="d-flex flex-column">
                        {{-- форма удаления --}}
                        <form role="form" action="{{ route('fileDelete') }}" autocomplete="off" method="POST" id="deletedForm">
                            @csrf
                            @method('DELETE')
                            <input type="text" id='md5' name="md5" hidden>
                            <input type="text" id='sha1' name="sha1" hidden>
                            <input type="text" id='sha512' name="sha512" hidden>
                        </form>
                        
                        {{-- форма изменения --}}
                        <form role="form" action="{{ route('editFile') }}" class="mx-3" autocomplete="off" enctype="multipart/form-data" method="POST" id="swapForm">
                            @csrf
                            <div class="upload-area dropzone" id="swapArea">
                                <img class="upload-image" src="{{ asset('public\assets\image\svg\file-upload-svgrepo-com.svg') }}" alt="">
                                <p>Для загрузки файлов перетащите их сюда.</p>
                            </div>
                            <input class="form-control my-3" type="file" id="fileEditInput" name="file" required>
                            <input type="text" id='md5edit' name="md5" hidden>
                            <input type="text" id='sha1edit' name="sha1" hidden>
                            <input type="text" id='sha512edit' name="sha512" hidden>
                            
                        </form>
                        <div>
                            <button type="submit" onclick="document.getElementById('deletedForm').submit()" class="btn btn-danger">Удалить</button>
                            <button type="submit" onclick="document.getElementById('swapForm').submit()" class="btn btn-primary">Заменить</button>
                        </div>
                    </div>
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
            function editForm(md5, sha1, sha512, fileName) {
                console.log(md5, sha1, sha512,fileName);
                document.getElementById('md5').value = md5;
                document.getElementById('sha1').value = sha1;
                document.getElementById('sha512').value = sha512;
                document.getElementById('md5edit').value = md5;
                document.getElementById('sha1edit').value = sha1;
                document.getElementById('sha512edit').value = sha512;
                document.getElementById('fileName').innerHTML = fileName;
                document.getElementById('editForm').style.display = 'block';
            }
        </script>
        
        <div class="ms-2">
            <h2 class="text-center">Загрузить фаил</h2>
            {{-- форма для загрузки файла --}}
            <form action="{{ route('fileUpload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf
                <div class="upload-area dropzone" id="uploadArea">
                    <img class="upload-image" src="{{ asset('public\assets\image\svg\file-upload-svgrepo-com.svg') }}" alt="">
                    <p>Для загрузки файлов перетащите их сюда.</p>
                </div>
                <input class="form-control my-3" type="file" id="fileInput" name="file" >
                @error('file')
                    <div class="alert">{{ $message }}</div>
                @enderror
                <button class="form-control" type="submit">Загрузить файл</button>
            </form>
            
            <script>
                //отслеживание файла для изменения
                document.getElementById('swapArea').addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.dataTransfer.dropEffect = 'copy';
                });
                //загрузка файла в форму для изменения
                document.getElementById('swapArea').addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('fileEditInput').files = e.dataTransfer.files;
                });

                //отслеживание файла для загрузки
                document.getElementById('uploadArea').addEventListener('dragover', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.dataTransfer.dropEffect = 'copy';
                });
                //загрузка файла в форму для загрузки
                document.getElementById('uploadArea').addEventListener('drop', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    document.getElementById('fileInput').files = e.dataTransfer.files;
                });
            </script>

        </div>
    </div>
    {{-- <div class="containet position-absolute bottom-0 ">
        <p>footer</p>
    </div> --}}
    @else
    <div class="d-flex flex-column justify-content-center align-items-center" style=" height: 80vh;">
        <h1>Необходим <a href="{{ url('loginPage') }}">вход</a>  с аккаунта Yandex</h1>
    </div>
@endif
@endsection
