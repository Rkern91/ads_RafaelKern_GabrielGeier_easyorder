<?php
  
  namespace App\Models;
  
  use Illuminate\Database\Eloquent\Model;
  
  class AdicionaisPedido extends Model
  {
    protected $table        = "adicionais_pedido";
    public    $timestamps   = false;
    public    $incrementing = false;
    
    protected $fillable = [
      "cd_item_pedido",
      "cd_adicional"
    ];
    
    public function item()
    {
      return $this->belongsTo(ItensPedido::class, "cd_item_pedido");
    }
    
    public function adicional()
    {
      return $this->belongsTo(Adicional::class, "cd_adicional");
    }
  }