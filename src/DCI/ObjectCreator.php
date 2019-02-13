<?php
declare(strict_types=1);

namespace App\DCI;

use ReflectionClass;
use ReflectionParameter;

/**
 * ObjectCreator
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
class({{ARGUMENTS}}) extends {{PARENT_CLASS}} {{INTERFACES}}
{
    {{TRAIT}}
}
TEMPLATE;

    private $parent;
    private $reflection;
    private $interfaces = [];
    private $traits = [];

    /**
     * __construct
     *
     * @param string $parent
     * @param array $interfaces
     * @param array $traits
     * @return void
     */
    private function __construct(string $parent, array $interfaces = [], array $traits = [])
    {
        $this->parent = $parent;
        foreach ($interfaces as $interface) {
            $this->actAs($interface);
        }
        foreach ($traits as $trait) {
            $this->use($trait);
        }
        $this->reflection = new ReflectionClass($parent);
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
                $this->getTraitString(),
                $this->getInterfaceString(),
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
    private function actAs(string $interface): self
    {
        if (!interface_exists($interface)) {
            //todo
            throw new \Exception($interface . ' is not defined as interface.');
        }
        $this->interfaces[] = $interface;
        return $this;
    }

    /**
     * use
     *
     * @param string $trait
     * @return self
     */
    private function use(string $trait): self
    {
        if (!trait_exists($trait)) {
            //todo
            throw new \Exception($trait . ' is not defined as trait.');
        }
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
            array_map(function (ReflectionParameter $arg) {
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
     * getTraitString
     *
     * @return string
     */
    private function getTraitString(): string
    {
        $methodFullRoles = '';
        foreach ($this->traits as $trait) {
            $methodFullRoles .= "use {$trait};".PHP_EOL;
        }
        return $methodFullRoles;
    }

    /**
     * getInterfaceString
     *
     * @return string
     */
    private function getInterfaceString(): string
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
