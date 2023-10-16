<?php

namespace App\Services\Classes\Admin;

use App\Services\BaseService;
use Inertia\Inertia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Request;
use App\Http\Requests\Admin\Address\AddressCreateRequest;
use App\Http\Requests\Admin\Address\AddressUpdateRequest;
use App\Models\Address;

final class AddressService extends BaseService
{
    public function index()
    {
        $addresses = Address::filter(Request::only('title'))
            ->orderBy('post_code', 'desc')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('Admin/Address/Index', [
            'filters' => Request::all('title'),
            'addresses' => $addresses,
        ]);
    }

    public function store(AddressCreateRequest $request)
    {
        $address = new Address($request->validated());

        $address->save();

        $this->log->add('create', 'address', $address->id, "$address->fullTitle");

        return to_route('Admin.AddressIndex')->with([
            'success' => 'Адрес успешно создан.'
        ]);
    }

    public function edit(int $id)
    {
        return Inertia::render('Admin/Address/Edit', [
            'address' => $this->getAddressByID($id)
        ]);
    }

    public function update(AddressUpdateRequest $request)
    {
        $address = $this->getAddressByID($request->id);

        $address->update($request->validated());

        $this->log->add('update', 'address', $address->id, "$address->fullTitle");

        return redirect()->back()->with([
            'success' => 'Адрес успешно обновлен.'
        ]);
    }

    public function remove(int $id)
    {
        try {
            $address = $this->getAddressByID($id);
        } catch (ModelNotFoundException $e) {
            return redirect()->back()->with(['error' => 'Адрес не найден.']);
        }

        $address->delete();

        $this->log->add('delete', 'address', $address->id, "$address->fullTitle");

        return to_route('Admin.AddressIndex')->with([
            'success' => 'Адрес успешно удален.'
        ]);
    }

    private function getAddressByID(int $id)
    {
        return Address::where('id', '=', $id)
            ->firstOrFail();
    }
}