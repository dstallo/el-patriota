<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Axys\AxysFlasher as Flasher;
use Illuminate\Support\Facades\Validator;

use App\Banner;

class Banners extends Controller
{
	public function link(Banner $banner)
	{
		$banner->clicks += 1;
		$banner->save();

		return redirect($banner->link);
	}
}