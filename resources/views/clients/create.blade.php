@extends('layouts.app')

@section('title', 'Создание клиента')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Создание клиента</h1>
        <a href="{{ route('clients.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('clients.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="company_name">Название компании</label>
                    <input type="text" name="company_name" id="company_name"
                           class="form-control @error('company_name') is-invalid @enderror"
                           value="{{ old('company_name') }}" required>
                    @error('company_name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">ФИО</label>
                    <input type="text" name="name" id="name"
                           class="form-control @error('name') is-invalid @enderror"
                           value="{{ old('name') }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="manager_id">Менеджер</label>
                    <select name="manager_id" id="manager_id"
                            class="form-control @error('manager_id') is-invalid @enderror">
                        <option value="">— Не назначен —</option>
                        @foreach ($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id') == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Создать
                </button>
            </form>
        </div>
    </div>
@stop
