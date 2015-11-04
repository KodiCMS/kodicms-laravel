<?php

namespace KodiCMS\Datasource\Behavior;

use KodiCMS\Pages\Model\FrontendPage;
use KodiCMS\Pages\Behavior\BehaviorAbstract;

class Document extends BehaviorAbstract
{
    /**
     * @var null|string
     */
    protected $settingsTemplate = 'datasource::behavior.document';

    /**
     * @return array
     */
    public function routeList()
    {
        return [
            '/<id>'   => [
                'regex'  => [
                    'id' => '[0-9]+',
                ],
                'method' => 'executeById',
            ],
            '/<slug>' => [
                'regex'  => [
                    'slug' => '.*',
                ],
                'method' => 'executeBySlug',
            ],
        ];
    }

    public function executeById()
    {
        $id = $this->getRouter()->getParameter('id');

        return $this->execute($id);
    }

    public function executeBySlug()
    {
        $slug = $this->getRouter()->getParameter('slug');

        return $this->execute($slug);
    }

    /**
     * @param string $value
     *
     * @return void
     */
    private function execute($value)
    {
        if (empty($value)) {
            return;
        }

        // Производим поиск страницы которая укзана в настройках типа страницы
        if (! empty($itemPageId = $this->getSettings()->getSetting('item_page_id'))) {
            $this->page = FrontendPage::findById($itemPageId);

            return;
        }
    }
}
