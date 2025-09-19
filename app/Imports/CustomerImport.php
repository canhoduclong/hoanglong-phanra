<?php
namespace App\Imports;

use App\Models\Customer;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class CustomerImport implements ToModel, WithHeadingRow, WithValidation
{

    public function model(array $row)
    {
        // Địa chỉ sẽ lưu vào note hoặc xử lý riêng nếu có bảng addresses
        $customer = new Customer([
            'name' => $row['name'] ?? null,
            'phone' => $row['phone'] ?? null,
            'email' => $row['email'] ?? null,
            'gender' => $row['gender'] ?? null,
            'dob' => $row['dob'] ?? null,
            'customer_type_id' => $row['customer_type_id'] ?? null,
            'note' => $row['note'] ?? null,
        ]);
        $customer->save();
        // Nếu có cột address, tạo CustomerAddress mặc định
        if (!empty($row['address'])) {
            \App\Models\CustomerAddress::create([
                'customer_id' => $customer->id,
                'note' => $row['address'],
                'is_default' => 1,
            ]);
        }
        return $customer;
    }

    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.phone' => 'required|string|max:30',
            '*.address' => 'required|string',
            '*.email' => 'nullable|email',
        ];
    }
}
