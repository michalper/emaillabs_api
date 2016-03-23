<?php

namespace EmailLabs;

class Response
{
    public
        $code,
        $data,
        $message,
        $status;

    public function __construct($response)
    {
        return $this->_prepareResponse(json_decode($response));
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    private function _prepareResponse($response)
    {
        if (isset($response->code)) {
            $this->code = $response->code;
        }

        if (isset($response->data)) {
            $this->data = $response->data;
        }

        if (isset($response->message)) {
            $this->message = $response->message;
        }

        if (isset($response->status)) {
            $this->status = $response->status;
        }

        return $this;
    }
}