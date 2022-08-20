<?php

namespace App\Http\Controllers\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\NotebookStoreRequest;
use App\Http\Requests\NotebookUpdateRequest;
use App\Models\Notebook;
use Illuminate\Http\JsonResponse;
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
     *     @OA\Response(
     *         response="201",
     *         description="Созданно",
     *
     *     ),
     *   @OA\RequestBody(
     *    required=true,
     *    @OA\JsonContent(
     *       required={"email","full_name","phone"},
     *       @OA\Property(property="full_name", type="string", example="ФИО"),
     *       @OA\Property(property="phone", type="string", example="79999999999"),
     *       @OA\Property(property="email", type="string", format="email", example="email@mail.ru"),
     *       @OA\Property(property="company", type="string", example="Компания"),
     *       @OA\Property(property="date_birth", type="date", example="11-11-1111"),
     *    ),
     * ),
     * )
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
     *     @OA\Response(
     *         response="404",
     *         description="Запись не найдена",
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
     * @OA\Put(
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
     *     @OA\Response(
     *         response="200",
     *         description="Все хорошо",
     *
     *     ),
     *     @OA\RequestBody(
     *      required=true,
     *      @OA\JsonContent(
     *          required={"email","full_name","phone"},
     *          @OA\Property(property="full_name", type="string", example="ФИО"),
     *          @OA\Property(property="phone", type="string", example="79999999999"),
     *          @OA\Property(property="email", type="string", format="email", example="email@mail.ru"),
     *           @OA\Property(property="company", type="string", example="Компания"),
     *          @OA\Property(property="date_birth", type="date", example="11-11-1111"),
     *      ),
     *     ),
     *
     * )
     *
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(NotebookUpdateRequest $request, Notebook $notebook)
    {

        $data = $request->validated();

        if (isset($data['photo']) && $notebook->photo !== null) {
            $data['photo'] = Storage::disk('public')->put('/photo', $data['photo']);
            dd(Storage::disk('public')->delete($notebook->photo)) ;
        }
        if(isset($data['photo']) && $notebook->photo == null){
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
