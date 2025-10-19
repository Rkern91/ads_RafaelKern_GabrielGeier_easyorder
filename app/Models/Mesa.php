<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mesa extends Model
{
  protected $table      = 'mesa';
  protected $primaryKey = 'cd_mesa';
  public    $timestamps = false;
  protected $fillable   = ['nm_mesa'];
}