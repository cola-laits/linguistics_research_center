<?php
 
class UserSeeder extends Seeder {
 
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Eloquent::unguard();
    	
    	DB::table('user')->delete();
    	
    	User::create(array(
                'username'   => 'babao',
                'email'      => 'babao@deathstar.com',
                'password'   => Hash::make('babao'),
                'first_name' => 'Baba',
                'last_name'  => 'ORiley',
                'created_by' => 'fmcgrath',
                'updated_by' => 'fmcgrath'
        		));
        
        User::create(array(
        		'username'   => 'bobtodd',
        		'email'      => 'bobtodd@math.utexas.edu',
        		'password'   => Hash::make('bobtodd'),
        		'first_name' => 'Todd',
        		'last_name'  => 'Krause',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'
				));
        
        User::create(array(
        		'username'   => 'fmcgrath',
        		'email'      => 'jfmcgrath@austin.utexas.edu',
        		'password'   => Hash::make('fmcgrath'),
        		'first_name' => 'Francis',
        		'last_name'  => 'McGrath',
        		'created_by' => 'fmcgrath',
        		'updated_by' => 'fmcgrath'
        		));        

    }
 
}