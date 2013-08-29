<?php

class ApplicationBehavior extends CBehavior{

    private $_gameRequestData;

    private $_requestTime;

    public function events(){  
        return array_merge(parent::events(),array(  
            'onBeginRequest'    => 'beginRequest',
            'onEndRequest'      => 'endRequest',
            'onError'           => 'errorHappen',
        ));
    }

    public function beginRequest(){
    }

    public function endRequest($event)
    {
    }

    function errorHappen($error)
    {
    }
}