<?php

namespace App\Http\Controllers;

use App\Models\CategoriesModel;
use App\Models\ImageModel;
use Illuminate\Http\Request;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
class UploadImagesController extends Controller
{
    public function catogeriesupload()
    {

        return view('pages.upload_categories');

    }

    public function store(Request $request)
    {
        // Validate form input
        $request->validate([
            'categories_name' => 'required|string|max:255|unique:categories,categories_name',
        ]);

        // Store category in database
        CategoriesModel::create([
            'categories_name' => $request->categories_name,
        ]);

        return back()->with('success', 'Added New Categorie successfully!');
    }

    public function show()
    {
        $categories = CategoriesModel::all();
        return response()->json(['categories' => $categories]);

    }


    public function imageupload()
    {
        $categories = CategoriesModel::with('images')->get();

        // foreach ($categories as $category) {
        //     foreach ($category->images as $image) {
        //         $imagePath = public_path($image->image_name);
        //         $extension = pathinfo($imagePath, PATHINFO_EXTENSION);

        //         if (in_array(strtolower($extension), ['tiff', 'tif'])) {
        //             // Convert TIFF to PNG (or JPEG)
        //             $convertedPath = 'uploads/converted_' . pathinfo($image->image_name, PATHINFO_FILENAME) . '.png';

        //             if (!Storage::exists($convertedPath)) {
        //                 $img = Image::make($imagePath)->encode('png');
        //                 Storage::put($convertedPath, $img);
        //             }

        //             // Update image path to use converted version
        //             $image->image_name = $convertedPath;
        //         }
        //     }
        // }

        return view('pages.upload_img', compact('categories'));
    }




    public function getimage()
    {
        $categories = CategoriesModel::with('images')->get()->map(function ($category) {
            $category->images->transform(function ($image) {
                $image->image_url = asset($image->image_name);
                return $image;
            });
            return $category;
        });

        return response()->json([
            'categories' => $categories
        ]);
    }

    public function trending()
    {
        $categories = CategoriesModel::where('categories_name', 'Trending') // Get the category where name = 'Trending'
            ->with([
                'images' => function ($query) {
                    $query->take(5); // Limit images to 5 per category
                }
            ])
            ->get()
            ->map(function ($category) {
                $category->images->transform(function ($image) {
                    $image->image_url = asset($image->image_name);
                    return $image;
                });
                return [
                    'category_id' => $category->id,
                    'category_name' => $category->categories_name,
                    'images' => $category->images
                ];
            });

        return response()->json([
            'categories' => $categories
        ]);
    }





    public function imagestore(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'image' => 'required',
            'price' => 'required',
        ]);

        if ($request->hasFile('image')) {
            // Get the original file name with extension
            $originalName = $request->image->getClientOriginalName();

            // Move to public/Images folder with the original name
            $request->image->move(public_path('Images'), $originalName);

            // Save image path to database
            ImageModel::create([
                'categories_id' => $request->category_id,
                'price' => $request->price,
                'image_name' => 'Images/' . $originalName
            ]);

            return redirect()->back()->with('success', 'Image uploaded successfully!');
        }

        return redirect()->back()->withErrors(['image' => 'Failed to upload image.']);
    }


    public function deleteImage(Request $request, $id)
    {
        $image = ImageModel::find($id);

        $imageName = $request->imageName;

        if (!$image) {
            return response()->json(['error' => 'Image not found'], 404);
        }

        // Get the image path
        $imagePath = public_path($imageName);

        // Delete the record from the database
        $image->delete();

        // Check if the file exists and delete it
        if (File::exists($imagePath)) {
            File::delete($imagePath);
        }

        return response()->json(['success' => 'Image deleted successfully']);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'categories_name' => 'required|string|max:255'
        ]);

        $category = CategoriesModel::findOrFail($id);
        $category->categories_name = $request->categories_name;
        $category->save();

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $category = CategoriesModel::findOrFail($id);
        $category->delete();

        return response()->json(['success' => true]);
    }



}
