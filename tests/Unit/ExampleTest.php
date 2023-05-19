<?php

namespace Tests\Unit;

use App\Http\Service\Workflow\ProjectService;
use Tests\TestCase;


class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_that_true_is_true()
    {
        $this->assertTrue(true);
    }

    public function testCreateProject()
    {
        $res = ProjectService::getWorkflowInstance()->createProject(['project_name' => '一个牛逼的项目名称']);

        var_dump($res);

        $this->assertTrue(true);

    }

}
