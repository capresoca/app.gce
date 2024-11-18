<?php

namespace App;

use App\Models\RedServicios\RsEntidade;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Jedrzej\Pimpable\PimpableTrait;
use Jedrzej\Searchable\Constraint;
use OwenIt\Auditing\Contracts\Auditable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User
    extends Authenticatable
    implements JWTSubject, Auditable
{
    use \OwenIt\Auditing\Auditable;
    use PimpableTrait;
    use Notifiable;

    protected $searchable = ['search', 'name', 'email', 'identification', 'phone', 'state'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'identification',
        'name',
        'email',
        'password',
        'rs_entidad_id',
        'state',
        'tipo',
        'phone'
    ];

    protected $table = 'gs_usuarios';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token', 'password', 'created_at', 'updated_at'
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function entidad()
    {
        return $this->belongsTo(RsEntidade::class, 'rs_entidad_id');
    }

    public function roles()
    {
        return $this->belongsToMany('App\GsRole', 'gs_rol_usuarios', 'gs_usuario_id', 'gs_role_id');
    }

    public function hasAnyRole($roles)
    {

        if ($roles->count()) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        } else {
            if ($this->hasRole($roles)) {
                return true;
            }
        }
    }

    public function hasRole($role)
    {
        if ($this->roles()->where('gs_roles.id', $role)->first()) {
            return true;
        }
    }

    protected function processSearchFilter(Builder $builder, Constraint $constraint)
    {
        // this logic should happen for LIKE/EQUAL operators only

        if ($constraint->getOperator() === Constraint::OPERATOR_LIKE || $constraint->getOperator() === Constraint::OPERATOR_EQUAL) {
            $builder->where('identification', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('name', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('email', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('state', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('phone', $constraint->getOperator(), $constraint->getValue())
                ->orWhere('tipo', $constraint->getOperator(), $constraint->getValue());
            return true;
        }
        // default logic should be executed otherwise
        return false;
    }


    public function scopeBuscar($query, $data)
    {
        return $query->where('identification', 'like', '%' . $data . '%')
            ->orWhere('name', 'like', '%' . $data . '%');
    }
}
