<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClientDocumentRequest;
use App\Models\Client;
use App\Models\ClientDocument;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Контроллер управления документами клиентов.
 */
class ClientDocumentController extends Controller
{
    /**
     * Загрузить документ для клиента.
     */
    public function store(ClientDocumentRequest $request, Client $client): RedirectResponse
    {
        $this->authorizeClientAccess($client);

        $file = $request->file('file');
        $type = $request->validated('type');
        $fileName = uniqid().'.'.$file->getClientOriginalExtension();
        $directory = "clients/{$client->id}/{$type}";

        Storage::putFileAs($directory, $file, $fileName);

        $client->documents()->create([
            'type' => $type,
            'original_name' => $file->getClientOriginalName(),
            'file_name' => $fileName,
            'is_approved' => false,
        ]);

        return redirect()->route('clients.show', $client)->with('status', 'Документ успешно загружен.');
    }

    /**
     * Скачать документ.
     */
    public function download(ClientDocument $document): StreamedResponse
    {
        $this->authorizeClientAccess($document->client);

        return Storage::download($document->filePath(), $document->original_name);
    }

    /**
     * Удалить документ.
     */
    public function destroy(ClientDocument $document): RedirectResponse
    {
        $this->authorizeClientAccess($document->client);
        $client = $document->client;

        Storage::delete($document->filePath());
        $document->delete();

        return redirect()->route('clients.show', $client)->with('status', 'Документ успешно удалён.');
    }

    /**
     * Одобрить документ (только для админа).
     */
    public function approve(ClientDocument $document): RedirectResponse
    {
        if (! auth()->user()->isAdmin()) {
            abort(403);
        }

        $document->update(['is_approved' => true]);

        return redirect()->route('clients.show', $document->client)->with('status', 'Документ одобрен.');
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
