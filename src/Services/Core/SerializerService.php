<?php

/**
 * Service
 *
 * @author Lucas Simonin <lsimonin2@gmail.com>
 */

namespace App\Services\Core;

use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\PropertyInfo\Extractor\ReflectionExtractor;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ArrayDenormalizer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

/**
 * Class SerializerService
 * `
 *
 * @package App\Services\Core
 */
class SerializerService extends BaseService
{
    private $serializer;


    /**
     * SerializerService constructor.
     */
    public function __construct()
    {
        $objectNormalizer = new ObjectNormalizer(null, null, null, new ReflectionExtractor());
        $objectNormalizer->setCircularReferenceLimit(2);
        // Add Circular reference handler
        $objectNormalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $encoders = array(new JsonEncoder(), new XmlEncoder());
        $normalizers = array($objectNormalizer, new ArrayDenormalizer(), new DateTimeNormalizer());
        $this->serializer = new Serializer($normalizers, $encoders);
    }
    public function serializeObjectToJson($object)
    {
        return $this->serializer->serialize($object, 'json');
    }
    public function serializeJsonToObject($data, $objectClass)
    {
        return $this->serializer->deserialize($data, $objectClass, 'json', array(
            'allow_extra_attributes' => false,
        ));
    }
}
