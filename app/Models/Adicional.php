<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adicional extends Model
{
  protected $table = 'adicional';
  protected $primaryKey = 'cd_adicional';
  public $timestamps = false;
  protected $fillable = ['nm_adicional','vl_adicional','ds_adicional'];
}