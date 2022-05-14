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

        $images = DB::table('tinymce_images')->latest()->paginate();

        return $images->through(function($itm){
            return  [
                'name' => $itm->name,
                'preview_url' => url(config('nova-tinymce5-editor.options.image_url_path').$itm->file_name),
                'image_url' => url(config('nova-tinymce5-editor.options.image_url_path').$itm->file_name),
                'size'  =>  $itm->file_size
            ];
        });
    }

    public function searchImages($query)
    {
        $images = DB::table('tinymce_images')->where('name','like' , "$query%")->take(10)->get();

        return $images->map(function($itm){
            return  [
                'name' => $itm->name,
                'preview_url' => url(config('nova-tinymce5-editor.options.image_url_path').$itm->file_name),
                'image_url' => url(config('nova-tinymce5-editor.options.image_url_path').$itm->file_name),
                'size'  =>  $itm->file_size
            ];
        });
    }

    public function uploadImage(Request $request)
    {
        if($request->hasFile('image')){
            $file = $request->file('image');
            $fileName = Str::random().".".$file->getClientOriginalExtension();
            $file->storeAs(config('nova-tinymce5-editor.options.storage_path'),$fileName);

            $rec = DB::table('tinymce_images')->insert([
                'name' => $file->getClientOriginalName(),
                'file_name' => $fileName,
                'file_size' => $file->getSize(),
                'disk' => config('filesystems.default'),
                'created_at' => now()
            ]);

            return [
                'name' => $file->getClientOriginalName(),
                'preview_url' => url(config('nova-tinymce5-editor.options.image_url_path').$fileName),
                'image_url' => url(config('nova-tinymce5-editor.options.image_url_path').$fileName),
                'size'  =>  $file->getSize()
            ];
        }

        return [];
    }
}
