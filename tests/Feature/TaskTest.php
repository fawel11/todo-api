<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected $title = "Lorem ipsum dolor sit amet";
    protected $description = "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.";
    private $task;


    protected function authenticate()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@gmail.com',
            'password' => bcrypt('secret123$'),
        ]);

        $this->user = $user;

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@gmail.com',
            'password' => 'secret123$',
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure(['accessToken']);
        return $response->json('accessToken');
    }


    /**
     * A protected method to create a todo
     *
     * @return response
     */

    protected function createTask()
    {
        $task = Task::create([
            'title' => $this->title,
            'description' => $this->description
        ]);

        $this->user->tasks()->save($task);
        return $task;

    }

    public function testStore()
    {
        //Get token
        $token = $this->authenticate();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('POST', route('api.task.store'), [
            'title' => $this->title,
            'description' => $this->description
        ]);

        $response->assertStatus(201);

        $count = $this->user->tasks()->count();
        $this->assertEquals(1, $count);
    }


    public function testAll()
    {

        $token = $this->authenticate();
        $this->createTask();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->json('GET', route('api.task.index'));
        $response->assertStatus(200);

        $this->assertEquals(1, count($response->json()));
        $this->assertEquals($this->title, $response->json()['data'][0]['title']);
    }

    public function testUpdate()
    {
        $token = $this->authenticate();
        $task = $this->createTask();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->json('POST', route('api.task.update', ['id' => $task->id]), [
            'title' => 'This is an Updated title'
        ]);
        $response->assertStatus(201);
        //Assert title is the new title
        $this->assertEquals('This is an Updated title', $this->user->tasks()->first()->title);
    }

    public function testShow()
    {
        $token = $this->authenticate();
        $this->task = $this->createTask();
        $task = $this->task;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->json('GET', route('api.task.view', [
                'id' => $task->id
            ])
        );

        $response->assertStatus(200);

        //Assert title is correct
        $this->assertEquals($this->title, $response->json()['data']['title']);
    }

    public function testDelete()
    {
        $token = $this->authenticate();
        $task = $this->createTask();
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->json('DELETE', route('api.task.destroy', [
                'id' => $task->id
            ])
        );
        $response->assertStatus(200);
        //Assert there is no todo
        $this->assertEquals(0, $this->user->tasks()->count());
    }


}
