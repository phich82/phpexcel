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

Route::get('/test/{isGrouped?}', function ($isGrouped = 0) {
    $data = [
        ['id' => 1, 'web_before_id' => 1, 'other_before_id' => 1, 'other_after_id' => 2, 'title' => 'title11', 'file' => 'file11', 'title_docs_1' => 'title_docs_11', 'title_docs_2' => 'title_docs_21'],
        ['id' => 2, 'web_before_id' => 2, 'other_before_id' => 1, 'other_after_id' => 2, 'title' => 'title22', 'file' => 'file22', 'title_docs_1' => 'title_docs_12', 'title_docs_2' => 'title_docs_22'],
        ['id' => 3, 'web_before_id' => 1, 'other_before_id' => 1, 'other_after_id' => 2, 'title' => 'title33', 'file' => 'file33', 'title_docs_1' => 'title_docs_13', 'title_docs_2' => 'title_docs_23'],
        ['id' => 4, 'web_before_id' => 4, 'other_before_id' => 1, 'other_after_id' => 2, 'title' => 'title44', 'file' => 'file44', 'title_docs_1' => 'title_docs_14', 'title_docs_2' => 'title_docs_24'],
        ['id' => 5, 'web_before_id' => 1, 'other_before_id' => 1, 'other_after_id' => 3, 'title' => 'title55', 'file' => 'file55', 'title_docs_1' => 'title_docs_15', 'title_docs_2' => 'title_docs_25'],
    ];

    $params = ['other_before_id' => 1, 'other_after_id' => 2];
    $isGrouped = $isGrouped == 1;

    $result = array_reduce(array_filter($data, function ($row) use ($params) {
        foreach ($params as $column => $v) {
            if (!array_key_exists($column, $row) || $row[$column] != $v) {
                return false;
            }
        }
        return true;
    }), function ($carry, $item) use ($isGrouped) {
        $patterns = [
            'title' => $item['title'],
            'file'  => $item['file']
        ];
        $docs = $carry['documents'] ?? [];
        for ($i=1; $i <= 2; $i++) {
            $docs[] = [
                'title' => $item['title_docs_'.$i],
                'file'  => $item['file_docs_'.$i] ?? null,
            ];
        }
        if ($isGrouped === true) {
            $carry['patterns'][] = $patterns;
            $carry['documents']  = $docs;
        } else {
            $carry[] = [
                'patterns'  => $patterns,
                'documents' => $docs
            ];
        }
        return $carry;
    });
    return $isGrouped ? view('test', $result) : $result;
});
