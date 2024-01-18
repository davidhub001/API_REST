<?php

$file_path = 'contacts.txt';

function readContacts() {
    global $file_path;
    $contacts = file_get_contents($file_path);
    return json_decode($contacts, true);
}

function writeContacts($contacts) {
    global $file_path;
    file_put_contents(__DIR__."/".$file_path, json_encode($contacts, JSON_PRETTY_PRINT));
}

// Vérifie la méthode de la requête HTTP
$request_method = $_SERVER['REQUEST_METHOD'];
// Traitement de la requête en fonction de la méthode
switch ($request_method) {
    case 'GET':
        // Récupérer la liste des contacts
        $contacts = readContacts();
        header('Content-Type: application/json');
        echo json_encode($contacts);
        break;

    case 'POST':
        // Ajouter un nouveau contact
        $postData = json_decode(file_get_contents("php://input"), true);
        
        if ($postData && isset($postData['name']) && isset($postData['phone'])) {
            $contacts = readContacts();
            $newContact = [
                'name' => $postData['name'],
                'phone' => $postData['phone'],
            ];
            $contacts[] = $newContact;
            writeContacts($contacts);
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Contact ajouté avec succès']);
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requête incorrecte']);
        }
        break;

    case 'PUT':
        // Mettre à jour un contact
        parse_str(file_get_contents("php://input"), $putData);

        if (isset($putData['id']) && isset($putData['name']) && isset($putData['phone'])) {
            $contacts = readContacts();
            $contactId = $putData['id'];

            if (isset($contacts[$contactId])) {
                $contacts[$contactId]['name'] = $putData['name'];
                $contacts[$contactId]['phone'] = $putData['phone'];
                writeContacts($contacts);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Contact mis à jour avec succès']);
            } else {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Contact non trouvé']);
            }
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requête incorrecte']);
        }
        break;

    case 'DELETE':
        // Supprimer un contact
        parse_str(file_get_contents("php://input"), $deleteData);

        if (isset($deleteData['id'])) {
            $contacts = readContacts();
            $contactId = $deleteData['id'];

            if (isset($contacts[$contactId])) {
                unset($contacts[$contactId]);
                writeContacts($contacts);
                header('Content-Type: application/json');
                echo json_encode(['success' => true, 'message' => 'Contact supprimé avec succès']);
            } else {
                header('Content-Type: application/json');
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Contact non trouvé']);
            }
        } else {
            header('Content-Type: application/json');
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Requête incorrecte']);
        }
        break;

    default:
        // Requête incorrecte, renvoie une réponse JSON avec un statut d'erreur
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Requête incorrecte']);
}

?>
