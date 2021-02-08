<?php
namespace Controllers;
use DateTime;
class FaqController
{
    private $db;

    // constructor receives container instance
    public function __construct($db) {
        $this->db = $db;
    }

    public function getFaqParent($request, $response, $args) {
        try{
            $sql = "SELECT faq_id, title 
                    FROM Faq 
                    WHERE parent_id =0 AND is_deleted = 0 
                    ORDER BY position ";
    
            $stmt = $this->db->prepare($sql);
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

    public function getAllFaqByParent($request, $response, $args) {
        $parent_id = $args['id'];
        try{
            $sql = "SELECT faq_id, title 
                    FROM Faq 
                    WHERE parent_id =:parent_id AND is_deleted = 0 
                    ORDER BY position ";
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("parent_id", $parent_id);
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

    public function getFaq($request, $response, $args) {
        $faq_id = $args['id'];
        try{
            $sql = "SELECT faq_id, parent_id, title, content 
                    FROM Faq 
                    WHERE faq_id =:faq_id";
    
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam("faq_id", $faq_id);
            $stmt->execute();
            $result = $stmt->fetchObject();
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
}