<?php

namespace App\Models\Biome\Events\Core;

use App\Models\Biome\Database\HumanoidDb;
use App\Models\Biome\Events\EventInterface;
use App\Models\Biome\Events\HandlerInterface;
use App\Models\Biome\Events\Topics;
use ModelsAlpha\Attributes\PreventToArrayOnNull;
use ModelsAlpha\BaseModel;

class BirthEvent extends BaseModel implements EventInterface, HandlerInterface
{
    public const string EVENT_TYPE = 'BirthEvent';

    public string $name;
    public int $yearOfBorn;
    public bool $sex;

    public string $motherName;
    #[PreventToArrayOnNull]
    public ?string $fatherName = null;

    public function key(): string
    {
        return 'id:' . $this->motherName;
    }

    public function topic(): string
    {
        return Topics::TOPIC_CHILD_BIRTH;
    }

    public function type(): string
    {
        return self::EVENT_TYPE;
    }

    public static function handle(mixed $event): bool
    {
        /** @var $event \App\Models\Biome\Events\Core\BirthEvent */

        $model = new HumanoidDb();
        $model->born = $event->yearOfBorn;
        $model->child_at = 0;
        $model->sex = $event->sex;
        $model->name = $event->name;
        $model->mother_id = $event->motherName ? HumanoidDb::whereName($event->motherName)->select(['id'])->firstOrFail()->id : null;
        $model->father_id = $event->fatherName ? HumanoidDb::whereName($event->fatherName)->select(['id'])->firstOrFail()->id : null;
        return $model->save();
    }
}
