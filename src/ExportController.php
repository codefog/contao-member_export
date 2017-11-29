<?php

namespace Codefog\MemberExportBundle;

use Codefog\MemberExportBundle\Exception\ExportException;
use Contao\BackendTemplate;
use Contao\Controller;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Contao\Environment;
use Contao\Message;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class ExportController
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * @var ExporterRegistry
     */
    private $registry;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * ExportController constructor.
     *
     * @param ContaoFrameworkInterface $framework
     * @param ExporterRegistry         $registry
     * @param RequestStack             $requestStack
     */
    public function __construct(
        ContaoFrameworkInterface $framework,
        ExporterRegistry $registry,
        RequestStack $requestStack
    ) {
        $this->framework = $framework;
        $this->registry = $registry;
        $this->requestStack = $requestStack;
    }

    /**
     * Run the controller
     *
     * @return string
     */
    public function run()
    {
        $formId = 'tl_member_export';
        $request = $this->requestStack->getCurrentRequest();

        if ($request->request->get('FORM_SUBMIT') === $formId) {
            $this->processForm($request);
        }

        return $this->getTemplate($formId)->parse();
    }

    /**
     * Process the form
     *
     * @param Request $request
     */
    protected function processForm(Request $request)
    {
        try {
            $exporter = $this->registry->get($request->request->get('format'));
            $exporter->export($this->createConfigFromRequest($request));
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
     * Create the config from request
     *
     * @param Request $request
     *
     * @return ExportConfig
     */
    protected function createConfigFromRequest(Request $request)
    {
        $config = new ExportConfig();
        $config->setHasHeaderFields((bool) $request->request->get('headerFields'));
        $config->setUseRawData((bool) $request->request->get('raw'));

        return $config;
    }

    /**
     * Get the template
     *
     * @param string $formId
     *
     * @return BackendTemplate
     */
    protected function getTemplate($formId)
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
     * Generate the options
     *
     * @return array
     */
    protected function generateOptions()
    {
        $options = [];

        foreach ($this->registry->getAliases() as $alias) {
            $options[$alias] = $GLOBALS['TL_LANG']['tl_member']['export_formatRef'][$alias];
        }

        return $options;
    }
}
