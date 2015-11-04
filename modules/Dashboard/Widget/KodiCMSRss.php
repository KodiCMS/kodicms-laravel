<?php

namespace KodiCMS\Dashboard\Widget;

use Cache as CacheFacade;

class KodiCMSRss extends Decorator
{
    /**
     * @var array
     */
    protected $size = [
        'x'        => 5,
        'y'        => 2,
        'max_size' => [5, 10],
        'min_size' => [3, 2],
    ];

    /**
     * @var string
     */
    protected $frontendTemplate = 'dashboard::widgets.kodicms_rss.template';

    /**
     * @return array
     */
    public function prepareData()
    {
        $request = CacheFacade::remember('kodicms.rss', 20, function () {
            return file_get_contents('https://github.com/KodiCMS/kodicms-laravel/commits/dev.atom');
        });

        return [
            'feed' => $this->parseRss($request),
        ];
    }

    /**
     * @param string $rss
     * @param int    $limit
     *
     * @return array
     */
    protected function parseRss($rss, $limit = 0)
    {
        // Load the feed
        $feed = simplexml_load_string($rss, 'SimpleXMLElement', LIBXML_NOCDATA);

        // Make limit an integer
        $limit = (int) $limit;

        // Feed could not be loaded
        if ($feed === false) {
            return [];
        }

        $namespaces = $feed->getNamespaces(true);

        // Detect the feed type. RSS 1.0/2.0 and Atom 1.0 are supported.
        $feed = isset($feed->channel) ? $feed->xpath('//item') : $feed->entry;

        $i = 0;
        $items = [];

        foreach ($feed as $item) {
            if ($limit > 0 and $i++ === $limit) {
                break;
            }
            $item_fields = (array) $item;

            // get namespaced tags
            foreach ($namespaces as $ns) {
                $item_fields += (array) $item->children($ns);
            }

            $items[] = $item_fields;
        }

        return $items;
    }
}
