<?php

namespace RedmineViewerBundle\Redmine;

use Curl\Curl;
use Symfony\Component\Serializer\Serializer;

class RedmineApiWorker
{
    private $apiUrl;
    private $apiKey;
    private $serializer;

    public function __construct(string $apiUrl, string $apiKey, Serializer $serializer)
    {
       $this->apiUrl = $apiUrl;
       $this->apiKey = $apiKey;
       $this->serializer = $serializer;
    }

    /**
     * @return array|null
     */
    public function getProjects()
    {

        $issues = $this->getInfoFromApi('projects');

        return $issues;
    }

    /**
     * @param int $projectId
     *
     * @return array|null
     */
    public function getIssuesPerProject(int $projectId)
    {
        $issues = $this->getInfoFromApi('issues', [
            'project_id' => $projectId,
        ]);

        if (!$issues) {
            return null;
        }

        // adding spended time for issues
        foreach ($issues as $key => $issue) {
            $timeEntries = $this->getInfoFromApi('time_entries', [
                'issue_id' => $issue['id'],
            ]);

            if (!$timeEntries) {
                $issues[$key]['spendedTime'] = 0;
                continue;
            }

            $spendedTime = 0;
            foreach ($timeEntries as $timeEntry) {
                $spendedTime += $timeEntry['hours'];
            }

            $issues[$key]['spendedTime'] = $spendedTime;
        }

        return $issues;
    }

    /**
     * @param int $issueId
     *
     * @return array|null
     */
    public function getIssueById(int $issueId)
    {
        $issue = $this->getInfoFromApi('issues', [
            'issue_id' => $issueId,
        ]);

        if (!$issue) {
            return null;
        }

        return $issue[0];
    }

    /**
     * @param string $content
     * @param array $param
     * @return array|null
     */
    private function getInfoFromApi(string $content, array $param = [])
    {
        $parameters = [
            'key' => $this->apiKey,
        ];

        foreach ($param as $key => $parameter) {
            $parameters[$key] = $parameter;
        }

        $curl = new Curl();
        $result = $curl->get($this->apiUrl.'/'.$content.'.json', $parameters);

        if ($result->error == true) {
            return null;
        }

        $decodedResult = $this->serializer->decode($result->response, 'json');

        if ($decodedResult['total_count'] == 0) {
            return null;
        }

        return $decodedResult[$content];
    }
}
