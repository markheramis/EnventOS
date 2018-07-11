<?php

use Illuminate\Database\Seeder;
use App\Models\Customer;
class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            'first_name' => 'Kris',
            'last_name' => 'Kristy',
            'email' => 'kristykriss@nsa.gov',
            'phone' => '52352',
            'address' => '123 Address St.',
            'city' => 'The City',
            'state' => 'The State`',
            'zip' => '123',
            'country' => 'Some Country',
            'company_name' => 'The Company'
        ]);
        $this->create([
            'first_name' => 'Sean',
            'last_name' => 'Hanedy',
            'email' => 'sean@fox.com.us'
            'phone' => '52352',
            'address' => '123 Address St.',
            'city' => 'The City',
            'state' => 'The State`',
            'zip' => '123',
            'country' => 'Some Country',
            'company_name' => 'The Company'
        ]);
        $this->create([
            'first_name' => 'Roderick',
            'last_name' => 'Pulpatine',
            'email' => 'pulpatine@senetae.gov'
            'phone' => '52352',
            'address' => '123 Address St.',
            'city' => 'The City',
            'state' => 'The State`',
            'zip' => '123',
            'country' => 'Some Country',
            'company_name' => 'The Company'
        ]);
    }
    public function create($args)
    {
        $c = new Customer;
        $c->first_name = $args['first_name'];
        $c->last_name = $args['last_name'];
        $c->email = (array_key_exists ('email',$args)) ? $args['email'] : null;
        $c->phone = (array_key_exists ('phone',$args)) ? $args['phone'] : null;
        $c->address = (array_key_exists ('address',$args)) ? $args['address'] : null;
        $c->city = (array_key_exists ('city',$args)) ? $args['city'] : null;
        $c->state = (array_key_exists ('state',$args)) ? $args['state'] : null;
        $c->zip = (array_key_exists ('zip',$args)) ? $args['zip'] : null;
        $c->company_name = (array_key_exists ('company_name',$args)) ? $args['company_name'] : null;
        $c->save();
    }
}
