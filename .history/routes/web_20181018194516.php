<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $data = [
        ['id' => 1, 'web_before_id' => 1, 'other_berfore_id' => 1, 'other_after_id' => 2, 'title' => 'title11', 'file' => 'file11', 'title_docs_1' => 'title_docs_11', 'title_docs_2' => 'title_docs_21'],
        ['id' => 2, 'web_before_id' => 2, 'other_berfore_id' => 1, 'other_after_id' => 2, 'title' => 'title22', 'file' => 'file22', 'title_docs_1' => 'title_docs_12', 'title_docs_2' => 'title_docs_22'],
        ['id' => 3, 'web_before_id' => 1, 'other_berfore_id' => 1, 'other_after_id' => 2, 'title' => 'title33', 'file' => 'file33', 'title_docs_1' => 'title_docs_13', 'title_docs_2' => 'title_docs_23'],
        ['id' => 4, 'web_before_id' => 4, 'other_berfore_id' => 1, 'other_after_id' => 2, 'title' => 'title44', 'file' => 'file44', 'title_docs_1' => 'title_docs_14', 'title_docs_2' => 'title_docs_24'],
        ['id' => 5, 'web_before_id' => 1, 'other_berfore_id' => 1, 'other_after_id' => 3, 'title' => 'title55', 'file' => 'file55', 'title_docs_1' => 'title_docs_15', 'title_docs_2' => 'title_docs_25'],
    ];

    $params = ['id' => 1];
dd(array_filter($data, function ($row) use ($params) {
    foreach ($params as $column => $v) {
        if (!array_key_exists($column, $row) || $row[$column] != $v) {
            return false;
        }
    }
    return true;
}));
    $result = array_reduce(array_filter($data, function ($row) use ($params) {
        foreach ($params as $column => $v) {
            if (!array_key_exists($column, $row) || $row[$column] != $v) {
                return false;
            }
        }
        return true;
    }), function ($carry, $item) {
        $migrationPattern = [
            'title' => $item['title'],
            'file' => $item['file']
        ];
        $docs = [];
        for ($i=1; $i <= 2; $i++) {
            $docs[] = [
                'title' => $item['title_docs_'.$i]
            ];
        }
        $carry[] = [
            'migration_pattern' => $migrationPattern,
            'documents' => $docs
        ];
        return $carry;
    });
    dd($result);
});
