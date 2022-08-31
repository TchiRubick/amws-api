<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use App\Models\Article;

class ArticleController extends Controller
{
    public function create(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'required|bail|min:2',
                'subtitle' => 'required|bail|min:5',
                'description' => 'required|bail|min:10',
                'image' => 'required|bail|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 401);
            }

            $imageName = time() . '.' . $request->image->extension();

            $request->image->move(public_path('images'), $imageName);

            $article = Article::create([
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            return response()->json($article);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function edit(Request $request, Article $article)
    {
        try {
            $validator = Validator::make($request->all(), [
                'title' => 'min:2',
                'subtitle' => 'min:5',
                'description' => 'min:10',
                'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['message' => $validator->errors()->first()], 401);
            }

            if ($request->hasFile('image')) {
                $imageName = time() . '.' . $request->image->extension();

                $request->image->move(public_path('images'), $imageName);
                // Preload this variable before losing it's value
                $imagePath = '/images/' . $article->image;

                $article->image = $imageName;

                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }

            if ($request->has('title')) {
                $article->title = $request->title;
            }

            if ($request->has('subtitle')) {
                $article->subtitle = $request->subtitle;
            }

            if ($request->has('description')) {
                $article->description = $request->description;
            }

            $article->save();

            return response()->json(['uploaded' => true, $article]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function getMany()
    {
        try {
            return response()->json(Article::get());
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function get(Article $article)
    {
        try {
            return response()->json($article);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    public function remove(Article $article)
    {
        try {
            $imagePath = '/images/' . $article->image;

            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }

            $article->delete();

            return response()->json(['removed' => true]);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }
}
