<?php
require_once '../bootstrap.php'; // Include your Doctrine bootstrap or autoload script
use Model\Repository\CategoryRepository;

header('Content-Type: application/json'); // Set the response type to JSON

try {
    // Get the POST data (JSON format)
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['id']) || !is_numeric($data['id'])) {
        echo json_encode(['success' => false, 'error' => 'Invalid category ID']);
        exit;
    }

    $categoryId = (int) $data['id'];

    // Initialize the repository
    $categoryRepository = new CategoryRepository($entityManager);

    // Find the category by ID
    $category = $categoryRepository->findById($categoryId);

    if (!$category) {
        echo json_encode(['success' => false, 'error' => 'Category not found']);
        exit;
    }

    // Delete the category
    $categoryRepository->delete($category);

    // Respond with success
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Respond with an error
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
