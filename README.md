
# search-cep

Este projeto é uma API para consulta de múltiplos CEPs usando a API ViaCEP. Ele retorna os dados dos endereços no formato especificado e inclui uma interface web para busca de CEPs.

## Tecnologias Utilizadas

- Laravel
- Docker
- Bootstrap

## Pré-requisitos

- Docker
- Docker Compose

## Configuração do Ambiente

1. Clone o repositório:

```bash
git clone https://github.com/glaiton-silva/search-cep
```

2. Navegue até o diretório do projeto:

```bash
cd search-cep
```

3. Inicie os containers Docker:

```bash
docker-compose up -d
```

4. Acesse a aplicação no navegador:

```
http://127.0.0.1:8000
```

5. Para buscar dados diretamente usando a API, acesse:

```
http://127.0.0.1:8000/search/local/{ceps}
```

Exemplo:

```
http://127.0.0.1:8000/search/local/15870023,15870060
```

## Utilização

### API de Consulta de CEPs

Para consultar múltiplos CEPs, acesse a rota `/search/local/{ceps}` onde `{ceps}` é uma lista de CEPs separados por vírgula.

### Interface Web

A aplicação inclui uma interface web para busca de CEPs. Basta acessar a URL base da aplicação e usar o formulário de busca.

## Estrutura do Projeto

```
search-cep/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── CEPController.php
├── routes/
│   └── web.php
├── resources/
│   └── views/
│       └── cep_search.blade.php
├── docker-compose.yml
├── Dockerfile
└── README.md
```

## Licença

[MIT](https://choosealicense.com/licenses/mit/)
