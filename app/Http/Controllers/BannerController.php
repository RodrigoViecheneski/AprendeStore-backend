<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;

class BannerController extends Controller
{
    public function getAllBanners()
    {
        $banners = Banner::all();

        $return = [];
        foreach ($banners as $banner) {
            $return[] = [
                'img' => asset('storage/' . $banner->file_path), #asset() is used to generate a URL for the given path. It will prepend the application's base URL to the path, which is useful for accessing files stored in the public directory.
                'link' => $banner->link,
            ];
        }
        return $return;
    }
}
