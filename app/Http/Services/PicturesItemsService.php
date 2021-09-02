<?php


namespace App\Http\Services;


use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

/**
 * Class PicturesItemsService
 * @package App\Http\Services
 */
class PicturesItemsService
{
    private $pathForPictures;
    private $pathForMiniPictures;
    private $pathPublicToStorage;
    private $pathForStorageToRootImages;

    public function __construct()
    {
        $this->pathForPictures = config('storage.path_for_pictures');
        $this->pathForMiniPictures = config('storage.path_for_mini_pictures');
        $this->pathPublicToStorage = config('storage.path_for_storage');
        $this->pathForStorageToRootImages = config('storage.path_for_storage_for_storage_service');
    }

    /**
     * @param $nameItem
     * @param $picture
     * @return array
     */
    public function savePictureForItem($nameItem, $picture) {
        $newNameForFile = md5($nameItem . '.' . Str::random(5)) . '.jpg';
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

    /**
     * @param $namePicture
     * @return bool
     */
    public function deletePictureForItem($namePicture) {
        return Storage::delete([
            $this->pathForStorageToRootImages . $this->pathForPictures .  $namePicture,
            $this->pathForStorageToRootImages . $this->pathForMiniPictures . $namePicture,
        ]);
    }
}
