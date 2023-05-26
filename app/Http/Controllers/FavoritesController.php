<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function addToFavorites(Request $request)
    {
        dd($request);
        $itemId = $request->input('item_id');
        // Perform any necessary validations

        // Create a new favorite record in the database
        $favorite = Favorite::create([
            'user_id' => auth()->user()->id, // Assuming you have an authenticated user
            'item_id' => $itemId,
        ]);

        return response()->json($favorite, 201);
    }

    public function removeFromFavorites($itemId)
    {
        // Find the favorite record by item ID and user ID
        $favorite = Favorite::where('user_id', auth()->user()->id)
            ->where('item_id', $itemId)
            ->first();

        if ($favorite) {
            // Delete the favorite record from the database
            $favorite->delete();
        }

        return response()->json(null, 204);
    }
}
