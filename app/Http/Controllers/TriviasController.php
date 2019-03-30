<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Excel;
use stdClass;
use App\User;
use Validator;
use App\Point;
use App\Trivia;
use App\Answer;
use App\Reward;
use App\Question;
use Carbon\Carbon;
use App\TriviaUser;
use Illuminate\Http\Request;
use App\Http\Controllers\TriviasExcelController;

class TriviasController extends Controller
{
    private $baseRules = [
        'name' => 'required|string|max:100',
        'description' => 'required|string',
        'start_date' => 'required|date_format:"d/m/Y"',
        'finish_date' => 'required|date_format:"d/m/Y"',
        'points_all_correct' => 'required|integer',
        'total_val' => 'required',
        'points_some_correct' => 'required',
        'reward_id' => 'required',
        'number_questions_random' => 'required|integer'
    ];

    public function index()
    {
        $trivias = Trivia::orderBy('created_at', 'desc')->paginate(10);

        return view('pages.admin.trivias.index')->with(compact('trivias'));
    }

    public function create()
    {
        $rewards = Reward::where('active', true)->get();

        return view('pages.admin.trivias.create')->with(compact('rewards'));
    }

    public function showCurrentTrivia()
    {
        $now = date('Y-m-d');
        $trivia = Trivia::where('active', true)->first();
        $isTriviaBetweenDates = false;

        //Compare dates range
        if (!is_null($trivia)) {
            if (strtotime($now) >= strtotime($trivia->start_date)
                && strtotime($now) <= strtotime($trivia->finish_date)) {
                $isTriviaBetweenDates = true;
        }
    }

    if ($isTriviaBetweenDates) {
        $user = Auth::user();
        $trivia->reward = Reward::find($trivia->reward_id);
        $usrTriviaResults = TriviaUser::where('trivia_id', $trivia->id)
        ->where('user_id', $user->id)->first();
        $currentRanking = TriviaUser::where('trivia_id', $trivia->id)
        ->orderBy('correctness_percent', 'desc')
        ->orderBy('time_to_respond', 'asc')->get();

        foreach ($currentRanking as $ranking) {
            $ranking->user = User::find($ranking->user_id);
        }

        $usrMadeTrivia = false;

        if (!is_null($usrTriviaResults)) {
            $usrMadeTrivia = true;
        }

        return view('pages.trivia')->with(compact('trivia', 'usrMadeTrivia', 'currentRanking'));
    } else {
        return redirect('trivias-inactivas');
    }
}

public function getActiveTrivia()
{
    $trivia = Trivia::where('active', true)->first();
    $trivia->reward = Reward::find($trivia->reward_id);
    if ($trivia->number_questions_random != NULL) {
      $trivia->questions = $trivia->questions()
      ->inRandomOrder()->limit($trivia->number_questions_random)->get();
  }else{
      $trivia->questions = $trivia->questions()->get();
  }

  foreach ($trivia->questions as $question) {
            //convert to milliseconds
    $question->seconds_to_answer = ($question->seconds_to_answer * 1000);
    $question->answers = $question->answers()->get();
}

return response()->json(['success' => true, 'info' => $trivia]);
}

public function showNoTriviaActive()
{
    return view('pages.trivia-no-trivia');
}

public function toggleTriviaState($id)
{
    $trivia = Trivia::find($id);

    if (!is_null($trivia)) {
        $trivia->active = !$trivia->active;
        $trivia->save();

        return back()->with('status', 'Se ha actualizado el estado de la trivia correctamente.');
    }
}

public function store(Request $request)
{
  $this->baseRules['excel_questions'] = 'required|file';

  if (!is_null($request->file('study_information'))) {
      $this->baseRules['study_information'] = 'required|file';
  }

  $validator = Validator::make($request->all(), $this->baseRules);

  if ($validator->fails()) {
      return back()->withErrors($validator)->withInput();
  }

  $excelQuestions = $request->file('excel_questions');
  $excelErrors = TriviasExcelController::validateQuestionsExcel($excelQuestions, $request->type);

  if (count($excelErrors)) {
      return back()->withErrors((object) ['excel_questions' => $excelErrors]);
  }

  $triviaItem = $this->getBaseTriviaItem($request);

  if ($request->hasFile('study_information')) {
      $triviaItem['study_information'] = $request->study_information->store('uploads');
  }

  Trivia::where('id','>',0)->update(['active' => false]);

  $trivia = Trivia::create($triviaItem);
  TriviasExcelController::storeQuestionsAndAnswers($excelQuestions, $trivia);

  return redirect('dashboard/trivias')->with('status', 'Se ha creado la trivia correctamente');
}

public function edit($id)
{
  $trivia = Trivia::find($id);
  $rewards = Reward::where('active', true)->get();

  return view('pages.admin.trivias.edit')->with(compact('trivia','rewards'));
}

public function update(Request $request, $id)
{
    if (!is_null($request->file('excel_questions'))) {
        $this->baseRules['excel_questions'] = 'required|file';
    }

    if (!is_null($request->file('study_information'))) {
        $this->baseRules['study_information'] = 'required|file';
    }

    $validator = Validator::make($request->all(), $this->baseRules);

    if ($validator->fails()) {
        return back()->withErrors($validator)->withInput();
    }

    $trivia = Trivia::find($id);

    if (!is_null($request->file('excel_questions'))) {
        $excelQuestions = $request->file('excel_questions');
        $excelErrors = TriviasExcelController::validateQuestionsExcel($excelQuestions, $request->type);

        if (count($excelErrors)) {
            return back()->withErrors((object) ['excel_questions' => $excelErrors]);
        }

        TriviasExcelController::updateQuestionsAndAnswers($excelQuestions, $trivia);
    }

    $triviaItem = $this->getBaseTriviaItem($request);

    if (!is_null($request->file('study_information'))) {
        if (file_exists(storage_path('app/public/'.$trivia->study_information))) {
            unlink(storage_path('app/public/'.$trivia->study_information));
        }

        $triviaItem['study_information'] = $request->study_information->store('uploads');
    }

    $trivia->update($triviaItem);

    return redirect('dashboard/trivias')->with('status', 'Se ha actualizado la trivia correctamente');
}

public function evaluateTrivia(Request $request)
{
    $validator = Validator::make($request->all(), [
        'trivia' => 'required|integer',
        'answers' => 'required'
    ]);

    if ($validator->fails()) {
        return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
    }

    $usrAnswersResults = [];
    $usrAnswers = json_decode(json_encode($request->input('answers')));
    $trivia = Trivia::find($request->input('trivia'));
    $questions = $trivia->questions()->get();
    $pointsPerAnswer = $trivia->total_val / count($usrAnswers);

    $totalCorrect = 0;
    $totalErroneous = 0;

    foreach ($questions as $question) {
        foreach ($usrAnswers as $item) {
            if ($item->question == $question->id) {
                $score = new stdClass;
                $score->question = $question->title;
                $score->time = $question->seconds_to_answer - (int) $item->time_left;

                if (is_null($item->answer)) {
                    $score->answer = 0;
                    $totalErroneous += 1;
                } else {
                    $answer = Answer::find($item->answer);
                    $score->answer = ($answer->correct == 1) ? $pointsPerAnswer : 0;
                    ($answer->correct == 1) ? $totalCorrect += 1 : $totalErroneous += 1;
                }

                array_push($usrAnswersResults, $score);
            }
        }
    }

    $totalPoints = 0;
    $totalTime = 0;

    foreach ($usrAnswersResults as $result) {
        $totalPoints = $totalPoints + $result->answer;
        $totalTime = $totalTime + $result->time;
    }

    $user = Auth::user();

    $usrTrivia = TriviaUser::create([
        'trivia_id' => $trivia->id,
        'user_id' => $user->id,
        'time_to_respond' => $this->secondsToTime($totalTime),
        'correctness_percent' => ($totalPoints * 100) / $trivia->total_val
    ]);

    $now = Carbon::now();

    $results['answers'] = $usrAnswersResults;
    $results['points'] = 0;

    $points = Point::firstOrCreate([
        'points_event' => 'Participación Trivia: '.$trivia->name,
        'user_id' => $user->id
    ], [
        'month' => $now->month,
        'year' => $now->year,
        'value' => 10,
        'type' => 4
    ]);

    $results['points'] += $points->value;

    if (intval($usrTrivia->correctness_percent) > 90) {
        $points = Point::firstOrCreate([
            'points_event' => 'Trivia: '.$trivia->name,
            'user_id' => $user->id
        ], [
            'month' => $now->month,
            'year' => $now->year,
            'value' => $trivia->points_all_correct,
            'type' => 4
        ]);
        $results['points'] = $trivia->points_all_correct;
    }

    if (intval($usrTrivia->correctness_percent) > 70 && intval($usrTrivia->correctness_percent) < 90) {
        $points = Point::firstOrCreate([
            'points_event' => 'Trivia: '.$trivia->name,
            'user_id' => $user->id
        ], [
            'month' => $now->month,
            'year' => $now->year,
            'value' => $trivia->points_some_correct,
            'type' => 4
        ]);

        $results['points'] = $trivia->points_some_correct;
    }

    return response()->json(['success' => true, 'info' => $results]);
}

private function secondsToTime($seconds)
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;

    return "$hours:$minutes:$seconds";
}


private function getBaseTriviaItem(Request $request)
{
    $startDate = Carbon::createFromFormat('d/m/Y', $request->start_date);
    $finishDate = Carbon::createFromFormat('d/m/Y', $request->finish_date);

    return [
        'name' => $request->name,
        'description' => $request->description,
        'start_date' => $startDate->toDateString(),
        'finish_date' => $finishDate->toDateString(),
        'total_val' => $request->total_val,
        'points_all_correct' => $request->points_all_correct,
        'points_some_correct' => $request->points_some_correct,
        'number_questions_random' => $request->number_questions_random,
        'reward_id' => $request->reward_id,
        'active' => true
    ];
}
}
