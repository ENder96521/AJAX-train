<?php
$db = new PDO("pgsql:host=127.0.0.1;dbname=jq_sample;");
date_default_timezone_set('Asia/Taipei');

switch ($_GET['do']) {
    case 'select':
        $start = $_POST['start'];
        $sql = "SELECT * FROM ajax_animal LIMIT 10 OFFSET $start";
        $rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        if ($rows) {
            foreach ($rows as $row) {
                echo '
                    <tr>
                        <td>' . htmlspecialchars($row['id']) . '</td>
                        <td class="name">' . htmlspecialchars($row['name']) . '</td>
                        <td>' . htmlspecialchars($row['weight']) . '</td>
                        <td>' . htmlspecialchars($row['info']) . '</td>
                        <td>' . htmlspecialchars($row['date']) . '</td>
                        <td>
                            <button class="mdy">修改</button>
                            <button onclick="del(this)">刪除</button>
                        </td>
                    </tr>
                ';
            }
        } else {
            echo 'fail';
        }
        break;

    case 'update':
        $sql = "UPDATE ajax_animal SET name=:name, weight=:weight, info=:info, date=NOW() WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $_POST['name'],
            ':weight' => $_POST['weight'],
            ':info' => $_POST['info'],
            ':id' => $_POST['id'],
        ]);
        if ($stmt->rowCount()) {
            echo date("Y-m-d H:i:s");
        }
        break;

    case 'delete':
        $sql = "DELETE FROM ajax_animal WHERE id=:id";
        $stmt = $db->prepare($sql);
        $stmt->execute([':id' => $_POST['id']]);
        if ($stmt->rowCount()) {
            echo "deleted";
        }
        break;

    case 'insert':
        $sql = "INSERT INTO ajax_animal (name, weight, info, date) VALUES (:name, :weight, :info, NOW())";
        echo $sql;
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':name' => $_POST['name'],
            ':weight' => $_POST['weight'],
            ':info' => $_POST['info'],
        ]);
        if ($stmt->rowCount()) {
            echo "inserted";
        } 
        break;
}
?>
