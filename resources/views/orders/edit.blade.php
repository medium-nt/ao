@extends('layouts.app')

@section('title', 'Редактирование заказа')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Редактирование заказа — {{ $client->company_name }}</h1>
        <a href="{{ route('clients.show', $client) }}" class="btn btn-default">
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

                <div class="form-group" id="status-group">
                    <label for="status_id">Статус</label>
                    <select name="status_id" id="status_id"
                            class="form-control @error('status_id') is-invalid @enderror">
                        <option value="">—</option>
                    </select>
                    @error('status_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
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

@push('js')
    <script>
        const serviceStatuses = @json($services->mapWithKeys(fn($s) => [$s->id => $s->statuses]));
        const currentStatusId = {{ $order->status_id ?? 'null' }};
        const currentServiceId = {{ $order->service_id }};
        const statuses = serviceStatuses[currentServiceId] || [];
        const statusSelect = document.getElementById('status_id');
        const statusGroup = document.getElementById('status-group');

        statusSelect.innerHTML = '<option value="">—</option>';
        statuses.forEach(function (status) {
            const opt = document.createElement('option');
            opt.value = status.id;
            opt.textContent = status.title;
            if (status.id == currentStatusId) opt.selected = true;
            statusSelect.appendChild(opt);
        });

        statusGroup.style.display = statuses.length > 0 ? 'block' : 'none';
    </script>
@endpush
