<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
use App\Models\ChartDetail;
use App\Models\Chart;

class ChartDetailExport implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithEvents
{
    protected $chartId;
    protected $groupBy;

    function __construct($chartId,$groupBy) {
            $this->chartId = $chartId;
            $this->groupBy = $groupBy;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $chart = Chart::find($this->chartId);
        $groupBy = $this->groupBy;

        return ChartDetail::where(function ($query) use ($chart) {
            if (!empty($chart)) {
                $query->where('chart_id', $chart->id);
            }
        })
        ->when($groupBy, function ($query) use ($groupBy) {
            return $query->groupBy($groupBy);
        })
        ->orderBy('sequence')
        ->get()
        ->map(function ($row) {
            $hcc = $row->hcc ? $this->formatHcc($row->hcc) : 'N/A';
            $native_hcc = $row->native_hcc ? $this->formatHcc($row->native_hcc) : 'N/A';

            return [
                'id' => $row->getPrefix(),
                'from_dos' => $row->from_dos ?? 'N/A',
                'to_dos' => $row->to_dos ?? 'N/A',
                'dx' => $row->dx ?? 'N/A',
                'medication' => $row->medication ?? 'N/A',
                'encounter_no' => $row->sequence ?? 'N/A',
                'doctor_name' => $row->doctor_id ?? 'N/A',
                'native_dx' => $row->native_dx ?? 'N/A',
                'hcc' => $hcc,
                'native_hcc' => $native_hcc,
                'bmi' => $row->bmi ?? 'N/A',
                'location' => $row->location ?? 'N/A',
                'institution' => $row->institution ?? 'N/A',
                'record_type' => $row->record_type ?? 'N/A',
                'comment' => $row->comments ?? 'N/A',
                'created_at' => $row->created_at ?? 'N/A',
                'updated_at' => $row->updated_at ?? 'N/A',
            ];
        });
    }

    private function formatHcc($hcc)
    {
        $formatted = '';

        if (!empty($hcc->rx)) {
            $formatted .= 'RX: ' . $hcc->rx;
        }
        if (!empty($hcc->cms)) {
            $formatted .= (!empty($formatted) ? ' ' : '') . 'CMS: ' . $hcc->cms;
        }
        if (!empty($hcc->esrd)) {
            $formatted .= (!empty($formatted) ? ' ' : '') . 'ESRD: ' . $hcc->esrd;
        }

        return $formatted ?: 'N/A';
    }

    public function headings(): array
    {
        return [
            'ID',
            'From DOS',
            'To DOS',
            'Dx',
            'Dx Description',
            'Encounter No',
            'Doctor Name',
            'Native DX',
            'HCC',
            'Native HCC',
            'BMI',
            'Location',
            'Institution',
            'Record Type',
            'Comment',
            'Created At',
            'Updated At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => ['font' => ['bold' => true]],
            2    => ['font' => ['bold' => true]],
        ];
    }

    public function startCell(): string
    {
        return 'A2';
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $chart = Chart::find($this->chartId);
                $projectName = $chart->project ? $chart->project->name : 'N/A'; 
                $chartName = $chart ? $chart->name : 'Unknown Chart';

                $event->sheet->setCellValue('A1', "Project Name: $projectName, Chart Name: $chartName");
                $event->sheet->mergeCells('A1:J1');
            },
        ];
    }
}
