<?php

class Validator
{
    private $validator_error;

    public function get_error()
    {
        return $this->validator_error;
    }
    public function is_valid_request_body($request_body)
    {
        if (!is_array($request_body)) {
            $this->validator_error = [
                'code' => 422,
                'message' => 'le format de votre body n\'est pas autorisé',
                'error' => [
                    'code' => 422,
                    'message' => 'not a valid json abject'
                ]
            ];
            return false;
        } else return true;
    }
}
