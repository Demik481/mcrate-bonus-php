<?php

namespace mcrate\bonus;

class McRate
{
    const MCRATE_URL = "http://mcrate.su";

    const TYPE_GET = 'GET';
    const TYPE_POST = 'POST';

    private $_secret;
    private $_type;
    private $_input;

    private $_requestObject;

    /**
     * McRate constructor.
     * @param string $secret Secret word for verification
     * @param string $type Type of request. McRate::TYPE_GET or McRate::TYPE_POST
     * @param array $input Array of input data from GET or POST.
     * @throws \Exception
     */
    public function __construct($secret, $type = McRate::TYPE_GET, $input = [])
    {
        $this->_secret = $secret;
        $this->_type = $type;
        $this->_input = $input;

        $this->_requestObject = $this->convertToObject();
    }

    /**
     * @return GetVoteInfo|PostVoteInfo
     * @throws \Exception
     */
    private function convertToObject()
    {
        switch ($this->_type) {
            case 'GET':
                $obj = new GetVoteInfo();
                break;
            case 'POST':
                $obj = new PostVoteInfo();
                break;
            default:
                throw new \Exception("Invalid request type for MCRate request.", 400);
        }
        $obj->fill($this->_input);
        return $obj;
    }

    public function validate()
    {
        return $this->encodeHash() === $this->_input['hash'];
    }

    public function getData()
    {
        return $this->_requestObject;
    }

    private function encodeHash()
    {
        return md5(md5($this->_input['nick'] . $this->_secret . 'mcrate'));
    }
}
