<?php
namespace SuperHeroList;

use Exception;
use SuperHeroList\app\Request;
use SuperHeroList\Models\Record;

class Controller
{
    public function __construct(private $view)
    {
    }

    /**
     * Показать главную страницу со всеми записями
     */
    public function index()
    {
        $records = Record::all();
        echo $this->view->render('index.html', ['records' => $records]);
    }

    public function saveRecord()
    {
        $r = Record::load(Request::$data[0]);
        $r->save();
        echo json_encode(['success' => true]);
    }

    public function getRecordsAll()
    {
        $records = Record::all();
        echo $this->view->render('main.html', ['records' => $records]);
    }

    public function deleteRecord()
    {
        $records = array_map(function($data) {
            return Record::load($data);
        }, Request::$data);
        $results = [];
        foreach ($records as $record) {
            if ($record->delete() === null) {
                $results[] = [
                'success' => false,
                'recordId' => $record->getId()
            ];
            } else $results[] = ['success' => true];
        }
        echo json_encode($results);
    }
}