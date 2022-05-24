<?php

namespace Kraftbit\NovaTinymce5Editor\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MediaController implements MediaControllerInterface
{
    public function getImages(Request $request)
    {

        $images = DB::table('tinymce_images')->latest()->paginate();

        return $images->through(function ($itm) {
            return  [
                'id' => $itm->id,
                'name' => $itm->name,
                'preview_url' => url(config('nova-tinymce5-editor.options.image_url_path') . $itm->file_name),
                'image_url' => url(config('nova-tinymce5-editor.options.image_url_path') . $itm->file_name),
                'size'  =>  $this->sizeFilter($itm->file_size)
            ];
        });
    }

    public function searchImages(string $query)
    {
        $images = DB::table('tinymce_images')->where('name', 'like', "$query%")->take(10)->get();

        return $images->map(function ($itm) {
            return  [
                'id' => $itm->id,
                'name' => $itm->name,
                'preview_url' => url(config('nova-tinymce5-editor.options.image_url_path') . $itm->file_name),
                'image_url' => url(config('nova-tinymce5-editor.options.image_url_path') . $itm->file_name),
                'size'  =>  $this->sizeFilter($itm->file_size)
            ];
        });
    }

    public function uploadImage(Request $request)
    {
        if ($request->hasFile('images')) {
            $out = [];
            foreach ($request->file('images') as $file){

                $fileName = Str::random() . "." . $file->getClientOriginalExtension();
                $file->storeAs(config('nova-tinymce5-editor.options.storage_path'), $fileName);

                $rec = DB::table('tinymce_images')->insertGetId([
                    'name' => $file->getClientOriginalName(),
                    'file_name' => $fileName,
                    'file_size' => $file->getSize(),
                    'disk' => config('filesystems.default'),
                    'created_at' => now()
                ]);

                $rec = DB::table('tinymce_images')->find($rec);

                $out[] = [
                    'id' => $rec->id,
                    'name' => $file->getClientOriginalName(),
                    'preview_url' => url(config('nova-tinymce5-editor.options.image_url_path') . $fileName),
                    'image_url' => url(config('nova-tinymce5-editor.options.image_url_path') . $fileName),
                    'size'  =>  $this->sizeFilter($file->getSize())
                ];

            }

            return $out;
        }

        return [];
    }


    public function deleteImages(Request $request)
    {
        foreach ($request->get('images') as $img) {
            $file =  DB::table('tinymce_images')->where('id', $img)->first();
            // remove file from storage
            Storage::delete(config('nova-tinymce5-editor.options.storage_path')."/".$file->file_name);
            // remove db entry
            DB::table('tinymce_images')->delete($img);
        }

        return response()->json("Succesfuly deleted !");
    }




    private function sizeFilter($bytes)
    {
        $label = array('B', 'KB', 'MB', 'GB', 'TB', 'PB');
        for (
            $i = 0;
            $bytes >= 1024 && $i < (count($label) - 1);
            $bytes /= 1024, $i++
        );
        return (round($bytes, 2) . " " . $label[$i]);
    }
}
