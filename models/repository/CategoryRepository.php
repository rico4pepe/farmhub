<?php
namespace Model\Repository;

use Doctrine\ORM\EntityManager;
use Model\Category;

class CategoryRepository
{
    private EntityManager $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Fetch all categories.
     *
     * @return Category[]
     */
    public function findAll(): array
    {
        return $this->entityManager->getRepository(Category::class)->findAll();
    }

    /**
     * Find a category by its ID.
     *
     * @param int $id
     * @return Category|null
     */
    public function findById(int $id): ?Category
    {
        return $this->entityManager->find(Category::class, $id);
    }

    /**
     * Save a new category or update an existing one.
     *
     * @param Category $category
     */
    public function save(Category $category): void
    {
        $this->entityManager->persist($category);
        $this->entityManager->flush();
    }

    /**
     * Delete a category.
     *
     * @param Category $category
     */
    public function delete(Category $category): void
    {
        $this->entityManager->remove($category);
        $this->entityManager->flush();
    }

    /**
     * Find all subcategories of a given category.
     *
     * @param Category $parent
     * @return Category[]
     */
    public function findSubcategories(?Category $parent): array
{
    return $this->entityManager->getRepository(Category::class)
        ->findBy(['parent' => $parent]);
}

    public function categoryExists(string $name): bool
{
    $category = $this->entityManager->getRepository(Category::class)
        ->findOneBy(['name' => $name]);

    return $category !== null;
}

public function getCategoriesWithSubcategories(): array
{
    $categories = $this->entityManager->getRepository(Category::class)->findBy(['parent' => null]);

    $menu = [];
    foreach ($categories as $category) {
        $menu[] = [
            'id' => $category->getId(),
            'name' => $category->getName(),
            'thumbnail' => $category->getThumbnail(),
            'subcategories' => array_map(function (Category $sub) {
                return [
                    'id' => $sub->getId(),
                    'name' => $sub->getName(),
                    'thumbnail' => $sub->getThumbnail(),
                ];
            }, $category->getChildren()->toArray()),
        ];
    }

    return $menu;
}

public function getParentCategories(): array
{
    return $this->entityManager->getRepository(Category::class)->findBy(['parent' => null]);
}



 /**
     * Add a subcategory under a parent category.
     *
     * @param string $subcategoryName
     * @param int $parentId
     * @return bool
     */
    public function subcategoryExists($subcategoryName, $parentId) {
        try {
            $query = $this->entityManager->getConnection()->prepare("
                SELECT COUNT(*) as count 
                FROM categories 
                WHERE category_name = :name AND parent_id = :parent_id
            ");
            
            $query->bindValue(':name', $subcategoryName);
            $query->bindValue(':parent_id', $parentId);
            
            $result = $query->executeQuery();
            $count = $result->fetchOne();
            
            return $count > 0;
            
        } catch (\Exception $e) {
            echo "Database Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }
    
    // Then you can modify your addSubcategory method to use this check:
    public function addSubcategory($subcategoryName, $parentId) {
        try {
            // First check if subcategory exists
            if ($this->subcategoryExists($subcategoryName, $parentId)) {
                echo "Subcategory already exists!<br>";
                return false;
            }
    
            $query = $this->entityManager->getConnection()->prepare("
                INSERT INTO categories (category_name, parent_id) VALUES (:name, :parent_id)
            ");
            $query->bindValue(':name', $subcategoryName);
            $query->bindValue(':parent_id', $parentId);
    
            $result = $query->executeStatement();
    
            if (!$result) {
                echo "Database insertion failed!<br>";
            }
    
            return $result;
        } catch (\Exception $e) {
            echo "Database Error: " . $e->getMessage() . "<br>";
            return false;
        }
    }




}
