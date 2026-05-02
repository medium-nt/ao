@extends('layouts.app')

@section('title', 'Пользователи')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Пользователи</h1>
        <a href="{{ route('users.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Создать
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ФИО</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role->label() }}</td>
                            <td class="text-right">
                                <a href="{{ route('users.edit', $user) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-pen"></i> Редактировать
                                </a>
                                <form action="{{ route('users.destroy', $user) }}" method="POST" style="display: inline-block;"
                                      onsubmit="return confirm('Удалить пользователя {{ $user->name }}?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i> Удалить
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    </div>
@stop
