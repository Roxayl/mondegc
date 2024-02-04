<?php

declare(strict_types=1);

namespace Roxayl\MondeGC\View\Components\Blocks;

use Illuminate\Http\Request;
use Illuminate\Support\ViewErrorBag;
use Roxayl\MondeGC\View\Components\BaseComponent;

class Flash extends BaseComponent
{
    /**
     * @param Request|null $request
     */
    public function __construct(
        private readonly ?Request $request
    ) {}

    /**
     * @inheritDoc
     */
    public function render(): string
    {
        return $this->renderSessionMessages() . $this->renderLegacyMessages();
    }

    /**
     * @return string
     */
    private function renderSessionMessages(): string
    {
        $output = '';

        if($this->request === null) {
            return $output;
        }

        // L'application gère les messages de validation via la clé "message".
        if($this->request->session()->has('message')) {
            list($type, $message) = explode('|', $this->request->session()->get('message'));
            $output .= sprintf('<div class="alert alert-%s">%s</div>', $type, $message);
        }

        // Par défaut, Laravel utilise la clé "errors" pour les erreurs, notamment de validation.
        $errors = $this->request->session()->get('errors') ?: new ViewErrorBag;
        if($errors->any()) {
            $output .= '<div class="alert alert-danger"><ul>';
            foreach ($errors->all() as $error) {
                $output .= sprintf('<li>%s</li>', $error);
            }
            $output .= '</ul></div>';
        }

        return $output;
    }

    /**
     * @return string
     */
    private function renderLegacyMessages(): string
    {
        $output = '';

        if(session_status() !== PHP_SESSION_ACTIVE) {
            return $output;
        }

        if(isset($_SESSION['errmsgs']) && count($_SESSION['errmsgs']) > 0) {
            foreach($_SESSION['errmsgs'] as $error) {
                showErrorMessage($error['err_type'], $error['msg']);
            }
            unset($_SESSION['errmsgs']);
        }

        return $output;
    }
}
