<?php

namespace Reivaj86\Multiapps\Traits;

use Reivaj86\Multiapps\Models\Permission;
use Reivaj86\Multiapps\Exceptions\RoleNotFoundException;
use Reivaj86\Multiapps\Exceptions\InvalidArgumentException;

trait IsApplUser
{
    /**
     * Property for caching appls.
     *
     * @var \Illuminate\Database\Eloquent\Collection|null
     */
    protected $appls;

    /**
     * User belongs to many appls.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appls()
    {
        return $this->belongsToMany('Reivaj86\Multiapps\Models\Appl')->withTimestamps();
    }

    /**
     * Get all appls as collection.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAppls()
    {
        return (!$this->appls) ? $this->appls = $this->appls()->get() : $this->appls;
    }

    /**
     * Check if the user uses an appl or appls.
     *
     * @param int|string|array $appl
     * @param string $methodName
     * @return bool
     * @throws \Reivaj86\Multiapps\Exceptions\InvalidArgumentException
     */
    public function uses($appl, $methodName = 'One')
    {
        if ($this->isPretendEnabled()) { return $this->pretend('uses'); }

        $this->checkMethodNameArgument($methodName);

        return $this->{'uses' . ucwords($methodName)}($this->getApplArrayFrom($appl));
    }

    /**
     * Check if the user uses at least one app.
     *
     * @param array $appls
     * @return bool
     */
    protected function usesOne(array $appls)
    {
        foreach ($appls as $appl) {
            if ($this->hasAppl($appl)) { return true; }
        }

        return false;
    }

    /**
     * Check if the user uses all appls.
     *
     * @param array $appls
     * @return bool
     */
    protected function usesAll(array $appls)
    {
        foreach ($appls as $appl) {
            if (!$this->hasAppl($appl)) { return false; }
        }

        return true;
    }

    /**
     * Check if the user has appl.
     *
     * @param int|string $appl
     * @return bool
     */
    protected function hasAppl($appl)
    {
        return $this->getAppls()->contains($appl) || $this->getAppls()->contains('slug', $appl);
    }

    /**
     * Attach appl to a user.
     *
     * @param int|\Reivaj86\Multiapps\Models\Appl $appl
     * @return null|bool
     */
    public function attachAppl($appl)
    {
        return (!$this->getAppls()->contains($appl)) ? $this->appls()->attach($appl) : true;
    }

    /**
     * Detach appl from a user.
     *
     * @param int|\Reivaj86\Multiapps\Models\Appl $appl
     * @return int
     */
    public function detachAppl($appl)
    {
        return $this->appls()->detach($appl);
    }

    /**
     * Detach all appls from a user.
     *
     * @return int
     */
    public function detachAllAppls()
    {
        return $this->appls()->detach();
    }
    /**
     * Get first appl level for a user.
     *
     * @return int
     * @throws \Reivaj86\Multiapps\Exceptions\RoleNotFoundException
     */
    public function applLevel($appl)
    {
        if ($appl_level = $this->getAppls()->contains($appl)->level) { return $appl_level; }

        throw new ApplNotFoundException('This user has no access to '.$appl);
    }
    /**
     * Get first appl level for a user.
     *
     * @return int
     * @throws \Reivaj86\Multiapps\Exceptions\RoleNotFoundException
     */
    public function ApplLevelMax()
    {
        if ($appl = $this->getAppls()->sortByDesc('level')->first()) { return $appl->level; }

        throw new RoleNotFoundException('This user has no appl.');
    }

    /**
     * Check if the user is allowed to manipulate an appl.
     *
     * @param string $providedAppl
     * @param object $entity
     * @param bool $owner
     * @param string $ownerColumn
     * @return bool
     */
    public function allowedAppl($providedAppl, $entity, $owner = true, $ownerColumn = 'user_id')
    {
        if ($this->isPurportEnabled()) { return $this->purport('allowedAppl'); }

        if ($owner === true && $entity->{$ownerColumn} == $this->id) { return true; }

        foreach ($this->getAppls() as $appl) {
            if ($appl->model != ''
                && get_class($entity) == $appl->model
                && ($appl->id == $providedAppl || $appl->slug === $providedAppl)
            ) { return true; }
        }

        return false;
    }
    /**
     * Check if purport option is enabled.
     *
     * @return bool
     */
    private function isPurportEnabled()
    {
        return (bool) config('multiapps.purport.enabled');
    }

    /**
     * Allows to purport or simulate package behavior.
     *
     * @param string $option
     * @return bool
     */
    private function purport($option = null)
    {
        return (bool) config('multiapps.purport.options.' . $option);
    }

    /**
     * Get an array from argument.
     *
     * @param int|string|array $argument
     * @return array
     */
    private function getApplArrayFrom($argument)
    {
        if (!is_array($argument)) { return preg_split('/ ?[,|] ?/', $argument); }

        return $argument;
    }

    /**
     * Check methodName argument.
     *
     * @param string $methodName
     * @return void
     * @throws \Bican\Roles\Exceptions\InvalidArgumentException
     */
    private function checkMethodNameArgument($methodName)
    {
        if (ucwords($methodName) != 'One' && ucwords($methodName) != 'All') {
            throw new InvalidArgumentException('You can pass only strings [one] or [all] as a second parameter in [is] or [can] method.');
        }
    }

    /**
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (starts_with($method, 'uses')) {
            return $this->is(snake_case(substr($method, 2), config('multiapps.separator')));
        } elseif (starts_with($method, 'allowed')) {
            return $this->allowed(snake_case(substr($method, 7), config('multiapps.separator')), $parameters[0], (isset($parameters[1])) ? $parameters[1] : true, (isset($parameters[2])) ? $parameters[2] : 'user_id');
        }

        return parent::__call($method, $parameters);
    }
}
