<?php

namespace App\LeadImport;

use App\Models\Lead;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class LeadImport implements ToModel, WithHeadingRow, WithValidation
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */

    use Importable;

    public function model(array $row)
    {
        return new Lead([
            'fullname' => $row['fullname'],
            'emailaddress' => $row['emailaddress'],
            'phonenumber' => $row['phonenumber'],
            'stage' => $row['stage'],
            'status' => $row['status'],
            'message' => $row['message']
        ]);
    }

    public function rules(): array
    {
        return [
            //If RULE returns TRUE then row is inserted into the database.
            // ============================================================
            // Note: Available Validation Rules
            // https://laravel.com/docs/8.x/validation#available-validation-rules


            'fullname' => 'required',
            'emailaddress' => 'required|regex:/[a-z0-9]*@[a-z0-9]*.[a-z0-9]{2,3}/',
            //'phonenumber' => 'required|numeric',
            'stage' => 'required|in:Marketing Acquired Lead,Marketing Qualified Lead',
            'status' => 'required|in:Prospect,Open',
            'message' => 'required',
            'phonenumber'  => 'required|regex:/[63]{1}\d{10}/|min:12|max:12',
            
            
            // Note: Available Validation Rules
            // https://laravel.com/api/8.x/Illuminate/Validation/Rule.html
            //'emailaddress' => Rule::exists('leads', 'emailaddress'),
            //'stage' => Rule::unique('leads','stage')


        ];
    }

    // public function customValidationMessages()
    // {
    //     return [
    //         'emailaddress.exists' => 'Custom message for :attribute.',
    //     ];
    // }

    // public function customValidationAttributes()
    // {
    //     return ['emailaddress' => 'emailaddressUpdated'];
    // }
}
