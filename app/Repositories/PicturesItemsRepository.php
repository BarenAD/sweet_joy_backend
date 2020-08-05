<?php


namespace App\Repositories;


use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class PicturesItemsRepository
{
    private $pathForPictures = 'item_images/';
    private $pathForMiniPictures = 'item_images/mini/';
    private $pathPublicToStorage = 'storage/';
    private $pathForStorageToRootImages = 'public/';

    public function savePictureForItem($nameItem, $picture) {
        $newNameForFile = md5($nameItem) . '.jpg';
        $fullPathForPicture = $this->pathPublicToStorage . $this->pathForPictures . $newNameForFile;
        $fullPathForMiniPicture = $this->pathPublicToStorage . $this->pathForMiniPictures . $newNameForFile;

        $img = Image::make($picture);
        $img->resize(700, 700);
        $img->save($fullPathForPicture);
        $img->resize(200, 200);
        $img->save($fullPathForMiniPicture);

        return [
            'name' => $newNameForFile,
            'fullPathForPicture' => $fullPathForPicture,
            'fullPathForMiniPicture' => $fullPathForMiniPicture
        ];
    }

    public function deletePictureForItem($namePicture) {
        return Storage::delete([
            $this->pathForStorageToRootImages . $this->pathForPictures .  $namePicture,
            $this->pathForStorageToRootImages . $this->pathForMiniPictures . $namePicture,
        ]);
    }
}
