<?php

namespace App\Service;

use App\Entity\SongData;
use App\Repository\SongDataRepository;
use Discogs\ClientFactory;
use Discogs\Subscriber\ThrottleSubscriber;
use Doctrine\ORM\EntityManagerInterface;

class DiscogsService
{
    private const USERAGENT = 'Shoutcast JSON API/0.1 +https://github.com/wwsh';
    private string $discogsToken;
    private SongDataRepository $repository;
    private EntityManagerInterface $manager;

    public function __construct(
        string $discogsToken,
        SongDataRepository $repository,
        EntityManagerInterface $manager
    ) {
        $this->discogsToken = $discogsToken;
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function read(string $songTitle): ?SongData
    {
        if (empty($songTitle)) {
            return null;
        }

        $client = ClientFactory::factory([
             'defaults' => [
                 'headers' => ['User-Agent' => self::USERAGENT],
                 'query' => [
                     'token' => $this->discogsToken,
                 ],
             ]
        ]);

        $client->getHttpClient()->getEmitter()->attach(new ThrottleSubscriber());

        $checksum = md5($songTitle);
        if ($song = $this->repository->findOneBy(['checksum' => $checksum])) {
            return $song;
        } else {
            $response = $client->search(['q' => $songTitle, 'type' => 'release']);
        }

        $results = $response['results'] ?? [];

        if (!empty($results)) {
            $matching = $this->findMatchingRelease($results, $songTitle);
        }

        $song = new SongData();

        if (empty($results) || empty($matching)) {
            [$artist, $title] = $this->getArtistTitle($songTitle);
            $song->setArtist($artist);
            $song->setTitle($title);
            $song->setChecksum($checksum);
            $song->setCreated(new \DateTime());
            $song->setRawSongtitle($songTitle);
        } else {
            $song->setArtist($matching['artist']);
            $song->setTitle($matching['title']);
            $song->setYear($matching['year'] ?? null);
            $song->setLabel($matching['label'] ?? '');
            $song->setChecksum($checksum);
            $song->setCreated(new \DateTime());
            $song->setMesh(json_encode($results));
            $song->setRawSongtitle($songTitle);
            $song->setCatno($matching['catno'] ?? '');
        }

        $this->manager->persist($song);
        $this->manager->flush();

        return $song;
    }

    private function findMatchingRelease(array $results, string $songTitle): ?array
    {
        $result = collect($results)
            ->first(fn($value, $key) =>
                // type is release and release name is matching searched text
                $value['type'] === 'release' &&
                (str_contains($songTitle, $value['title']) || similar_song($songTitle, $value['title']) >= 50)
            );

        if (!$result) {
            return null;
        }

        // do some postprocessing
        $artist = $this->getArtistTitle($result['title'])[0];
        [$result['artist'], $result['title']] = $this->getArtistTitle($songTitle);
        $result['artist'] = $artist;
        // remove the CATNO from the songtitle
        $result['title'] = preg_replace('/,\s[12][0-9]{3}\s.*$/', '', $result['title']);
        $result['label'] = $result['label'] ? $result['label'][0] : '';

        $result['artist'] = $this->cleanupString($result['artist']);
        $result['title'] = $this->cleanupString($result['title']);
        $result['label'] = $this->cleanupString($result['label']);

        return $result;
    }

    private function getArtistTitle(string $songTitle): array
    {
        $result = explode(' - ', $songTitle);

        if (count($result) < 2) {
            return ['', $songTitle]; // return at least a title
        }

        return [$result[0], $result[1]];
    }

    private function cleanupString(string $string): string
    {
        $string = preg_replace('/\([0-9]+\)/', '', $string);
        $string = str_replace('*', '', $string);

        $string = trim($string);

        return $string;
    }
}
