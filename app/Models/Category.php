<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    private static $category, $image, $extension, $imageName, $directory, $imageUrl;

    private static function getImageUrl($request)
    {
        self::$image        = $request->file('image');
        self::$extension    = self::$image->getClientOriginalExtension();
        self::$imageName    = time().'.'.self::$extension;
        self::$directory    = 'upload/category-images/';
        self::$image->move(self::$directory,self::$imageName);
        self::$imageUrl     = self::$directory.self::$imageName;
        return self::$imageUrl;

    }
    public static function newCategory($request)
    {
        self::$category = new Category();
        self::$category->name           = $request->name;
        self::$category->description    = $request->description;
        self::$category->image          = self::getImageUrl($request);
        self::$category->status         = $request->status;
        self::$category->save();
    }

    public static function updateCategory($request, $category)
    {
        if ($request->file('image'))
        {
            if (file_exists($category->image))
            {
                unlink($category->image);
            }
            self::$imageUrl = self::getImageUrl($request);
        }
        else
        {
            self::$imageUrl = $category->image;
        }

        $category->name             = $request->name;
        $category->description      = $request->description;
        $category->image            = self::$imageUrl;
        $category->status           = $request->status;
        $category->save();
    }

    public static function deleteCategory($category)
    {
        if (file_exists($category))
        {
            unlink($category->image);
        }
        $category->delete();
    }

    public function subCategory()
    {
        return $this->hasMany(SubCategory::class);
    }
}
