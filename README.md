# Sistema de Delivery

Este é um sistema de delivery desenvolvido em PHP, que permite que restaurantes solicitem entregas e entregadores aceitem os pedidos. O sistema utiliza PHP e um banco de dados MySQL.

## Funcionalidades

- Restaurantes podem solicitar entregas.
- Entregadores podem aceitar pedidos.
- Os pedidos são enviados para um grupo do WhatsApp dos entregadores.
- Cada entregador tem um código de 4 dígitos para aceitar a entrega.
- O primeiro entregador que aceita o pedido é atribuído à entrega.
- Dashboard administrativo para restaurantes e entregadores.

## Requisitos

- PHP, MySQL e Apache.

## Configuração

1. Instale o XAMPP em seu sistema operacional.
2. Clone este repositório em sua máquina local.
3. Inicie o Apache e o MySQL usando o painel de controle do XAMPP.
4. Importe o arquivo do banco de dados fornecido (`delivery.sql`) para criar o banco de dados necessário.
5. Configure as credenciais do banco de dados no arquivo de configuração (`conexao.php`).
6. Abra o sistema em seu navegador e comece a usar!

## Estrutura do Projeto

- `index.php`: Página inicial do sistema (Landing Page).
- `/restaurantes/dashboard.php`: Dashboard para restaurantes.
- `/entregadores/dashboard.php`: Dashboard para entregadores.
- `conexao.php`: Arquivo de configuração do banco de dados.
- `delivery.sql`: Arquivo SQL para importação do banco de dados.

## Contribuindo

Contribuições são bem-vindas! Sinta-se à vontade para abrir um problema ou enviar um pull request.

## Licença

Este projeto é apenas para fins de estudo e é livre para uso. Sinta-se à vontade para modificar e utilizar o sistema de acordo com suas necessidades.


