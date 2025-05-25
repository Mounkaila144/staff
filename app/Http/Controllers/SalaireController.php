<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SalaireController extends Controller
{
    public function index()
    {
        if (auth()->user()->isIntern()) {
            return view('salaire.access-denied');
        }
        
        $interns = User::where('role', 'intern')->get();
        return view('salaire.index', compact('interns'));
    }

    public function show(User $intern)
    {
        if (auth()->user()->isIntern()) {
            return view('salaire.access-denied');
        }
        return view('salaire.show', compact('intern'));
    }
} 