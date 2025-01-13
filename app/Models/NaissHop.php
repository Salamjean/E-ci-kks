<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NaissHop extends Model
{
    use HasFactory;
    protected $fillable = [
        'NomM',
        'PrM',
        'contM',
        'dateM',
        'CNI_mere',
        'NomP',
        'PrP',
        'contP',
        'CNI_Pere',
        'NomEnf',
        'commune',
        'codeCMU',
        'lien',
        'DateNaissance',
        'codeDM',
        'codeCMN',
        'sexe',
        'sous_admin_id',  // Ajout de sous_admin_id
    ];

     // Définir la relation avec le docteur
     public function sous_admin()
     {
         return $this->belongsTo(SousAdmin::class);
     }
}
