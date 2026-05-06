@extends('layouts.app')

@section('title', 'Новый заказ')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Новый заказ — {{ $client->company_name }}</h1>
        <a href="{{ route('clients.show', $client) }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('orders.store', $client) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="service_id">Услуга</label>
                    <select name="service_id" id="service_id"
                            class="form-control @error('service_id') is-invalid @enderror" required>
                        <option value="">Выберите услугу</option>
                        @foreach ($services as $service)
                            <option value="{{ $service->id }}" {{ old('service_id') == $service->id ? 'selected' : '' }}>
                                {{ $service->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('service_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="start_date">Дата начала</label>
                    <input type="date" name="start_date" id="start_date"
                           class="form-control @error('start_date') is-invalid @enderror"
                           value="{{ old('start_date') }}" required>
                    @error('start_date')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Стоимость</label>
                    <input type="number" name="price" id="price"
                           class="form-control @error('price') is-invalid @enderror"
                           step="0.01" min="0"
                           value="{{ old('price') }}" required>
                    @error('price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="note">Примечание</label>
                    <textarea name="note" id="note" rows="3"
                              class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>
                    @error('note')
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
