@extends('layouts.app')

@section('title', 'Редактирование заказа')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Редактирование заказа — {{ $client->company_name }}</h1>
        <a href="{{ route('orders.show', [$client, $order]) }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Назад
        </a>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <form action="{{ route('orders.update', [$client, $order]) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label>Услуга</label>
                    <input type="text" class="form-control" value="{{ $order->service->title }}" disabled>
                    <input type="hidden" name="service_id" value="{{ $order->service_id }}">
                </div>

                <div class="form-group">
                    <label>Текущий статус</label>
                    <input type="text" class="form-control" value="{{ $order->status?->title ?? '—' }}" disabled>
                </div>

                <div class="row">
                    <div class="col-sm-6 form-group">
                        <label for="start_date">Дата начала</label>
                        <input type="date" name="start_date" id="start_date"
                               class="form-control @error('start_date') is-invalid @enderror"
                               value="{{ old('start_date', $order->start_date->format('Y-m-d')) }}" required>
                        @error('start_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-sm-6 form-group">
                        <label for="end_date">Дата завершения</label>
                        <input type="date" name="end_date" id="end_date"
                               class="form-control @error('end_date') is-invalid @enderror"
                               value="{{ old('end_date', $order->end_date?->format('Y-m-d')) }}">
                        @error('end_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="price">Стоимость</label>
                    <input type="number" name="price" id="price"
                           class="form-control @error('price') is-invalid @enderror"
                           step="0.01" min="0"
                           value="{{ old('price', $order->price) }}" required>
                    @error('price')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="note">Примечание</label>
                    <textarea name="note" id="note" rows="3"
                              class="form-control @error('note') is-invalid @enderror">{{ old('note', $order->note) }}</textarea>
                    @error('note')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Сохранить
                </button>
            </form>
        </div>
    </div>
@stop

