<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Usuario extends Authenticatable
{
  protected $table      = 'usuario';
  protected $primaryKey = 'cd_pessoa';
  public    $timestamps = false;
  protected $fillable   = [
    'nm_pessoa','ds_apelido','nr_cpf_cnpj','ds_email','ds_senha','ds_cargo'
  ];

  protected $hidden = ['ds_senha'];

  public function getAuthPassword()
  {
    return $this->ds_senha;
  }
}