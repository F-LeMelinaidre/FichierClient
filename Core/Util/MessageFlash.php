<?php
// Utilise Bootstrap 5
namespace Core\Util;

class MessageFlash {

    private static $type;
    private static $message;

    /**
     * @param string $message
     * @param string $type type de message
     */
    public static function create(string $message, string $type = "default")
    {
        $_SESSION['flash'] = [
            'message' => $message,
            'type' => $type
        ];
    }

    public static function helper(): mixed
    {
        $flash = null;
        if (self::hasFlashMessage()) {

            switch (self::$type) {
                case "succes":
                case "valide":
                    $class = "bg-success";
                    break;

                case "erreur":
                case "invalide":
                    $class = "bg-danger";
                    break;

                case "warning":
                case "danger":
                    $class = "bg-warning";
                    break;

                default:
                    $class = "bg-info-subtle";
            }

            $flash = self::render($class, self::$message);
            self::destroy();
        }



        return $flash;
    }

    public static function hasFlashMessage(): bool
    {
        $result = isset($_SESSION['flash']);

        self::$type = ($result) ? $_SESSION['flash']['type'] : '';
        self::$message = ($result) ? $_SESSION['flash']['message'] : '';

        return $result;
    }

    private static function destroy()
    {
        unset($_SESSION['flash']);
    }

    private static function render($class, $message): string
    {
        return <<<HTML
            <div id="MessageFlash" class=" $class">
                <p>$message</p>
            </div>
            <script id="FlashScript">
                jQuery(document).ready(function($) {
                    // Supprime la div et le bloc script automatiquement
                    setTimeout(function() {
                        $("#MessageFlash").slideUp(500, function() {
                            $(this).remove();
                        });
                        $("#FlashScript").remove();
                    }, 3000);


                    // Supprime la div et le bloc script
                    $("#MessageFlash .close-btn").on("click", function() {

                        $("#MessageFlash").slideUp(500, function() {

                                $(this).remove();
                                $("#FlashScript").remove();
                        });
                    });
                });

                
            </script>
HTML;
    }
}
