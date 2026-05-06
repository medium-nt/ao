@extends('layouts.app')

@section('title', 'Карточка заказа')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $order->service->title }} — {{ $client->company_name }}</h1>
        <a href="{{ route('clients.show', $client) }}" class="btn btn-default">
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
                            <th style="width: 200px;">Услуга</th>
                            <td>{{ $order->service->title }}</td>
                        </tr>
                        <tr>
                            <th>Текущий статус</th>
                            <td>
                                @if ($order->status)
                                    <span class="badge badge-info">{{ $order->status->title }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Дата начала</th>
                            <td>{{ $order->start_date->format('d.m.Y') }}</td>
                        </tr>
                        <tr>
                            <th>Дата завершения</th>
                            <td>{{ $order->end_date?->format('d.m.Y') ?? '—' }}</td>
                        </tr>
                        <tr>
                            <th>Стоимость</th>
                            <td>{{ number_format($order->price, 2, ',', ' ') }}</td>
                        </tr>
                        <tr>
                            <th>Примечание</th>
                            <td>{{ $order->note ?? '—' }}</td>
                        </tr>
                    </table>
                </div>
                <div class="card-footer">
                    @if ($nextStatus)
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#changeStatusModal">
                            <i class="fas fa-forward"></i> Сменить статус
                        </button>
                    @endif
                    <a href="{{ route('orders.edit', [$client, $order]) }}" class="btn btn-primary">
                        <i class="fas fa-pen"></i> Редактировать
                    </a>
                    @if (auth()->user()->isAdmin())
                        <form action="{{ route('orders.destroy', [$client, $order]) }}" method="POST" style="display: inline-block;"
                              onsubmit="return confirm('Удалить заказ «{{ $order->service->title }}»?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-trash"></i> Удалить
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            @php
                $paid = $order->transactions->sum('amount');
                $debt = $order->price - $paid;
            @endphp

            <div class="card">
                <div class="card-header clearfix">
                    <h3 class="card-title d-inline">Финансы</h3>
                    <button type="button" class="btn btn-sm btn-primary float-right" data-toggle="modal" data-target="#addTransactionModal">
                        <i class="fas fa-plus"></i> Добавить платёж
                    </button>
                </div>
                <div class="card-body">
                    <table class="table table-bordered mb-3">
                        <tr>
                            <th style="width: 200px;">Стоимость</th>
                            <td>{{ number_format($order->price, 2, ',', ' ') }} ₽</td>
                        </tr>
                        <tr>
                            <th>Оплачено</th>
                            <td>{{ number_format($paid, 2, ',', ' ') }} ₽</td>
                        </tr>
                        <tr class="{{ $debt > 0 ? 'table-danger' : 'table-success' }}">
                            <th>Долг</th>
                            <td>{{ number_format($debt, 2, ',', ' ') }} ₽</td>
                        </tr>
                    </table>

                    @if ($order->transactions->isNotEmpty())
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Дата</th>
                                    <th>Тип</th>
                                    <th>Сумма</th>
                                    <th>Комментарий</th>
                                    <th class="text-right">Действия</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('d.m.Y H:i') }}</td>
                                        <td>
                                            @if ($transaction->type === \App\Models\Transaction::TYPE_PREPAYMENT)
                                                <span class="badge badge-warning">Предоплата</span>
                                            @else
                                                <span class="badge badge-success">Оплата</span>
                                            @endif
                                        </td>
                                        <td>{{ number_format($transaction->amount, 2, ',', ' ') }} ₽</td>
                                        <td>{{ $transaction->note ?? '—' }}</td>
                                        <td class="text-right">
                                            <form action="{{ route('transactions.destroy', [$client, $order, $transaction]) }}" method="POST"
                                                  style="display: inline-block;"
                                                  onsubmit="return confirm('Удалить транзакцию?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pipeline услуги</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-sm">
                        @foreach ($order->service->statuses as $s)
                            <tr class="{{ $s->id === $order->status_id ? 'table-info font-weight-bold' : '' }}">
                                <td style="width: 30px; text-align: center;">
                                    @if ($s->id === $order->status_id)
                                        <i class="fas fa-arrow-right text-info"></i>
                                    @endif
                                </td>
                                <td>{{ $s->title }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">История смены статусов</h3>
                </div>
                <div class="card-body">
                    @if ($order->histories->isNotEmpty())
                        <div class="timeline">
                            @foreach ($order->histories->groupBy(fn ($h) => $h->created_at->format('d.m.Y')) as $date => $histories)
                                <div class="time-label">
                                    <span class="bg-info">{{ $date }}</span>
                                </div>
                                @foreach ($histories as $history)
                                    <div>
                                        <i class="fas fa-arrow-right bg-info"></i>
                                        <div class="timeline-item">
                                            <span class="time"><i class="fas fa-clock"></i> {{ $history->created_at->format('H:i') }}</span>
                                            <h3 class="timeline-header font-weight-bold">{{ $history->status_title }}</h3>
                                            <div class="timeline-body">
                                                {{ $history->note ?? '—' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endforeach
                            @if ($nextStatus)
                                <div>
                                    <i class="fas fa-clock bg-gray"></i>
                                </div>
                            @endif
                        </div>
                    @else
                        <p class="text-muted text-center py-3 mb-0">Статус ещё не менялся.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal: Сменить статус --}}
    @if ($nextStatus)
        <div class="modal fade" id="changeStatusModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="{{ route('orders.change-status', [$client, $order]) }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Смена статуса</h5>
                            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info mb-3">
                                <strong>{{ $order->status?->title ?? '—' }}</strong>
                                &rarr;
                                <strong>{{ $nextStatus->title }}</strong>
                            </div>
                            <div class="form-group">
                                <label for="note">Комментарий</label>
                                <textarea name="note" id="note" rows="3" class="form-control"
                                          placeholder="Что было сделано для перехода на следующий этап..." required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-forward"></i> Перенести
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal: Добавить платёж --}}
    <div class="modal fade" id="addTransactionModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('transactions.store', [$client, $order]) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Добавить платёж</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="txn-type">Тип</label>
                            <select name="type" id="txn-type" class="form-control" required>
                                <option value="{{ \App\Models\Transaction::TYPE_PREPAYMENT }}">Предоплата</option>
                                <option value="{{ \App\Models\Transaction::TYPE_PAYMENT }}">Оплата</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="txn-amount">Сумма</label>
                            <input type="number" name="amount" id="txn-amount" class="form-control"
                                   step="0.01" min="0.01" required>
                        </div>
                        <div class="form-group">
                            <label for="txn-note">Комментарий</label>
                            <input type="text" name="note" id="txn-note" class="form-control"
                                   maxlength="255">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
                        <button type="submit" class="btn btn-primary">Добавить</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
