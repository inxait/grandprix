<?php

namespace App;

use DB;
use Auth;
use App\Helpers\Calendar;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{

	public static function getUsers(){
		$identification = Auth::user()->identification;
		$distributor = Distributor::where(['nit' => $identification])->first();

		$users = [];
		if(!is_null($distributor)){
			$users = User::select('users.*')->join('distributor_user','distributor_user.user_id','=','users.id')
			->where('distributor_user.distributor_id',$distributor->id)->get();
		}

		return $users;
	}

	public static function pointsUpdateProfile($user_id){
		$months_period = [7,8,9];
		$points = DB::table('points')->select(DB::raw('SUM(value) as total'))->where('type',1)->whereIn('month',$months_period)->where('user_id', $user_id)->first();
		if (!is_null($points)) {
			return $points->total;
		}else{
			return '-';
		}
	}

	public static function pointsTrivia($user_id,$trivia_name){
		$months_period = [7,8,9];
		$result = DB::table('points')->where('type',4)->where('points_event','like','%'.$trivia_name.'%')->whereIn('month',$months_period)->where('user_id', $user_id)->first();
		if (!is_null($result)) {
			return $result;
		}else{
			return 0;
		}
	}

	public static function timeTrivia($user_id,$trivia_id){
		$result = DB::table('trivia_user')->where('trivia_id',$trivia_id)->where('created_at','>','2018-07-01')->where('created_at','<','2018-09-30')
									->where('user_id', $user_id)->first();
		if (!is_null($result)) {
			return $result;
		}else{
			return 0;
		}
	}

	public static function pointsActivity($user_id,$activity_name){
		$months_period = [7,8,9];
		$result = DB::table('points')->where('type',5)->where('points_event','like','%'.$activity_name.'%')->whereIn('month',$months_period)->where('user_id', $user_id)->first();
		if (!is_null($result)) {
			return $result;
		}else{
			return 0;
		}
	}

	public static function totalType($user_id,$type){
		$months_period = [7,8,9];
		switch ($type) {
			case 'trivia':
					$points = Point::select(DB::raw('SUM(value) as total'))->where('type',4)->whereIn('month',$months_period)->where('user_id', $user_id)->first();
				break;
			case 'other':
					$points = Point::select(DB::raw('SUM(value) as total'))->whereIn('type',[2,3,5])->whereIn('month',$months_period)->where('user_id', $user_id)->first();
				break;
			case 'update':
					$points = Point::select(DB::raw('SUM(value) as total'))->where('type',1)->whereIn('month',$months_period)->where('user_id', $user_id)->first();
				break;
			case 'all':
					$points = Point::select(DB::raw('SUM(value) as total'))->whereIn('month',$months_period)->where('user_id', $user_id)->first();
				break;
		}

		if (!is_null($points)) {
			return $points;
		}else{
			return '0';
		}

	}

	public static function setStyles($sheet){
		$sheet->getDefaultStyle()
			->getAlignment()
			->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

		$sheet->mergeCells('D1:F1');
		$sheet->mergeCells('G1:I1');
		$sheet->mergeCells('J1:L1');
		$sheet->mergeCells('M1:O1');
		$sheet->mergeCells('P1:R1');
		$sheet->mergeCells('S1:U1');
		$sheet->mergeCells('V1:X1');
		$sheet->mergeCells('Y1:AA1');
		$sheet->mergeCells('AB1:AD1');
		$sheet->mergeCells('AE1:AG1');
		$sheet->mergeCells('AH1:AJ1');
		$sheet->mergeCells('AK1:AM1');
		$sheet->mergeCells('AN1:AP1');
		$sheet->mergeCells('AQ1:AS1');
		$sheet->mergeCells('AT1:AV1');

		$sheet->cells('A1:BZ2', function($cells) {
				$cells->setFontWeight('bold');
				$cells->setAlignment('center');
		});

		return $sheet;
	}

}
