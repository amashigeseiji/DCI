<?php
declare(strict_types=1);

namespace App\DCI;

/**
 * ObjectCreator
 *
 * @final
 *
 * This class make dynamic object.
 * Object combined with interface and trait.
 *
 * usage:
 *
 * ```php
 * ObjectCreator::make(
 *     Hoge::class,
 *     [SomeInterface::class],
 *     [SomeTrait::class]
 * )->construct(new Fuga(1));
 * ```
 */
final class ObjectCreator
{
    private const TEMPLATE = <<<TEMPLATE
class({{ARGUMENTS}})
  extends {{PARENT_CLASS}}
  {{INTERFACES}}
{
    {{TRAIT}}
}
TEMPLATE;

    private $class;
    private $constructorArguments;

    /**
     * __construct
     *
     * @param string $classString
     * @param array $constructorArguments
     * @return void
     */
    private function __construct(string $classString, array $constructorArguments = [])
    {
        $this->class = $classString;
        $this->constructorArguments = $constructorArguments;
    }

    /**
     * construct
     *
     * @param array $args
     * @return object
     */
    public function construct(...$args)
    {
        extract($this->constructorArguments($args));
        eval("\$object = new {$this->class};");
        return $object;
    }

    /**
     * constructorArguments
     *
     * @param array $args
     * @return array
     */
    private function constructorArguments(array $args): array
    {
        return array_combine(
            array_map(function ($arg) {
                return ltrim($arg, '$');
            }, $this->constructorArguments),
            $args
        );
    }

    /**
     * Make instance of ObjectCreator
     * and define class dynamically.
     *
     * @param string $parent
     * @param array $interfaces
     * @param array $traits
     * @return self
     */
    public static function make(string $parent, array $interfaces = [], array $traits = []): self
    {
        $methodFullRoles = '';
        foreach ($traits as $trait) {
            $methodFullRoles .= "use {$trait};".PHP_EOL;
        }
        $methodLessRoles = $interfaces ? 'implements ' . implode(',', $interfaces) : '';

        $constructorArguments = [];
        $reflection = new \ReflectionClass($parent);
        $constructor = $reflection->getConstructor();
        if ($constructor) {
            foreach ($constructor->getParameters() as $param) {
                $constructorArguments[] = "\$" . $param->name;
            }
        }
        $args = implode(',', $constructorArguments);

        $classString = str_replace(
            ['{{TRAIT}}', '{{INTERFACES}}', '{{PARENT_CLASS}}', '{{ARGUMENTS}}'],
            [$methodFullRoles, $methodLessRoles, $parent, $args],
            self::TEMPLATE
        );

        return new self($classString, $constructorArguments);
    }
}
