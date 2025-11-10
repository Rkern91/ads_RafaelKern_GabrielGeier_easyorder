<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Adicional extends Model
{
  protected $table      = 'adicional';
  protected $primaryKey = 'cd_adicional';
  public    $timestamps = false;
  protected $fillable   = ['nm_adicional','vl_adicional','ds_adicional', 'cd_categoria'];
  protected $hidden     = ['img_b64'];
  
  public function categoria()
  {
    return $this->belongsTo(ProdutoCategoria::class, 'cd_categoria', 'cd_categoria');
  }
}