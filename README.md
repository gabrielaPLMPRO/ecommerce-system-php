# **Ecommerce System - Projeto de Programação de Aplicações Web II**

## **Descrição**
Este repositório contém a implementação de um sistema de pedidos para uma loja virtual como parte do trabalho da disciplina *Programação de Aplicações Web II* (ADS3006) da Universidade de Caxias do Sul. O sistema foi desenvolvido utilizando as tecnologias HTML5, CSS3, JavaScript, PHP e MySQL/PostgreSQL.

O sistema possui funcionalidades como cadastro de usuários, fornecedores e produtos, gerenciamento de estoque, realização de pedidos, e muito mais. Além disso, a aplicação conta com um layout responsivo e uma API REST para consulta de pedidos.

## **Funcionalidades**
### Parte 1:
- Cadastro de **usuários** e **login**.
- Cadastro de **fornecedores**.
- Cadastro de **produtos** (sem imagem), associando-os aos fornecedores.
- **Manutenção de estoque** de produtos com as seguintes funcionalidades:
  - Inclusão
  - Alteração
  - Exclusão
  - Consulta (por código e por nome)

### Parte 2:
- **Upload de imagens** para os produtos cadastrados.
- Inclusão de **paginação** nos cadastros de fornecedores, produtos e usuários.
- Tela de **consulta de produtos** com pesquisa e carrinho de compras para realização de pedidos.
  - **Verificação de estoque** para garantir que não há vendas de itens fora de estoque.
  - Mensagens de erro caso a quantidade solicitada ultrapasse o estoque.
  - Cálculo e exibição do **valor total do pedido** utilizando **AJAX**.
- **Consulta de pedidos** realizados pela administração da loja (Ator Interno), com funcionalidades para alteração do status do pedido.
- **Carrossel de fotos** dos itens de um pedido.
- **API REST** para consulta de pedidos, acessível por número do pedido ou nome do cliente.

## **Tecnologias Utilizadas**
- **Frontend**: HTML5, CSS3, JavaScript, AJAX (para atualizações assíncronas)
- **Backend**: PHP
- **Banco de Dados**: MySQL/PostgreSQL
- **API**: RESTful API para consulta de pedidos
- **Layout Responsivo**: Garantindo boa usabilidade tanto em dispositivos móveis quanto desktops.

## **Instruções de Instalação**
1. Clone este repositório:
   ```bash
   git clone https://github.com/username/repository-name.git
