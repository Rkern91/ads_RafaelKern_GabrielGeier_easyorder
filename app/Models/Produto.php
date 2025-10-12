<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
  protected $table = 'produto';
  protected $primaryKey = 'cd_produto';
  public $timestamps = false;
  protected $fillable = ['cd_categoria','nm_produto','vl_valor','ds_produto','cd_usuario_cadastro'];

  public function categoria()
  {
    return $this->belongsTo(ProdutoCategoria::class, 'cd_categoria', 'cd_categoria');
  }
}