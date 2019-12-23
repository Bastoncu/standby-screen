<?php

namespace App\Http\Controllers;

use App\ClassModel;
use App\Events\QueueList;
use App\Events\TriggerImport;
use App\Imports\QueueListImport;
use App\QueueListModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class QueueListController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post')) {
            $queueList = QueueListModel::where('class_id', $request->get('classId'))->where('status', 1)->latest()->get();
            $currentUser = QueueListModel::where('class_id', $request->get('classId'))->where('status', 2)->latest('updated_at')->first();

            return response()->json(['queueList' => $queueList, 'currentUser' => $currentUser->fullname ?? '']);
        }

        return '';
    }

    public function store(QueueListModel $queueList, Request $request)
    {
        $queueList->where('class_id', $request->get('classId'))->where('status', 2)->update([
            'status' => 0
        ]);

        $queueList->where('id', $request->queueUserId)->update([
            'status' => 2
        ]);

        $currentUser = $queueList->where('class_id', $request->get('classId'))->where('status', 2)->latest('updated_at')->first();
        event(new QueueList($currentUser));

        return response()->json($currentUser);
    }

    public function getClasses()
    {
        return ClassModel::get();
    }

    public function import(Request $request)
    {
        $file = $request->file('file');
        $rules = [
            'file' => 'required|max:2048|mimes:xlsx,zip'
        ];
        $validator = Validator::make($request->all(), $rules);

        if (! $validator->fails()) {
            QueueListModel::truncate();
            ClassModel::truncate();
            $fileName = 'queue.' . $file->getClientOriginalExtension();
            $file->move(storage_path('imports/'), $fileName);
            $import = Excel::import(new QueueListImport, storage_path('imports/' . $fileName));
            if ($import) {
                event(new TriggerImport('Mülakat Başlamıştır!'));
                return response()->json(['status' => 'success', 'message' => 'Liste Başarıyla Yüklendi!']);
            }
        }
        return response()->json(['status' => 'error', 'message' => 'Liste Yüklenemedi!']);
    }

    public function getQueueList(QueueListModel $queueList)
    {
        $currentQueue = $queueList->where('status', 2)->get();
        $lastQueue = $queueList->where('status', 0)->latest('updated_at')->limit(10)->get();
        return response()->json(['currentQueue' => $currentQueue, 'lastQueue' => $lastQueue]);
    }
}
