<?php

namespace App\Http\Controllers;

use Excel;
use App\User;
use App\Answer;
use App\Trivia;
use App\Question;
use Carbon\Carbon;
use App\TriviaUser;

class TriviasExcelController extends Controller
{
    public function participationReport($id)
    {
        $trivia = Trivia::find($id);

        if (!is_null($trivia)) {
            $triviaUsers = TriviaUser::where('trivia_id', $trivia->id)->get();

            $date = Carbon::now();
            $fileName = $date.'_'.$trivia->name.'_participantes';

            return Excel::create($fileName, function($excel) use ($trivia, $triviaUsers) {
                 $excel->sheet('Participantes', function($sheet) use ($trivia, $triviaUsers) {
                    $sheet->setStyle(array(
                        'font' => array(
                            'name' =>  'Arial',
                            'size' =>  11,
                        )
                    ));

                    $sheet->row(1, array(
                        'Documento', 'Nombre', 'Apellidos', 'Distribuidor',
                        'Tiempo de respuesta', 'Porcentaje de acierto', 'Puntos ganados'
                    ));

                    $sheet->cells('A1:G1', function($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setAlignment('center');
                    });

                    foreach ($triviaUsers as $item) {
                        $user = User::find($item->user_id);
                        $user->company = $user->companies()->first();

                        $user->won_points = 0;

                        if ($item->correctness_percent > 90) {
                            $user->won_points = $trivia->points_all_correct;
                        }

                        if ($item->correctness_percent > 79 && $item->correctness_percent < 90) {
                            $user->won_points = $trivia->points_all_correct;
                        }

                        $sheet->appendRow(array(
                            $user->identification,
                            $user->first_name,
                            $user->last_name,
                            (isset($user->company->name)) ? $user->company->name : '-',
                            $item->time_to_respond,
                            $item->correctness_percent,
                            $user->won_points
                        ));
                    }
                });
            })->export('xlsx');
        }
    }

    /**
   * Valida la información cargada del excel de preguntas y respuestas.
   *
   * @param  \Illuminate\Http\UploadedFile  $file
   * @param  string  $type
   * @return array
   */
    public static function validateQuestionsExcel($file, $type)
    {
        set_time_limit(1200); //20 min
        $errors = [];
        $index = 2;

        $rows = Excel::load($file)->get();

        foreach ($rows as $row) {
            if (isset($row['pregunta']) && is_null($row['pregunta'])) {
                array_push($errors, 'La fila '.$index.' debe tener pregunta.');
            }

            for ($i = 1; $i < 5; $i++) {
                if (isset($row[$i]) && is_null($row[$i])) {
                    array_push($errors, 'La fila '.$index.' debe tener respuesta '.$i);
                }
            }

            if (isset($row['correcta']) && is_null($row['correcta'])) {
                array_push($errors, 'La fila '.$index.' debe tener no. de respuesta correcta.');
            }

            if (isset($row['tiempo']) && is_null($row['tiempo'])) {
                array_push($errors, 'La fila '.$index.' debe tener tiempo de respuesta.');
            }

            if ($type == 'trivias') {
                if (isset($row['tiempo']) && !is_null($row['tiempo'])) {
                    if ($row['tiempo'] > 60) {
                        array_push($errors, 'La fila '.$index.' debe tener un tiempo menor a 60 (segundos).');
                    }
                }
            }

            $index++;
        }

        return $errors;
    }

    /**
     * Guarda la información cargada del excel de preguntas y respuestas.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  \App\Trivia  $trivia
     * @return void
     */
    public static function storeQuestionsAndAnswers($file, $trivia)
    {
        set_time_limit(1200); //20 min

        $rows = Excel::load($file)->get();

        foreach ($rows as $row) {
            $question = Question::create([
                'title' => $row['pregunta'],
                'seconds_to_answer' => $row['tiempo']
            ]);

            $trivia->questions()->attach($question->id);

            for ($i = 1; $i < 5; $i++) {
                Answer::create([
                    'description' => $row[$i],
                    'correct' => ($row['correcta'] == $i) ? true : false,
                    'question_id' => $question->id
                ]);
            }
        }
    }

    /**
     * Actualiza la información cargada del excel de preguntas y respuestas.
     *
     * @param  \Illuminate\Http\UploadedFile  $file
     * @param  \App\Trivia  $trivia
     * @return void
     */
    public static function updateQuestionsAndAnswers($file, $trivia)
    {
        $questions = $trivia->questions()->get();
        $trivia->questions()->detach();

        foreach ($questions as $question) {
            $question->answers()->delete();
            $question->delete();
        }

        self::storeQuestionsAndAnswers($file, $trivia);
    }
}
