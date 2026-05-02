@extends('layouts.app')

@section('title', 'Создание услуги')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Создание услуги</h1>
        <a href="{{ route('services.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('services.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="title">Название</label>
                    <input type="text" name="title" id="title"
                           class="form-control @error('title') is-invalid @enderror"
                           value="{{ old('title') }}" required>
                    @error('title')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Описание</label>
                    <textarea name="description" id="description" rows="4"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
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
