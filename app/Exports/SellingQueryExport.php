<?php
// namespace App\Exports;

// use Maatwebsite\Excel\Concerns\FromArray;
// use Maatwebsite\Excel\Concerns\WithHeadings;

// class SellingQueryExport implements FromArray, WithHeadings
// {
//     protected $query;

//     public function __construct(array $query)
//     {
//         $this->query = $query;
//     }

//     public function array(): array
//     {
//         return array_map(function ($item) {
//             return [
//                 $item['user']['name'],
//                 $item['phone'],
//                 $item['user']['email'],
//                 $item['address'],
//                 $item['product']['title'],
//                 strip_tags(string: $item['remarks']),
//                 \Carbon\Carbon::parse($item['created_at'])->format('d M Y h:i A'),
//             ];
//         }, $this->query);
//     }

//     public function headings(): array
//     {
//         return ['Name', 'Phone No', 'Email','Address','Model Name','Remarks','Request Date'];
//     }
// }


namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\SellingQuery;

class SellingQueryExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct()
    {

    }
    public function collection()
    {
        $data = SellingQuery::with([
          'user:id,name,country_code,mobile,email,address,city,pincode,profile_image',
            'product:id,title',
      ])
            ->orderBy('id', 'DESC')
            ->get()->map(function ($query) {
              $query->phone=$query->user->country_code.$query->user->mobile;
              $query->address=$query->user->address;
              if(!empty($query->user->city))
              {
                $query->address.=$query->user->city;

              }
              if(!empty($query->user->pincode))
              {
                $query->address.=$query->user->pincode;

              }

            return [
                'name' => $query->user->name,
                'phone' => $query->phone,
                'email' => $query->user->email,
                'address' => $query->address,
                'model' => $query->product->title,
                'remarks' => $query->remarks,
                'created_at' => date('d-m-Y h:i A',strtotime($query->created_at)),
            ];
        })
        ->toArray();



    }

    public function headings(): array
    {
      return ['Name', 'Phone No', 'Email','Address','Model Name','Remarks','Request Date'];

    }
}


