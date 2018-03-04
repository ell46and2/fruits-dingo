<?php

namespace Tests\Feature;

use App\Fruit;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FruitsTest extends TestCase
{
	use DatabaseMigrations;

   	/** @test */
   	public function it_praises_the_fruits()
   	{
   		$this->get('/api')
   			->assertJson([
   				'Fruits' => 'Delicious and healthy!'
   			]);	
   	}

   	/** @test */
   	public function it_fetches_fruits()
   	{
   	    $this->seed('FruitsTableSeeder');

   	    $this->get('/api/fruits')
   	    	->assertJsonStructure([
   	    		'data' => [
   	    			'*' => [
   	    				'name', 'color', 'weight', 'delicious'
   	    			]
   	    		]
   	    	]);
   	}

   	/** @test */
   	public function it_fetches_a_single_fruit()
   	{
   	    $this->seed('FruitsTableSeeder');

   	    $this->get('api/fruit/1')
   	    	->assertJson([
   	    		'data' => [
   	    			'id' => 1,
   	    			'name' => 'Apple',
   	    			'color' => 'Green',
   	    			'weight' => '150 grams',
   	    			'delicious' => true
   	    		]
   	    	]);
   	}

   	/** @test */
   	public function it_authenticates_a_user()
   	{
   	    $user = factory(User::class)->create(['password' => bcrypt('foo')]);

   	    $this->post('/api/authenticate', [
   	    	'email' => $user->email,
   	    	'password' => 'foo'
   	    	])
   	    	->assertJsonStructure(['token']);	
   	}

   	/** @test */
   	public function it_saves_a_fruit()
   	{
   		$user = factory(User::class)->create(['password' => bcrypt('foo')]);

   		$fruit = ['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => true];

    	$this->post('/api/fruits', $fruit, $this->headers($user))
         ->assertStatus(201);
   	}

   	/** @test */
   	public function it_401s_when_not_authorized()
   	{
   		$fruit = Fruit::create([
   			'name' => 'peache',
   			'color' => 'peache',
   			'weight' => 175,
   			'delicious' => true
   		])->toArray();

   		$this->post('/api/fruits', $fruit)
   			->assertStatus(401);
   	}

   	/** @test */
   	public function it_422_when_validation_fails()
   	{
   	    $user = factory(User::class)->create(['password' => bcrypt('foo')]);

	    $fruit = ['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => true];

	    $this->post('/api/fruits', $fruit, $this->headers($user))
	         ->assertStatus(201);

	    // fruit name must be unique
	    $this->post('/api/fruits', $fruit, $this->headers($user))
	         ->assertStatus(422);
   	}

   	/** @test */
   	public function it_deletes_a_fruit()
   	{
   		$user = factory(User::class)->create(['password' => bcrypt('foo')]);

    	$fruit = Fruit::create(['name' => 'peache', 'color' => 'peache', 'weight' => 175, 'delicious' => true]);

    	$this->delete('/api/fruits/' . $fruit->id, [], $this->headers($user))
         ->assertStatus(204);
   	}
}
