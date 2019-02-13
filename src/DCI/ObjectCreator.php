<?php
declare(strict_types=1);

namespace App\DCI;

use ReflectionClass;
use ReflectionObject;
use ReflectionParameter;
use ReflectionMethod;

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

    /* @var ReflectionClass */
    private $parent;
    /* @var string[] */
    private $interfaces = [];
    /* @var string[] */
    private $traits = [];
    /* @var self[] */
    private static $classes = [];
    /* @var ReflectionObject */
    private $object;

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
        $hash = md5($parent . var_export($interfaces, true) . var_export($traits, true));
        if (!isset(self::$classes[$hash])) {
            self::$classes[$hash] = new self($parent, $interfaces, $traits);
        }
        return self::$classes[$hash];
    }

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
        $this->parent = new ReflectionClass($parent);
        foreach ($interfaces as $interface) {
            if (!interface_exists($interface)) {
                //todo
                throw new \Exception($interface . ' is not defined as interface.');
            }
            $this->interfaces[] = $interface;
        }
        foreach ($traits as $trait) {
            if (!trait_exists($trait)) {
                //todo
                throw new \Exception($trait . ' is not defined as trait.');
            }
            $this->traits[] = $trait;
        }
    }

    /**
     * construct
     *
     * @param array $args
     * @return object
     */
    public function construct(...$args)
    {
        if (!$this->object) {
            extract($this->constructorArguments($args));
            $this->object = new ReflectionObject(eval("return new " . $this->getTemplate() . ";"));
        }
        return $this->object->newInstanceArgs($args);
    }

    /**
     * constructorArguments
     *
     * @param array $args
     * @return array
     */
    private function constructorArguments(array $args): array
    {
        $constructor = $this->parent->getConstructor();
        if (!$constructor) {
            return [];
        }
        return array_combine(
            array_map(function (ReflectionParameter $arg) {
                return $arg->name;
            }, $constructor->getParameters()),
            $args
        );
    }

    /**
     * getTemplate
     *
     * @return string
     */
    private function getTemplate(): string
    {
        $replace = [
            '{{TRAIT}}' => self::getTraitString($this->traits),
            '{{INTERFACES}}' => self::getInterfaceString($this->interfaces),
            '{{PARENT_CLASS}}' => $this->parent->name,
            '{{ARGUMENTS}}' => self::getConstructorArgumentsString($this->parent->getConstructor()),
        ];
        return str_replace(array_keys($replace), array_values($replace), self::TEMPLATE);
    }

    /**
     * getTraitString
     *
     * @return string
     */
    private static function getTraitString(array $traits): string
    {
        $methodFullRoles = '';
        foreach ($traits as $trait) {
            $methodFullRoles .= "use {$trait};".PHP_EOL;
        }
        return $methodFullRoles;
    }

    /**
     * getInterfaceString
     *
     * @return string
     */
    private static function getInterfaceString(array $interfaces): string
    {
        return $interfaces ? 'implements ' . implode(',', $interfaces) : '';
    }

    /**
     * getConstructorArgumentsString
     *
     * @return string
     */
    private static function getConstructorArgumentsString(?ReflectionMethod $constructor): string
    {
        if (!$constructor) {
            return '';
        }
        $constructorArguments = [];
        foreach ($constructor->getParameters() as $param) {
            $constructorArguments[] = "\$" . $param->name;
        }
        return implode(',', $constructorArguments);
    }
}
