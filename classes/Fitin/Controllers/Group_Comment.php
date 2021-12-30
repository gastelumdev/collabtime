<?php
namespace Fitin\Controllers;
use \Ninja\DatabaseTable;
use \Ninja\Authentication;

class Group_Comment {
    public function __construct(DatabaseTable $groupCommentsTable, Authentication $authentication) {
        $this->groupCommentsTable = $groupCommentsTable;
        $this->authentication = $authentication;
    }

    public function post() {
        $activeUser = $this->authentication->getUser();

        $groupComment = $_POST;
        $groupComment['date'] = date("Y-m-d h:i:s");
        $groupComment['userid'] = $activeUser['id'];

        $this->groupCommentsTable->save($groupComment);

        $groupComment['user_name'] = $activeUser['firstname'] . ' ' . $activeUser['lastname'];
        $groupComment['user_image'] = $activeUser['image'];

        $groupComments = $this->groupCommentsTable->findAll();
        $lastGroupComment = end($groupComments);

        $date = date_create($lastGroupComment['date']);

        $groupComment['month'] = date_format($date, 'F');
        $groupComment['day'] = date_format($date, 'd');
        $groupComment['hour'] = date_format($date, 'g');
        $groupComment['minutes'] = date_format($date, 'i');
        $groupComment['meridiem'] = date_format($date, 'A');

        $json = json_encode($groupComment);
        return $json;
    }
}