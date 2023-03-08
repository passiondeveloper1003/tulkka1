<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReview extends Model
{
  protected $table = 'user_reviews';
  public $timestamps = false;
  protected $guarded = ['id'];



  public function instructor()
  {
    return $this->belongsTo('App\User', 'instructor_id', 'id');
  }
  public function creator()
  {
        return $this->belongsTo('App\User', 'creator_id', 'id');
  }

  public function getRate()
  {
      $rate = 0;

      if (!empty($this->avg_rates)) {
          $rate = $this->avg_rates;
      } else {
          $reviews = $this->reviews()
              ->where('status', 'active')
              ->get();

          if (!empty($reviews) and $reviews->count() > 0) {
              $rate = number_format($reviews->avg('rates'), 2);
          }
      }


      if ($rate > 5) {
          $rate = 5;
      }

      return $rate > 0 ? number_format($rate, 2) : 0;
  }
}
