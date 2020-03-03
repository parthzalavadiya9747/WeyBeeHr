<?php 

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\ExcelExport;

class EmployeeExport implements FromCollection, WithHeadings{

	

	public function collection()
    {
    	
    	return ExcelExport::all();

    }



	public function headings(): array
    {
        return [
            'Employee Id',
            'Employee Name',
            'Date',
            'Check In',
            'Check Out'
        ];
    }





}

?>