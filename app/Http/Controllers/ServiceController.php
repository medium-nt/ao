<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

/**
 * Контроллер управления услугами.
 */
class ServiceController extends Controller
{
    /**
     * Отобразить список услуг.
     */
    public function index(): Renderable
    {
        $services = Service::withCount('orders')->orderBy('id')->paginate(15);

        return view('services.index', compact('services'));
    }

    /**
     * Показать карточку услуги со статусами.
     */
    public function show(Service $service): Renderable
    {
        $service->load('statuses');

        return view('services.show', compact('service'));
    }

    /**
     * Показать форму создания новой услуги.
     */
    public function create(): Renderable
    {
        return view('services.create');
    }

    /**
     * Сохранить новую услугу в базу данных.
     */
    public function store(StoreServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());

        return redirect()->route('services.index')->with('status', 'Услуга успешно создана.');
    }

    /**
     * Показать форму редактирования услуги.
     */
    public function edit(Service $service): Renderable
    {
        return view('services.edit', compact('service'));
    }

    /**
     * Обновить данные услуги в базе данных.
     */
    public function update(UpdateServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());

        return redirect()->route('services.show', $service)->with('status', 'Услуга успешно обновлена.');
    }

    /**
     * Удалить услугу из базы данных.
     */
    public function destroy(Service $service): RedirectResponse
    {
        if ($service->orders()->exists()) {
            return redirect()->route('services.index')
                ->with('error', 'Нельзя удалить услугу, у которой есть заказы.');
        }

        $service->delete();

        return redirect()->route('services.index')
            ->with('status', 'Услуга успешно удалена.');
    }
}
