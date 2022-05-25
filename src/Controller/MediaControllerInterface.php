<?php


namespace Visanduma\NovaImageTinymce\Controller;


use Illuminate\Http\Request;

interface MediaControllerInterface
{
    public function getImages(Request $request);

    public function searchImages(string $query);

    public function uploadImage(Request $request);

    public function deleteImages(Request $request);


}