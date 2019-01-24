<?php

namespace mcrate\bonus;

class PostVoteInfo extends VoteInfoInterface
{
    /** @var string Voter browser information */
    public $browser;

    protected function fill($array)
    {
        parent::fill($array);

        if (!in_array('browser', $array))
            throw new \InvalidArgumentException("Browser not found in request");
        $this->browser = $array['browser'];
    }
}
