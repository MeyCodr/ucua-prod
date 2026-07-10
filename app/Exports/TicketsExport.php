<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Table;
use PhpOffice\PhpSpreadsheet\Worksheet\Table\TableStyle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TicketsExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths, WithStyles, WithEvents
{
    protected Collection $tickets;

    protected int $rowNumber = 0;

    public function __construct(Collection $tickets)
    {
        $this->tickets = $tickets;
    }

    public function collection()
    {
        return $this->tickets;
    }

    public function headings(): array
    {
        return [
            'No',
            'Observation #',
            'Ticket ID',
            'Reported By',
            'Email',
            'Phone Number',
            'Staff ID',
            'Department',
            'Plant',
            'Status',
            'Submitted On',
        ];
    }

    /**
     * @param Ticket $ticket
     */
    public function map($ticket): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $ticket->id,
            $ticket->ticket_id,
            $ticket->name,
            $ticket->email,
            $ticket->phone_number,
            $ticket->staff_id,
            $ticket->department?->name ?? $ticket->department_other ?? 'N/A',
            $ticket->plant?->name ?? 'N/A',
            $ticket->status,
            optional($ticket->created_at)->format('d/m/Y H:i'),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,  // No
            'B' => 14, // Observation #
            'C' => 18, // Ticket ID
            'D' => 24, // Reported By
            'E' => 28, // Email
            'F' => 16, // Phone Number
            'G' => 12, // Staff ID
            'H' => 26, // Department
            'I' => 22, // Plant
            'J' => 12, // Status
            'K' => 18, // Submitted On
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:K1')->getFont()->setBold(true);
        $sheet->getStyle('A1:K1')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(1)->setRowHeight(22);

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastRow = $this->tickets->count() + 1;

                $tableStyle = new TableStyle(TableStyle::TABLE_STYLE_MEDIUM2);
                $tableStyle->setShowRowStripes(true);

                $table = new Table('A1:K' . $lastRow, 'TicketsTable');
                $table->setStyle($tableStyle);

                $sheet->addTable($table);
            },
        ];
    }
}
