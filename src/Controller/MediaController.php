<?php

namespace Kraftbit\NovaTinymce5Editor\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController implements MediaControllerInterface
{
    public function getImages(Request $request)
    {

        $images = DB::table('tinymce_media')->paginate();

        return $images->through(function($itm){
            return  [
                'name' => $itm->name,
                'preview_url' => url('/storage/tinymce/'.$itm->file_name),
                'image_url' => url('/storage/tinymce/'.$itm->file_name),
                'size'  =>  $itm->file_size
            ];
        });
    }

    public function searchImages($query)
    {
        $images = DB::table('tinymce_media')->where('name','like' , "$query%")->take(10)->get();

        return $images->map(function($itm){
            return  [
                'name' => $itm->name,
                'preview_url' => url('/storage/tinymce/'.$itm->file_name),
                'image_url' => url('/storage/tinymce/'.$itm->file_name),
                'size'  =>  $itm->file_size
            ];
        });
    }

    public function uploadImage(Request $request)
    {
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = Str::random().".".$file->getClientOriginalExtension();
            $file->storeAs('tinymce',$fileName);

            $rec = DB::table('tinymce_media')->insert([
                'name' => $file->getClientOriginalName(),
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'disk' => config('filesystems.default')
            ]);

            return [
                'name' => $file->getClientOriginalName(),
                'preview_url' => "https://picsum.photos/200/300?random=234",
                'image_url' => "https://picsum.photos/200/300?random=2343",
                'size'  =>  $file->getSize()
            ];
        }

        return [];
    }
}
