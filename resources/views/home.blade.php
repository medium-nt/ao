@extends('layouts.app')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    @if (auth()->user()->isAdmin())
        {{-- Фильтры --}}
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('home') }}" class="form-inline">
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Год</label>
                        <select name="year" class="form-control form-control-sm">
                            <option value="" {{ !$filters['year'] ? 'selected' : '' }}>Все</option>
                            @foreach ($years as $year)
                                <option value="{{ $year }}" {{ $filters['year'] == $year ? 'selected' : '' }}>{{ $year }}</option>
                            @endforeach
                            @if ($years->isEmpty())
                                <option value="{{ now()->year }}" selected>{{ now()->year }}</option>
                            @endif
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Месяц</label>
                        <select name="month" class="form-control form-control-sm">
                            <option value="" {{ !$filters['month'] ? 'selected' : '' }}>Все</option>
                            @for ($m = 1; $m <= 12; $m++)
                                <option value="{{ $m }}" {{ $filters['month'] == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->translatedFormat('F') }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Услуга</label>
                        <select name="service_id" class="form-control form-control-sm">
                            <option value="">Все</option>
                            @foreach ($allServices as $s)
                                <option value="{{ $s->id }}" {{ $filters['service_id'] == $s->id ? 'selected' : '' }}>{{ $s->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <label class="mr-1">Исполнитель</label>
                        <select name="manager_id" class="form-control form-control-sm">
                            <option value="">Все</option>
                            @foreach ($managers as $m)
                                <option value="{{ $m->id }}" {{ $filters['manager_id'] == $m->id ? 'selected' : '' }}>{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-sm btn-primary mb-2"><i class="fas fa-filter"></i> Фильтр</button>
                    <a href="{{ route('home') }}" class="btn btn-sm btn-default mb-2 ml-1">Сбросить</a>
                </form>
            </div>
        </div>

        {{-- Итого --}}
        <div class="row mt-3">
            <div class="col-lg-4 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($totals['price'], 2, ',', ' ') }} ₽</h3>
                        <p>Сумма всех услуг</p>
                    </div>
                    <div class="icon"><i class="fas fa-coins"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($totals['paid'], 2, ',', ' ') }} ₽</h3>
                        <p>Сумма оплат</p>
                    </div>
                    <div class="icon"><i class="fas fa-money-bill-wave"></i></div>
                </div>
            </div>
            <div class="col-lg-4 col-6">
                <div class="small-box {{ $totals['debt'] > 0 ? 'bg-danger' : 'bg-success' }}">
                    <div class="inner">
                        <h3>{{ number_format($totals['debt'], 2, ',', ' ') }} ₽</h3>
                        <p>Долг</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                </div>
            </div>
        </div>

        {{-- Таблицы по услугам --}}
        @foreach ($services as $service)
            @php
                $serviceOrders = $orders->where('service_id', $service->id);
            @endphp
            <div class="card mt-3">
                <div class="card-header">
                    <h3 class="card-title">{{ $service->title }} <span class="badge badge-secondary">{{ $serviceOrders->count() }}</span></h3>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>Дата услуги</th>
                                <th>Компания</th>
                                @foreach ($service->statuses as $status)
                                    <th class="text-center">{{ $status->title }}</th>
                                @endforeach
                                <th>Стоимость</th>
                                <th>Оплаты</th>
                                <th>Долг</th>
                                <th>Менеджер</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($serviceOrders as $order)
                                @php
                                    $paid = $order->transactions->sum('amount');
                                    $debt = $order->price - $paid;
                                    $currentStatusIndex = $service->statuses->search(fn ($s) => $s->id === $order->status_id);
                                @endphp
                                <tr>
                                    <td>{{ $order->start_date->format('d.m.Y') }}</td>
                                    <td>
                                        <a href="{{ route('clients.show', $order->client) }}">
                                            {{ $order->client->company_name }}
                                        </a>
                                    </td>
                                    @foreach ($service->statuses as $statusIndex => $status)
                                        <td class="text-center">
                                            @if ($statusIndex <= $currentStatusIndex)
                                                <i class="fas fa-check-circle text-success"></i>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td>{{ number_format($order->price, 2, ',', ' ') }} ₽</td>
                                    <td>{{ number_format($paid, 2, ',', ' ') }} ₽</td>
                                    <td class="{{ $debt > 0 ? 'text-danger font-weight-bold' : 'text-success' }}">
                                        {{ number_format($debt, 2, ',', ' ') }} ₽
                                    </td>
                                    <td>{{ $order->client->manager?->name ?? '—' }}</td>
                                </tr>
                            @endforeach
                            @if ($serviceOrders->isEmpty())
                                <tr><td colspan="{{ 4 + $service->statuses->count() }}" class="text-center text-muted">Нет заказов</td></tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

        @if ($services->isEmpty())
            <div class="card mt-3">
                <div class="card-body text-center text-muted py-4">
                    Нет данных за выбранный период.
                </div>
            </div>
        @endif
    @else
        <div class="card">
            <div class="card-body text-center text-muted py-5">
                <h4>Добро пожаловать!</h4>
                <p>Перейдите в раздел «Клиенты» для работы.</p>
            </div>
        </div>
    @endif
@stop
