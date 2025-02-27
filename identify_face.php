<?php
require 'config/conexao.php';

    function euclideanDistance($a, $b) {
        // return 0;
        $sum = 0;
        for ($i = 0; $i < count($a); $i++) {
            $sum += pow($a[$i] - $b[$i], 2);
        }
        return sqrt($sum);
    }

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start(); // Ensure session is started

    $rawData = file_get_contents("php://input");
    $data = json_decode($rawData, true);

    // Debugging: Log received data
    file_put_contents("debug_log.txt", "Raw Data: " . $rawData . "\n", FILE_APPEND);
    file_put_contents("debug_log.txt", "Decoded Data: " . print_r($data, true) . "\n", FILE_APPEND);
    
    $image_descriptor = isset($data['descriptor']) ? $data['descriptor'] : null;

    if (!$image_descriptor) {
        echo json_encode(["error" => "No descriptor received"]);
        exit;
    }//else{
//         echo json_encode(["descriptor" =>$image_descriptor]);
//         exit;
//     }

    try {
        $conexao = conexao::getInstance();
        $sql = "SELECT matricula, descriptors FROM tab_face_reg";
        $stm = $conexao->prepare($sql);
        $stm->execute();
        $faces = $stm->fetchAll(PDO::FETCH_ASSOC);
    
        $inputDescriptor = $image_descriptor;
        $bestMatch = null;
        $match_matricula = null;
        $lowestDistance = INF;
    
        foreach ($faces as $face) {
            $storedDescriptors = json_decode($face['descriptors'], true); // Now an array of multiple descriptors
            $cur_matricula = $face['matricula'];
            
            if (!is_array($storedDescriptors)) {
                error_log("Invalid descriptor format for Matricula {$face['matricula']}");
                continue;
            }
            
            $minDistance = INF;
            
            // Compare against each stored descriptor
            foreach ($storedDescriptors as $storedDescriptor) {
                if (!is_array($storedDescriptor) || count($storedDescriptor) !== count($inputDescriptor)) {
                    error_log("Descriptor mismatch for Matricula {$face['matricula']}: Structure or length differs.");
                    continue;
                }
               
                $distance = euclideanDistance($inputDescriptor, $storedDescriptor);
                // echo json_encode(["distance" => $distance]);
                // exit;
                error_log("Distance with Matricula {$face['matricula']}: " . $distance);
        
                if ($distance < $minDistance) {
                    $minDistance = $distance;
                }
            }
        
            // If the closest descriptor is a match, update the best match
            if ($minDistance < 0.45 && $minDistance < $lowestDistance) {
                $bestMatch = $face;
                $lowestDistance = $minDistance;
                $match_matricula = $cur_matricula;
            }
        }
    
        if ($bestMatch) {
            echo json_encode([
                "success" => true,
                "distance" => $lowestDistance,
                "matricula" => $match_matricula,
                // "nome" => $bestMatch['nome']
            ]);
        } else {
            echo json_encode(["success" => false]);
        }
    } catch (PDOException $e) {
        echo json_encode(["error" => "Database error: " . $e->getMessage()]);
    }
    
    // Calculate Euclidean distance
    
    
}
?>
