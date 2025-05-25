<?php
// tests/Unit/TaskTest.php

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_belongs_to_assigned_user()
    {
        $user = User::factory()->create();
        $task = Task::factory()->create(['assigned_to' => $user->id]);

        $this->assertInstanceOf(User::class, $task->assignedUser);
        $this->assertEquals($user->id, $task->assignedUser->id);
    }

    public function test_today_scope_returns_todays_tasks()
    {
        Task::factory()->create(['end_date' => now()]);
        Task::factory()->create(['end_date' => now()->subDay()]);
        Task::factory()->create(['end_date' => now()->addDay()]);

        $todayTasks = Task::today()->get();

        $this->assertCount(1, $todayTasks);
    }

    public function test_completed_at_is_set_when_status_completed()
    {
        $task = Task::factory()->create(['status' => 'pending']);

        $task->update(['status' => 'completed']);

        $this->assertNotNull($task->fresh()->completed_at);
    }
}
