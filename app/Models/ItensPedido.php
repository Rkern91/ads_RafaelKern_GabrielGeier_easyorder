<?php
  
  namespace App\Models;
  
  use Illuminate\Database\Eloquent\Model;
  
  class ItensPedido extends Model
  {
    protected $table      = "itens_pedido";
    protected $primaryKey = "cd_item_pedido";
    public $timestamps    = false;
    
    protected $fillable = [
      "cd_pedido",
      "cd_produto",
      "qt_produto"
    ];
    
    public function produto()
    {
      return $this->belongsTo(Produto::class, 'cd_produto', 'cd_produto');
    }
    
    public function adicionais()
    {
      return $this->hasMany(AdicionaisPedido::class, 'cd_item_pedido', 'cd_item_pedido');
    }
  }
