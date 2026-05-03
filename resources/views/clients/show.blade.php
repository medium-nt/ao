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
    <div class="row">
        <div class="col-lg-8">
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

            <div class="card mt-3">
                <div class="card-header clearfix">
                    <h3 class="card-title d-inline">Заказы</h3>
                    <a href="{{ route('orders.create', $client) }}" class="btn btn-sm btn-primary float-right">
                        <i class="fas fa-plus"></i> Добавить заказ
                    </a>
                </div>
                <div class="card-body table-responsive p-0">
                    @if ($client->orders->isNotEmpty())
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Услуга</th>
                                    <th>Статус</th>
                                    <th>Даты</th>
                                    <th>Стоимость</th>
                                    <th>Примечание</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($client->orders as $order)
                                    <tr>
                                        <td><a href="{{ route('orders.show', [$client, $order]) }}">{{ $order->service->title }}</a></td>
                                        <td>{{ $order->status?->title ?? '—' }}</td>
                                        <td>{{ $order->start_date->format('d.m.Y') }}{{ $order->end_date ? ' — ' . $order->end_date->format('d.m.Y') : '' }}</td>
                                        <td>{{ number_format($order->price, 2, ',', ' ') }}</td>
                                        <td>{{ $order->note ?? '—' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted text-center py-3 mb-0">Заказы не добавлены.</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            @foreach (\App\Enums\DocumentType::cases() as $docType)
                @php
                    $typeDocs = $client->documents->where('type', $docType);
                @endphp
                <div class="card {{ $loop->first ? '' : 'mt-3' }}">
                    <div class="card-header">
                        <h3 class="card-title mb-0">{{ $docType->label() }} <span class="badge badge-secondary">{{ $typeDocs->count() }}</span></h3>
                    </div>
                    <div class="card-body p-0">
                        @if ($typeDocs->isNotEmpty())
                            <ul class="list-group list-group-flush">
                                @foreach ($typeDocs as $doc)
                                    <li class="list-group-item d-flex justify-content-between align-items-center py-2">
                                        <div class="text-truncate mr-2" style="max-width: 60%;">
                                            <span class="d-block text-truncate">{{ $doc->original_name }}</span>
                                            <small class="text-muted">{{ $doc->created_at->format('d.m.Y') }}</small>
                                            @if ($doc->is_approved)
                                                <span class="badge badge-success">Одобрен</span>
                                            @else
                                                <span class="badge badge-warning">Не одобрен</span>
                                            @endif
                                        </div>
                                        <div class="d-flex align-items-center text-nowrap">
                                            <a href="{{ route('client-documents.download', $doc) }}" class="btn btn-sm btn-secondary mr-1" title="Скачать">
                                                <i class="fas fa-download"></i>
                                            </a>
                                            @if (! $doc->is_approved && auth()->user()->isAdmin())
                                                <form action="{{ route('client-documents.approve', $doc) }}" method="POST" style="display: inline-block;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success mr-1" title="Одобрить">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('client-documents.destroy', $doc) }}" method="POST" style="display: inline-block;"
                                                  onsubmit="return confirm('Удалить документ {{ $doc->original_name }}?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Удалить">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted mb-0 px-3 py-2">Документы не загружены.</p>
                        @endif
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('client-documents.store', $client) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ $docType->value }}">
                            <div class="custom-file mb-2">
                                <input type="file" name="file" id="file_{{ $docType->value }}" class="custom-file-input" required>
                                <label class="custom-file-label" for="file_{{ $docType->value }}" data-browse="Выбрать">Выберите файл...</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fas fa-upload"></i> Загрузить
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@push('js')
    <script>
        document.querySelectorAll('.custom-file-input').forEach(function(input) {
            input.addEventListener('change', function() {
                var fileName = this.files[0] ? this.files[0].name : 'Выберите файл...';
                var label = this.nextElementSibling;
                label.textContent = fileName;
            });
        });
    </script>
@endpush
