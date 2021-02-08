<?php
namespace Controllers;

class ChatThemeController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    public function getAllChatTheme($request, $response, $args) {
        $user_id = isset($_GET['user_id']) ? $_GET['user_id'] : 0;
        try{
            $sql = "SELECT id, name, IFNULL(exist, 0) exist 
                    FROM ChatTheme 
                    LEFT JOIN (SELECT DISTINCT theme_id, '1' AS exist 
                                FROM ChatManage 
                                WHERE user_id=:user_id
                                ) AS ChatManage 
                        ON ChatTheme.id = ChatManage.theme_id 
                    WHERE ChatTheme.edited = 0 
                    ORDER BY id ";
            
            $limit = isset($_GET['limit']) ? $_GET['limit'] : 0;
            $offset= isset($_GET['offset']) ? $_GET['offset'] : 0;
            if($limit>0){
                $sql .= "LIMIT $limit OFFSET $offset ";
            }
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("user_id", $user_id);
            $stmt->execute();
            $result = $stmt->fetchAll();
            return $response->withJson($result);
        }
        // any errors from the above database queries will be catched
        catch (PDOException $e){
            // roll back transaction
            //return $response->write($e);
            return $response->withStatus(500);
            // log any errors to file
            ExceptionErrorHandler($e);
            exit;
        }
    }

    //Get ChatTheme by ChatThemeId
    public function getChatThemeById($request, $response, $args) {
        $id = $args['id'];
        
        //get ChatTheme
        $sql = "SELECT id, name 
                FROM ChatTheme WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $result = $stmt->fetchObject();
        
        return $response->withJson($result);
    }

    // Add a new ChatTheme
    public function addNewChatTheme($request, $response) {
        $input = $request->getParsedBody();
        $name = $input['name'];
    
        $this->db->beginTransaction();
        try {
            //check exist
            $sql = "SELECT name 
                    FROM ChatTheme 
                    WHERE name=:name";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
            $stmt->execute();
            $result = $stmt->fetch();
            if($stmt->rowCount()>0){
                $input['id'] = -1;
                return $response->withJson($input);
            }

            //insert ChatTheme
            $sql = "INSERT INTO ChatTheme (name) 
                        VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
    
            $stmt->execute();
            $input['id'] = $this->db->lastInsertId();
    
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

    // Update ChatTheme with Id
    public function updateChatThemeWithId($request, $response, $args) {
        $input = $request->getParsedBody();
        $id = $args['id'];
        $name = $input['name'];
    
        $this->db->beginTransaction();
        try {
            // update edited
            $sql = "UPDATE ChatTheme SET edited=1 
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("id", $id);
            $stmt->execute();
            
            //insert ChatTheme
            $sql = "INSERT INTO ChatTheme (name) 
                        VALUES (:name)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("name", $name);
    
            $stmt->execute();
            $input['id'] = $this->db->lastInsertId();

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

    // delete a ChatTheme with Id
    public function deleteChatThemeWithId($request, $response, $args) {
        //get id その他
        $name = "その他";
        $sql = "SELECT id 
                FROM ChatTheme 
                WHERE name=:name";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $result = $stmt->fetchObject();
        if($stmt->rowCount()>0){
            $theme_id = $result->id;
            if($args['id'] == $theme_id){
                return $response->withStatus(500);
            }
        } else {
            return $response->withStatus(500);
        }

        $this->db->beginTransaction();
        try {
            // update theme_id
            $sql = "UPDATE ChatManage SET theme_id=:theme_id_new 
                    WHERE theme_id=:theme_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("theme_id_new", $theme_id);
            $stmt->bindParam("theme_id", $args['id']);
            $stmt->execute();

            // update theme_id
            $sql = "UPDATE ContactManage SET theme_id=:theme_id_new 
                    WHERE theme_id=:theme_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("theme_id_new", $theme_id);
            $stmt->bindParam("theme_id", $args['id']);
            $stmt->execute();

            //delete theme_id
            $stmt = $this->db->prepare("DELETE FROM ChatTheme WHERE id=:id");
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
}