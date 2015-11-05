<?php

namespace KodiCMS\Datasource;

class FieldGroupManager extends AbstractManager
{
    /**
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;

        foreach ($this->config as $type => $data) {
            if (! FieldGroupType::isValid($data)) {
                continue;
            }
            $this->types[$type] = new FieldGroupType($type, $data);
        }
    }
}
