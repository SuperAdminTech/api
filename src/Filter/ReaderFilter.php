<?php


namespace App\Filter;


use App\Annotation\ReaderAware;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ReaderFilter extends SQLFilter
{
    /** @var Reader */
    private $reader;

    /**
     * @inheritDoc
     */
    public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
    {
        if (null === $this->reader) {
            throw new \RuntimeException(sprintf('An annotation reader must be provided. Be sure to call "%s::setAnnotationReader()".', __CLASS__));
        }

        // The Doctrine filter is called for any query on any entity
        // Check if the current entity is "user aware" (marked with an annotation)
        /** @var ReaderAware $readerAware */
        $readerAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            ReaderAware::class
        );
        if (!$readerAware) {
            return '';
        }


        $fieldName = $readerAware->readerFieldName;
        try {
            // Don't worry, getParameter automatically escapes parameters
            $userId = $this->getParameter('id');
        } catch (\InvalidArgumentException $e) {
            // No user id has been defined
            return '';
        }

        if (empty($fieldName) || empty($userId)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $userId);
    }

    public function setAnnotationReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
}