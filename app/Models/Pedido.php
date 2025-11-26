<?php
  
  namespace App\Models;
  
  use Illuminate\Database\Eloquent\Model;
  
  class Pedido extends Model
  {
    protected $table        = "pedido";
    protected $primaryKey   = "cd_pedido";
    public    $incrementing = true;
    protected $keyType      = "int";
    public    $timestamps   = false;
    
    // Campos preenchíveis
    protected $fillable = [
      "cd_mesa",
      "vl_pedido",
      "dt_pedido",
      "id_status",
      "ds_observacao",
      "ds_asaas_customer_id",
      "ds_asaas_payment_id",
      "ds_asaas_return",
    ];
    
    // Cast dos campos
    protected $casts = [
      "vl_pedido" => "decimal:2",
      "dt_pedido" => "datetime",
      "id_status" => "integer",
    ];
    
    /**
     * RELACIONAMENTOS
     */
    public function mesa()
    {
      return $this->belongsTo(Mesa::class, "cd_mesa", "cd_mesa");
    }    
    
    public function produtos()
    {
      return $this->belongsToMany(Produto::class, "itens_pedido", "cd_pedido", "cd_produto")
        ->withPivot("qt_produto");
    }
  }