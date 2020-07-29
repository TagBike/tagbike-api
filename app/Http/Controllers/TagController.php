<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;

class TagController extends Controller
{

    private $tag;

    public function __construct(Tag $tag){

        $this->tag = $tag;

    }

    public function index(){

        $data = ['data' => $this->tag::paginate(10)];
        return response()->json($data);
    }

    public function show($id){

        $tag = $this->tag->find($id);
        if (! $tag) return response()->json(ApiError::errorMessage('Etiqueta não encontrado!', 4040), 404);

        $data = ['data' => $tag];
        return response()->json($data);
    }

    public function update(Request $request, $id) {
        $name = $request->input('name');

        if($id && $name){
            $tag = $this->tag->find($id);

            if($tag){

                $tag->name = $name;
                $tag->save();

            } else {
                return response()->json('error');
            }
        }
    } 

    public function create(Request $request) {
        $array = ['error' => ''];

        $name = $request->input('name');

        if ($name) {
            $newTag = new Tag;
            $newTag->name = $name;
            $newTag->save();
            return response()->json("sucess");
        } else {
            $array['error'] = "não enviou todos os campos";
            return $array;
        }
    }
    
    public function delete(Tag $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Etiqueta Excluída com sucesso!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json(ApiError::errorMessage($e->getMessage(), 1012));
            }
            return response()->json(ApiError::errorMessage('Error ao realizar operação de exclusão', 1012));
        }
    }
}
