<?php
// create Scopes folder in app folder, it's a global query scope
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class LatestScope implements Scope{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
        // created_at existing column in blogpost and comment table timestamp
        // apply static::addGlobalScope() find in BlogPost.php line 39
        // it will add this part of the query to all the queries related with that model in explicitly
        // $builder->orderBy('created_at', 'desc');

        // column might be overwrite by other name,
        // we use model constant instead
        $builder->orderBy($model::CREATED_AT, 'desc');
    }

}
