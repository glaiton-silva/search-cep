<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

class CEPController extends Controller
{
    /**
     * Exibe a página de busca de CEP.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('cep_search');
    }

    /**
     * Busca informações de endereços para os CEPs fornecidos.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string|null  $ceps
     * @return \Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
    public function search(Request $request, $ceps = null)
    {
        // Recupera os CEPs do input se não foram passados como parâmetro
        if ($ceps === null) {
            $ceps = $request->input('ceps');
        }

        // Valida e limpa os CEPs
        $cepArray = $this->validateAndCleanCeps($ceps);
        $results = $this->fetchCepDetails($cepArray);

        // Retorna JSON para requisições AJAX ou exibe a view com os resultados
        if ($request->ajax() || $ceps !== null) {
            return response()->json($results);
        }

        return view('cep_search', ['results' => $results]);
    }

    /**
     * Valida e limpa os CEPs fornecidos.
     *
     * @param  string  $ceps
     * @return array
     */
    private function validateAndCleanCeps(string $ceps): array
    {
        return array_filter(explode(',', $ceps), function ($cep) {
            return preg_match('/^\d{5}-?\d{3}$/', trim($cep));
        });
    }

    /**
     * Busca os detalhes de cada CEP utilizando a API ViaCEP.
     *
     * @param  array  $cepArray
     * @return array
     */
    private function fetchCepDetails(array $cepArray): array
    {
        $results = [];

        foreach ($cepArray as $cep) {
            $cep = str_replace('-', '', trim($cep));
            $response = Http::get("https://viacep.com.br/ws/{$cep}/json/");

            if ($response->successful()) {
                $data = $response->json();
                if (!isset($data['erro'])) {
                    $results[] = $this->formatCepData($data);
                }
            } else {
                // Log error or handle unsuccessful response
                // Log::error("Failed to fetch data for CEP: {$cep}");
            }
        }

        return $results;
    }

    /**
     * Formata os dados retornados pela API ViaCEP.
     *
     * @param  array  $data
     * @return array
     */
    private function formatCepData(array $data): array
    {
        return [
            'cep' => $data['cep'],
            'label' => "{$data['logradouro']}, {$data['localidade']}",
            'logradouro' => $data['logradouro'],
            'complemento' => $data['complemento'],
            'bairro' => $data['bairro'],
            'localidade' => $data['localidade'],
            'uf' => $data['uf'],
            'ibge' => $data['ibge'],
            'gia' => $data['gia'],
            'ddd' => $data['ddd'],
            'siafi' => $data['siafi']
        ];
    }
}
