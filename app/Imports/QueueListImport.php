<?php

namespace App\Imports;

use App\ClassModel;
use App\QueueListModel;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToCollection;

class QueueListImport implements ToCollection
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function collection(Collection $rows)
    {
        try {
            foreach ($rows as $row) {
                if ($row[0] == 'Ad') {
                    continue;
                }
                $class = ClassModel::firstOrCreate([
                    'name' => (string)$row[2]
                ]);
                QueueListModel::create([
                    'class_id' => $class->id,
                    'name' => (string)$row[0],
                    'surname' => (string)$row[1],
                    'status' => (int)1
                ]);
            }
        } catch (\Exception $e) {
            Log::info('selam');
        }
    }
}
