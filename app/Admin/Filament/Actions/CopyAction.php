<?php

namespace App\Admin\Filament\Actions;

use Filament\Forms\Components\Actions\Action;
use Webbingbrasil\FilamentCopyActions\Concerns\HasCopyable;

class CopyAction extends Action
{
    use HasCopyable {
        HasCopyable::getCopyable as getDefaultCopyable;
    }

    public function getCopyable(): ?string
    {
        if ($this->copyable === null) {
            return $this->evaluate(fn ($component) => '$wire'.$this->changeFromDottedToBracket($component->getStatePath()));
        }

        return parent::getDefaultCopyable();
    }

    public function changeFromDottedToBracket($statePath): ?string{
        if (strpos($statePath, '.') === false) {
            return $statePath;
        }

        $finalPath = "";
        foreach (explode(".", $statePath) as $key => $value) {
            if($value == "mountedTableActionsData"){
                $finalPath = $finalPath.$value;
            }else{
                $finalPath = $finalPath."['".$value."']";
            }
        }

        return $finalPath;
    }
}
