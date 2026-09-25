A pasta `controllers` está reservada para classes responsáveis pelo controle do fluxo das operações do sistema.

Neste projeto, não foram utilizados controllers separados. As responsabilidades estão organizadas entre:

* `classes/` — regras e operações dos objetos do sistema;
* `pages/` — páginas e interfaces da aplicação;
* `ajax/` — endpoints responsáveis pelas operações assíncronas com JavaScript/Fetch API.

A pasta foi mantida na estrutura do projeto para possibilitar uma futura separação das responsabilidades, caso necessário.
