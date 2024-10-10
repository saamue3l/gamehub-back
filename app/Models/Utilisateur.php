<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class Utilisateur
 *
 * @property int $idUtilisateur
 * @property string $pseudo
 * @property string $email
 * @property string $password
 * @property string|null $photo
 * @property int|null $xp
 * @property int $idStatut
 * @property int $idRole
 *
 * @property Statut $statut
 * @property Role $role
 * @property Collection|Disponibilite[] $disponibilites
 * @property Collection|Evenement[] $evenements
 * @property Collection|HistoriqueAction[] $historiqueactions
 * @property Collection|JeuFavori[] $jeufavoris
 * @property Collection|Participation[] $participations
 * @property Collection|Post[] $posts
 * @property Collection|Pseudo[] $pseudos
 * @property Collection|Reaction[] $reactions
 * @property Collection|SuccesObtenu[] $succesobtenus
 * @property Collection|Sujet[] $sujets
 *
 * @package App\Models
 */
class Utilisateur extends Authenticatable
{
    protected $table = 'utilisateur';
    protected $primaryKey = 'idUtilisateur';
    public $timestamps = false;

    protected $casts = [
        'xp' => 'int',
        'idStatut' => 'int',
        'idRole' => 'int'
    ];

    protected $hidden = [
        'password'
    ];

    protected $fillable = [
        'pseudo',
        'email',
        'password',
        'photo',
        'xp',
        'idStatut',
        'idRole'
    ];

    public static function rules(): array
    {
        return [
            'pseudo' => 'required|string|max:50|unique:utilisateur,pseudo',
            'email' => 'required|string|email|max:100|unique:utilisateur,email',
            'password' => 'required|string|min:8',
            'photo' => 'nullable|string',
            'xp' => 'nullable|integer|min:0',
            'idStatut' => 'required|integer|exists:statut,idStatut',
            'idRole' => 'required|integer|exists:role,idRole',
        ];
    }


    public function statut()
    {
        return $this->belongsTo(Statut::class, 'idStatut');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'idRole');
    }

    public function disponibilites()
    {
        return $this->hasMany(Disponibilite::class, 'idUtilisateur');
    }

    public function evenements()
    {
        return $this->hasMany(Evenement::class, 'idUtilisateur');
    }

    public function historiqueactions()
    {
        return $this->hasMany(HistoriqueAction::class, 'idUtilisateur');
    }

    public function jeufavoris()
    {
        return $this->hasMany(JeuFavori::class, 'idUtilisateur');
    }

    public function participations()
    {
        return $this->hasMany(Participation::class, 'idUtilisateur');
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'idUtilisateur');
    }

    public function pseudos()
    {
        return $this->hasMany(Pseudo::class, 'idUtilisateur');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'idUtilisateur');
    }

    public function succesobtenus()
    {
        return $this->hasMany(SuccesObtenu::class, 'idUtilisateur');
    }

    public function sujets()
    {
        return $this->hasMany(Sujet::class, 'idUtilisateur');
    }
}
