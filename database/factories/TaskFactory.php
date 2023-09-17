<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Task;
use App\Models\Organization;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $users = collect(User::all()->modelKeys());
        $organizations = collect(Organization::all()->modelKeys());

        return [
            'title'           => $this->faker->sentence(),
            'description'     => $this->faker->paragraph(),
            'user_id'         => $users->random(),
            'organization_id' => $organizations->random(),
            'deadline'        => $this->faker->dateTimeBetween('+1 month', '+6 month'),
            'status'          => Arr::random(Task::STATUS),
        ];
    }
}
