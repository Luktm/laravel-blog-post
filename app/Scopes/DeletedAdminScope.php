<?php
// create Scopes folder in app folder, it's a global query scope
namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class DeletedAdminScope implements Scope
{
    public function apply(Builder $builder, Model $model) {
        // if user was admin, it can see everything included softdeleted
        if(Auth::check() && Auth::user()->is_admin) {
            // $builder->withTrashed();
            // find in kernel SoftDeletingScope.php of namespace, a global query scope only admin able to see the with trashed(softdeleted) data
            $builder->withoutGlobalScope('Illuminate\Database\Eloquent\SoftDeletingScope');
        }
    }
}
