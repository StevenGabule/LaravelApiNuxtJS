<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\IUser;
use App\Models\User;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;

class UserRepository extends BaseRepository implements IUser
{
  public function model() {
    return User::class;
  }

  public function findByEmail($email) {
    return $this->model->where('email', $email)->first();
  }

  public function search(Request $request) {
    $query = (new $this->model)->newQuery();

    // only designers who have designs
    if ($request->has_design) {
      $query->has('designs');
    }

    // check for available_to_hire
    if ($request->available_to_hire) {
      $query->where('available_to_hire', true);
    }

    // geographic search
    $lat = $request->latitude;
    $lng = $request->longtitude;
    $dist = $request->distance;
    $unit = $request->unit;

    if ($lat && $lng) {
      $point = new Point($lat, $lng);
      $unit == 'km' ? $dist *= 1000 : $dist *= 1609.34;
      $query->distanceSphereExcludingSelf('location', $point, $dist);
    }

    // order the results
    if ($request->orderBy == 'closest') {
      $query->orderByDistanceSphere('location', $point, 'asc');
    } else if ($request->orderBy == 'latest') {
      $query->latest();
    } else {
      $query->oldest();
    }

    return $query->get();
  }
}
