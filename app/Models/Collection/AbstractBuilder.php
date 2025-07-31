<?php

namespace App\Models\Collection;

use Illuminate\Database\Eloquent\Builder as BuilderModel;
use Illuminate\Database\Query\Builder as BuilderQuery;

class AbstractBuilder
{
    public static function whereBeginEndInRange(BuilderModel|BuilderQuery $query, int $fromYear, int $untilYear): BuilderModel|BuilderQuery
    {
        return $query->where(function (BuilderModel|BuilderQuery $orBuilder) use ($fromYear, $untilYear) {
            return $orBuilder->where(function (BuilderModel|BuilderQuery $builder) use ($fromYear, $untilYear) {
                return $builder->where('begin', '>=', $fromYear)->where('begin', '<=', $untilYear);

            })->orWhere(function (BuilderModel|BuilderQuery $builder) use ($fromYear, $untilYear) {
                return $builder->where('end', '>=', $fromYear)->where('end', '<=', $untilYear);

            })->orWhere(function (BuilderModel|BuilderQuery $builder) use ($fromYear, $untilYear) {
                return $builder->where('begin', '<', $fromYear)->where('end', '>', $untilYear);
            });
        });
    }

    public static function whereBeginMaybeInRange(BuilderModel|BuilderQuery $query, ?int $fromYear = null, ?int $untilYear = null): BuilderModel|BuilderQuery
    {
        if ($fromYear > 0) {
            $query->where('begin', '>=', $fromYear);
        }
        if ($untilYear > 0) {
            $query->where('begin', '<=', $untilYear);
        }
        return $query;
    }
}
