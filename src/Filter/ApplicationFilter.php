<?php


namespace App\Filter;


use App\Annotation\ApplicationAware;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query\Filter\SQLFilter;

class ApplicationFilter extends SQLFilter
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
        /** @var ApplicationAware $applicationAware */
        $applicationAware = $this->reader->getClassAnnotation(
            $targetEntity->getReflectionClass(),
            ApplicationAware::class
        );
        if (!$applicationAware) {
            return '';
        }


        $fieldName = $applicationAware->applicationFieldName;
        try {
            // Don't worry, getParameter automatically escapes parameters
            $applicationId = $this->getParameter('application');
        } catch (\InvalidArgumentException $e) {
            // No user id has been defined
            return '';
        }

        if (empty($fieldName) || empty($applicationId)) {
            return '';
        }

        return sprintf('%s.%s = %s', $targetTableAlias, $fieldName, $applicationId);
    }

    public function setAnnotationReader(Reader $reader): void
    {
        $this->reader = $reader;
    }
}