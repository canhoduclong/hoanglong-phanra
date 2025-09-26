<?php
namespace App\Exports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CustomerExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Customer::select('id','name','phone','email','gender','dob','customer_type_id','note')->get();
    }
    public function headings(): array
    {
        return ['id','name','phone','email','gender','dob','customer_type_id','note'];
    }
}
