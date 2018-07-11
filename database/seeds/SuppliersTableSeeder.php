<?php

use Illuminate\Database\Seeder;

use App\Models\Supplier;
class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->create([
            'first_name' => 'David',
            'last_name' => 'Rosenstein',
            'email' => 'david_r@resensteintech.com',
            'phone' => '1231231231',
            'address' => 'Some address',
            'city' => 'Some City',
            'state' => 'Some State',
            'zip' => '12345',
            'country' => 'Some Country',
            'company_name' => 'Resentein Technologies'
        ]);
        $this->create([
            'first_name' => 'Jason',
            'last_name' => 'Mitnick',
            'email' => 'jmitnick@intel.co',
            'phone' => '12341313',
            'address' => 'Some Address',
            'city' => 'Some City',
            'state' => 'Some State',
            'zip' => '12345',
            'country' => 'Some Country',
            'company_name' => 'Intel Corporation'
        ]);
    }
    public function create($data)
    {
        $supplier = new Supplier;
        $supplier->first_name = $data['first_name'];
        $supplier->last_name = $data['last_name'];
        $supplier->email = $data['email'];
        $supplier->phone = $data['phone'];
        $supplier->address = $data['address'];
        $supplier->city = $data['city'];
        $supplier->state = $data['state'];
        $supplier->zip = $data['zip'];
        $supplier->company_name = $data['company_name'];

        return $supplier->save();
    }
}
