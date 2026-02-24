<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait SupportsFullTextSearch
{
    public function scopeWhereFullTextOrLike(Builder $query, array $columns, string $keyword): Builder
    {
        if (in_array(DB::getDriverName(), ['mysql', 'mariadb', 'pgsql'])) {
            return $query->whereFullText($columns, $keyword, ['mode' => 'boolean']);
        }

        return $query->where(function (Builder $q) use ($columns, $keyword) {
            foreach ($columns as $i => $column) {
                $method = $i === 0 ? 'where' : 'orWhere';
                $q->{$method}($column, 'like', "%{$keyword}%");
            }
        });
    }
}
