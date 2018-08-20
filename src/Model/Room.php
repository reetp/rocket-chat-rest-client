<?php

namespace RocketChat\Model;

use Httpful\Request;
use RocketChat\Model\Base as BaseModel;

class Room extends BaseModel {

    public $id;
    public $name;
    public $members = [];

    public function __construct($data = [])
    {
        if( is_string($data) ) {
            $data = (object)['name' => $data];
        }
        $this->setData($data);
    }

    public function isMemberLoadRequired() {
        return false;
    }

    public function setRemoteData($data) {
        parent::setRemoteData($data);

        $this->name = $this->remoteData->name;
        $this->id = $this->remoteData->_id;


        //load members if not present in remote data, or search for members in a list of all users
        if($this->isMemberLoadRequired() || !isset($this->remoteData->usernames)) {
            $this->loadMembers();
        } elseif (!empty($this->remoteData->usernames)) {
            $users = $this->getClient()->getAllUsers();
            $this->members = array_intersect_key($users, array_combine($this->remoteData->usernames, $this->remoteData->usernames));
        }
    }
}

