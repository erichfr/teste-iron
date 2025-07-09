<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'descricao',
        'data_vencimento',
        'status',
        'prioridade',
        'user_id',
        'usuario_atribuido_id',
    ];


    protected $casts = [
        'data_vencimento' => 'datetime:Y-m-d',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function usuarioAtribuido()
    {
        return $this->belongsTo(User::class, 'usuario_atribuido_id');
    }
}
