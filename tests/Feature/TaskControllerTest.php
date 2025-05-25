<?php
// tests/Feature/TaskControllerTest.php

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $intern;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->intern = User::factory()->create(['role' => 'intern']);
    }

    public function test_intern_can_view_own_tasks()
    {
        $task = Task::factory()->create(['assigned_to' => $this->intern->id]);

        $response = $this->actingAs($this->intern)
            ->get(route('tasks.index'));

        $response->assertStatus(200)
            ->assertSee($task->title);
    }

    public function test_intern_can_update_task_status()
    {
        $task = Task::factory()->create([
            'assigned_to' => $this->intern->id,
            'status' => 'pending'
        ]);

        $response = $this->actingAs($this->intern)
            ->putJson(route('tasks.update.status', $task), [
                'status' => 'completed'
            ]);

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'status' => 'completed'
        ]);
    }

    public function test_intern_cannot_update_others_tasks()
    {
        $otherIntern = User::factory()->create(['role' => 'intern']);
        $task = Task::factory()->create(['assigned_to' => $otherIntern->id]);

        $response = $this->actingAs($this->intern)
            ->putJson(route('tasks.update.status', $task), [
                'status' => 'completed'
            ]);

        $response->assertStatus(403);
    }
}
