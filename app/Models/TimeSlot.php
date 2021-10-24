<?php

namespace App\Models;

use App\Models\Traits\Active;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Ramsey\Uuid\Type\Time;

class TimeSlot extends Model
{
    use Active;
    protected $table='time_slot';

    protected $fillable=['name','day','from_time', 'to_time', 'order_till', 'isactive'];

    protected $hidden = ['created_at','day','deleted_at','updated_at'];

    public static function getAvailableTimeSlotsList($order_time, $city=null){

        $time_slots=[];

        if(empty($city)){
            $timeslot=TimeSlot::active()
                ->orderBy('order_till', 'asc')
                ->get();
        }else{
            $timeslot=TimeSlot::active()
                ->orderBy('order_till', 'asc')
                ->where('city', $city)
                ->get();
        }


        foreach($timeslot as $ts){
            if($order_time < $ts->order_till){
                if($ts->day == 0){
                    $time_slots[date('Y-m-d').' '.$ts->from_time]=[
                        'name' => 'Today '.$ts->name,
                        'id' => date('Y-m-d').'**'.$ts->id
                    ];
                }else{
                    $time_slots[date('Y-m-d', strtotime('+1 days')).' '.$ts->from_time]=[
                        'name' => 'Tomorrow '.$ts->name,
                        'id' => date('Y-m-d', strtotime('+1 days')).'**'.$ts->id
                    ];
                }
            }else{
                if($ts->day == 0){
                    $time_slots[date('Y-m-d', strtotime('+1 days')).' '.$ts->from_time]=[
                        'name' => 'Tomorrow '.$ts->name,
                        'id' => date('Y-m-d', strtotime('+1 days')).'**'.$ts->id
                    ];
                }else{
                    $time_slots[date('Y-m-d', strtotime('+2 days')).' '.$ts->from_time]=[
                        'name' => date('d M Y', strtotime('+2 days')).' '.$ts->name,
                        'id' => date('Y-m-d', strtotime('+2 days')).'**'.$ts->id
                    ];
                }
            }
        }

        ksort($time_slots);

        return array_values($time_slots);

    }
}
