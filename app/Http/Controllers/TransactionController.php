<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionRequest;
use App\Models\Client;
use App\Models\Order;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер управления финансовыми транзакциями заказа.
 */
class TransactionController extends Controller
{
    /**
     * Добавить транзакцию к заказу.
     */
    public function store(TransactionRequest $request, Client $client, Order $order): RedirectResponse
    {
        $order->transactions()->create($request->validated());

        return redirect()->route('orders.show', [$client, $order])->with('status', 'Транзакция успешно добавлена.');
    }

    /**
     * Удалить транзакцию.
     */
    public function destroy(Client $client, Order $order, Transaction $transaction): RedirectResponse
    {
        $transaction->delete();

        return redirect()->route('orders.show', [$client, $order])->with('status', 'Транзакция удалена.');
    }
}
