<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use GuzzleHttp\Client;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return $products;
    }


    public function store(Request $request)
    {
        $product = new Product();
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $product->save();
    }


    public function show($id)
    {
        $product = Product::find($id);
        return $product;
    }


    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($request->id);
        $product->description = $request->description;
        $product->price = $request->price;
        $product->stock = $request->stock;

        $product->save();

        return $product;
    }


    public function destroy($id)
    {
        $product = Product::destroy($id);
        return $product;
    }



    public function images()
    {

        $client = new Client([
            'base_uri' => 'https://dog.ceo/api/',
            'verify' => false,
        ]);



        // Obtengo la lista de razas de perros
        $razas_response = $client->request('GET', 'breeds/list/all');
        $razas_data = json_decode($razas_response->getBody()->getContents(), true);
        $razas = array_keys($razas_data['message']);

        // Selecciono una raza correcta aleatoria
        $raza_correcta = $razas[array_rand($razas)];

        // Obtengo una imagen de un perro aleatoria
        $imagen_perro_response = $client->request('GET', 'breed/' . $raza_correcta . '/images/random');
        $imagen_perro_data = json_decode($imagen_perro_response->getBody()->getContents(), true);
        $imagen_perro = $imagen_perro_data['message'];

        // Selecciono dos razas incorrectas aleatorias
        $razas_incorrectas = [];
        do {
            $raza_incorrecta_1 = $razas[array_rand($razas)];
        } while ($raza_incorrecta_1 === $raza_correcta);
        do {
            $raza_incorrecta_2 = $razas[array_rand($razas)];
        } while ($raza_incorrecta_2 === $raza_correcta || $raza_incorrecta_2 === $raza_incorrecta_1);

        // Envío estos datos al frontend
        $datos = [
            'imagen_perro' => $imagen_perro,
            'raza_correcta' => $raza_correcta,
            'razas_incorrectas' => [$raza_incorrecta_1, $raza_incorrecta_2]
        ];

        return $datos;
    }
}
