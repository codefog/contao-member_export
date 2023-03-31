<?php

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2017, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\Exception\ExportException;
use Contao\BackendTemplate;
use Contao\Controller;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Environment;
use Contao\Message;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ExportController
{
    public function __construct(
        private readonly ContaoFramework $framework,
        private readonly ExporterRegistry $registry,
        private readonly RequestStack $requestStack
    ) {
    }

    /**
     * Run the controller.
     *
     * @codeCoverageIgnore
     */
    public function run(): string
    {
        $formId = 'tl_member_export';
        $request = $this->requestStack->getCurrentRequest();

        if ($request->request->get('FORM_SUBMIT') === $formId) {
            $this->processForm($request);
        }

        return $this->getTemplate($formId)->parse();
    }

    /**
     * Process the form.
     *
     * @codeCoverageIgnore
     */
    protected function processForm(Request $request): void
    {
        try {
            $exporter = $this->registry->get($request->request->get('format'));
            $response = $exporter->export($this->createConfigFromRequest($request));

            throw new ResponseException($response);
        } catch (ExportException $e) {
            /** @var Message $message */
            $message = $this->framework->getAdapter(Message::class);
            $message->addError($e->getMessage());
        }

        /** @var Controller $controller */
        $controller = $this->framework->getAdapter(Controller::class);
        $controller->reload();
    }

    /**
     * Create the config from request.
     *
     * @codeCoverageIgnore
     */
    protected function createConfigFromRequest(Request $request): ExportConfig
    {
        $config = new ExportConfig();
        $config->setConsiderFilters((bool) $request->request->get('considerFilters'));
        $config->setHasHeaderFields((bool) $request->request->get('headerFields'));
        $config->setUseRawData((bool) $request->request->get('raw'));

        return $config;
    }

    /**
     * Get the template.
     *
     * @codeCoverageIgnore
     */
    protected function getTemplate(string $formId): BackendTemplate
    {
        /**
         * @var Environment $environment
         * @var Message     $message
         * @var System      $system
         */
        $environment = $this->framework->getAdapter(Environment::class);
        $message = $this->framework->getAdapter(Message::class);
        $system = $this->framework->getAdapter(System::class);

        $GLOBALS['TL_CSS'][] = 'bundles/codefogmemberexport/backend.css';

        $template = new BackendTemplate('be_member_export');
        $template->backUrl = $system->getReferer();
        $template->action = $environment->get('request');
        $template->formId = $formId;
        $template->options = $this->generateOptions();
        $template->message = $message->generate();

        return $template;
    }

    /**
     * Generate the options.
     *
     * @codeCoverageIgnore
     */
    protected function generateOptions(): array
    {
        $options = [];

        foreach ($this->registry->getAliases() as $alias) {
            $options[$alias] = $GLOBALS['TL_LANG']['tl_member']['export_formatRef'][$alias];
        }

        return $options;
    }
}
