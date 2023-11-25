<?php

namespace App\Services\Traits;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

trait ImportTrait {

    public static function formatDate($date){
        try {
            $format_date = Carbon::createFromFormat('Y-m-d', $date);
            return $format_date;
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException("Format DATE should be YYYY-MM-DD");
        }
    }

    public static function sheetData($name = "noname", $file){
        try {
            $new_file = $name.'.xlsx';
            if (is_file('tmp/' . $new_file)) // Jika file tersebut ada
                unlink('tmp/' . $new_file); // Hapus file tersebut

            $tmp_file = $file->getRealPath();
            move_uploaded_file($tmp_file, 'file_import/' . $new_file);
            $spreadsheet = IOFactory::load('file_import/' . $new_file);
            $sheetData = $spreadsheet->getActiveSheet()->toArray();
            return $sheetData;
        } catch (\Throwable $th) {
            throw new \InvalidArgumentException($th->getMessage());
        }
    }
}
