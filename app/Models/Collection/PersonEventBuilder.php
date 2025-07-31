<?php

namespace App\Models\Collection;

use App\Models\Person\EventType;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

class PersonEventBuilder extends AbstractBuilder
{
    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function byPersonID(int $personID): Builder
    {
        return PersonEvent::where(fn (Builder $orBuilder) => $orBuilder
            ->where('person_id', $personID)
            ->orWhereIn('id', PersonEventConnect::wherePersonId($personID)->select('event_id')
        ));
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function honorRelationsSlaveBy(int $personID): Builder
    {
        return PersonEvent::where(PersonEvent::TABLE_NAME . '.person_id', $personID)
            ->orWhereIn(PersonEvent::TABLE_NAME . '.id', PersonEventConnect::wherePersonId($personID)->select('event_id'))
            ->join(EventType::TABLE_NAME, fn (JoinClause $onClosure) => $onClosure
                ->on(PersonEvent::TABLE_NAME . '.type_id', '=', EventType::TABLE_NAME . '.id')
                ->on(fn (JoinClause $orClosure) => $orClosure
                    ->on(EventType::TABLE_NAME . '.is_honor', '=', DB::raw(true))
                    ->orOn(EventType::TABLE_NAME . '.is_relation', '=', DB::raw(true))
                    ->orOn(EventType::TABLE_NAME . '.is_slave', '=', DB::raw(true))
            ))
            ->select(PersonEvent::dbColumns());
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function slaveByLifeId(int $lifeID): Builder
    {
        return static::singleQualityFilter('is_slave', $lifeID);
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function relationByLifeId(int $lifeID): Builder
    {
        return static::singleQualityFilter('is_relation', $lifeID);
    }

    /**
     * @param string $quality is_holy is_relation is_work is_slave
     * @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    protected static function singleQualityFilter(string $quality, int $lifeID): Builder
    {
        return PersonEvent::where(PersonEvent::TABLE_NAME . '.life_id', $lifeID)
            ->orWhereIn(PersonEvent::TABLE_NAME . '.id', PersonEventConnect::wherelifeId($lifeID)->select('event_id'))
            ->join(EventType::TABLE_NAME, fn (JoinClause $onClosure) => $onClosure
                ->on(PersonEvent::TABLE_NAME . '.type_id', '=', EventType::TABLE_NAME . '.id')
                ->on(EventType::TABLE_NAME . '.' . $quality, '=', DB::raw(true))
            )
            ->select(PersonEvent::dbColumns());
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function holyLivesBy(int $personID): Builder
    {
        return static::byPersonID($personID)->whereTypeId(EventType::HOLY_LIFE);
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function slaveLivesBy(int $personID): Builder
    {
        return static::byPersonID($personID)->whereIn('type_id', EventType::whereIsSlave(true)->select('id'));
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function byYearsAndPersonID(int $personID, int $fromYear, int $untilYear): Builder
    {
        return static::byPersonID($personID)
            ->where('begin', '>=', $fromYear)
            ->where('begin', '<=', $untilYear);
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function byLifeID(int $lifeID): Builder
    {
        return PersonEvent::where(fn (Builder $orBuilder) => $orBuilder
            ->where('life_id', $lifeID)
            ->orWhereIn('id', PersonEventConnect::whereLifeId($lifeID)->select('event_id'))
        );
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEventConnect */
    protected static function eventIdsOfDeepLoveVsConnectBy(int $personID): Builder
    {
        return PersonEventConnect::where(PersonEventConnect::TABLE_NAME . '.person_id', $personID)
            ->join(PersonEvent::TABLE_NAME, fn (JoinClause $onClosure) => $onClosure
                ->on(PersonEventConnect::TABLE_NAME . '.event_id', '=', PersonEvent::TABLE_NAME . '.id')
                ->on(PersonEvent::TABLE_NAME . '.type_id', '=', DB::raw(EventType::DEEP_LOVE))
            )
            ->select(PersonEventConnect::TABLE_NAME . '.event_id');
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function deepLoveBy(int $personID): Builder
    {
        return PersonEvent::where(fn (Builder $andBuilder) => $andBuilder->where('person_id', $personID)
                                                                         ->where('type_id', EventType::DEEP_LOVE))
            ->orWhereIn('id', static::eventIdsOfDeepLoveVsConnectBy($personID));
    }

    /** @return \Illuminate\Database\Eloquent\Builder|PersonEvent */
    public static function byYearsRange(int $fromYear, int $untilYear): Builder
    {
        return AbstractBuilder::whereBeginEndInRange(
            PersonEvent::query(),
            $fromYear,
            $untilYear
        );
    }
}
