<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdutoCategoria extends Model
{
  protected $table = 'produto_categoria';
  protected $primaryKey = 'cd_categoria';
  public $timestamps = false;
  protected $fillable = ['nm_categoria'];
}