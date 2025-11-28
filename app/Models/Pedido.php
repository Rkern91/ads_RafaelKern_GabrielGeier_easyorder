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
    
    public function itens()
    {
      return $this->hasMany(ItensPedido::class, 'cd_pedido', 'cd_pedido');
    }
    
    /**
     * Regra para trazer apenas pedidos ainda não quitados ao listar a conta da mesa.
     * @param $query
     * @return mixed
     */
    public function scopeNaoPagos($query)
    {
      return $query->whereNull('ds_asaas_return')
        ->orWhere('ds_asaas_return', '');
    }
  }