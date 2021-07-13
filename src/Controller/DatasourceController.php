<?php

namespace App\Controller;

use App\Entity\SongData;
use App\Repository\SongDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DatasourceController extends AbstractController
{
    private const EMPTY_TITLE = '[TYTUŁ]';
    private const EMPTY_ARTIST = '[WYKONAWCA]';
    private const EMPTY_YEAR = '199X';
    private const EMPTY_LABEL = '-';
    private const EMPTY_CATNO = 'NONE';

    #[Route('/get/title', name: 'get_title')]
    public function title(SongDataRepository $repository, Request $request): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $title = $current?->getTitle() ?: self::EMPTY_TITLE;

        if ($request->get('uppercase')) {
            $title = mb_strtoupper($title, 'UTF-8');
        }

        return $this->json([
            'result' => $title
        ]);
    }

    #[Route('/get/artist', name: 'get_artist')]
    public function artist(SongDataRepository $repository, Request $request): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $artist = $current?->getArtist() ?: self::EMPTY_ARTIST;

        if ($request->get('uppercase')) {
            $artist = mb_strtoupper($artist, 'UTF-8');
        }

        return $this->json([
            'result' => $artist
        ]);
    }

    #[Route('/get/year', name: 'get_year')]
    public function year(SongDataRepository $repository): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $year = $current?->getYear() ?: self::EMPTY_YEAR;

        return $this->json([
            'year' => $year
        ]);
    }

    #[Route('/get/label', name: 'get_label')]
    public function label(SongDataRepository $repository, Request $request): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $result = $current?->getLabel() ?: self::EMPTY_LABEL;

        if ($request->get('uppercase')) {
            $result = mb_strtoupper($result, 'UTF-8');
        }

        return $this->json([
            'label' => $result
        ]);
    }

    #[Route('/get/catno', name: 'get_catno')]
    public function catno(SongDataRepository $repository): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $result = $current?->getCatno() ?: self::EMPTY_CATNO;

        return $this->json([
            'result' => $result
        ]);
    }

    #[Route('/get/ucf', name: 'get_ucf')]
    public function ucf(SongDataRepository $repository): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $result = sprintf('%04d %s [%s]',
            $current->getYear(),
            $current->getArtist(),
            $current->getReleaseTitle(),
            $current->getCatno(),
        );

        return $this->json([
            'result' => $result
        ]);
    }

    #[Route('/get/history', name: 'get_history')]
    public function history(SongDataRepository $repository): Response
    {
        $result = $repository->findBy([], ['id' => 'DESC']);

        return $this->json(collect($result)
        ->map(fn (SongData $entity) => [
            'artist' => $entity->getArtist(),
            'title' => $entity->getTitle(),
            'year' => $entity->getYear(),
            'catno' => $entity->getCatno(),
            'label' => $entity->getLabel(),
            'created' => $entity->getCreated(),
            ])
        ->all());
    }
}
