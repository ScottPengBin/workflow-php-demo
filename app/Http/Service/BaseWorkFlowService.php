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

        $trigger = $this->TriggerTask($taskName);

        if (!empty($trigger)) {
            return '需要系统审批';
        }

        return app($this->className)->$name(...$arguments);

    }

    public function TriggerTask($taskName): \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder|null
    {
        return WorkflowMainSetting::query()->where('trigger_task', $taskName)
            ->first();
    }
}
