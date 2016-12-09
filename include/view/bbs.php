<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>ひとこと掲示板</title>
</head>
<body>
<?php if (count($msg) > 0) { ?>
    <?php foreach ((array)$msg as $value) { ?>
    <p><?php print $value; ?></p>
    <?php } ?>
<?php } ?>
<?php if (count($err_msg) > 0) { ?>
    <?php foreach ((array)$err_msg as $value) { ?>
    <p><?php print $value; ?></p>
    <?php } ?>
<?php } ?>
    <h1>みんなの意見交換所</h1>
 
    <form method="post">
        <label>お名前:<input type="text" name="name"><br>
        発言　:<input type="text" name="comment"><br>
        <input type="submit" name="submit" value="発言する"></label>
    </form>
    
    <table>
    <caption>発言一覧</caption>
<?php foreach ($bbs_data as $read) { ?>
    <tr>
        <td><?php print $read['name']; ?></td>
        <td><?php print $read['date']; ?></td>
        <td><?php print $read['comment']; ?></td>
    </tr>
<?php } ?>
    </table>
</body>
</html>