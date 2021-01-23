<?php

declare(strict_types=1);

namespace App\Behat;

use Behat\Gherkin\Node\PyStringNode;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Behat\Mink\WebAssert;
use Coduo\PHPMatcher\PHPMatcher;
use FriendsOfBehat\SymfonyExtension\Driver\SymfonyDriver;

final class ApiContext implements Context
{
    /**
     * @var array<string, string>
     */
    private $serverParameters = [];

    public function __construct(private Session $session) {}

    /**
     * @When I add :name header equal to :value
     */
    public function iAddHeaderEqualTo(string $name, string $value): void
    {
        $contentHeaders = array('CONTENT_LENGTH' => true, 'CONTENT_MD5' => true, 'CONTENT_TYPE' => true);
        $name = str_replace('-', '_', strtoupper($name));

        // CONTENT_* are not prefixed with HTTP_ in PHP when building $_SERVER
        if (!isset($contentHeaders[$name])) {
            $name = 'HTTP_' . $name;
        }

        $this->serverParameters[$name] = $value;
    }

    /**
     * @BeforeScenario
     */
    public function reset(): void
    {
        $this->serverParameters = [];
    }

    /**
     * @When I send a :method request to :url with body:
     */
    public function iSendARequestToWithBody(string $method, string $url, PyStringNode $body): void
    {
        /**
         * @var SymfonyDriver $driver
         */
        $driver = $this->session->getDriver();
        $driver->getClient()->request(
            $method,
            $url,
            [],
            [],
            $this->serverParameters,
            $body->getRaw()
        );
    }

    /**
     * @Then the response status code should be :statusCode
     */
    public function theResponseStatusCodeShouldBe(int $statusCode): void
    {
        $assert = new WebAssert($this->session);
        $assert->statusCodeEquals($statusCode);
    }

    /**
     * @Then the JSON should be equal to:
     */
    public function theJsonShouldBeEqualTo(PyStringNode $expectedJson)
    {
        $matcher = new PHPMatcher();
        $match = $matcher->match($expectedJson->getRaw(), $this->session->getDriver()->getContent());

        if (!$match) {
            throw new \Exception(sprintf('Response does not match %s', $matcher->error()));
        }
    }
}
