<?php

namespace Kraftbit\NovaTinymce5Editor\Controller;

use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController implements MediaControllerInterface
{
    public function getImages(Request $request)
    {
        $images = Media::paginate();

        sleep(1);

        return $images->through(function($itm){
            return  [
                'name' => $itm->name,
                'preview_url' => "https://picsum.photos/200/300?random={$itm->id}",
                'image_url' => "https://picsum.photos/200/300?random={$itm->id}",
                'size'  =>  $itm->human_readable_size
            ];
        });
    }

    public function searchImages($query)
    {
        $images = Media::take(5)->get();

        return $images->map(function($itm){
            return  [
                'name' => $itm->name,
                'preview_url' => "https://picsum.photos/200/300?random={$itm->id}",
                'image_url' => "https://picsum.photos/200/300?random={$itm->id}",
                'size'  =>  $itm->human_readable_size
            ];
        });
    }

    public function uploadimage(Request $request)
    {
        // handle uploaded image & return url of uploaded image
        sleep(2);
        return [
            'name' => "uploadde image",
            'preview_url' => "https://picsum.photos/200/300?random=234",
            'image_url' => "https://picsum.photos/200/300?random=2343",
            'size'  =>  '78KB'
        ];
    }
}