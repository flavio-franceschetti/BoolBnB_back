<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
    {
        $apartments = Apartment::where('is_visible', true)->with('services')->get();

        if ($apartments) {
            $success = true;
            foreach ($apartments as $apartment) {
                $apartment->img = asset('storage/' . $apartment->img);
            }
        } else {
            $success = false;
        }
        return response()->json(compact('success', 'apartments'));
    }

    public function apartmentById($id)
    {
        $apartment = Apartment::where('id', $id)->where('is_visible', true)->with('services')->first();

        if ($apartment) {
            $success = true;
            $apartment->img = asset('storage/' . $apartment->img);
        } else {
            $success = false;
        }
        return response()->json(compact('success', 'apartment'));
    }
}
