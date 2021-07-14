<?php

namespace App\Controller;

use App\Entity\SongData;
use App\Repository\ShoutDataRepository;
use App\Repository\SongDataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class DatasourceController extends AbstractController
{
    private const EMPTY_TITLE = '[TYTUŁ]';
    private const EMPTY_ARTIST = '[WYKONAWCA]';
    private const EMPTY_YEAR = '199X';
    private const EMPTY_LABEL = '-';
    private const EMPTY_CATNO = 'NONE';
    private const LIMIT = 100;

    #[Route('/get/title', name: 'get_title')]
    public function title(SongDataRepository $repository, Request $request): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $title = $current?->getTitle() ?: self::EMPTY_TITLE;

        if ($request->get('uppercase')) {
            $title = mb_strtoupper($title, 'UTF-8');
        }

        return $this->json([
            ['result' => $title]
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
            ['result' => $artist]
        ]);
    }

    #[Route('/get/year', name: 'get_year')]
    public function year(SongDataRepository $repository): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $year = $current?->getYear() ?: self::EMPTY_YEAR;

        return $this->json([
            ['result' => $year]
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
            ['result' => $result]
        ]);
    }

    #[Route('/get/catno', name: 'get_catno')]
    public function catno(SongDataRepository $repository): Response
    {
        $current = $repository->findOneBy([], ['id' => 'DESC']);

        $result = $current?->getCatno() ?: self::EMPTY_CATNO;

        return $this->json([
            ['result' => $result]
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
            ['result' => $result]
        ]);
    }

    #[Route('/get/history', name: 'get_history')]
    public function history(SongDataRepository $repository, Request $request): Response
    {
        $result = $repository->findBy([], ['updated' => 'DESC'], self::LIMIT);

        $uppercase = fn ($value) => $request->get('uppercase') ?
            mb_strtoupper((string) $value, 'UTF-8') : (string) $value;

        return $this->json(collect($result)
        ->map(fn (SongData $entity) => [
            'artist' => $uppercase($entity->getArtist()),
            'title' => $uppercase($entity->getTitle()),
            'year' => $entity->getYear(),
            'catno' => $entity->getCatno(),
            'label' => $uppercase($entity->getLabel()),
            'created' => $entity->getCreated(),
            'updated' => $entity->getUpdated(),
            ])
        ->all());
    }

    #[Route('/get/debug', name: 'get_debug')]
    public function debug(
        ShoutDataRepository $repository,
        SongDataRepository $songDataRepository
    ): Response {
        $result = $repository->findOneBy([], ['id' => 'DESC']);

        if (!$result) {
            return $this->json([]);
        }

        $data = $result;

        $result = $songDataRepository->findOneBy([], ['updated' => 'DESC']);

        if (!$result) {
            return $this->json([
                [
                    'caption' => 'last SHOUTcast xml',
                    'data' => $data,
                ],
            ]);
        }

        return $this->json([
            [
                'caption' => 'last SHOUTcast xml',
                'data' => $data,
            ],
            [
                'caption' => 'last response',
                'data' => $result,
            ],
        ]);
    }
}
