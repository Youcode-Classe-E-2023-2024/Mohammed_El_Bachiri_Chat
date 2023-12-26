<?php

class Room
{
    public $id;
    public $name;
    public $creator_id;

    static function creat($creator_id, $room_name)
    {
        global $db;

        $query = "INSERT INTO rooms (creator_id, room_name) VALUES (?, ?)";
        $stm = $db->prepare($query);
        $stm->bind_param('is', $creator_id, $room_name);

        try {
            $execution = $stm->execute();
            if (!$execution) {
                throw new Exception($stm->error);
            }
        }
        catch (Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    static function getUserRooms($user_id)
    {
        global $db;

        $query = "SELECT * FROM rooms WHERE creator_id = ?";
        $stm = $db->prepare($query);
        $stm->bind_param('i', $user_id);
        $execution = $stm->execute();

        if (!$execution) {
            throw new Exception($stm->error);
        }

        $result = $stm->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);    
    }


    static function addMember($user_id, $room_id)
    {
        global $db;

        $query = "insert into user_room (user_id, room_id) values (?, ?)";

        $stm = $db->prepare($query);
        $stm->bind_param('ii', $user_id, $room_id);

        try {
            $execution = $stm->execute();
            if (!$execution) {
                throw new Exception($stm->error);
            }
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return true;
    }

    /**
     * @throws Exception
     */
    static function getRoomMembers($room_id)
    {
        global $db;

        $query = "select users.username, users.image, users.user_id from user_room
         join users on users.user_id = user_room.user_id
         where room_id = ?";

        $stm = $db->prepare($query);
        $stm->bind_param('i', $room_id);
        $execution = $stm->execute();

        if (!$execution) {
            throw new Exception($stm->error);
        }

        $result = $stm->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}