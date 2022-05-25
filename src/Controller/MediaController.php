<?php

namespace Visanduma\NovaImageTinymce\Controller;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MediaController implements MediaControllerInterface
{
    public function getImages(Request $request)
    {

        $images = DB::table('tinymce_images')->latest()->paginate();

        return $images->through(function ($itm) {
            return  [
                'id' => $itm->id,
                'name' => $itm->name,
                'preview_url' => $this->getImageUrl($itm->file_name),
                'image_url' => $this->getImageUrl($itm->file_name),
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
                'preview_url' => $this->getImageUrl($itm->file_name),
                'image_url' => $this->getImageUrl($itm->file_name),
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
                $file->storeAs(Str::finish(config('nova-tinymce5-editor.storage_path'), "/"), $fileName, ['disk' => config('nova-tinymce5-editor.disk')]);

                $rec = DB::table('tinymce_images')->insertGetId([
                    'name' => $file->getClientOriginalName(),
                    'file_name' => $fileName,
                    'file_size' => $file->getSize(),
                    'disk' => config('nova-tinymce5-editor.disk'),
                    'created_at' => now()
                ]);

                $rec = DB::table('tinymce_images')->find($rec);

                $out[] = [
                    'id' => $rec->id,
                    'name' => $file->getClientOriginalName(),
                    'preview_url' => $this->getImageUrl($rec->file_name),
                    'image_url' => $this->getImageUrl($rec->file_name),
                    'size'  =>  $this->sizeFilter($rec->file_size)
                ];

            }

            return $out;
        }

        return [];
    }

    public function deleteImages(Request $request)
    {
        foreach ($request->get('images') as $img) {
            $file = DB::table('tinymce_images')->where('id', $img)->first();
            // remove file from storage
            Storage::disk(config('nova-tinymce5-editor.disk'))
                ->delete(config('nova-tinymce5-editor.storage_path') . "/" . $file->file_name);
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

    private function getImageUrl($image)
    {
        return Storage::disk(config('nova-tinymce5-editor.disk'))
            ->url(config('nova-tinymce5-editor.storage_path') . $image);
    }
}
