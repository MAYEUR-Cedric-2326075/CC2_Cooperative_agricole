<?php

namespace gui;

class ViewError {
    private Layout $layout;
    private string $message;
    private string $redirect;

    public function __construct(Layout $layout, string $message, string $redirect) {
        $this->layout = $layout;
        $this->message = $message;
        $this->redirect = $redirect;
    }

    public function display(): void {
        $title = "Error";
        $connexion = "<a href='" . htmlspecialchars($this->redirect) . "'>🔙 Return</a>";

        $content = "<div style='text-align: center; margin-top: 50px;'>
                        <h2 style='color: red;'>❌ " . htmlspecialchars($this->message) . "</h2>
                        <p>Please try again or contact support.</p>
                    </div>";

        $this->layout->display($title, $connexion, $content);
    }
}
