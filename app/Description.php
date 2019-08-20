<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Input as Input;
use Auth;
class Description extends Model
{
    //protected $hidden = [
      //'date' ,
    //];
    public function saveDescription($date) {
        $this->description = Input::get("description");
        $this->id_users = Auth::User()->id;
        $this->date = $date;
        $this->save();
        $id = $this->where("id_users",Auth::User()->id)
                ->orderBy("id","DESC")->first();
        return $id->id;
    }
}
