<?php

use Illuminate\Database\Seeder;
use App\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'email_verified' => '1'
        ]);
        for ($i = 1; $i < 30; $i++) {
            $this->create([
                "name" => "user$i",
                "email" => "user$i@example.com",
                "email_verified" => random_int(0, 1)
            ]);
        }
    }

    private function create($args)
    {
        $user = new User;
        $user->name = $args['name'];
        $user->email = $args['email'];
        $user->password = bcrypt('password');
        $user->email_verified = $args['email_verified'];
        $user->save();
    }

}
