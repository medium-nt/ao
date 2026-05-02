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
     * Админ видит всех, менеджер — только своих.
     */
    public function index(): Renderable
    {
        $query = Client::with('manager')->orderBy('id');

        if (! auth()->user()->isAdmin()) {
            $query->where('manager_id', auth()->id());
        }

        $clients = $query->paginate(15);

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
     * Менеджеру автоматически проставляется его ID.
     */
    public function store(ClientRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if (! auth()->user()->isAdmin()) {
            $data['manager_id'] = auth()->id();
        }

        Client::create($data);

        return redirect()->route('clients.index')->with('status', 'Клиент успешно создан.');
    }

    /**
     * Показать карточку клиента.
     */
    public function show(Client $client): Renderable
    {
        $this->authorizeAccess($client);
        $client->load('manager', 'documents');

        return view('clients.show', compact('client'));
    }

    /**
     * Показать форму редактирования клиента.
     */
    public function edit(Client $client): Renderable
    {
        $this->authorizeAccess($client);
        $managers = User::where('role', Role::Manager)->orderBy('name')->get();

        return view('clients.edit', compact('client', 'managers'));
    }

    /**
     * Обновить данные клиента в базе данных.
     */
    public function update(ClientRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeAccess($client);
        $data = $request->validated();

        if (! auth()->user()->isAdmin()) {
            $data['manager_id'] = auth()->id();
        }

        $client->update($data);

        return redirect()->route('clients.show', $client)->with('status', 'Клиент успешно обновлён.');
    }

    /**
     * Удалить клиента из базы данных.
     */
    public function destroy(Client $client): RedirectResponse
    {
        $this->authorizeAccess($client);
        $client->delete();

        return redirect()->route('clients.index')->with('status', 'Клиент успешно удалён.');
    }

    /**
     * Проверить доступ: менеджер может работать только со своими клиентами.
     */
    private function authorizeAccess(Client $client): void
    {
        if (! auth()->user()->isAdmin() && $client->manager_id !== auth()->id()) {
            abort(403);
        }
    }
}
