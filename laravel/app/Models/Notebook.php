<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
/**
 * @OA\Schema(
 *     title="Notebook",
 *     description="Notebook model",
 *     @OA\Xml(
 *         name="Notebook"
 *     )
 * )
 */

class Notebook extends Model
{


    protected $table = 'notebooks';
    protected $guarded = false;

    protected $casts = [
        'date_birth' => 'date:d-m-Y',
    ];
}
