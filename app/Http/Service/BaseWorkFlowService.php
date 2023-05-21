<?php

namespace App\Http\Service;

use App\Http\Service\Workflow\ProjectService;
use App\Models\WorkflowMainSetting;

class BaseWorkFlowService
{

    public static function getWorkflowInstance(): BaseWorkFlowService|ProjectService
    {
        return new BaseWorkFlowService(get_called_class());
    }

    public function __construct(private $className = null)
    {

    }

    public function __call(string $name, array $arguments)
    {

        $taskName = $this->className . '@' . $name;

        $workflow = $this->getWorkflow($taskName);


        //是触发器
        if (!empty($workflow)) {

            //有触发条件判断
            if (!empty($workflow['workflow_start_condition'])) {
                //获取判断class
                $workFlowEngineClass = $this->getWorkFlowEngineClass();

                $startCondition = app($workFlowEngineClass)->workflowStartCondition($workflow);

                //满足触发条件
                if ($startCondition === true) {
                    return [
                        'msg' => '需要系统审批',
                        'workflow' => $workflow->toArray(),
                    ];
                }
            } else {
                //没有触发条件
                return [
                    'msg' => '需要系统审批',
                    'workflow' => $workflow->toArray(),
                ];
            }

        }


        //实际执行
        return app($this->className)->$name(...$arguments);

    }

    private function getWorkflow($taskName): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|null
    {
        return WorkflowMainSetting::query()->where('trigger_task', $taskName)
            ->first();
    }


    private function getWorkFlowEngineClass(): string
    {
        $workFlowEngineClass = config('workflow.location', 'app/Workflow/') . class_basename($this->className) . 'EngineWorkflow';
        if (class_exists($workFlowEngineClass)) {
            return $workFlowEngineClass;
        }
        return config('workflow.location', 'app/Workflow/') . 'BaseEngineWorkflow';
    }

}
