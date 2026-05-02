@extends('layouts.app')

@section('title', 'Услуги')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Услуги</h1>
        <a href="{{ route('services.create') }}" class="btn btn-primary">
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
                        <th>Название</th>
                        <th>Описание</th>
                        <th class="text-right">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($services as $service)
                        <tr>
                            <td>{{ $service->id }}</td>
                            <td>{{ $service->title }}</td>
                            <td>{{ $service->description ?? '—' }}</td>
                            <td class="text-right">
                                <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> Просмотр
                                </a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST" style="display: inline-block;"
                                      onsubmit="return confirm('Удалить услугу «{{ $service->title }}»?');">
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
            {{ $services->links() }}
        </div>
    </div>
@stop
