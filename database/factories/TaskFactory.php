<?php
// database/factories/TaskFactory.php

class TaskFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'end_date' => $this->faker->dateTimeBetween('today', '+1 month'),
            'priority' => $this->faker->randomElement(['low', 'medium', 'high']),
            'status' => $this->faker->randomElement(['pending', 'in_progress', 'completed']),
            'assigned_to' => User::factory(),
            'created_by' => User::factory(),
        ];
    }

    public function completed()
    {
        return $this->state([
            'status' => 'completed',
            'completed_at' => $this->faker->dateTimeBetween('-1 week', 'now')
        ]);
    }

    public function highPriority()
    {
        return $this->state([
            'priority' => 'high'
        ]);
    }

    public function dueToday()
    {
        return $this->state([
            'end_date' => now()->format('Y-m-d H:i:s')
        ]);
    }
}
