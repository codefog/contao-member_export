<?php

declare(strict_types=1);

/*
 * Member Export bundle for Contao Open Source CMS.
 *
 * @copyright  Copyright (c) 2026, Codefog
 * @author     Codefog <https://codefog.pl>
 * @license    MIT
 */

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\Exception\ExportException;
use Contao\Controller;
use Contao\CoreBundle\Controller\AbstractController;
use Contao\CoreBundle\Exception\ResponseException;
use Contao\Message;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Contracts\Translation\TranslatorInterface;

class ExportController extends AbstractController
{
    public function __construct(
        private readonly ExporterRegistry $registry,
        private readonly RequestStack $requestStack,
        private readonly TranslatorInterface $translator,
    ) {
    }

    public function run(): string
    {
        $formId = 'tl_member_export';
        $request = $this->requestStack->getCurrentRequest();

        if ($request->request->get('FORM_SUBMIT') === $formId) {
            $this->processForm($request);
        }

        return $this->parseTemplate($formId);
    }

    private function processForm(Request $request): never
    {
        try {
            $exporter = $this->registry->get($request->request->get('format'));
            $response = $exporter->export($this->createConfigFromRequest($request));

            throw new ResponseException($response);
        } catch (ExportException $e) {
            $this->getContaoAdapter(Message::class)->addError($e->getMessage());
        }

        $this->getContaoAdapter(Controller::class)->reload();
    }

    private function createConfigFromRequest(Request $request): ExportConfig
    {
        $config = new ExportConfig();
        $config->setConsiderFilters((bool) $request->request->get('considerFilters'));
        $config->setHasHeaderFields((bool) $request->request->get('headerFields'));
        $config->setUseRawData((bool) $request->request->get('raw'));

        return $config;
    }

    private function parseTemplate(string $formId): string
    {
        $message = $this->getContaoAdapter(Message::class);
        $system = $this->getContaoAdapter(System::class);

        $GLOBALS['TL_CSS'][] = 'bundles/codefogmemberexport/backend.css';

        return $this->renderView('@Contao/backend/member_export.html.twig', [
            'backUrl' => $system->getReferer(),
            'formId' => $formId,
            'options' => $this->generateOptions(),
            'message' => $message->generate(),
        ]);
    }

    private function generateOptions(): array
    {
        $options = [];

        foreach ($this->registry->getAliases() as $alias) {
            $options[$alias] = $this->translator->trans('tl_member.export_formatRef.'.$alias, [], 'contao_default');
        }

        return $options;
    }
}
