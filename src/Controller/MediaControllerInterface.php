<?php


namespace Kraftbit\NovaTinymce5Editor\Controller;


use Illuminate\Http\Request;

interface MediaControllerInterface
{
    public function getImages(Request $request);

    public function searchImages(string $query);

    public function uploadimage(Request $request);


}