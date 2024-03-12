

      function exibirDetalhesEntregador(entregador) {
    // Realize a requisição Ajax para buscar as informações do entregador
    $.ajax({
      url: "buscar_entregador_da_entrega.php",
      type: "POST",
      data: {
        entregador: entregador
      },
      success: function(response) {
        // Se a requisição for bem-sucedida, preencha os dados do entregador no modal
        var entregador = JSON.parse(response);
        $('#nomeEntregador').text(entregador.nome);
        $('#placaMoto').text(entregador.documento_moto);
        $('#num_entregas').text(entregador.num_entregas);
        $('#ranking').text(entregador.nivel_rank);
        $('#telefone').text(entregador.telefone);
        $('#imagemPerfil').attr('src', 'http://localhost/entregadores/'+ entregador.imagem_perfil);
        // Preencha outros campos do modal com as informações necessárias

        // Exiba o modal
        $('#modalEntregador').modal('show');
      },
      error: function(xhr, status, error) {
        // Em caso de erro, exiba uma mensagem de erro ou faça qualquer outra ação necessária
        alert("Ocorreu um erro ao carregar as informações do entregador.");
      }
    });
  }


  function finalizarEntrega(id) {
    if (confirm("Tem certeza de que deseja finalizar esta entrega?")) {
      // Realiza a requisição Ajax para atualizar o status da entrega
      $.ajax({
        url: "finalizar_entrega.php",
        type: "POST",
        data: {
          id: id
        },
        success: function(response) {
          // Se a requisição for bem-sucedida, atualize a página ou faça qualquer outra ação necessária
          location.reload(); // Recarrega a página para refletir as alterações
        },
        error: function(xhr, status, error) {
          // Em caso de erro, exiba uma mensagem de erro ou faça qualquer outra ação necessária
          alert("Ocorreu um erro ao finalizar a entrega.");
        }
      });
    }
  }




function atualizarHoraData() {
  // Obter a data e hora atual
  var dataHoraAtual = new Date();

  // Obter horas, minutos e segundos
  var horas = dataHoraAtual.getHours();
  var minutos = dataHoraAtual.getMinutes();
  var segundos = dataHoraAtual.getSeconds();

  // Obter a data atual
  var dia = dataHoraAtual.getDate();
  var mes = dataHoraAtual.getMonth() + 1; // Os meses são indexados de 0 a 11
  var ano = dataHoraAtual.getFullYear();

  // Formatar os valores com dois dígitos (ex: 08, 09)
  horas = formatarNumeroDoisDigitos(horas);
  minutos = formatarNumeroDoisDigitos(minutos);
  segundos = formatarNumeroDoisDigitos(segundos);
  dia = formatarNumeroDoisDigitos(dia);
  mes = formatarNumeroDoisDigitos(mes);

  // Exibir hora, minutos, segundos e data na página HTML
  document.getElementById("hora").innerHTML = horas + ":" + minutos + ":" + segundos;
  document.getElementById("data").innerHTML = dia + "/" + mes + "/" + ano;
}

function formatarNumeroDoisDigitos(numero) {
  return numero < 10 ? "0" + numero : numero.toString();
}

atualizarHoraData();
// Atualizar a hora e data a cada 1 segundo
setInterval(atualizarHoraData, 1000);


       // Obtenha uma referência para o link
    var link = document.getElementById("botaoRepasse");
    var divResultado = document.getElementById("resultadoRepasse");

    // Adicione um evento de clique ao link
    link.addEventListener("click", function(event) {
        event.preventDefault(); // Previne o comportamento padrão do link

        // Use AJAX para enviar uma solicitação para o servidor PHP
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "obterRepasse.php", true);

        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // A resposta do servidor está pronta
                var resposta = xhr.responseText;
                 if (resposta.trim() !== "") {
    divResultado.innerHTML = "" + resposta; // Define o conteúdo da div como a resposta
} else {
    divResultado.innerHTML = ""; // Define uma mensagem padrão quando não há registros
}
            }
        };

        xhr.send();
    });


    $(document).ready(function() {
    // Manipulador de envio do formulário
    $('#abrirTicketForm').submit(function(event) {
        event.preventDefault();

        var assunto = $('#assunto').val();
        var mensagem = $('#mensagem').val();
        var prioridade = $('input[name=prioridade]:checked').val();

        // Enviar solicitação AJAX para abrir um novo ticket
        $.ajax({
            url: 'ticket/abrir_ticket.php',
            type: 'POST',
            data: {
                assunto: assunto,
                mensagem: mensagem,
                prioridade: prioridade
            },
            success: function(response) {
                alert('Ticket aberto com sucesso!');
                // Redirecionar para outra página ou executar outras ações necessárias
            },
            error: function() {
                alert('Erro ao abrir o ticket.');
            }
        });
    });

    // Função para obter e exibir a lista de tickets
    function obterListaTickets() {
        $.ajax({
            url: 'ticket/obter_tickets.php',
            type: 'GET',
            success: function(response) {
                $('#listaTickets').html(response);
            },
            error: function() {
                alert('Erro ao obter a lista de tickets.');
            }
        });
    }

    // Chamar a função para obter e exibir a lista de tickets ao carregar a página
    obterListaTickets();



   function fetchNewMessages() {
  $.ajax({
    url: 'check_new_messages.php',
    type: 'GET',
    dataType: 'json',
    success: function(response) {
      if (response.success) {
        var totalMensagensNaoLidas = response.totalMensagensNaoLidas;
        if (totalMensagensNaoLidas > 0) {
          $('#mensagensNaoLidas').html("<span class='badge rounded-pill badge-center h-px-20 w-px-20 bg-label-danger'>" + totalMensagensNaoLidas + "</span>");
        } else {
          $('#mensagensNaoLidas').empty();
        }
      } else {
        console.log('Failed to fetch new messages: ' + response.error);
      }
    },
    error: function(xhr, status, error) {
      console.log('AJAX request error: ' + error);
    }
  });
}

fetchNewMessages();
setInterval(fetchNewMessages, 5000); // Chama a função a cada 5 segundos (5000 milissegundos)






});
