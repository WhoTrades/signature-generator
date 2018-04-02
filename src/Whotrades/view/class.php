<?='<?php'?>
<?php
/** @var $reflection ReflectionClass */
?>

<?php if ($reflection->getNamespaceName()) {?>
namespace <?=$reflection->getNamespaceName()?>;
<?php }?>

<?=$reflection->getDocComment()?>
class <?=$reflection->getShortName()?>

{
<?php foreach ($reflection->getConstants() as $key => $constant) {?>
    const <?=$key?> = <?=$constant?>;
<?php }?>

<?php foreach ($reflection->getProperties() as $name => $property) {
    if ($property->isPrivate()) {
        continue;
    }
    echo $property->getDocComment();
    echo $property->isPublic() ? '    public ' : '    protected ';
    if ($property->isStatic()) {
        echo 'static ';
    }
    echo '$' . $property->getName();
    $default = $reflection->getDefaultProperties();
    if (isset($default[$property->getName()])) {
        echo " = " . var_export($default[$property->getName()], 1);
    }

    echo ";\n";
}?>

<?php foreach ($reflection->getMethods() as $name => $method) {
    if ($method->isPrivate()) {
        continue;
    }
    echo $method->getDocComment();

    echo $method->isPublic() ? '    public ' : '    protected ';
    if ($method->isStatic()) {
        echo 'static ';
    }
    echo 'function ' . $method->getName() . "(";


    echo implode(", ", array_map(function(ReflectionParameter $val){
        $result = '';
        if ($val->isPassedByReference()) {
            $result .= '&';
        }
        $result .= '$' . $val->getName();

        if ($val->allowsNull()) {
            $result .= " = null";
        }

        return $result;
    }, $method->getParameters()));
    echo ") {}\n\n";


}
?>
}

======================
