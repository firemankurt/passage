<?php namespace KurtJensen\Passage\Classes;

use Auth;
use KurtJensen\Passage\Models\Key;

/**
 * Blog Markdown tag processor.
 *
 * @package kurtjensen\passage
 * @author Kurt Jensen
 */
class Keys
{
    use \October\Rain\Support\Traits\Singleton;

    /**
     * @var array Cache of keys.
     */
    private $keys = [];

    public function can($lock)
    {
        return in_array($lock, $this->keys());
    }

    public function keys()
    {
        if (!count($this->keys)) {
            if (!Auth::getUser()) {
                return [1];
            }

            $this->keys = Key::whereHas('groups.users', function ($q) {
                     $q->where('user_id', Auth::getUser()->id);
                 })
                 ->lists('name', 'id');

        }
        return $this->keys;
    }
}
