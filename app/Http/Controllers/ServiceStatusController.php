<?php

namespace App\Http\Controllers;

use App\Http\Requests\ServiceStatusRequest;
use App\Models\Service;
use App\Models\ServiceStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Контроллер управления статусами услуги.
 */
class ServiceStatusController extends Controller
{
    /**
     * Создать новый статус услуги.
     */
    public function store(ServiceStatusRequest $request, Service $service): RedirectResponse
    {
        $maxOrder = $service->statuses()->max('sort_order') ?? 0;

        $service->statuses()->create([
            'title' => $request->validated('title'),
            'sort_order' => $maxOrder + 1,
        ]);

        return redirect()->route('services.show', $service)->with('status', 'Статус успешно добавлен.');
    }

    /**
     * Обновить название статуса услуги.
     */
    public function update(ServiceStatusRequest $request, Service $service, ServiceStatus $status): RedirectResponse
    {
        $status->update($request->validated());

        return redirect()->route('services.show', $service)->with('status', 'Статус успешно обновлён.');
    }

    /**
     * Удалить статус услуги.
     */
    public function destroy(Service $service, ServiceStatus $status): RedirectResponse
    {
        $status->delete();

        return redirect()->route('services.show', $service)->with('status', 'Статус успешно удалён.');
    }

    /**
     * Обновить порядок статусов (AJAX).
     */
    public function reorder(Request $request, Service $service): JsonResponse
    {
        $ids = $request->validate([
            'ids' => ['required', 'array'],
            'ids.*' => ['integer', 'exists:service_statuses,id'],
        ])['ids'];

        foreach ($ids as $order => $id) {
            ServiceStatus::where('id', $id)->where('service_id', $service->id)->update(['sort_order' => $order + 1]);
        }

        return response()->json(['ok' => true]);
    }
}
