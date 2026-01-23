<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreGuestRequest;
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

    public function update(Request $request, Guest $guest)
    {
        $validated = $request->validate([
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:guests,email,' . $guest->id,
            'phone_number' => 'sometimes|nullable|string|max:20',
        ]);
        $guest->update($validated);
        return response()->json($guest);
    }

    public function destroy(Guest $guest)
    {
        $guest->delete();

        return response()->json(null, 204);
    }
}
