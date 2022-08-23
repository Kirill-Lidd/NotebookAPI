<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotebookStoreRequest;
use App\Http\Requests\NotebookUpdateRequest;
use App\Models\Notebook;
use Illuminate\Support\Facades\Storage;

class NotebookController extends Controller
{
    /**
     * @OA\Get(
     *     path="/notebook",
     *     operationId="AllRecords",
     *     tags={"Notebook"},
     *     summary="Покозать все записи",
     *     security={
     *       {"api_key": {}},
     *     },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Номер страницы",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *     ),
     *
     * )
     *
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Request
     */

    public function index()
    {
        return Notebook::query()->paginate(5);
    }

    /**
     * @OA\Post(
     *     path="/notebook",
     *     operationId="RecordCreate",
     *     tags={"Notebook"},
     *     summary="Создать запись",
     *     security={
     *       {"api_key": {}},
     *     },
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\MediaType(
     *       mediaType="multipart/form-data",
     *       @OA\Schema(
     *         required={"full_name","phone","email"},
     *         @OA\Property(property="full_name", type="string", example="ФИО"),
     *         @OA\Property(property="phone", type="string", example="79999999999"),
     *         @OA\Property(property="email", type="string", format="email", example="email@example.com"),
     *         @OA\Property(property="company", type="string", example="Компания"),
     *         @OA\Property(description="Upload image",property="photo",type="string",format="binary"),
     *         @OA\Property(property="date_birth",type="string",format="date",example="22-02-1212"),
     *       )
     *     )
     *   ),
     *   @OA\Response(
     *         response="201",
     *         description="Созданно",
     *   ),
     * )
     *
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\NotebookStoreRequest $request
     *
     * @return \Illuminate\Http\Request
     */

    public function store(NotebookStoreRequest $request)
    {
        $data = $request->validated();

        if (isset($data['photo'])) {
            $data['photo'] = Storage::disk('public')->put('/photo', $data['photo']);
        }

        return Notebook::create($data);
    }

    /**
     * @OA\Get(
     *     path="/notebook/{id}",
     *     operationId="RecordGet",
     *     tags={"Notebook"},
     *     summary="Получить запись по ID",
     *     security={
     *       {"api_key": {}},
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID записи",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *     ),
     * )
     *
     * Display a listing of the resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */

    public function show(Notebook $notebook)
    {
        return Notebook::find($notebook);
    }

    /**
     * @OA\PUT(
     *     path="/notebook/{id}",
     *     operationId="RecordUpdate",
     *     tags={"Notebook"},
     *     summary="Изменить запись по ID",
     *     security={
     *       {"api_key": {}},
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID записи",
     *         required=true,
     *         example="",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *       @OA\MediaType(
     *       mediaType="application/json",
     *          @OA\Schema(
     *              required={"email","full_name","phone"},
     *              @OA\Property(property="full_name", type="string", example="ФИО(update)"),
     *              @OA\Property(property="phone", type="string", example="79999999999(update)"),
     *              @OA\Property(property="email", type="string", format="email", example="email@example.com(update)"),
     *              @OA\Property(property="company", type="string", example="Компания(update)"),
     *              @OA\Property(property="date_birth", type="string",format="date",example="01-01-1111"),
     *          ),
     *     ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *     ),
     *
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(NotebookUpdateRequest $request, Notebook $notebook)
    {

        $data = $request->validated();

        if (isset($data['photo']) && $notebook->photo !== null) {
            $data['photo'] = Storage::disk('public')->put('/photo', $data['photo']);
            dd(Storage::disk('public')->delete($notebook->photo));
        }
        if (isset($data['photo']) && $notebook->photo == null) {
            $data['photo'] = Storage::disk('public')->put('/photo', $data['photo']);
        }

        return $notebook->update($data);
    }

    /**
     * @OA\Delete(
     *     path="/notebook/{id}",
     *     operationId="RecordDelete",
     *     tags={"Notebook"},
     *     summary="Удалить запись по ID",
     *     security={
     *       {"api_key": {}},
     *     },
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Удалить по ID ",
     *         required=true,
     *         example="",
     *         @OA\Schema(
     *             type="integer",
     *         ),
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *     ),
     * )
     *
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notebook $notebook)
    {
        if (isset($notebook->photo)) {
            Storage::disk('public')->delete($notebook->photo);
        }
        return $notebook->delete();
    }
}
