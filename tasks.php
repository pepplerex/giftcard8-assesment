<?php

require_once 'controller/TaskController.php';

// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');
$taskManager = new TaskManager();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // get tasks
        echo json_encode($taskManager->getTasks());
        break;
    case 'POST':
        // add task
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($taskManager->addTask($data->name, $data->description));
        break;
    case 'PUT':
        // update task
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($taskManager->updateTask($data->id, $data->name, $data->description));
        break;
    case 'DELETE':
        // delete task
        $data = json_decode(file_get_contents("php://input"));
        echo json_encode($taskManager->deleteTask($data->id));
        break;
    default:
        // method not allowed
        echo json_encode(["message" => "Method not allowed."]);
        break;
}
