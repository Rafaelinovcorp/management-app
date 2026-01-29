<?php

namespace App\Http\Controllers;

use App\Models\ContactoFuncao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactoFuncaoController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Contactos/Funcoes/Index', [
            'funcoes' => ContactoFuncao::orderBy('nome')->get(),
        ]);
    }

    public function create()
    {
        return Inertia::render('Settings/Contactos/Funcoes/Create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'ativo' => 'required|boolean',
        ]);

        ContactoFuncao::create($validated);

        return redirect()->route('contactos.funcoes.index')
            ->with('success', 'Função criada com sucesso.');
    }

    public function edit(ContactoFuncao $funcao)
    {
        return Inertia::render('Settings/Contactos/Funcoes/Edit', [
            'funcao' => $funcao,
        ]);
    }

    public function update(Request $request, ContactoFuncao $funcao)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'ativo' => 'required|boolean',
        ]);

        $funcao->update($validated);

        return redirect()->route('contactos.funcoes.index')
            ->with('success', 'Função atualizada com sucesso.');
    }

    public function destroy(ContactoFuncao $funcao)
    {
        $funcao->delete();

        return redirect()->back()
            ->with('success', 'Função removida com sucesso.');
    }
}
