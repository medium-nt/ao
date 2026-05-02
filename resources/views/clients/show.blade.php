@extends('layouts.app')

@section('title', 'Карточка клиента')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $client->company_name }}</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <tr>
                    <th style="width: 200px;">Название компании</th>
                    <td>{{ $client->company_name }}</td>
                </tr>
                <tr>
                    <th>ФИО</th>
                    <td>{{ $client->name }}</td>
                </tr>
                <tr>
                    <th>Менеджер</th>
                    <td>{{ $client->manager?->name ?? '—' }}</td>
                </tr>
                <tr>
                    <th>Дата создания</th>
                    <td>{{ $client->created_at->format('d.m.Y H:i') }}</td>
                </tr>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">
                <i class="fas fa-pen"></i> Редактировать
            </a>
        </div>
    </div>
@stop
