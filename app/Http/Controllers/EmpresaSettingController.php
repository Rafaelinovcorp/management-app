<?php
namespace App\Http\Controllers;

use App\Models\EmpresaSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class EmpresaSettingController extends Controller
{
    public function edit()
    {
        $empresa = EmpresaSetting::first();

        return Inertia::render('Settings/Empresa/Edit', [
            'empresa' => $empresa
        ]);
    }

    public function update(Request $request)
{
    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'nif' => 'required|string|max:20',
        'morada' => 'required|string|max:255',
        'codigo_postal' => 'required|regex:/^\d{4}-\d{3}$/',
        'localidade' => 'required|string|max:255',
        'logotipo' => 'nullable|image|max:2048',
    ]);

    $empresa = EmpresaSetting::first();

    if (!$empresa) {
        $empresa = new EmpresaSetting();
    }

    $empresa->nome = $validated['nome'];
    $empresa->nif = $validated['nif'];
    $empresa->morada = $validated['morada'];
    $empresa->codigo_postal = $validated['codigo_postal'];
    $empresa->localidade = $validated['localidade'];

    if ($request->hasFile('logotipo')) {
        if ($empresa->logotipo_path) {
            Storage::disk('local')->delete($empresa->logotipo_path);
        }

        $empresa->logotipo_path = $request->file('logotipo')
            ->store('empresa', 'local');
    }

    $empresa->save();

   return redirect()->route('dashboard')
    ->with('success', 'Dados da empresa guardados com sucesso.');

}

}
