<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Tag;
use File;

class TagController extends Controller
{

    private $tag;
    private $loggedUser;

    public function __construct(Tag $tag){
        $this->middleware('auth:api');
        $this->loggedUser = Auth::user();
        $this->tag = $tag;

    }

    public function index(){

        $data = $this->tag->all();
        return response()->json($data);
    }

    public function show($id){

        $tag = $this->tag->find($id);
        if (! $tag) return response()->json('Tag not foud!', 404);

        $data = ['data' => $tag];
        return response()->json($data);
    }

    public function update(Request $request, $id) {
        
        $name = $request->input('name');
        $qrCode = $request->input('qrCode');
        $qrImg = $request->file('qrImg');

        $tag = $this->tag->find($id);

        if($tag){ 

            if ($tag['qr_img'] === $tag['qr_img']) {
                if(!empty($name)){
                    $tag->name = $name;
                }
                if(!empty($qrCode)){
                    $tag->qr_code = $qrCode;
                }
                $tag->update(); 
            
                return response()->json('Tag update successfully ', 202);

            } else {
                $imgName = $tag['qr_img'];
                File::delete('media/images/'.$imgName);

                $ext = $qrImg->getClientOriginalExtension();
                $imageName = time().'.'.$ext;

                $request->file('qrImg')->move(public_path('media/images'), $imageName);
                $uploadImg = 'media/images/'.$imageName;

                if(!empty($name)){
                    $tag->name = $name;
                }
                if(!empty($qrCode)){
                    $tag->qr_code = $qrCode;
                }
                $tag->qr_img= $uploadImg;
                $tag->update(); 

                return response()->json('foi aqui 2', 202);
            }

        } else {
            return response()->json('Error update', 400);
        }
    } 

    public function create(Request $request) {
    
        $name = $request->input('name');
        $qrCode = $request->input('qrCode');
        // $qrImg = $request->file('qrImg');

        // $ext = $qrImg->getClientOriginalExtension();
        // $imageName = time().'.'.$ext;

        // $request->file('qrImg')->move(public_path('media/images'), $imageName);
        // $uploadImg = 'media/images/'.$imageName;

        $newTag = new Tag;
        $newTag->name = $name;
        $newTag->id_bike = "1";
        $newTag->qr_code = $qrCode;
        // $newTag->qr_img = $uploadImg;
        $newTag->save();
        return response()->json("sucess");
       
    }
    
    public function delete(Tag $id){
        try {
            $id->delete();

            return response()->json(['data' => ['msg' => 'Tag delete successfully!']], 200);

        } catch (\Exception $e) {
            if (config('app.debug')) {
                return response()->json('Error', 1012);
            }
            return response()->json('Error ao realizar operação de exclusão', 1012);
        }
    }
}
