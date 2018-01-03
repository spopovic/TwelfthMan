<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Http\Resources\ImageCollection;
use App\Http\Resources\Image as ImageResource;
use App\Image;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Intervention\Image\Exception\NotSupportedException;
use Illuminate\Support\Facades\File;


class ImageController extends Controller
{

    /**
     * Display a listing of the images.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->input('filter', false)) {
            //Return images with pagination
            return new ImageCollection(Image::paginate(config('image.items_per_page')));
        } else {
            //Return deleted images with pagination
            return new ImageCollection(Image::onlyTrashed()->paginate(config('image.items_per_page')));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'image' => 'required',
        ]);
        if (!$validator->fails()) {
            try {

                $imageFilename = ImageHelper::storeImage($request->file('image'), $request->file('image')->getClientOriginalExtension());
                $image = new Image();
                $image->path = $imageFilename;
                $image->name = $request->input('name');
                $image->save();

                return response([], Response::HTTP_CREATED);
            } catch (NotSupportedException $e) {
                return response(['data' => ['message' => 'Unsupported format']], Response::HTTP_BAD_REQUEST);
            } catch (\Exception $e) {
                return response(['data' => ['message' => 'Unable to upload file']], Response::HTTP_BAD_REQUEST);
            }
        } else {
            return response(['data' => ['message' => 'Fill all required fields', 'errors' => $validator->errors()]], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Image $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        return new ImageResource($image);
    }

    /**
     * @param $id
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function update($id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $image = Image::withTrashed()->find($id);

        if ($image) {
            if (isset($data['deleted'])) {
                if ($data['deleted']) {
                    $image->delete();
                } else if (!$data['deleted']) {
                    $image->restore();
                } else {
                    return response(['data' => ['message' => 'Deleted property is required']], Response::HTTP_BAD_REQUEST);
                }
                return response(null, Response::HTTP_NO_CONTENT);

            } else {
                return response(['data' => ['message' => 'Deleted property is required']], Response::HTTP_BAD_REQUEST);
            }
        } else {
            throw new ModelNotFoundException();
        }
    }
}
