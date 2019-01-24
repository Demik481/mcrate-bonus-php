<?php

namespace mcrate\bonus;

abstract class VoteInfoInterface
{
    /** @var integer Unique ID of vote on MCRate */
    public $id;

    /** @var string Nickname of voter */
    public $nick;

    /** @var integer Timestamp of vote */
    public $timestamp;

    /** @var string Voter IP */
    public $ip;

    /** @var string Verification hash */
    public $hash;

    protected function fill($array)
    {
        if (!in_array('id', $array))
            throw new \InvalidArgumentException("ID not found in request");
        $this->id = $array['id'];

        if (!in_array('nick', $array))
            throw new \InvalidArgumentException("Nick not found in request");
        $this->nick = $array['nick'];

        if (!in_array('timestamp', $array))
            throw new \InvalidArgumentException("Timestamp not found in request");
        $this->timestamp = $array['timestamp'];

        if (!in_array('ip', $array))
            throw new \InvalidArgumentException("IP not found in request");
        $this->ip = $array['ip'];

        if (!in_array('hash', $array))
            throw new \InvalidArgumentException("HASH not found in request");
        $this->hash = $array['hash'];
    }
}
