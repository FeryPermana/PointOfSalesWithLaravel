<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('setting')->insert([
            'id_setting'      => 1,
            'nama_perusahaan' => 'Bintang Permai',
            'alamat'          => 'Kepuh Permai D31, Wedomartani, Ngemplak, Sleman',
            'telepon'         => '08983674571',
            'tipe_nota'       => 2,
            'diskon'          => 5,
            'path_logo'       => '',
            'path_kartu_member' => ''
        ]);
    }
}
