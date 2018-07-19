# Simulado ENEM - Projeto

> **Este foi meu primeiro projeto com PHP.

O projeto surgiu com a ideia de criar um sistema onde os alunos pudessem se preparar para o ENEM resolvendo questões. O sistema é voltado para estudantes, assim sendo, o sistema deveria ser acessível por dispositivos moveis uma vez que estudantes possuem uma agenda cheia de provas e atividades escolares. O sistema foi criado principalmente com a framework Bootstrap 3 de modo a permitir componentes reutilizáveis que aceleravam o processo de desenvolvimento. Utilizou-se as linguagens HTML, CSS, JavaScript e PHP para criar toda a parte de front-end e back-end. O sistema em sua fase final apresenta páginas que mostram o desempenho do aluno ao resolver questões do repositório, um ranking que classifica o aluno em relação aos demais alunos, opções de filtro e a página de simulados. Além disso, utilizou-se tecnologias que permitem comunicação em tempo real para tornar a experiência de utilização a melhor e mais precisa possível.

## Telas do sistema
<img src="https://imgur.com/WKIXLh0.png" height="250" alt="Tela de login - Desktop">
<img src="https://imgur.com/xuDbLcs.png" width="150" alt="Tela de login - Smartphone" style="display: inline-block">
<img src="https://imgur.com/rAOnL0i.png" width="150" alt="Menu de opções - Smartphone" style="display: inline-block">
<img src="https://imgur.com/GWVWMC4.png" width="150" alt="Tela de gráficos- Smartphone" style="display: inline-block">
<img src="https://imgur.com/8qr6k0t.png" height="250" alt="Tela de perfil - Desktop">


## Websockets

O projeto ainda contava com comunicação full-duplex através de Websockets. O HTTP não é um protocolo feito para esse tipo de comunicação (full-duplex), então ao utilizar o AJAX, para verificar a existência de mensagens, o polling abre e fecha uma conexão em busca de updates gerando um overhead de rede desnecessário. Em contrapartida, o WebSocket é um protocolo que não causa overheads, pois durante o handshake inicial entre o
cliente e o servidor, faz-se um upgrade do protocolo HTTP para o WebSocket, a partir dai os quadros de dados WebSocket podem ser enviados e recebidos entre o cliente e o servidor a qualquer momento.

Exemplo de adição de comentário com o WebSocket provendo conexão full-duplex:
![Exemplo de adição de comentário com o WebSocket provendo conexão full-duplex](https://imgur.com/UCj9kiP.png)

Código para propagar o novo comentário:
![Código para propagar o novo comentário.](https://imgur.com/UwubMYQ.png)

## Relatórios
Desde que o foco era um sistema leve e rápido foi adicionado técnicas como:

 - Minificação de arquivos
 - API Intersection Observer (Evita carregar imagens a menos que o usuário queira vê-las)
 - GZIP

Relação da quantidade de Kilobytes ocupada por um arquivo em
diferentes tipos de compressão:
![Gráfico de comparação](https://imgur.com/I6NCefT.png)

## Gráfico gerado pela ferramenta Audits do Google Chrome

O gráfico contempla períodos do inicio ao final do projeto, devemos dar um enfoque à performance que aumentou bastante desde sua versão inicial.

![Gráfico que mostra a melhoria do projeto](https://imgur.com/EODHrZJ.png)
