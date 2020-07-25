<?php

require_once 'vendor/autoload.php';

$sqlWinners = "SELECT Lots.id lot_id, Users.id user_id, lot_name, user_name, user_email
                 FROM Lots
                      INNER JOIN Users
                              ON Users.id =
                                 (SELECT user_id
                                    FROM Rates
                                   WHERE lot_id = Lots.id
                                   ORDER BY date_rate DESC
                                   LIMIT 1)
                WHERE final_date < now()
                  AND winner IS NULL";

$winners = getResultAsArray($connect, $sqlWinners);

foreach ($winners as $winner) {
    $sqlAddWinner = "UPDATE Lots SET winner = {$winner['user_id']}
                      WHERE id = {$winner['lot_id']}";

    $result = mysqli_query($connect, $sqlAddWinner);

    if ($result) {
        $msg_content = include_template('email.php', ['winner' => $winner, 'host' => $_SERVER["HTTP_HOST"]]);

        $transport = new Swift_SmtpTransport("phpdemo.ru", 25);
        $transport->setUserName("keks@phpdemo.ru");
        $transport->setPassword("htmlacademy");
        $transport->setEncryption(null);

        $message = new Swift_Message();
        $message->setSubject("Ваша ставка победила");
        $message->setFrom(['keks@phpdemo.ru' => 'YetiCave']);
        $message->setTo([$winner['user_email'] => $winner['user_name']]);
        $message->setBody($msg_content, 'text/html');

        $mailer = new Swift_Mailer($transport);
        $result = $mailer->send($message);
    } else {
        print(mysqli_error($connect));
    }
}
