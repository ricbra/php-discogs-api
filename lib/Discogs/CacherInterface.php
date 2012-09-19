<?php
namespace Discogs;

interface CacherInterface
{
    /**
     * @param string $key
     * @param string $rawData
     * @return null
     */
    public function persist($key, $rawData);

    /**
     * @param string $key
     * @return string
     */
    public function retrieve($key);

    /**
     * Whether cacher implementation initialized correctly and operational. The option required to define different
     * types of cachers as services. The problem is, for example, if project doesn't use Mongo the service
     * ricbra_cacher_mongodb won't be operational since optional document manager service won't be passes.
     *
     * @return bool
     */
    public function isOperational();
}
