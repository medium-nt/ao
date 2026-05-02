<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Http\Requests\ClientRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер управления клиентами.
 */
class ClientController extends Controller
{
    /**
     * Отобразить список клиентов.
     */
    public function index(): Renderable
    {
        $clients = Client::with('manager')->orderBy('id')->paginate(15);

        return view('clients.index', compact('clients'));
    }

    /**
     * Показать форму создания нового клиента.
     */
    public function create(): Renderable
    {
        $managers = User::where('role', Role::Manager)->orderBy('name')->get();

        return view('clients.create', compact('managers'));
    }

    /**
     * Сохранить нового клиента в базу данных.
     */
    public function store(ClientRequest $request): RedirectResponse
    {
        Client::create($request->validated());

        return redirect()->route('clients.index')->with('status', 'Клиент успешно создан.');
    }

    /**
     * Показать карточку клиента.
     */
    public function show(Client $client): Renderable
    {
        $client->load('manager');

        return view('clients.show', compact('client'));
    }

    /**
     * Показать форму редактирования клиента.
     */
    public function edit(Client $client): Renderable
    {
        $managers = User::where('role', Role::Manager)->orderBy('name')->get();

        return view('clients.edit', compact('client', 'managers'));
    }

    /**
     * Обновить данные клиента в базе данных.
     */
    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $client->update($request->validated());

        return redirect()->route('clients.index')->with('status', 'Клиент успешно обновлён.');
    }

    /**
     * Удалить клиента из базы данных.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('clients.index')->with('status', 'Клиент успешно удалён.');
    }
}
