<?php

use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin=App\Admin::create([
            'name'=>'Hatem Mohammed',
            'email'=>'super_admin@app.com',
//            'status'=>true,
            'password'=>bcrypt(12345),
            'gender'=>'male',
            'role'=>'admin',
            'phone'=>'01090703457',
            'avatar'=>'admin.png'
        ]);
        auth('webadmin')->loginUsingId($admin->id);
    }
}
