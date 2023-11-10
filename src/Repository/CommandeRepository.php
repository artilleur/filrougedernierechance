<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Commande>
 *
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    public function save(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Commande $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function myCommande($id): array
    {
        
        $qb = $this->createQueryBuilder('c')
            //->select('c.id as c_id, u.id as user_id, u. pro.id as p_id, produit.nom as p_nom, detail.prix as p_prix, detail.quantite as p_quantite')
            ->select('c.id as com_id,c.adresse as adresse_livraison, c.adresse_fact as adresse_facturation, u.id as user_id,  produit.nom as p_nom, detail.prix as prix, detail.quantite as quantite, c.date_commande as date')
            ->join('c.utilisateur', 'u')
            ->join('c.commandeDetails', 'detail')
            ->join('detail.pro', 'produit')
            ->where('u.id = :comUtiId')
            ->setParameter('comUtiId', $id);
          
    
        $query = $qb->getQuery();
    
        return $query->execute();
        // return $query->getResult();
    
        // to get just one result:
        // $product = $query->setMaxResults(1)->getOneOrNullResult();
    }

//    /**
//     * @return Commande[] Returns an array of Commande objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Commande
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
