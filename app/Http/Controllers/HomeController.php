<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Показать дашборд со статистикой (только для админа).
     */
    public function index(Request $request): Renderable
    {
        if (! auth()->user()->isAdmin()) {
            return view('home');
        }

        $managers = User::where('role', Role::Manager)->orderBy('name')->get();
        $allServices = Service::with('statuses')->orderBy('title')->get();

        $years = Order::pluck('start_date')
            ->map(fn ($date) => $date?->year)
            ->filter()
            ->unique()
            ->sortDesc()
            ->values();

        $filters = [
            'year' => $request->input('year', now()->year),
            'month' => $request->input('month', now()->month),
            'service_id' => $request->input('service_id', ''),
            'manager_id' => $request->input('manager_id', ''),
        ];

        $orders = Order::with(['client.manager', 'service.statuses', 'status', 'transactions'])
            ->when($filters['year'], fn ($q) => $q->whereYear('start_date', $filters['year']))
            ->when($filters['month'], fn ($q) => $q->whereMonth('start_date', $filters['month']))
            ->when($filters['service_id'], fn ($q) => $q->where('service_id', $filters['service_id']))
            ->when($filters['manager_id'], fn ($q) => $q->whereHas('client', fn ($q) => $q->where('manager_id', $filters['manager_id'])))
            ->orderBy('start_date')
            ->get();

        $totals = [
            'price' => $orders->sum('price'),
            'paid' => $orders->sum(fn ($order) => $order->transactions->sum('amount')),
        ];
        $totals['debt'] = $totals['price'] - $totals['paid'];

        $services = $allServices->filter(fn ($service) => $orders->contains('service_id', $service->id));

        return view('home', compact('services', 'orders', 'filters', 'totals', 'managers', 'allServices', 'years'));
    }
}
