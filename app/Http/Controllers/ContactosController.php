<?php
namespace App\Http\Controllers;

use App\Models\Contacto;
use App\Models\Entidade;
use App\Models\ContactoFuncao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactosController extends Controller
{
    public function index()
    {
        $contactos = Contacto::where(function ($query) {
                $query->whereNull('contacto_funcao_id')
                      ->orWhereHas('funcao', function ($q) {
                          $q->where('ativo', true);
                      });
            })
            ->with(['entidade', 'funcao'])
            ->orderBy('nome')
            ->get();

        return Inertia::render('Contactos/Index', [
            'contactos' => $contactos,
        ]);
    }

    public function create()
    {
        return Inertia::render('Contactos/Create', [
            'entidades' => Entidade::orderBy('nome')->get(['id', 'nome']),
            'funcoes' => ContactoFuncao::where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome']),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => ['nullable', 'string', 'max:50'],
            'entidade_id' => ['required', 'exists:entidades,id'],
            'nome' => ['required', 'string', 'max:255'],
            'apelido' => ['required', 'string', 'max:255'],
            'contacto_funcao_id' => ['nullable', 'exists:contacto_funcoes,id'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'telemovel' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'consentimento_rgpd' => ['boolean'],
            'observacoes' => ['nullable', 'string'],
            'estado' => ['boolean'],
        ]);

        Contacto::create($validated);

        return redirect()
            ->route('contactos.index')
            ->with('success', 'Contacto criado com sucesso.');
    }

    public function edit(Contacto $contacto)
    {
        if ($contacto->funcao && !$contacto->funcao->ativo) {
            abort(403);
        }

        return Inertia::render('Contactos/Edit', [
            'contacto' => $contacto,
            'entidades' => Entidade::orderBy('nome')->get(['id', 'nome']),
            'funcoes' => ContactoFuncao::where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome']),
        ]);
    }

    public function update(Request $request, Contacto $contacto)
    {
        $validated = $request->validate([
            'numero' => ['nullable', 'string', 'max:50'],
            'entidade_id' => ['required', 'exists:entidades,id'],
            'nome' => ['required', 'string', 'max:255'],
            'apelido' => ['required', 'string', 'max:255'],
            'contacto_funcao_id' => ['nullable', 'exists:contacto_funcoes,id'],
            'telefone' => ['nullable', 'string', 'max:20'],
            'telemovel' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255'],
            'consentimento_rgpd' => ['boolean'],
            'observacoes' => ['nullable', 'string'],
            'estado' => ['boolean'],
        ]);

        $contacto->update($validated);

        return redirect()
            ->route('contactos.index')
            ->with('success', 'Contacto atualizado com sucesso.');
    }

    public function destroy(Contacto $contacto)
    {
        $contacto->delete();

        return redirect()
            ->route('contactos.index')
            ->with('success', 'Contacto removido com sucesso.');
    }
}
