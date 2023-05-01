<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return  \App\Models\User::commonFunctionMethod(User::select('users.id', 'users.name', 'users.company_name', 'users.email', 'users.contact_number',
            \Illuminate\Support\Facades\DB::raw('(SELECT name from roles WHERE id = users.role_id) AS role_name')),
            $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return[
            'Id',
            'name',
            'company_name',
            'email',
            'contact_number',
            'Role name'
        ];
    }
}
