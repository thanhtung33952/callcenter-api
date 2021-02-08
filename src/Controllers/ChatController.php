<?php
namespace Controllers;
use DateTime;
class ChatController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    //Get Chat by ChatId
    public function getChatByChatId($request, $response, $args) {
        $chat_id = $args['id'];
        $sql = "SELECT Chat.id, chat_id, 
                        CASE writer_id WHEN '1' THEN chat_name ELSE staff_name END AS write_name, 
                        CASE writer_id WHEN '1' THEN staff_name ELSE chat_name END AS receiver_name,
                        writer_id, time, text, type 
                FROM Chat 
                INNER JOIN (SELECT id, user_id, staff_id FROM ChatManage WHERE id = :chat_id) AS ChatManage 
                  ON Chat.chat_id = ChatManage.id 
                LEFT JOIN (SELECT id, family_name as chat_name FROM ChatUser) AS ChatUser 
                  ON ChatManage.user_id = ChatUser.id 
                LEFT JOIN (SELECT id, family_name as staff_name FROM CentersStaff) AS CentersStaff 
                  ON ChatManage.staff_id = CentersStaff.id 
                ORDER BY Chat.id ";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("chat_id", $chat_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }

    //Get Chat by ChatId
    public function getChatByFaqIdAndUserId($request, $response, $args) {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
        $faq1_id = isset($_GET['faq1_id']) ? $_GET['faq1_id'] : 0;
        $faq2_id = isset($_GET['faq2_id']) ? $_GET['faq2_id'] : 0;

        $sql = "SELECT Chat.id, chat_id, 
                        CASE writer_id WHEN '1' THEN chat_name ELSE staff_name END AS write_name, 
                        CASE writer_id WHEN '1' THEN staff_name ELSE chat_name END AS receiver_name,
                        writer_id, time, text, type 
                FROM Chat 
                INNER JOIN (SELECT id, user_id, staff_id 
                            FROM ChatManage 
                            WHERE user_id=:user_id AND faq1_id=:faq1_id AND faq2_id=:faq2_id 
                            ) AS ChatManage 
                  ON Chat.chat_id = ChatManage.id 
                LEFT JOIN (SELECT id, family_name as chat_name FROM ChatUser) AS ChatUser 
                  ON ChatManage.user_id = ChatUser.id 
                LEFT JOIN (SELECT id, family_name as staff_name FROM CentersStaff) AS CentersStaff 
                  ON ChatManage.staff_id = CentersStaff.id 
                ORDER BY Chat.id ";

        $limit = isset($_GET['limit']) ? $_GET['limit'] : 0;
        $offset= isset($_GET['offset']) ? $_GET['offset'] : 0;
        if($limit>0){
            $sql .= "LIMIT $limit OFFSET $offset ";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("user_id", $user_id);
        $stmt->bindParam("faq1_id", $faq1_id);
        $stmt->bindParam("faq2_id", $faq2_id);
        $stmt->execute();
        $result = $stmt->fetchAll();
        
        return $response->withJson($result);
    }

    // Add a new Chat
    public function addNewChat($request, $response) {
        $input = $request->getParsedBody();
        $chat_id = $input['chat_id'];
        $writer_id = $input['writer_id'];// 1: user chat, 0: staff
        $text = $input['text'];
        $type = isset($input['type']) ? $input['type'] : 0;
        $not_seen = 1;
        if($writer_id == '0'){
            $not_seen = 0;
        }
        
        $this->db->beginTransaction();
        try {
            $sql = "INSERT INTO Chat (chat_id, writer_id, text, type, not_seen) 
                        VALUES (:chat_id, :writer_id, :text, :type, :not_seen)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("chat_id", $chat_id);
            $stmt->bindParam("writer_id", $writer_id);
            $stmt->bindParam("text", $text);
            $stmt->bindParam("type", $type);
            $stmt->bindParam("not_seen", $not_seen);
    
            $stmt->execute();
            $input['id'] = $this->db->lastInsertId();

            $sql = "SELECT time 
                    FROM Chat 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $input['id']);
            $stmt->execute();
            $input['time'] = $stmt->fetchColumn();
            
            $this->db->commit();

            return $response->withJson($input);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // Update chat with ChatId
    public function updateChatWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $args['id'];
        $text = $input['text'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE Chat SET text=:text  
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("text", $text);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            
            $this->db->commit();
            return $response->withJson($input);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // delete a Chat with ChatId
    public function deleteChatWithId($request, $response, $args) {
        $this->db->beginTransaction();
        try {
            $stmt = $this->db->prepare("DELETE FROM Chat WHERE id=:id");
            $stmt->bindParam("id", $args['id']);
            $result = $stmt->execute();
            
            $this->db->commit();
            return $response->withStatus(200);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    // Update not seen chat with ChatId
    public function updateSeenChatWithChatId($request, $response, $args) {
        $chat_id = $args['id'];
    
        $this->db->beginTransaction();
        try {
            $sql = "UPDATE Chat SET not_seen=0 
                    WHERE chat_id = :chat_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("chat_id", $chat_id);
            $stmt->execute();
            
            $this->db->commit();
            return $response->withStatus(200);
        }               
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            $this->db->rollback();
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    function generateId(){
        $date = new DateTime();
        $result = $date->format('Y-m-d H:i:s');
        $str = $this->generateRandomString().$result;
        return MD5($str);
    }
    
    function generateRandomString($length = 6) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}