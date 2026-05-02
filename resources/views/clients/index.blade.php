@extends('layouts.app')

@section('title', 'Клиенты')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Клиенты</h1>
        <a href="{{ route('clients.create') }}" class="btn btn-primary">
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
                        <th>Компания</th>
                        <th>ФИО</th>
                        <th>Менеджер</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->id }}</td>
                            <td>{{ $client->company_name }}</td>
                            <td>{{ $client->name }}</td>
                            <td>{{ $client->manager?->name ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('clients.show', $client) }}" class="btn btn-sm btn-secondary">
                                    <i class="fas fa-eye"></i> Просмотр
                                </a>
                                <form action="{{ route('clients.destroy', $client) }}" method="POST" style="display: inline-block;"
                                      onsubmit="return confirm('Удалить клиента {{ $client->company_name }}?');">
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
            {{ $clients->links() }}
        </div>
    </div>
@stop
