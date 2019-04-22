<?php
/**
 * Created by PhpStorm.
 * User: linux
 * Date: 07/04/19
 * Time: 16:47
 */

namespace App\Form\DataTransformer;

use App\Entity\Tag;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;
use App\Repository\TagRepository;

class TagArrayToStringTransformer implements DataTransformerInterface
{
    private $tags;

    public function __construct(TagRepository $tagRepository) {
        $this->tags = $tagRepository;
    }

    /**
     * Transforms a value from the original representation to a transformed representation.
     *
     * This method is called on two occasions inside a form field:
     *
     * 1. When the form field is initialized with the data attached from the datasource (object or array).
     * 2. When data from a request is submitted using {@link Form::submit()} to transform the new input data
     *    back into the renderable format. For example if you have a date field and submit '2009-10-10'
     *    you might accept this value because its easily parsed, but the transformer still writes back
     *    "2009/10/10" onto the form field (for further displaying or other purposes).
     *
     * This method must be able to deal with empty values. Usually this will
     * be NULL, but depending on your implementation other empty values are
     * possible as well (such as empty strings). The reasoning behind this is
     * that value transformers must be chainable. If the transform() method
     * of the first value transformer outputs NULL, the second value transformer
     * must be able to process that value.
     *
     * By convention, transform() should return an empty string if NULL is
     * passed.
     *
     * @param mixed $value The value in the original representation
     *
     * @return mixed The value in the transformed representation
     *
     * @throws TransformationFailedException when the transformation fails
     */
    public function transform($value)
    {
        return implode(',', $value);
    }

    public function reverseTransform($string) {
        if($string === "" || $string === null) {
            return []; // devolvemos un array vacio
        }
        $names = array_filter(array_unique(array_map('trim', explode(',', $string))));
        $tags = $this->tags->findBy([
            'tag' => $names
        ]);
        $newNames = array_diff($names, $tags);
        foreach($newNames as $newName) {
            $tag = new Tag();
            $tag->setTag($newName);
            $tags[] = $tag;
        }
        return $tags;
    }
}