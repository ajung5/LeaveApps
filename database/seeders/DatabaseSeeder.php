<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use App\Models\LeaveType;
use App\Models\OrganizationalUnit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        OrganizationalUnit::firstOrCreate(['name'=>'Dinas Komunikasi dan Informatika']);
        foreach ([
            ['TAHUNAN','Cuti Tahunan','working',true,false],
            ['BESAR','Cuti Besar','calendar',false,false],
            ['SAKIT','Cuti Sakit','calendar',false,true],
            ['BERSALIN','Cuti Bersalin','calendar',false,true],
            ['PENTING','Cuti Karena Alasan Penting','calendar',false,false],
            ['CLTN','Cuti di Luar Tanggungan Negara','calendar',false,true],
        ] as [$code,$name,$mode,$balance,$attachment]) {
            LeaveType::firstOrCreate(['code'=>$code],['name'=>$name,'count_mode'=>$mode,'uses_balance'=>$balance,
                'requires_attachment'=>$attachment,'is_active'=>true]);
        }
        foreach ([
            'institution_name'=>'DINAS KOMUNIKASI DAN INFORMATIKA',
            'government_name'=>'PEMERINTAH DAERAH KABUPATEN SUBANG',
            'institution_address'=>'Jalan Mayjen Soetoyo Nomor 46, Subang, Jawa Barat 41211',
            'institution_phone'=>'Telepon (0260) 411318, Faksimile (0260) 411318',
            'institution_city'=>'Subang',
            'authority_name'=>'', 'authority_nip'=>'', 'authority_position'=>'KEPALA DINAS KOMUNIKASI DAN INFORMATIKA',
            'authority_rank'=>'', 'letter_prefix'=>'KP.06.03', 'letter_suffix'=>'Sekre',
        ] as $key=>$value) AppSetting::firstOrCreate(['key'=>$key],['value'=>$value]);
    }
}
