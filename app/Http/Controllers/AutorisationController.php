<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AutorisationController extends Controller
{
    public function index()
    {
        if (auth()->user()->isIntern()) {
            return view('autorisation.access-denied');
        }
        
        $autorisations = []; // À remplacer par le modèle Autorisation quand il sera créé
        return view('autorisation.index', compact('autorisations'));
    }

    public function create()
    {
        if (auth()->user()->isIntern()) {
            return view('autorisation.access-denied');
        }
        
        return view('autorisation.create');
    }

    public function store(Request $request)
    {
        if (auth()->user()->isIntern()) {
            return view('autorisation.access-denied');
        }
        
        // Logique de création d'une demande d'autorisation
        return redirect()->route('autorisation.index')
            ->with('success', 'Demande d\'autorisation créée avec succès.');
    }
} 