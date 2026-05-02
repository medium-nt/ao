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
        <div class="col-lg-6">
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
        </div>
        <div class="col-lg-6">
            @foreach (\App\Enums\DocumentType::cases() as $docType)
                @php
                    $typeDocs = $client->documents->where('type', $docType);
                @endphp
                <div class="card {{ $loop->first ? '' : 'mt-3' }}">
                    <div class="card-header">
                        <h3 class="card-title mb-0">{{ $docType->label() }} <span class="badge badge-secondary">{{ $typeDocs->count() }}</span></h3>
                    </div>
                    <div class="card-body">
                        @if ($typeDocs->isNotEmpty())
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Файл</th>
                                        <th>Дата загрузки</th>
                                        <th>Статус</th>
                                        <th class="text-right">Действия</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($typeDocs as $doc)
                                        <tr>
                                            <td>{{ $doc->original_name }}</td>
                                            <td>{{ $doc->created_at->format('d.m.Y H:i') }}</td>
                                            <td>
                                                @if ($doc->is_approved)
                                                    <span class="badge badge-success">Одобрен</span>
                                                @else
                                                    <span class="badge badge-warning">Не одобрен</span>
                                                @endif
                                            </td>
                                            <td class="text-right">
                                                <a href="{{ route('client-documents.download', $doc) }}" class="btn btn-sm btn-secondary">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                                @if (! $doc->is_approved && auth()->user()->isAdmin())
                                                    <form action="{{ route('client-documents.approve', $doc) }}" method="POST" style="display: inline-block;">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Одобрить">
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
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted mb-0">Документы не загружены.</p>
                        @endif

                        <hr>
                        <form action="{{ route('client-documents.store', $client) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="type" value="{{ $docType->value }}">
                            <div class="form-inline">
                                <div class="input-group mr-2" style="flex: 1;">
                                    <div class="custom-file">
                                        <input type="file" name="file" id="file_{{ $docType->value }}" class="custom-file-input" required>
                                        <label class="custom-file-label" for="file_{{ $docType->value }}" data-browse="Выбрать">Выберите файл...</label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-upload"></i> Загрузить
                                </button>
                            </div>
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
