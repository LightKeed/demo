<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Address\AddressCreateRequest;
use App\Http\Requests\Admin\Address\AddressUpdateRequest;
use App\Services\Classes\Admin\AddressService;

class AddressController extends Controller
{
    private AddressService $service;

    public function __construct(AddressService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return $this->service->index();
    }

    public function store(AddressCreateRequest $request)
    {
        return $this->service->store($request);
    }

    public function edit(int $id)
    {
        return $this->service->edit($id);
    }

    public function update(AddressUpdateRequest $request)
    {
        return $this->service->update($request);
    }

    public function destroy(int $id)
    {
        return $this->service->remove($id);
    }
}
