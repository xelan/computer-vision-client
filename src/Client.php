<?php
/**
 * Computer Vision Client.
 *
 * @copyright (c) 2017 Andreas Erhard
 * @author Andreas Erhard <developer@andaris.at>
 */

namespace Andaris\ComputerVision;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException as HttpClientException;
use Andaris\ComputerVision\Exception\ClientException;

class Client
{
    /**
     * Visual Features supported by the Computer Vision API.
     *
     * @see https://westus.dev.cognitive.microsoft.com/docs/services/56f91f2d778daf23d8ec6739/operations/56f91f2e778daf14a499e1fa
     */
    const FEATURE_CATEGORIES = 'Categories';
    const FEATURE_TAGS = 'Tags';
    const FEATURE_DESCRIPTION = 'Description';
    const FEATURE_FACES = 'Faces';
    const FEATURE_IMAGE_TYPE = 'ImageType';
    const FEATURE_COLOR = 'Color';
    const FEATURE_ADULT = 'Adult';

    /**
     * @var string Computer Vision API Key
     *
     * @see https://azure.microsoft.com/en-us/try/cognitive-services/my-apis/
     */
    private $apiKey = '';

    /**
     * @var string Computer Vision API Endpoint, the default should work for most users.
     */
    private $endpoint = '';

    /**
     * @var  HttpClient Guzzle HTTP Client.
     */
    private $httpClient;

    /**
     * Constructor.
     *
     * @param string          $apiKey
     * @param string          $endpoint
     * @param HttpClient|null $httpClient
     */
    public function __construct(
        $apiKey,
        $endpoint = 'https://westcentralus.api.cognitive.microsoft.com/vision/v1.0/analyze',
        $httpClient = null
    ) {
        $this->apiKey = $apiKey;
        $this->endpoint = $endpoint;

        $this->httpClient = $httpClient ? $httpClient : new HttpClient();
    }

    /**
     * Analyzes an image.
     *
     * @param string   $imageData       Binary image data.
     * @param string[] $visualFeatures  Features which should be analyzed, see FEATURE_* constants.
     *
     * @return array Result of the Computer Vision API, the JSON is automatically decoded.
     *               @see https://docs.microsoft.com/en-us/azure/cognitive-services/computer-vision/quickstarts/php
     *
     * @throws ClientException
     */
    public function analyze($imageData, $visualFeatures = [self::FEATURE_TAGS])
    {
        $headers = [
            'Content-Type' => 'application/octet-stream',
            'Ocp-Apim-Subscription-Key' => $this->apiKey,
        ];

        $parameters = [
            'visualFeatures' => implode(',', $visualFeatures),
            'language' => 'en',
        ];

        try {
            $response = $this->httpClient->request(
                'POST',
                $this->endpoint,
                [
                    'headers' => $headers,
                    'query' => $parameters,
                    'body' => $imageData,
                ]
            );
        } catch (HttpClientException $e) {
            throw new ClientException(
                sprintf('An error occured when calling the Computer Vision API: "%s"', $e->getMessage())
            );
        }

        return json_decode($response->getBody(), true);
    }
}
