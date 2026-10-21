<?php

namespace App\Http\Controllers;

use App\Enums\CategoriaFornecedor;
use App\Models\FornecedorConfianca;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FornecedorConfiancaController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(auth()->user()->tipoUsuario === 'ASSESSOR', 403);

        $query = auth()->user()->fornecedoresConfianca();

        if ($request->filled('categoria')) {
            $query->where('categoriaFornecedorConfianca', $request->categoria);
        }

        $fornecedores = $query->orderBy('nomeFornecedorConfianca')->get();

        return view('fornecedores-confianca.index', compact('fornecedores'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->tipoUsuario === 'ASSESSOR', 403);

        $request->validate([
            'nomeFornecedorConfianca' => ['required', 'string', 'max:255'],
            'categoriaFornecedorConfianca' => ['nullable', Rule::enum(CategoriaFornecedor::class)],
            'telefoneFornecedorConfianca' => ['nullable', 'string'],
            'instagramFornecedorConfianca' => ['nullable', 'string'],
        ], [
            'nomeFornecedorConfianca.required' => 'O nome do fornecedor é obrigatório.',
        ]);

        auth()->user()->fornecedoresConfianca()->create($request->only([
            'nomeFornecedorConfianca',
            'categoriaFornecedorConfianca',
            'telefoneFornecedorConfianca',
            'instagramFornecedorConfianca',
        ]));

        return redirect()->route('fornecedor-confianca.index')->with('sucesso', 'Fornecedor adicionado com sucesso!');
    }

    public function update(Request $request, FornecedorConfianca $fornecedorConfianca)
    {
        abort_unless(auth()->user()->tipoUsuario === 'ASSESSOR', 403);
        abort_unless($fornecedorConfianca->user_id === auth()->id(), 403);

        $dados = $request->validate([
            'nomeFornecedorConfianca' => ['required', 'string', 'max:255'],
            'categoriaFornecedorConfianca' => ['nullable', Rule::enum(CategoriaFornecedor::class)],
            'telefoneFornecedorConfianca' => ['nullable', 'string'],
            'instagramFornecedorConfianca' => ['nullable', 'string'],
        ], [
            'nomeFornecedorConfianca.required' => 'O nome do fornecedor é obrigatório.',
        ]);

        $fornecedorConfianca->update($dados);

        return redirect()->route('fornecedor-confianca.index')->with('sucesso', 'Fornecedor atualizado com sucesso!');
    }

    public function destroy(FornecedorConfianca $fornecedorConfianca)
    {
        abort_unless($fornecedorConfianca->user_id === auth()->id(), 403);

        $fornecedorConfianca->delete();

        return back()->with('sucesso', 'Fornecedor removido com sucesso!');
    }

}
?>