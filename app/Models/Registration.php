<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'vaccine_center_id',
        'scheduled_date',
        'status'
    ];

    public static function boot()  {
        parent::boot();
        static::creating(function($registration)  {
            $count = self::where('vaccine_center_id',$registration->vaccine_center_id )
                ->where('scheduled_date', $registration->scheduled_date)
                ->count();

            $center = VaccineCenter::find($registration->vaccine_center_id);

            if ($count >= $center->daily_capacity) {
                throw new \Exception('No Available slot for this date!');
            }

            // while ($count >= $center->daily_capacity || self::isWeekEnd($registration->scheduled_date)) {
            //     $date = date('Y-m-d', strtotime($registration->scheduled_date, ' +1 day'));
            //     $count = self::where('vaccine_center_id', $registration->vaccine_center_id)
            //         ->where('scheduled_date', $registration->scheduled_date)
            //         ->count();
            // }

            while ($count >= $center->daily_capacity || self::isWeekEnd($registration->scheduled_date)) {
                $registration->scheduled_date = $registration->scheduled_date->modify('+1 day');
                $count = self::where('vaccine_center_id', $registration->vaccine_center_id)
                    ->where('scheduled_date', $registration->scheduled_date)
                    ->count();
            }


        });
    }
    // protected static function isWeekEnd($data)  {
    //     $dateOfWeek = date('w', strtotime($data));
    //     return ($dateOfWeek == 5 || $dateOfWeek == 6);
    // }
    protected static function isWeekEnd($data) {
        if ($data instanceof \DateTime) {
            $data = $data->format('Y-m-d');
        }
        $dateOfWeek = date('w', strtotime($data));
        return ($dateOfWeek == 5 || $dateOfWeek == 6);
    }


    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function vaccineCenter() : BelongsTo {
        return $this->belongsTo(VaccineCenter::class);
    }

}
