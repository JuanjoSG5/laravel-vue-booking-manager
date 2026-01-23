<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGuestRequest;
use App\Http\Requests\UpdateGuestRequest;
use App\Models\Guest;

class GuestController extends Controller
{
    public function store(StoreGuestRequest $request)
    {
        $validated = $request->validated();

        $guest = Guest::create($validated);

        return response()->json($guest, 201);
    }

    public function show(Guest $guest)
    {
        return response()->json($guest);
    }

    public function update(UpdateGuestRequest $request, Guest $guest)
    {
        $guest->update($request->validated());
        return response()->json($guest, 200);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();

        return response()->json(null, 204);
    }
}
