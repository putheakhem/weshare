<?php

namespace App\Http\Controllers\Files;

use App\Http\Controllers\Controller;
use App\Models\FavouriteItem;
use App\Models\Items;
use App\Models\Majors;
use App\Models\Types;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class FavouriteItemsController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        $search = $request->input('search');
        $favourites = Items::join('users', 'items.user_id', '=', 'users.id')
            ->join('file_details', 'items.file_de_id', '=', 'file_details.id')
            ->join('majors', 'file_details.major_id', '=', 'majors.id')
            ->join('types', 'file_details.filetype_id', '=', 'types.id')
            ->join('favourite_items', function ($join) use ($userId) {
                $join->on('items.id', '=', 'favourite_items.item_id')
                    ->where('favourite_items.user_id', '=', $userId);
            })
            ->select('items.*', 'users.name as user_name', 'file_details.title as title', 'file_details.desc as description', 'file_details.major_id as major_id', 'file_details.filetype_id as type_id', 'majors.name as major_name', 'types.name as file_type')
            ->selectRaw('IF(favourite_items.user_id IS NOT NULL, true, false) as is_favorite')
            ->when($search, function ($query, $search) {
                return $query->where('file_details.title', 'LIKE', '%' . $search . '%')
                    ->orWhere('majors.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('types.name', 'LIKE', '%' . $search . '%')
                    ->orWhere('items.filename', 'LIKE', '%' . $search . '%')
                ;
            })
            ->get();
            $majors = Majors::all();
            $types = Types::all();
        return Inertia::render('Files/FavouriteItems', [
            'favourites' => $favourites,
            'majors' => $majors,
            'types' => $types
        ]);
    }

    public function toggleFavorite(Request $request, $itemId)
    {
        $userId = $request->user()->id;
        // Check if the item is already favorited by the user
        $favoriteItem = FavouriteItem::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->first();

        if ($favoriteItem) {
            // Item is already favorited, so remove it from favorites
            $favoriteItem->delete();
            $isFavorite = false;
        } else {
            // Item is not favorited, so add it to favorites
            FavouriteItem::create([
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);
            $isFavorite = true;
        }

        return response()->json(['is_favorite' => $isFavorite]);
    }

    public function destroy(Request $request, $itemId)
    {
        $userId = $request->user()->id;
        $favoriteItem = FavouriteItem::where('user_id', $userId)
            ->where('item_id', $itemId)
            ->first();

        if ($favoriteItem) {
            $favoriteItem->delete();
            $isFavorite = false;
        } else {
            FavouriteItem::create([
                'user_id' => $userId,
                'item_id' => $itemId,
            ]);
            $isFavorite = true;
        }
        return response()->json(['is_favorite' => $isFavorite]);
    }

}