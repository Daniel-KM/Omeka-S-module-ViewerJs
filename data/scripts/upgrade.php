<?php declare(strict_types=1);

namespace ViewerJs;

use Common\Stdlib\PsrMessage;

/**
 * @var Module $this
 * @var \Laminas\ServiceManager\ServiceLocatorInterface $services
 * @var string $newVersion
 * @var string $oldVersion
 *
 * @var \Omeka\Api\Manager $api
 * @var \Omeka\Settings\Settings $settings
 * @var \Doctrine\DBAL\Connection $connection
 * @var \Doctrine\ORM\EntityManager $entityManager
 * @var \Omeka\Mvc\Controller\Plugin\Messenger $messenger
 */
$plugins = $services->get('ControllerPluginManager');
$api = $plugins->get('api');
$settings = $services->get('Omeka\Settings');
$translate = $plugins->get('translate');
$connection = $services->get('Omeka\Connection');
$messenger = $plugins->get('messenger');
$entityManager = $services->get('Omeka\EntityManager');

if (!method_exists($this, 'checkModuleActiveVersion') || !$this->checkModuleActiveVersion('Common', '3.4.60')) {
    $message = new \Omeka\Stdlib\Message(
        $translate('The module %1$s should be upgraded to version %2$s or later.'), // @translate
        'Common', '3.4.60'
    );
    throw new \Omeka\Module\Exception\ModuleCannotInstallException((string) $message);
}

if (version_compare($oldVersion, '3.1.3', '<')) {
    $this->updateWhitelist();

    $settings->delete('viewerjs_style');

    $siteSettings = $services->get('Omeka\Settings\Site');
    /** @var \Omeka\Api\Representation\SiteRepresentation[] $sites */
    $sites = $api->search('sites')->getContent();
    foreach ($sites as $site) {
        $siteSettings->setTargetId($site->id());
        $siteSettings->delete('viewerjs_style');
    }
}

if (version_compare($oldVersion, '3.4.4', '<')) {
    /**
     * Migrate blocks of this module to new blocks of Omeka S v4.1.
     *
     * Replace filled settting "heading" by a specific block "Heading".
     *
     * @var \Laminas\Log\Logger $logger
     *
     * @see \Omeka\Db\Migrations\MigrateBlockLayoutData
     */
    $logger = $services->get('Omeka\Logger');
    $pageRepository = $entityManager->getRepository(\Omeka\Entity\SitePage::class);
    $blocksRepository = $entityManager->getRepository(\Omeka\Entity\SitePageBlock::class);

    $viewHelpers = $services->get('ViewHelperManager');
    $escape = $viewHelpers->get('escapeHtml');
    $hasBlockPlus = $this->isModuleActive('BlockPlus');

    $pagesUpdated = [];
    foreach ($pageRepository->findAll() as $page) {
        $pageId = $page->getId();
        $pageSlug = $page->getSlug();
        $siteSlug = $page->getSite()->getSlug();
        $position = 0;
        foreach ($page->getBlocks() as $block) {
            $block->setPosition(++$position);
            $layout = $block->getLayout();
            if ($layout !== 'viewerJs') {
                continue;
            }
            $blockId = $block->getId();
            $data = $block->getData() ?: [];

            $heading = $data['heading'] ?? '';
            if (strlen($heading)) {
                $b = new \Omeka\Entity\SitePageBlock();
                $b->setPage($page);
                $b->setPosition(++$position);
                if ($hasBlockPlus) {
                    $b->setLayout('heading');
                    $b->setData([
                        'text' => $heading,
                        'level' => 2,
                    ]);
                } else {
                    $b->setLayout('html');
                    $b->setData([
                        'html' => '<h2>' . $escape($heading) . '</h2>',
                    ]);
                }
                $entityManager->persist($b);
                $block->setPosition(++$position);
                $pagesUpdated[$siteSlug][$pageSlug] = $pageSlug;
            }
            unset($data['heading']);

            $block->setData($data);
        }
    }

    $entityManager->flush();

    if ($pagesUpdated) {
        $result = array_map('array_values', $pagesUpdated);
        $message = new PsrMessage(
            'The setting "heading" was removed from block Viewer Js. New blocks "Heading" or "Html" were prepended to all blocks that had a filled heading. You may check pages for styles: {json}', // @translate
            ['json' => json_encode($result, 448)]
        );
        $messenger->addWarning($message);
        $logger->warn($message->getMessage(), $message->getContext());
    }
}
