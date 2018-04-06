<?php

/*
 * This file is part of the Dektrium project.
 *
 * (c) Dektrium project <http://github.com/dektrium>
 *
 * For the full copyright and license information, please view the LICENSE.md
 * file that was distributed with this source code.
 */

/**
 * @var dektrium\user\models\User
 */
?>
Hello,

Your account on Nesltebaby.ru has been created.

<?php if ($token !== null): ?>
In order to complete your registration, please click the link below.

<?= $token->url ?>

If you cannot click the link, please try pasting the text into your browser.
<?php endif ?>

If you did not make this request you can ignore this email.
