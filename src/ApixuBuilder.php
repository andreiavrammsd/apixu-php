<?php declare(strict_types = 1);

namespace Apixu;

use Apixu\Api\Api;
use Apixu\Exception\ApiKeyMissingException;
use Apixu\Exception\LanguageMissingException;
use GuzzleHttp\Client;
use Serializer\Format\UnknownFormatException;
use Serializer\SerializerBuilder;

final class ApixuBuilder
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $language;

    /**
     * @return ApixuBuilder
     */
    public static function instance() : ApixuBuilder
    {
        return new static();
    }

    /**
     * @param string $apiKey
     * @return ApixuBuilder
     * @throws ApiKeyMissingException
     */
    public function setApiKey(string $apiKey) : ApixuBuilder
    {
        if (trim($apiKey) === '') {
            throw new ApiKeyMissingException('API key not set.');
        }

        $this->apiKey = $apiKey;

        return $this;
    }

  /**
     * @param string $language
     * @return ApixuBuilder
     * @throws LanguageMissingException
     */
    public function setLanguage(string $language) : ApixuBuilder
    {
        if (trim($language) === '') {
            throw new LanguageMissingException('Language code not set.');
        }

        $this->language = $language;

        return $this;
    }

    /**
     * @return ApixuInterface
     * @throws UnknownFormatException
     */
    public function build() : ApixuInterface
    {
        $httpClient = new Client([
            'timeout' => Config::HTTP_TIMEOUT,
        ]);

        $serializer = SerializerBuilder::instance()
            ->setFormat(Config::FORMAT)
            ->build();

        $api = new Api($this->apiKey, $httpClient);

        return new Apixu($api, $serializer, $this->language);
    }
}
