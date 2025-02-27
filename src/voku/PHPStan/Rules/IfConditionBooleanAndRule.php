<?php

declare(strict_types=1);

namespace voku\PHPStan\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\Rule;

/**
 * @implements Rule<\PHPStan\Node\BooleanAndNode>
 */
final class IfConditionBooleanAndRule implements Rule
{

    /**
     * @var array<int, class-string>
     */
    private $classesNotInIfConditions;

    /**
     * @var null|ReflectionProvider
     */
    private $reflectionProvider;

    /**
     * @param array<int, class-string> $classesNotInIfConditions
     */
    public function __construct(array $classesNotInIfConditions, ?ReflectionProvider $reflectionProvider = null)
    {
        $this->reflectionProvider = $reflectionProvider;

        $this->classesNotInIfConditions = $classesNotInIfConditions;
    }

    public function getNodeType(): string
    {
        return \PHPStan\Node\BooleanAndNode::class;
    }

    /**
     * @param \PHPStan\Node\BooleanAndNode $node
     *
     * @return array<int, \PHPStan\Rules\RuleError>
     */
    public function processNode(Node $node, Scope $scope): array
    {
        $cond = $node->getOriginalNode();
        
        $errors = IfConditionHelper::processBooleanNodeHelper(
            $cond, 
            $scope, 
            $this->classesNotInIfConditions,
            $node,
            $this->reflectionProvider
        );

        return $errors;
    }
}
