<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class ,function (Faker $faker ) {

        $position_type =
            $faker->randomElement($array = array(
//            App\CEO::class,
//            App\Director::class,
//            App\Manager::class,
//            App\Team_Lead::class,
            App\Staff::class
        ));

        $position_obj = factory($position_type)->create();

        return [
            'name'              => $faker->name,
            'email'             => $faker->word . $faker->unique()->safeEmail ,
            'password'          => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'remember_token'    => str_random(10),
            'userable_id'       => $position_obj->id,
            'userable_type'     => $position_type,
            'position'          => $position_obj->userPosition(),
            'salary_size'       => $position_obj->salary_size,
            'recruitment_date'  => $position_obj->recruitment_date,
            'ruler_name'        => $position_obj->ruler_name
        ];

});

$factory->define(App\CEO::class, function (Faker $faker) {
    return [
        'recruitment_date'  => $faker->dateTime(),
        'salary_size'       => $faker->randomNumber(),
        'position'          => App\CEO::TYPE
    ];
});

$factory->define(App\Director::class, function (Faker $faker) {

    $ceo_id = $faker->numberBetween(1 , App\CEO::count() );
    $ceo = \App\CEO::where( 'id', $ceo_id )->with('user')->get();

    return [
        'recruitment_date'  => $faker->dateTime(),
        'salary_size'       => $faker->randomNumber(),
        'position'          => App\Director::TYPE,
        'c_e_o_id'          => $ceo_id,
        'ruler_name'        => $ceo[0]['user']['name']
    ];
});

$factory->define(App\Manager::class, function (Faker $faker) {

    $director_id = $faker->numberBetween(1,App\Director::count());
    $director = \App\Director::where( 'id', $director_id )->with('user')->get();

    return [
        'recruitment_date'  => $faker->dateTime(),
        'salary_size'       => $faker->randomNumber(),
        'position'          => App\Manager::TYPE,
        'director_id'       => $director_id,
        'ruler_name'       => $director[0]['user']['name'],
    ];
});

$factory->define(App\Team_Lead::class, function (Faker $faker) {

    $manager_id = $faker->numberBetween(1, App\Manager::count() );
    $manager = \App\Manager::where( 'id', $manager_id )->with('user')->get();

    return [
        'recruitment_date'  => $faker->dateTime(),
        'salary_size'       => $faker->randomNumber(),
        'position'          => App\Team_Lead::TYPE,
        'manager_id'        => $manager_id,
        'ruler_name'      => $manager[0]['user']['name']
    ];
});

$factory->define(App\Staff::class, function (Faker $faker) {

    $team_lead_id = $faker->numberBetween(1, App\Team_Lead::count() );
    $team_lead = \App\Team_Lead::where( 'id', $team_lead_id )->with('user')->get();

    return [
        'recruitment_date'  => $faker->dateTime(),
        'salary_size'       => $faker->randomNumber(),
        'position'          => App\Staff::TYPE,
        'team__lead_id'     => $team_lead_id,
        'ruler_name'   => $team_lead[0]['user']['name']
    ];
});

