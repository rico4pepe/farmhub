<?php

namespace Model\Repository;

use Doctrine\ORM\EntityRepository;
//use Doctrine\ORM\QueryBuilder;
use Model\User;
//use Doctrine\ORM\Query\Expr;
use Doctrine\ORM\EntityManagerInterface;

class UserRepository extends EntityRepository
{
   private EntityManagerInterface $entityManager;

   public function __construct(EntityManagerInterface $entityManager)
   {
       $this->entityManager = $entityManager;
       parent::__construct($entityManager, $entityManager->getClassMetadata(User::class));
   }

   public function findByEmail(string $email): ?User
   {
       return $this->findOneBy(['email' => $email]);
   }

   public function findByUsername(string $username): ?User
   {
       return $this->findOneBy(['username' => $username]);
   }

   public function findByRole(string $role): array
   {
       $qb = $this->createQueryBuilder('u')
           ->join('u.role', 'r')
           ->where('r.name = :role')
           ->setParameter('role', $role);
       
       return $qb->getQuery()->getResult();
   }

   public function findActiveUsers(): array
   {
       $qb = $this->createQueryBuilder('u')
           ->where('u.role IS NOT NULL');
           
       return $qb->getQuery()->getResult();
   }

   public function searchUsers(string $term): array
   {
       $qb = $this->createQueryBuilder('u')
           ->where('u.username LIKE :term')
           ->orWhere('u.email LIKE :term')
           ->orWhere('u.firstName LIKE :term')
           ->orWhere('u.lastName LIKE :term')
           ->setParameter('term', '%' . $term . '%');
           
       return $qb->getQuery()->getResult();
   }

   public function countAll(): int
   {
       return $this->createQueryBuilder('u')
           ->select('COUNT(u.id)')
           ->getQuery()
           ->getSingleScalarResult();
   }

     /**
     * Custom query with advanced filtering
     *
     * @param array $criteria
     * @return array
     */
    public function findByCustomCriteria(array $criteria): array
    {
        $queryBuilder = $this->createQueryBuilder('u');
        
        foreach ($criteria as $field => $value) {
            if ($value) {
                $queryBuilder->andWhere("u.$field = :$field")
                             ->setParameter($field, $value);
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }

       /**
     * Find users who have not confirmed their email yet.
     *
     * @return array
     */
    public function findUnconfirmedUsers(): array
    {
        return $this->findBy(['isConfirmed' => false]);
    }


 /**
     * Save a new user or update an existing one
     *
     * @param User $user
     * @return void
     */
    public function save(User $user): void
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }



       /**
     * Delete a user
     *
     * @param User $user
     * @return void
     */
    public function remove(User $user): void
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    
    public function confirmEmail(string $token): bool 
    {
        $user = $this->findOneBy(['confirmationToken' => $token]);
        
        if (!$user) {
            return false;
        }
    
        $user->setIsConfirmed(true);
        $user->setConfirmationToken(null);
        $this->save($user);
        
        return true;
    }
    
    public function isEmailConfirmed(string $email): bool 
    {
        $user = $this->findByEmail($email);
        return $user ? $user->getIsConfirmed() : false;
    } 


public function findByResetToken(string $token): ?User
{
    return $this->findOneBy(['resetToken' => $token]);
}

public function createPasswordResetToken(User $user): string
{
    $token = bin2hex(random_bytes(32));
    $expires = new \DateTime('+1 hour');
    
    $user->setResetToken($token);
    $user->setResetTokenExpires($expires);
    $this->save($user);
    
    return $token;
}

public function resetPassword(User $user, string $newPassword): void
{
    $user->setPassword(password_hash($newPassword, PASSWORD_BCRYPT));
    $user->setResetToken(null);
    $user->setResetTokenExpires(null);
    $this->save($user);
}


    
    
}