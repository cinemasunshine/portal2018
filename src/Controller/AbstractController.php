<?php

declare(strict_types=1);

namespace App\Controller;

use App\Authorization\RewardManager as RewardAuthorizationManager;
use App\Exception\RedirectException;
use App\Session\SessionManager;
use App\User\Manager as UserManager;
use Doctrine\ORM\EntityManager;
use LogicException;
use Monolog\Logger;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\UriInterface;
use Slim\Http\Environment;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

/**
 * @property-read RewardAuthorizationManager $rewardAuth
 * @property-read EntityManager $em
 * @property-read Environment $environment
 * @property-read Logger $logger
 * @property-read Router $router
 * @property-read array $settings
 * @property-read SessionManager $sm
 * @property-read UserManager $um
 * @property-read Twig $view
 */
abstract class AbstractController
{
    protected ContainerInterface $container;
    protected string $actionName;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * execute
     *
     * 前後でpreExecute(),postExecute()処理を自動実行するために実装。
     * __call()からの呼び出しを想定。
     *
     * @param array<string, mixed> $args
     */
    protected function execute(
        string $actionMethod,
        Request $request,
        Response $response,
        array $args
    ): Response {
        try {
            $this->logger->debug('Run preExecute().');
            $this->preExecute($request, $response, $args);

            $this->logger->debug('Run {method}().', ['method' => $actionMethod]);

            $response = $this->$actionMethod($request, $response, $args);

            $this->logger->debug('Run postExecute().');
            $this->postExecute($request, $response, $args);
        } catch (RedirectException $e) {
            $this->logger->debug('Redirect.', [
                'url'    => $e->getUrl(),
                'status' => $e->getStatus(),
            ]);

            return $response->withRedirect($e->getUrl(), $e->getStatus());
        }

        return $response;
    }

    /**
     * pre execute
     *
     * responseなどをreturnしたいケースがあれば検討する。
     *
     * @param array<string, mixed> $args
     */
    abstract protected function preExecute(Request $request, Response $response, array $args): void;

    /**
     * pre execute
     *
     * responseなどをreturnしたいケースがあれば検討する。
     *
     * @param array<string, mixed> $args
     */
    abstract protected function postExecute(Request $request, Response $response, array $args): void;

    /**
     * redirect
     *
     * withRedirect()ではなくこちらを使う。
     * すぐにリダイレクトさせるためにExceptionを利用している。
     *
     * @param string|UriInterface $url
     *
     * @throws RedirectException
     */
    protected function redirect($url, ?int $status = null): void
    {
        throw new RedirectException($url, $status);
    }

    /**
     * @param array<int, mixed> $argments
     *
     * @throws LogicException
     */
    public function __call(string $name, array $argments): Response
    {
        $this->logger->debug('Call "{name}" action.', ['name' => $name]);

        $actionMethod = 'execute' . ucfirst($name);

        // is_callable()は__call()があると常にtrueとなるので不可
        if (! method_exists($this, $actionMethod)) {
            throw new LogicException(sprintf('The method "%s" dose not exist.', $name));
        }

        $this->actionName = $name;

        return $this->execute($actionMethod, $argments[0], $argments[1], $argments[2]);
    }

    /**
     * @return mixed
     */
    public function __get(string $name)
    {
        return $this->container->get($name);
    }
}
