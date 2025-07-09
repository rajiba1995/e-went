<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserJourneyExport implements FromArray, WithHeadings
{
    protected $journey;

    public function __construct(array $journey)
    {
        $this->journey = $journey;
    }

    public function array(): array
    {
        return array_map(function ($item) {
            return [
                $item['title'],
                strip_tags($item['description']),
                \Carbon\Carbon::parse($item['date'])->format('d M Y h:i A'),
            ];
        }, $this->journey);
    }

    public function headings(): array
    {
        return ['Title', 'Description', 'Date & Time'];
    }
}

