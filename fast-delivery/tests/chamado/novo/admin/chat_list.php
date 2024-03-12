<?php foreach ($chats as $chat): ?>
  <li>
    <a href="?chatId=<?php echo $chat['id']; ?>">
      <?php echo $chat['nome_estabelecimento']; ?>
    </a>
  </li>
<?php endforeach; ?>
