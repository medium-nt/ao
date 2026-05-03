<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер управления заказами клиентов.
 */
class OrderController extends Controller
{
    /**
     * Показать форму создания нового заказа.
     */
    public function create(Client $client): Renderable
    {
        $this->authorizeClientAccess($client);
        $services = Service::with('statuses')->orderBy('title')->get();

        return view('orders.create', compact('client', 'services'));
    }

    /**
     * Сохранить новый заказ в базу данных.
     * Если статус не выбран — автоматически назначается первый статус услуги.
     */
    public function store(OrderRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeClientAccess($client);

        $data = $request->validated();
        $data['client_id'] = $client->id;

        if (empty($data['status_id'])) {
            $firstStatus = ServiceStatus::where('service_id', $data['service_id'])
                ->orderBy('sort_order')
                ->first();
            $data['status_id'] = $firstStatus?->id;
        }

        Order::create($data);

        return redirect()->route('clients.show', $client)->with('status', 'Заказ успешно создан.');
    }

    /**
     * Показать форму редактирования заказа.
     */
    public function edit(Client $client, Order $order): Renderable
    {
        $this->authorizeClientAccess($client);
        $services = Service::with('statuses')->orderBy('title')->get();

        return view('orders.edit', compact('client', 'order', 'services'));
    }

    /**
     * Обновить данные заказа в базе данных.
     */
    public function update(OrderRequest $request, Client $client, Order $order): RedirectResponse
    {
        $this->authorizeClientAccess($client);
        $order->update($request->validated());

        return redirect()->route('clients.show', $client)->with('status', 'Заказ успешно обновлён.');
    }

    /**
     * Удалить заказ из базы данных.
     */
    public function destroy(Client $client, Order $order): RedirectResponse
    {
        $this->authorizeClientAccess($client);
        $order->delete();

        return redirect()->route('clients.show', $client)->with('status', 'Заказ успешно удалён.');
    }

    /**
     * Проверить доступ к клиенту: менеджер может работать только со своими клиентами.
     */
    private function authorizeClientAccess(Client $client): void
    {
        if (! auth()->user()->isAdmin() && $client->manager_id !== auth()->id()) {
            abort(403);
        }
    }
}
