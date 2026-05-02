@extends('layouts.app')

@section('title', $service->title)

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $service->title }}</h1>
        <a href="{{ route('services.index') }}" class="btn btn-default">
            <i class="fas fa-arrow-left"></i> Назад к списку
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
                            <th style="width: 200px;">Название</th>
                            <td>{{ $service->title }}</td>
                        </tr>
                        <tr>
                            <th>Описание</th>
                            <td>{{ $service->description ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    <a href="{{ route('services.edit', $service) }}" class="btn btn-primary">
                        <i class="fas fa-pen"></i> Редактировать
                    </a>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header clearfix">
                    <h3 class="card-title d-inline">Статусы</h3>
                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addStatusModal">
                        <i class="fas fa-plus"></i> Добавить статус
                    </button>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover" id="statuses-table">
                        <thead>
                            <tr>
                                <th style="width: 40px;"></th>
                                <th>Название</th>
                                <th class="text-right">Действия</th>
                            </tr>
                        </thead>
                        <tbody id="statuses-body">
                            @foreach ($service->statuses as $status)
                                <tr data-id="{{ $status->id }}">
                                    <td class="handle" style="cursor: grab;"><i class="fas fa-grip-vertical text-muted"></i></td>
                                    <td>{{ $status->title }}</td>
                                    <td class="text-right">
                                        <button type="button" class="btn btn-sm btn-secondary"
                                                onclick="openEditModal({{ $status->id }}, '{{ addslashes($status->title) }}')">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('service-statuses.destroy', [$service, $status]) }}" method="POST"
                                              style="display: inline-block;"
                                              onsubmit="return confirm('Удалить статус «{{ $status->title }}»?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            @if ($service->statuses->isEmpty())
                                <tr><td colspan="3" class="text-center text-muted py-3">Статусы не добавлены</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Добавить статус --}}
    <div class="modal fade" id="addStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('service-statuses.store', $service) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить статус</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="add-title">Название</label>
                            <input type="text" name="title" id="add-title" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Создать</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Modal: Редактировать статус --}}
    <div class="modal fade" id="editStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="edit-status-form" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Редактировать статус</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit-title">Название</label>
                            <input type="text" name="title" id="edit-title" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>
    <script>
        const reorderUrl = '{{ route('service-statuses.reorder', $service) }}';

        new Sortable(document.getElementById('statuses-body'), {
            handle: '.handle',
            animation: 150,
            onEnd: function () {
                const rows = document.querySelectorAll('#statuses-body tr[data-id]');
                const ids = Array.from(rows).map(row => parseInt(row.dataset.id));

                fetch(reorderUrl, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ ids })
                }).then(response => {
                    if (!response.ok) toastr.error('Ошибка при сохранении порядка.');
                });
            }
        });

        function openEditModal(id, title) {
            document.getElementById('edit-status-form').action =
                '{{ url("/services/" . $service->id . "/statuses") }}/' + id;
            document.getElementById('edit-title').value = title;
            $('#editStatusModal').modal('show');
        }
    </script>
@endpush
