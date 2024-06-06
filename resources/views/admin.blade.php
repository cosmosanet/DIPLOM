@extends('layouts.app')

@section('content')
    <div class="container p-3">
      <div class="d-flex justify-content-between  ">
          <form class="mx-3" action="{{ route('admin') }}" method="get">
            @csrf
            <button class="btn btn-primary" type="submit" name="filter" value="{{ "fileDowload" }}">Скачивания</button>
            <button class="btn btn-primary" type="submit" name="filter" value="{{ "registerFile" }}">Загрузки</button>
            <button class="btn btn-primary" type="submit" name="filter" value="{{ "deleteFile" }}">Удаления</button>
            <button class="btn btn-primary" type="submit" name="filter" value="{{ "editFile" }}">Замены</button>
            <button class="btn btn-primary" type="submit" name="filter" value="{{ "" }}">Все</button>
          </form>
          <form action="{{ route('admin') }}" method="get" class="d-flex">
            @csrf
            <input class="form-control " type="text" name="user" id="" {{ isset($user)? "value=" . $user : ''}} placeholder="Пользователь">
            <button class="btn btn-primary mx-3" type="submit" name="filter" value="{{ $filter }}">Поиск</button>
          </form>
         
      </div>
        <table class="table">
            <thead>
              <tr>
                <th scope="col">№</th>
                <th scope="col">Пользователь</th>
                <th scope="col">Лог</th>
                <th scope="col">Действие</th>
                <th scope="col">Время создания</th>
              </tr>
            </thead>
            <tbody>
             
                @foreach ($logs as $logItem)      
                    <tr>
                        <th scope="row">{{ $loop->index+1}}</th>
                        <td>{{$logItem->user->name}}</td>
                        <td><p style="word-wrap: break-word; width: 500px">{{$logItem->log_text}}</p></td>
                        <td>{{$logItem->action}}</td>
                        <td>{{$logItem->log_create_time }}</td>
                    </tr>
                @endforeach
            </tbody>
          </table>
    </div>
@endsection
