<!DOCTYPE html>
<html>
<head>
  <title>Input de 4 caixas quadradas</title>
  <style>
    .input-box {
      width: 30px;
      height: 30px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div id="input-container">
    <input type="text" class="input-box" maxlength="1">
    <input type="text" class="input-box" maxlength="1">
    <input type="text" class="input-box" maxlength="1">
    <input type="text" class="input-box" maxlength="1">
  </div>

  <input type='text' class='form-control' placeholder='insira seu identificador' autofocus type='text' id='identificador' name='identificador' required>

  <script>
    // Obtém todos os elementos de entrada
const inputBoxes = document.getElementsByClassName('input-box');
const identificadorInput = document.getElementById('identificador');

// Adiciona um evento de digitação a cada caixa de entrada
for (let i = 0; i < inputBoxes.length; i++) {
  inputBoxes[i].addEventListener('input', function() {
    // Obtém os valores dos campos de entrada
    const inputValues = Array.from(inputBoxes).map(inputBox => inputBox.value);

    // Atualiza o valor do campo "identificador" com os valores dos campos de entrada
    identificadorInput.value = inputValues.join('');
    
    // Preenche a próxima caixa de entrada se houver um valor digitado
    if (this.value.length > 0 && i < inputBoxes.length - 1) {
      inputBoxes[i + 1].focus();
    }
  });

  // Adiciona um evento de tecla pressionada (keydown) a cada caixa de entrada
  inputBoxes[i].addEventListener('keydown', function(event) {
    // Obtém o valor digitado
    const inputValue = this.value;

    // Verifica se a tecla pressionada é a tecla Backspace e a caixa de entrada está vazia
    if (event.key === 'Backspace' && inputValue.length === 0 && i > 0) {
      inputBoxes[i - 1].focus(); // Move o foco para a caixa de entrada anterior
      inputBoxes[i - 1].value = ''; // Apaga o valor da caixa de entrada anterior

      // Atualiza o valor do campo "identificador" com os valores atualizados dos campos de entrada
      const inputValues = Array.from(inputBoxes).map(inputBox => inputBox.value);
      identificadorInput.value = inputValues.join('');
    }
  });
}
  </script>
</body>
</html>
