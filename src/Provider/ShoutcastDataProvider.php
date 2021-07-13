<?php

namespace App\Provider;

use App\Entity\ShoutData;
use App\Repository\ShoutDataRepository;
use Doctrine\ORM\EntityManagerInterface;

class ShoutcastDataProvider
{
    private string $statsUrl;
    private ShoutDataRepository $repository;
    private EntityManagerInterface $manager;

    public function __construct(
        string $statsUrl,
        ShoutDataRepository $repository,
        EntityManagerInterface $manager
    ) {
        $this->statsUrl = $statsUrl;
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function retrieve(): string
    {
        $raw = file_get_contents($this->statsUrl);
        $xml = simplexml_load_string($raw);

        $checksum = md5($xml->SONGTITLE);

        $data = new ShoutData();
        $data->setXml($raw);
        $data->setCreated(new \DateTime());
        $data->setChecksum($checksum);

        if (!$this->repository->findOneBy(['checksum' => $checksum])) {
            $this->manager->persist($data);
            $this->manager->flush();
        }

        return $xml->SONGTITLE;
    }
}
