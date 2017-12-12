<?php
/*
 * ApiListener.php
 *
 * Copyright (C) WASSA SAS - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited
 * Proprietary and confidential
 *
 * 30/01/2016
 */

namespace Wassa\SimpleApiKeyAuthenticatorBundle\Listener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ApiKeyAuthenticatorListener
{
    private $container;
    private $correctApiKey;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->correctApiKey = $this->container->getParameter('wassa_simple_api_key_authenticator.api_key');
    }

    public function onKernelRequest(GetResponseEvent $responseEvent)
    {
        $defaultAction = $this->container->getParameter('wassa_simple_api_key_authenticator.default_action');
        $securedPatterns = $this->container->getParameter('wassa_simple_api_key_authenticator.secured_patterns');
        $unsecuredPatterns = $this->container->getParameter('wassa_simple_api_key_authenticator.unsecured_patterns');
        $order = $this->container->getParameter('wassa_simple_api_key_authenticator.order');
        $patterns = [];
        $request = $responseEvent->getRequest();
        $pathInfo = $request->getPathInfo();
        $apiKey = $request->headers->get('apikey');

        // Check if an API key has been configured
        if (!$this->correctApiKey) {
            $this->defaultAction($responseEvent, $defaultAction, $apiKey, 'No API key configured');
            return;
        };

        // Go through all configured patterns in selected ordre
        if ($order == 'secured,unsecured') {
            foreach ($securedPatterns as $pattern) {
                $patterns[$pattern] = 'secured';
            }

            foreach ($unsecuredPatterns as $pattern) {
                $patterns[$pattern] = 'unsecured';
            }
        }
        else {
            foreach ($unsecuredPatterns as $pattern) {
                $patterns[$pattern] = 'unsecured';
            }

            foreach ($securedPatterns as $pattern) {
                $patterns[$pattern] = 'secured';
            }
        }

        // Check if the current route matches one of the configured patterns
        foreach ($patterns as $pattern => $value) {
            $pattern = "/$pattern/";

            if (preg_match($pattern, $pathInfo)) {
                if ($value == 'secured') {
                    $this->checkApiKey($responseEvent, $apiKey);
                    return;
                }
            }
        }

        // If current doesn't match any pattern, apply default action
        $this->defaultAction($responseEvent, $defaultAction, $apiKey, 'Access denied');
    }

    private function checkApiKey(GetResponseEvent $responseEvent, $apiKey)
    {
        if (!$apiKey) {
            $responseEvent->setResponse(new Response('No API key found', 403));
        }

        if ($apiKey != $this->correctApiKey) {
            $responseEvent->setResponse(new Response(sprintf('API Key "%s" is incorrect.', $apiKey), 403));
        }
    }

    private function defaultAction(GetResponseEvent $responseEvent, $defaultAction, $apiKey, $message)
    {
        if ($defaultAction == 'check' && $apiKey != $this->correctApiKey) {
            $responseEvent->setResponse(new Response($message, 403));
        }
    }
}