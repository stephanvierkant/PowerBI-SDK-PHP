<?php

namespace Tngnt\PBI\API;

use Tngnt\PBI\Client;
use function sprintf;

/**
 * Class Report
 *
 * @package Tngnt\PBI\API
 */
class Report
{
    const REPORT_URL = "https://api.powerbi.com/v1.0/myorg/reports";
    const GROUP_REPORT_URL = "https://api.powerbi.com/v1.0/myorg/groups/%s/reports";
    const GROUP_REPORT_REBIND_URL = "https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/";
    const GROUP_REPORT_EMBED_URL = "https://api.powerbi.com/v1.0/myorg/groups/%s/reports/%s/GenerateToken";

    /**
     * The SDK client
     *
     * @var Client
     */
    private $client;

    /**
     * Table constructor.
     *
     * @param Client $client The SDK client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Retrieves a list of reports on PowerBI
     *
     * @param null|string $groupId An optional group ID
     *
     * @return \Tngnt\PBI\Response
     */
    public function getReports($groupId = null)
    {
        $url = $this->getUrl($groupId);

        $response = $this->client->request(Client::METHOD_GET, $url);

        return $this->client->generateResponse($response);
    }

    /**
     * Retrieves the embed token for embedding a report
     *
     * @param string      $reportId    The report ID of the report
     * @param string      $groupId     The group ID of the report
     * @param null|string $accessLevel The access level used for the report
     *
     * @return \Tngnt\PBI\Response
     */
    public function getReportEmbedToken($reportId, $groupId, $accessLevel = 'view')
    {
        $url = sprintf(self::GROUP_REPORT_EMBED_URL, $groupId, $reportId);

        $body = [
            'accessLevel' => $accessLevel,
        ];

        $response = $this->client->request(Client::METHOD_POST, $url, $body);

        return $this->client->generateResponse($response);
    }

    /**
     * Rebinds the specified report from the specified workspace to the requested dataset.
     *
     * @param string      $groupId   The workspace id
     * @param string      $reportId  The report id
     * @param string      datasetId  The new dataset for the rebound report. If the dataset resides in a different workspace than the report, a shared dataset will be created in the report's workspace
     *
     * @return \Tngnt\PBI\Response
     */
    public function rebindInGroup(string $groupId, string $reportId, string $datasetId)
    {
        $url = sprintf(self::GROUP_REPORT_REBIND_URL, $groupId, $reportId);

        $body = [
            'datasetId' => $datasetId,
        ];

        $response = $this->client->request(Client::METHOD_POST, $url, $body);

        return $this->client->generateResponse($response);
    }

    /**
     * Helper function to format the request URL
     *
     * @param null|string $groupId An optional group ID
     *
     * @return string
     */
    private function getUrl($groupId)
    {
        if ($groupId) {
            return sprintf(self::GROUP_REPORT_URL, $groupId);
        }

        return self::REPORT_URL;
    }
}
