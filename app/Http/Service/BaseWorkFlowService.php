<?php

namespace App\Http\Service;

use App\Http\Service\Workflow\ProjectService;
use App\Models\WorkflowMainSetting;
use App\Models\WorkflowNodeSetting;

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

        //说明提交审核
        if (!empty($arguments[0]['workflow'])) {
            $nextNodes = WorkflowNodeSetting::query()->where('workflow_id', $arguments[0]['workflow']['workflow_id'])
                ->where('node_task', $arguments[0]['workflow']['node_task'])
                ->get();
            //没有子节点
            if ($nextNodes->isEmpty()) {
                //实际执行
                return app($this->className)->$name(...$arguments);
            }
            $workFlowEngineClass = $this->getWorkFlowEngineClass();

            $nextNode = app($workFlowEngineClass)->getNextNode($nextNodes);

            if (empty($nextNode)) {
                return app($this->className)->$name(...$arguments);
            }

            $nodeTask = explode('@', $nextNode->node_task);

            $nodeClass = $nodeTask[0];
            $nodeMethod = $nodeTask[1];

            //node_id
            $arguments[0]['workflow']['node_id'] = $nextNode->id;

            return app($nodeClass)->$nodeMethod(...$arguments);

        }


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

        //是node节点


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
        $workFlowEngineClass = config('workflow.location', 'App\\Workflow\\') . class_basename($this->className) . 'EngineWorkflow';
        if (class_exists($workFlowEngineClass)) {
            return $workFlowEngineClass;
        }
        return config('workflow.location', 'App\\Workflow\\') . 'BaseEngineWorkflow';
    }

}
