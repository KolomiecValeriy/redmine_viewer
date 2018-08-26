<?php

namespace RedmineViewerBundle\Redmine;

use Curl\Curl;
use Symfony\Component\Serializer\Serializer;

class RedmineApiWorker
{
    private $apiKey;
    private $serializer;

    public function __construct(string $apiKey, Serializer $serializer)
    {
       $this->apiKey = $apiKey;
       $this->serializer = $serializer;
    }

    /**
     * @return array|null
     */
    public function getProjects()
    {
        $curl = new Curl();
        $result = $curl->get('https://redmine.ekreative.com/projects.json', [
            'key' => $this->apiKey,
        ]);

        if ($result->error == true) {
            return null;
        }

        $decodedResult = $this->serializer->decode($result->response, 'json');

        if ($decodedResult['total_count'] == 0) {
            return null;
        }

        return $decodedResult['projects'];
    }
}
