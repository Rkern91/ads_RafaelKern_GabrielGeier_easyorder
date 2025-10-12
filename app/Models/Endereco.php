<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Endereco extends Model
{
  protected $table = 'endereco';
  protected $primaryKey = 'cd_endereco';
  public $timestamps = false;
  protected $fillable = [
    'nm_cidade','nm_uf','nm_bairro','nm_logradouro','nr_endereco','nr_cep','ds_complemento'
  ];
}