<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Category;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the category can view any models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the category can view the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Category  $model
     * @return mixed
     */
    public function view(User $user, Category $model)
    {
        return true;
    }

    /**
     * Determine whether the category can create models.
     *
     * @param  App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the category can update the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Category  $model
     * @return mixed
     */
    public function update(User $user, Category $model)
    {
        return true;
    }

    /**
     * Determine whether the category can delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Category  $model
     * @return mixed
     */
    public function delete(User $user, Category $model)
    {
        return true;
    }

    /**
     * Determine whether the user can delete multiple instances of the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Category  $model
     * @return mixed
     */
    public function deleteAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the category can restore the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Category  $model
     * @return mixed
     */
    public function restore(User $user, Category $model)
    {
        return false;
    }

    /**
     * Determine whether the category can permanently delete the model.
     *
     * @param  App\Models\User  $user
     * @param  App\Models\Category  $model
     * @return mixed
     */
    public function forceDelete(User $user, Category $model)
    {
        return false;
    }
}
