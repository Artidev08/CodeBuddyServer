<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use App\Models\Project;
use App\Models\ChartDetail;
use Illuminate\Support\Collection;

class ProjectExport implements FromCollection, WithStyles, WithMapping
{
    protected $project;

    function __construct(Project $project) {
        $this->project = $project;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $data = new Collection();

        foreach ($this->project->charts as $chart) {
            // Add the chart name as a row with chart name in the first column
            $data->push([
                'chart_name' => $chart->name,
                'encounter' => route('secureEncounter', secureToken($chart->id)),
                'id' => '',
                'doctor_name' => '',
                'from_dos' => '',
                'to_dos' => '',
                'dx' => '',
                'icd10' => '',
                'nICD10' => '',
                'location' => '',
                'page_no' => '',
                'record_type' => '',
                'comment' => '',
                'created_at' => '',
                'updated_at' => '',
            ]);

            // Fetch the chart details
            $chartDetails = ChartDetail::where('chart_id', $chart->id)->get()->map(function ($row) {
                return [
                    'chart_name' => '', 
                    'encounter' => '', 
                    'id' => $row->getPrefix() ?? 'N/A',
                    'doctor_name' => $row->doctor_id ?? 'N/A',
                    'from_dos' => $row->from_dos ?? 'N/A',
                    'to_dos' => $row->to_dos ?? 'N/A',
                    'dx' => $row->dx ?? 'N/A',
                    'icd10' => $row->icd10 ?? 'N/A',
                    'nICD10' => $row->nICD10 ?? 'N/A',
                    'location' => $row->location ?? 'N/A',
                    'page_no' => $row->page_no ?? 'N/A',
                    'record_type' => $row->record_type ?? 'N/A',
                    'comment' => $row->comment ?? 'N/A',
                    // 'encounter' => route('secureEncounter', secureToken($row->id)),
                    'created_at' => $row->created_at ?? 'N/A',
                    'updated_at' => $row->updated_at ?? 'N/A',
                ];
            });

            if (!$chartDetails->isEmpty()) {
                // Add the chart details
                $data = $data->merge($chartDetails);
            }

            // Add an empty row after the chart details
            $data->push([
                'chart_name' => '',
                'encounter' => '',
                'id' => '',
                'doctor_name' => '',
                'from_dos' => '',
                'to_dos' => '',
                'dx' => '',
                'icd10' => '',
                'nICD10' => '',
                'location' => '',
                'page_no' => '',
                'record_type' => '',
                'comment' => '',
                'created_at' => '',
                'updated_at' => '',
            ]);
        }

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Style for headings row
        ];
    }

    public function map($row): array
    {
        return [
            $row['chart_name'] ?? '',
            $row['encounter'] ?? '',
            $row['id'] ?? '',
            $row['doctor_name'] ?? '',
            $row['from_dos'] ?? '',
            $row['to_dos'] ?? '',
            $row['dx'] ?? '',
            $row['icd10'] ?? '',
            $row['nICD10'] ?? '',
            $row['location'] ?? '',
            $row['page_no'] ?? '',
            $row['record_type'] ?? '',
            $row['comment'] ?? '',
            $row['created_at'] ?? '',
            $row['updated_at'] ?? '',
        ];
    }
}
