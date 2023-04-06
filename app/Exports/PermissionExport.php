<?php

namespace App\Exports;

use App\Models\Permission;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PermissionExport implements FromCollection, WithHeadings
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
        return  \App\Models\User::commonFunctionMethod(Permission::select('permissions.id', 
            'permissions.name', 
            'permissions.guard_name', 
            'permissions.label'),
            $this->request, true, null, null, true);
    }

    public function headings():array
    {
        return[
            'Id', 
            'Name', 
            'Guard name', 
            'Label'
        ];
    }
}
