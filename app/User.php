<?php

namespace App;

use App\Http\Controllers\MailjetController;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use DB;

class User extends Authenticatable {

    use Notifiable,
    HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'agile_id', 'first_name', 'last_name', 'surname', 'identification', 'email', 'password',
        'cellphone', 'address', 'gender', 'ip_address', 'approved_sent',
        'updated_data', 'data_update_date', 'zone', 'city_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function avatar() {
        return $this->hasOne('App\Avatar');
    }

    public function points() {
        return $this->hasMany('App\Point');
    }

    public function companies() {
        return $this->belongsToMany('App\Distributor');
    }

    /**
     *
     * Obtiene la relacion de usuario y liquidaciones.
     *
     */
    public function liquidations(){
        return $this->belongsToMany('App\Liquidation')->withPivot('history', 'liquidation_id', 'user_id', 'created_at');
    }

    public function sendPasswordResetNotification($token) {
        $url = env('APP_URL') . '/password/reset/' . $token;
        $name = $this->first_name;

        $template = view('emails.reset-password', compact('url', 'name'))->render();

        $data['user_email'] = $this->email;
        $data['user_name'] = $name;
        $data['email_subject'] = 'No es hora de pits, recupera tu contraseña ahora';
        $data['email_description'] = 'Restaurar acceso a la platafoma Grand Prix ACDelco';
        $data['email_template'] = $template;

        MailjetController::sendEmail($data);
    }

    /**
     * Permite realizar busqueda de los usuarios por una palabra clave
     * @param \Illuminate\Database\Query\Builder $query
     * @param string $palabra
     */
    public function scopeWord($query, $palabra) {
        //Valida que la palabra enviada no sea vacia
        if (!is_null($palabra)) {
            $query->where('first_name', 'like', '%' . $palabra . '%')
            ->orWhere('last_name', 'like', '%' . $palabra . '%')
            ->orWhere('identification', 'like', '%' . $palabra . '%');
        }
    }

    /**
     *
     * Obtiene los puntos acumulados por usuario.
     * @param int $id
     * @return int
     *
     */
    public function getPoints($id)
    {
        $points = DB::table('points')->select(DB::raw('SUM(value) as total'))->groupBy('user_id')->where('user_id',$id)->first();

        if($points == null) {
          $total = 0;
        }else{
          $total = $points->total;
        }
        return $total;
    }
}
