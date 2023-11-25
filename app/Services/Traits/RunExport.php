<?php

namespace App\Services\Traits;

use Illuminate\Validation\ValidationException;
use App\Exports\GeneralExport;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Trait RunExport
 *
 * A trait for exporting data to Excel.
 */
trait RunExport
{
    /**
     * Apply query builder modifications.
     *
     * @return object
     */
    public function applyQueryBuilder(): object
    {
        parent::applyQueryBuilder();
        return $this;
    }

    /**
     * Run the export process.
     *
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws ValidationException
     */
    public function runExport()
    {

        try {
            $this->selectColumns();
            $this->applyQueryBuilder();
            $data = $this->parseQuerytoArrayVer2();
            return Excel::download(new GeneralExport($data), $this->request->name . "." . $this->request->file_type);
        } catch (\Throwable $th) {
            dd($th);
            throw ValidationException::withMessages(["message-error" => "Failed Export: " . $th->getMessage()]);
        }
    }

    /**
     * Parse data to Array (version 1).
     *
     * @return array
     */
    private function parseQuerytoArrayVer1()
    {
        $data_query = $this->getBuilder()->select($this->request->columns)->get()->toArray();
        return $data_query;
    }

    /**
     * Parse data to Array (version 2).
     *
     * @return array
     */
    private function parseQuerytoArrayVer2()
    {
        $data_query = DB::table(function ($query) {
            $query->select(
                $this->request->columns,
                DB::raw("count(*) as total")
            )->from($this->getBuilder());
        }, 'query')->get()->toArray();
        return $data_query;
    }

    /**
     * Select columns for export.
     */
    private function selectColumns()
    {
        if ($this->request["select-column-export"] == 0) {
            $this->request->columns = ["*"];
        } else {
            $this->request->columns = $this->request->columns;
        }
    }
}
