<?php
declare(strict_types=1);

namespace App\DCI;

use ReflectionClass;

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

    private $parent;
    private $reflection;
    private $interfaces = [];
    private $traits = [];
    private $constructorArguments;

    /**
     * __construct
     *
     * @param string $parent
     * @param array $methodLessRoles
     * @param array $methodFullRoles
     * @return void
     */
    private function __construct(string $parent, array $methodLessRoles = [], array $methodFullRoles = [])
    {
        $this->parent = $parent;
        $this->interfaces = $methodLessRoles;
        $this->traits = $methodFullRoles;
        $this->reflection = new \ReflectionClass($parent);
    }

    /**
     * getTemplate
     *
     * @return string
     */
    private function getTemplate(): string
    {
        return str_replace(
            [
                '{{TRAIT}}',
                '{{INTERFACES}}',
                '{{PARENT_CLASS}}',
                '{{ARGUMENTS}}'
            ],
            [
                $this->getMethodFullRolesString(),
                $this->getMethodLessRolesString(),
                $this->parent,
                $this->getConstructorArgumentsString()
            ],
            self::TEMPLATE
        );
    }

    /**
     * actAs
     *
     * @param string $interface
     * @return self
     */
    public function actAs(string $interface): self
    {
        $this->interfaces[] = $interface;
        return $this;
    }

    /**
     * use
     *
     * @param string $trait
     * @return self
     */
    public function use(string $trait): self
    {
        $this->traits[] = $trait;
        return $this;
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
        eval("\$object = new " . $this->getTemplate() . ";");
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
                return $arg->name;
            }, $this->getConstructorParameters()),
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
        return new self($parent, $interfaces, $traits);
    }

    /**
     * getMethodFullRolesString
     *
     * @return string
     */
    private function getMethodFullRolesString(): string
    {
        $methodFullRoles = '';
        foreach ($this->traits as $trait) {
            $methodFullRoles .= "use {$trait};".PHP_EOL;
        }
        return $methodFullRoles;
    }

    /**
     * getMethodLessRolesString
     *
     * @return string
     */
    private function getMethodLessRolesString(): string
    {
        return $this->interfaces ? 'implements ' . implode(',', $this->interfaces) : '';
    }

    /**
     * getConstructorArgumentsString
     *
     * @return string
     */
    private function getConstructorArgumentsString(): string
    {
        $constructorArguments = [];
        foreach ($this->getConstructorParameters() as $param) {
            $constructorArguments[] = "\$" . $param->name;
        }
        return implode(',', $constructorArguments);
    }

    /**
     * getConstructorParameters
     *
     * @return ReflectionParameter[]
     */
    private function getConstructorParameters(): array
    {
        $constructor = $this->reflection->getConstructor();
        return $constructor ? $constructor->getParameters() : [];
    }
}
